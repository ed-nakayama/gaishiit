{{-- カテゴリ別クチコミボタン $cat $comp $ranking --}}

			<div class="ttl">
				<h2>カテゴリ別クチコミ</h2>
			</div>
			<div class="eval">
				<div class="inner">
					<ul>
						
						<li @if ($cat['sel'] == '1') style="background:#4AA5CE;" @endif>
							<div class="button-chart2">
								<a href="/company/{{ $comp->id }}/salary" @if ($cat['sel'] == '1') style="color:#ffffff;" @endif>給与（{{ $ranking->salary_count }}件）</a>
							</div>
						</li>
						<li @if ($cat['sel'] == '2') style="background:#4AA5CE;" @endif>
							<div class="button-chart2">
								<a href="/company/{{ $comp->id }}/welfare" @if ($cat['sel'] == '2') style="color:#ffffff;" @endif>福利厚生（{{ $ranking->welfare_count }}件）</a>
							</div>
						</li>
						<li @if ($cat['sel'] == '3') style="background:#4AA5CE;" @endif>
							<div class="button-chart2">
								<a href="/company/{{ $comp->id }}/upbring" @if ($cat['sel'] == '3') style="color:#ffffff;" @endif>育成（{{ $ranking->upbring_count }}件）</a>
							</div>
						</li>
						<li @if ($cat['sel'] == '4') style="background:#4AA5CE;" @endif>
							<div class="button-chart2">
								<a href="/company/{{ $comp->id }}/compliance" @if ($cat['sel'] == '4') style="color:#ffffff;" @endif>法令遵守の意識（{{ $ranking->compliance_count }}件）</a>
							</div>
						</li>
						<li @if ($cat['sel'] == '5') style="background:#4AA5CE;" @endif>
							<div class="button-chart2">
								<a href="/company/{{ $comp->id }}/motivation" @if ($cat['sel'] == '5') style="color:#ffffff;" @endif>社員のモチベーション（{{ $ranking->motivation_count }}件）</a>
							</div>
						</li>
						<li @if ($cat['sel'] == '6') style="background:#4AA5CE;" @endif>
							<div class="button-chart2">
								<a href="/company/{{ $comp->id }}/worklife" @if ($cat['sel'] == '6') style="color:#ffffff;" @endif>ワークライフバランス（{{ $ranking->work_life_count }}件）</a>
							</div>
						</li>
						<li @if ($cat['sel'] == '7') style="background:#4AA5CE;" @endif>
							<div class="button-chart2">
								<a href="/company/{{ $comp->id }}/remote" @if ($cat['sel'] == '7') style="color:#ffffff;" @endif>勤務体系（{{ $ranking->remote_count }}件）</a>
							</div>
						</li>
						<li @if ($cat['sel'] == '8') style="background:#4AA5CE;" @endif>
							<div class="button-chart2">
								<a href="/company/{{ $comp->id }}/retirement" @if ($cat['sel'] == '8') style="color:#ffffff;" @endif>定年（{{ $ranking->retire_count }}件）</a>
							</div>
						</li>
					</ul>
				</div>
			</div>

{{-- END カテゴリ別クチコミボタン --}}
