@extends('adminlte::page')

@section('title', 'AdminLTE')

@section('content_header')
<meta name="csrf-token" content="{{ csrf_token() }}" />
    <h1>VPN </h1>
@stop

@section('content')
    @if(checkAccess('mikrotikconf','readAcc'))
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
                  <div class="form-group col-md-8">
                    <label for="inputEmail3" class="col-sm-3 control-label">Router Name</label>

                    <div class="col-sm-9">
                      <input type="text" readonly="readonly" class="form-control detailtMikrotik" name="router_name" id="routerName" value="{{$mikrotik->router_name}}">
                    </div>
                  </div>
                </div>
                <div class="row ">
                  <div class="form-group col-md-8 ">
                    <label for="inputPassword3" class="col-sm-3 control-label">IP Address</label>

                    <div class="col-sm-9">
                      <input type="text" readonly="readonly" class="form-control detailtMikrotik" id="ip" name="ip" value="{{$mikrotik->ip}}">
                    </div>
                  </div>
                </div>
                <div class="row ">
                  <div class="form-group col-md-8 ">
                    <label for="inputPassword3" class="col-sm-3 control-label">IP Range</label>

                    <div class="col-sm-9">
                      <input type="text" readonly="readonly" class="form-control detailtMikrotik" id="ipRange" name="ip_range" value="{{$mikrotik->ip_range}}">
                    </div>
                  </div>
                </div>
                <div class="row ">
                  <div class="form-group col-md-8 ">
                    <label for="inputPassword3" readonly="readonly" class="col-sm-3 control-label">Username</label>

                    <div class="col-sm-9">
                      <input type="text" readonly="readonly" class="form-control detailtMikrotik" id="username" name="username" value="{{$mikrotik->username}}">
                    </div>
                  </div>
                </div>
                <div class="row ">
                  <div class="form-group col-md-8 ">
                    <label for="inputPassword3" class="col-sm-3 control-label">Description</label>

                    <div class="col-sm-9">
                      <textarea readonly="readonly" class="form-control detailtMikrotik" id="description" name="description">{{$mikrotik->description}}</textarea>
                    </div>
                  </div>
                </div>
              </div>
            </form>
              <!-- /.box-body -->
              @if(checkAccess('mikrotikconf','readAcc'))
              <div class="box-footer">
                <a href="#editMikrotik" class="btn btn-primary btn-flat"  data-toggle="modal" data-target="#editMikrotikModal"><i class="fa fa-edit"></i>Edit Mikrotik</a>
                <!-- <button type="submit" class="btn btn-info pull-right">Sign in</button> -->
              </div>
              @endif
              <!-- /.box-footer -->
          </div>
          <!-- /.box -->
      </div>
    </div>
    @endif
     <div class="row">
        <div class="col-md-12">
             <div class="box">
                @if(checkAccess('ports','createAcc'))
                <div class="box-header with-border">
                    <h3 class="box-title"><a href="#createPortForwading" class="btn btn-block btn-primary btn-flat"  data-toggle="modal" data-target="#createPortForwadingModal"><i class="fa fa-plus"></i> Create VPN</a></h3>
                </div>
                @endif
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
        <h4 class="modal-title">Create VPN</h4>
      </div>
        <form action="#create" class="form-horizontal" id="form-create_port_forwading" method="post" accept-charset="utf-8">
      <div class="modal-body">
            
            {!! csrf_field() !!}
            <input type="hidden" readonly="readonly" name="mikrotik_id" id="mikrotikId" value="{{$mikrotik->id}}">
            

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
                <label for="name" class="col-sm-2 control-label">User secret</label>
                <div class="col-sm-10">
                    <input type="text" name="user_secret" value="" id="user_secret" class="form-control" />
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="col-sm-2 control-label">Password secret</label>
                <div class="col-sm-10">
                    <input type="password" name="password_secret" value="" id="password_secret" class="form-control" />
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="col-sm-2 control-label">dst port</label>
                <div class="col-sm-10">
                    <input type="text" name="dst-port" value="" id="dst-port" class="form-control" placeholder="1234"/>
                </div>
            </div>
            <!-- <div class="form-group">
                <label for="name" class="col-sm-2 control-label">To addresses</label>
                <div class="col-sm-10">
                    <input type="text" name="to-addresses" value="" id="to-addresses" class="form-control" placeholder="192.168.1.69"/>
                </div>
            </div> -->
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
        <h4 class="modal-title">Edit VPN</h4>
      </div>
      <div class="modal-body">
            
            {!! csrf_field() !!}
            
            <input type="hidden" name="editPortForwading_id" value="" id="editPortForwading_uID" class="form-control editPortForwading"/>
             
            <input type="hidden" readonly="readonly" name="editPortForwading_mikrotik_id" id="editPortForwading_mikrotikId" value="{{$mikrotik->id}}">

            <div class="form-group">
                <label for="name" class="col-sm-2 control-label">Mechine Name</label>
                <div class="col-sm-10">
                    <input type="text" name="editPortForwading_mechine_name" value="" id="mechine_name" class="form-control editPortForwading" />
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="col-sm-2 control-label">Mechine Desc</label>
                <div class="col-sm-10">
                  <textarea name="editPortForwading_mechine_desc" value="" id="mechine_desc" class="form-control editPortForwading"></textarea>
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="col-sm-2 control-label">User secret</label>
                <div class="col-sm-10">
                    <input type="text" name="editPortForwading_secret_name" value="" id="editPortForwading_secret_name" class="form-control editPortForwading" />
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="col-sm-2 control-label">Password secret</label>
                <div class="col-sm-10">
                    <input type="password" name="editPortForwading_password_secret" value="" id="editPortForwading_password_secret" class="form-control editPortForwading" />
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="col-sm-2 control-label">dst port</label>
                <div class="col-sm-10">
                    <input type="text" name="editPortForwading_dst-port" value="" id="dst-port" class="form-control editPortForwading" placeholder="1234"/>
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="col-sm-2 control-label">To addresses</label>
                <div class="col-sm-10">
                    <input type="text" readonly="readonly" name="editPortForwading_to-addresses" value="" id="to-addresses" class="form-control editPortForwading" placeholder="192.168.1.69"/>
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="col-sm-2 control-label">To ports</label>
                <div class="col-sm-10">
                    <input type="text" name="editPortForwading_to-ports" value="" id="to-ports" class="form-control editPortForwading" placeholder="8080"/>
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
            <p>Are you sure want to delete this vpn..?</p>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-danger" id="btnDelete">Delete</button>
      </div>
        </form>
    </div>

  </div>
