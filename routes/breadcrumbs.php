<?php

// Top
Breadcrumbs::for('top', function ($trail) {
    $trail->push('TOP', url("/"));
});

// 企業一覧    TOP > 企業一覧
Breadcrumbs::for('comp_list', function ($trail) {
	$trail->parent('top');
	$trail->push('企業一覧', url("/company"));
});

// 企業詳細  TOP > 企業一覧 > 株式会社〇〇
Breadcrumbs::for('comp_detail', function ($trail, $comp) {
	$trail->parent('comp_list');
	$trail->push($comp->name ,url("/company/{$comp->id}"));
});


// 企業詳細  TOP > 企業一覧 > 株式会社〇〇 > 株式会社〇〇の求人一覧
Breadcrumbs::for('comp_job_list', function ($trail, $comp) {
	$trail->parent('comp_detail' ,$comp);
	$trail->push("{$comp->name}の求人一覧" ,url("/company/{$comp->id}/joblist"));
});


// 企業詳細  TOP > 企業一覧 > 株式会社〇〇 > 〇〇（カテゴリ名）のクチコミ
Breadcrumbs::for('comp_detail_eval', function ($trail, $comp, $catName) {
	$trail->parent('comp_detail' ,$comp);
	$trail->push("{$catName}の口コミ" ,url("/company/{$comp->id}"));
});


// 部署詳細  TOP > 企業一覧 > 株式会社〇〇 > 〇〇部門
Breadcrumbs::for('unit_detail', function ($trail, $comp ,$unit) {
	$trail->parent('comp_detail' ,$comp);
	$trail->push('部門', url("/company/{$unit->company_id}/unit/{$unit->id}"));
});

// 部署詳細  TOP > 企業一覧 > 株式会社〇〇 > xxxイベント
Breadcrumbs::for('event_detail', function ($trail, $comp, $event) {
	$trail->parent('comp_detail' ,$comp);
	$trail->push('イベント', url("/company/{$event->company_id}/event/{$event->id}"));
});

// 求人一覧  TOP > 求人検索
Breadcrumbs::for('job_search', function ($trail) {
	$trail->parent('top');
    $trail->push('求人検索', url("/job"));
});

// 求人一覧  TOP > 求人検索 > 求人一覧
Breadcrumbs::for('job_list', function ($trail) {
	$trail->parent('job_search');
    $trail->push("求人一覧");
});

// 求人一覧  TOP > 求人検索 > XXの求人一覧
Breadcrumbs::for('job_list_cat', function ($trail ,$cat) {
	$trail->parent('job_search');
    $trail->push("{$cat}の求人一覧");
});

// 求人一覧  TOP > 求人検索 > XXの XXの求人一覧
Breadcrumbs::for('job_list_cat2', function ($trail ,$cat ,$cat2) {
	$trail->parent('job_search');
    $trail->push("{$cat}の{$cat2}の求人一覧");
});

// 求人一覧  TOP > 求人検索 > XXの XXの XXの求人一覧
Breadcrumbs::for('job_list_cat3', function ($trail ,$cat ,$cat2 ,$cat3) {
	$trail->parent('job_search');
    $trail->push("{$cat}の{$cat2}の{$cat3}の求人一覧");
});

// 求人詳細  TOP > 求人一覧 >  株式会社〇〇 > 求人詳細
Breadcrumbs::for('job_detail', function ($trail ,$comp, $job) {
	$trail->parent('comp_detail' ,$comp);
    $trail->push('求人詳細', url("/company/{$job->company_id}/{$job->id}"));
});


// 企業一覧    TOP > 新規登録
Breadcrumbs::for('register', function ($trail) {
	$trail->parent('top');
	$trail->push('新規登録');
});

// 企業一覧    TOP > 新規登録 > 確認
Breadcrumbs::for('register_confirm', function ($trail) {
	$trail->parent('register');
	$trail->push('確認');
});

// 企業一覧    TOP > 新規登録 > 確認 > 完了
Breadcrumbs::for('register_complete', function ($trail) {
	$trail->parent('register_confirm');
	$trail->push('完了');
});

// 企業詳細  TOP > 企業一覧 > ランキング
Breadcrumbs::for('comp_ranking', function ($trail) {
	$trail->parent('comp_list');
	$trail->push('ランキング');
});



// ブログトップ
Breadcrumbs::for('blog', function ($trail) {
	$trail->parent('top');
	$trail->push('ブログ', url("/blog"));
});

// ブログカテゴリ
Breadcrumbs::for('blog_list', function ($trail, $cat) {
	$trail->parent('blog');
	$trail->push("{$cat->name}");
});

// ブログ詳細（記事ページ）
Breadcrumbs::for('blog_detail', function ($trail, $detail) {
	$trail->parent('blog');
	$trail->push("{$detail->title}");
});


// マイページ
Breadcrumbs::for('mypage', function ($trail) {
    $trail->push('マイページ', url("/mypage"));
});

// 企業一覧    マイページ > メッセージ一覧
Breadcrumbs::for('interview_list', function ($trail) {
	$trail->parent('mypage');
	$trail->push('メッセージ一覧', url("/interview/list"));
});
