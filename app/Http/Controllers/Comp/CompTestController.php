<?php

namespace App\Http\Controllers\Comp;

use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;


class CompTestController extends Controller
{
	public function __construct()
    {
//        $this->middleware('auth:comp');
    }
    
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

		$userId = 50000000;

		$user = User::find($userId);
		Auth::login($user);

		$loginUser = Auth::user();

//ddd($loginUser);

   		return redirect(route('comp.mypage'));

	}

}
