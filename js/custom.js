jQuery( document ).ready(function( $ ) {
		$("#delete").click(function () {
        var table_id = $('#table_id').val();
        $.ajax({
                    url: ajax_url,
                    type: 'post',
                    data: {
                        action       :'delete_datatable',
                        table_id : table_id,
                    },
                    success: function (response) {
                        console.log("Successfully Deleted");
                    }
                });

    	});
	});