<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Evaluation;
use App\Models\Ranking;
use App\Models\Company;

class SetRanking extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:setranking';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'get ranking';

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
        $compList = Company::leftJoin('rankings', 'rankings.company_id', 'companies.id')
			->whereNull('rankings.company_id')
        	->get();

		foreach ($compList as $comp) {
			Ranking::create(
				['company_id' => $comp->id],
			);
		}

		$rankingList = Evaluation::where('approve_flag' ,8)
			->selectRaw('company_id' .
				',sum(salary_point) / count(salary_point) as salary_point' .
				',sum(welfare_point) / count(welfare_point) as welfare_point' .
				',sum(upbring_point) / count(upbring_point) as upbring_point' .
				',sum(compliance_point) / count(compliance_point) as compliance_point' .
				',sum(motivation_point) / count(motivation_point) as motivation_point' .
				',sum(work_life_point) /  count(work_life_point) as work_life_point ' .
				',sum(remote_point) /  count(remote_point) as remote_point' .
				',sum(retire_point) /  count(retire_point) as retire_point' .

				',count(salary_content) as salary_count' .
				',count(welfare_content) as welfare_count' .
				',count(upbring_content) as upbring_count' .
				',count(compliance_content) as compliance_count' .
				',count(motivation_content) as motivation_count' .
				',count(work_life_content) as work_life_count' .
				',count(remote_content) as remote_count' .
				',count(retire_content) as retire_count' .
				
				',count(salary_point) as answer_count')
			->groupBy('company_id')
			->get();

		foreach ($rankingList as $ranking) {
			
			$total_point = ($ranking->salary_point
				+ $ranking->welfare_point
				+ $ranking->upbring_point
				+ $ranking->compliance_point
				+ $ranking->motivation_point
				+ $ranking->work_life_point
				+ $ranking->remote_point
				+ $ranking->retire_point) / 8;

			$total_rate = $total_point / 5 * 100;

			Ranking::where('company_id', $ranking->company_id)
				->update([
					'salary_point'     => $ranking->salary_point,
					'welfare_point'    => $ranking->welfare_point,
					'upbring_point'    => $ranking->upbring_point,
					'compliance_point' => $ranking->compliance_point,
					'motivation_point' => $ranking->motivation_point,
					'work_life_point'  => $ranking->work_life_point,
					'remote_point'     => $ranking->remote_point,
					'retire_point'     => $ranking->retire_point,
	
					'salary_count'     => $ranking->salary_count,
					'welfare_count'    => $ranking->welfare_count,
					'upbring_count'    => $ranking->upbring_count,
					'compliance_count' => $ranking->compliance_count,
					'motivation_count' => $ranking->motivation_count,
					'work_life_count'  => $ranking->work_life_count,
					'remote_count'     => $ranking->remote_count,
					'retire_count'     => $ranking->retire_count,

					'total_point'      => $total_point,
					'total_rate'       => $total_rate,
					'answer_count'     => $ranking->answer_count,
				]
			);
		}

	}


}

?>
