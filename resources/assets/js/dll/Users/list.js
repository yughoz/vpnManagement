role = {

baseUrl : "",
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
    // $(document).ajaxStop($.unblockUI);

		self.tblRole = $('#tblRole').DataTable({ 
							"responsive": true,
						    "serverSide": true,
							"bSort" : false,
							"bLengthChange": false,
							"bFilter" : false,
							"pageLength": 10,
						    "ajax":{
											"url": self.baseUrl+"/API/listRole/"+$('#selectRole').val(),
						          data : function ( d ) {
						          		d.groupId = $('#selectRole').val();
						          },
						          error: function(){  // error handling
						              $(".employee-grid-error").html("");
						              $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
						              $("#employee-grid_processing").css("display","none");
						          }
						      },
										"dom": "<'row' <'col-md-12'T>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // horizobtal scrollable datatable
										"columns": [
										            { "data": "num" },
										            { "data": "module_name" },
										            { "data": "createAcc" },
										            { "data": "readAcc" },
										            { "data": "updateAcc" },
										            { "data": "deleteAcc" },
										        ],
										"order": [
						                    [1, "asc"]
						                ]//
						  } );

		// $('#btnSave').click(function() {
		// 	self.assignedRoleAccess();
		// });

  },