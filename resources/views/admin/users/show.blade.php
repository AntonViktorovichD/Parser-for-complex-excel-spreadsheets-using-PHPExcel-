@extends('layouts.admin')
@section('content')

    <div class="card">
        <div class="card-header">
            {{ trans('global.show') }} {{ trans('cruds.user.title') }}
        </div>
        <div class="card-body">
            <div class="mb-2">
                <table class="table table-bordered table-striped">
                    <tbody>
                    <tr>
                        <th>
                            ID
                        </th>
                        <td>
                            {{ $user->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Creation date
                        </th>
                        <td>
                            {{ $user->created_at }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Upadate date
                        </th>
                        <td>
                            {{ $user->updated_at }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Name
                        </th>
                        <td>
                            {{ $user->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Email
                        </th>
                        <td>
                            {{ $user->email }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Roles
                        </th>
                        <td>
                            @foreach($user->roles()->pluck('name') as $role)
                                <span class="label label-info label-many">{{ $role }}</span>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Department
                        </th>
                        <td>
                            @php
                                foreach ($orgs as $org) {
                                   if($org->id == $user->department) {
                                      echo $org->title;
                                   }
                                }
                            @endphp
                        </td>
                    </tr>
                    <tr>
                        <th>
                            District
                        </th>
                        <td>
                            @php
                                foreach ($orgs as $org) {
                                   if($org->id == $user->district) {
                                      echo $org->distr_title;
                                   }
                                }
                            @endphp
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Responsible specialist
                        </th>
                        <td>
                            {{ $user->responsible_specialist  }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            City phone
                        </th>
                        <td>
                            {{ $user->city_phone }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Mobile phone
                        </th>
                        <td>
                            {{ $user->mobile_phone }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Director
                        </th>
                        <td>
                            {{ $user->director }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Director's phone
                        </th>
                        <td>
                            {{ $user-> directors_phone }}
                        </td>
                    </tr>


                    </tbody>
                </table>
                <a style="margin-top:20px;" class="btn btn-default" href="{{ url()->previous() }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
@endsection
