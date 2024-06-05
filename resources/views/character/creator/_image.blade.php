@php $isBaseSet = false; @endphp
@foreach($b64Images as $image)
    @if(!$isBaseSet)
        <img src="{{ $image }}" class="creator-base" style="max-width:100%;" />
        @php $isBaseSet = true; @endphp
    @else
        <img src="{{ $image }}" class="creator-layer" style="max-width:100%;" />
    @endif
@endforeach