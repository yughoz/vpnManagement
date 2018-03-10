editProfile = {

baseUrl : "",
idDelete : "",
toastrOptions : {
				  "closeButton": true,
				  "debug": false,
				  "positionClass": "toast-top-right",
				  "onclick": null,
				  "showDuration": "1000",
				  "hideDuration": "1000",
				  "timeOut": "5000",
				  "extendedTimeOut": "1000",
				  "showEasing": "swing",
				  "hideEasing": "linear",
				  "showMethod": "fadeIn",
				  "hideMethod": "fadeOut"
				},

init : function()	{
    self = this;

    toastr.options = self.toastrOptions;
    $(document).ajaxStop($.unblockUI);
    $("#form-edit_user").on('submit',(function(e) {
			e.preventDefault();
			url = self.baseUrl+"API/user/edit/profile"
			$('#editUserModal').modal('hide');
			$.blockUI({
				// 
                message: '<h4>  Please wait... <img src='+self.baseUrl+'public/images/spinner.gif></h4>',
            });
			$.ajax({
				headers: {
		            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		        },
				// url: self.baseUrl+"ajaxAddQuotes", // Url to which the request is send
				url: url, // Url to which the request is send
				type: "POST",             // Type of request to be send, called as method
				data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
				contentType: false,       // The content type used when sending data to the server.
				cache: false,             // To unable request pages to be cached
				processData:false,        // To send DOMDocument or non processed data file it is set to false
				success: function(resp)   // A function to be called if request succeeds
				{
					try {
						console.log(resp.desc);
						// parseData = $.parseJSON(resp);
						parseData = resp;
						if(parseData['statusCode'] == 202){
							toastr.success(parseData['desc']);
						} else{
							toastr.warning(parseData['desc']);
							toastr.warning(parseData['error'].join('<br>'));
							$('#editUserModal').modal('show');
						}
					} catch(e) {
						console.log(e);
            			toastr.error('Internal Server Error');
						$('#editUserModal').modal('show');
					}
				},
	            error: function (data) {
	                console.log('Error:', data);
            		toastr.error('Connection Error');
					$('#editUserModal').modal('show');
	            }
			});
		}));

  },
	
}