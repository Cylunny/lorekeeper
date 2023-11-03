<li class="list-group-item">
    <a class="card-title h5 collapse-title"  data-toggle="collapse" href="#redeemTitle">Unlock Title</a>
    <div id="redeemTitle" class="collapse">
        {!! Form::hidden('tag', $tag->tag) !!}

        <p>
            This action is not reversible. This will unlock a random Title from the following list for your account.
            <br>
            Please be careful to not select a higher quantity to unlock than options listed below.
        </p>

        <p class="mb-0"><strong>Possible Results:</strong></p>
        <div class="row mb-2">
            @if(is_array($tag->getData()) && count($tag->getData()))
                @foreach($tag->getData() as $loot)
                    <div class="col-md-3" style="{{ Auth::user()->hasTitle($loot->rewardable_id) ? 'text-decoration: line-through; opacity:0.5;' : '' }}">{!! App\Models\Character\CharacterTitle::find($loot->rewardable_id)->displayName !!}</div>
                @endforeach
            @else
                @foreach(App\Models\Character\CharacterTitle::orderBy('name')->where('is_user_selectable', 0)->get() as $loot)
                    <div class="col-md-3" style="{{ Auth::user()->hasTitle($loot->id) ? 'text-decoration: line-through; opacity:0.5;' : '' }}">{!! $loot->displayName !!}</div>
                @endforeach
            @endif
        </div>
        <p>
            Crossed out results above mean that you already have them.
            <br>
            If there are no Titles that aren't crossed out, you have all Titles that can be found via this rewarding item!
        </p>

        <div class="text-right">
            {!! Form::button('Redeem', ['class' => 'btn btn-primary', 'name' => 'action', 'value' => 'act', 'type' => 'submit']) !!}
        </div>
    </div>
</li>