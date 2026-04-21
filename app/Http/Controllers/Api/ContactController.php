<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\ContactReplyMail;
use App\Models\Contact;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    /**
     * Public: Store a new contact message.
     * POST /api/contact
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'    => ['required', 'string', 'max:255'],
            'email'   => ['required', 'email', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $contact = Contact::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Your message has been sent successfully. We will get back to you soon!',
            'data'    => $contact,
        ], 201);
    }

    /**
     * Admin: List all contact messages (newest first).
     * GET /api/admin/contacts
     */
    public function index(): JsonResponse
    {
        $contacts = Contact::orderByDesc('created_at')->get();

        return response()->json([
            'success' => true,
            'message' => 'Contacts retrieved successfully',
            'data'    => $contacts,
        ], 200);
    }

    /**
     * Admin: Get a single contact message.
     * GET /api/admin/contacts/{id}
     */
    public function show(string $id): JsonResponse
    {
        $contact = Contact::find($id);

        if (! $contact) {
            return response()->json([
                'success' => false,
                'message' => 'Contact message not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Contact retrieved successfully',
            'data'    => $contact,
        ], 200);
    }

    /**
     * Admin: Reply to a contact message and send email.
     * POST /api/admin/contacts/{id}/reply
     */
    public function reply(Request $request, string $id): JsonResponse
    {
        $contact = Contact::find($id);

        if (! $contact) {
            return response()->json([
                'success' => false,
                'message' => 'Contact message not found',
            ], 404);
        }

        $validated = $request->validate([
            'reply' => ['required', 'string', 'max:10000'],
        ]);

        $contact->update([
            'reply'      => $validated['reply'],
            'is_replied' => true,
        ]);

        // Send email to the user
        try {
            Mail::to($contact->email)->send(new ContactReplyMail($contact));
        } catch (\Throwable $e) {
            // Log the error but don't fail the request — the reply is already saved
            report($e);

            return response()->json([
                'success' => true,
                'message' => 'Reply saved, but email could not be sent. Check mail configuration.',
                'data'    => $contact->fresh(),
            ], 200);
        }

        return response()->json([
            'success' => true,
            'message' => 'Reply sent successfully and email delivered!',
            'data'    => $contact->fresh(),
        ], 200);
    }
}
