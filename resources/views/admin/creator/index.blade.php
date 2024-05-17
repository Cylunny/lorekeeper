@extends('admin.layout')

@section('admin-title') Character Creators @endsection

@section('admin-content')
{!! breadcrumbs(['Admin Panel' => 'admin', 'Character Creators' => 'admin/data/creators']) !!}

<h1>Character Creators</h1>

<p>You can create new character creators here.</p>

<div class="text-right mb-3"><a class="btn btn-primary" href="{{ url('admin/data/creators/create') }}"><i class="fas fa-plus"></i> Create Character Creator</a></div>
@if(!count($creators))
    <p>No creator found.</p>
@else
    {!! $creators->render() !!}
      <div class="row ml-md-2">
        <div class="d-flex row flex-wrap col-12 pb-1 px-0 ubt-bottom">
          <div class="col-12 col-md-5 font-weight-bold">Name</div>
          <div class="col-6 col-md-3 font-weight-bold">Created At</div>
          <div class="col-6 col-md-3 font-weight-bold">Last Edited</div>
        </div>
        @foreach($creators as $creator)
        <div class="d-flex row flex-wrap col-12 mt-1 pt-2 px-0 ubt-top">
          <div class="col-12 col-md-5">
              @if(!$creator->is_visible)
              <i class="fas fa-eye-slash mr-1" data-toggle="tooltip" title="This creator is hidden."></i>
              @endif
              <a href="{{ $creator->url }}">{{ $creator->name }}</a>
          </div>
          <div class="col-6 col-md-3">{!! pretty_date($creator->created_at) !!}</div>
          <div class="col-6 col-md-3">{!! pretty_date($creator->updated_at) !!}</div>
          <div class="col-12 col-md-1 text-right"><a href="{{ url('admin/data/creators/edit/'.$creator->id) }}" class="btn btn-primary py-0 px-2 w-100">Edit</a></div>
        </div>
        @endforeach
      </div>
    {!! $creators->render() !!}

    <div class="text-center mt-4 small text-muted">{{ $creators->total() }} result{{ $creators->total() == 1 ? '' : 's' }} found.</div>

@endif

@endsection
