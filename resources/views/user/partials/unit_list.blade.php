{{-- 部門一覧 $comp --}}
@php

	// 部署
	$unitList = App\Models\Unit::leftJoin('job_cats','units.job_cat_id','=','job_cats.id')
		->selectRaw('units.* ,job_cats.name as job_cat_name ')
		->where('units.company_id' , $comp->id)
		->where('units.open_flag' ,'1')
		->get();

@endphp

@isset($unitList[0])

	<div class="eval">
		<div class="inner">
			<h2>部門</h2>
			<ul>
				@foreach ($unitList as $unit)
				<li>
					<a href="/company/{{ $comp->id }}/unit/{{ $unit->id }}" style="font-size:16px;color:#4AA5CE;">{{ $unit->name }}</a>
				</li>
				 @endforeach

			</ul>
		</div>
	</div>

@endisset
{{-- END 部門一覧 --}}