</div>

<!-- Modal -->
<div id="editMikrotikModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
        <form action="#edit" class="form-horizontal" id="form-edit_mikrotik" method="put" accept-charset="utf-8">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Edit Mikrotik Config</h4>
      </div>
      <div class="modal-body">
        <input type="hidden" readonly="readonly" name="editMikrotik_mikrotik_id" id="editMikrotik_mikrotikId" value="{{$mikrotik->id}}">
        <div class="form-group">
          <label for="inputEmail3" class="col-sm-2 control-label editMikrotik">Router Name</label>

          <div class="col-sm-10">
            <input type="text"  class="form-control editMikrotik" name="editMikrotik_router_name" id="routerName" value="{{$mikrotik->router_name}}">
          </div>
        </div>
        <div class="form-group ">
          <label for="inputPassword3" class="col-sm-2 control-label editMikrotik">IP Address</label>

          <div class="col-sm-10">
            <input type="text"  class="form-control editMikrotik" id="ip" name="editMikrotik_ip" value="{{$mikrotik->ip}}">
          </div>
        </div>
        <div class="form-group ">
          <label for="inputPassword3" class="col-sm-2 control-label editMikrotik">IP Range</label>

          <div class="col-sm-10">
            <input type="text"  class="form-control editMikrotik" id="ipRange" name="editMikrotik_ip_range" value="{{$mikrotik->ip_range}}">
          </div>
        </div>
        <div class="form-group ">
          <label for="inputPassword3"  class="col-sm-2 control-label editMikrotik">Username</label>

          <div class="col-sm-10">
            <input type="text"  class="form-control editMikrotik" id="username" name="editMikrotik_username" value="{{$mikrotik->username}}">
          </div>
        </div>
        <div class="form-group ">
          <label for="inputPassword3" class="col-sm-2 control-label editMikrotik">Password</label>

          <div class="col-sm-10">
            <input type="password" class="form-control editMikrotik" id="password" name="editMikrotik_password" value="{{$mikrotik->password}}">
          </div>
        </div>
        <div class="form-group ">
          <label for="inputPassword3" class="col-sm-2 control-label editMikrotik">Description</label>

          <div class="col-sm-10">
            <textarea  class="form-control editMikrotik" id="description" name="editMikrotik_description">{{$mikrotik->description}}</textarea>
          </div>
        </div>
        </div>
      
      <div id="wraperStatus"></div>

      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-info" id="btnCheckConnection" id="">Check Conection</button>
        <button type="submit" class="btn btn-primary" id="btnEdit">Save</button>
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
