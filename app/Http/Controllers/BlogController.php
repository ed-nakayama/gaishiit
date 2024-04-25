<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Blog;
use App\Models\BlogCat;
use App\Models\Ranking;


class BlogController extends Controller
{
	public function __construct()
	{
// 		$this->middleware('auth:admin');
	}


/*************************************
* ブログTOP
**************************************/
	public function index()
	{
		$blogList = Blog::where('open_flag' , '1')
			->orderBy('open_date', 'DESC')
			->orderBy('updated_at', 'DESC')
			->paginate(18);

		$catList = Blog::where('open_flag' , '1')
			->distinct()
			->select('cat_id')
			->get();

		foreach ($catList as $cat) {
			$bArray[] = $cat->cat_id;
		}

		$blogCatLlist = BlogCat::orderBy('order_num')
			->whereIn('id' ,$bArray)
			->orderBy('id')
			->get();

		$blogRankList = Blog::where('open_flag' , '1')
			->orderBy('article_count', 'DESC')
			->limit(5)
			->get();

    	$rankingList = $this->ranking();


		return view('user.blog' ,compact(
			'blogList',
			'blogRankList',
			'blogCatLlist',
			'rankingList',
			));
	}


/*************************************
* 一覧
**************************************/
	public function category(Request $request, $cat = '1')
	{
		$blogCat = BlogCat::find($cat);

		$blogList = Blog::where('open_flag' , '1')
			->where('cat_id' ,$cat)
			->orderBy('open_date', 'DESC')
			->orderBy('updated_at', 'DESC')
			->paginate(18);

		$catList = Blog::where('open_flag' , '1')
			->distinct()
			->select('cat_id')
			->get();

		foreach ($catList as $cat) {
			$bArray[] = $cat->cat_id;
		}

		$blogCatLlist = BlogCat::orderBy('order_num')
			->whereIn('id' ,$bArray)
			->orderBy('id')
			->get();

		$blogRankList = Blog::where('open_flag' , '1')
			->orderBy('article_count', 'DESC')
			->limit(5)
			->get();

    	$rankingList = $this->ranking();
    
		return view('user.blog_list' ,compact(
			'blogCat',
			'blogRankList',
			'blogList',
			'blogCatLlist',
			'rankingList',
		));
	}


/*************************************
* 詳細
**************************************/
	public function detail(Request $request, $cat = '1', $detail = '1')
	{
		$blogCat = BlogCat::find($cat);

		$blog = Blog::find($detail);
		$blog->article_count++;
		$blog->save();

		$catList = Blog::where('open_flag' , '1')
			->distinct()
			->select('cat_id')
			->get();

		foreach ($catList as $catCont) {
			$bArray[] = $catCont->cat_id;
		}

		$blogCatLlist = BlogCat::orderBy('order_num')
			->whereIn('id' ,$bArray)
			->orderBy('id')
			->get();

		$blogRankList = Blog::where('open_flag' , '1')
			->orderBy('article_count', 'DESC')
			->limit(5)
			->get();

		$blogList = Blog::where('open_flag' , '1')
			->where('cat_id' ,$cat)
			->where('id', '<>', $detail)
			->orderBy('open_date', 'DESC')
			->orderBy('updated_at', 'DESC')
			->limit(4)
			->get();

		$newBlogList = Blog::where('open_flag' , '1')
			->orderBy('open_date', 'DESC')
			->orderBy('updated_at', 'DESC')
			->limit(3)
			->get();


    	$rankingList = $this->ranking();

		return view('user.blog_detail' ,compact(
			'blog',
			'blogRankList',
			'blogCat',
			'blogCatLlist',
			'rankingList',
			'blogList',
			'newBlogList',
		));
	}


/*************************************
* 企業クチコランキング
**************************************/
	public function ranking()
	{

		$rankingList = Ranking::Join('companies','rankings.company_id','=','companies.id')
			->where('companies.open_flag' ,'1')
			->selectRaw('rankings.*, companies.name as company_name ,companies.logo_file as logo_file ,companies.image_file as image_file')
			->orderBy('total_point', 'DESC')
			->paginate(5);

		return $rankingList;
	}


}
