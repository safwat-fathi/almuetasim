@if ($paginator->hasPages())
    <nav role="navigation" aria-label="التنقل بين الصفحات" class="w-full">
        <div class="flex items-center justify-center">
            <div class="join ">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <span class="btn btn-sm join-item btn-disabled" aria-disabled="true" aria-label="السابق">السابق</span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="btn btn-sm join-item" aria-label="السابق">السابق</a>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($elements as $element)
                    {{-- Separator --}}
                    @if (is_string($element))
                        <span class="btn btn-sm join-item btn-disabled" aria-hidden="true">{{ $element }}</span>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span class="btn btn-sm join-item btn-primary" aria-current="page">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="btn btn-sm join-item" aria-label="الانتقال إلى الصفحة {{ $page }}">{{ $page }}</a>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="btn btn-sm join-item" aria-label="التالي">التالي</a>
                @else
                    <span class="btn btn-sm join-item btn-disabled" aria-disabled="true" aria-label="التالي">التالي</span>
                @endif
            </div>
        </div>

        <div class="mt-2 text-sm text-base-content/60 text-center">
            @if ($paginator->firstItem())
                عرض
                <span class="font-medium">{{ $paginator->firstItem() }}</span>
                إلى
                <span class="font-medium">{{ $paginator->lastItem() }}</span>
                من
                <span class="font-medium">{{ $paginator->total() }}</span>
                نتائج
            @else
                عرض <span class="font-medium">{{ $paginator->count() }}</span> نتائج
            @endif
        </div>
    </nav>
@endif
