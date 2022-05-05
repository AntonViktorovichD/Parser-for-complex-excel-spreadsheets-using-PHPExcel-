<!DOCTYPE HTML>
<html lang="ru-ru" dir="ltr" data-config='{"twitter":0,"plusone":0,"facebook":0,"style":"minimal"}'>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow"/>
    <title>Информационно-аналитический сервис &quot;Автоматизированный сбор показателей работы социальных учреждений
        Нижегородской области&quot;</title>
    <link href="/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon"/>
    <script src="/js/jquery.min.js" type="text/javascript"></script>
    <script src="/js/jquery-noconflict.js" type="text/javascript"></script>
    <script src="/js/bootstrap.min.js" type="text/javascript"></script>
    <link rel="stylesheet" href="/css/main.css">
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

<body class="tm-sidebar-b-right tm-sidebars-1 tm-noblog">

<h1 class="main-header">ИНФОРМАЦИОННО-АНАЛИТИЧЕСКИЙ СЕРВИС "АВТОМАТИЗИРОВАННЫЙ СБОР ПОКАЗАТЕЛЕЙ РАБОТЫ СОЦИАЛЬНЫХ
    УЧРЕЖДЕНИЙ НИЖЕГОРОДСКОЙ ОБЛАСТИ"</h1>
<div class="tm-navbar-container ">
    <nav class="tm-navbar uk-navbar">
        <div class="uk-container uk-container-center">
            <div class="uk-grid uk-flex-middle uk-hidden-small">
                <div class="uk-flex-item-1">
                    <ul class="uk-navbar-nav uk-hidden-small">
                        <li><a href="/index.php?option=com_users&amp;view=login&amp;Itemid=101">Начало
                                работы</a></li>
                        <li><a href="/index.php?option=com_fabrik&amp;view=form&amp;formid=1&amp;Itemid=116">Создать
                                запрос из таблицы excel</a></li>
                        <li><a href="/index.php?option=com_content&amp;view=featured&amp;Itemid=154">Результаты
                                заполнения запросов</a></li>
                        <li><a href="/index.php?option=com_wrapper&amp;view=wrapper&amp;Itemid=136">Квартальный
                                отчёт</a></li>
                        <li><a href="/index.php?option=com_wrapper&amp;view=wrapper&amp;Itemid=137">Удаление
                                запросов</a></li>
                        <li><a href="/index.php?option=com_fabrik&amp;view=list&amp;listid=5&amp;Itemid=138">Справочник
                                телефонов</a></li>
                        <li><a href="/index.php?option=com_content&amp;view=featured&amp;Itemid=139">Ежедневные
                                отчеты</a></li>
                        <li><a href="/index.php?option=com_content&amp;view=featured&amp;Itemid=152">ЕЖЕДНЕВНЫЕ ОТЧЕТЫ
                                (ТОЛЬКО ДЛЯ АДМИНИСТРАТОРОВ)</a></li>
                    </ul>
                </div>
                <div class="uk-flex-item-1">
                    <ul class="tm-nav-secondary uk-hidden uk-navbar-nav uk-float-right"></ul>
                </div>
            </div>
        </div>
    </nav>
    <script>
        (function ($) {
            var navbar = $('.tm-navbar'),
                menuItems = navbar.find('.uk-navbar-nav > li'),
                logo = $('a.tm-logo');

            if (menuItems.length && logo.length) {

                menuItems.filter(function (index) {
                    return index > Math.floor(menuItems.length / 2) - 1;
                }).appendTo('.tm-nav-secondary');

                $('.tm-nav-secondary').removeClass('uk-hidden');

            }

        })(jQuery);
    </script>
