@extends('layouts.admin')
@section('content')
    <style>
        .department select {
            height: calc(1.5em + .75rem + 2px);
            padding: .375rem .75rem;
            font-size: .875rem;
            font-weight: 400;
            line-height: 1.5;
            color: #5c6873;
            background-color: #ffffff;
            background-clip: padding-box;
            border: 1px solid #e4e7ea;
            border-radius: .25rem;
        }

    </style>
    <div class="card">
        <div class="card-header">
            {{ trans('global.create') }} {{ trans('cruds.user.title_singular') }}
        </div>

        <div class="card-body">
            <form action="/admin/users" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                    <label for="name">{{ trans('cruds.user.fields.name') }}*</label>
                    <input type="text" id="name" name="name" class="form-control"
                           value="{{ old('name', isset($user) ? $user->name : '') }}" required>
                    @if($errors->has('name'))
                        <em class="invalid-feedback">
                            {{ $errors->first('name') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.user.fields.name_helper') }}
                    </p>
                </div>
                <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                    <label for="email">{{ trans('cruds.user.fields.email') }}*</label>
                    <input type="email" id="email" name="email" class="form-control"
                           value="{{ old('email', isset($user) ? $user->email : '') }}" required>
                    @if($errors->has('email'))
                        <em class="invalid-feedback">
                            {{ $errors->first('email') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.user.fields.email_helper') }}
                    </p>
                </div>
                <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                    <label for="password">{{ trans('cruds.user.fields.password') }}</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                    @if($errors->has('password'))
                        <em class="invalid-feedback">
                            {{ $errors->first('password') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.user.fields.password_helper') }}
                    </p>
                </div>
                <div class="form-group">
                    <label for="districts">Район: {{$district}}
                        <select name="district" id="district" class="district">
                            <option disabled value="" selected>Выберите один из вариантов</option>
                            @foreach($distrs as $distr)
                                <option value="{{ $distr->id }}">{{ $distr->title }}</option>
                            @endforeach
                        </select>
                    </label>
                </div>
                <div class="form-group">
                    <label for="departments">Организация: {{$department}}
                        <select name="department" class="department" hidden>
                            @foreach($orgs as $org)
                                <option class="orgs" id="{{ $org->id }}" value="{{ $org->distr_id }}"
                                        label="{{ $org->title }}"></option>
                            @endforeach
                        </select>
                        <div id="div1"></div>
                    </label>
                </div>
                <div class="form-group {{ $errors->has('responsible_specialist') ? 'has-error' : '' }}">
                    <label for="responsible_specialist">Responsible specialist </label>
                    <input type="responsible_specialist" id="responsible_specialist" name="responsible_specialist"
                           class="form-control" required>
                    @if($errors->has('responsible_specialist'))
                        <em class="invalid-feedback">
                            {{ $errors->first('responsible_specialist') }}
                        </em>
                    @endif
                </div>
                <div class="form-group {{ $errors->has('city_phone') ? 'has-error' : '' }}">
                    <label for="city_phone">City phone </label>
                    <input type="city_phone" id="city_phone" name="city_phone" class="form-control" required>
                    @if($errors->has('city_phone'))
                        <em class="invalid-feedback">
                            {{ $errors->first('city_phone') }}
                        </em>
                    @endif
                </div>
                <div class="form-group {{ $errors->has('mobile_phone') ? 'has-error' : '' }}">
                    <label for="mobile_phone">Mobile phone</label>
                    <input type="mobile_phone" id="mobile_phone" name="mobile_phone" class="form-control" required>
                    @if($errors->has('mobile_phone'))
                        <em class="invalid-feedback">
                            {{ $errors->first('mobile_phone') }}
                        </em>
                    @endif
                </div>
                <div class="form-group {{ $errors->has('director') ? 'has-error' : '' }}">
                    <label for="director">Director</label>
                    <input type="director" id="director" name="director" class="form-control" required>
                    @if($errors->has('director'))
                        <em class="invalid-feedback">
                            {{ $errors->first('director') }}
                        </em>
                    @endif
                </div>
                <div class="form-group {{ $errors->has('directors_phone') ? 'has-error' : '' }}">
                    <label for="directors_phone">Directors phone </label>
                    <input type="directors_phone" id="directors_phone" name="directors_phone" class="form-control"
                           required>
                    @if($errors->has('directors_phone'))
                        <em class="invalid-feedback">
                            {{ $errors->first('directors_phone') }}
                        </em>
                    @endif

                </div>
                <div class="form-group {{ $errors->has('roles') ? 'has-error' : '' }}">
                    <label for="roles">{{ trans('cruds.user.fields.roles') }}*
                        <span class="btn btn-info btn-xs select-all">{{ trans('global.select_all') }}</span>
                        <span
                            class="btn btn-info btn-xs deselect-all">{{ trans('global.deselect_all') }}</span></label>
                    <select name="roles[]" id="roles" class="form-control select2" multiple="multiple" required>
                        @foreach($roles as $id => $roles)
                            <option
                                value="{{ $id }}" {{ (in_array($id, old('roles', [])) || isset($user) && $user->roles->contains($id)) ? 'selected' : '' }}>{{ $roles }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('roles'))
                        <em class="invalid-feedback">
                            {{ $errors->first('roles') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.user.fields.roles_helper') }}
                    </p>
                </div>
                <div>
                    <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }}">
                </div>
            </form>
        </div>
    </div>
@endsection
<script src="/js/vanilla.js"></script>
<script src="/js/create_edit_user.js"></script>
