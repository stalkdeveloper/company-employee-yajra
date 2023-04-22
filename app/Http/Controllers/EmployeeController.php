<?php

namespace App\Http\Controllers;

use App\Events\EmployeeEvent;
use App\Models\Company;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Contracts\DataTable;
use Yajra\DataTables\Facades\DataTables;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $data = Employee::latest()->paginate(10);
        // return view('employee.index', compact('data'));

        if ($request->ajax()) {
            $data = Employee::select('id', 'first_name', 'last_name', 'email', 'phone', 'company_name')->get();

            return DataTables::of($data)->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '
                    <a href=" '.route('employee.show', $row->id).'" class="delete btn btn-info btn-sm">Show</a>
                    <a href=" '.route('employee.edit', $row->id).'"  class="edit btn btn-success btn-sm ">Edit</a>
                    <a href=" '.route('employee.delete', $row->id).'"  class="edit btn btn-danger btn-sm ">Delete</a>
                    ';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('employee.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $companies=Company::all();
        return view('employee.create',compact('companies'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name'=>'required',
            'last_name'=>'required',
            'email'=>'required',
            'phone'=>'required',
            'company_name'=>'required',
            //'user_id' => Auth()->user()->id,

        ]);
        $data = $request->all();  
        $data['user_id'] = Auth()->user()->id;

        Employee::create($data);

        $mail = ['first_name'=>$data['first_name'], 'company_name' => $data['company_name'],
                     'email' =>$data['email'], 'user' => Auth()->user()->name, 'user_id' => $data['user_id'],
                      'message' => 'Congrats, Your company is an allotted.'
                    ];
        event(new EmployeeEvent($mail));

        return redirect()->route('employee.index')->with('success','Company Employee created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $employee = Employee::findorfail($id);
        return view('employee.show',compact('employee'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $companies=Company::all();
        $employee = Employee::findorfail($id);
        return view('employee.edit',compact('employee', 'companies'));
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
        $request->validate([
            'first_name'=>'required',
            'last_name'=>'required',
            'email'=>'required',
            'phone'=>'required',
        ]);

        $data = $request->all();
        $data['user_id'] = Auth()->user()->id;

        $mail = ['first_name'=>$data['first_name'], 'company_name' => $data['company_name'],
                     'email' =>$data['email'], 'user' => Auth()->user()->name, 'user_id' => $data['user_id'],
                      'message' => 'Congrats, Your company is an Updated.'
                    ];
        
        event(new EmployeeEvent($mail));

        $employee = Employee::findorfail($id);   
        $employee->update($data);  
        
        return redirect()->route('employee.index')->with('success','Company Employee details update successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Employee $employee)
    {
    
    $data = Employee::get();
    $employee->delete($data);
    
        return redirect()->route('employee.index')->with('success','Company Employee details deleted successfully');
    }
}
