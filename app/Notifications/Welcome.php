<?php

namespace App\Notifications;

use App\Tenancy\Models\Tenant;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class Welcome extends Notification
{
    use Queueable;

    public $tenant;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Tenant $tenant)
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
        $domain_url = url('https://' . $this->tenant->subdomain . '.toucart.com');
        $dashboard_url = url('https://' . $this->tenant->subdomain . '.toucart.com/ar/dashboard');
        // $domain_url = '<a href="' . url('https://' . $this->tenant->subdomain . '.toucart.com') . '">' . url('https://' . $this->tenant->subdomain . '.toucart.com') . '</a>';
        // $dashboard_url = '<a href="' . url('https://' . $this->tenant->subdomain . '.toucart.com/ar/dashboard') . '">' . url('https://' . $this->tenant->subdomain . '.toucart.com/ar/dashboard') . '</a>';

        return (new MailMessage())
            ->subject("تم إنشاء متجرك الجديد: {$this->tenant->name}")

            // ->greeting($this->rtlLine('أهلا!'))
            ->line($this->rtlLine('<b>أهلا!</b>'))
            ->line($this->rtlLine("تم إنشاء متجرك ({$this->tenant->name}) علي منصة توكان."))

            ->line($this->rtlLine('يمكنك الإطلاع عليه من هذا الرابط:'))
            // ->line(new HtmlString($domain_url))
            ->line("[{$domain_url}]({$domain_url})")
            ->action($this->tenant->name, $domain_url)

            ->line($this->rtlLine('لوحة التحكم:'))
            ->line("[{$dashboard_url}]({$dashboard_url})")
            // ->line(url('https://' . $this->tenant->subdomain . '.toucart.com/ar/dashboard'))

            ->line(new HtmlString('<br>'))
            ->line($this->rtlLine('البريد الالكتروني: ' . $this->tenant->email))

            ->line(new HtmlString('<br>'))
            ->line($this->rtlLine('شكرا لإستخدامك متجر توكان.'))

            ->salutation($this->rtlLine(config('app.name')));
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

    protected function rtlLine(string $line)
    {
        return new HtmlString('<div dir="rtl" style="direction: rtl;">' . $line . '</div>');
    }
}
