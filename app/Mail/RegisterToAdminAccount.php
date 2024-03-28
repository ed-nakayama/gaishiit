<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

use App\Models\Admin;

class RegisterToAdminAccount extends Mailable
{
    use Queueable, SerializesModels;

    // 下記を追記
    /**
     * メール送信引数
     *
     * @var array
     */
    private $admin;
    // 上記までを追記

    // 下記内容を修正
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Admin $admin)
    {
//        $this->admin = new Admin();
        $this->admin = $admin;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
	    return $this->to($this->admin->email)       // 送信先アドレス
    	    ->subject('【ガイシIT】登録完了のお知らせ')        // 件名
        	->text('mail_templates.register_to_admin_account') // 本文
        	->with(['admin' => $this->admin]);       // 本文に送る値
    }

}