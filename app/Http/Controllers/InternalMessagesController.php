<?php

namespace App\Http\Controllers;

use App\Models\InternalMessages;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        //dd($users);
        
        return view('messages.index', compact('users'));
    }

    //function to show message thread
    public function thread($threadId)
    {
        $messages = InternalMessages::where('thread_id', '=', $threadId, 'and')
            ->where('company_id', Auth::user()->company_id)
            ->orderBy('created_at', 'asc')
            ->get();
        return view('messages.thread', compact('messages', 'threadId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:5000',
            'receiver_id' => 'required|integer|exists:users,id',
            'attachment_path' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png,gif|max:5120',
        ]);

        $attachmentPath = null;
        $attachmentName = null;

        if ($request->hasFile('attachment_path')) {
            $file = $request->file('attachment_path');
            $attachmentName = $file->getClientOriginalName();
            $attachmentPath = $file->store('attachments/messages', 'public');
        }

        InternalMessages::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $validated['receiver_id'],
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
