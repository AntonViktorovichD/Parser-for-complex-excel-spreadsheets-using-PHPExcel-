@include('layouts.header')
@include('layouts.menu')
<div id="tm-main" class="tm-block-main uk-block uk-block-default ">
    <div class="uk-container uk-container-center">
        <div class="tm-main uk-grid uk-position-relative" data-uk-grid-match data-uk-grid-margin>
            <div class="tm-main uk-width-medium-3-4 uk-flex-order-last">
                <section id="tm-main-top" class="tm-main-top uk-grid" data-uk-grid-match="{target:'> div > .uk-panel'}"
                         data-uk-grid-margin>
                    <div class="uk-width-1-1">
                        <div class="uk-panel uk-panel-box">
                            <div class="warning good">
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
@include('layouts.footer')
