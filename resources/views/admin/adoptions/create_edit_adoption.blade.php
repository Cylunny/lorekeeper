@extends('admin.layout')

@section('admin-title') Adoptions @endsection

@section('admin-content')
{!! breadcrumbs(['Admin Panel' => 'admin', 'Adoptions' => 'admin/data/adoptions',  'Edit Adoption' => 'admin/data/adoptions/edit/'.$adoption->id]) !!}

<h1>
    Edit Adoption
</h1>

{!! Form::open(['url' => 'admin/data/adoptions/edit/'.$adoption->id, 'files' => true]) !!}

<h3>Basic Information</h3>

<div class="form-group">
    {!! Form::label('Name') !!}
    {!! Form::text('name', $adoption->name, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('Adoption Image (Optional)') !!} {!! add_help('This image is used on the adoption index and on the adoption page as a header.') !!}
    <div>{!! Form::file('image') !!}</div>
    <div class="text-muted">Recommended size: None (Choose a standard size for all adoption images)</div>
    @if($adoption->has_image)
        <div class="form-check">
            {!! Form::checkbox('remove_image', 1, false, ['class' => 'form-check-input']) !!}
            {!! Form::label('remove_image', 'Remove current image', ['class' => 'form-check-label']) !!}
        </div>
    @endif
</div>

<div class="form-group">
    {!! Form::label('Description (Optional)') !!}
    {!! Form::textarea('description', $adoption->description, ['class' => 'form-control wysiwyg']) !!}
</div>

<div class="form-group">
    {!! Form::checkbox('is_active', 1, $adoption->id ? $adoption->is_active : 1, ['class' => 'form-check-input', 'data-toggle' => 'toggle']) !!}
    {!! Form::label('is_active', 'Set Active', ['class' => 'form-check-label ml-3']) !!} {!! add_help('If turned off, the adoption will not be visible to regular users.') !!}
</div>


<h3> Adoption Price over time</h3>

@include('admin.adoptions._price_container')


<div class="text-right">
    {!! Form::submit('Edit', ['class' => 'btn btn-primary']) !!}
</div>

{!! Form::close() !!}

<hr>

<h3>Current Adoption Stock</h3>
@foreach($adoption->stock as $stocks)
<div class="card mb-2">
    <div class="card-body row p-3">
        <div class="col col-form-label">
            <strong><a href="{{ $stocks->character->url }}"> {!! $stocks->character->displayname !!}</a> (<a href="Species">{!! $stocks->character->image->species->name !!}</a>)</strong>
        </div>
        <div class="col col-form-label">
            @if($stocks->currency == '[]') No cost added
            @else
            @foreach($stocks->currency as $currency)
            {!! $currency->cost !!}
            {!! $currency->currency->name !!},
            @endforeach
            @endif
        </div>
        <div class="col col-form-label">
            @if($stocks->use_character_bank == 1)
            <i class="fas fa-paw" data-toggle="tooltip" title="Can be purchased using Character Bank"></i> 
            @endif
            @if($stocks->use_user_bank == 1) 
            <i class="fas fa-user" data-toggle="tooltip" title="Can be purchased using User Bank"></i> 
            @endif
        </div>
        <div class="col text-right">
            <a href="{{ url('admin/data/stock/edit/'.$stocks->id) }}" class="btn btn-dark">Edit Adoptable</a>
        </div>
    </div>
</div>
@endforeach
<a href="{{ url('admin/data/stock/create') }}" class="btn btn-primary">Create Adopt Stock</a>
@endsection

@section('scripts')
    @parent
    <script>
        $(document).ready(function() {

            attachRemoveListener($('#priceContainer .remove-price-button'));

            var prices = $('#priceContainer');
            var priceRow = $('#priceContainer').find('.card.hide');

            $('#addPrice').on('click', function(e) {
                e.preventDefault();
                var priceId = Math.random().toString(16).slice(2)

                //setup clone and add its unique id
                var clone = priceRow.clone();
                clone.removeClass('hide');
                prices.append(clone);
                attachRemoveListener(clone.find('.remove-price-button'));
                var priceInput = clone.find('.price input');
                priceInput.attr("name", "prices[" + priceId + "]");
                var daysInput = clone.find('.days input');
                daysInput.attr("name", "days[" + priceId + "]");
                var currencyInput = clone.find('.currency select');
                currencyInput.attr("name", "currency_id[" + priceId + "]");
            });

            function attachRemoveListener(node) {
                node.on('click', function(e) {
                    e.preventDefault();
                    $(this).parent().parent().remove();
                });
            }
        });
    </script>
@endsection
