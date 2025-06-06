<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubmitTicketRequest;
use App\Models\Category;
use App\Models\Ticket;
use App\Services\TicketService;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function create()
    {
        $categories = Category::all();
        return view('support.form', compact('categories'));
    }

    public function store(SubmitTicketRequest $request, TicketService $service)
    {
        $validated = $request->validated();
        $ticket = $service->createFromWebForm($validated);
        return redirect()->back()->with('success', 'Ticket created successfully: AT-Ticket-'.str_pad($ticket->id, 5, '0', STR_PAD_LEFT));
    }

    public function index()
    {
        $tickets = Ticket::with('category')->where('user_id', Auth::id())->orderByDesc('created_at')->get();
        return view('support.tickets', compact('tickets'));
    }
}
