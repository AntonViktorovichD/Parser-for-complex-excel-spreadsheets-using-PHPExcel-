@extends('layouts.app')
<style>
    .card-group {
        box-shadow: 2px 26px 69px 0 rgba(0, 0, 0, 0.1);
    }

    .tm-heading-teaser {
        font-family: 'Open Sans';
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

    .tm-heading-teaser-span {
        margin-bottom: 50px !important;
        line-height: 2 !important;
        font-size: 20px;
    }

    .form-control {
        margin-top: 25px !important;
    }

</style>
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card-group">
                <div class="card p-4">
                    <div class="card-body">
                        @if(\Session::has('message'))
                            <p class="alert alert-info">
                                {{ \Session::get('message') }}
                            </p>
                        @endif
                        <form method="POST" action="{{ route('login') }}">
                            {{ csrf_field() }}
                            <h1 class="tm-heading-teaser text-center"><span class="tm-heading-teaser-span">Информационно-аналитический сервис</span><br/>
                                Автоматизированный сбор показателей работы социальных учреждений Нижегородской области
                            </h1>
                            <div class="input-group mb-3">

                                <input name="login" type="text"
                                       class="form-control {{ $errors->has('login') ? ' is-invalid' : '' }}" required
                                       autofocus placeholder="Login" value="{{ old('login', null) }}">
                                @if($errors->has('login'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('login') }}
                                    </div>
                                @endif
                            </div>

                            <div class="input-group mb-3">

                                <input name="password" type="password"
                                       class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}" required
                                       placeholder="Password">
                                @if($errors->has('password'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('password') }}
                                    </div>
                                @endif
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <button type="submit" class="btn btn-primary px-4">
                                        Login
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
