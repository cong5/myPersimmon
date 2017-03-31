@if ($paginator->hasPages())
    <!--page nav-->
    <div id="pager">
        <div class="row">
            <div class="small-12 large-8 large-centered columns pager-inner">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    {{--<a href="javascript:void(0);">没有了</a>--}}
                @else
                    <a rel="prev" class="pager-btn pager-previous" href="{{ $paginator->previousPageUrl() }}">&larr; 最近</a>
                @endif

                <span class="pager-number">{{ $paginator->currentPage() }} / {{ $paginator->total() }}</span>

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <a rel="next" class="pager-btn pager-next" href="{{ $paginator->nextPageUrl() }}">更早 &rarr;</a>
                @else
                   {{-- <a href="javascript:void(0);">没有了</a>--}}
                @endif
            </div>
        </div>
    </div>
@endif