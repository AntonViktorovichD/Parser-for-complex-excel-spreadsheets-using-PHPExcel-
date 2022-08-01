@extends('layouts.app')
<style>
    body {
        font-family: "Helvetica, Arial, Sans-Serif" !important;
    }

    .card-group {
        box-shadow: 2px 26px 69px 0 rgba(0, 0, 0, 0.1);
    }

    .btn_mon {
        margin-top: 35px !important;
    }

    .tm-heading-teaser {
        text-transform: uppercase;
        left: 0;
        right: 0;
        padding: 0 0;
        font-weight: 400;
        text-shadow: 0 0 0 0 rgba(0, 0, 0, 0);
        z-index: -1;
        margin-top: -10px;
        line-height: 1.2;
        font-size: 30px !important;
    }

    .form-control:focus {
        text-shadow: none !important;
        background: rgba(150, 150, 150, 0.15) !important;
        box-shadow: 0 0 6px #f8b9b7 !important;
        border-color: #cccccc !important;
    }

    .tm-heading-teaser-span {
        margin-bottom: 50px !important;
        line-height: 2 !important;
        font-size: 20px;
    }

    .input-group {
        width: 300px !important;
        margin-top: 40px !important;
        font-size: 14px !important;
        line-height: 2.75 !important;
    }

    .btn {
        margin-top: 50px !important;
        margin-bottom: 30px !important;
    }
</style>
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-9">
                <div class="card-group">
                    <div class="card p-4">
                        <div class="card-body text-center">
                            @if(\Session::has('message'))
                                <p class="alert alert-info">
                                    {{ \Session::get('message') }}
                                </p>
                            @endif
                            <form method="POST" action="{{ route('login') }}">
                                {{ csrf_field() }}
                                <h1 class="tm-heading-teaser "><span class="tm-heading-teaser-span">Информационно-аналитический сервис</span><br/>
                                    Автоматизированный сбор показателей работы социальных учреждений Нижегородской
                                    области
                                </h1>
                                <div class="input-group">
                                    <label for="login">Логин * &nbsp&nbsp&nbsp&nbsp</label>
                                    <input name="login" type="text"
                                           class="form-control {{ $errors->has('login') ? ' is-invalid' : '' }}"
                                           required
                                           autofocus value="{{ old('login', null) }}">
                                    @if($errors->has('login'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('login') }}
                                        </div>
                                    @endif
                                </div>

                                <div class="input-group">
                                    <span>Пароль * &nbsp&nbsp</span>
                                    <input name="password" type="password"
                                           class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}"
                                           required>
                                    @if($errors->has('password'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('password') }}
                                        </div>
                                    @endif
                                </div>

                                <div class="row">
                                    <button type="submit" class="btn btn_mon btn-outline-danger">
                                        Войти
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
