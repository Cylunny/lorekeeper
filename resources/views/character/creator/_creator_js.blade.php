<script>

    var deletionObserver = new MutationObserver(
    function (mutations) {
        mutations.forEach(
        function (mutation) {
            mutation.removedNodes.forEach(
            function(node) {
                if(node.classList && node.classList.contains('colorpicker-bs-popover')){
                    updatePreview();
                }
            });
        });
    });
    var body = document.querySelector('body');
    deletionObserver.observe(body, {
        childList: true,
        subtree: true
    });

    $(document).on('change', '.creator-select', function() {
        updatePreview();
    });
    
    function updatePreview(){
        console.log("Send update!");
        var data = {
            "_token": "{{ csrf_token() }}",
        };
        $(".form-control").each(function (index, element) {
            // element == this
            var name = $(this).attr("name");
            if( $(this).is('input') ) {
                data[name] = $(this).val();
            }
            if( $(this).is('select') ) {
                data[name] = $(this).find(":selected").val();
            }
        });
        $.ajax({
            type: "POST",
            url: "{{ url('character-creator/' . $creator->id . '/image') }}",
            dataType: "html",
            data:  data,
        }).done(function(res) {
            $("#creator-container").html(res);
        }).fail(function(jqXHR, textStatus, errorThrown) {
            alert("AJAX call failed: " + textStatus + ", " + errorThrown);
        });
    }
</script>