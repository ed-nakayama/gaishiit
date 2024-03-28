<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;


class CsvError extends Mailable
{
    use Queueable, SerializesModels;

    // 下記を追記
    /**
     * メール送信引数
     *
     * @var array
     */
    private $addr;
    private $files;

    // 上記までを追記

    // 下記内容を修正
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($addr ,$files)
    {
        $this->addr = $addr;
        $this->files = $files;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
		$cmd =  $this->to($this->addr)       // 送信先アドレス
    	    ->subject('【ガイシIT】更新CSVファイル取り込エラー')        // 件名
        	->text('mail_templates.csv_error');

		foreach ($this->files as $file) {
        	$cmd = $cmd->attachFromStorage($file); // 添付ファイル
		}

	    return $cmd;
    }

}