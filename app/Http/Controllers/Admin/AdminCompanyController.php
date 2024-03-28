<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\CompanyController;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;

use App\Models\Company;
use App\Models\CompMember;
use App\Models\CompInmail;
use App\Models\BusinessCatDetail;

class AdminCompanyController extends CompanyController
{
	public function __construct()
	{
 		$this->middleware('auth:admin');
	}


/*************************************
* 初期表示
**************************************/
	public function list(Request $request)
	{

		$comp_id = $request->comp_id;
		$comp_name =$request->comp_name;
		$compList = Company::orderBy('companies.name_english');

		if (!empty($comp_id) ) {
			$compList = $compList->where('id' , 'like' ,"$comp_id%");
		}
		
		if (!empty($comp_name) ) {
			$compList = $compList->where(function($query) use ($comp_name) {
	    		$query->where('name' , 'like' ,"%$comp_name%")
					->orWhere('name_kana' , 'like' ,"%$comp_name%")
					->orWhere('name_english' , 'like' ,"%$comp_name%");
				});
		}

		$compList = $compList->paginate(20);

		$yearMon = date("Y-m-01");

		$arg = 0;
		foreach ($compList as $comp) {
			$inmail = CompInmail::where('company_id' , $comp->id)
				->where('year_month' ,$yearMon)
				->first();
			
			if (empty($inmail)) {
				$inmail = CompInmail::create([
					'company_id' => $comp->id,
					'year_month' => $yearMon,
					'inmail_formal' => 0,
					'inmail_casual' => 0,
				]);
			}

			$compList[$arg]['mon_inmail_formal'] = $inmail->inmail_formal;
			$compList[$arg]['mon_inmail_casual'] = $inmail->inmail_casual;

			$arg++;
		}

		return view('admin.comp_list' ,compact(
			'compList',
			'comp_id',
			'comp_name',
		));
	}


/*************************************
* inmail更新
**************************************/
	public function inMailPost(Request $request)
	{

		$comp = Company::find($request->comp_id);

		if (!empty($request->agency_flag)) {
			$comp->agency_flag = $request->agency_flag;
		} else {
			$comp->agency_flag = 0;
		}

		$comp->in_mail_casual = $request->in_mail_casual;
		$comp->in_mail_formal = $request->in_mail_formal;
		
		$comp->save();

		return redirect()->route('admin.comp.list', ['page' => $request->get('page') ,'comp_name' => $request->get('comp_name')]);
	}



/*************************************
* 新規作成
**************************************/
	public function getRegister()
	{
		$comp = new Company();
		$comp_id = "";

		$comp->backg_flag = '1';

		return view('admin.comp_edit' ,compact(
			'comp',
			'comp_id',
		));
	}



/*************************************
* 登録
**************************************/
	public function postRegister(Request $request)
	{

		$validatedData = $request->validate([
			'name'             => ['required', 'string', 'max:40'],
			'name_english'     => ['required', 'string', 'max:60'],
   		]);

		$agency_flag = '0';
		$backg_flag = '0';
		$backg_eng_flag = '0';
		$personal_flag = '0';
		$open_flag = '0';
		if ($request->agency_flag == '1') $agency_flag = '1';
		if ($request->backg_flag == '1') $backg_flag = '1';
		if ($request->backg_eng_flag == '1') $backg_eng_flag = '1';
		if ($request->personal_flag == '1') $personal_flag = '1';
		if ($request->open_flag == '1') $open_flag = '1';


		$comp = Company::updateOrCreate(
			['id' => $request->comp_id],
			['name'              => $request->name,
            'name_kana'          => $request->name_kana,
            'name_english'       => $request->name_english,
            'business_cat_id'    => $request->business_cat,
            'every_start_date'   => $request->every_start_date,
            'every_end_date'     => $request->every_end_date,
            'monthly_start_date' => $request->monthly_start_date,
            'monthly_end_date'   => $request->monthly_end_date,
            'monthly_price'      => $request->monthly_price,
            'yearly_start_date'  => $request->yearly_start_date,
            'yearly_end_date'    => $request->yearly_end_date,
            'yearly_price'       => $request->yearly_price,
            'address'            => $request->address,
//            'personnel_name'     => $request->personnel_name,
//            'personnel_mail'     => $request->personnel_mail,
            'fee_memo'           => $request->fee_memo,
            'cost_person_name'   => $request->cost_person_name,
            'cost_person_mail'   => $request->cost_person_mail,
            'agency_flag'        => $agency_flag,
            'backg_flag'         => $backg_flag,
            'backg_eng_flag'     => $backg_eng_flag,
            'personal_flag'      => $personal_flag,
            'in_mail_casual'     => $request->in_mail_casual,
            'in_mail_formal'     => $request->in_mail_formal,
            'salesforce_id'      => $request->salesforce_id,
            'intro'              => $request->intro,
            'memo'               => $request->memo,
            'url'                => $request->url,
            'ceo'                => $request->ceo,
            'employ_num'         => $request->employ_num,
            'establish_year'     => $request->establish_year,
            'capital'            => $request->capital,
            'open_flag'          => $open_flag,
            'commit_cat_id'      => $request->commit_cat,
			]
		);

		// ロゴファイル保存
		$logo = '';
		if (!empty($request->file('logo'))) {
			$file_name = $request->file('logo')->getClientOriginalName();
			$logo =  "/storage/comp/{$comp->id}/{$file_name}";
			$mid_logo =  "/storage/comp/{$comp->id}/mid_{$file_name}";
			$request->file('logo')->storeAs("public/comp/{$comp->id}" ,"original_{$file_name}");

			$imgPath = storage_path("app/public/comp/{$comp->id}/original_{$file_name}");

			$img = ImageManager::imagick()->read($imgPath);
			$img->scaleDown(width: 70); // 65 x xxx
	        $img->save(storage_path("app/public/comp/{$comp->id}/{$file_name}"));



/*
        $imgx = \Image::make($imgPath);
        $imgx->limitColors(null);
        //一覧オリジナルwebP
        $imgx->encode('webp')->save($file_path);
*/
			$comp->logo_file = $logo;

			$img2 = ImageManager::imagick()->read($imgPath);
			$img2->scaleDown(width: 140); // 140 x xxx
	        $img2->save(storage_path("app/public/comp/{$comp->id}/mid_{$file_name}"));
			$comp->mid_logo_file = $mid_logo;
		}

		// イメージファイル保存
		$image = '';
		if (!empty($request->file('image'))) {
			$file_name = $request->file('image')->getClientOriginalName();
			$image =  '/storage/comp/'  . $comp->id . '/'  . $file_name;
			$request->file('image')->storeAs('public/comp/' . $comp->id ,$file_name);

			$comp->image_file = $image;
		}

		// 業種保存
		$comp->setBusCat($request->businessCat);
		// こだわり保存
		$comp->setCommit($request->commitCat);

		$comp->save();

		return redirect()->route('admin.comp.edit', ['comp_id' => $comp->id ]);
	}


/*************************************
* 編集
**************************************/
	public function edit( Request $request)
	{
		$comp = Company::find($request->comp_id);
		$comp_id = $request->comp_id;

		return view('admin.comp_edit' ,compact(
			'comp',
			'comp_id',
		));
	}


}
