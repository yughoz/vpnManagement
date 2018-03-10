<?php

namespace App\Http\Controllers;

use App\Ports;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use DB;

class PortsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index($uID)
    {
        $mikrotik = DB::table('mikrotik')->where('id', $uID)->first();
        return view('port_forwading.list',compact('mikrotik'));
    }
    

    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function anyData()
    {
        $ports = DB::table('port_forwading')
            ->select(['port_forwading.mechine_name', 'port_forwading.id', 'port_forwading.to-addresses', 'port_forwading.to-ports', 'port_forwading.dst-port',
                'port_forwading.updated_at']);
            
        return Datatables::of($ports)
            ->addColumn('action', function ($ports) {
                    return '<a href="#edit-'.$ports->id.'" class="btn btn-xs btn-primary" onclick="list.editModal('.$ports->id.')"><i class="glyphicon glyphicon-edit"></i> Edit</a> <a href="#delete-'.$ports->id.'" class="btn btn-xs btn-danger" onclick="list.deleteModal('.$ports->id.')"><i class="glyphicon glyphicon-remove"></i> Delete</a>';
                })
            ->escapeColumns([])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->passes()) {
            $resultID = Port_forwading::insertGetId(
                array(  
                    'name' => $request->input('name'),
                    'email' => $request->input('email'),
                    'phone' => $request->input('phone'),
                    'password' => bcrypt($request->input('password')),
                    'group_id' =>  config('adminlte.default_group'),
                    'created_at' => new \DateTime(),
                    'updated_at' => new \DateTime(),
                    )
            );

            return response()->json([
                                        'status'=>'success',
                                        'statusCode'=>'201',
                                        'desc'=>'success insert data',
                                        'lastID'=> $resultID,
                                        'success'=>'Added new records.'
                                    ]);
        } else {

            return response()->json([
                                        'status'=>'validate',
                                        'statusCode'=> 501,
                                        'desc'=>'Validate',
                                        'error'=> $validator->errors()->all()
                                    ]);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Port_forwading  
     * @return \Illuminate\Http\Response
     */
    public function get($uID)
    {
        $dataPort_forwading = Port_forwading::where('id', $uID)->first();
        // $user = Port_forwading::findOrFail($uID);
                    // ->where('id', $uID)->first();

       return response()->json([
                                    'status'=>'success',
                                    'statusCode'=>'202',
                                    'data'=> $dataPort_forwading,
                                    'desc'=>'exists',
                                ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Port_forwading  $ports
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $uID)
    {
        $pars = array();
        foreach ($request->all() as $key => $value) {
            $pars[str_replace("editPort_forwading_", "", $key)] = $value;
        }
        // echo json_encode($pars);
        $validator = Validator::make($pars, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'group_id' => 'required|integer',
            'phone' => 'required|numeric',
            // 'password' => 'required|string|min:6|same:password_confirm',
            // 'password_confirm' => 'required|string|min:6|',
        ]);

        if ($validator->passes()) {
            Port_forwading::where('id', $uID)
                    ->update([
                                'name' => $pars['name'],
                                'email' => $pars['email'],
                                'phone' => $pars['phone'],
                                'group_id' => $pars['group_id'],
                                // 'group_id' =>  config('adminlte.default_group'),
                            ]);

            return response()->json([
                                        'status'=>'success',
                                        'statusCode'=>'202',
                                        'desc'=>'success update data',
                                        'success'=>'Added new records.'
                                    ]);
        } else {;
            return response()->json([
                                        'status'=>'validate',
                                        'statusCode'=>'501',
                                        'desc'=>'Validate',
                                        'error' => $validator->errors()->all()
                                    ]);
        }
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Users  $users
     * @return \Illuminate\Http\Response
     */
    public function delete($uID)
    {
        Port_forwading::where('id', $uID)
            ->delete();
        return response()->json([
                                        'status'=>'delete',
                                        'statusCode'=>'204',
                                        'desc'=>'success delete data'
                                    ]);
    }
}
