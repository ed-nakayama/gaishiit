@unless ($breadcrumbs->isEmpty())

<style>
    .breadcrumb {
		list-style: none;
		background-color:#FAFAFA;
    }

    .breadcrumb-item+.breadcrumb-item::before {
    	display: inline;/*横に並ぶように*/
        list-style: none;
        margin-left: 12px;
		content: '';
    }

    .breadcrumb li {
		display: inline;
		list-style: none;
	}

    .breadcrumb li:after {
    /* >を表示*/
    font-family: "Font Awesome 5 Free";
    content: ' >';
    font-weight: 900;
    padding-left: 5px;
    /*color: gray;*/
	}

	.breadcrumb li:last-child:before {
	    content: '';
	}

	.breadcrumb li:last-child:after {
	    content: '';
	}

	.breadcrumb li a {
	    text-decoration: none;
	    /*color: gray;*/
	}

	.breadcrumb li a:hover {
	    text-decoration: underline;
	}


</style>

    <ol class="breadcrumb">
        @foreach ($breadcrumbs as $breadcrumb)

            @if (!is_null($breadcrumb->url) && !$loop->last)
                <li class="breadcrumb-item"><a href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a></li>
            @else
                <li class="breadcrumb-item active">{{ $breadcrumb->title }}</li>
            @endif

        @endforeach
    </ol>

@endunless