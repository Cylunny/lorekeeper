@foreach($b64Images as $id => $image)
    <img src="{{ $image ?? '' }}" style="max-width:100%;" id="group-{{$id}}"/>
@endforeach