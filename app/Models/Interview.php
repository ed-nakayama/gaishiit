<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kyslik\ColumnSortable\Sortable;

class Interview extends Model
{
	use SoftDeletes;
	use Sortable;
	public $company;
	public $unit;
	public $job;
	public $user;
	public $msgStatus;
	public $stage;
	public $status;
	public $result;
	public $person;

//     protected $table = 'interviews';

	protected $guarded = [
        'id',
    ];

	public $sortable = ['id','aprove_flag'];  //追記(ソートに使うカラムを指定


/*************************************
* interview_kind 取得
**************************************/
	public function getKind()
	{
		$result = null;

		if ($this->interview_kind == '0') {
			$result = '企業';
		} else if ($this->interview_kind == '1') {
			$result = '部署';
		} else {
			$result = 'ジョブ';
		}

		return $result;
	}


/*************************************
* ユーザ情報 取得
**************************************/
	public function getUser()
	{
		$this->user = null;

		if (!empty($this->user_id)) {
			$this->user = User::withTrashed()
				->find($this->user_id);
		}
	}


/*************************************
* 企業情報 取得
**************************************/
	public function getCompany()
	{
		$this->company = null;

		if (!empty($this->company_id)) {
			$this->company = Company::withTrashed()
				->find($this->company_id);
		}
	}


/*************************************
* 企業名 取得
**************************************/
	public function getCompanyName()
	{
		$this->getCompany();

		if (!empty($this->company)) {
			return $this->company->name;
		} else {
			return '';
		}
	}


/*************************************
* 部署情報 取得
**************************************/
	public function getUnit()
	{
		$this->unit = null;

		if (!empty($this->unit_id)) {
			$this->unit = Unit::withTrashed()
				->find($this->unit_id);
		}
	}


/*************************************
* 部署名 取得
**************************************/
	public function getUnitName()
	{
		$this->getUnit();

		if (!empty($this->unit)) {
			return $this->unit->name;
		} else {
			return '';
		}
	}


/*************************************
* ジョブ 取得
**************************************/
	public function getJob()
	{
		$this->job = null;

		if (!empty($this->job_id)) {
			$this->job = Job::withTrashed()
				->find($this->job_id);
		}
	}


/*************************************
* ジョブ名 取得
**************************************/
	public function getJobName()
	{
		$this->getJob();

		if (!empty($this->job)) {
			return $this->job->name;
		} else {
			return '';
		}
	}


/*************************************
* イベント 取得
**************************************/
	public function getEvent()
	{
		$this->event = null;

		if (!empty($this->event_id)) {
			$this->event = Event::withTrashed()
				->find($this->event_id);
		}
	}


/*************************************
* イベント名 取得
**************************************/
	public function getEventName()
	{
		$this->getEvent();

		if (!empty($this->event)) {
			return $this->event->name;
		} else {
			return '';
		}
	}


/*************************************
* メッセージステータス 取得
**************************************/
	public function getMsgStatus($readerId)
	{
		$this->status = null;

		$this->status = InterviewMsgStatus::where('interview_id' ,$this->id)
			->where('reader_id', $readerId)
			->first();
	}


/*************************************
* ステージ 取得
**************************************/
	public function getStage()
	{
		$this->stage = null;

		if (!empty($this->stage_id)) {
			$this->stage = ConstStage::where('id' ,$this->stage_id)
			->first();
		}
	}


/*************************************
* ステージ名 取得
**************************************/
	public function getStageName()
	{
		$this->getStage();

		if (!empty($this->stage)) {
			return $this->stage->name;
		} else {
			return '';
		}
	}


/*************************************
* ステータス 取得
**************************************/
	public function getStatus()
	{
		$this->status = null;

		if (!empty($this->status_id)) {
			$this->status = ConstStatus::where('id' ,$this->status_id)
			->first();
		}
	}


/*************************************
* ステータス名 取得
**************************************/
	public function getStatusName()
	{
		$this->getStatus();

		if (!empty($this->status)) {
			return $this->status->name;
		} else {
			return '';
		}
	}



/*************************************
* 結果 取得
**************************************/
	public function getResult()
	{
		$this->result = null;

		if (!empty($this->result_id)) {
			$this->result = ConstResult::where('id' ,$this->result_id)
			->first();
		}
	}


/*************************************
* 結果名 取得
**************************************/
	public function getResultName()
	{
		$this->getResult();

		if (!empty($this->result)) {
			return $this->result->name;
		} else {
			return '';
		}
	}


/*************************************
* 担当者 取得
**************************************/
	public function getPerson()
	{
		$this->person = null;

		$loc = array();
		if ($this->interview_type == '0' && $this->interview_kind == '0') {
			if ( !empty($this->company->person) ) $loc = explode(',', $this->company->person);

		} elseif ($this->interview_type == '0' && $this->interview_kind == '1') {
			if ( !empty($this->unit->person) ) $loc = explode(',', $this->unit->person);

		} elseif ( ($this->interview_type == '0' && $this->interview_kind == '2') || $this->interview_type == '1' ) {
			if ( !empty($this->job->person) ) $loc = explode(',', $this->job->person);

		} elseif ($this->interview_type == '2') {
			if ( !empty($this->event->person) ) $loc = explode(',', $this->event->person);

		};
		
		if ( !empty($loc) ) {
			$ln = CompMember::whereIn('id' ,$loc)->get();

			$person_name = array();
			for ($i = 0; $i < count($ln); $i++) {
				$person_name[] = $ln[$i]['name'];
			}

			$this->person = implode('/', $person_name);
		}

	}

}
