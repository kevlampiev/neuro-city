<?php

namespace App\Http\Controllers\Task;

use App\Dataservices\Task\MessageDataservice;
use App\Http\Controllers\Controller;
use App\Http\Requests\Task\MessageRequest;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\TaskCommented;

class MessageController extends Controller
{
    public static function createTaskMessage(Request $request)
    {

    }

    public function createReply(Request $request, Message $message)
    {
        if (url()->previous() !== url()->current()) session(['previous_url' => url()->previous()]);
        $message = MessageDataservice::createReply($request, $message);
        return view('tasks.messages.reply-edit',
            ['message' => $message]);
    }

    public function store(MessageRequest $request, Message $message): RedirectResponse
    {
        $message = MessageDataservice::store($request);
        $task = $message->root_task;
        foreach ($task->getAllInterestedUsers() as $el) {
            if ($el != Auth::user()->id) User::find($el)->notify(new TaskCommented($task));
        }
        
        $route = session('previous_url');
        return redirect()->to($route);
    }

    public function edit(Request $request, Message $message)
    {
        if (url()->previous() !== url()->current()) session(['previous_url' => url()->previous()]);
        MessageDataservice::edit($request, $message);
        if ($message->reply_to_message_if) {
            return view('tasks.messages.reply-edit',
                MessageDataservice::provideEditor($message));
        } else {
            return view('tasks.messages.message-edit',
                MessageDataservice::provideEditor($message));
        }

    }

    public function update(MessageRequest $request, Message $message): RedirectResponse
    {
        MessageDataservice::update($request, $message);
        $route = session('previous_url');
        return redirect()->to($route);
    }


    public function delete(Message $message): RedirectResponse
    {
        MessageDataservice::delete($message);
        return redirect()->back();
    }
}
