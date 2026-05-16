<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactSubmissionRequest;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;

class ContactController extends Controller
{
    public function store(ContactSubmissionRequest $request): RedirectResponse
    {
        ContactMessage::query()->create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'subject' => $request->input('subject'),
            'message' => $request->input('message'),
            'ip_address' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 1000),
            'status' => 'new',
        ]);

        return back()
            ->with('contact_success', 'Pesan Anda sudah terkirim. Tim kami akan meninjaunya secepat mungkin.')
            ->withFragment('contact-form');
    }
}
