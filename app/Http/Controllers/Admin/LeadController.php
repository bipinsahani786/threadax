<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactLead;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function index(Request $request)
    {
        $query = ContactLead::query();

        // 1. Text Search (Name, Email, Phone, Order Number, Message)
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('order_number', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        // 2. Status Filter
        if ($request->filled('status') && in_array($request->status, ['new', 'in_progress', 'resolved'])) {
            $query->where('status', $request->status);
        } elseif ($request->status === 'overdue') {
            $query->whereNotNull('follow_up_date')
                  ->where('follow_up_date', '<=', today())
                  ->where('status', '!=', 'resolved');
        }

        // 3. Custom Date Range Filters
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // 4. Quick Date Presets
        if ($request->filled('preset')) {
            if ($request->preset === 'today') {
                $query->whereDate('created_at', today());
            } elseif ($request->preset === '7_days') {
                $query->where('created_at', '>=', now()->subDays(7));
            } elseif ($request->preset === '30_days') {
                $query->where('created_at', '>=', now()->subDays(30));
            }
        }

        // 5. Analytics Stats & Count Summaries
        $stats = [
            'total'       => ContactLead::count(),
            'new'         => ContactLead::where('status', 'new')->count(),
            'in_progress' => ContactLead::where('status', 'in_progress')->count(),
            'resolved'    => ContactLead::where('status', 'resolved')->count(),
            'overdue'     => ContactLead::whereNotNull('follow_up_date')->where('follow_up_date', '<=', today())->where('status', '!=', 'resolved')->count(),
            'today'       => ContactLead::whereDate('created_at', today())->count(),
        ];

        $leads = $query->latest()->paginate(12)->withQueryString();

        return view('admin.pages.leads.index', compact('leads', 'stats'));
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

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Lead updated successfully!']);
        }

        return back()->with('success', 'Lead updated successfully!');
    }

    public function sendReply(Request $request, ContactLead $lead)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'reply_message' => 'required|string|max:5000',
            'mark_status' => 'nullable|in:in_progress,resolved'
        ]);

        // Send Email via Mail
        try {
            \Illuminate\Support\Facades\Mail::raw($request->reply_message, function ($message) use ($lead, $request) {
                $message->to($lead->email, $lead->first_name . ' ' . $lead->last_name)
                        ->subject($request->subject);
            });
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning("Could not send lead email reply: " . $e->getMessage());
        }

        // Append log to notes and update status
        $timestamp = now()->format('M d, Y h:i A');
        $replyLog = "\n\n[Email Reply Sent on {$timestamp}]\nSubject: {$request->subject}\n{$request->reply_message}";
        $updatedNotes = ($lead->notes ?? '') . $replyLog;

        $updateData = ['notes' => trim($updatedNotes)];
        if ($request->filled('mark_status')) {
            $updateData['status'] = $request->mark_status;
        } else {
            $updateData['status'] = 'in_progress';
        }

        $lead->update($updateData);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true, 
                'message' => 'Email reply successfully sent to ' . $lead->email . '!'
            ]);
        }

        return back()->with('success', 'Email reply successfully sent to ' . $lead->email . '!');
    }

    public function destroy(ContactLead $lead)
    {
        $lead->delete();
        return redirect()->route('admin.leads.index')->with('success', 'Lead deleted successfully!');
    }
}
