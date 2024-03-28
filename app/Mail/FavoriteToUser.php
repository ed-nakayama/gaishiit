<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

use App\Models\User;

class FavoriteToUser extends Mailable
{
    use Queueable, SerializesModels;

    // 下記を追記
    /**
     * メール送信引数
     *
     * @var array
     */
    private $user;
    private $jobList;
    // 上記までを追記

    // 下記内容を修正
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user ,$jobList)
    {
        $this->user = $user;
        $this->jobList = $jobList;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
	    return $this->to($this->user->email)       // 送信先アドレス
    	    ->subject('【ガイシIT】お気に入りジョブ更新のお知らせ')        // 件名
        	->text('mail_templates.favorite_to_user') // 本文
        	->with(['user' => $this->user,
        			'jobList' => $this->jobList,
        			]);       // 本文に送る値
    }

}