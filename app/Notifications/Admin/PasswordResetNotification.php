<?php

namespace App\Notifications\Admin;

use Illuminate\Bus\Queueable;
//use Illuminate\Notifications\Notification;
//use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\ResetPassword;

//class PasswordResetNotification extends Notification
class PasswordResetNotification extends ResetPassword
{
    use Queueable;

    public $token;
    protected $title = 'パスワードリセット 通知';

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token)
    {
      $this->token = $token;
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
          ->subject($this->title)
          ->view(
            'mail.html.passwordreset',
            [
              'reset_url' => url('admin/password/reset', $this->token),
            ]);
    }

}
