<h3>Titles</h3>Titles

<a data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
    {!! Form::checkbox('all_titles', 1, !(is_array($tag->getData())), ['class' => 'form-check-input', 'data-toggle' => 'toggle', 'data-on' => 'Take from all unlockable Titles', 'data-off' => 'Use specific Titles']) !!}
</a>
<br /><br />
  <div class="collapse {{ (is_array($tag->getData())) ? 'show' : '' }}" id="collapseExample">
    <div class="card card-body">
        <div class="text-right mb-3">
            <a href="#" class="btn btn-outline-info" id="addLoot">Add Title</a>
        </div>
        <table class="table table-sm" id="lootTable">
            <thead>
                <tr>
                    <th width="50%">Title</th>
                    <th width="10%"></th>
                </tr>
            </thead>
            <tbody id="lootTableBody">
                @if(is_array($tag->getData()))
                    @foreach($tag->getData() as $loot)
                        <tr class="loot-row">
                            <td class="loot-row-select">
                                    {!! Form::select('rewardable_id[]', $titles, $loot->rewardable_id, ['class' => 'form-control title-select selectize', 'placeholder' => 'Select Title']) !!}
                            </td>
                            <td class="text-right"><a href="#" class="btn btn-danger remove-loot-button">Remove</a></td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
  </div>

  <hr>