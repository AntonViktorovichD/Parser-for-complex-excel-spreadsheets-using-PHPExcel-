@include('layouts.header')
@include('layouts.menu')
<style>
    body {
        max-width: 95vw !important;
    }

    table {
        height: 600px !important;
        overflow-y: auto !important;
    }

    .uk-navbar-nav {
        margin-left: 30px !important;
    }

    .btn_pos {
        position: relative !important;
    }

    .btn {
        position: absolute !important;
        bottom: 35px !important;
    }

    .linktofile {
        color: #e43d3c;
        text-decoration: underline;
        font-size: 16px;
        font-weight: normal;
    }
</style>
<div class="container px-5">
    <div class="row justify-content-between">
        <div class="bg_info btn_pos col-9">
            <div class="warning_text">
                <h2>ВНИМАНИЕ!</h2>

                <p style="margin-top:10px;">В связи с частыми обращениями на почту с просьбой
                    предоставить номер телефона
                    сотрудников, ответственных за создание запросов, было принято решение о
                    необходимости создания справочника телефонов ответственных сотрудников</p>
                <p style="margin-top:10px;">Для того, чтобы воспользоваться справочником, необходимо
                    перейти в пункт меню
                    "Справочник телефонов" и найти нужного специалиста.</p>
                <p style="margin-top:10px;">Ответственным специалистам министерства необходимо проверить
                    корректность указанных
                    номеров телефонов в справочнике.</p>
                <p style="margin-top:10px;">В случае, если указанный номер некорректен или отсутствует,
                    просьба направить письмо
                    с актуальной информацией в приёмную ГБУ НО "Объединённая дирекция по реализации
                    жилищных программ".</p>
            </div>
            <div class="instruction">
                <h3>Инструкция для пользователей от 8.05.2018</h3>
                <a class="linktofile" download href="{{ URL::to('/')}}/{{Storage::disk('local')->url('files/Instruction(user)(08.05.2018).pdf')}}">Скачать инструкцию
                    для пользователей</a>
                <h3>Инструкция для администраторов от 8.05.2018</h3>
                <a class="linktofile" download href="{{ URL::to('/')}}/{{Storage::disk('local')->url('files/Instruction(admin)(08.05.2018).pdf')}}">Скачать инструкцию
                    для администраторов</a>
                <h3>Шаблон Excel файла</h3>
                <a class="linktofile" download href="{{ URL::to('/')}}/{{Storage::disk('local')->url('files/ExcelFileTemplate.xls')}}">Скачать шаблон Excel файла</a>
                <h3>Приказ №2 от 09.01.2017</h3>
                <a class="linktofile" download href={{ URL::to('/')}}/{{Storage::disk('local')->url('files/Prikaz N2 ot 09.01.2017.pdf')}}">Скачать Приказ №2 от
                    09.01.2017</a>
            </div>
            <div class="logout">
                <form id="logoutform" action="{{ route('logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
                <button type="submit" class="btn btn_mon btn-outline-danger"
                        onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                    < Выйти
                </button>
            </div>
        </div>
        <div class="bg_info col-2 daily_reports">
            @php
                //            if ($user_role == 3) {
                                echo '<table class="table">';
                                echo '<tr>';
                                echo '<th scope="col">Ежедневные отчеты, требующие заполнения</th>';
                                echo '</tr>';
                                echo '<td id="nothing" hidden><i class="cmpl uk-icon-times" style="color: #e43d3c;"></i> Нет отчетов для заполнения</td>';
                                foreach (json_decode($arrs, true) as $arr) {
                                        echo '<tr class="tables_daily" id="' . $arr['fill'] . '">';
                                        echo '<td>';
                                        echo '<a  href="#">' . $arr['table_name'] . '</a>';
                                        echo '</td>';
                                        echo '</tr>';
                                }
                                echo '</table>';
                //                }
            @endphp
        </div>
    </div>
</div>
<script>
    window.onload = () => {
        let tables = document.querySelectorAll('.tables_daily');
        let counter = 0;
        for (let table of tables) {
            if (table.id == 100) {
                table.hidden = true;
                counter++;
            } else if (tables.length == counter) {
                nothing.hidden = false
            }
        }
    }
</script>
@include('layouts.footer')
