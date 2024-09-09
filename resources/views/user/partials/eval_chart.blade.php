{{-- チャート $my_count $comp $ranking  --}}

	<h2 class="company-details-title">社員による会社評価スコア</h2>
		<div class="company-details-column">
			<div class="company-details-chart" style="position: relative;">
				<canvas id="myRadarChart" style="width:75%;"></canvas>
			</div>

			<div class="company-details-chartlists">
				<dl class="company-details-chartlist">
					<dt>給与</dt>
					<dd>
						<div class="company-details-chartlist__rate">
							<span>{{ number_format($ranking->salary_point, 2) }}</span>
							<span class="star5_rating" style="--rate:  {{ $ranking->salary_rate . '%' }};"></span>
						</div>
						<div class="detail-link-button">
							<a href="/company/{{ $comp->id }}/salary" >{{ $ranking->salary_count }} 件の口コミを見る</a>
						</div>
					</dd>

					<dt>福利厚生</dt>
					<dd>
						<div class="company-details-chartlist__rate">
							<span>{{ number_format($ranking->welfare_point, 2) }}</span>
							<span class="star5_rating" style="--rate: {{ $ranking->welfare_rate . '%' }};"></span>
						</div>
						<div class="detail-link-button">
							<a href="/company/{{ $comp->id }}/welfare" >{{ $ranking->welfare_count }} 件の口コミを見る</a>
						</div>
					</dd>

					<dt>育成</dt>
					<dd>
						<div class="company-details-chartlist__rate">
							<span>{{ number_format($ranking->upbring_point, 2) }}</span>
							<span class="star5_rating" style="--rate: {{ $ranking->upbring_rate . '%' }};"></span>
						</div>
						<div class="detail-link-button">
							<a href="/company/{{ $comp->id }}/upbring" >{{ $ranking->upbring_count }} 件の口コミを見る</a>
						</div>
					</dd>

					<dt>法令遵守の意識</dt>
					<dd>
						<div class="company-details-chartlist__rate">
							<span>{{ number_format($ranking->compliance_point, 2) }}</span>
							<span class="star5_rating" style="--rate:  {{ $ranking->compliance_rate . '%' }};"></span>
						</div>
						<div class="detail-link-button">
							<a href="/company/{{ $comp->id }}/compliance" >{{ $ranking->compliance_count }} 件の口コミを見る</a>
						</div>
					</dd>

					<dt>社員のモチベーション</dt>
					<dd>
						<div class="company-details-chartlist__rate">
							<span>{{ number_format($ranking->motivation_point, 2) }}</span>
							<span class="star5_rating" style="--rate:  {{ $ranking->motivation_rate . '%' }};"></span>
						</div>
						<div class="detail-link-button">
							<a href="/company/{{ $comp->id }}/motivation" >{{ $ranking->motivation_count }} 件の口コミを見る</a>
						</div>
					</dd>

					<dt>ワークライフバランス</dt>
					<dd>
						<div class="company-details-chartlist__rate">
							<span>{{ number_format($ranking->work_life_point, 2) }}</span>
							<span class="star5_rating" style="--rate: {{ $ranking->work_life_rate . '%' }};"></span>
						</div>
						<div class="detail-link-button">
							<a href="/company/{{ $comp->id }}/worklife" >{{ $ranking->work_life_count }} 件の口コミを見る</a>
						</div>
					</dd>

					<dt>勤務体系</dt>
					<dd>
						<div class="company-details-chartlist__rate">
							<span>{{ number_format($ranking->remote_point, 2) }}</span>
							<span class="star5_rating" style="--rate: {{ $ranking->remote_rate . '%' }};"></span>
						</div>
						<div class="detail-link-button">
							<a href="/company/{{ $comp->id }}/remote" >{{ $ranking->remote_count }} 件の口コミを見る</a>
						</div>
					</dd>

					<dt>定年</dt>
					<dd>
						<div class="company-details-chartlist__rate">
							<span>{{ number_format($ranking->retire_point, 2) }}</span>
							<span class="star5_rating" style="--rate: {{ $ranking->retire_rate . '%' }};"></span>
						</div>
						<div class="detail-link-button">
							<a href="/company/{{ $comp->id }}/retirement" >{{ $ranking->retire_count }} 件の口コミを見る</a>
						</div>
					</dd>
				</dl>

			</div><!-- company-details-chartlists -->

		</div><!-- company-details-column -->
					@if ($my_count == 0)
						<div class="button-eval">
					@else
						<div class="button-eval2">
					@endif
						@if (Auth::guard('user')->check())
							<a href="/eval/regist?comp_id={{ $comp->id }}" >企業の評価をする</a>
						@else
							<a class="openModal button-modal" href="#modalLogin">企業の評価をする</a>
						@endif
					</div>

	<div><!-- company-details-column -->

{{-- END チャート --}}

<script>

@if (!empty($ranking))

	var ctx = document.getElementById("myRadarChart");
	var myRadarChart = new Chart(ctx, {
		//グラフの種類
		type: 'radar',
		//データの設定
		data: {
			labels: [
				['給与',                 '{{ number_format($ranking->salary_point,     1) }}' ],
				['福利厚生',             '{{ number_format($ranking->welfare_point,    1) }}' ],
				['育成',                 '{{ number_format($ranking->upbring_point,    1) }}' ],
				['法令遵守の意識',       '{{ number_format($ranking->compliance_point, 1) }}' ],
				['社員のモチベーション', '{{ number_format($ranking->motivation_point, 1) }}' ],
				['ワークライフバランス', '{{ number_format($ranking->work_life_point,  1) }}' ],
				['勤務体系',         '{{ number_format($ranking->remote_point,     1) }}' ],
				['定年',                 '{{ number_format($ranking->retire_point,     1) }}' ],
			],
			datasets: [{
				//グラフのデータ
				data: [
					'{{ number_format($ranking->salary_point,     1) }}',
					'{{ number_format($ranking->welfare_point,    1) }}',
					'{{ number_format($ranking->upbring_point,    1) }}',
					'{{ number_format($ranking->compliance_point, 1) }}',
					'{{ number_format($ranking->motivation_point, 1) }}',
					'{{ number_format($ranking->work_life_point,  1) }}',
					'{{ number_format($ranking->remote_point,     1) }}',
					'{{ number_format($ranking->retire_point,     1) }}',
				 ],
				// データライン
				borderColor: '#5D99FF',
				borderWidth: 1,
    		}],
  		},
		//オプションの設定
		options: {
			responsive: true,
			maintainAspectRatio: false,
			scales: {
				r: {
					//グラフの最小値・最大値
					min: 0,
					max: 5,
					//背景色
					backgroundColor: 'white',
					//グリッドライン
					grid: {
						color: '#CCCCCC',
					},
					//アングルライン
					angleLines: {
						color: '#CCCCCC',
					},
					//各項目のラベル
					pointLabels: {
						color: '#444444',
						font: {
							size: 12,
						}
					},
					ticks: {
						stepSize: 1,
					}
				},
			},
			plugins:{
				legend:{
					display:false // lavel 非表示
				}
			}
		}, 
	});

@endif

</script>

