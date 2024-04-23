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
use App\Models\BlogSupervisor;
use App\Models\BlogContent;


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
		$blogList = Blog::orderBy('id', 'DESC')
			->paginate(10);
    
		return view('admin.blog_list' ,compact(
			'blogList',
		));
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

		$blogContentList = BlogContent::where('blog_id', $request->blog_id)->get();

		if (empty($blogContentList[0])) {
			for ($i = 0; $i < 20; $i++) {
				$cont = BlogContent::create([
					'blog_id' => $request->blog_id,
	        	]);
			}
		}

		$blogContentList = BlogContent::where('blog_id', $request->blog_id)->get();

	
		$blogCatList = BlogCat::get();
		$superList = BlogSupervisor::get();
		
		return view('admin.blog' ,compact(
			'blog',
			'blogContentList',
			'blogCatList',
			'superList',
		));
	}


/*************************************
* 保存
**************************************/
	public function store(Request $request)
	{
		$validatedData = $request->validate([
	  		'title'     => ['required', 'string'],
	  		'meta_desc' => ['required', 'string'],
	  		'cat_id'    => ['required'],
	  		'tag.*'     => ['required'],
   		]);


		// DB保存
		if (isset($request->blog_id)) {
			$blog = Blog::find($request->blog_id);

			if (!isset($blog)) {
				abort(404);
			}
			
		} else {
	        $blog = Blog::create([
				'cat_id' => $request->cat_id,
        	]);
		}


		// イメージファイル保存
		$image = null;
		$thumb = null;
		if (!empty($request->file('image'))) {

			$path = $request->file('image')->getClientOriginalName();
			$request->file('image')->storeAs('public/blog/original/' ,$path);

			$imgPath = storage_path('app/public/blog/original/' . $path);

			$baseName = pathinfo($path);

			$image =  '/storage/blog/'       . $baseName['filename'] . '.png';
			$thumb =  '/storage/blog/thumb/' . $baseName['filename'] . '.png';
			
			$img = ImageManager::imagick()->read($imgPath);
			$img->toPng()->save(storage_path('app/public/blog/' . $baseName['filename'] . '.png'));

			$img2 = ImageManager::imagick()->read($imgPath);
			$img2->scaleDown(width: 400); // 400 x 270
			$img2->toPng()->save(storage_path('app/public/blog/thumb/' . $baseName['filename'] . '.png'));
		}


		// 内容保存
		$idList = $request->idList;
		$full = '';
		
		$cnt = count($idList);
		
		for ($i = 0; $i < $cnt; $i++) {
			$cont = BlogContent::find($idList[$i]);
			
			if (empty($cont)) {
				$cont = new BlogContent();
				$cont->blog_id = $blog->id;
			}
			
			$cont->tag       = $request->tag[$i];
			$cont->sub_title = $request->sub_title[$i];
			$cont->content   = $request->content[$i];
				
			$cont->save();
				
			if (!empty($cont->tag)) {
				if ($cont->tag == '2') {
					$full .= "<h2 id=\"h2_{$cont->id}\">{$cont->sub_title}</h2>";
				} else if ($cont->tag == '3') {
					$full .= "<h3 id=\"h3_{$cont->id}\">{$cont->sub_title}</h3>";
				} else if ($cont->tag == '4') {
					$full .= "<h4>{$cont->sub_title}</h4>";
				}

				if (!empty($cont->content)) {
					if ($cont->tag == '5') {
						$tr_list = '';
						$line = explode("\r\n", $cont->content);
						if (!empty($line[0])) {
							foreach ($line as $index => $tr) {

								$td_all = '';
								$tdList = explode("/", $tr);
								foreach ($tdList as $index2 => $td) {
									if ($index == '0') {
										$td_all .= "<th>{$td}</th>";
									} else {
										$td_all .= "<td>{$td}</td>";
									}
								}
								$tr_list .= "<tr>{$td_all}</tr>";
							}
							$full .= "<table>{$tr_list}</table>";
						}
							
					}else {
						$full .= "<p>{$cont->content}</p>";
					}
				}
			}

		}

		// 目次作成
		$pattern = '@<h[2-3](?:.*?)>(.*?)</h[2-3]>@';
		preg_match_all($pattern, $full, $result);

		$pattern2 = '@<h2(?:.*?)>(.*?)</h2>@';
		preg_match_all($pattern2, $full, $result2);
		$contents_table = '';

		if (!empty($result[1])) {
			foreach ($result[1] as $index => $res) {
				foreach ($result2[1] as $index2 => $res2) {
					if ($res == $res2) {
						preg_match('@<h2 id="(.*?)">@', $result2[0][$index2] ,$tag);
						$contents_table .= "<p class=\"heading-primary\"><a href=\"#{$tag[1]}\">{$res}</a></p>";
						break;
					}
					if ($index2 === array_key_last($result2[1])) {
						preg_match('@<h3 id="(.*?)">@', $result[0][$index] ,$tag);
						$contents_table .= "<p class=\"heading-secondary\"><a href=\"#{$tag[1]}\">{$res}</a></p>";
					}
				}
			}
		}

		$blog->title              = $request->title;
		$blog->intro              = $request->intro;
		$blog->supervisor         = $request->supervisor;
		$blog->content            = $full;
		$blog->cat_id             = $request->cat_id;
		$blog->contents_table     = $contents_table;
		$blog->meta_desc          = $request->meta_desc;

		if (!empty($image)) {
			$blog->image = $image;
			$blog->thumb = $thumb;
		}

		if (!empty($super_img)) {
			$blog->supervisor_img = $super_img;
		}

		$blog->save();


		return redirect()->route('admin.blog.detail', [ 'blog_id' => $blog->id ] )->with('update_success', 'ブログを保存しました。');
	}



