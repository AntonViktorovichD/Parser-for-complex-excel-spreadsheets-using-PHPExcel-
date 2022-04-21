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
        $distrs = DB::table('distr_helper')->get();
        $orgs = DB::table('org_helper')->get();

        return view('admin.users.create', compact('roles', 'distrs', 'orgs'));
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
        $userId = $user->id;
        $department = $request->get('department');
        $district = $request->get('district');
        $responsible_specialist = $request->input('responsible_specialist');
        $city_phone = $request->input('city_phone');
        $mobile_phone = $request->input('mobile_phone');
        $director = $request->input('director');
        $directors_phone = $request->input('directors_phone');

        DB::table('users')->where('id', $userId)->update(
            ['district' => $district,
                'department' => $department,
                'responsible_specialist' => $responsible_specialist,
                'city_phone' => $city_phone,
                'mobile_phone' => $mobile_phone,
                'director' => $director,
                'directors_phone' => $directors_phone]);

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
        $distrs = DB::table('distr_helper')->get();
        $orgs = DB::table('org_helper')->get();

        return view('admin.users.edit', compact('user', 'roles', 'distrs', 'orgs'));
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
            $selDist = $request->get('district');
            $inpRS = $request->get('responsible_specialist');
            $inpCP = $request->get('city_phone');
            $inpMP = $request->get('mobile_phone');
            $inpDir = $request->get('director');
            $inpDP = $request->get('directors_phone');
            $oldVals = DB::table('users')->where('id', '=', $userId)->select(
                'name',
                'email',
                'district',
                'department',
                'password',
                'responsible_specialist',
                'city_phone',
                'mobile_phone',
                'director',
                'directors_phone')->get();

            $name = $oldVals[0]->name;
            $email = $oldVals[0]->email;
            $district = $oldVals[0]->district;
            $department = $oldVals[0]->department;
            $responsible_specialist = $oldVals[0]->responsible_specialist;
            $city_phone = $oldVals[0]->city_phone;
            $mobile_phone = $oldVals[0]->mobile_phone;
            $director = $oldVals[0]->director;
            $directors_phone = $oldVals[0]->directors_phone;
            $password = $oldVals[0]->password;

            if ($name != $user->name) {
                $name = $user->name;
            }
            if ($email != $user->email) {
                $email = $user->email;
            }
            if ($district != $selDist) {
                $district = $selDist;
            }
            if ($department != $selDep) {
                $department = $selDep;
            }
            if ($password != password_hash($user->password, PASSWORD_BCRYPT)) {
                $password = $user->password;
            }
            if ($responsible_specialist != $inpRS) {
                $responsible_specialist = $inpRS;
            }
            if ($city_phone != $inpCP) {
                $city_phone = $inpCP;
            }
            if ($mobile_phone != $inpMP) {
                $mobile_phone = $inpMP;
            }
            if ($director != $inpDir) {
                $director = $inpDir;
            }
            if ($directors_phone != $inpDP) {
                $directors_phone = $inpDP;
            }


            $password = password_hash($password, PASSWORD_BCRYPT);

            DB::table('users')->where('id', $userId)->update([
                'name' => $name,
                'email' => $email,
                'district' => $district,
                'department' => $department,
                'password' => $password,
                'responsible_specialist' => $responsible_specialist,
                'city_phone' => $city_phone,
                'mobile_phone' => $mobile_phone,
                'director' => $director,
                'directors_phone' => $directors_phone
            ]);

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
