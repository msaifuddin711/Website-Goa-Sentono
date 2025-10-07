@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-center mt-16">
        <div class="flex justify-between flex-1 sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="relative inline-flex items-center px-6 py-3 text-sm font-bold text-gray-400 bg-gray-100 border-2 border-gray-200 cursor-default leading-5 rounded-full transition-all duration-300">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                    {!! __('pagination.previous') !!}
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="relative inline-flex items-center px-6 py-3 text-sm font-bold text-white bg-primary border-2 border-primary leading-5 rounded-full hover:bg-opacity-90 hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 active:scale-95">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                    {!! __('pagination.previous') !!}
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="relative inline-flex items-center px-6 py-3 ml-3 text-sm font-bold text-white bg-primary border-2 border-primary leading-5 rounded-full hover:bg-opacity-90 hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 active:scale-95">
                    {!! __('pagination.next') !!}
                    <svg class="w-4 h-4 ml-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </a>
            @else
                <span class="relative inline-flex items-center px-6 py-3 ml-3 text-sm font-bold text-gray-400 bg-gray-100 border-2 border-gray-200 cursor-default leading-5 rounded-full transition-all duration-300">
                    {!! __('pagination.next') !!}
                    <svg class="w-4 h-4 ml-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </span>
            @endif
        </div>

        <!-- Desktop Version -->
        <div class="hidden sm:flex sm:flex-col sm:items-center sm:space-y-6">
            <!-- Info Text -->
            <div class="text-center">
                <p class="text-sm text-accent font-medium leading-5">
                    Menampilkan
                    @if ($paginator->firstItem())
                        <span class="font-bold text-primary">{{ $paginator->firstItem() }}</span>
                        sampai
                        <span class="font-bold text-primary">{{ $paginator->lastItem() }}</span>
                    @else
                        <span class="font-bold text-primary">{{ $paginator->count() }}</span>
                    @endif
                    dari
                    <span class="font-bold text-primary">{{ $paginator->total() }}</span>
                    artikel
                </p>
            </div>

            <!-- Pagination Buttons -->
            <div class="flex items-center space-x-2">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}" class="relative inline-flex items-center justify-center w-12 h-12 text-gray-400 bg-gray-100 border-2 border-gray-200 cursor-default rounded-full transition-all duration-300">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="relative inline-flex items-center justify-center w-12 h-12 text-white bg-primary border-2 border-primary rounded-full hover:bg-opacity-90 hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 active:scale-95" aria-label="{{ __('pagination.previous') }}">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </a>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <span aria-disabled="true" class="relative inline-flex items-center justify-center w-12 h-12 text-accent font-bold bg-white border-2 border-gray-200 cursor-default rounded-full">
                            {{ $element }}
                        </span>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span aria-current="page" class="relative inline-flex items-center justify-center w-12 h-12 text-white font-bold bg-primary border-2 border-primary cursor-default rounded-full shadow-lg">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}" class="relative inline-flex items-center justify-center w-12 h-12 text-primary font-bold bg-white border-2 border-gray-200 rounded-full hover:border-primary hover:bg-cream hover:shadow-lg transform hover:-translate-y-1 transition-all duration-300 active:scale-95" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="relative inline-flex items-center justify-center w-12 h-12 text-white bg-primary border-2 border-primary rounded-full hover:bg-opacity-90 hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 active:scale-95" aria-label="{{ __('pagination.next') }}">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </a>
                @else
                    <span aria-disabled="true" aria-label="{{ __('pagination.next') }}" class="relative inline-flex items-center justify-center w-12 h-12 text-gray-400 bg-gray-100 border-2 border-gray-200 cursor-default rounded-full transition-all duration-300">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </span>
                @endif
            </div>
        </div>
    </nav>
@endif