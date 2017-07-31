// After page loading
jQuery(function($) {
    $(document).ready(function(){
            
        // delete an event
        $('#bb_details #delete').on('click', function(e) {
            e.preventDefault();
            if (confirm('Are you sure?')) {
                var forma = $('#bb_details');
                var id = $(forma).find('#id').val();
                var start = $(forma).find('#b_start_time').val();
                var end = $(forma).find('#b_end_time').val();
                var all = $(forma).find('#b_all').is(':checked') ? 1 : 0;
                window.location = $(this).attr('href') + "&id=" + id + "&start=" + start + "&end=" + end+"&all=" + all;
            }
        })
        
        // show a modal  dialog with event
        $('a.aroom').on('click', function(e) {
                e.preventDefault();
                var data = JSON.parse($(this).attr('data-id'));
                var forma = $('#bb_details');
                var start = data.start.substr(11, 5);
                var end = data.end.substr(11, 5);
                $(forma ).find( "#b_start_time" ).val(start);
                $(forma).find('#b_end_time').val(end);
                $(forma).find('#b_notes').val(data.notes);
                $(forma).find('#submitted span').html(data.submitted);
                if (data.reccuring_start) {
                    $(forma).find('.g_all').show();
                }
                $(forma).find('#id').val(data.id);
            })
            
    });
});


