<ul class="nav">
    <li class="nav-item"><a class="nav-link" href="/home">Начало
            работы</a></li>
    <li class="nav-item"><a class="nav-link" href="/user_add">Создать
            запрос из таблицы excel</a></li>
    <li class="nav-item"><a class="nav-link" href="/json">Результаты
            заполнения запросов</a></li>
    <li class="nav-item"><a class="nav-link" href="/quarterly_reports">Квартальный
            отчёт</a></li>
    @php
        if(Auth::id() == 1 || Auth::id() == 4) {
        echo '<li class="nav-item"><a class="nav-link" href="/delete_tables">Удаление
                запросов</a></li>';
        }
    @endphp
    <li class="nav-item"><a class="nav-link" href="/">Справочник
            телефонов</a></li>

    <li class="nav-item"><a class="nav-link" href="/daily_reports">Ежедневные отчеты</a></li>
    @php
        if(Auth::user()->roles->first()->id == 1 || Auth::user()->roles->first()->id == 4) {
          echo '<li class="nav-item" ><a class="nav-link" href="/admin_reports">ЕЖЕДНЕВНЫЕ ОТЧЕТЫ
            (ТОЛЬКО ДЛЯ АДМИНИСТРАТОРОВ)</a></li>';
          }
    @endphp
</ul>
<div class="uk-flex-item-1">
    <ul class="tm-nav-secondary uk-hidden uk-navbar-nav uk-float-right"></ul>
</div>

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
<div class="uk-width-1-1 uk-row-first">
    <div class="uk-panel uk-panel-box">
