<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Spatie\Sitemap\SitemapGenerator;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

use App\Models\Company;
use App\Models\Unit;
use App\Models\Event;
use App\Models\Job;
use App\Models\ConstLocation;
use App\Models\JobCat;
use App\Models\JobCatDetail;
use App\Models\IndustoryCat;
use App\Models\IndustoryCatDetail;
use App\Models\BusinessCat;
use App\Models\BusinessCatDetail;
use App\Models\Income;
use App\Models\CommitCatDetail;


class GenerateSitemap extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the sitemap.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $sitemap = Sitemap::create('https://gaishiit.com/')
			->add(Url::create('/')->setPriority(0.5))
			->add(Url::create('/corporate')->setPriority(0.5))
			->add(Url::create('/privacy')->setPriority(0.5))
			->add(Url::create('/kiyaku')->setPriority(0.5))
			->add(Url::create('/adminfaq')->setPriority(0.5))
			->add(Url::create(route('user.register'))->setPriority(0.5))
			->add(Url::create('/company')->setPriority(0.5))
			->add(Url::create('/company/ranking')->setPriority(0.5))
			->add(Url::create('/job')->setPriority(0.5))
			->add(Url::create('/job/list')->setPriority(0.5));

			$compList = Company::where('open_flag' ,'1')->get();
			foreach ($compList as $comp) {
				$sitemap->add(Url::create("/company/{$comp->id}")->setPriority(0.5));
			}


			$unitList = Unit::join('companies', 'units.company_id', '=', 'companies.id')
				->selectRaw('units.*')
				->where('units.open_flag' ,'1')
				->where('companies.open_flag' ,'1')
				->get();

			foreach ($unitList as $unit) {
				$sitemap->add(Url::create("/company/{$unit->company_id}/unit/{$unit->id}")->setPriority(0.5));
			}


			$eventList = Event::join('companies', 'events.company_id', '=', 'companies.id')
				->selectRaw('events.*')
				->where('events.open_flag' ,'1')
				->where('companies.open_flag' ,'1')
				->get();

			foreach ($eventList as $event) {
				$sitemap->add(Url::create("/company/{$event->company_id}/unit/{$event->id}")->setPriority(0.5));
			}


			$jobList = Job::join('companies', 'jobs.company_id', '=', 'companies.id')
				->selectRaw('jobs.*')
				->where('jobs.open_flag' ,'1')
				->where('companies.open_flag' ,'1')
				->get();

			foreach ($jobList as $job) {
				$sitemap->add(Url::create("/company/{$job->company_id}/{$job->id}")->setPriority(0.5));
			}


			$locList = ConstLocation::where('del_flag','0')->get();
			$jobcatList = JobCat::where('del_flag','0')->get();
			$jobcatDetailList = JobCatDetail::where('del_flag','0')->get();
			$indcatList = IndustoryCat::where('del_flag','0')->get();
			$indcatDetailList = IndustoryCat::where('del_flag','0')->get();
			$buscatList = BusinessCat::where('del_flag','0')->get();
			$buscatDetailList = BusinessCatDetail::where('del_flag','0')->get();
			$incomeList = Income::where('del_flag','0')->get();
			$commitList = CommitCatDetail::where('del_flag','0')->get();


			// 1語パターン
			foreach ($locList as $loc) {
				$job = $this->findJob($loc->id);

				if (!empty($job)) {
					$sitemap->add(Url::create("/job/list/location{$loc->id}")->setPriority(0.5));
				}
			}
			foreach ($jobcatList as $cat) {
				$job = $this->findJob(null, $cat->id);

				if (!empty($job)) {
					$sitemap->add(Url::create("/job/list/jobcategory{$cat->id}")->setPriority(0.5));
				}
			}

			foreach ($jobcatDetailList as $cat) {
				$job = $this->findJob(null, null, $cat->id);

				if (!empty($job)) {
					$sitemap->add(Url::create("/job/list/occupation{$cat->id}")->setPriority(0.5));
				}
			}

			foreach ($indcatList as $cat) {
				$job = $this->findJob(null, null, null, $cat->id);

				if (!empty($job)) {
					$sitemap->add(Url::create("/job/list/indcat{$cat->id}")->setPriority(0.5));
				}
			}

			foreach ($indcatDetailList as $cat) {
				$job = $this->findJob(null, null, null, null, $cat->id);

				if (!empty($job)) {
					$sitemap->add(Url::create("/job/list/industory{$cat->id}")->setPriority(0.5));
				}
			}

			foreach ($buscatList as $cat) {
				$job = $this->findJob(null, null, null, null, null, $cat->id);

				if (!empty($job)) {
					$sitemap->add(Url::create("/job/list/buscat{$cat->id}")->setPriority(0.5));
				}
			}

			foreach ($buscatDetailList as $cat) {
				$job = $this->findJob(null, null, null, null, null, null, $cat->id);

				if (!empty($job)) {
					$sitemap->add(Url::create("/job/list/business{$cat->id}")->setPriority(0.5));
				}
			}

			foreach ($commitList as $commit) {
				$job = $this->findJob(null, null, null, null, null, null, null, $commit->id);

				if (!empty($job)) {
					$sitemap->add(Url::create("/job/list/commit{$commit->id}")->setPriority(0.5));
				}
			}

			foreach ($incomeList as $income) {
				$job = $this->findJob(null, null, null, null, null, null, null, null, $income->id);

				if (!empty($job)) {
					$sitemap->add(Url::create("/job/list/income{$income->id}")->setPriority(0.5));
				}
			}


			// ２語パターン
			foreach ($locList as $param1) {
				foreach ($jobcatList as $cat) {
					$job = $this->findJob($param1->id, $cat->id);

					if (!empty($job)) {
						$sitemap->add(Url::create("/job/list/location{$param1->id}/jobcategory{$cat->id}")->setPriority(0.5));
					}
				}

				foreach ($jobcatDetailList as $cat) {
					$job = $this->findJob($param1->id, null, $cat->id);

					if (!empty($job)) {
						$sitemap->add(Url::create("/job/list/location{$param1->id}/occupation{$cat->id}")->setPriority(0.5));
					}
				}

				foreach ($indcatList as $cat) {
					$job = $this->findJob($param1->id, null, null, $cat->id);

					if (!empty($job)) {
						$sitemap->add(Url::create("/job/list/location{$param1->id}/indcat{$cat->id}")->setPriority(0.5));
					}
				}

				foreach ($indcatDetailList as $cat) {
					$job = $this->findJob($param1->id, null, null, null, $cat->id);

					if (!empty($job)) {
						$sitemap->add(Url::create("/job/list/location{$param1->id}/industory{$cat->id}")->setPriority(0.5));
					}
				}

				foreach ($buscatList as $cat) {
					$job = $this->findJob($param1->id, null, null, null, null, $cat->id);

					if (!empty($job)) {
						$sitemap->add(Url::create("/job/list/location{$param1->id}/buscat{$cat->id}")->setPriority(0.5));
					}
				}

				foreach ($buscatDetailList as $cat) {
					$job = $this->findJob($param1->id, null, null, null, null, null, $cat->id);

					if (!empty($job)) {
						$sitemap->add(Url::create("/job/list/location{$param1->id}/business{$cat->id}")->setPriority(0.5));
					}
				}

				foreach ($commitList as $cat) {
					if ($commit->index_flag == '1') {
						$job = $this->findJob($param1->id, null, null, null, null, null, null, $cat->id);

						if (!empty($job)) {
							$sitemap->add(Url::create("/job/list/location{$param1->id}/commit{$cat->id}")->setPriority(0.5));
						}
					}
				}

				foreach ($incomeList as $cat) {
					$job = $this->findJob($param1->id, null, null, null, null, null, null, null, $cat->id);

					if (!empty($job)) {
						$sitemap->add(Url::create("/job/list/location{$param1->id}/income{$cat->id}")->setPriority(0.5));
					}
				}
			}


			foreach ($jobcatList as $param1) {
				foreach ($indcatList as $cat) {
					$job = $this->findJob(null, $param1->id, $cat->id);

					if (!empty($job)) {
						$sitemap->add(Url::create("/job/list/jobcategory{$param1->id}/indcat{$cat->id}")->setPriority(0.5));
					}
				}

				foreach ($indcatDetailList as $cat) {
					$job = $this->findJob(null, $param1->id, null, $cat->id);

					if (!empty($job)) {
						$sitemap->add(Url::create("/job/list/jobcategory{$param1->id}/industory{$cat->id}")->setPriority(0.5));
					}
				}

				foreach ($buscatList as $cat) {
					$sitemap->add(Url::create("/job/list/jobcategory{$param1->id}/buscat{$cat->id}")->setPriority(0.5));
				}

				foreach ($buscatDetailList as $cat) {
					$job = $this->findJob(null, $param1->id, null, null, $cat->id);

					if (!empty($job)) {
						$sitemap->add(Url::create("/job/list/jobcategory{$param1->id}/business{$cat->id}")->setPriority(0.5));
					}
				}

				foreach ($commitList as $cat) {
					if ($commit->index_flag == '1') {
						$job = $this->findJob(null, $param1->id, null, null, null, $cat->id);

						if (!empty($job)) {
							$sitemap->add(Url::create("/job/list/jobcategory{$param1->id}/commit{$cat->id}")->setPriority(0.5));
						}
					}
				}

				foreach ($incomeList as $cat) {
					$job = $this->findJob(null, $param1->id, null, null, null, null, $cat->id);

					if (!empty($job)) {
						$sitemap->add(Url::create("/job/list/jobcategory{$param1->id}/income{$cat->id}")->setPriority(0.5));
					}
				}
			}


			foreach ($jobcatDetailList as $param1) {
				foreach ($indcatList as $cat) {
					$job = $this->findJob(null, null, $param1->id, $cat->id);

					if (!empty($job)) {
						$sitemap->add(Url::create("/job/list/occupation{$param1->id}/indcat{$cat->id}")->setPriority(0.5));
					}
				}

				foreach ($indcatDetailList as $cat) {
					$job = $this->findJob(null, null, $param1->id, null, $cat->id);

					if (!empty($job)) {
						$sitemap->add(Url::create("/job/list/occupation{$param1->id}/industory{$cat->id}")->setPriority(0.5));
					}
				}

				foreach ($buscatList as $cat) {
					$job = $this->findJob(null, null, $param1->id, null, null, $cat->id);

					if (!empty($job)) {
						$sitemap->add(Url::create("/job/list/occupation{$param1->id}/buscat{$cat->id}")->setPriority(0.5));
					}
				}

				foreach ($buscatDetailList as $cat) {
					$job = $this->findJob(null, null, $param1->id, null, null, null, $cat->id);

					if (!empty($job)) {
						$sitemap->add(Url::create("/job/list/occupation{$param1->id}/business{$cat->id}")->setPriority(0.5));
					}
				}

				foreach ($commitList as $cat) {
					if ($commit->index_flag == '1') {
						$job = $this->findJob(null, null, $param1->id, null, null, null, null, $cat->id);

						if (!empty($job)) {
							$sitemap->add(Url::create("/job/list/occupation{$param1->id}/commit{$cat->id}")->setPriority(0.5));
						}
					}
				}

				foreach ($incomeList as $cat) {
					$job = $this->findJob(null, null, $param1->id, null, null, null, null, null, $cat->id);

					if (!empty($job)) {
						$sitemap->add(Url::create("/job/list/occupation{$param1->id}/income{$cat->id}")->setPriority(0.5));
					}
				}
			}


			// ３語パターン
			foreach ($locList as $param1) {
				foreach ($jobcatList as $param2) {
					foreach ($indcatList as $cat) {
						$job = $this->findJob($param1->id,$param2->id, $cat->id);

						if (!empty($job)) {
							$sitemap->add(Url::create("/job/list/location{$param1->id}/jobcategory{$param2->id}/indcat{$cat->id}")->setPriority(0.5));
						}
					}

					foreach ($indcatDetailList as $cat) {
						$job = $this->findJob($param1->id,$param2->id, null, $cat->id);

						if (!empty($job)) {
							$sitemap->add(Url::create("/job/list/location{$param1->id}/jobcategory{$param2->id}/industory{$cat->id}")->setPriority(0.5));
						}
					}

					foreach ($buscatList as $cat) {
						$job = $this->findJob($param1->id,$param2->id, null, null, $cat->id);

						if (!empty($job)) {
							$sitemap->add(Url::create("/job/list/location{$param1->id}/jobcategory{$param2->id}/buscat{$cat->id}")->setPriority(0.5));
						}
					}

					foreach ($buscatDetailList as $cat) {
						$job = $this->findJob($param1->id,$param2->id, null, null, null, $cat->id);

						if (!empty($job)) {
							$sitemap->add(Url::create("/job/list/location{$param1->id}/jobcategory{$param2->id}/business{$cat->id}")->setPriority(0.5));
						}
					}

					foreach ($commitList as $cat) {
						if ($commit->index_flag == '1') {
							$job = $this->findJob($param1->id,$param2->id, null, null, null, null, $cat->id);

							if (!empty($job)) {
								$sitemap->add(Url::create("/job/list/location{$param1->id}/jobcategory{$param2->id}/commit{$cat->id}")->setPriority(0.5));
							}
						}
					}

					foreach ($incomeList as $cat) {
						$job = $this->findJob($param1->id,$param2->id, null, null, null, null, null, $cat->id);

						if (!empty($job)) {
							$sitemap->add(Url::create("/job/list/location{$param1->id}/jobcategory{$param2->id}/income{$cat->id}")->setPriority(0.5));
						}
					}
				}

				foreach ($jobcatDetailList as $param2) {
					foreach ($indcatList as $cat) {
						$job = $this->findJob($param1->id, null, $param2->id, $cat->id);

						if (!empty($job)) {
							$sitemap->add(Url::create("/job/list/location{$param1->id}/occupation{$param2->id}/indcat{$cat->id}")->setPriority(0.5));
						}
					}

					foreach ($indcatDetailList as $cat) {
						$job = $this->findJob($param1->id, null, $param2->id, null, $cat->id);

						if (!empty($job)) {
							$sitemap->add(Url::create("/job/list/location{$param1->id}/occupation{$param2->id}/industory{$cat->id}")->setPriority(0.5));
						}
					}

					foreach ($buscatList as $cat) {
						$job = $this->findJob($param1->id, null, $param2->id, null, null, $cat->id);

						if (!empty($job)) {
							$sitemap->add(Url::create("/job/list/location{$param1->id}/occupation{$param2->id}/buscat{$cat->id}")->setPriority(0.5));
						}
					}

					foreach ($buscatDetailList as $cat) {
						$job = $this->findJob($param1->id, null, $param2->id, null, null, null, $cat->id);

						if (!empty($job)) {
							$sitemap->add(Url::create("/job/list/location{$param1->id}/occupation{$param2->id}/business{$cat->id}")->setPriority(0.5));
						}
					}

					foreach ($commitList as $cat) {
						if ($commit->index_flag == '1') {
							$job = $this->findJob($param1->id, null, $param2->id, null, null, null, null, $cat->id);

							if (!empty($job)) {
								$sitemap->add(Url::create("/job/list/location{$param1->id}/occupation{$param2->id}/commit{$cat->id}")->setPriority(0.5));
							}
						}
					}

					foreach ($incomeList as $cat) {
						$job = $this->findJob($param1->id, null, $param2->id, null, null, null, null, null, $cat->id);

						if (!empty($job)) {
							$sitemap->add(Url::create("/job/list/location{$param1->id}/occupation{$param2->id}/income{$cat->id}")->setPriority(0.5));
						}
					}
				}
			}


		$sitemap->writeToFile(public_path('sitemap.xml'));
    }


	/********************************************
	 * ジョブ検索
	 ********************************************/
    private function findJob($locations,
    		$job_cats              = null,
    		$job_cat_details       = null,
    		$industory_cats        = null,
    		$industory_cat_details = null,
    		$business_cats         = null,
    		$business_cat_details  = null,
    		$commit_cat_details    = null,
    		$income                = null)
    {

		$jobList = Job::Join('companies','jobs.company_id', 'companies.id')
			->where('companies.open_flag' ,'1')
			->where('jobs.open_flag','1')
			->whereNotNull('jobs.intro')
			->where('jobs.intro','!=','');

		// ロケーション
		if (!empty($location)) {
			$jobList = $jobList->where('jobs.locations' ,'like', '%' . $location .'%');
		}

		// 職種1
		if ( isset($job_cats) ) {
			$jobList = $jobList->where('jobs.job_cats' ,'like', '%[' . $job_cats .']%');
		}

		// 職種2
		if ( isset($job_cat_details) ) {
			$jobList = $jobList->where('jobs.job_cat_details' ,'like', '%[' . $job_cat_details .']%');
		}

		// インダストリ1
		if ( isset($industory_cats) ) {
			$jobList = $jobList->where('jobs.industory_cats' ,'like', '%[' . $industory_cats .']%');
		}

		// インダストリ2
		if ( isset($industory_cat_details) ) {
			$jobList = $jobList->where('jobs.industory_cat_details' ,'like', '%[' . $industory_cat_details .']%');
		}

		// 業種1
		if ( isset($business_cats) ) {
			$jobList = $jobList->where('companies.business_cats' ,'like', '%[' . $business_cats .']%');
		}

		// 業種2
		if ( isset($business_cat_details) ) {
			$jobList = $jobList->where('companies.business_cat_details' ,'like', '%[' . $business_cat_details .']%');
		}

		// こだわり
		if ( !empty($commit_cat_details) ) {
			$jobList = $jobList->where('companies.commit_cats' ,'like', '%[' . $commit_cat_details .']%');
		}

		// 年収
		if ( !empty($income) ) {
		    $jobList = $jobList->where('jobs.income_id' , $income);
		}

		$jobList = $jobList->selectRaw('jobs.id')->first();

		return $jobList;
	}

}