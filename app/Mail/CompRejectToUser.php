<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CompRejectToUser extends Mailable
{
    use Queueable, SerializesModels;

    // 下記を追記
    /**
     * メール送信引数
     *
     * @var array
     */
    private $user;
    private $interview;
    // 上記までを追記

    // 下記内容を修正
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $interview)
    {
        $this->user = $user;
        $this->interview = $interview;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
	    return $this->to($this->user->email)       // 送信先アドレス
    	    ->subject('【ガイシIT】お申込みお断りのお知らせ')        // 件名
        	->text('mail_templates.comp_reject_to_user') // 本文
        	->with(['interview' => $this->interview,
		        	'user' => $this->user,
        		]);       // 本文に送る値
    }



}