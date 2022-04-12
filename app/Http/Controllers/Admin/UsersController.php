<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUsersRequest;
use App\Http\Requests\Admin\UpdateUsersRequest;
use Illuminate\Support\Facades\DB;

class UsersController extends Controller {
    /**
     * Display a listing of User.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        if (!Gate::allows('users_manage')) {
            return abort(401);
        }

        $users = User::all();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating new User.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        if (!Gate::allows('users_manage')) {
            return abort(401);
        }
        $roles = Role::get()->pluck('name', 'name');

        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created User in storage.
     *
     * @param \App\Http\Requests\StoreUsersRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUsersRequest $request) {
        if (!Gate::allows('users_manage')) {
            return abort(401);
        }
        $user = User::create($request->all());
        $roles = $request->input('roles') ? $request->input('roles') : [];
        $user->assignRole($roles);

        return redirect()->route('admin.users.index');
    }


    /**
     * Show the form for editing User.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user) {
        if (!Gate::allows('users_manage')) {
            return abort(401);
        }
        $roles = Role::get()->pluck('name', 'name');

        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update User in storage.
     *
     * @param \App\Http\Requests\UpdateUsersRequest $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUsersRequest $request, User $user) {
        if (!Gate::allows('users_manage')) {
            return abort(401);
        }
        try {
            $user->update($request->all());
            $roles = $request->input('roles') ? $request->input('roles') : [];

            $user->syncRoles($roles);

            $userId = $user->id;

            $selDep = $request->get('department');
            $oldVals = DB::table('users')->where('id', '=', $userId)->select('id', 'name', 'email', 'department', 'password')->get();

            $name = $oldVals[0]->name;
            $email = $oldVals[0]->email;
            $department = $oldVals[0]->department;
            $password = $oldVals[0]->password;

            if ($name != $user->name) {
                $name = $user->name;
            }
            if ($email != $user->email) {
                $email = $user->email;
            }
            if ($department != $selDep) {
                $department = $selDep;
            }
            if ($password != password_hash($user->password, PASSWORD_BCRYPT)) {
                $password = $user->password;
            }

            $password = password_hash($password, PASSWORD_BCRYPT);

            DB::table('users')->where('id', $userId)->update(['name' => $name, 'email' => $email, 'department' => $department, 'password' => $password]);

            return view('/edit_user', ['user' => 'Обновление завершено']);

        } catch (\Exception $e) {
            die("Нет подключения к базе данных.");
        }

//        return redirect()->route('admin.users.index');
    }

    public function show(User $user) {
        if (!Gate::allows('users_manage')) {
            return abort(401);
        }

        $user->load('roles');

        return view('admin.users.show', compact('user'));
    }

    /**
     * Remove User from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user) {
        if (!Gate::allows('users_manage')) {
            return abort(401);
        }

        $user->delete();

        return redirect()->route('admin.users.index');
    }

    /**
     * Delete all selected User at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request) {
        if (!Gate::allows('users_manage')) {
            return abort(401);
        }
        User::whereIn('id', request('ids'))->delete();

        return response()->noContent();
    }

}
