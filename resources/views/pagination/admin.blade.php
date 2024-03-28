@if ($paginator->hasPages())
    <nav>
        <ul class="pagerList">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                    <span aria-hidden="true"><span class="material-icons">&lt;</span></span>
                </li>
            @else
                <li class="page__btn">
                    <a href="{{ $paginator->previousPageUrl() }}"><span class="material-icons">&lt;</span></a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li  class="page__dots"><span>{{ $element }}</span></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page__numbers active"><span"><font color="red"><b>{{ $page }}</b></font></span></li>
                        @else
                            <li class="page__numbers"><a href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page__btn">
                    <a href="{{ $paginator->nextPageUrl() }}"><span class="material-icons">&gt;</span></a>
                </li>
            @else
                <li class="disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                    <span aria-hidden="true"><span class="material-icons">&gt;</span></span>
                </li>
            @endif
        </ul>
    </nav>
@endif
