<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use DB;
use Illuminate\Support\Facades\Validator;

class DefaultController extends Controller
{
    /*
    *change **$Var** to example : $varUsers
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
        return view('**tblname**.list');
    }
    

    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function anyData()
    {
        **$Var** = DB::table('**TBLNAME**')
            ->select(['**TBLNAME**.name', '**TBLNAME**.id', '**TBLNAME**.email', '**TBLNAME**.group_id', '**TBLNAME**.created_at',
                '**TBLNAME**.updated_at', '**TBLNAME**.active']);
            
        return Datatables::of(**$Var**)
            ->addColumn('action', function (**$Var**) {
                    return '<a href="#edit-'.**$Var**->id.'" class="btn btn-xs btn-primary" onclick="list.editModal('.**$Var**->id.')"><i class="glyphicon glyphicon-edit"></i> Edit</a> <a href="#delete-'.**$Var**->id.'" class="btn btn-xs btn-danger" onclick="list.deleteModal('.**$Var**->id.')"><i class="glyphicon glyphicon-remove"></i> Delete</a>';
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
            $resultID = **TBLNAME**::insertGetId(
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
     * @param  \App\**TBLNAME**  
     * @return \Illuminate\Http\Response
     */
    public function get($uID)
    {
        $data**TBLNAME** = **TBLNAME**::where('id', $uID)->first();
        // $user = **TBLNAME**::findOrFail($uID);
                    // ->where('id', $uID)->first();

       return response()->json([
                                    'status'=>'success',
                                    'statusCode'=>'202',
                                    'data'=> $data**TBLNAME**,
                                    'desc'=>'exists',
                                ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\**TBLNAME**  **$Var**
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $uID)
    {
        $pars = array();
        foreach ($request->all() as $key => $value) {
            $pars[str_replace("edit**TBLNAME**_", "", $key)] = $value;
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
            **TBLNAME**::where('id', $uID)
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
        **TBLNAME**::where('id', $uID)
            ->delete();
        return response()->json([
                                        'status'=>'delete',
                                        'statusCode'=>'204',
                                        'desc'=>'success delete data'
                                    ]);
    }

}
