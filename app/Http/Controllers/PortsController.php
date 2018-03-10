<?php

namespace App\Http\Controllers;

use App\Port_forwading;
use App\Ports;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use DB;
use Illuminate\Support\Facades\Storage;
use Chumper\Zipper\Zipper;

class PortsController extends Controller
{
    public function __construct(Request $request)
    {
        $this->middleware('auth');
        $this->log      = new \App\library\logging;
        $this->mikrotik = new \App\library\routerosAPI;
        $this->mikrotik->debug = false;
        $this->mikrotik->attempts = 1;
        $this->mikrotik->delay = 0;
        $this->log->request = $request->all();
    }
    public function index($uID)
    {
        $mikrotik = DB::table('mikrotik')->where('id', $uID)->first();
        $this->checkFileCertificate($mikrotik);
        return view('port_forwading.list',compact('mikrotik'));
    }
    public function tested()
    {
        if ($this->mikrotik->connect('192.168.88.1', 'admin', '')) {
            $ARRAY = $this->mikrotik->comm('/ip/firewall/nat/print');

            echo json_encode($ARRAY[count($ARRAY)-1]);
            // echo json_encode($ARRAY);
            $this->mikrotik->disconnect();
        }
    }
    

    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function anyData()
    {
        $ports = DB::table('port_forwadings')
            ->select(['port_forwadings.mechine_name', 'port_forwadings.id', 'port_forwadings.to-addresses', 'port_forwadings.to-ports', 'port_forwadings.dst-port',
                'port_forwadings.updated_at']);
            
        $this->log->apiLog('api_list_port');
        return Datatables::of($ports)
            ->addColumn('action', function ($ports) {
                    $actionHtml = "";
                    if (checkAccess('ports','readAcc')) {
                         $actionHtml .= '<a href="#config-'.$ports->id.'" class="btn btn-xs btn-primary"  onclick="list.listConfig('.$ports->id.')"><i class="glyphicon glyphicon-download-alt"></i>Config</a> ';
                    }
                    if (checkAccess('ports','updateAcc')) {
                    	 $actionHtml .= '<a href="#edit-'.$ports->id.'" class="btn btn-xs btn-primary" onclick="list.editModal('.$ports->id.')"><i class="glyphicon glyphicon-edit"></i> Edit</a> ';
                    }
                    if (checkAccess('ports','deleteAcc')) {
                    	 $actionHtml .= '<a href="#delete-'.$ports->id.'" class="btn btn-xs btn-danger" onclick="list.deleteModal('.$ports->id.')"><i class="glyphicon glyphicon-remove"></i> Delete</a>';
                    }
                    return empty($actionHtml) ? "No action" : $actionHtml;
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
            'mechine_name'  => 'required|string|max:255',
            'mechine_desc'  => 'required|string|max:255',
            'dst-port'      => 'required|integer',
            'to-ports'      => 'required|integer',
            'mikrotik_id'   => 'required|integer',
            // 'to-addresses'     => 'required|string|max:255',
            'password_secret'  => 'required|string|max:255',
            'user_secret'      => 'required|string|max:255',
        ]);

        if ($validator->passes()) {
            $mikrotik = DB::table('mikrotik')->where('id', $request->input('mikrotik_id'))->first();
            $ipAvailable = $this->checkIPAvailable($mikrotik);
            if ($ipAvailable && $this->mikrotik->connect($mikrotik->ip, $mikrotik->username, $mikrotik->password)) {
                $resultCreateVPN = $this->mikrotik->comm("/ppp/secret/add", [
                          "name"            => $request->input('user_secret'),
                          "profile"         => "default-encryption",
                          "password"        => $request->input('password_secret'),
                          "local-address"   => $mikrotik->ip_range.".1",
                          "remote-address"  => $ipAvailable,
                      ]);
                $this->log->apiLog('mikrotik_create_secret',$resultCreateVPN);

                $resultCreatePort = $this->mikrotik->comm("/ip/firewall/nat/add", [
                              "chain"         => "dstnat",
                              "protocol"      => "tcp",
                              "action"        => "dst-nat",
                              "dst-port"      => $request->input('dst-port'),
                              "to-addresses"  => $ipAvailable,
                              "to-ports"      => $request->input('to-ports'),
                      ]);
                $this->log->apiLog('mikrotik_create_port',$resultCreatePort);
                // $resultList = $this->mikrotik->comm('/ip/firewall/nat/print');
                // $lastData = $resultList[count($resultList)-1];

                $resultList = $this->mikrotik->comm('/ip/firewall/nat/print',['?.id'=> $resultCreatePort]);
                $lastData = $resultList[0];
                $this->log->apiLog('mikrotik_create_port_get',$resultList);
                // echo json_encode($ARRAY);
                $this->mikrotik->disconnect();
                $resultID = Port_forwading::insertGetId(
                    array(  
                        'mikrotik_id'   => $request->input('mikrotik_id'),
                        'mechine_name'  => $request->input('mechine_name'),
                        'mechine_desc'  => $request->input('mechine_desc'),
                        'dst-port'      => $request->input('dst-port'),
                        'to-ports'      => $request->input('to-ports'),
                        'secret_name'   => $request->input('user_secret'),
                        'secret_password'  => $request->input('password_secret'),
                        'to-addresses'  => $ipAvailable,
                        'status_code'   => "0",
                        'status_desc'   => "0",
                        'id_in_router'  => $lastData['.id'],
                        'chain'         => $lastData['chain'],
                        'action'        => $lastData['action'],
                        'protocol'      => $lastData['protocol'],
                        'log'           => $lastData['log'],
                        'log-prefix'    => $lastData['log-prefix'],
                        'bytes'         => $lastData['bytes'],
                        'packets'       => $lastData['packets'],
                        'invalid'       => $lastData['invalid'],
                        'dynamic'       => $lastData['dynamic'],
                        'disabled'      => $lastData['disabled'],
                        'created_at'    => new \DateTime(),
                        'updated_at'    => new \DateTime(),
                        )
                );

                $result = [
                                'status'=>'success',
                                'statusCode'=>'201',
                                'desc'=>'success insert data',
                                'lastID'=> $resultID,
                                'success'=>'Added new records.'
                            ];
            } else {
                $result = [
                            'status'=>'connection_failed',
                            'statusCode'=> 509,
                            'desc'=>'connection failed',
                        ];
            }
        } else {
            $result = [
                            'status'=>'validate',
                            'statusCode'=> 501,
                            'desc'=>'Validate',
                            'error'=> $validator->errors()->all()
                        ];
        }

        $this->log->apiLog('api_create_port',$result);
        return response()->json($result);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Port_forwading  
     * @return \Illuminate\Http\Response
     */
    public function checkIPAvailable($mikrotikCONF,$ipArr ="")
    {
        if ($this->mikrotik->connect($mikrotikCONF->ip, $mikrotikCONF->username, $mikrotikCONF->password)) {

            for ($ip=2; $ip < 255 ; $ip++) { 
                $resultList = $this->mikrotik->comm('/ppp/secret/print',['?remote-address'=> $mikrotikCONF->ip_range.".".$ip]);
                if (empty($resultList)) {
                   // $this->mikrotik->disconnect();
                   return $mikrotikCONF->ip_range.".".$ip;
                }
            }
        }

        return false;
        
    }
    public function checkFileCertificate($mikrotikCONF)
    {
        // $contents = Storage::get('public/miksrotik/test.crt');
        if ($this->mikrotik->connect($mikrotikCONF->ip, $mikrotikCONF->username, $mikrotikCONF->password)) {

            $resultList = $this->mikrotik->comm('/file/print');
            foreach ($resultList as $key => $value) {
                if (!empty($value['size']) && !empty($value['contents'])) {
                    $contents = Storage::exists('public/mikrotik/'.$value['name']);
                    /*if (!$contents) {
                        echo $value['name'];
                    }*/
                    Storage::put('public/mikrotik/certificate/'.$value['name'], $value['contents']);
                }
                // echo json_encode($contents);
                # code...
            }
            $this->mikrotik->disconnect();
        }
 
    }
    public function listConfig($uID)
    {
        $dataPort_forwading = Port_forwading::where('id', $uID)->first();
        $zipfile = 'config_vpn/vpn_'.$uID.'.zip';
        if (!is_file(public_path($zipfile))) {
            $certificatePath = "public/mikrotik/certificate/";
            
            $confTemplate     = Storage::get('public/mikrotik/confTemplate/confTemplate.conf');
            $confTemplatePath = 'public/mikrotik/confOpenVPN/'.$uID;
            $loginFile = $dataPort_forwading->secret_name."\n".$dataPort_forwading->secret_password;
            Storage::put($confTemplatePath.'/vpnConf.conf', $confTemplate);
            Storage::put($confTemplatePath.'/login', $loginFile);
            Storage::put($confTemplatePath.'/password', "12345678");
           
            $filesConfig    = glob(storage_path("app/".$confTemplatePath.'/*'));
            $filesSecret = glob(storage_path("app/".$certificatePath.'*'));
            $filesZip = array_merge($filesConfig,$filesSecret);
            $zipper = new Zipper();
            $zipper->make($zipfile)->add($filesZip)->close();            
        }
        return response()->download(public_path($zipfile));
    }
    public function listConfigBU($uID)
    {
        $zipfile = 'config_vpn/vpn_'.$uID.'.zip';
        $certificatePath = "public/mikrotik/certificate/";
        $files = Storage::allFiles($certificatePath);
        foreach ($files as $key => $file) {
            // $confTemplate     = Storage::get('public/mikrotik/confTemplate/confTemplate.conf');
            // Storage::put($confTemplatePath.'/vpnConf.conf', $confTemplate);
            $url[] = Storage::url($file);
        }

        $confTemplate     = Storage::get('public/mikrotik/confTemplate/confTemplate.conf');
        $confTemplatePath = 'public/mikrotik/confOpenVPN/'.$uID;
        $dataPort_forwading = Port_forwading::where('id', $uID)->first();
        $loginFile = $dataPort_forwading->secret_name."\n".$dataPort_forwading->secret_password;
        Storage::put($confTemplatePath.'/vpnConf.conf', $confTemplate);
        Storage::put($confTemplatePath.'/login', $loginFile);
        Storage::put($confTemplatePath.'/password', "12345678");
        // $confTemplate = "public/mikrotik/confOpenVPN/".$uID."/vpnConf.conf";
        // Storage::copy('public/mikrotik/confTemplate/confTemplate.conf', $confTemplate);
        $url[] = Storage::url($confTemplatePath.'/vpnConf.conf');
        $url[] = Storage::url($confTemplatePath.'/login');
        $url[] = Storage::url($confTemplatePath.'/password');
        // echo $url;
        $result = [
                                'status'=>'success',
                                'statusCode'=>'200',
                                'success'=>'Added new records.',
                                'data' => $url
                            ];
        // $filesZip = glob($confTemplatePath.'/*');
        $filesConfig    = glob(storage_path("app/".$confTemplatePath.'/*'));
        $filesSecret = glob(storage_path("app/".$certificatePath.'*'));
        $filesZip = array_merge($filesConfig,$filesSecret);
        $zipper = new Zipper();
        $zipper->make($zipfile)->add($filesZip)->close();;

        // usleep(500000);
        // sleep(3);
        // $checkingFile = is_file(public_path($zipfile));
        // while (!is_file(public_path($zipfile))) {
        //     usleep(100000);
        //     $checkingFile = is_file(public_path($zipfile));

        // }
        if (!is_file(public_path($zipfile))) {
            
        }
        return response()->download(public_path($zipfile));
        // return response()->json($filesZip);
    }
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
    public function getMikrotik($uID)
    {
        $dataPort_forwading = DB::table('mikrotik')->where('id', $uID)->first();
        // $user = Port_forwading::findOrFail($uID);
                    // ->where('id', $uID)->first();

       return response()->json([
                                    'status'=>'success',
                                    'statusCode'=>'202',
                                    'data'=> $dataPort_forwading,
                                    'desc'=>'exists',
                                ]);
    }
    public function checkMikrotik(Request $request)
    {
        if ($this->mikrotik->connect($request->input('ip'), $request->input('username'), $request->input('password'))) {
            $result = [
                            'status'=>'success',
                            'statusCode'=>'200',
                            'desc'=> 'Connection Successful!',
                        ];
            $this->mikrotik->disconnect();
        } else {
            $result = [
                            'status'=>'failed',
                            'statusCode'=>'500',
                            'desc'=>'Connection failed!',
                        ];
        }

        return response()->json($result);
    
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
            $pars[str_replace("editPortForwading_", "", $key)] = $value;
        }
        // echo json_encode($pars);
        $valid =  [
		            'mechine_name'  => 'required|string|max:255',
		            'mechine_desc'  => 'required|string|max:255',
		            'secret_name'   => 'required|string|max:255',
		            'dst-port'      => 'required|integer',
		            'to-ports'      => 'required|integer',
		            // 'to-addresses'  => 'required|string|max:255',
		        ];
        if (!empty($pars['password_secret'])) {
            $valid['password_secret'] = 'required|string|min:6';
        }
        $validator = Validator::make($pars, $valid);

        if ($validator->passes()) {
            $mikrotik = DB::table('mikrotik')->where('id', $pars['mikrotik_id'])->first();
            if ($this->mikrotik->connect($mikrotik->ip, $mikrotik->username, $mikrotik->password)) {
                $dataPort = Port_forwading::where('id', $uID)->first();
                $arrSecret = [
                              "name"      	=> $pars['secret_name'],
                              'numbers'     => $dataPort->secret_name,
                      		];
                if (!empty($pars['secret_password'])) {
	                $arrSecret['password'] = $pars['secret_password'];
	            }
                $resultUpdate['Secret'] = $this->mikrotik->comm("/ppp/secret/set", $arrSecret);
                $resultUpdate['Port'] = $this->mikrotik->comm("/ip/firewall/nat/set", [
                              "dst-port"      => $pars['dst-port'],
                              // "to-addresses"  => $pars['to-addresses'],
                              "to-ports"      => $pars['to-ports'],
                              'numbers'       => $dataPort->id_in_router,
                      ]);
                // $this->log->apiLog('mikrotik_create_port',$resultUpdatePort);
                $arrUpdate = [
                                    'mechine_name'  => $pars['mechine_name'],
                                    'secret_name'   => $pars['secret_name'],
                                    'mechine_desc'  => $pars['mechine_desc'],
                                    'dst-port'      => $pars['dst-port'],
                                    'to-ports'      => $pars['to-ports'],
                                    // 'to-addresses'  => $pars['to-addresses'],
                                ];
                if (!empty($pars['secret_password'])) {
	                $arrUpdate['secret_password'] = $pars['secret_password'];
	            }
                Port_forwading::where('id', $uID)
                        ->update($arrUpdate);
                $result = [
                                'status'=>'success',
                                'statusCode'=>'202',
                                'desc'=>'success update data',
                                'success'=>'Added new records.'
                            ];
            

            } else {
                $result = [
                            'status'=>'connection_failed',
                            'statusCode'=> 509,
                            'desc'=>'connection failed',
                        ];
            }
        } else {;
            $result = [
                            'status'=>'validate',
                            'statusCode'=>501,
                            'desc'=>'Validate',
                            'error' => $validator->errors()->all()
                        ];
        }

        // $this->log->apiLog('api_update_port',array_merge($result,$resultUpdate));
        $tempResult = array_merge($result,$resultUpdate);
        $this->log->apiLog('api_update_port',$tempResult);
        return response()->json($tempResult);
    }
    
