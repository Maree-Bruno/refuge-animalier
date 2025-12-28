<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class ContactMessageController extends Controller
{
    public function index(Request $request)
    {
        $query = ContactMessage::query();

        if ($request->filled('message_search')) {
            $search = $request->message_search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%")
                    ->orWhere('phone', 'like', "%$search%")
                    ->orWhere('subject', 'like', "%$search%")
                    ->orWhere('content', 'like', "%$search%");
            });
        }

        if ($request->filled('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('orderby')) {
            $direction = $request->input('dir', 'asc');
            $orderBy = $request->orderby;

            $query->orderBy($orderBy, $direction);
        } else {
            $query->latest('send_date');
        }

        $messages = $query->paginate(10)->withQueryString();

        return Inertia::render('ContactMessageIndexView', [
            'title' => 'Messages de contact',
            'messages' => $messages,
            'filters' => $request->only(['message_search', 'orderby', 'dir', 'status', 'type']),
        ]);
    }

    public function submitContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'message' => 'required|string',
        ]);


        $contact = ContactMessage::create([
            'type' => ContactMessage::TYPE_CONTACT,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'message' => $request->message,
            'subject' => 'Demande d\'information',
            'status' => ContactMessage::STATUS_NEW,
            'send_date' => now(),
        ]);


        return back()->with('success', __('contact.request_sent'));
    }
    public function submitVolunteer(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string|max:255',
            'number' => 'nullable|string|max:10',
            'cp' => 'nullable|string|max:10',
            'city' => 'nullable|string|max:255',
            'message' => 'required|string',
        ]);

        $volunteer = ContactMessage::create([
            'type' => ContactMessage::TYPE_VOLUNTEER,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'number' => $request->number,
            'cp' => $request->cp,
            'city' => $request->city,
            'message' => $request->message,
            'subject' => 'Demande de volontariat',
            'status' => ContactMessage::STATUS_NEW,
            'send_date' => now(),
        ]);

        return back();
    }

    public function update(Request $request, $id)
    {
        $message = ContactMessage::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:nouveau,lu,archivé',
        ]);

        $message->update([
            'status' => $validated['status'],
        ]);

        return back();
    }

    public function destroy($id)
    {
        $message = ContactMessage::findOrFail($id);
        $message->update([
            'status' => 'archivé',
        ]);

        return back();
    }
}
