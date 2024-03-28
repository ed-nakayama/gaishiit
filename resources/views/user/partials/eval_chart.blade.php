{{-- チャート $my_count $comp $ranking  --}}

			<div class="ttl">
				<h2>社員による会社評価スコア</h2>
			</div>


			<div class="canClass">
				<div>
					<canvas id="myRadarChart"></canvas>
				</div>
				<div style="text-align: center;">
					<p class="txt" style="font-size:16px;font-weight: bold;">総合評価</p>　<b>{{ number_format($ranking->total_point, 2) }}</b>
					<p class="txt" style="font-size:12px;">回答者数 {{ $ranking->answer_count }}人</p>
					<p><span class="star5_rating"  style="--rate: {{ $ranking->total_rate . '%' }};"></span></p>
					
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
				</div>
			</div>
			
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
				['リモート勤務',         '{{ number_format($ranking->remote_point,     1) }}' ],
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

