<table>
	<thead>
		<tr>
			<th>Job ID</th>
			<th>募集内容</th>
		</tr>
	</thead>
	<tbody>
		@foreach ($jobList as $job)
			<tr>
				<td>{{ $job['id'] }}</td>
				<td>{{ $job['intro'] }}</td>
			</tr>
		@endforeach
	</tbody>
</table>
