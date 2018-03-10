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
        		{ "bSortable": false, "aTargets": [ 4 ] }, 
                { "bSearchable": false, "aTargets": [ 4 ] }
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

    $("#btnCheckConnection").click(function(){	
		self.checkConnection(	
								$(".editMikrotik[name=editMikrotik_ip]").val(),
								$(".editMikrotik[name=editMikrotik_username]").val(),
								$(".editMikrotik[name=editMikrotik_password]").val()
								);
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
							if (parseData['error'] != undefined) {
								toastr.warning(parseData['error'].join('<br>'));
							}
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
							if (parseData['error'] != undefined) {
								toastr.warning(parseData['error'].join('<br>'));
							}
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

    $("#form-edit_mikrotik").on('submit',(function(e) {
			e.preventDefault();
			$('#editMikrotikModal').modal('hide');
			$.blockUI({
                message: '<h4>  Please wait... <img src='+self.baseUrl+'public/images/spinner.gif></h4>',
            });
			$.ajax({
				headers: {
		            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		        },
				// url: self.baseUrl+"ajaxAddQuotes", // Url to which the request is send
				url: self.baseUrl+"/API/PortForwading/mikrotik/"+$("#editMikrotik_mikrotikId").val(), // Url to which the request is send
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
							// document.getElementById("form-edit_port_forwading").reset();
							self.getMikrotik($("#editMikrotik_mikrotikId").val());
							toastr.success(parseData['desc']);
						} else{
							toastr.warning(parseData['desc']);
							if (parseData['error'] != undefined) {
								toastr.warning(parseData['error'].join('<br>'));
							}
							$('#editMikrotikModal').modal('show');
						}
					} catch(e) {
						console.log(e);
            			toastr.error('Internal Server Error');
						$('#editMikrotikModal').modal('show');
					}
				},
	            error: function (data) {
	                console.log('Error:', data);
            		toastr.error('Connection Error');
					$('#editMikrotikModal').modal('show');
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
// 
		param = {
			"mikrotik_id" : $("#mikrotikId").val(),
			"uID" : uid
		};
		$.ajax({
				headers: {
		            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		        },
				type: "DELETE",
				url: self.baseUrl+"API/PortForwading/"+$("#mikrotikId").val()+"/"+uid,
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
  	listConfig : function(uid)	{
	    self = this;
	    toastr.options = self.toastrOptions;
		url = self.baseUrl+"API/PortForwading/listConfig/"+uid;
		window.open(url);
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
  	getMikrotik : function(uid)	{
	    self = this;
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
				url: self.baseUrl+"API/PortForwading/mikrotik/"+uid,
				success: function(resp) {
					try {
						// parseData = $.parseJSON(resp);
						parseData = resp;
						if(parseData['statusCode'] == 202){
							// $('a#active'+uid).text(parseData['data']);
							// toastr.success(parseData['desc']);
							$.each(parseData['data'], function(k,v){
								$(".editMikrotik[name=editMikrotik_"+k+"]").val(v);
								$(".detailtMikrotik[name="+k+"]").val(v);
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

  	checkConnection : function(ip,username,password)	{
	    self = this;
	    // alert(ip);
	    // alert(username);
	    // alert(password);
	    toastr.options = self.toastrOptions;
		$('#editMikrotikModal').modal('hide');
	    // alert($('#check'+uid).prop('checked'));
		$.blockUI({
					message: '<h4> <img src='+self.baseUrl+'assets/images/ajax_loading.gif> Please wait...</h4>',
			});
		param = {
			"ip" : ip,
			"username" : username,
			"password" : password,
		};
		$.ajax({
				headers: {
		            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		        },
				type: "GET",
				data: param,
				url: self.baseUrl+"API/PortForwading/connection/mikrotik",
				success: function(resp) {
					try {
						// parseData = $.parseJSON(resp);
						parseData = resp;
						if(parseData['statusCode'] == 200){
							toastr.success(parseData['desc']);
						} else if (parseData['desc'] != undefined) {
	        				toastr.warning(parseData['desc']);
						} 
						else {
							// $('a#active'+uid).text("ERROR");
	        				toastr.error('Internal Server Error');
						}
					} catch(e) {
						console.log(e);
	        			toastr.error('Connection Error');
					}
				$('#editMikrotikModal').modal('show');
				},
	            error: function (data) {
	                console.log('Error:', data);
	        		toastr.error('Connection Error');
					$('#editMikrotikModal').modal('show');
	            }
		});
  	},
}