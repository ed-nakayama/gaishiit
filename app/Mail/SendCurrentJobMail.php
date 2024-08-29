<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

use App\Models\User;

class SendCurrentJobMail extends Mailable
{
    use Queueable, SerializesModels;

    // 下記を追記
    /**
     * メール送信引数
     *
     * @var array
     */
    private $updateName;
    private $deleteName;

    // 上記までを追記

    // 下記内容を修正
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($updateName ,$deleteName)
    {
        $this->updateName = $updateName;
        $this->deleteName = $deleteName;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $to[] = 'rpa-result@gaishiit.com';
//        $to[] = 't.nakayama@d-ark.co.jp';

		return $this->to($to)       // 送信先アドレス
    	    ->subject('【外資IT】更新／削除ジョブ一覧')        // 件名
        	->text('mail_templates.send_current_job') // 本文
			->attachFromStorage($this->updateName) // 添付ファイル
			->attachFromStorage($this->deleteName); // 添付ファイル

    }

}