/*************************************
* 状態変更
**************************************/
	public function change( Request $request )
	{
		if ($request->open_flag == '1') {
			$validatedData = $request->validate([
				'open_date' => ['required'],
			]);
		}

		$blog = Blog::find($request->blog_id);

		if ($request->del_flag == '1') {
			$blog->delete();
			
		} else {
			if ($request->open_flag == '1') {
				$blog->open_flag = 1;
			} else {
				$blog->open_flag = 0;
			}

			$blog->open_date = $request->open_date;

			$blog->save();
		}
	
		if ($request->del_flag == '1') {
			return redirect('admin/blog/list');
		} else {
			return redirect()->route('admin.blog.detail', [ 'blog_id' => $blog->id ] )->with('option_success', 'ブログを保存しました。');
		}
		
	}




/*************************************
* カテゴリ一覧
**************************************/
	public function blogIndex()
	{
		$catList = BlogCat::get();
    
		return view('admin.blogcat_list' ,compact('catList'));
	}


	/*************************************
	* ブログカテゴリ追加
	**************************************/
	public function blogCatAdd( Request $request ){


		$validatedData = $request->validate([
			'name'     => ['required', 'string', 'max:60'],
		]);

		$cat = new BlogCat();

        $retCat = BlogCat::create([
            'name' => $request->name,
        ]);

		return redirect('admin/blogcat');
    }


	/*************************************
	* ブログカテゴリ保存
	**************************************/
	public function blogCatStore(Request $request)
	{

		$validatedData = $request->validate([
			'name'     => ['required', 'string', 'max:60'],
   		]);

		$cat = BlogCat::find($request->cat_id);

		$cat->name =  $request->name;

		if (!empty($request->order_num)) {
			$cat->order_num = $request->order_num;
		} else {
			$cat->order_num = null;
		}
		
		if (!empty($request->del_flag)) {
			$cat->del_flag = '1';
		} else {
			$cat->del_flag = '0';
		}
		
		$cat->save();

		return redirect('admin/blogcat');
	}


/*************************************
* 監修者一覧
**************************************/
	public function superIndex()
	{
		$superList =BlogSupervisor::get();
    
		return view('admin.blog_supervisor' ,compact('superList'));
	}


	/*************************************
	* 監修者追加
	**************************************/
	public function superAdd( Request $request ){


		$validatedData = $request->validate([
			'name'     => ['required', 'string', 'max:60'],
		]);

        $retCat = BlogSupervisor::create([
            'name' => $request->name,
        ]);

		return redirect('admin/supervisor');
    }


	/*************************************
	* 監修者保存
	**************************************/
	public function superStore(Request $request)
	{

		$validatedData = $request->validate([
			'name'     => ['required', 'string', 'max:60'],
   		]);

		$super = BlogSupervisor::find($request->id);

		$super->name    =  $request->name;
		$super->content = $request->content;
		$super->url     = $request->url;

		if (!empty($request->del_flag)) {
			$super->del_flag = '1';
		} else {
			$super->del_flag = '0';
		}

		$super_img = null;
		if (!empty($request->file('image'))) {

			$file_name = $request->file('image')->getClientOriginalName();
			$request->file('image')->storeAs('public/blog/original/' ,'super_' . $file_name);

			$imgPath = storage_path('app/public/blog/original/super_' . $file_name);

			$super_img =  '/storage/blog/super_'  . $file_name;

			$img = ImageManager::imagick()->read($imgPath);
			$img->scaleDown(width: 100); // 100 x 100
			$img->save(storage_path('app/public/blog/super_'  . $file_name));
		}

		if (!empty($super_img)) {
			$super->image = $super_img;
		}


		$super->save();

		return redirect('admin/supervisor');
	}




}
