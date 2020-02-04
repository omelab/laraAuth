<?php
namespace App\Http\Controllers;

use App\Writer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use DB;
use Hash;

class WriterController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard(Request $request)
    {
        $title = "Dashboard";
        return view('writers.dashboard',compact('title'));
    }
 
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request)
    {
        $data = Writer::orderBy('id','DESC')->paginate(5);

        return view('writers.list',compact('data'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::pluck('name','name')->all();
        return view('writers.create',compact('roles'));
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
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|same:confirm-password',
            'roles' => 'required'
        ]);

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        $writer = Writer::create($input);
        //$writer->syncRoles([$request->input('roles'), 'writer']);

        $writer->assignRole($request->input('roles')); 

        return redirect()->route('writers.list')->with('success','Writer created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Writer  $writers
     * @return \Illuminate\Http\Response
     */
    public function show(Writer $writers)
    {
       // $writers = Writer::find($id);
        return view('writers.show',compact('writers'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Writer  $writer
     * @return \Illuminate\Http\Response
     */
    public function edit(Writer $writer)
    {

        //$writers = Writer::find($id);
        $roles = Role::pluck('name','name')->all();
        $writerRole = $writer->roles->pluck('name','name')->all();
        return view('writers.edit',compact('writer','roles','writerRole'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Writer  $writers
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Writer $writer)
    {
        $id = $writers->id;

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:writers,email,'.$id,
            'password' => 'same:confirm-password',
            'roles' => 'required'
        ]);

        $input = $request->all();

        if(!empty($input['password'])){ 
            $input['password'] = Hash::make($input['password']);
        }else{
            $input = $request->except(['password']);
        }
 
        $writer->update($input);

        DB::table('model_has_roles')->where('model_id',$id)->delete();

        $writer->assignRole($request->input('roles')); 

        return redirect()->route('writers.list')->with('success','Writer updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\writer  $writer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Writer $writer)
    {
        Writer::find($id)->delete();

        return redirect()->route('writers.list')
            ->with('success','Writer deleted successfully');
    }
}
