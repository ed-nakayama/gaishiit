<table>
	<thead>
		<tr>
			<th>SFDC ID</th>
			<th>企業ID</th>
			<th>会社名</th>
			<th>部署名</th>
			<th>職種名</th>
			<th>募集番号</th>
			<th>ジョブタイトル</th>
			<th>募集内容_1</th>
			<th>募集内容_2</th>
			<th>募集内容_3</th>
			<th>募集内容_4</th>
			<th>募集内容_5</th>
			<th>勤務地</th>
			<th>登録日</th>
			<th>エージェント用</th>
			<th>URL</th>
			<th>Event/Job</th>
			<th>エラー理由</th>
		</tr>
	</thead>
	<tbody>
		@foreach ($eventList as $job)
			<tr>
				<td>{{ $job['salesforce_id'] }}</td>
				<td>{{ $job['comp_id'] }}</td>
				<td>{{ $job['comp_name'] }}</td>
				<td>{{ $job['unit_name'] }}</td>
				<td>{{ $job['cat_name'] }}</td>
				<td>{{ $job['job_id'] }}</td>
				<td>{{ $job['job_title'] }}</td>
				<td>{{ $job['job_detail'] }}</td>
				<td>{{ $job['job_detail_2'] }}</td>
				<td>{{ $job['job_detail_3'] }}</td>
				<td>{{ $job['job_detail_4'] }}</td>
				<td>{{ $job['job_detail_5'] }}</td>
				<td>{{ $job['working_place'] }}</td>
				<td>{{ $job['register_date'] }}</td>
				<td>{{ $job['agent'] }}</td>
				<td>{{ $job['url'] }}</td>
				<td>Event</td>
				<td>{{ $job['reason'] }}</td>
			</tr>
		@endforeach
	</tbody>
</table>
