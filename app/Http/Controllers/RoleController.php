<?php

namespace App\Http\Controllers;

use App\Group_access;
use App\Groups;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use DB;
use Illuminate\Support\Facades\Validator;


class RoleController extends Controller
{
     /*
    *change "$Var" to example : $varUsers
    *change **TBLNAME** to example : Users
    *change **tblname** to example : users
    */
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $groups = Groups::all();
        $modules = $this->getModules(config('adminlte.menu'));
        
        // echo json_encode($modules);
        return view('role.list', compact('groups','modules'));
    }

    public function getModules($menus,&$modules = array())
    {
        

        foreach ($menus as $key => $value) {
            if (!empty($value['module_code'])) {
                // echo print_r($value);
                $modules[$value['module_code']] = [
                                "text" => $value['text'],
                                "module_code" => $value['module_code'],
                            ];
                if (!empty($value['submenu'])) {
                    $this->getModules($value['submenu'], $modules);
                }
            }
        }

        return $modules;
    }
    

    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function anyData()
    {
        $role = DB::table('group_access')
            ->select(['group_access.id', 'group_access.created_at',
                'group_access.module_code', 'group_access.createAcc', 'group_access.readAcc', 'group_access.updateAcc', 'group_access.deleteAcc']); #THISCHANGE
        

        $datatables =  app('datatables')->of($role);
        if ($groupId = $datatables->request->get('groupId')) {
            $role->where('group_access.group_id', $groupId);
        }
        return Datatables::of($role)
            // ->addColumn('module_name', function ($role) {
            //         return '<a href="#edit-'.$role->id.'" class="btn btn-xs btn-primary" onclick="list.editModal('.$role->id.')"><i class="glyphicon glyphicon-edit"></i> Edit</a> <a href="#delete-'.$role->id.'" class="btn btn-xs btn-danger" onclick="list.deleteModal('.$role->id.')"><i class="glyphicon glyphicon-remove"></i> Delete</a>';
            //     })
            ->editColumn('module_code', function ($role) {
                $modules = $this->getModules(config('adminlte.menu'));
                
                return $modules[$role->module_code]['text'] ?? "UNDEFINED";

                })
            ->editColumn('createAcc', function ($role) {
                $checked =  ($role->createAcc) ? "checked" : "";
                $checkedHtml =  ($role->createAcc) ? '<i class="fa fa-check"></i>' : '<i class="fa fa-remove"></i>';
                $activeHTML = '<a href="#deactivate-'.$role->id.'" onclick="list.changeStatus('.$role->id.','."'createAcc'".')"><input type="checkbox" class="btnC" '.$checked.' id="createAcc'.$role->id.'"></a>';
                return checkAccess('roles','editAcc') ? $activeHTML : $checkedHtml;

                })
            ->editColumn('readAcc', function ($role) {
                $checked =  ($role->readAcc) ? "checked" : "";
                $checkedHtml =  ($role->readAcc) ? '<i class="fa fa-check"></i>' : '<i class="fa fa-remove"></i>';

                $activeHTML = '<a href="#deactivate-'.$role->id.'" onclick="list.changeStatus('.$role->id.','."'readAcc'".')"><input type="checkbox" '.$checked.' class="btnC" id="readAcc'.$role->id.'"></a>';
                return checkAccess('roles','editAcc') ? $activeHTML : $checkedHtml;
                })
            ->editColumn('updateAcc', function ($role) {
                $checked =  ($role->updateAcc) ? "checked" : "";
                $checkedHtml =  ($role->updateAcc) ? '<i class="fa fa-check"></i>' : '<i class="fa fa-remove"></i>';
                $activeHTML = '<a href="#deactivate-'.$role->id.'" onclick="list.changeStatus('.$role->id.','."'updateAcc'".')"><input type="checkbox" '.$checked.' class="btnC" id="updateAcc'.$role->id.'"></a>';
                return checkAccess('roles','editAcc') ? $activeHTML : $checkedHtml;
                })
            ->editColumn('deleteAcc', function ($role) {
                $checked =  ($role->deleteAcc) ? "checked" : "";
                $checkedHtml =  ($role->deleteAcc) ? '<i class="fa fa-check"></i>' : '<i class="fa fa-remove"></i>';
                $activeHTML = '<a href="#deactivate-'.$role->id.'" onclick="list.changeStatus('.$role->id.','."'deleteAcc'".')"><input type="checkbox" '.$checked.' class="btnC" id="deleteAcc'.$role->id.'"></a>';
                return checkAccess('roles','editAcc') ? $activeHTML : $checkedHtml;
                })
            ->escapeColumns([])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function assign(Request $request, $groupId)
    {
        $validator = Validator::make($request->all(), [
            'selectRole' => 'required|string|max:25',
        ]);

        if ($validator->passes()) {
            $checkAcc = DB::table('group_access')
                        ->where('group_id' , $groupId)
                        ->where('module_code', $request->input('selectRole'))
                        ->count()
                        ;


            if ($checkAcc == 0) {
                $resultID = DB::table('group_access')->insertGetId(
                    array(  
                        'group_id' => $groupId,
                        'module_code' => $request->input('selectRole'),
                        'readAcc' => 0,
                        'createAcc' => 0,
                        'updateAcc' => 0,
                        'deleteAcc' => 0,
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
                            'status'=>'exist',
                            'statusCode'=> 502,
                            'desc'=>'role already exist',
                            // 'error'=> ['role already exist'],
                        ];
            }
        } else {

            return response()->json([
                                        'status'=>'validate',
                                        'statusCode'=> 501,
                                        'desc'=>'Validate',
                                        'error'=> $validator->errors()->all()
                                    ]);
        }

        return response()->json($result);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Group_access  
     * @return \Illuminate\Http\Response
     */
    public function get($uID)
    {
        $dataGroup_access = Group_access::where('id', $uID)->first();
        // $user = Group_access::findOrFail($uID);
                    // ->where('id', $uID)->first();

       return response()->json([
                                    'status'=>'success',
                                    'statusCode'=>'202',
                                    'data'=> $dataGroup_access,
                                    'desc'=>'exists',
                                ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Group_access  $role
     * @return \Illuminate\Http\Response
     */
    public function active($uID, $action, $active)
    {
        //
        // $active = $active == "true" ? 1 : 0 ;
        DB::table('group_access')
        ->where('id', $uID)
            ->update([$action => $active]);

         return response()->json([
                                    'status'=>'success',
                                    'statusCode'=>'202',
                                    'active'=> $active,
                                    'desc'=>'success update data',
                                ]);
    }

    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Users  $users
     * @return \Illuminate\Http\Response
     */
    public function delete($uID)
    {
        Group_access::where('id', $uID)
            ->delete();
        return response()->json([
                                        'status'=>'delete',
                                        'statusCode'=>'204',
                                        'desc'=>'success delete data'
                                    ]);
    }
}
