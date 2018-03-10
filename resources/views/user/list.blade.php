@extends('adminlte::page')

@section('title', 'AdminLTE')

@section('content_header')
<meta name="csrf-token" content="{{ csrf_token() }}" />
    <h1>Users</h1>
@stop

@section('content')
     <div class="row">
        <div class="col-md-12">
             <div class="box">
                <div class="box-header with-border">
                    @if(checkAccess('users','createAcc'))
                        <h3 class="box-title"><a href="#createUser" class="btn btn-block btn-primary btn-flat"  data-toggle="modal" data-target="#createUserModal"><i class="fa fa-plus"></i> Create user</a></h3>
                    @endif
                </div>
                <div class="box-body">
                    <table class="table table-striped table-hover" id="tblUser">
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
<div id="createUserModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Create User</h4>
      </div>
        <form action="#create" class="form-horizontal" id="form-create_user" method="post" accept-charset="utf-8">
      <div class="modal-body">
            
            {!! csrf_field() !!}
            
            <div class="form-group">
                <label for="name" class="col-sm-2 control-label">Username</label>
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
            <div class="form-group">
                <label for="phone" class="col-sm-2 control-label">Phone</label>
                <div class="col-sm-10">
                    <input type="tel" name="phone" value="" id="phone" pattern="^((\+\d{1,3}(-| )?\(?\d\)?(-| )?\d{1,5})|(\(?\d{2,6}\)?))(-| )?(\d{3,4})(-| )?(\d{4})(( x| ext)\d{1,5}){0,1}$" class="form-control"/>
                </div>
            </div>
            <div class="form-group">
                <label for="password" class="col-sm-2 control-label">Password</label>
                <div class="col-sm-10">
                    <input type="password" name="password" value="" id="password" class="form-control"/>
                    <div class="progress" style="margin:0">
                        <div class="pwstrength_viewport_progress"></div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="password_confirm" class="col-sm-2 control-label">Password confirm</label>
                <div class="col-sm-10">
                    <input type="password" name="password_confirm" value="" id="password_confirm" class="form-control"/>
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
<div id="editUserModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
        <form action="#edit" class="form-horizontal" id="form-edit_user" method="put" accept-charset="utf-8">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Edit User</h4>
      </div>
      <div class="modal-body">
            
            {!! csrf_field() !!}
            
            <input type="hidden" name="editUser_id" value="" id="editUser_uID" class="form-control editUser"/>
            <div class="form-group">
                <label for="name" class="col-sm-2 control-label">Username</label>
                <div class="col-sm-10">
                    <input type="text" name="editUser_name" value="" id="editUser_name" class="form-control editUser"/>
                </div>
            </div>
            <div class="form-group">
                <label for="email" class="col-sm-2 control-label">Email</label>
                <div class="col-sm-10">
                    <input type="email" name="editUser_email" value="" id="editUser_email" class="form-control editUser"/>
                </div>
            </div>
            <div class="form-group">
                <label for="phone" class="col-sm-2 control-label">Phone</label>
                <div class="col-sm-10">
                    <input type="tel" name="editUser_phone" value="" id="editUser_phone" pattern="^((\+\d{1,3}(-| )?\(?\d\)?(-| )?\d{1,5})|(\(?\d{2,6}\)?))(-| )?(\d{3,4})(-| )?(\d{4})(( x| ext)\d{1,5}){0,1}$" class="form-control editUser"/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">Member of groups</label>
                <div class="col-sm-10">
                    <select id="editUser_groups" name="editUser_group_id" class="form-control editUser">
                         @foreach($groups as $g)
                        <option value="{{$g->id}}">{{$g->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
      <div class="modal-header" style="padding-top: 30px">
        <h4 class="modal-title">Reset Password</h4>
      </div>
      <div class="modal-body">         
            <div class="form-group">
                <label for="password" class="col-sm-2 control-label">Password</label>
                <div class="col-sm-10">
                    <input type="password" name="password" value="" id="password" class="form-control editUser"/>
                    <div class="progress" style="margin:0">
                        <div class="pwstrength_viewport_progress"></div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="password_confirm" class="col-sm-2 control-label">Password confirm</label>
                <div class="col-sm-10">
                    <input type="password" name="password_confirm" value="" id="password_confirm" class="form-control editUser"/>
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
            <p>Are you sure want to delete this product..?</p>
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
    <script src="{{ asset('js/dll/users/list.js') }}"></script>
    <script type="text/javascript" language="javascript" > 
        
    	list.baseUrl = "{{ url('') }}/"; 
    	list.init();
    </script>
@stop