</div>
<div id="tm-main" class="tm-block-main uk-block uk-block-default ">
    <div class="uk-container uk-container-center">
        <div class="tm-main uk-grid uk-position-relative" data-uk-grid-match data-uk-grid-margin>

            <div class="tm-main uk-width-medium-3-4 uk-flex-order-last">

                <section id="tm-main-top" class="tm-main-top uk-grid" data-uk-grid-match="{target:'> div > .uk-panel'}"
                         data-uk-grid-margin>
                    <div class="uk-width-1-1">
                        <div class="uk-panel uk-panel-box">
                            <style>
                                div.good p {
                                    margin-top: 10px;
                                }
                            </style>
                            <div class="warning good" style="margin-top:10px;">
                                <h2>ВНИМАНИЕ!</h2>
                                <p>В связи с частыми обращениями на почту с просьбой предоставить номер телефона
                                    сотрудников, ответственных за создание запросов, было принято решение о
                                    необходимости создания справочника телефонов ответственных сотрудников</p>
                                <p>Для того, чтобы воспользоваться справочником, необходимо перейти в пункт меню
                                    "Справочник телефонов" и найти нужного специалиста.</p>
                                <p>Ответственным специалистам министерства необходимо проверить корректность указанных
                                    номеров телефонов в справочнике.</p>
                                <p>В случае, если указанный номер некорректен или отсутствует, просьба направить письмо
                                    с актуальной информацией в приёмную ГБУ НО "Объединённая дирекция по реализации
                                    жилищных программ".</p>
                            </div>
                            <h3>Инструкция для пользователей от 8.05.2018</h3>
                            <a class="linktofile" download href="Instruction(user)(08.05.2018).pdf">Скачать инструкцию
                                для пользователей</a>
                            <h3>Инструкция для администраторов от 8.05.2018</h3>
                            <a class="linktofile" download href="Instruction(admin)(08.05.2018).pdf">Скачать инструкцию
                                для администраторов</a>
                            <h3>Шаблон Excel файла</h3>
                            <a class="linktofile" download href="ExcelFileTemplate.xls">Скачать шаблон Excel файла</a>
                            <h3>Приказ №2 от 09.01.2017</h3>
                            <a class="linktofile" download href="Prikaz N2 ot 09.01.2017.pdf">Скачать Приказ №2 от
                                09.01.2017</a></div>
                    </div>
                </section>
                <main id="tm-content" class="tm-content">
                    <div id="system-message-container">
                    </div>
                    <div class="logout">
                        <form id="logoutform" action="{{ route('logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                        <button type="submit" class="btn btn-primary"
                                onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                            <i class="nav-icon fas fa-fw fa-sign-out-alt">
                            </i>
                            ВЫХОД
                        </button>
                    </div>
                </main>
            </div>
            <aside class="tm-sidebar-b uk-width-medium-1-4 uk-flex-order-last">
                <div class="uk-panel uk-panel-box">
                    <style>
                        .cmpl {
                            color: #08c;
                        }

                        .not-cmpl {
                            color: #e43d3c;
                        }

                        .repo {
                            font-size: 12px;
                            line-height: 18px;
                        }

                        .table td span {
                            font-weight: bold;
                        }

                        hr {
                            border-top: 1px solid #969696;
                        }

                        .table {
                            display: flex;
                            overflow: auto;
                        }
                    </style>

                    <table class="table table-striped">
                        <tr>
                            <th scope="col">Ежедневные отчеты, требующие заполнения</th>
                        </tr>
                        <tr>
                            <td><i class='cmpl uk-icon-times' style='color: #e43d3c;'></i> Нет отчетов для заполнения
                            </td>
                        </tr>
                    </table>
                </div>
            </aside>

        </div>
    </div>
</div>
<footer id="tm-footer" class="tm-footer uk-block uk-block-default ">
    <div class="uk-container uk-container-center">
        <div class="uk-flex uk-flex-middle uk-flex-space-between uk-text-center-small">
            <div class="tm-footer-right">
                <div class="uk-panel">
                    <p>Права защищены © @php echo date("Y") @endphp Министерство социальной политики Нижегородской
                        области</p>
                </div>
            </div>
        </div>
    </div>
</footer>
</body>
</html>
