<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\Company;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {  
        return view('company.index');
    }

    public function getcompany(Request $request)
    {
        if ($request->ajax()) {
            $data = Company::latest()->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '
                   <a href=" ' . route('company.show', $row->id) . '" class="delete btn btn-info btn-sm">Show</a>
                   <a href=" ' . route('company.edit', $row->id) . '"  class="edit btn btn-success btn-sm ">Edit</a>
                   <a href=" ' . route('company.delete', $row->id) . '"  class="edit btn btn-danger btn-sm ">Delete</a>
                        ';

                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    } 



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('company.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $data = $request->all();  
 
        if($request->hasfile('image')){
            $file = $request->file('image');
            $extention = $file->getClientOriginalExtension();
            $filename = time().'.'.$extention;
            $file->move('uploads/logo/', $filename);
            $data['image'] = $filename;
        }
        $data['user_id'] = Auth()->user()->id;

        Company::create($data)->save();        
        return redirect()->route('company.index')->with('success','Company created successfully.');
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {   $company=Company::findorfail($id);
        return view('company.show',compact('company'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {   $company=Company::findorfail($id);
        return view('company.edit',compact('company'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, $id)
    {
        $request->validate([
            'company_name' => 'required',
            'email'=>'required',
            'image' => 'required',
            'website'=>'required',           
        ]); 

        $data = $request->all();  
 
        if($request->hasfile('image')){
            $file = $request->file('image');
            $extention = $file->getClientOriginalExtension();
            $filename = time().'.'.$extention;
            $file->move('uploads/logo/', $filename);
            $data['image'] = $filename;
        }
        $company=Company::findorfail($id);
        $company->update($data);        
        return redirect()->route('company.index')->with('success','Company update successfully.');
        //return view('company.index', compact('company'))->with('success','Company details update successfully');;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       
        $company=Company::findorfail($id);
        $Image_Path = 'image/'.$company->image;
        unset($Image_Path);
        $company->delete();
        return redirect()->route('company.index')->with('success','Company details deleted successfully');
    }
}
