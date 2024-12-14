<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class TaskCommented extends Notification
{
    use Queueable;

    protected $task;


    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'sender' => auth()->user()->name,
            'subject' => "Получен комментарий по задаче: '" . 
                     Str::limit(strip_tags($this->task->description), 75) . "'",
            'link' => route('taskCard', ['task' => $this->task, 'page' => 'messages'])
        ];
    }


    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'sender' => auth()->user()->name,
            'subject' => "Получен комментарий по задаче: '" . 
                     Str::limit(strip_tags($this->task->description), 75) . "'",
            'link' => route('taskCard', ['task' => $this->task, 'page' => 'messages'])
        ];
    }
}
