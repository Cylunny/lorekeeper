@extends('admin.layout')

@section('admin-title') Site Design @endsection

@section('admin-content')
{!! breadcrumbs(['Admin Panel' => 'admin', 'Site Design' => 'admin/design']) !!}

<h1>Site Design</h1>

<p>Here, you can change the base look of your lorekeeper.</p>

{!! Form::open(['url' => 'admin/design' ]) !!}

<h3> Design </h3>
<div class="form-group">
    {!! Form::label('design', 'Design') !!} 
    {!! Form::select('design', ['default'=>'Default', 'headerless' => 'Headerless', 'squished' => 'Squished', 'full-width' => 'Full Width'], $design->design ?? 'default', ['class' => 'form-control']) !!}
</div>
<p>
  <a class="btn btn-sm btn-secondary" data-toggle="collapse" href="#fontcollapse" role="button" aria-expanded="false" aria-controls="collapseExample">
    View Font Examples
  </a>
</p>
<div class="collapse" id="fontcollapse">
  <div class="card-body">
    @foreach($fonts as $font)
    <div class="row border border-bottom p-2">
        <b>{{ $font }}</b> 
        <span class="ml-auto" style="font-family: {{ $font }}; font-size: 20px;">Lorem ipsum dolor sit amet,</span> <span style="font-family: {{ $font }}; font-size: 20px;text-transform:uppercase;"> consectetur adipiscing elit.</span>
    </div>
    @endforeach
  </div>
</div>


<h3>Headings</h3>


<div class="form-group row">
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label('heading_font_family', 'Font Family') !!} 
            {!! Form::select('heading_font_family', $fonts, $design->heading_font_family ?? 'Roboto Condensed, serif', ['class' => 'form-control']) !!}
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label('heading_letter_spacing', 'Letter Spacing') !!} 
            {!! Form::text('heading_letter_spacing', $design->heading_letter_spacing ?? 0, ['class' => 'form-control']) !!}
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label('heading_text_transform', 'Text Transform') !!} 
            {!! Form::select('heading_text_transform', ['uppercase' => 'uppercase', 'lowercase' => 'lowercase', 'capitalize' => 'capitalize'], $design->heading_text_transform ?? 'uppercase', ['class' => 'form-control']) !!}
        </div>
    </div>
    <div class="col-md-3">
    <div class="form-group">
            {!! Form::label('heading_font_weight', 'Font Weight') !!} 
            {!! Form::text('heading_font_weight', $design->heading_font_weight ?? 800, ['class' => 'form-control']) !!}
        </div>
    </div>
</div>


<h3>Navigation</h3>
<div class="form-group row">
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label('navigation_font_family', 'Font Family') !!} 
            {!! Form::select('navigation_font_family', $fonts, $design->navigation_font_family ?? 'Roboto Condensed, serif', ['class' => 'form-control']) !!}
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label('navigation_letter_spacing', 'Letter Spacing') !!} 
            {!! Form::text('navigation_letter_spacing', $design->navigation_letter_spacing ?? 0, ['class' => 'form-control']) !!}
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label('navigation_text_transform', 'Text Transform') !!} 
            {!! Form::select('navigation_text_transform', ['uppercase' => 'uppercase', 'lowercase' => 'lowercase', 'capitalize' => 'capitalize'], $design->navigation_text_transform ?? 'uppercase', ['class' => 'form-control']) !!}
        </div>
    </div>
    <div class="col-md-3">
    <div class="form-group">
            {!! Form::label('navigation_font_weight', 'Font Weight') !!} 
            {!! Form::text('navigation_font_weight', $design->navigation_font_weight ?? 600, ['class' => 'form-control']) !!}
        </div>
    </div>
</div>


<h3>Sidebars</h3>
<div class="form-group row">
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label('sidebar_font_family', 'Font Family') !!} 
            {!! Form::select('sidebar_font_family', $fonts, $design->sidebar_font_family ?? 'Roboto Condensed, serif', ['class' => 'form-control']) !!}
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label('sidebar_letter_spacing', 'Letter Spacing') !!} 
            {!! Form::text('sidebar_letter_spacing', $design->sidebar_letter_spacing ?? 0, ['class' => 'form-control']) !!}
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label('sidebar_text_transform', 'Text Transform') !!} 
            {!! Form::select('sidebar_text_transform', ['uppercase' => 'uppercase', 'lowercase' => 'lowercase', 'capitalize' => 'capitalize'], $design->sidebar_text_transform ?? 'uppercase', ['class' => 'form-control']) !!}
        </div>
    </div>
    <div class="col-md-3">
    <div class="form-group">
            {!! Form::label('sidebar_font_weight', 'Font Weight') !!} 
            {!! Form::text('sidebar_font_weight', $design->sidebar_font_weight ?? 500, ['class' => 'form-control']) !!}
        </div>
    </div>
</div>


<h3>Body</h3>
<div class="form-group row">
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label('body_font_family', 'Font Family') !!} 
            {!! Form::select('body_font_family', $fonts, $design->body_font_family ?? 'Lato', ['class' => 'form-control']) !!}
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label('body_letter_spacing', 'Letter Spacing') !!} 
            {!! Form::text('body_letter_spacing', $design->body_letter_spacing ?? 0, ['class' => 'form-control']) !!}
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label('body_text_transform', 'Text Transform') !!} 
            {!! Form::select('body_text_transform', ['none' => 'none', 'uppercase' => 'uppercase', 'lowercase' => 'lowercase', 'capitalize' => 'capitalize'], $design->body_text_transform ?? 'capitalize', ['class' => 'form-control']) !!}
        </div>
    </div>
    <div class="col-md-3">
    <div class="form-group">
            {!! Form::label('body_font_weight', 'Font Weight') !!} 
            {!! Form::text('body_font_weight', $design->body_font_weight ?? 400, ['class' => 'form-control']) !!}
        </div>
    </div>
</div>


<div class="text-right">
    {!! Form::submit('Edit' , ['class' => 'btn btn-primary']) !!}
</div>
{!! Form::close() !!}

@endsection