<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

use App\Models\User;

class AgentToUser extends Mailable
{
    use Queueable, SerializesModels;

    // 下記を追記
    /**
     * メール送信引数
     *
     * @var array
     */
    private $from_mail;
    private $to_mail;
    private $cc_mail;
    private $title;
    private $content;
    // 上記までを追記

    // 下記内容を修正
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($from_mail, $to_mail, $cc_mail, $title, $content)
    {
        $this->from_mail = $from_mail;
        $this->to_mail   = $to_mail;
        $this->cc_mail   = $cc_mail;
        $this->title     = $title;
        $this->content   = $content;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
/*
		$ret = $this->to($this->to_mail)
			->from($this->from_mail);

		if (!empty($this->cc_mail)) $ret = $ret->cc($this->cc_mail);
*/

		return $this->to($this->to_mail)      // 送信先アドレス
			->from($this->from_mail)
			->cc($this->cc_mail)
			->subject($this->title)        // 件名
			->text('mail_templates.agent_to_user') // 本文
			->with(['content' => $this->content
		]);       // 本文に送る値
    }

}