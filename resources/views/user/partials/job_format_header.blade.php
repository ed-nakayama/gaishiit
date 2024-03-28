{{-- ジョブフォーマットヘッダ $job --}}

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
{{--
.job-location {position: relative; font-size: 1.8rem; margin-left: 20px; white-space: nowrap; }
.job-location::before {content: ''; display: inline-block; background: url('/img/icon_location.png') no-repeat; 
  /* background-size: 100% auto; */
  background-size: contain;
  margin-right: 6px; width: 1em; height: 1em;
}

.job-income {position: relative; font-size: 1.8rem; margin-left: 20px; white-space: nowrap; }
.job-income::before {content: ''; display: inline-block; background: url('/img/icon_yen.jpg') no-repeat; 
  /* background-size: 100% auto; */
  background-size: contain;
  margin-right: 6px; width: 1em; height: 1em;
}
--}}
.job-unit {
  font-size: 1.4rem;
  display: inline-block;
  background: #E5AF24;
  border-radius: 14px;
  margin-bottom: 6px;
  display: inline-block;
  color: #fff;
  padding: 4px 16px;
}


</style>

		<table style="font-size: 20px; margin-left:10px; margin-right:20px; width:100%;">
			<tr>
				<td style="width:20px;">
					@if(!empty($job->logo_file))
					<div class="job-corp-name">
						<figure>
							<img src="{{ $job->logo_file }}" alt="" width="70px" style="vertical-align: middle;">
						</figure>
					</div>
					@endif
				</td>
				<td>
@php
	$ranking = $job->getCompanyRanking();
	$total_rate = $ranking->total_rate;
	$total_point = $ranking->total_point;
@endphp
					<p class="expand-name"><a href="/company/{{ $job->company_id }}" style="text-decoration:underline;">{{ $job->company_name }}</a><p>
					<div style="text-align: center; display:flex; margin-top:auto; margin-bottom:auto;">
						<p style="font-size:16px;">
							<span class="star5_rating" style="--rate: {{ $total_rate . '%' }};font-size:20px;"></span>
							　総合評価　<b>{{ number_format($total_point, 2) }}</b>
						</p>
					</div>
				</td>
			</tr>
		</table>
		<hr>

{{-- END ジョブフォーマットヘッダ --}}
