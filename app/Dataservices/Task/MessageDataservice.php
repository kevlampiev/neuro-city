<?php


namespace App\DataServices\Task;


use App\Http\Requests\Task\MessageRequest;
use App\Models\Message;
use Error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageDataservice
{
    public static function provideData(): array
    {
        return [];
    }

    public static function provideEditor(Message $message): array
    {
        return ['message' => $message,
            'task' => $message->task];
    }

    public static function createReply(Request $request, Message $message): Message
    {
        $m = new Message();
        $m->reply_to_message_id = $message->id;
        $m->task_id = $message->task_id;
        $m->user_id = Auth::user()->id;
        if (!empty($request->old())) $m->fill($request->old());
        return $m;
    }

    public static function saveChanges(MessageRequest $request, Message $message): Message
    {
        $message->fill($request->all());
        $message->user_id = Auth::user()->id;
        if ($message->id) $message->updated_at = now();
        else $message->created_at = now();
        $message->save();
        return $message;
    }

    public static function store(MessageRequest $request): Message
    {
        try {
            $message = new Message();
            $message = self::saveChanges($request, $message);
            session()->flash('message', 'Добавлено новое сообщение');
            return $message;
        } catch (Error $err) {
            session()->flash('error', 'Не удалось добавить новое сообщение');
            return null;
        }

    }


    public static function edit(Request $request, Message $message)
    {
        if (!empty($request->old())) $message->fill($request->old());
    }

    public static function update(MessageRequest $request, Message $message)
    {
        try {
            self::saveChanges($request, $message);
            session()->flash('message', 'Сообщение обновлено');
        } catch (Error $err) {
            session()->flash('error', 'Не удалось обновить сообщение');
        }
    }

    public static function delete(Message $message)
    {
        try {
            if (count($message->replies) == 0) {
                $message->delete();
                session()->flash('message', 'Сообщение удалено');
            } else {
                session()->flash('error', 'Невозможно удалить сообщения, на которые есть ответы');
            }
        } catch (Error $err) {
            session()->flash('error', 'Не удалось удалить сообщение');
        }
    }
}
