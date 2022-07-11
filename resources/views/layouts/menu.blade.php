<div class="tm-navbar-container ">
    <nav class="tm-navbar uk-navbar">
        <div class="uk-container uk-container-center">
            <div class="uk-grid uk-flex-middle uk-hidden-small">
                <div class="uk-flex-item-1">
                    <ul class="uk-navbar-nav uk-hidden-small">
                        <li><a href="/">Начало
                                работы</a></li>
                        <li><a href="/user_add">Создать
                                запрос из таблицы excel</a></li>
                        <li><a href="/json">Результаты
                                заполнения запросов</a></li>
                        <li><a href="/quarterly_reports">Квартальный
                                отчёт</a></li>
                        <li><a href="/delete_tables">Удаление
                                запросов</a></li>
                        <li><a href="/index.php?option=com_fabrik&amp;view=list&amp;listid=5&amp;Itemid=138">Справочник
                                телефонов</a></li>

                        <li><a href="/daily_reports">Ежедневные отчеты</a></li>
                        @php
                            if(Auth::id() == 1 || Auth::id() == 4) {
                              echo '<li><a href="/admin_reports">ЕЖЕДНЕВНЫЕ ОТЧЕТЫ
                                (ТОЛЬКО ДЛЯ АДМИНИСТРАТОРОВ)</a></li>';
                              }
                        @endphp
                    </ul>
                </div>
                <div class="uk-flex-item-1">
                    <ul class="tm-nav-secondary uk-hidden uk-navbar-nav uk-float-right"></ul>
                </div>
            </div>
        </div>
    </nav>
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
