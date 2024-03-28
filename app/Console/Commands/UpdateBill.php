<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Company;
use App\Models\Bill;
use App\Models\Interview;


class UpdateBill extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:updatebill';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'update bill data';

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
       
        $compList = Company::get();
        
        foreach ($compList as $comp) {

			// 都度払い
			if ( !empty($comp->every_end_date) && $comp->every_end_date < date('Y-m-d') ) { // 終了日が過去だったら何もしない

			} else {
				if (!empty($comp->every_start_date)) {

					$start_day = $comp->every_start_date;
					if (!empty($comp->every_end_date)) {
						$end_date = $comp->every_end_date;
					} else {
						$end_date = date("Y-m-t", strtotime("+3 month"));
					}

					while ($start_day < $end_date) {
						$last_day = date('Y-m-t', strtotime(date($start_day)));

						$bill = Bill::where('company_id', $comp->id)
							->where('bill_date', $last_day)
							->first();

						if ( empty($bill) ) {
							$bill = Bill::create([
								'company_id' =>  $comp->id,
								'bill_date'  => $last_day,
								'bill_type'  => '0',
							]);
						};

						// 合計値計算
						$billTotal = Interview::where('company_id' ,$comp->id)
							->where('interviews.interview_type' ,'1' )
							->where('entrance_date' ,'>=' ,$start_day)
							->where('entrance_date' ,'<=' ,$end_date)
							->selectRaw('sum(amount) as sub_total')
							->first();

						$bill->bill_type = '0';
						$bill->total_price = 0;
						if (!empty($billTotal->sub_total)) $bill->total_price = $billTotal->sub_total;
						$bill->save();
				
				
						$start_day = date("Y-m-d", strtotime("{$start_day} 1 month"));
					}
				}
			}



			// 月々払い
			if ( !empty($comp->monthly_end_date) && $comp->monthly_end_date < date('Y-m-d') ) { // 終了日が過去だったら何もしない

			} else {
				if (!empty($comp->monthly_start_date) )  {

					$start_day = $comp->monthly_start_date;
					if ($comp->monthly_end_date != '') {
						$end_date = $comp->monthly_end_date;
					} else {
						$end_date = date("Y-m-t", strtotime("+3 month"));
					}

					while ($start_day < $end_date) {
						$last_day = date('Y-m-t', strtotime(date($start_day)));

						$bill = Bill::where('company_id', $comp->id)
							->where('bill_date', $last_day)
							->first();

						if ( empty($bill) ) {
							Bill::create([
								'company_id' =>  $comp->id,
								'bill_date'  => $last_day,
								'bill_type'  => '1',
								'total_price'  => $comp->monthly_price,
							]);
						} else {
							if ($bill->bill_type != '1') {
								$bill->bill_type = '1';
								$bill->total_price = $comp->monthly_price;
								$bill->save();
							}
						}

						$start_day = date("Y-m-d", strtotime("{$start_day} 1 month"));
					}
				}
			}


			// 年払い
			if ( !empty($comp->yearly_end_date) && $comp->yearly_end_date < date('Y-m-d') ) { // 終了日が過去だったら何もしない

			} else {
				if (!empty($comp->yearly_start_date))  {

					$start_day = $comp->yearly_start_date;
					if ($comp->yearly_end_date != '') {
						$end_date = $comp->yearly_end_date;
					} else {
						$end_date = date("Y-m-t", strtotime("+3 month"));
					}

					while ($start_day < $end_date) {
						$last_day = date('Y-m-t', strtotime(date($start_day)));

						$bill = Bill::where('company_id', $comp->id)
							->where('bill_date', $last_day)
							->first();

						if ( empty($bill) ) {
							Bill::create([
								'company_id' =>  $comp->id,
								'bill_date'  => $last_day,
								'bill_type'  => '2',
								'total_price'  => $comp->yearly_price,
							]);
						} else {
							if ($bill->bill_type != '2') {
								$bill->bill_type = '2';
								$bill->total_price = $comp->yearly_price;
								$bill->save();
							}
						}

						$start_day = date("Y-m-d", strtotime("{$start_day} 1 month"));
					}
				}
			}

        } // end foreach


    }
}
