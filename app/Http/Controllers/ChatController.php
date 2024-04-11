<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Http\Requests\StoreMessageRequest;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ChatController extends Controller
{
    public function index(Request $request): \Inertia\Response
    {
        return Inertia::render('Chats/Index', [
            'users' => User::getUsersWithLatestMessage(),
        ]);
    }

    public function store(StoreMessageRequest $request): \Illuminate\Http\RedirectResponse
    {
        $message = Message::create([
            'receiver_id' => $request->input('receiver_id'),
            'sender_id' => $request->user()->id,
            'body' => $request->input('message'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        MessageSent::dispatch($message);

        return back();
    }

    public function getMessages(Request $request, ?int $id): \Illuminate\Http\JsonResponse
    {
        return response()->json(Message::getAllMessages($id, $request->user()->id));
    }
}
