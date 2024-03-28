<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

use App\Models\Admin;
use App\Models\User;

class FaqToUser extends Mailable
{
    use Queueable, SerializesModels;

    // 下記を追記
    /**
     * メール送信引数
     *
     * @var array
     */
    private $user_name;
    private $email;
    private $content;
    // 上記までを追記

    // 下記内容を修正
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user_name, $email, $content)
    {
        $this->user_name = $user_name;
        $this->email = $email;
        $this->content = $content;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
/*
		$admins = Admin::where('eval_priv','1')
			->get();

		$cc = array();

		foreach ( $admins as $ad ) {
		$cc[] = $ad['email'];
		}
*/
	    return $this->to($this->email)       // 送信先アドレス
//			->cc($cc)
			->subject('【ガイシIT】お問合せ登録のお知らせ')        // 件名
			->text('mail_templates.faq_to_user') // 本文
			->with([
				'user_name' => $this->user_name,
				'email'     => $this->email,
				'content'   => $this->content,
			]);       // 本文に送る値
    }

}