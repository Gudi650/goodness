<?php

namespace App\Http\Controllers;

use App\Models\InternalMessages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InternalMessagesController extends Controller
{
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
}
