@extends('layouts.comp.auth')

@section('content')

<head>
	@if ($int_type == '0')
		<title>カジュアル面談依頼｜{{ config('app.name', 'Laravel') }}</title>
	@elseif ($int_type == '1')
		<title>正式応募依頼｜{{ config('app.name', 'Laravel') }}</title>
	@endif
</head>


{{--@include('comp.member_activity')--}}

            <div class="mainContentsInner">

            <div class="mainContentsInner">
                <div class="mainTtl title-main">
					@if ($int_type == '0')
                	    <h2>カジュアル面談を打診</h2>
					@elseif ($int_type == '1')
                	    <h2>正式応募を打診</h2>
					@endif
                </div><!-- /.mainTtl -->
                
                <div class="containerContents">

                    <section class="secContents-mb">
                        <div class="secContentsInner">
	        	            {{ Form::open(['url' => '/comp/interview/request/store', 'name' => 'regform' , 'id' => 'regform']) }}
    		                {{ Form::hidden('user_id', $userDetail->id , ['class' => 'form-control' ] ) }}
    		                {{ Form::hidden('int_type', $int_type , ['class' => 'form-control' ] ) }}

                            <div class="containerUserDetail">
                                <div class="userDetailInner">

                                    <div class="userDetailMain">
                                        <p class="name">@if ($userDetail->open_flag == '1'){{ $userDetail->name }}@else{{ $userDetail->nick_name }}@endif</p>
                                        <p class="status">採用：<span class="off">@if (!empty($userComp)){{ $userComp->result_name }}@else未設定@endif</span></p>
                                    </div><!-- /.userDetailMain -->
                        		</div>

                        	</div>
                        </div>

						<div class="secContentsInner">
							<div class="modalTitle" style="margin-top: 20px;">
								<h2>部門とジョブを選択してください</h2>
							</div><!-- /.modalTitle -->
    
							<div class="formContainer mg-ajust-midashi">
 								<div class="item-name">
									<p>部門</p>
								</div><!-- /.item-name -->
								<div class="item-input">
									<div class="selectWrap">
										<select name="unit_id" id="unit_id"  class="select-no" >
											<option value=""></option>
											@foreach ($unitList as $unit)
												<option value="{{ $unit->id }}">{{ $unit->name }}</option>
											@endforeach
										</select>
									</div>
								</div>
							</div>

							<div class="formContainer mg-ajust-midashi">
								<div class="item-name">
									<p>ジョブ</p>
								</div><!-- /.item-name -->
									<div class="item-input">
									<div class="selectWrap">
										<select name="job_id"  id="job_id"  class="select-no">
											<option value=""></option>
											@foreach ($jobList as $job)
												<option value="{{ $job->id }}">{{ $job->name }}</option>
											@endforeach
										</select>
									</div>
									<ul class="oneRow">
										@error('job_id')
											<li><span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span></li>
										@enderror
									</ul>
								</div>
							</div>

							<div class="formContainer al-item-none mg-ajust">
								<div class="item-name">
									<p>メッセージ<span>*</span></p>
								</div><!-- /.item-name -->
								<div class="item-input">
									<textarea class="form-mt" name="msg" id="" cols="30" rows="10" placeholder="送信メッセージ">{{ old('msg') }}</textarea>
									<ul class="oneRow">
										@error('msg')
										<li><span class="invalid-feedback" role="alert" style="color:#ff0000;">{{ $message }}</span></li>
										@enderror
									</ul>
								</div><!-- /.item-input -->
							</div><!-- END formContainer -->

                            <div class="btnContainer">
                               <a href="javascript:regform.submit()" class="squareBtn btn-large">送信</a>
                            </div><!-- /.btn-container -->
                            {{ Form::close() }}
						</div><!-- /.modalInner -->

                    </section><!-- /.secContents-mb -->

                </div><!-- /.containerContents -->
            </div><!-- /.mainContentsInner -->
        </div><!-- /.mainContents -->


<script>

/////////////////////////////////////////////////////////
// 成功メッセージクリア
/////////////////////////////////////////////////////////
document.getElementById('unit_id').onchange = function(){

	const joblist = @json($jobList);

	jobs = document.getElementById("job_id");
	jobs.options.length = 0;

	for (let i = 0; i < joblist.length; i++) {
    	var op = document.createElement("option");
    	value = joblist[i];
    	if (value.unit_id == unit_id.value) {
    		op.value = value.id;
    		op.text = value.name;
    		jobs.appendChild(op);
  		}
  	}
  
}


</script>

@endsection

