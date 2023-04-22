<?php
 namespace App\Http\Controllers; 

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Arr;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller

{
    function __construct()
    {
        // set permission
         $this->middleware('permission:user-list|user-create|user-edit|user-delete', ['only' => ['index','show']]);
         $this->middleware('permission:user-create', ['only' => ['create','store']]);
         $this->middleware('permission:user-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:user-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        //$data = User::orderBy('id','DESC')->paginate(5);        
        // return view('users.index',compact('data'))->with('i', ($request->input('page', 1) - 1) * 5);
        if ($request->ajax()) {
            $data = User::with('roles')->get();

            return DataTables::of($data)->addIndexColumn()
            ->addColumn('user_roles', function ($row) {
                if (count($row->roles) > 0) {
                    $user_role =  $row->roles->pluck('name')->implode('<br>') ;
                } else {
                    $user_role = 'No Roles Assigned';
                }
                return $user_role;
            })
                ->addColumn('action', function ($row) {
                    $btn = '
                    <a href=" '.route('users.show', $row->id).' " class="show btn btn-info btn-sm">Show</a>
                    <a href=" '.route('users.edit', $row->id).' "  class="edit btn btn-success btn-sm ">Edit</a>
                    <a href=" '.route('users.delete', $row->id).' "  class="delete btn btn-danger btn-sm ">Delete</a>
                    ';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('users.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()
    {
        $roles = Role::pluck('name','name')->all();
        return view('users.create',compact('roles'));
    }
     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
            'roles' => 'required'
        ]);   

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);    

        $user = User::create($input);
        $user->assignRole($request->input('roles'));    

        return redirect()->route('users.index')->with('success','User created successfully');
    }    

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function show($id)
    {
        $user = User::findorfail($id);
        return view('users.show',compact('user'));
    }    

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        $roles = Role::pluck('name','name')->all();
        $userRole = $user->roles->pluck('name','name')->all();    

        return view('users.edit',compact('user','roles','userRole'));
    }   

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'same:confirm-password',
            'roles' => 'required'
        ]);
    

        $input = $request->all();
        if(!empty($input['password'])){ 
            $input['password'] = Hash::make($input['password']);
        }else{
            $input = Arr::except($input,array('password'));
        }

        $user = User::find($id);
        $user->update($input);
        DB::table('model_has_roles')->where('model_id',$id)->delete();
        $user->assignRole($request->input('roles'));

        return redirect()->route('users.index')->with('success','User updated successfully');

    } 

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       $user=  User::findorfail($id);
       $user->delete($id);
        return redirect()->route('users.index')->with('success','User deleted successfully');
    }

}