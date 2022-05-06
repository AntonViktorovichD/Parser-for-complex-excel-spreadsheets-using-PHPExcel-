<!DOCTYPE HTML>
<html lang="ru-ru" dir="ltr" data-config='{"twitter":0,"plusone":0,"facebook":0,"style":"minimal"}'>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow"/>
    <title>Информационно-аналитический сервис &quot;Автоматизированный сбор показателей работы социальных учреждений
        Нижегородской области&quot;</title>
    <link href="/templates/inf-serv/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon"/>
    <script src="js/jquery.min.js" type="text/javascript"></script>
    <script src="/js/jquery-noconflict.js" type="text/javascript"></script>
    <script src="/js/html5fallback.js" type="text/javascript"></script>
    <script src="/js/bootstrap.min.js" type="text/javascript"></script>
    <script type="text/javascript">
        window.setInterval(function () {
            var r;
            try {
                r = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP")
            } catch (e) {
            }
            if (r) {
                r.open("GET", "/index.php?option=com_ajax&format=json", true);
                r.send(null)
            }
        }, 2640000);
    </script>

    <link rel="apple-touch-icon-precomposed" href="/templates/inf-serv/apple_touch_icon.png">
    <link rel="stylesheet" href="/css/bootstrap.css">
    <link rel="stylesheet" href="/css/theme.css">
    <link rel="stylesheet" href="/css/custom.css">
    <script src="/js/uikit.js"></script>
    <script src="/js/autocomplete.js"></script>
    <script src="/js/search.js"></script>
    <script src="/js/tooltip.js"></script>
    <script src="/js/sticky.js"></script>
    <script src="/js/social.js"></script>
    <script src="/js/theme.js"></script>
</head>
<body class="tm-noblog">
<div id="tm-main" class="tm-block-main uk-block uk-block-default ">
    <div class="uk-container uk-container-center">
        <div class="tm-main uk-grid uk-position-relative" data-uk-grid-match data-uk-grid-margin>
            <div class="tm-main uk-width-medium-1-1 uk-flex-order-last">
                <main id="tm-content" class="tm-content">
                    <div id="system-message-container"></div>
                    <div class="login">
                        <form action="{{ route('login') }}" method="post" class="form-validate form-horizontal well">
                            {{ csrf_field() }}
                            <h1 class="tm-heading-teaser" style="font-size: 30px;">
                                <span style="font-size: 20px;">Информационно-аналитический сервис</span><br/>Автоматизированный
                                сбор показателей работы социальных учреждений Нижегородской области
                            </h1><br/>
                            <div class="control-group">
                                <div class="control-label">
                                    <label id="username-lbl" for="username" class="required">
                                        Логин<span class="star">&#160;*</span></label>
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
                                        Пароль<span class="star">&#160;*</span></label>
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
                    <div>
                        <ul class="nav nav-tabs nav-stacked hide">
                            <li>
                                <a href="">
                                </a>
                            </li>
                            <li>
                                <a href="">
                                </a>
                            </li>
                        </ul>
                    </div>
                </main>
            </div>
        </div>
    </div>
</div>
<footer id="tm-footer" class="tm-footer uk-block uk-block-default ">
    <div class="uk-container uk-container-center">
        <div class="uk-flex uk-flex-middle uk-flex-space-between uk-text-center-small">
            <div class="tm-footer-right">
                <div class="uk-panel">
                    <p style="padding: 28px 0 30px 0">Права защищены © @php echo date("Y") @endphp Министерство
                        социальной политики Нижегородской области</p>
                </div>
            </div>
        </div>
    </div>
</footer>


</body>
</html>
