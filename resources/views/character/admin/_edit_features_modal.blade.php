{!! Form::open(['url' => 'admin/character/image/'.$image->id.'/traits']) !!}
    <div class="form-group">
        {!! Form::label('Species') !!}
        {!! Form::select('species_id', $specieses, $image->species_id, ['class' => 'form-control', 'id' => 'species']) !!}
    </div>

    <div class="form-group" id="subtypes">
        {!! Form::label('Subtype (Optional)') !!}
        {!! Form::select('subtype_id', $subtypes, $image->subtype_id, ['class' => 'form-control', 'id' => 'subtype']) !!}
    </div>

    <div class="form-group">
        {!! Form::label('Character Rarity') !!}
        {!! Form::select('rarity_id', $rarities, $image->rarity_id, ['class' => 'form-control']) !!}
    </div>

    <div class="row no-gutters">
        <div class="col-md-6 pr-2">
            <div class="form-group">
                {!! Form::label('Character Title') !!}
                {!! Form::select('title_id', $titles, $image->title_id ?? (isset($image->title_data) ? 'custom' : null), ['class' => 'form-control', 'id' => 'charTitle']) !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group" id="titleOptions">
                {!! Form::label('Extra Info/Custom Title (Optional)') !!} {!! add_help('If \'custom title\' is selected, this will be displayed as the title. If a preexisting title is selected, it will be displayed in addition to it.'.(Settings::get('character_title_display') ? ' The short version is only used in the case of a custom title.' : '')) !!}
                <div class="d-flex">
                    {!! Form::text('title_data[full]', isset($image->title_data['full']) ? $image->title_data['full'] : null, ['class' => 'form-control mr-2', 'placeholder' => 'Full Title']) !!}
                    @if(Settings::get('character_title_display'))
                        {!! Form::text('title_data[short]', isset($image->title_data['short']) ? $image->title_data['short'] : null, ['class' => 'form-control mr-2', 'placeholder' => 'Short Title (Optional)']) !!}
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('Traits') !!}
        <div id="featureList">
            @foreach($image->features as $feature)
                <div class="d-flex mb-2">
                    {!! Form::select('feature_id[]', $features, $feature->feature_id, ['class' => 'form-control mr-2 feature-select original', 'placeholder' => 'Select Trait']) !!}
                    {!! Form::text('feature_data[]', $feature->data, ['class' => 'form-control mr-2', 'placeholder' => 'Extra Info (Optional)']) !!}
                    <a href="#" class="remove-feature btn btn-danger mb-2">×</a>
                </div>
            @endforeach
        </div>
        <div><a href="#" class="btn btn-primary" id="add-feature">Add Trait</a></div>
        <div class="feature-row hide mb-2">
            {!! Form::select('feature_id[]', $features, null, ['class' => 'form-control mr-2 feature-select', 'placeholder' => 'Select Trait']) !!}
            {!! Form::text('feature_data[]', null, ['class' => 'form-control mr-2', 'placeholder' => 'Extra Info (Optional)']) !!}
            <a href="#" class="remove-feature btn btn-danger mb-2">×</a>
        </div>
    </div>

    <div class="text-right">
        {!! Form::submit('Edit', ['class' => 'btn btn-primary']) !!}
    </div>
{!! Form::close() !!}

<script>
    $(document).ready(function() {
        $('.original.feature-select').selectize();
        $('#add-feature').on('click', function(e) {
            e.preventDefault();
            addFeatureRow();
        });
        $('.remove-feature').on('click', function(e) {
            e.preventDefault();
            removeFeatureRow($(this));
        })
        function addFeatureRow() {
            var $clone = $('.feature-row').clone();
            $('#featureList').append($clone);
            $clone.removeClass('hide feature-row');
            $clone.addClass('d-flex');
            $clone.find('.remove-feature').on('click', function(e) {
                e.preventDefault();
                removeFeatureRow($(this));
            })
            $clone.find('.feature-select').selectize();
        }
        function removeFeatureRow($trigger) {
            $trigger.parent().remove();
        }
    });

    $(document).ready(function() {
        var $title = $('#charTitle');
        var $titleOptions = $('#titleOptions');

        var titleEntry = $title.val() != 0;

        updateTitleEntry(titleEntry);

        $title.on('change', function(e) {
            var titleEntry = $title.val() != 0;
            updateTitleEntry(titleEntry);
        });

        function updateTitleEntry($show) {
            if($show) $titleOptions.removeClass('hide');
            else $titleOptions.addClass('hide');
        }
    });

    $( "#species" ).change(function() {
      var species = $('#species').val();
      var id = '<?php echo($image->id); ?>';
      $.ajax({
        type: "GET", url: "{{ url('admin/character/image/traits/subtype') }}?species="+species+"&id="+id, dataType: "text"
      }).done(function (res) { $("#subtypes").html(res); }).fail(function (jqXHR, textStatus, errorThrown) { alert("AJAX call failed: " + textStatus + ", " + errorThrown); });

    });

</script>
