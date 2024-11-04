<div class="mb-1">
        <div class="row">
                <div class="col-md col-4"><h5>Species</h5></div>
                <div class="col-md col-8">{!! $request->species ? $request->species->displayName : 'None Selected' !!}</div>
        </div>
        @if($request->subtype_id)
        <div class="row">
                <div class="col-md col-4"><h5>Subtype</h5></div>
                <div class="col-md col-8">
                @if($request->character->is_myo_slot && $request->character->image->subtype_id)
                    {!! $request->character->image->subtype->displayName !!}
                @else
                    {!! $request->subtype_id ? $request->subtype->displayName : 'None Selected' !!}
                @endif
                </div>
        </div>
        @endif
        <div class="row">
                <div class="col-md col-4"><h5>Rarity</h5></div>
                <div class="col-md col-8">{!! $request->rarity ? $request->rarity->displayName : 'None Selected' !!}</div>
        </div>
</div>
<h5>Traits</h5>
<div>
        @if($request->character && $request->character->is_myo_slot && $request->character->image->features)
                @foreach($request->character->image->features as $feature)
                    <div>@if($feature->feature->feature_category_id) <strong>{!! $feature->feature->category->displayName !!}:</strong> @endif {!! $feature->feature->displayName !!} @if($feature->data) ({{ $feature->data }}) @endif <span class="text-danger">*Required</span></div>
                @endforeach
        @endif
        @foreach($request->features as $feature)
                <div>@if($feature->feature->feature_category_id) <strong>{!! $feature->feature->category->displayName !!}:</strong> @endif {!! $feature->feature->displayName !!} @if($feature->data) ({{ $feature->data }}) @endif</div>
         @endforeach
</div>

@php 
$checklist = $request->species->checklist ?? null;
$subChecklist = $request->subtype->checklist ?? null;
@endphp

@if(isset($request->species))
    <hr>
    <h5>Species Approval Checklist</h5>
    {!! $checklist->parsed_description ?? 'None provided.' !!}
@endif

@if(isset($request->subtype))
    <hr>
    <h5>Subtype Approval Checklist</h5>
    {!! $subChecklist->parsed_description ?? 'None provided.' !!}
@endif