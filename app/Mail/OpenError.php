<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;


class OpenError extends Mailable
{
    use Queueable, SerializesModels;

    // 下記を追記
    /**
     * メール送信引数
     *
     * @var array
     */
    private $addr;
    private $file;

    // 上記までを追記

    // 下記内容を修正
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($addr ,$file)
    {
        $this->addr = $addr;
        $this->file = $file;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
		$cmd =  $this->to($this->addr)       // 送信先アドレス
    	    ->subject('【ガイシIT】一般情報なし：エージェントポータルJob')        // 件名
        	->text('mail_templates.open_error');

        $cmd = $cmd->attachFromStorage($this->file); // 添付ファイル

	    return $cmd;
    }

}