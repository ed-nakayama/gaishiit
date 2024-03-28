<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Evaluation;

use Illuminate\Support\Facades\Mail;

use App\Mail\EvalToAdmin;


class EvalMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:evalmail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'send eval regist mail to admin';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $evalCount = Evaluation::where('approve_flag' ,'1')
			->count();

		if ($evalCount > 0) {
			Mail::send(new EvalToAdmin($evalCount));
			Evaluation::where('approve_flag', '1')->update(['approve_flag' => '2']);
		}
		
        $evalList = Evaluation::where('approve_flag' ,'9')
        	->where('updated_at', '<', date("Y-m-d 00:00:00", strtotime("-2 week")))
			->get();

		if (isset($evalList[0])) {
			foreach ($evalList as $eval) {
				$eval->delete();
			}
		}

    }


}
