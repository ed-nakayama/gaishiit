<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;


class ClientController extends Controller
{
	public $loginUser;
	
	public function __construct()
	{
 		$this->middleware('auth:comp');
	}


}
