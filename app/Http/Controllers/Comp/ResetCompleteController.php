<?php

namespace App\Http\Controllers\Comp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ResetCompleteController extends Controller
{
    public function index()
    {
      return view('comp.auth.passwords.complete');
    }

}
