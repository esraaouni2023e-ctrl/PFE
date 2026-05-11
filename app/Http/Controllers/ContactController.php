<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'sujet' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        Contact::create([
            'name' => $request->name,
            'email' => $request->email,
            'sujet' => $request->sujet,
            'message' => $request->message,
            'lire' => 0,
        ]);

        return redirect()->back()->with('success', 'Votre message a été envoyé avec succès !');
    }

    public function getContacts()
    {
        $contacts = Contact::orderBy('lire', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();
        
        $nonLusCount = Contact::nonLus()->count();

        return view('admin.contacts.index', compact('contacts', 'nonLusCount'));
    }

    public function show($id)
    {
        $contact = Contact::findOrFail($id);

        if ($contact->lire == 0) {
            $contact->update(['lire' => 1]);
        }

        $nonLusCount = Contact::nonLus()->count();

        return view('admin.contacts.show', compact('contact', 'nonLusCount'));
    }

    public function destroy(Request $request, $id)
    {
        $contact = Contact::findOrFail($id);
        $contact->delete();

        $nonLusCount = Contact::nonLus()->count();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'nonLusCount' => $nonLusCount
            ]);
        }

        return redirect()->route('admin.contacts.index')->with('success', 'Message supprimé avec succès.');
    }

    public function notificationCount()
    {
        return response()->json([
            'count' => Contact::nonLus()->count()
        ]);
    }
}
