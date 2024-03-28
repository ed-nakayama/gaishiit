<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Formal;
use App\Models\FormalMessage;
use App\Models\FormalMsgStatus;
use Illuminate\Support\Facades\Auth;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log; 
use Storage;
use Validator;

class FormalController extends Controller
{
	public $loginUser;
	
	public function __construct()
	{
 		$this->middleware('auth:user');
	}


/*************************************
* 一覧
**************************************/
	public function list()
	{
 
		$loginUser = Auth::user();

 		$query = Formal::query();

		$formalList = $query->leftJoin('formal_msg_statuses','formals.id','=','formal_msg_statuses.formal_id')->select('formals.*' ,  'formal_msg_statuses.read_flag')->where('reader_id',$loginUser->id)->paginate(10);

		$i = 0;
		foreach ($formalList as $cas) {
	 		$msg = FormalMessage::select('content')->where('formal_id', $cas->id)->orderBy('id', 'desc')->first();
 	 		$formalList[$i]->last_msg = $msg->content;
			
		}
		
		
		return view('formal_list' ,compact('formalList'));
	}


/*************************************
* 編集
**************************************/
	public function edit( Request $request )
	{
        if (empty($request->id)) {
			$loginUser = Auth::user();

			if  (Auth::guard('comp')->check()) {
	 			$request->id = $loginUser->company_id;
			} else {
				$request->id = $loginUser->id;
			}
       }


		$comp = Company::find($request->id);
		$personList = CompPerson::select()->where('company_id' , $request->id)->get();
      
		$Pref = ConstLocation::select('id', 'name')->get();
    
     // key,value ペアに直す
		$prefList = $Pref->pluck('name','id')->prepend( "未設定");
//Log::info($comp);
		return view('comp_edit' ,compact('comp' ,'personList' ,'prefList'));
	}


/*************************************
* データ保存
**************************************/
public function store( CompanyRequest $request ){

// Log::info($request);


        if (empty($request->id)) {
			$comp = new Company();
        } else {
            $comp = Company::find($request->id);
        }
 
		// 希望勤務地設定
		$working_place = '';
		if (!empty($request->prefId)) {
			foreach ($request->prefId as $pid) {
				$working_place .= $pid . ",";
			}
		}

        $comp->name = $request->name;
        $comp->name_kana = $request->name_kana;

        $comp->email = $request->email;
        $comp->tel = $request->tel;
        $comp->zip_code = $request->zip_code;
        $comp->pref_code = $request->pref_code;
        $comp->address = $request->address;

       if (empty($request->id)) {
           $comp->password = bcrypt($request->password);
		} else {
           $comp->password = $request->password;
		}
        $comp->personnel_name = $request->personnel_name;
        $comp->personnel_mail = $request->personnel_mail;
        $comp->cost_person_name = $request->cost_person_name;
        $comp->cost_person_mail = $request->cost_person_mail;

        $comp->company_summary = $request->company_summary;
        $comp->company_scheme = $request->company_scheme;
        $comp->company_url = $request->company_url;

        if ( empty($request->aprove_flag) ) {
        	$comp->aprove_flag = '0';
		} else {
            $comp->aprove_flag = $request->aprove_flag;
		}

        if ( empty($request->del_flag) ) {
            $comp->del_flag = '0';
		} else {
            $comp->del_flag = $request->del_flag;
		}


		// アップロードファイル保存
		if (!empty($request->file('logo'))) {
			$file_name = $request->file('logo')->getClientOriginalName();

			$comp->logo_name =  '/storage/comp/' . $request->id . '/logo/' . $file_name;
			
			$request->file('logo')->storeAs('public/comp/' . $request->id . '/logo' ,$file_name);
		}

		$comp->save();
		

//Log::info($request);
		//*** company person のvalidation
 		$rulus = [
   			'person.*.name' => 'required',
   			'person.*.email' => 'required|distinct',
   			'person.*.password' => 'required|min:8',
  		];

		$message = [
    		'person.*.name.required' => '担当者名は必須です。',
    		'person.*.password.required' => 'パスワードは必須です。',
    		'person.*.password.min' => 'パスワードは、8文字以上で指定してください。',
    		'person.*.email.required' => 'メールアドレスは必須です。',
     		'person.*.email.distinct' => 'メールアドレスが重複しています。',
 		];

		$validator = Validator::make($request->all(), $rulus, $message);
  
		if ($validator->fails()) {
			return redirect()->back()
				->withInput()
				->withErrors($validator);
		}
		//*** company person のvalidation END
        
        		
		// 担当者データの設定
		if (isset($request->person)) {
    		foreach ($request->person as $prz) {

				if ( empty($prz['id']) && empty($prz['del_flag']) ) {
					// 新規作成
					$person = new CompPerson();
			        $person->company_id = $comp->id;
			        if ( isset($prz['name']) ) $person->name = $prz['name'];
			        if ( isset($prz['email']) )$person->email = $prz['email'];
				    if ( isset($prz['password']) ) $person->password = bcrypt($person['password']);
				    if ( isset($prz['tel']) ) $person->tel = bcrypt($person['tel']);
				    if ( isset($prz['priv_flag']) ) {
						$person->priv_flag = $prz['priv_flag'];
					} else {
						$person->priv_flag = '0';
					}
					$person->save();

				} elseif ( isset($prz['id']) ) {

					if ( empty($prz['del_flag']) ) {
					 // 更新
						$person = ComPerson::select()->where('company_id' , $comp->id)->where('id' , $prz['id'])->first();

				        if ( isset($prz['name']) ) $person->name = $prz['name'];
				        if ( isset($prz['email']) )$person->email = $prz['email'];
					    if ( isset($prz['tel']) ) $person->tel = bcrypt($person['tel']);
					    if ( isset($prz['priv_flag']) ) {
							$person->priv_flag = $prz['priv_flag'];
						} else {
							$person->priv_flag = '0';
						}
	 	       			$person->save();

	        		} else {
	        		 // 削除
	        		 CompPerson::where('id', $prz['id'])->delete();
	        		}
	     		}
			}
		}
		
	

	return redirect()->back()->with('update_user_success', '更新しました。');

    }



	public function editPassword(){
        return view('company.company_password_edit');
    }

}
