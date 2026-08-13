<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactLead;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function index()
    {
        $leads = ContactLead::latest()->paginate(15);
        return view('admin.pages.leads.index', compact('leads'));
    }

    public function show(ContactLead $lead)
    {
        return view('admin.pages.leads.show', compact('lead'));
    }

    public function update(Request $request, ContactLead $lead)
    {
        $validated = $request->validate([
            'status' => 'required|in:new,in_progress,resolved',
            'follow_up_date' => 'nullable|date',
            'notes' => 'nullable|string'
        ]);

        $lead->update($validated);

        return back()->with('success', 'Lead updated successfully!');
    }

    public function destroy(ContactLead $lead)
    {
        $lead->delete();
        return redirect()->route('admin.leads.index')->with('success', 'Lead deleted successfully!');
    }
}
