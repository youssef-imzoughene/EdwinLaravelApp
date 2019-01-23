<?php

namespace App\Http\Controllers;

use App\User;
use App\Role;
use App\Photo;
use Illuminate\Http\Request;
use App\Http\Requests\UsersRequest;
use App\Http\Requests\UserEditRequest;
use Illuminate\Support\Facades\Session;

use Carbon;

class AdminUsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        return view('admin.users.index',compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::lists('name','id')->all();
        return view('admin.users.create',compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(UsersRequest $request)
    {
      //return $request->all();

      if(trim($request->password) == ''){
        $input = $request->except('password');
      }else{
        $input = $request->all();
        $input['password']=bcrypt($input['password']);
      }




      if($file = $request->file('photo_id')){
        $name = time() . $file->getClientOriginalName();
        $file->move('images',$name);
        $photo = Photo::create(['photo_path'=>$name]);
        //return 'photo exist';
        $input['photo_id']=$photo->id;
      }



      $user = User::create($input);
      //return $user;
      return redirect('/admin/users');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('admin.users.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::lists('name','id')->all();
        return view('admin.users.edit',compact('user','roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UserEditRequest $request, $id)
    {
      $user = User::find($id);

      if(trim($request->password) == ''){
        $input = $request->except('password');
      }else{
        $input = $request->all();
        $input['password']=bcrypt($input['password']);
      }

      if($file = $request->file('photo_id')){
        $name = time() . $file->getClientOriginalName();
        $file->move('images',$name);
        $photo = Photo::create(['photo_path'=>$name]);
        //return 'photo exist';
        $input['photo_id']=$photo->id;
      }

      $user->update($input);
      //return $request->all();
      return redirect('/admin/users');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      $user = User::find($id);
      $path = public_path().$user->photo->getFileAttribute($user->photo->photo_path);
      //return $path;
      unlink($path);
      $user->delete();

      Session::flash('deleted_user','The user has been deleted');

      return redirect('/admin/users');
    }
}
