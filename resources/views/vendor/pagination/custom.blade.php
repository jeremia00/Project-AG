@if ($paginator->hasPages())
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mt-4">
        <!-- Left Side: Showing X to Y of Z results -->
        <div class="text-muted fs-6">
            Showing <span class="fw-bold text-dark">{{ $paginator->firstItem() }}</span> to <span class="fw-bold text-dark">{{ $paginator->lastItem() }}</span> of <span class="fw-bold text-dark">{{ $paginator->total() }}</span> results
        </div>

        <!-- Right Side: Custom Sleek Pagination Controls -->
        <ul class="pagination-custom d-flex align-items-center gap-1 mb-0 list-unstyled">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link-custom opacity-30 text-muted"><i class="bi bi-chevron-left fs-6"></i></span>
                </li>
            @else
                <li class="page-item">
                    <a href="{{ $paginator->previousPageUrl() }}" class="page-link-custom text-dark"><i class="bi bi-chevron-left fs-6"></i></a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="page-item disabled"><span class="page-link-custom text-muted">{{ $element }}</span></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active">
                                <span class="page-link-custom active-page">{{ $page }}</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a href="{{ $url }}" class="page-link-custom text-dark">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a href="{{ $paginator->nextPageUrl() }}" class="page-link-custom text-dark"><i class="bi bi-chevron-right fs-6"></i></a>
                </li>
            @else
                <li class="page-item disabled">
                    <span class="page-link-custom opacity-30 text-muted"><i class="bi bi-chevron-right fs-6"></i></span>
                </li>
            @endif
        </ul>
    </div>
@endif
