<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

use App\Models\Admin;

class EvalToAdmin extends Mailable
{
    use Queueable, SerializesModels;

	
    // 下記を追記
    /**
     * メール送信引数
     *
     * @var array
     */
    private $evalCount;

    // 下記内容を修正
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($evalCount)
    {
        $this->evalCount = $evalCount;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $admins = Admin::where('eval_priv','1')
        	->get();

        $to = array();

        foreach ( $admins as $ad ) {
            $to[] = $ad['email'];
		}

	    return $this->to($to)       // 送信先アドレス
    	    ->subject('【ガイシIT】クチコミ申込みのお知らせ')        // 件名
        	->text('mail_templates.eval_to_admin') // 本文
        	->with([
        		'evalCount' => $this->evalCount,
        	]);       // 本文に送る値
    }

}