    public function updateMikrotik(Request $request, $uID)
    {
        $pars = array();
        foreach ($request->all() as $key => $value) {
            $pars[str_replace("editMikrotik_", "", $key)] = $value;
        }
        // echo json_encode($pars);
        $validator = Validator::make($pars, [
            'router_name'  => 'required|string|max:255',
            'description'  => 'required|string|max:255',
            'username'      => 'required|string|max:255',
            'ip'  => 'required|string|max:255',
            'ip_range'  => 'required|string|max:255',
        ]);

        if ($validator->passes()) {
            DB::table('mikrotik')->where('id', $uID)
                    ->update([
                                'router_name'   => $pars['router_name'],
                                'description'   => $pars['description'],
                                'password'      => $pars['password'],
                                'username'      => $pars['username'],
                                'ip'            => $pars['ip'],
                                'ip_range'      => $pars['ip_range'],
                            ]);
            $result = [
                            'status'=>'success',
                            'statusCode'=>'202',
                            'desc'=>'success update data',
                            'success'=>'Added new records.'
                        ];
        } else {;
            $result = [
                            'status'=>'validate',
                            'statusCode'=>'501',
                            'desc'=>'Validate',
                            'error' => $validator->errors()->all()
                        ];
        }

        $this->log->apiLog('api_update_mikrotik',$result);
            return response()->json($result);
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Users  $users
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request,$mikrotik_id, $uID)
    {
        // 
        $mikrotik = DB::table('mikrotik')->where('id', $mikrotik_id)->first();
        if ($this->mikrotik->connect($mikrotik->ip, $mikrotik->username, $mikrotik->password)) {
            $dataPort = Port_forwading::where('id', $uID)->first();

            $resultRemoveVpn = $this->mikrotik->comm('/ppp/secret/remove',['numbers'=> $dataPort->secret_name]);
            $resultRemove = $this->mikrotik->comm('/ip/firewall/nat/remove',['numbers'=> $dataPort->id_in_router]);

            Port_forwading::where('id', $uID)
                ->delete();

            $result = [
                            'status'=>'delete',
                            'statusCode'=>'204',
                            'desc'=>'success delete data'
                        ];
            $this->log->apiLog('backup_delete_port',$dataPort,'backup');

        } else {
            $result = [
                        'status'=>'connection_failed',
                        'statusCode'=> 509,
                        'desc'=>'connection failed',
                    ];
        }
        $this->log->apiLog('api_delete_port',compact('result','resultRemove','resultRemoveVpn'));
        return response()->json($result);
    }
}
