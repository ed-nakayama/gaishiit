<?php

namespace App\Notifications\Comp;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\ResetPassword;

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
//        dd($notifiable);
        return (new MailMessage)
          ->subject($this->title)
          ->view(
            'mail_templates.reset_password',
            [
              'reset_url' => url('comp/password/reset', $this->token),
            ]);
    }

}
