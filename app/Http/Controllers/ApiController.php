<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Str;

use App\Models\Company;


class ApiController extends Controller
{
	public function __construct()
	{
	}


/*************************************
* 企業名取得
**************************************/
	public function complist(Request $request)
	{
		$word = $request->comp_name;
		
		$compList = Company::where(function($query) use ($word) {
						$query->where('name' , 'like', '%' . $word .'%')
						->orWhere('name_kana' , 'like', '%' . $word . '%')
						->orWhere('name_english' , 'like', '%' . $word . '%');
						})
					->selectRaw('id , name')
					->get();

		if (!isset($compList)) {
			abort(404);
		}

		return response()->json($compList);
	}

}
