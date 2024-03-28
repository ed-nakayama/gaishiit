{{-- 簡易的な企業の紹介情報 $comp --}}

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

</style>

	<div class="con-wrap">

		<div class="job-item">
			<table style="margin-left:20px; margin-right:20px; font-size: 16px;">
				<tr>
					<td style="width:10%;">
						<div class="job-corp-name">
							<figure>
								@if(!empty($comp->logo_file))
									<img src="{{ $comp->logo_file }}" alt="">
								@endif
							</figure>
						</div>
					</td>
					<td>
						<p>{{ $comp->name }}<p>
						<div style="text-align: center; display:flex; ">
							<p class="txt" style="font-size:16px;">
								<span class="star5_rating" style="--rate: {{ $comp->total_rate . '%' }};font-size:20px;"></span>
								　総合評価　<b>{{ number_format($comp->total_point, 2) }}</b>
							</p>
						</div>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<p style="margin-left:90px;">{!! mb_strimwidth($comp->intro, 0, 100, "...") !!}</p>
					</td>
				</tr>
			</table>
		</div>

	</div>
{{-- 簡易的な企業の紹介情報 --}}
