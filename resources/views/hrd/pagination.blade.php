@if ($paginator->hasPages())
    <div class="hrd-pagination">
        <span>Menampilkan <strong>{{ $paginator->firstItem() ?? 0 }}–{{ $paginator->lastItem() ?? 0 }}</strong> dari <strong>{{ $paginator->total() }}</strong> data</span>
        <div>
            @if ($paginator->onFirstPage())
                <button type="button" disabled aria-label="Halaman sebelumnya"><i class="bi bi-chevron-left"></i></button>
            @else
                <a href="{{ $paginator->previousPageUrl() }}#{{ $view }}" aria-label="Halaman sebelumnya"><i class="bi bi-chevron-left"></i></a>
            @endif
            @foreach ($paginator->getUrlRange(max(1, $paginator->currentPage() - 2), min($paginator->lastPage(), $paginator->currentPage() + 2)) as $page => $url)
                <a href="{{ $url }}#{{ $view }}" @class(['current' => $page === $paginator->currentPage()]) aria-label="Halaman {{ $page }}" @if ($page === $paginator->currentPage()) aria-current="page" @endif>{{ $page }}</a>
            @endforeach
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}#{{ $view }}" aria-label="Halaman berikutnya"><i class="bi bi-chevron-right"></i></a>
            @else
                <button type="button" disabled aria-label="Halaman berikutnya"><i class="bi bi-chevron-right"></i></button>
            @endif
        </div>
    </div>
@endif
