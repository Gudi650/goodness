<?php

namespace App\Http\Controllers;

use App\Models\InternalMessages;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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
                $q->where('sender_id', Auth::id())->where('receiver_id', $user->id);
            })->orWhere(function ($q) use ($user) {
                $q->where('sender_id', $user->id)->where('receiver_id', Auth::id());
            })->orderBy('created_at', 'desc')->first();

            $user->last_message_at = $latest ? $latest->created_at : null;
            $user->last_message_text = $latest ? Str::limit($latest->message, 60) : null;
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
            $user->last_message_text = $latest ? Str::limit($latest->message, 60) : null;
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
        

        InternalMessages::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $threadId,
            'message' => $validated['message'],
            'attachment_path' => $attachmentPath,
            'attachment_name' => $attachmentName,
            'company_id' => Auth::user()->company_id,
            'is_read' => false,
        ]);

        return redirect()->back()->with('success', 'Message sent successfully!');
    }

    //check if the logged in user is CEO, admin or Accountant
    public function isPrivilegedUser()
    {
        $user = Auth::user();
        return $user && $user->role && in_array($user->role->name, ['CEO', 'Admin', 'Accountant']);
    }   
}
