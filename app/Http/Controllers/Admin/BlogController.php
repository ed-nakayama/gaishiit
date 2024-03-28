<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

use Intervention\Image\ImageManager;

use App\Models\Blog;
use App\Models\BlogCat;


class BlogController extends Controller
{
	public function __construct()
	{
 		$this->middleware('auth:admin');
	}


/*************************************
* 一覧
**************************************/
	public function index()
	{
		$blogList = Blog::get();
    
		return view('admin.blog_list' ,compact('blogList'));
	}


/*************************************
* 編集
**************************************/
	public function detail(Request $request)
	{
		if ( isset($request->blog_id) ) {
			$blog = Blog::find($request->blog_id);

			if ( !isset($blog) ) {
				abort(404);
			}
		} else {
			$blog = new Blog();
		}
		
		$blogCatList = BlogCat::get();
		
		return view('admin.blog' ,compact(
			'blog',
			'blogCatList',
		));
	}


/*************************************
* 保存
**************************************/
	public function store(Request $request)
	{

		$validatedData = $request->validate([
	  		'title'    => ['required', 'string'],
	  		'content'  => ['required', 'string'],
	  		'cat_id'   => ['required'],
   		]);

		// イメージファイル保存
		$image = null;
		$thumb = null;
		if (!empty($request->file('image'))) {

			$file_name = $request->file('image')->getClientOriginalName();
			$image =  '/storage/blog/'  . $file_name;
			$thumb =  '/storage/thumb/'  . $file_name;
			$request->file('image')->storeAs('public/blog' ,$file_name);

			$imgPath = storage_path('app/public/blog/' . $file_name);

			$img = ImageManager::imagick()->read($imgPath);

			$img->scaleDown(width: 200); // 200 x 150

	        $img->save(storage_path('app/public/thumb/' . $file_name));
		}

		if (isset($request->blog_id)) {
			$blog = Blog::find($request->blog_id);

			if (!isset($blog)) {
				abort(404);
			}
			
			$blog->title   = $request->title;
			$blog->content = $request->content;
			$blog->cat_id  = $request->cat_id;

			if (!empty($image)) {
				$blog->image = $image;
				$blog->thumb = $thumb;
			}

			$blog->save();

		} else {
	        $blog = Blog::create([
    	        'title'   => $request->title,
    	        'content' => $request->content,
    	        'cat_id'  => $request->cat_id,
    	        'image'   => $image,
    	        'thumb'   => $thumb,
        	]);
		}


		return redirect()->route('admin.blog.detail', [ 'blog_id' => $blog->id ] )->with('update_success', 'ブログを保存しました。');
	}


/*************************************
* 状態変更
**************************************/
	public function change( Request $request )
	{
		$blog = Blog::find($request->blog_id);

		if ($request->del_flag == '1') {
			$blog->delete();
			
		} else {
			if ($request->open_flag == '1') {
				$blog->open_flag = 1;
			} else {
				$blog->open_flag = 0;
			}
			$blog->save();
		}
	
		if ($request->del_flag == '1') {
			return redirect('admin/blog/list');
		} else {
			return redirect()->route('admin.blog', [ 'blog_id' => $blog->id ] )->with('option_success', 'FAQ情報を保存しました。');
		}
		
	}


}
