<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

use App\Models\Admin;

class RegisterToAdmin extends Mailable
{
    use Queueable, SerializesModels;

    // 下記を追記
    /**
     * メール送信引数
     *
     * @var array
     */

    // 下記内容を修正
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $admins = Admin::where('aprove_priv','1')
        	->get();

        $to = array();

        foreach ( $admins as $ad ) {
            $to[] = $ad['email'];
		}

	    return $this->to($to)       // 送信先アドレス
    	    ->subject('【ガイシIT】候補者新規登録のお知らせ')        // 件名
        	->text('mail_templates.register_to_admin'); // 本文
    }

}