<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RequisitionCreated extends Notification
{
    use Queueable;

    protected $requisition; // 👈 define requisition property

    /**
     * Create a new notification instance.
     */
    public function __construct($requisition)
    {
        $this->requisition = $requisition; // 👈 assign requisition
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        // if you want mail + database:
         return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Requisition Created')
            ->line('Requisition #'.$this->requisition->id.' has been created.')
            ->action('View Requisition', url('/requisitions/'.$this->requisition->id))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array/database representation of the notification.
     */
    public function toDatabase($notifiable)
    {
        return [
            'title' => 'New Requisition Created',
            'message' => 'Requisition #'.$this->requisition->requisition_no.' created by '.$this->requisition->creator->name,
            'requisition_id' => $this->requisition->id
        ];
    }
}
