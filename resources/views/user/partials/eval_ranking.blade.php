{{-- クチコミ数ランキング --}}
@php
	$rankingList = App\Models\Ranking::Join('companies','rankings.company_id','=','companies.id')
		->where('companies.open_flag' ,'1')
		->selectRaw('rankings.*, companies.name as company_name ,companies.logo_file as logo_file ,companies.image_file as image_file')
		->take($more_ranking)
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
		<h2>クチコミ数ランキング</h2>
		<div class="form-wrap">

			@foreach ($rankingList as $ranking)
			<div class="job-item" style="margin-left:10px;margin-right:10px;cursor: pointer;"  onclick="javascript:rankform{{ $ranking->company_id }}.submit()">
				<table style="margin-left:20px; margin-right:20px; font-size: 16px;">
					<tr>
						<td style="width:10%;">
							@if(!empty($ranking->logo_file))
								<div class="job-corp-name">
									<figure>
										<img src="{{ $ranking->logo_file }}" alt="">
									</figure>
								</div>
							@endif
						</td>
						<td>
							<b>{{ $ranking->company_name }}</b><br>
							<div style="text-align: center; display:flex; ">
								<p class="txt" style="font-size:16px;">
									<span class="star5_rating" style="--rate: {{ $ranking->total_rate . '%' }};font-size:20px;"></span>
									　総合評価　<b>{{ number_format($ranking->total_point, 2) }}</b>
								</p>
							</div>
						</td>
						<td style="font-size:14px;width:25%;">
							<table>
								<tr>
									<td>
										クチコミ数
									</td>
								</tr>
								<tr>
									<td align="right">
										<p class="txt" style="font-size:16px;">
											{{ number_format($ranking->total_count) }}
										</p>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>

				<div class="con-wrap">
						<div class="button-flex" style="width:200px;margin-top:0px; margin-bottom:10px;">
							<a href="/company/{{ $ranking->company_id }}">企業詳細</a>
						</div>
				</div>
				
			</div>
			@endforeach

			<div class="con-wrap">
					<div class="button-flex">
						<a href="'/company/ranking">クチコミ企業ランキングへ</a>
					</div>
			</div>
		</div>
	</div>
{{-- END クチコミ数ランキング --}}
