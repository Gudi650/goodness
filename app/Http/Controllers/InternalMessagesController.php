<?php

namespace App\Http\Controllers;

use App\Models\InternalMessages;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Events\MessageSent;

class InternalMessagesController extends Controller
{

    //function to show the individuals to message
    public function index()
    {

        $users = User::where('company_id', Auth::user()->company_id)
            ->where('id', '!=', Auth::id())
            ->get();

        //check if the user is CEO,Admin ir accountant, if so display all users of all companies
        if ($this->isPrivilegedUser()) {
            $users = User::where('id', '!=', Auth::id())->get();
        }

        // attach last message timestamp and text between auth user and each user, then sort by latest
        $users = $users->map(function ($user) {
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
            // unread messages sent to auth user from this user
            $user->unread_count = InternalMessages::where('sender_id', $user->id)
                ->where('receiver_id', Auth::id())
                ->where('is_read', false)
                ->count();
            return $user;
        })->sortByDesc(function ($u) {
            return $u->last_message_at ?? $u->created_at ?? null;
        })->values();

        return view('messages.index', compact('users'));
    }

    //function to show message thread
    public function thread($threadId)
    {
        $users = User::where('company_id', Auth::user()->company_id)
            ->where('id', '!=', Auth::id())
            ->get();

        if ($this->isPrivilegedUser()) {
            $users = User::where('id', '!=', Auth::id())->get();
        }

        // attach last message timestamp and text, then sort users by most recent conversation
        $users = $users->map(function ($user) {
            $latest = InternalMessages::where(function ($q) use ($user) {
                $q->where('sender_id', Auth::id())->where('receiver_id', $user->id);
            })->orWhere(function ($q) use ($user) {
                $q->where('sender_id', $user->id)->where('receiver_id', Auth::id());
            })->orderBy('created_at', 'desc')->first();

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
            // unread messages sent to auth user from this user
            $user->unread_count = InternalMessages::where('sender_id', $user->id)
                ->where('receiver_id', Auth::id())
                ->where('is_read', false)
                ->count();
            return $user;
        })->sortByDesc(function ($u) {
            return $u->last_message_at ?? $u->created_at ?? null;
        })->values();

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
            ], 201);
        }

        return redirect()->back()->with('success', 'Message sent successfully!');
    }

    //check if the logged in user is CEO, admin or Accountant
    public function isPrivilegedUser()
    {
        $user = Auth::user();
        return $user && $user->role && in_array($user->role->name, ['CEO', 'Admin', 'Accountant']);
    }   
}
