{{-- クチコミ数ランキング --}}
@php
	$rankingList = App\Models\Ranking::Join('companies','rankings.company_id','=','companies.id')
		->where('companies.open_flag' ,'1')
		->selectRaw('rankings.*, companies.name as company_name ,companies.logo_file as logo_file ,companies.image_file as image_file')
		->orderBy('total_point', 'DESC')
		->take(10)
		->get();

@endphp


<style>
.job-item {
  background: #fff;
  border: 4px solid #E5AF24;
  border-radius: 20px;
  overflow: hidden;
  margin-top: 10px;
}
.job-corp-name figure {
  border-radius: 50%;
  overflow: hidden;
  width: 70px;
  border: 1px solid #D6D6D6;
  background: #D6D6D6;
}

.button-flex {
  display: flex;
  justify-content: center;
  margin: 32px auto;
}

.button-flex a {
  display: inline-block;
  font-size: 1.8rem;
  font-weight: 600;
  color: #fff;
  background: #4AA5CE;
  padding: 10px 20px;
  border-radius: 30px;
  max-width: 380px;
  width: 100%;
  text-align: center;
}

.button-flex a:nth-child(n+2) {
  margin-left: 30px;
}

</style>

	<div class="con-wrap">
		<h2>クチコミ評価ランキング</h2>
		<div class="form-wrap">

			@foreach ($rankingList as $ranking)
				{{-- ランキングフォーマット $ranking --}}
					@include ('user/partials/ranking_format')
				{{-- END ランキングフォーマット --}}
			@endforeach

			<div class="con-wrap">
				<div class="button-flex">
					<a href="/company/ranking">クチコミ企業ランキングへ</a>
				</div>
			</div>

		</div>
	</div>
{{-- END クチコミ数ランキング --}}
