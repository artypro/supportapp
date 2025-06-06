<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubmitTicketRequest;
use App\Models\Ticket;
use App\Models\Category;
use App\Models\TicketMessage;
use App\Notifications\TicketSubmittedSlack;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Auth;

class SupportController extends Controller
{
    public function showForm()
    {
        $categories = Category::all();

        return view('support.form', compact('categories'));
    }

    public function submitTicket(SubmitTicketRequest $request)
    {
        $validated = $request->validated();

        $ticket = Ticket::create([
            'user_id' => Auth::id(),
            'channel' => Ticket::CHANNEL_WEB,
            'category_id' => $validated['category_id'],
            'subject' => $validated['subject'],
            'status' => Ticket::STATUS_NEW,
        ]);

        // Create initial TicketMessage
        TicketMessage::create([
            'ticket_id' => $ticket->id,
            'sender_id' => Auth::id(),
            'text' => $validated['message'] ?? $validated['subject'], // fallback if no message field
        ]);

        // Send Slack notification
        Notification::route('slack', config('services.slack.webhook_url'))
            ->notify(new TicketSubmittedSlack($ticket));

        return redirect()->back()->with('success', 'Ticket created successfully: AT-Ticket-'.str_pad($ticket->id, 5, '0', STR_PAD_LEFT));
    }
}
