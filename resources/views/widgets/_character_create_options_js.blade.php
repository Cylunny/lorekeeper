<script>
    $(document).ready(function() {
        $('#userSelect').selectize();
        $( "#datepicker" ).datetimepicker({
            dateFormat: "yy-mm-dd",
            timeFormat: 'HH:mm:ss',
        });
        // Resell options /////////////////////////////////////////////////////////////////////////////

        var $resellable = $('#resellable');
        var $resellOptions = $('#resellOptions');

        var resellable = $resellable.is(':checked');

        updateOptions();

        $resellable.on('change', function(e) {
            resellable = $resellable.is(':checked');

            updateOptions();
        });

        function updateOptions() {
            if(resellable) $resellOptions.removeClass('hide');
            else $resellOptions.addClass('hide');
        }

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
</script>
