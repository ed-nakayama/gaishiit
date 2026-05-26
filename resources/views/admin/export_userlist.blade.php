<table>
	<thead>
		<tr>
			<th>ID</th>
			<th>氏名</th>
			<th>メール</th>
			<th>生年月日</th>
			<th>性別</th>
			<th>最終学歴</th>
			<th>勤務先（現在または在籍していた）</th>
			<th>職種</th>
			<th>事業部・部門</th>
			<th>役職</th>
			<th>職務内容</th>
			<th>過去3年の平均実績（Actual Earnings）</th>
			<th>理論年収（OTE）</th>
			<th>上記以外の過去の勤務先</th>
			<th>キャリアに関する希望</th>
			<th>非表示企業</th>
			<th>転職希望時期</th>
			<th>希望勤務地</th>
			<th>転職を希望する業種</th>
			<th>転職を希望する職種</th>
			<th>希望年収</th>
		</tr>
	</thead>
	<tbody>
		@foreach ($userList as $user)
			<tr>
				<td>{{ $user->id }}</td>
				<td>{{ $user->name }}@if (!empty($user->name2))　{{ $user->name2 }}@endif @if (!empty($user->name_kana))／@endif{{ $user->name_kana }}@if (!empty($user->name_kana2))　{{ $user->name_kana2 }}@endif</td>
				<td>{{ $user->email }}</td>
				<td>{{ $user->getBirthday() }}</td>
				<td>{{ $user->getSex() }}</td>
				<td>{{ $user->graduation }}</td>
				<td>{{ $user->company }}</td>
				<td>{{ $user->getCurrentJob() }}　{{ $user->occupation }}</td>
				<td>{{ $user->section }}</td>
				<td>{{ $user->job_title }}</td>
				<td>{!! nl2br(e($user->job_content)) !!}</td>
				<td>{{ $user->actual_income }} 万円</td>
				<td>{{ $user->ote_income }} 万円</td>
				<td>{{ $user->old_company }}</td>
				<td>{!! nl2br(e($user->request_carrier)) !!}</td>
				<td>{{ $user->getNoCompany() }}</td>
				<td>{{ $user->getChangeTime() }}</td>
				<td>{{ $user->getLocation() }}　{{ $user->else_location }}</td>
				<td>{{ $user->getBusDetail() }}</td>
				<td>{{ $user->getCatDetail() }}</td>
				<td>{{ $user->getIncome() }}</td>
			</tr>
		@endforeach
	</tbody>
</table>
