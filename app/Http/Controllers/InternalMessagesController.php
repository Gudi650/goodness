<?php

namespace App\Http\Controllers;

use App\Models\InternalMessages;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Events\MessageSent;
use App\Events\MessageSeen;

class InternalMessagesController extends Controller
{

    private function conversationUsers()
    {
        $users = User::where('company_id', Auth::user()->company_id)
            ->where('id', '!=', Auth::id())
            ->get();

        if ($this->isPrivilegedUser()) {
            $users = User::where('id', '!=', Auth::id())->get();
        }

        return $users->map(function ($user) {
            $latest = InternalMessages::where(function ($q) use ($user) {
                    $q->where('sender_id', Auth::id())
                    ->where('receiver_id', $user->id);
                    })
                    ->orWhere(function ($q) use ($user) {
                    $q->where('sender_id', $user->id)
                    ->where('receiver_id', Auth::id());
                    })
                    ->orderBy('created_at', 'desc')
                    ->first();

            $user->last_message_at = $latest ? $latest->created_at : null;
            if ($latest) {
                if (!empty($latest->message)) {
                    $user->last_message_text = Str::limit($latest->message, 60);
                } elseif (!empty($latest->attachment_name)) {
                    $user->last_message_text = 'Attachment: ' . Str::limit($latest->attachment_name, 60);
                } else {
                    $user->last_message_text = null;
                }
            } else {
                $user->last_message_text = null;
            }

            $user->unread_count = InternalMessages::where('sender_id', $user->id)
                ->where('receiver_id', Auth::id())
                ->where('is_read', false)
                ->count();

            return $user;
        })->sortByDesc(function ($u) {
            return $u->last_message_at ?? $u->created_at ?? null;
        })->values();
    }

    //function to show the individuals to message
    public function index()
    {
        $users = $this->conversationUsers();

        return view('messages.index', compact('users'));
    }

    //function to show message thread
    public function thread($threadId)
    {
        $unseenMessages = InternalMessages::where('sender_id', $threadId)
            ->where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->get();

        foreach ($unseenMessages as $message) {
            $message->update([
                'is_read' => true,
                'delivered' => true,
                'seen' => true,
                'seen_at' => now(),
            ]);

           // event(new MessageSeen($message));
        }

        $users = $this->conversationUsers();

        $messages = InternalMessages::where(function ($q) use ($threadId) {
            $q->where('sender_id', Auth::id())->where('receiver_id', $threadId);
            })
            ->orWhere(function ($q) use ($threadId) {
            $q->where('sender_id', $threadId)->where('receiver_id', Auth::id());
            })
            ->orderBy('created_at', 'asc')
            ->get();

        $selectedThread = $threadId;

        return view('messages.index', compact('users', 'messages', 'selectedThread'));
    }

    public function pollConversations()
    {
        $users = $this->conversationUsers();

        return response()->json([
            'conversations' => $users->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'last_message_at' => optional($user->last_message_at)->toDateTimeString(),
                    'last_message_text' => $user->last_message_text,
                    'unread_count' => (int) $user->unread_count,
                ];
            })->values(),
            'conversation_count' => $users->count(),
            'unread_total' => $users->sum('unread_count'),
        ]);
    }

    /**
     * Poll the active thread for new messages.
     * This keeps the old realtime code untouched while AJAX is the active path.
     */
    public function poll(Request $request, $threadId)
    {
        $sinceId = (int) $request->query('since_id', 0);

        $messages = InternalMessages::where(function ($q) use ($threadId) {
            $q->where('sender_id', Auth::id())
              ->where('receiver_id', $threadId);
        })->orWhere(function ($q) use ($threadId) {
            $q->where('sender_id', $threadId)
              ->where('receiver_id', Auth::id());
        })->when($sinceId > 0, function ($q) use ($sinceId) {
            $q->where('id', '>', $sinceId);
        })->orderBy('id', 'asc')->get();

        foreach ($messages as $message) {
            // If the other user sent it while this thread is open, mark it seen immediately.
            if ((int) $message->sender_id === (int) $threadId && (int) $message->receiver_id === (int) Auth::id() && !$message->seen) {
                $message->update([
                    'is_read' => true,
                    'delivered' => true,
                    'seen' => true,
                    'seen_at' => now(),
                ]);
            }
        }

        return response()->json([
            'messages' => $messages->map(function ($message) {
                return [
                    'id' => $message->id,
                    'sender_id' => $message->sender_id,
                    'receiver_id' => $message->receiver_id,
                    'message' => $message->message,
                    'attachment_path' => $message->attachment_path,
                    'attachment_name' => $message->attachment_name,
                    'created_at' => optional($message->created_at)->toDateTimeString(),
                    'sender_name' => optional($message->sender)->name ?? null,
                    'delivered' => $message->delivered,
                    'seen' => $message->seen,
                    'seen_at' => optional($message->seen_at)->toDateTimeString(),
                ];
            })->values(),
            'latest_id' => $messages->isNotEmpty() ? $messages->last()->id : $sinceId,
        ]);
    }

    public function store(Request $request, $threadId)
    {
        $validated = $request->validate([
            'message' => 'nullable|string|max:5000',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,gif,txt,zip|max:5120',
        ]);

        $attachmentPath = null;
        $attachmentName = null;

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $attachmentName = $file->getClientOriginalName();
            $attachmentPath = $file->store('attachments/messages', 'public');
        }
        

        $message = InternalMessages::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $threadId,
            'message' => $validated['message'],
            'attachment_path' => $attachmentPath,
            'attachment_name' => $attachmentName,
            'company_id' => Auth::user()->company_id,
            'is_read' => false,
            'delivered' => false,
            'seen' => false,
            'seen_at' => null,
        ]);

        // broadcast the message to the receiver
        event(new MessageSent($message));

        // if the request expects JSON (AJAX), return the created message payload
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'id' => $message->id,
                'sender_id' => $message->sender_id,
                'receiver_id' => $message->receiver_id,
                'message' => $message->message,
                'attachment_path' => $message->attachment_path,
                'attachment_name' => $message->attachment_name,
                'created_at' => $message->created_at->toDateTimeString(),
                'sender_name' => optional($message->sender)->name ?? Auth::user()->name,
                'delivered' => $message->delivered,
                'seen' => $message->seen,
                'seen_at' => $message->seen_at,
            ], 201);
        }

        //

        return redirect()->back()->with('success', 'Message sent successfully!');
    }

    //check if the logged in user is CEO, admin or Accountant
    public function isPrivilegedUser()
    {
        $user = Auth::user();
        return $user && $user->role && in_array($user->role->name, ['CEO', 'Admin', 'Accountant']);
    }   
}
