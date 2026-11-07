@if ($paginator->hasPages())
    @php($nama = $paginator->getPageName())
    <nav aria-label="Pagination" class="d-flex flex-wrap gap-1">
        <button type="button" class="btn-page" wire:click="previousPage('{{ $nama }}')" @disabled($paginator->onFirstPage())>‹ Sebelumnya</button>

        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="btn-page border-0 bg-transparent text-secondary">{{ $element }}</span>
            @elseif (is_array($element))
                @foreach ($element as $halaman => $url)
                    <button type="button" wire:key="hal-{{ $halaman }}"
                            class="btn-page {{ $halaman == $paginator->currentPage() ? 'active' : '' }}"
                            wire:click="gotoPage({{ $halaman }}, '{{ $nama }}')"
                            aria-label="Halaman {{ $halaman }}"
                            @if ($halaman == $paginator->currentPage()) aria-current="page" @endif>{{ $halaman }}</button>
                @endforeach
            @endif
        @endforeach

        <button type="button" class="btn-page" wire:click="nextPage('{{ $nama }}')" @disabled(! $paginator->hasMorePages())>Berikutnya ›</button>
    </nav>
@endif
