<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;


class ExcelOk extends Mailable
{
    use Queueable, SerializesModels;

    // 下記を追記
    /**
     * メール送信引数
     *
     * @var array
     */
    private $addr;
    private $comp_name;

    // 上記までを追記

    // 下記内容を修正
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($addr ,$comp_name)
    {
        $this->addr = $addr;
        $this->comp_name = $comp_name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
		$cmd =  $this->to($this->addr)       // 送信先アドレス
    	    ->subject('【ガイシIT】RPAファイル取込完了')        // 件名
        	->text('mail_templates.rpa_ok')
        	->with([
        		'comp_name' => $this->comp_name,
        	]);       // 本文に送る値

	    return $cmd;
    }

}