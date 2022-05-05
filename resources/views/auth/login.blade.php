<!DOCTYPE HTML>
<html lang="ru-ru" dir="ltr" data-config='{"twitter":0,"plusone":0,"facebook":0,"style":"minimal"}'>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow"/>
    <title>Информационно-аналитический сервис &quot;Автоматизированный сбор показателей работы социальных учреждений
        Нижегородской области&quot;</title>
    <link href="/css/bootstrap@5.1.3.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/theme.css">
    <link href="/css/montserrat.css" rel="stylesheet">
    <link href="/css/auth.css" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100">
    <div class="login">
        <form class="form-validate form-horizontal well" method="POST" action="{{ route('login') }}">
            {{ csrf_field() }}
            <h1 class="tm-heading-teaser" style="font-size: 30px;">
                <span style="font-size: 20px;">Информационно-аналитический сервис</span><br>
                Автоматизированный сбор показателей работы социальных учреждений Нижегородской области
            </h1><br>
            <div class="control-group">
                <div class="control-label">
                    <label id="username-lbl" for="username" class="required">
                        Логин<span class="star">&nbsp;*</span></label>
                    &nbsp;
                </div>
                <div class="controls">
                    <input name="email" type="text"
                           class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }} validate-username required"
                           required
                           autofocus value="{{ old('email', null) }}">
                </div>
            </div>
            <div class="control-group">
                <div class="control-label">
                    <label id="password-lbl" for="password" class="required">
                        Пароль<span class="star">&nbsp;*</span></label>
                    &nbsp;
                </div>
                <div class="controls">
                    <input name="password" type="password" size="25" maxlength="99" aria-required="true"
                           class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }} validate-password required"
                           required>
                </div>
            </div>
            <div class="control-group margin-btn">
                <button type="submit" class="btn btn-primary">
                    Войти
                </button>
            </div>
        </form>
    </div>
    <footer class="fixed-bottom">
{{--        <nav class="fixed-bottom ">--}}
{{--            <div class="container-fluid">--}}
                <p>Права защищены © @php echo date("Y") @endphp Министерство социальной политики Нижегородской
                    области</p>
{{--            </div>--}}
{{--        </nav>--}}
    </footer>
    {{--<div class="tm-footer row align-items-end">--}}
    {{--    <div class="col">--}}
    {{--    <p>Права защищены © @php echo date("Y") @endphp Министерство социальной политики Нижегородской области</p>--}}
    {{--</div>--}}
    {{--</div>--}}
</body>
</html>
