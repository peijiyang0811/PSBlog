<small>第{{$paginator->currentPage()}}页 / 共 {{$paginator -> lastPage()}} 页， 共 {{$paginator->total()}} 条数据 | 本页 {{$paginator -> count()}} 条数据 </small>
@if ($paginator->hasPages())
    <ul class="am-pagination tpl-pagination">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="am-disabled"><span>首页</span></li>
        @else
            <li><a href="{{ $paginator->previousPageUrl() }}" rel="prev">上一页</a></li>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <li class="disabled"><span>{{ $element }}</span></li>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="am-active"><span>{{ $page }}</span></li>
                    @else
                        <li><a href="{{ $url }}">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li><a href="{{ $paginator->nextPageUrl() }}" rel="next">下一页</a></li>
        @else
            <li class="am-disabled"><span>尾页</span></li>
        @endif
    </ul>
@endif
