<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Pusher\Pusher;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index()
    {
        $users = null;

        if (auth()->user()->is_admin) {
            $users = DB::select("
                SELECT users.id, users.name, users.email, count(is_read) as unread
                FROM users LEFT JOIN messages ON users.id = messages.from AND is_read = 0
                AND messages.to = " . auth()->user()->id  . "
                WHERE users.id != " . auth()->user()->id  . "
                GROUP BY users.id, users.name, users.email
            ");
        } else {
            $users = DB::select("
                SELECT users.id, users.name, users.email, count(is_read) as unread
                FROM users LEFT JOIN messages ON users.id = messages.from AND is_read = 0
                AND messages.to = " . auth()->user()->id  . "
                WHERE users.id != " . auth()->user()->id  . "
                AND users.is_admin = 1
                GROUP BY users.id, users.name, users.email
            ");
        }

        return view('chat.chat', ['users' => $users]);
    }

    public function get_messages(Request $request)
    {
        $userId = $request->input('receiverId');
        $myId = auth()->user()->id;

        $userNameTo = User::where('id', $userId)->first()->name;

        Message::where(['from' => $userId, 'to' => $myId])->update([
            'is_read' => true
        ]);

        $messages = Message::where(function ($query) use ($userId, $myId) {
            $query->where('from', $myId)->where('to', $userId);
        })->orWhere(function ($query) use ($userId, $myId) {
            $query->where('from', $userId)->where('to', $myId);
        })->get();

        return view('chat.messages', ['messages' => $messages, 'userNameTo' => $userNameTo]);
    }

    public function send_message(Request $request)
    {
        $from = auth()->user()->id;
        $to = intval($request->input('receiverId'));
        $sendMessage = $request->input('message');

        $message = new Message;
        $message->from = $from;
        $message->to = $to;
        $message->message = $sendMessage;
        $message->is_read = false;
        $message->save();

        $options = array(
            'cluster' => 'eu',
            'useTLS' => true
        );

        $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            $options
        );

        $data = ['from' => $from, 'to' => $to];
        $pusher->trigger('my-channel', 'my-event', $data);
    }
}
