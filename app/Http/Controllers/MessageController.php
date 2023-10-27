<?php

namespace App\Http\Controllers;

use App\Events\StoreMessageEvent;
use App\Http\Requests\Message\StoreRequest;
use App\Http\Resources\Message\MessageResource;
use App\Models\Message;
use Inertia\Response;
use Inertia\ResponseFactory;
use function inertia;

class MessageController extends Controller
{
    public function index(): Response|ResponseFactory
    {
        $messages = Message::latest()->get();
        $messages = MessageResource::collection($messages)->resolve();
        return inertia('Message/Index', compact('messages'));
    }

    public function store(StoreRequest $request): array
    {
        $data = $request->validated();
        $message = Message::create($data);

        // Send message with Websocket to others
        broadcast(new StoreMessageEvent($message))->toOthers();

        // Show message on my front-end
        return MessageResource::make($message)->resolve();
    }
}
