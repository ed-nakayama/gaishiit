<table>
	<thead>
		<tr>
			<th>企業ID</th>
			<th>会社名</th>
			<th>部署名</th>
			<th>募集番号</th>
			<th>ジョブタイトル</th>
			<th>募集内容</th>
			<th>勤務地詳細</th>
		</tr>
	</thead>
	<tbody>
		@foreach ($delList as $job)
			<tr>
				<td>{{ $job['comp_id'] }}</td>
				<td>{{ $job['comp_name'] }}</td>
				<td>{{ $job['unit_name'] }}</td>
				<td>{{ $job['job_code'] }}</td>
				<td>{{ $job['job_title'] }}</td>
				<td>{{ $job['job_detail'] }}</td>
				<td>{{ $job['working_place'] }}</td>
			</tr>
		@endforeach
	</tbody>
</table>
