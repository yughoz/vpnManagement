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
    self.tblRole = $('#tblRole').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
			"url": self.baseUrl+"/API/Role/list",
    	    data : function ( d ) {
          		d.groupId = $('#selectRole').val();
	        },
		},//list.baseUrl+"/API/Role/list",
        "aoColumnDefs": [
        		{ "bSortable": false, "aTargets": [ 1,2,3, 4 ] }, 
                { "bSearchable": false, "aTargets": [ 1,2,3, 4 ] }
                ],
       //  data : function ( d ) {
	      // 		d.groupId = $('#selectRole').val();
	      // },
        columns: [
            { data: 'module_code', name: 'module_code' },
            { data: 'createAcc', name: 'createAcc' },
            { data: 'readAcc', name: 'readAcc' },
            { data: 'updateAcc', name: 'updateAcc' },
            { data: 'deleteAcc', name: 'deleteAcc' }
        ]
    });

    $("#btnDelete").click(function(){	
			self.deleteConfirm(self.idDelete);
		});
    $('#selectRole').change(function() {
		self.selectRole = $('#selectRole').val();
		$('#groupNameSelect').val($("#selectRole option[value='"+$('#selectRole').val()+"']").text());
		self.tblRole.draw();
	
	});

	$('#tblRole').on( 'draw.dt', function () {
	   $('.btnC').bootstrapToggle({
	      size:"mini",
	      on: 'Active',
	      off: 'Inactive'
	    });
	} );

    $(document).ajaxStop($.unblockUI);
    $("#form-create_role").on('submit',(function(e) {
			e.preventDefault();
			$('#createRoleModal').modal('hide');
			$.blockUI({
				// 
                message: '<h4>  Please wait... <img src='+self.baseUrl+'public/images/spinner.gif></h4>',
            });
			$.ajax({
				headers: {
		            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		        },
				url: self.baseUrl+"/API/Role/Assign/"+$("#selectRole").val(), // Url to which the request is send
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
							// document.getElementById("form-create_role").reset();
							self.tblRole.draw();
							toastr.success(parseData['desc']);
						} else{
							toastr.warning(parseData['desc']);
							toastr.warning(parseData['error'].join('<br>'));
							$('#createRoleModal').modal('show');
						}
					} catch(e) {
						console.log(e);
            			toastr.error('Internal Server Error');
						$('#createRoleModal').modal('show');
					}
				},
	            error: function (data) {
	                console.log('Error:', data);
            		toastr.error('Connection Error');
					$('#createRoleModal').modal('show');
	            }
			});
		}));

    $("#form-edit_role").on('submit',(function(e) {
			e.preventDefault();
			$('#editRoleModal').modal('hide');
			$.blockUI({
                message: '<h4>  Please wait... <img src='+self.baseUrl+'public/images/spinner.gif></h4>',
            });
			$.ajax({
				headers: {
		            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		        },
				// url: self.baseUrl+"ajaxAddQuotes", // Url to which the request is send
				url: self.baseUrl+"/API/Role/"+$(".editRole[name=editRole_id]").val(), // Url to which the request is send
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
							document.getElementById("form-edit_role").reset();
							self.tblRole.draw();
							toastr.success(parseData['desc']);
						} else{
							toastr.warning(parseData['desc']);
							toastr.warning(parseData['error'].join('<br>'));
							$('#editRoleModal').modal('show');
						}
					} catch(e) {
						console.log(e);
            			toastr.error('Internal Server Error');
						$('#editRoleModal').modal('show');
					}
				},
	            error: function (data) {
	                console.log('Error:', data);
            		toastr.error('Connection Error');
					$('#editRoleModal').modal('show');
	            }
			});
		}));

  },
	

	changeStatus : function(uid,action)	{
	    self = this;
	    toastr.options = self.toastrOptions;
	    // alert($('#check'+uid).prop('checked'));
			$.blockUI({
						message: '<h4> <img src='+self.baseUrl+'assets/images/ajax_loading.gif> Please wait...</h4>',
				});
			if ($('#'+action+uid).prop('checked') == true) {
				active = 0;
			} else {
				active = 1;
			}
			$.ajax({

					headers: {
			            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			        },
					type: "PUT",
					url: self.baseUrl+"API/Role/"+uid+"/"+action+"/"+active,
					success: function(resp) {
						try {
							// parseData = $.parseJSON(resp);
							parseData = resp;
							if(parseData['statusCode'] == 202){
								// $('a#active'+uid).text(parseData['data']);
								toastr.success(parseData['desc']);
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
  	// alert(uid)
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
				url: self.baseUrl+"API/Role/"+uid,
				success: function(resp) {
					try {
						// parseData = $.parseJSON(resp);
						parseData = resp;
						if(parseData['statusCode'] == 204){
							self.tblRole.draw();
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
		$('#editRoleModal').modal('show');
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
				url: self.baseUrl+"API/Role/"+uid,
				success: function(resp) {
					try {
						// parseData = $.parseJSON(resp);
						parseData = resp;
						if(parseData['statusCode'] == 202){
							// $('a#active'+uid).text(parseData['data']);
							// toastr.success(parseData['desc']);
							$.each(parseData['data'], function(k,v){
								$(".editRole[name=editRole_"+k+"]").val(v);
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