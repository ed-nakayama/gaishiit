<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Blog;
use App\Models\BlogCat;


class BlogController extends Controller
{
	public function __construct()
	{
// 		$this->middleware('auth:admin');
	}


/*************************************
* 一覧
**************************************/
	public function index()
	{
		$blogList = Blog::get();
    
		return view('user.blog' ,compact('blogList'));
	}


/*************************************
* 一覧
**************************************/
	public function category(Request $request, $cat = '1')
	{
		$blogCat = BlogCat::find($cat);
		$blogList = Blog::get();
    
		return view('user.blog_list' ,compact(
			'blogCat',
			'blogList',
		));
	}


/*************************************
* 編集
**************************************/
	public function detail(Request $request, $cat = '1', $detail = '1')
	{
		$blogCat = BlogCat::find($cat);
		$blog = Blog::find($detail);

		return view('user.blog_detail' ,compact(
			'blog',
			'blogCat',
		));
	}



}
