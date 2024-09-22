@if(!$creator->allow_character_creation || !$user)
<p> Character creation is disabled for this creator, or you are not logged in. </p>      
@else
    @if($creator->cost > 0) 
        <p>This will create a final PNG of your character for you to download, and <b>remove {{ $creator->cost }} {!! isset($creator->currency) ? $creator->currency->displayName : $creator->item->displayName !!} from your account.</b></p> 
    @else
        <p>This will create a final PNG of your character for you to download.</b></p> 
    @endif

    <p>Are you sure you want to do this? <b>This might take a while to load due to image manipulations.</b></p>

    <a href="#" class="btn btn-success float-right confirm-create-character-button">Create Character</a>
@endif

<script>
    
    $('.confirm-create-character-button').on('click', function(e) {
        e.preventDefault();

        var data = {
            "_token": "{{ csrf_token() }}",
        };
        $(".form-control").each(function(index, element) {
            var name = $(this).attr("name");
            if ($(this).is('input')) {
                data[name] = $(this).val();
            }
            if ($(this).is('select')) {
                data[name] = $(this).find(":selected").val();
            }
        });
        $.ajax({
            type: "POST",
            url: "{{ url('character-creator/' . $creator->id . '/create') }}",
            dataType: "html",
            data: data,
        }).done(function(res) {
            $('#modal').modal('hide');
            $("#creator").html(res);
        }).fail(function(jqXHR, textStatus, errorThrown) {
            alert("AJAX call failed: " + textStatus + ", " + errorThrown);
        });
    });

</script>