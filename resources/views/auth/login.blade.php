<link href="/css/bootstrap@5.1.3.css" rel="stylesheet">
<link href="/css/montserrat.css" rel="stylesheet">
<link href="/css/auth.css" rel="stylesheet">
<body>
<div class="bg">
    <div id="wrap">
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

    </div>
    <div class="tm-footer uk-block uk-block-default">
        <p>Права защищены © @php echo date("Y") @endphp Министерство социальной политики Нижегородской области</p>
    </div>
</div>
</body>
