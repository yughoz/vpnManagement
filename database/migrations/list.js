list = {

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
    self.tblPortForwading = $('#tblPortForwading').DataTable({
        processing: true,
        serverSide: true,
        ajax: list.baseUrl+"/API/PortForwading/list",
        "aoColumnDefs": [
        		{ "bSortable": false, "aTargets": [ 2, 4 ] }, 
                { "bSearchable": false, "aTargets": [ 2, 4 ] }
                ],
        columns: [
            { data: 'mechine_name', name: 'mechine_name' },
            { data: 'to-addresses', name: 'to-addresses' },
            { data: 'to-ports', name: 'to-ports' },
            { data: 'dst-port', name: 'dst-port' },
            { data: 'action', name: 'action' }
        ]
    });

    $("#btnDelete").click(function(){	
			self.deleteConfirm(self.idDelete);
		});

	$('#tblPortForwading').on( 'draw.dt', function () {
	   
	} );

    $(document).ajaxStop($.unblockUI);
    $("#form-create_port_forwading").on('submit',(function(e) {
			e.preventDefault();
			$('#createPortForwadingModal').modal('hide');
			$.blockUI({
				// 
                message: '<h4>  Please wait... <img src='+self.baseUrl+'public/images/spinner.gif></h4>',
            });
			$.ajax({
				headers: {
		            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		        },
				url: self.baseUrl+"/API/PortForwading/Create", // Url to which the request is send
				type: "POST",             // Type of request to be send, called as method
				data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
				contentType: false,       // The content type used when sending data to the server.
				cache: false,             // To unable request pages to be cached
				processData:false,        // To send DOMDocument or non processed data file it is set to false
				success: function(resp)   // A function to be called if request succeeds
				{
					try {
						parseData = resp;
						if(parseData['statusCode'] == 201){
							document.getElementById("form-create_port_forwading").reset();
							self.tblPortForwading.draw();
							toastr.success(parseData['desc']);
						} else{
							toastr.warning(parseData['desc']);
							toastr.warning(parseData['error'].join('<br>'));
							$('#createPortForwadingModal').modal('show');
						}
					} catch(e) {
						console.log(e);
            			toastr.error('Internal Server Error');
						$('#createPortForwadingModal').modal('show');
					}
				},
	            error: function (data) {
	                console.log('Error:', data);
            		toastr.error('Connection Error');
					$('#createPortForwadingModal').modal('show');
	            }
			});
		}));

    $("#form-edit_port_forwading").on('submit',(function(e) {
			e.preventDefault();
			$('#editPortForwadingModal').modal('hide');
			$.blockUI({
                message: '<h4>  Please wait... <img src='+self.baseUrl+'public/images/spinner.gif></h4>',
            });
			$.ajax({
				headers: {
		            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		        },
				// url: self.baseUrl+"ajaxAddQuotes", // Url to which the request is send
				url: self.baseUrl+"/API/PortForwading/"+$(".editPortForwading[name=editPortForwading_id]").val(), // Url to which the request is send
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
							// urlDetailForm = "<p>Add Answer click <a href='"+self.baseUrl+"admin/form/"+parseData["idCreate"]+"'>here</a>";
							document.getElementById("form-edit_port_forwading").reset();
							self.tblPortForwading.draw();
							toastr.success(parseData['desc']);
						} else{
							toastr.warning(parseData['desc']);
							toastr.warning(parseData['error'].join('<br>'));
							$('#editPortForwadingModal').modal('show');
						}
					} catch(e) {
						console.log(e);
            			toastr.error('Internal Server Error');
						$('#editPortForwadingModal').modal('show');
					}
				},
	            error: function (data) {
	                console.log('Error:', data);
            		toastr.error('Connection Error');
					$('#editPortForwadingModal').modal('show');
	            }
			});
		}));

  },
	

	deleteConfirm : function(uid)	{
	    self = this;
		$('#deleteModal').modal('hide');
	    toastr.options = self.toastrOptions;
		$.blockUI({
					message: '<h4> <img src='+self.baseUrl+'assets/images/ajax_loading.gif> Please wait...</h4>',
			});

		$.ajax({
				headers: {
		            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		        },
				type: "DELETE",
				url: self.baseUrl+"API/PortForwading/"+uid,
				success: function(resp) {
					try {
						// parseData = $.parseJSON(resp);
						parseData = resp;
						if(parseData['statusCode'] == 204){
							self.tblPortForwading.draw();
							toastr.warning(parseData['desc']);
						} else {
							// $('a#active'+uid).text("ERROR");
	        				toastr.error('Internal Server Error');
						}
					} catch(e) {
						console.log(e);
	        			toastr.error('Internal Server Error');
					}
				},
	            error: function (data) {
	                console.log('Error:', data);
	        		toastr.error('Connection Error');
	            }
		});
	},
	deleteModal : function(uid)	{
	    self = this;
		$('#deleteModal').modal('show');
		self.idDelete = uid;
	},
  	editModal : function(uid)	{
	    self = this;
		$('#editPortForwadingModal').modal('show');
	    toastr.options = self.toastrOptions;
	    // alert($('#check'+uid).prop('checked'));
		$.blockUI({
					message: '<h4> <img src='+self.baseUrl+'assets/images/ajax_loading.gif> Please wait...</h4>',
			});

		$.ajax({

				headers: {
		            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		        },
				type: "GET",
				url: self.baseUrl+"API/PortForwading/"+uid,
				success: function(resp) {
					try {
						// parseData = $.parseJSON(resp);
						parseData = resp;
						if(parseData['statusCode'] == 202){
							// $('a#active'+uid).text(parseData['data']);
							// toastr.success(parseData['desc']);
							$.each(parseData['data'], function(k,v){
								$(".editPortForwading[name=editPortForwading_"+k+"]").val(v);
							});
						} else {
							// $('a#active'+uid).text("ERROR");
	        				toastr.error('Internal Server Error');
						}
					} catch(e) {
						console.log(e);
	        			toastr.error('Internal Server Error');
					}
				},
	            error: function (data) {
	                console.log('Error:', data);
	        		toastr.error('Connection Error');
	            }
		});
  	},
}