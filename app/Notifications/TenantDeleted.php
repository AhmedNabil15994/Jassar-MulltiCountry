<?php

namespace App\Notifications;

use App\Tenancy\Models\Tenant;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TenantDeleted extends Notification
{
    use Queueable;

    public $tenant;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(object $tenant)
    {
        $this->tenant = $tenant;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage())
            ->subject("Tenant Deleted: {$this->tenant->name}")
            ->greeting('Well Done!')
            ->line("The tenant named ({$this->tenant->name}) has been deleted successfully.")
            // ->action($this->tenant->name, url('https://' . $this->tenant->subdomain . '.toucart.com'))
            ->line('Thank you!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
