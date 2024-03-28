{{-- 求人一覧ボタン $comp --}}
<style>
.button-flex {
  display: flex;
  justify-content: center;
  margin: 32px auto;
}

.button-flex a {
  display: inline-block;
  font-size: 1.8rem;
  font-weight: 600;
  color: #fff;
  background: #4AA5CE;
  padding: 10px 20px;
  border-radius: 30px;
  max-width: 380px;
  width: 100%;
  text-align: center;
}

.button-flex a:nth-child(n+2) {
  margin-left: 30px;
}

</style>

	<div class="con-wrap">
			<div class="button-flex">
				<a href="/job/list">求人一覧</a>
			</div>
	</div>

{{-- END 求人一覧ボタン --}}
