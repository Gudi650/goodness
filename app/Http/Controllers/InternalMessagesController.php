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
        $users = User::where('company_id', Auth::user()->company_id)
            ->where('id', '!=', Auth::id())
            ->get();

        if ($this->isPrivilegedUser()) {
            $users = User::where('id', '!=', Auth::id())->get();
        }

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
            'message' => 'required|string|max:5000',
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
