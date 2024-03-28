<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;

use App\Mail\OpenError;

class SendOpenUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:sendopenupdate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'company job update';

	private $LOG_DIR      = 'public/comp_jobs/logs';
	private $MAIL_DIR      = 'public/comp_jobs/mail';
	
//	private $mail_addr = 'nakayama@aci7.com';
	private $mail_addr = 'rpa-result@gaishiit.com';

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
		$baseName = '';
		$attachFiles = array();

		$workName   = $this->LOG_DIR  . "/" . "Working_" . date("Ymd")  . ".log";
		$mailName   = $this->MAIL_DIR  . "/" . "mail_log.csv";

		if (Storage::exists($mailName) ) {

			$workLog = date("Y/m/d H:i:s") . " Start";
			Storage::disk('local')->append($workName, $workLog);

			// ファイル名変更
			$saveFile =  $this->MAIL_DIR  . "/" . "mail_log_" . date("Ymd_His") . ".csv";
			Storage::move($mailName, $saveFile);

			$file = Storage::disk('local')->path($saveFile);
			$filepath = pathinfo($file);

			Mail::send(new OpenError($this->mail_addr ,$saveFile));

			$workLog = date("Y/m/d H:i:s") . " Send Mail : " . $filepath['basename'];
			Storage::disk('local')->append($workName, $workLog);

			$workLog = date("Y/m/d H:i:s") . " End";
			Storage::disk('local')->append($workName, $workLog);
		}

	}

}

?>
