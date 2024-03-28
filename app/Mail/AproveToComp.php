<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

use App\Models\User;

class AproveToComp extends Mailable
{
    use Queueable, SerializesModels;

    // 下記を追記
    /**
     * メール送信引数
     *
     * @var array
     */
    private $member;
    private $url;
    // 上記までを追記

    // 下記内容を修正
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($member ,$url)
    {
        $this->member = $member;
        $this->url = $url;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
	    return $this->to($this->member->email)       // 送信先アドレス
    	    ->subject('【ガイシIT】新規候補者登録のお知らせ')        // 件名
        	->text('mail_templates.aprove_to_comp') // 本文
        	->with(['member' => $this->member,
        			'url' => $this->url
        		]);       // 本文に送る値
    }

}