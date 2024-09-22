@php $isBaseSet = false; @endphp
@foreach($b64Images as $id => $image)
    @if($image == null)
    <img src="#" class="creator-layer hide" style="max-width:100%;" id="group-{{$id}}"/>
    @else
    <img src="{{ $image }}" class="creator-layer" style="max-width:100%;" id="group-{{$id}}"/>
    @endif
@endforeach