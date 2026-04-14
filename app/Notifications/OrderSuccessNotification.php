<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class OrderSuccessNotification extends Notification
{
    use Queueable;

    private $orderInfo;

    public function __construct($orderInfo)
    {
        $this->orderInfo = $orderInfo;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        // Thêm thông tin user và ngày tháng vào orderInfo
        $this->orderInfo['user_name'] = $notifiable->name;
        $this->orderInfo['order_date'] = now()->format('d/m/Y H:i:s');
        
        $paymentMethod = '';
        if (isset($this->orderInfo['payment_method'])) {
            $paymentMethod = $this->orderInfo['payment_method'] == 1 
                ? 'Tiền mặt' 
                : 'Chuyển khoản ngân hàng';
        } else {
            $paymentMethod = 'Tiền mặt';
        }
        $this->orderInfo['payment_method'] = $paymentMethod;

        return (new MailMessage)
            ->subject('Xác nhận đơn hàng #' . $this->orderInfo['order_id'])
            ->view('email_template.order_success', [
                'orderInfo' => $this->orderInfo
            ]);
    }
}