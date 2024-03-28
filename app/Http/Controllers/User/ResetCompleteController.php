<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ResetCompleteController extends Controller
{
    public function index()
    {
      return view('user.auth.passwords.complete');
    }

}
