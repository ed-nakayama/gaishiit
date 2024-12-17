<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;


class OpenAPING extends Mailable
{
    use Queueable, SerializesModels;

    // 下記を追記
    /**
     * メール送信引数
     *
     * @var array
     */
    private $addr;
    private $msg;

    // 上記までを追記

    // 下記内容を修正
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($addr ,$msg)
    {
        $this->addr = $addr;
        $this->msg = $msg;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
		$cmd =  $this->to($this->addr)       // 送信先アドレス
    	    ->subject('【外資IT】OpenAI エラー')        // 件名
        	->text('mail_templates.open_api_ng')
        	->with([
        		'msg' => $this->msg,
        	]);       // 本文に送る値

	    return $cmd;
    }

}