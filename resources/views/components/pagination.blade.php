@if ($paginator->onFirstPage())
<span class="badge bg-gradient-light text-dark"><i class="fa-solid fa-chevron-left"></i></span>
@else
<span class="badge bg-gradient-dark"><a class="text-white" href="{{ $paginator->previousPageUrl() }}"><i class="fa-solid fa-chevron-left"></i></a></span>
@endif 
@foreach ($elements as $element)
    @if (is_string($element))
        <span class="badge bg-gradient-light mx-1"><a class="text-dark" href="">{{ $element }}</a></span>
    @endif

    @if (is_array($element))
        @foreach ($element as $page => $url)
            @if ($page == $paginator->currentPage())
                <span class="badge bg-gradient-dark mx-1"><a class="text-white" href="">{{ $page }}</a></span>
            @else
                <span class="badge bg-gradient-light mx-1"><a class="text-dark" href="{{ $url }}">{{ $page }}</a></span>
            @endif
        @endforeach
    @endif
@endforeach

@if($paginator->hasMorePages())
</span><span class="badge bg-gradient-dark"><a class="text-white" href="{{ $paginator->nextPageUrl() }}"><i class="fa-solid fa-chevron-right"></i></a></span>
@else
</span><span class="badge bg-gradient-light text-dark"><i class="fa-solid fa-chevron-right"></i></span>
@endif