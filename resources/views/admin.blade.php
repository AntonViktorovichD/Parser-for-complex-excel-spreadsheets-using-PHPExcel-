@extends('layouts.admin')
@section('content')
    <style>
        ul {
            margin-left: 0;
            padding-left: 0;
        }

        li {
            list-style-type: none;
        }

        .nav-item {
            font-size: 20px !important;
        }
    </style>
    <div class="content">
        <div class="row">
            <div class="col-lg-12">
                <li class="admin">
                    <ul class="nav-dropdown-items">
                        <li class="nav-item">
                            <a href="{{ route("admin.permissions.index") }}" class="nav-link {{ request()->is('admin/permissions') || request()->is('admin/permissions/*') ? 'active' : '' }}">
                                {{ trans('cruds.permission.title') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route("admin.roles.index") }}" class="nav-link {{ request()->is('admin/roles') || request()->is('admin/roles/*') ? 'active' : '' }}">
                                {{ trans('cruds.role.title') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route("admin.users.index") }}" class="nav-link {{ request()->is('admin/users') || request()->is('admin/users/*') ? 'active' : '' }}">
                                {{ trans('cruds.user.title') }}
                            </a>
                        </li>
                    </ul>
                </li>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    @parent

@endsection
