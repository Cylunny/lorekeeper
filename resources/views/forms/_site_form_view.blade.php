@if ($number && $number <= $form->latestSubmissionNumber())
    <h2> View Form Submission {{ $number }}</h2>
    <div class="border rounded p-4">
        @foreach ($form->questions as $question)
            <h5>{{ $question->question }}</h5>
            @if ($question->options->count() > 0)
                @foreach ($question->options as $option)
                    <div class="form-group mb-0">
                        <label>{{ Form::radio($question->id,$option->id,$question->answers->where('user_id', $user->id)->where('submission_number', $number)->first()?->option_id == $option->id? true: false,['class' => 'mr-1', 'disabled' => 'disabled']) }} {{ $option->option }}</label>
                    </div>
                @endforeach
            @else
                {!! Form::textarea(
                    $question->id,
                    $number
                        ? $question->answers->where('user_id', $user->id)->where('submission_number', $number)->first()?->answer
                        : null,
                    ['class' => 'form-control', 'disabled' => 'disabled'],
                ) !!}
            @endif
        @endforeach
        {!! Form::hidden('action', 'edit') !!}
        {!! Form::hidden('submission_number', $number) !!}
    </div>
    <div class="text-right mt-2">
        This form is not editable.
    </div>
@else
    <h2>View Form Submissions </h2>
    @foreach ($form->userAnswers($user) as $submission => $answers)
        <div class="row">
            <div class="col-8"> Submission {{ $submission }} </div>
            <div class="col-4"> <a class="btn btn-primary btn-sm float-right" href="?action=edit&number={{ $submission }}">View</a> </div>
        </div>
        <hr>
    @endforeach
@endif
