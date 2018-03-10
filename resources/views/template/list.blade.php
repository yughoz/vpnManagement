@extends('adminlte::page')

@section('title', 'AdminLTE')

@section('content_header')
<meta name="csrf-token" content="{{ csrf_token() }}" />
    <h1>**Custom**</h1>
@stop

@section('content')
     <div class="row">
        <div class="col-md-12">
             <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title"><a href="#create**Custom**" class="btn btn-block btn-primary btn-flat"  data-toggle="modal" data-target="#create**Custom**Modal"><i class="fa fa-plus"></i> Create **Custom**</a></h3>
                </div>
                <div class="box-body">
                    <table class="table table-striped table-hover" id="tbl**Custom**">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Groups</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
         </div>
    </div>

<!-- Modal -->
<div id="create**Custom**Modal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Create **Custom**</h4>
      </div>
        <form action="#create" class="form-horizontal" id="form-create_**custom**" method="post" accept-charset="utf-8">
      <div class="modal-body">
            
            {!! csrf_field() !!}
            
            <div class="form-group">
                <label for="name" class="col-sm-2 control-label">name</label>
                <div class="col-sm-10">
                    <input type="text" name="name" value="" id="name" class="form-control"/>
                </div>
            </div>
            <div class="form-group">
                <label for="email" class="col-sm-2 control-label">Email</label>
                <div class="col-sm-10">
                    <input type="email" name="email" value="" id="email" class="form-control"/>
                </div>
            </div>

      </div>
      <div id="wraperStatus"></div>

      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="reset" class="btn btn-warning btn-flat">Reset</button>
        <button type="submit" class="btn btn-primary" id="btnSave">Save</button>
      </div>
        </form>
    </div>

  </div>
</div>

<!-- Modal -->
<div id="edit**Custom**Modal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
        <form action="#edit" class="form-horizontal" id="form-edit_**custom**" method="put" accept-charset="utf-8">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Edit **Custom**</h4>
      </div>
      <div class="modal-body">
            
            {!! csrf_field() !!}
            
            <input type="hidden" name="edit**Custom**_id" value="" id="edit**Custom**_uID" class="form-control edit**Custom**"/>
            <div class="form-group">
                <label for="name" class="col-sm-2 control-label">name</label>
                <div class="col-sm-10">
                    <input type="text" name="edit**Custom**_name" value="" id="edit**Custom**_name" class="form-control edit**Custom**"/>
                </div>
            </div>
            <div class="form-group">
                <label for="email" class="col-sm-2 control-label">Email</label>
                <div class="col-sm-10">
                    <input type="email" name="edit**Custom**_email" value="" id="edit**Custom**_email" class="form-control edit**Custom**"/>
                </div>
            </div>
        </div>
      
      <div id="wraperStatus"></div>

      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" id="btnEdit">Save</button>
      </div>
        </form>
    </div>

  </div>
</div>

<!-- Modal -->
<div id="deleteModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Confirm Delete</h4>
      </div>
      <div class="modal-body">
            <p>Are you sure want to delete this **custom**..?</p>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-danger" id="btnDelete">Delete</button>
      </div>
        </form>
    </div>

  </div>
</div>

@stop

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/adminlte/vendor/datatables/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/adminlte/vendor/toastr-master/build/toastr.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('vendor/adminlte/vendor/bootstrap-toggle-master/css/bootstrap-toggle.css') }}">
@stop
@section('js')
    <script src="{{ asset('vendor/adminlte/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/adminlte/vendor/datatables/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('vendor/adminlte/vendor/toastr-master/toastr.js') }}"></script>
    <script src="{{ asset('vendor/adminlte/vendor/blockui-master/jquery.blockUI.js') }}"></script>
    <script src="{{ asset('vendor/adminlte/vendor/bootstrap-toggle-master/js/bootstrap-toggle.js') }}"></script>
    <script src="{{ asset('js/dll/**custom**/list.js') }}"></script>
    <script type="text/javascript" language="javascript" > 
        
    	list.baseUrl = "{{ url('') }}/"; 
    	list.init();
    </script>
@stop
