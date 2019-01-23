@extends('layouts.admin')

@section('content')

  @if(Session::has('deleted_user'))
  <div class="alert alert-danger">
    {{session('deleted_user')}}
  </div>
  @endif
  <h1>Users</h1>
  <table class="table">
   <thead>
     <tr>
       <th>Id</th>
       <th>Name</th>
       <th>Email</th>
       <th>Role</th>
       <th>Status</th>
       <th>Created</th>
       <th>Updated</th>
       <th>Photo</th>
     </tr>
   </thead>
   <tbody>
     @if($users)
       @foreach ($users as $user)
         <tr>
           <td>{{$user->id}}</td>
           <td><a href="{{route('admin.users.edit',$user->id)}}">{{$user->name}}</a></td>
           <td>{{$user->email}}</td>
           <td>{{$user->role->name}}</td>
           <td>{{$user->is_active ==1 ? 'Active' : 'Not Active'}}</td>
           <td>{{$user->created_at->diffForHumans()}}</td>
           <td>{{$user->updated_at->diffForHumans()}}</td>
           <td>
              <img src="{{$user->photo != null ? $user->photo->getFileAttribute($user->photo->photo_path) : '#'}}" alt="" style="width:100px">
             </td>
         </tr>
       @endforeach
     @endif
   </tbody>
 </table>
@stop
