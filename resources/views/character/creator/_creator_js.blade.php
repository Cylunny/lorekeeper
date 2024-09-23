<script>

    // we want the colorpicker to only send when the lil window closes...
    var deletionObserver = new MutationObserver(
        function(mutations) {
            mutations.forEach(
                function(mutation) {
                    mutation.removedNodes.forEach(
                        function(node) {
                            if (node.classList && node.classList.contains('colorpicker-bs-popover')) {
                                updatePreview(node);
                            }
                        });
                });
        });
    var body = document.querySelector('body');
    deletionObserver.observe(body, {
        childList: true,
        subtree: true
    });

    $(document).ready(function() {
        //also update the selections manually once..
        $('.creator-select').each(function(i, obj) {
            var optionId = $(obj).find(":selected").val();
            var groupId = $(obj).attr("name").split('_')[0];

            $.ajax({
                type: "GET",
                url: "{{ url('character-creator/choices') }}?groupId=" + groupId + "&optionId=" + optionId,
                dataType: "text"
            }).done(function(res) {
                $("#" + groupId + "_choices").html(res);
            }).fail(function(jqXHR, textStatus, errorThrown) {
                alert("AJAX call failed: " + textStatus + ", " + errorThrown);
            });
        });
        $('.cp').colorpicker();
        //send request to start with..this should only run on page reload        
        updatePreview();

    });

    $(document).on('change', '.creator-select', function(evt) {
        //then only send if user changes smth
        updatePreview(evt);
    });

    $(document).on('change', '.marking-select', function(evt) {
        //then only send if user changes smth
        updatePreview(evt);
    });

    
    $(document).on('blur', '.colorpicker-element', function(evt) {
        //then only send if user changes smth
        updatePreview(evt);
    });


    function updatePreview(evt) {
        $('#spinner').removeClass('hide');

        // update the image stack
        var data = {
            "_token": "{{ csrf_token() }}",
            "reload": evt == null ? 1 : 0
        };
        $(".form-control").each(function(index, element) {
            var name = $(this).attr("name");
            if ($(this).is('input')) {
                if(evt) data[name] = $(this).val();
                else data[name] = "#FFFFFF";
            }
            if ($(this).is('select')) {
                data[name] = $(this).find(":selected").val();
            }
        });
        $.ajax({
            type: "POST",
            url: "{{ url('character-creator/' . $creator->id . '/image') }}",
            dataType: "html",
            data: data,
        }).done(function(res) {
            $(res).each(function(i, obj) {
                //replace images with new ones OR add new ones
                if(obj.id){
                     $("#" + obj.id).html(obj);
                     if(obj.src != '') {
                        $("#" + obj.id).removeClass('hide'); 
                     }
                } 
            });       
            $('.cp').colorpicker();
            $('#spinner').addClass('hide');
        }).fail(function(jqXHR, textStatus, errorThrown) {
            alert("AJAX call failed: " + textStatus + ", " + errorThrown + ". If you are an admin, please check the application logs.");
        });

        if(evt) {
         // update the choices
            if ($(evt.target).is('select') && $(evt.target).hasClass('base-select')) {
                var optionId = $(evt.target).find(":selected").val();
                var groupId = $(evt.target).attr("name").split('_')[0];

                $.ajax({
                    type: "GET",
                    url: "{{ url('character-creator/choices') }}?groupId=" + groupId + "&optionId=" + optionId,
                    dataType: "text"
                }).done(function(res) {
                    $("#" + groupId + "_choices").html(res);
                }).fail(function(jqXHR, textStatus, errorThrown) {
                    alert("AJAX call failed: " + textStatus + ", " + errorThrown);
                });
            }
        }

    }
</script>