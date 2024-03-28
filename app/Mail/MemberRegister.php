<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

use App\Models\CompMember;

class MemberRegister extends Mailable
{
    use Queueable, SerializesModels;

    // 下記を追記
    /**
     * メール送信引数
     *
     * @var array
     */
    private $member;
    private $comp;
    // 上記までを追記

    // 下記内容を修正
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($comp, $member)
    {
        $this->member = $member;
        $this->comp = $comp;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if ($this->member->admin_flag == '1') {
        	$title = '【ガイシIT】登録完了のお知らせ/企業登録について';
        } else {
        	$title = '【ガイシIT】登録完了のお知らせ';
        }
        
	    return $this->to($this->member->email)       // 送信先アドレス
    	    ->subject($title)        // 件名
        	->text('mail_templates.member_register') // 本文
        	->with(['member' => $this->member,
        			'comp' => $this->comp
        		]);       // 本文に送る値
    }

}