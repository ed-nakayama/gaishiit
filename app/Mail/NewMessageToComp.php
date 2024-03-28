<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

use App\Models\User;

class NewMessageToComp extends Mailable
{
    use Queueable, SerializesModels;

    // 下記を追記
    /**
     * メール送信引数
     *
     * @var array
     */
    private $user;
    private $comp;
    private $member;
    private $interview;
    // 上記までを追記

    // 下記内容を修正
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user ,$comp ,$member ,$interview)
    {
//        $this->user = new User();
        $this->user = $user;
        $this->comp = $comp;
        $this->member = $member;
        $this->interview = $interview;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
	    return $this->to($this->member->email)       // 送信先アドレス
    	    ->subject('【ガイシIT】新規応募の申請がありました。')        // 件名
        	->text('mail_templates.message_to_comp') // 本文
        	->with(['user' => $this->user,
        			'comp' => $this->comp,
        			'member' => $this->member,
        			'interview' => $this->interview
        			]);       // 本文に送る値
    }

}