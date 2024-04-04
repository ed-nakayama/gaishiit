{{-- ランキングフォーマット $ranking --}}

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

.expand-rank-content {
	font-size: 1.6rem;
	margin-left: 20px;
	margin-right: 20px;
	width:15%;
}

@media screen and (max-width: 820px) {
	.expand-rank-content {
		font-size: 1.2rem;
		width:25%;
	}
}

</style>

<div class="job-item" style="margin-left:10px;margin-right:10px;">
	<table style="margin-left:20px; margin-right:20px; font-size: 16px;width:100%;">
		<tr>
			<td style="width:20px;">
				<div class="job-corp-name">
					<figure>
						<img src="{{ $ranking->logo_file }}" alt="">
					</figure>
				</div>
			</td>
			<td style="padding:0 1em;">
				<a href="/company/{{ $ranking->company_id }}" style="text-decoration:underline;">{{ $ranking->company_name }}</a><br>
				<div style="text-align: center; display:flex; flex-wrap: wrap;">
					<p class="star5_rating" style="--rate: {{ $ranking->total_rate . '%' }};font-size:20px;"></p>
					<p style="margin:auto 0;">　　総合評価　<b>{{ number_format($ranking->total_point, 2) }}</b></p>
					<p style="margin:auto 0;">　　クチコミ数：{{ number_format($ranking->answer_count) }}　</p>
				</div>
			</td>
		</tr>
	</table>

	<div class="con-wrap">
		<div class="button-flex" style="width:200px;margin-top:0px; margin-bottom:10px;">
			<a href="/company/{{ $ranking->company_id }}">企業詳細</a>
		</div>
	</div>
	
</div>

{{-- END ランキングフォーマット --}}
