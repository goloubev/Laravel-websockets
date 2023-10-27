<?php

namespace App\Http\Controllers;

use App\Events\SendLikeEvent;
use App\Http\Requests\User\SendLikeRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Inertia\Response;
use Inertia\ResponseFactory;

class UserController extends Controller
{
    public function show(User $user): Response|ResponseFactory
    {
        return inertia('User/Show', compact('user'));
    }

    public function sendLike(User $user, SendLikeRequest $request): JsonResponse
    {
        $data = $request->validated();
        $likeStr = 'Your LIKE from user with ID: ' . $data['from_id'];

        // Send like with Websocket to user
        broadcast(new SendLikeEvent($likeStr, $user->id))->toOthers();

        return \response()->json([
            'like_str' => $likeStr
        ]);
    }
}
