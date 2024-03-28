<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

use App\Models\Admin;

class AgentRequest extends Mailable
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
    private $unit;
    private $job;
    // 上記までを追記

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $comp, $unit ,$job)
    {
        $this->user = $user;
        $this->comp = $comp;
        $this->unit = $unit;
        $this->job = $job;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $admins = Admin::where('aprove_priv','1')
        	->get();

        $to = array();

        foreach ( $admins as $ad ) {
            $to[] = $ad['email'];
		}

	    return $this->to($to)       // 送信先アドレス
    	    ->subject('【ガイシIT】転職エージェントに相談の申請')        // 件名
        	->text('mail_templates.agent_request') // 本文
        	->with(['user' => $this->user,
        			'comp' => $this->comp,
        			'unit' => $this->unit,
        			'job' => $this->job,
        			]);       // 本文に送る値
    }

}