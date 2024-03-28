@extends('layouts.admin.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Mypage') }}{{ __('Admin') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<div id="root"></div>
<script type="text/babel">

const { useState } = React　//cdnの利用の場合必須

function Modal({show, setShow, content}) {
  const closeModal = () => {
    setShow(false)
  }
  if (show) {
    return (
      <div id="overlay" onClick={closeModal}>
        <div id="content" onClick={(e) => e.stopPropagation()}>
          <p>{content}</p>
          <button onClick={closeModal}>Close</button>
        </div>
      </div >
    )
  } else {
    return null;
  }
}


function App() {
   const [show, setShow] = useState(false)
  return (
<div>
       <button onClick={() => setShow(true)}>Click</button>
       <Modal show={show} setShow={setShow} content="Appから内容を変更できます"/>
  </div>
  )

}

ReactDOM.render(<App />, document.getElementById('root'))

  </script>
@endsection