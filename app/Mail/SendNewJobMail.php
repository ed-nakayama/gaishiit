<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

use App\Models\User;

class SendNewJobMail extends Mailable
{
    use Queueable, SerializesModels;

    // 下記を追記
    /**
     * メール送信引数
     *
     * @var array
     */
    private $fileName;
    // 上記までを追記

    // 下記内容を修正
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($fileName)
    {
        $this->fileName = $fileName;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $to[] = 'm.kawata@d-ark.co.jp';
//        $to[] = 't.nakayama@d-ark.co.jp';

		return $this->to($to)       // 送信先アドレス
    	    ->subject('【ガイシIT】新規ジョブ一覧')        // 件名
        	->text('mail_templates.send_new_job') // 本文
			->attachFromStorage($this->fileName); // 添付ファイル

    }

}