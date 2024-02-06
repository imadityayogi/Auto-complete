$('#event_id').autocomplete({
        source: function(request, response) {
            if(request.term != ''){
                $('#event_id_hidden').val(0); // Set the name in the input field
            }
            // Make an AJAX request to fetch the autocomplete suggestions
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ url('admin/autocomplete-events') }}",
                dataType: 'json',
                method: 'POST',
                data: {
                    term: request.term
                },
                success: function(data) {
                    response(data.map(function(item) {
                        return {
                            label: item.name,
                            value: item.id
                        };
                    }));
                }
            });
        },
        minLength: 3, // Minimum characters before triggering autocomplete
        select: function(event, ui) {
            // This function is called when an item is selected
            $('#event_id').val(ui.item.label); // Set the name in the input field
            $('#event_id_hidden').val(ui.item.value); // Set the name in the input field
            // alert('Selected ID: ' + ui.item.value);
            get_event_detail(ui.item.value)
            return false; // Prevent the default behavior of setting the value in the input
        }
    });



function autocomplete_events(Request $request)
    {
        $term = $request->input('term');
        $events = DB::select("SELECT id,event_name FROM events WHERE event_name LIKE :term", ['term' => '%' . $term . '%']);

        $response = array_map(function ($event) {
            return [
                'id' => $event->id,
                'name' => $event->event_name,
            ];
        }, $events);

        return response()->json($response);
    }
