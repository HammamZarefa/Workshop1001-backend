<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Order;

class OrderStatusChanged extends Notification
{
    use Queueable;

    protected Order $order;
    protected string $old;
    protected string $new;

    public function __construct(Order $order, string $old, string $new)
    {
        $this->order = $order;
        $this->old = $old;
        $this->new = $new;
    }

    public function via($notifiable)
    {
        return ['mail', 'database']; 
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject("Order #{$this->order->id} status updated")
            ->greeting("Hello {$notifiable->first_name}")
            ->line("The status of your order #{$this->order->id} changed from {$this->old} to {$this->new}.")
            ->action('View order', url(route('admin.orders.show', $this->order->id)))
            ->line('Thank you for your purchase!');
    }

    public function toArray($notifiable)
    {
        return [
            'order_id' => $this->order->id,
            'old_status' => $this->old,
            'new_status' => $this->new,
        ];
    }
}
