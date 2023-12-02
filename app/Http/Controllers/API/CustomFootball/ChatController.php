<?php

namespace App\Http\Controllers\API\CustomFootball;

use App\Http\Controllers\Controller;
use App\Http\Middleware\CustomFootball\CheckUserIsCompetitor;
use App\Http\Requests\CustomFootball\ChatRequest;
use App\Http\Resources\CustomFootball\ChatResource;
use App\Models\CustomFootball\Chat;
use App\Models\CustomFootball\Competition;
use App\Services\CustomFootball\ChatService;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function __construct()
    {
        $this->middleware(CheckUserIsCompetitor::class)->only(['store']);
    }

    public function index(Request $request, Competition $competition){
        return ChatResource::collection($competition->chats()->latest()->get());
    }

    public function store(ChatRequest $request, Competition $competition){
        $chat = Chat::forceCreate([
            'comment' => utf8_encode($request->comment),
            'competition_id' => $competition->id,
            'parent_id' => $request->input('chat', null),
            'user_id' => $request->user()->id,
        ]);

        return ChatResource::make($chat);
    }

    public function getUnreadChat(Request $request, Competition $competition) {
        $comments = ChatService::getUnreadComments($request->user(), $competition);
        return response()->json(['data' => count($comments)]);
    }

    public function markAsReadChat(Request $request, Competition $competition) {
        $comments = ChatService::markAsReadChat($request->user(), $competition);
        return ChatResource::collection($comments);
    }
}
