<?php

namespace App\Http\Controllers;

use App\Http\Requests\UsersEditRequest;
use App\Http\Requests\UsersRequest;
use App\Photo;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\User;
use App\Role;
use Illuminate\Support\Facades\Session;

class AdminUsersController extends Controller
{

    public function index()
    {
        //
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        //
        $roles = Role::lists('name', 'id')->all();
        return view('admin.users.create', compact('roles'));
    }

    public function store(UsersRequest $request)
    {
        //
        if(trim($request->password) == ''){
            $input = $request->except('password');
        }else{
            $input = $request->all();

            $input['password'] = bcrypt($request->password);
        }

        if($file = $request->file('photo_id'))
        {
            $name = time() ."_". $file->getClientOriginalName();

            $file->move('images', $name);

            $photo = Photo::create(['file'=>$name]);

            $input['photo_id'] = $photo->id;
        }

        User::create($input);

        Session::flash('userMsg', 'user has been created');

        return redirect('/admin/users');
    }

    public function show($id)
    {
        //
        return view('admin.users.show');
    }

    public function edit($id)
    {
        //
        $user = User::findOrFail($id);

        $roles = Role::lists('name', 'id')->all();

        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(UsersEditRequest $request, $id)
    {
        //
        $user = User::findOrFail($id);

        if(trim($request->password) == '')
        {
            $input = $request->except('password');
        }
        else
            {
            $input = $request->all();

            $input['password'] = bcrypt($request->password);
        }

        if($file = $request->file('photo_id'))
        {
            $name = time() . '_' . $file->getClientOriginalName();

            $file->move('images', $name);

            $photo = Photo::create(['file'=>$name]);

            $input['photo_id'] = $photo->id;
        }

        $user->update($input);

        Session::flash('userMsg', 'user has been updated');

        return redirect('/admin/users');

    }

    public function destroy($id)
    {
        //
        $user = User::findOrFail($id);

        unlink(public_path() . $user->photo->file);

        $user->delete();

        Session::flash('userMsg', 'user has been deleted');

        return redirect('/admin/users');
    }
}
