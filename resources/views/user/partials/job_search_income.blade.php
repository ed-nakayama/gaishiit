{{-- エリアから求人を探す --}}
<style>
.job-check-box-btn label {
  cursor: pointer; display: inline-block; line-height: 2;
}
.job-check-box-btn label input {
  display: none;
}
.job-check-box-btn label span {
  color: #4AA5CE;
  font-size: 14px;
  border: 1px solid #4AA5CE;
  border-radius: 20px;
  padding: 5px 20px;
}

.job-item {
  background: #fff;
  border: 4px solid #E5AF24;
  border-radius: 20px;
  overflow: hidden;
  margin-top: 10px;
}
.area_box {
  margin: 10px 10px 10px 10px;
}

</style>


{{-- 年収から求人を探す --}}
	<div class="con-wrap">
		<h2>年収から求人を探す</h2>
		<div class="job-item">
			<div class="area_box">
				<div class="form-wrap">
					<div class="form-block">
						<div class="form-inner">
							<div class="job-check-box-btn">
								@foreach ($incomeList as $income)
									<label style="margin-bottom:10px;">
										<a href="/job/list/income{{ $income->id }}"><span>{{ $income->name }}</span></a>
									</label>
								@endforeach
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
{{-- END 年収から求人を探す --}}

