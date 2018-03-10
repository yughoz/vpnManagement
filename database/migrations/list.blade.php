@extends('adminlte::page')

@section('title', 'AdminLTE')

@section('content_header')
<meta name="csrf-token" content="{{ csrf_token() }}" />
    <h1>Port Forwading</h1>
@stop

@section('content')
     <div class="row">
      <div class="col-md-12">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Detail Mikrotik</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->  
            <form class="form-horizontal">
              <div class="box-body">
                <div class="row ">
                  <div class="form-group col-md-6">
                    <label for="inputEmail3" class="col-sm-2 control-label">Router Name</label>

                    <div class="col-sm-10">
                      <input type="text" readonly="readonly" class="form-control" name="router_name" id="routerName" value="{{$mikrotik->router_name}}">
                    </div>
                  </div>
                </div>
                <div class="row ">
                  <div class="form-group col-md-6 ">
                    <label for="inputPassword3" class="col-sm-2 control-label">IP Address</label>

                    <div class="col-sm-10">
                      <input type="text" readonly="readonly" class="form-control" id="ip" name="ip" value="{{$mikrotik->ip}}">
                    </div>
                  </div>
                </div>
                <div class="row ">
                  <div class="form-group col-md-6 ">
                    <label for="inputPassword3" readonly="readonly" class="col-sm-2 control-label">Username</label>

                    <div class="col-sm-10">
                      <input type="text" readonly="readonly" class="form-control" id="username" name="username" value="{{$mikrotik->username}}">
                    </div>
                  </div>
                </div>
                <div class="row ">
                  <div class="form-group col-md-6 ">
                    <label for="inputPassword3" class="col-sm-2 control-label">Description</label>

                    <div class="col-sm-10">
                      <textarea readonly="readonly" class="form-control" id="description" name="description">{{$mikrotik->description}}</textarea>
                    </div>
                  </div>
                </div>
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <button class="btn btn-default">Edit</button>
                <!-- <button type="submit" class="btn btn-info pull-right">Sign in</button> -->
              </div>
              <!-- /.box-footer -->
            </form>
          </div>
          <!-- /.box -->
    </div>
    </div>
     <div class="row">
        <div class="col-md-12">
             <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title"><a href="#createPortForwading" class="btn btn-block btn-primary btn-flat"  data-toggle="modal" data-target="#createPortForwadingModal"><i class="fa fa-plus"></i> Add Port Forwading</a></h3>
                </div>
                <div class="box-body">
                    <table class="table table-striped table-hover" id="tblPortForwading">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Address</th>
                                <th>To Port</th>
                                <th>Dst Port</th>
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
<div id="createPortForwadingModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add Port Forwading</h4>
      </div>
        <form action="#create" class="form-horizontal" id="form-create_port_forwading" method="post" accept-charset="utf-8">
      <div class="modal-body">
            
            {!! csrf_field() !!}
            

            <div class="form-group">
                <label for="name" class="col-sm-2 control-label">Mechine Name</label>
                <div class="col-sm-10">
                    <input type="text" name="mechine_name" value="" id="mechine_name" class="form-control" />
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="col-sm-2 control-label">Mechine Desc</label>
                <div class="col-sm-10">
                  <textarea name="mechine_desc" value="" id="mechine_desc" class="form-control"></textarea>
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="col-sm-2 control-label">dst port</label>
                <div class="col-sm-10">
                    <input type="text" name="dst-port" value="" id="dst-port" class="form-control" placeholder="1234"/>
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="col-sm-2 control-label">To addresses</label>
                <div class="col-sm-10">
                    <input type="text" name="to-addresses" value="" id="to-addresses" class="form-control" placeholder="192.168.1.69"/>
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="col-sm-2 control-label">To ports</label>
                <div class="col-sm-10">
                    <input type="text" name="to-ports" value="" id="to-ports" class="form-control" placeholder="8080"/>
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
<div id="editPortForwadingModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
        <form action="#edit" class="form-horizontal" id="form-edit_port_forwading" method="put" accept-charset="utf-8">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Edit PortForwading</h4>
      </div>
      <div class="modal-body">
            
            {!! csrf_field() !!}
            
            <input type="hidden" name="editPortForwading_id" value="" id="editPortForwading_uID" class="form-control editPortForwading"/>
            <div class="form-group">
                <label for="name" class="col-sm-2 control-label">name</label>
                <div class="col-sm-10">
                    <input type="text" name="editPortForwading_name" value="" id="editPortForwading_name" class="form-control editPortForwading"/>
                </div>
            </div>
            <div class="form-group">
                <label for="email" class="col-sm-2 control-label">Email</label>
                <div class="col-sm-10">
                    <input type="email" name="editPortForwading_email" value="" id="editPortForwading_email" class="form-control editPortForwading"/>
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
            <p>Are you sure want to delete this port forwading..?</p>
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
@stop
@section('js')
    <script src="{{ asset('vendor/adminlte/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/adminlte/vendor/datatables/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('vendor/adminlte/vendor/toastr-master/toastr.js') }}"></script>
    <script src="{{ asset('vendor/adminlte/vendor/blockui-master/jquery.blockUI.js') }}"></script>
    <script src="{{ asset('vendor/adminlte/vendor/bootstrap-toggle-master/js/bootstrap-toggle.js') }}"></script>
    <script src="{{ asset('js/dll/port_forwading/list.js') }}"></script>
    <script type="text/javascript" language="javascript" > 
        
    	list.baseUrl = "{{ url('') }}/"; 
    	list.init();
    </script>
@stop
