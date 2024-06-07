@extends('admin.layout')

@section('admin-title')
    Forms & Polls
@endsection

@section('admin-content')
    {!! breadcrumbs(['Admin Panel' => 'admin', 'Forms & Polls' => 'admin/forms', 'Form Results' => '']) !!}

    <h1>
        Form Results by Respondent
    </h1>

    <div class="card mb-3">
        @include('forms._site_form_header')

        <div class="card-body">
            {{-- Always only one because pagination is one at a time, and I mainly need to reference the info on the first answer here --}}
            @php
                $userAnswer = $userAnswers->first()->first();
            @endphp
            {{-- First and last page links - would add to general pagination but only really want it on this page --}}
            <div class="d-flex">
                @if ($userAnswers->onFirstPage())
                    <span class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.first')">
                        <span class="page-link" aria-hidden="true">&laquo;</span>
                    </span>
                @else
                    <span class="page-item">
                        <a class="page-link" href="{{ \Request::url() }}" rel="prev" aria-label="@lang('pagination.first')">&laquo;</a>
                    </span>
                @endif
                {!! $userAnswers->render() !!}
                @if ($userAnswers->hasMorePages())
                    <span class="page-item">
                        <a class="page-link" href="{{ \Request::url() . '?page=' . $userAnswers->lastPage() }}" rel="last" aria-label="@lang('pagination.last')">&raquo;</a>
                    </span>
                @else
                    <span class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.last')">
                        <span class="page-link" aria-hidden="true">&raquo;</span>
                    </span>
                @endif
            </div>
            <h1>
                Submitted By {!! $form->is_anonymous ? 'Anonymous' : $userAnswer->user->displayName !!}
            </h1>
            <h5 class="mb-4">
                {!! format_date($userAnswers->first()->last()->created_at) !!} (Last Edited {!! pretty_date($userAnswer->created_at) !!})
            </h5>

            @include('forms._site_form_view', ['user' => $userAnswer->user, 'number' => $userAnswer->submission_number])

            <div class="d-flex">
                @if ($userAnswers->onFirstPage())
                    <span class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.first')">
                        <span class="page-link" aria-hidden="true">&laquo;</span>
                    </span>
                @else
                    <span class="page-item">
                        <a class="page-link" href="{{ \Request::url() }}" rel="prev" aria-label="@lang('pagination.first')">&laquo;</a>
                    </span>
                @endif
                {!! $userAnswers->render() !!}
                @if ($userAnswers->hasMorePages())
                    <span class="page-item">
                        <a class="page-link" href="{{ \Request::url() . '?page=' . $userAnswers->lastPage() }}" rel="last" aria-label="@lang('pagination.last')">&raquo;</a>
                    </span>
                @else
                    <span class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.last')">
                        <span class="page-link" aria-hidden="true">&raquo;</span>
                    </span>
                @endif
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    @parent
    <style>
        .pagination .page-item:first-child .page-link {
            border-radius: 0;
            margin-left: -1px;
        }

        .pagination .page-item:last-child .page-link {
            border-radius: 0;
        }
    </style>
@endsection
