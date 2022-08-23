<nav class="nav" >
    <ul class="uk-navbar-nav " style="width: 100vw !important">
        <li class="nav-item"><a id="nav" class="nav-link" href="/">Начало
                работы</a></li>
        <li class="nav-item"><a id="nav" class="nav-link" href="/add">Создать
                запрос из таблицы excel</a></li>
        <li class="nav-item"><a id="nav" class="nav-link" href="/list">Результаты
                заполнения запросов</a></li>
        <li class="nav-item"><a id="nav" class="nav-link" href="/quarterly_reports">Квартальный
                отчёт</a></li>
        @php
            if(Auth::user()->roles->first()->id == 1 || Auth::user()->roles->first()->id == 4) {
            echo '<li class="nav-item"><a id="nav" class="nav-link" href="/delete_tables">Удаление
                    запросов</a></li>';
            }
        @endphp
        <li class="nav-item"><a id="nav" class="nav-link" href="/phones">Справочник
                телефонов</a></li>

        <li class="nav-item"><a id="nav" class="nav-link" href="/daily_reports">Ежедневные отчеты</a></li>
        @php
            if(Auth::user()->roles->first()->id == 1 || Auth::user()->roles->first()->id == 4) {
              echo '<li class="nav-item" ><a id="nav" class="nav-link" href="/admin_reports">ЕЖЕДНЕВНЫЕ ОТЧЕТЫ
                (ТОЛЬКО ДЛЯ АДМИНИСТРАТОРОВ)</a></li>';
              }
        @endphp
    </ul>
</nav>

<script>
    let pathname = window.location.pathname;
    let links = document.querySelectorAll('#nav');
    for (let link of links) {
        if (link.attributes.href.value == pathname) {
            link.addClass('active');
        }
    }
</script>
