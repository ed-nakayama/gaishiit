<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;


class JobClose extends Mailable
{
    use Queueable, SerializesModels;

    // 下記を追記
    /**
     * メール送信引数
     *
     * @var array
     */
    private $addr;
    private $close_log;

    // 上記までを追記

    // 下記内容を修正
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($addr ,$close_log)
    {
        $this->addr = $addr;
        $this->close_log = $close_log;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
	    return $this->to($this->addr)       // 送信先アドレス
    	    ->subject('【ガイシIT】自動ジョブ非公開設定')        // 件名
        	->text('mail_templates.close_log')
        	->attachFromStorage($this->close_log); // 添付ファイル
    }

}