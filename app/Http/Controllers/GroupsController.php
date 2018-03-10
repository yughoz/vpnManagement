<?php

namespace App\Http\Controllers;

use App\Groups;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use DB;
use Illuminate\Support\Facades\Validator;

class GroupsController extends Controller
{

    /*
    *change "**$Var**" to example : $varUsers
    *change **TBLNAME** to example : Users
    *change **tblname** to example : users
    */


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->middleware('auth');
        $this->log = new \App\library\logging;
        $this->log->request = $request->all();
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('groups.list');
    }

    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function anyData()
    {
        $groups = DB::table('groups')
            ->select(['groups.name', 'groups.id', 'groups.description', 'groups.bgcolor']);
            
        $this->log->apiLog('api_list_group');
        return Datatables::of($groups)
            ->addColumn('action', function ($groups) {
                $actionHtml = "";
                if (checkAccess('groups','updateAcc')) {
                    $actionHtml .= '<a href="#edit-'.$groups->id.'" class="btn btn-xs btn-primary" onclick="list.editModal('.$groups->id.')"><i class="glyphicon glyphicon-edit"></i> Edit</a> ';
                }
                if (checkAccess('groups','updateAcc')) {
                    $actionHtml .= '<a href="#delete-'.$groups->id.'" class="btn btn-xs btn-danger" onclick="list.deleteModal('.$groups->id.')"><i class="glyphicon glyphicon-remove"></i> Delete</a> ';
                }
                    return empty($actionHtml) ? "No action" : $actionHtml;
                })

            /*->editColumn('bgcolor', function ($groups) {
                return '<i class="fa fa-stop" style="color:'.$groups->bgcolor.'"></i>';
                })*/
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
            'description' => 'required|string|max:255',
            'bgcolor' => 'required|string|max:20',
        ]);

        if ($validator->passes()) {
            $resultID = Groups::insertGetId(
                array(  
                    'name' => $request->input('name'),
                    'description' => $request->input('description'),
                    'bgcolor' => $request->input('bgcolor')
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
                            'status'=>'validate',
                            'statusCode'=> 501,
                            'desc'=>'Validate',
                            'error'=> $validator->errors()->all()
                        ];
        }

        $this->log->apiLog('api_create_groups',$result);        
        return response()->json($result);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Groups  
     * @return \Illuminate\Http\Response
     */
    public function get($uID)
    {
        $dataGroups = Groups::where('id', $uID)->first();
        // $user = Groups::findOrFail($uID);
                    // ->where('id', $uID)->first();

        $this->log->apiLog('api_get_groups',$uID);
        return response()->json([
                                    'status'=>'success',
                                    'statusCode'=>'202',
                                    'data'=> $dataGroups,
                                    'desc'=>'exists',
                                ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Groups  $groups
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $uID)
    {
        $pars = array();
        foreach ($request->all() as $key => $value) {
            $pars[str_replace("editGroups_", "", $key)] = $value;
        }
        $validator = Validator::make($pars, [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'bgcolor' => 'required|string|max:20',
            // 'password' => 'required|string|min:6|same:password_confirm',
            // 'password_confirm' => 'required|string|min:6|',
        ]);

        if ($validator->passes()) {
            $backUp = Groups::where('id', $uID)->first();
            $this->log->apiLog('backup_update_groups',$backUp,'backup');
            Groups::where('id', $uID)
                    ->update([
                                'name' => $pars['name'],
                                'description' => $pars['description'],
                                'bgcolor' => $pars['bgcolor'],
                            ]);

            $result = [
                            'status'=>'success',
                            'statusCode'=>'202',
                            'desc'=>'success update data',
                            'success'=>'Added new records.'
                        ];
        } else {
            $result = [
                            'status'=>'validate',
                            'statusCode'=>'501',
                            'desc'=>'Validate',
                            'error' => $validator->errors()->all()
                        ];
        }
        $this->log->apiLog('api_get_groups',$result);
        return response()->json($result);
    }
     
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Users  $users
     * @return \Illuminate\Http\Response
     */
    public function delete($uID)
    {
        $backUp = Groups::where('id', $uID)->first();
        $this->log->apiLog('backup_delete_groups',$backUp,'backup');
        DB::table('group_access')
        ->where('id', $uID)
            ->delete();
        Groups::where('id', $uID)
            ->delete();
        return response()->json([
                                        'status'=>'delete',
                                        'statusCode'=>'204',
                                        'desc'=>'success delete data'
                                    ]);
    }
}
