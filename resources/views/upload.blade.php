@include('layouts.header')
@include('layouts.menu')
<style>
    .cols-3 {
        columns: 3;
    }

    .cols-4 {
        columns: 4;
    }

    .inputs_cols {
        margin: 30px 0 !important;
    }

    .ml-add {
        margin-left: 17px;
    }

    .container-flex {
        margin-right: 35px !important;
    }
</style>
<link rel="stylesheet" href="/css/jquery.datetimepicker.min.css">
<script src="/js/jquery.js"></script>
<script src="/js/jquery.datetimepicker.full.js"></script>
<div class="container-flex px-4 ">
    <h1 class="title_h1">Создание запроса данных</h1>
    {{ $ulerror }}
    <form method="post" id="form" action="/ul" enctype="multipart/form-data">
        <div class="row row_info">
            <legend class="legend">Создание запроса из таблицы Excel</legend>
        </div>
        @csrf
        @php
            $prev_create_time = DB::table('tables')->orderBy('id', 'desc')->value('created_at');;
            echo '<div id="prev" hidden>' . $prev_create_time . '</div>';
            date_default_timezone_set('Europe/Moscow');
            $time_delay = DB::table('notification_rights')->value('time_delay');
            $now = new DateTime();
            $date = DateTime::createFromFormat("Y-m-d H:i:s", $prev_create_time);
            $hours = $now->diff($date)->d * 24 + $now->diff($date)->h;
            $minutes = $now->diff($date)->d * 24 * 60 + $now->diff($date)->h * 60 + $now->diff($date)->i;$hours = $now->diff($date)->d * 24 + $now->diff($date)->h;
            echo '<div class="row row_info">';
            echo '<div class="col-1 text-nowrap info_headers">';
            echo '<label for="filename">Название запроса </label>';
            echo '</div>';
            echo '<div class="col-1">';
            echo '<input type="text" name="filename">';
            echo '</div>';
            echo '</div>';
            if (Auth::user()->getRoleNames()[0] == 'moderator' || Auth::user()->getRoleNames()[0] == 'administrator') {
               echo '<div class="row row_info">';
               echo '<div class="col-1 text-nowrap info_headers">';
               echo '<label for="periodicity">Выбор периодичности</label>';
               echo '</div>';
               echo '<div class="col-1">';
               echo '<select id="periodicity" name="periodicity">';
               echo '<option selected value="0"> Разовый </option>';
               echo '<option value="1"> Ежедневный </option>';
               echo '<option value="2"> Еженедельный </option>';
               echo '<option value="3"> Ежемесячный </option>';
               echo '<option value="4"> Квартальный </option>';
               echo '</select>';
               echo '</div>';
               echo '</div>';
            } else {
               echo '<input type="hidden" name="periodicity" value="0">';
            }
            echo '<input type="radio" name="reg_func" value="v_text">&nbsp text';
            echo '<input type="radio" name="reg_func" value="v_int">&nbsp int';
            echo '<input type="radio" name="reg_func" value="v_float">&nbsp float';
            echo '<input type="radio" name="reg_func" value="v_all">&nbsp all';
            echo '<div class="row  row_info">';
            echo '<div class="col-1 text-nowrap info_headers">';
            echo '<label for="created_at">
                            Начало сбора данных</label>';
            echo '</div>';
            echo '<div class="col-1">';
            echo '<input name="created_at" id="datetimepicker-s" type="text">';
            echo '</div>';
            echo '</div>';
            echo '<div class="row  row_info">';
            echo '<div class="col-1 text-nowrap info_headers">';
            echo '<label for="updated_at"> Конец сбора данных </label>';
            echo '</div>';
            echo '<div class="col-1">';
            echo '<input name="updated_at" id="datetimepicker-f" type="text">';
            echo '</div>';
            echo '</div>';
            echo '<div class="row  row_info">';
            echo '<div class="col-1 text-nowrap info_headers">';
            echo '<label class="userfile"> Excel файл запроса </label>';
            echo '</div>';
            echo '<div class="col-1">';
            echo '<input type="file" name="userfile" accept=".xls,.xlsx">';
            echo '</div>';
            echo '</div>';
            $depart_helper = DB::table('depart_helper')->pluck('title');
            $depart_helper = (json_decode(json_encode($depart_helper, JSON_UNESCAPED_UNICODE), true));
            $depart_helper_id = DB::table('depart_helper')->pluck('id');
            $depart_helper_id = (json_decode(json_encode($depart_helper_id, JSON_UNESCAPED_UNICODE), true));

            echo '<h3 class="inputs_cols">Типы Учреждений:</h3>';
            foreach ($depart_helper as $depart_counter => $depart) {
               echo '<input type="checkbox" class="depart" id=" ' . $depart . ' " data-checker="depart" value=" ' . $depart_helper_id[$depart_counter] . ' " data-value=" ' . $depart_helper_id[$depart_counter] . ' "><label for="' . $depart . '">' . $depart . '</label><br />';
            }
            $distr_helper = DB::table('distr_helper')->pluck('title');
            $distr_helper = (json_decode(json_encode($distr_helper, JSON_UNESCAPED_UNICODE), true));
            $distr_helper_id = DB::table('distr_helper')->pluck('id');
            $distr_helper_id = (json_decode(json_encode($distr_helper_id, JSON_UNESCAPED_UNICODE), true));
            echo '<h3  class="inputs_cols">Районы:</h3>';
            echo '<div class="cols-4">';
            foreach ($distr_helper as $distr_counter => $distr) {
               echo '<input type="checkbox" class="distr" id=" ' . $distr . '" data-checker="distr" value=" ' . $distr_helper_id[$distr_counter] . ' " data-value=" ' . $distr_helper_id[$distr_counter] . ' "><label for="' . $distr . '">' . $distr . '</label><br />';
            }
            echo '</div>';
            $org_helper = DB::table('org_helper')->pluck('title');
            $org_helper = (json_decode(json_encode($org_helper, JSON_UNESCAPED_UNICODE), true));
            $org_depart_id = DB::table('org_helper')->pluck('depart_id');
            $org_depart_id = (json_decode(json_encode($org_depart_id, JSON_UNESCAPED_UNICODE), true));
            $org_distr_id = DB::table('org_helper')->pluck('distr_id');
            $org_distr_id = (json_decode(json_encode($org_distr_id, JSON_UNESCAPED_UNICODE), true));
            $org_helper_id = DB::table('org_helper')->pluck('id');
            $org_helper_id = (json_decode(json_encode($org_helper_id, JSON_UNESCAPED_UNICODE), true));
            echo '<h3  class="inputs_cols">Учреждения:</h3>';
            echo '<div class="cols-3">';
            foreach ($org_helper as $org_counter => $org) {
               $org = preg_replace('#"#', '&quot', $org);
               echo '<input type="checkbox" class="org" name="org[' . $org_helper_id[$org_counter] . ']" id=" ' . $org . '"data-checker="org" data-org="0" data-departId=" ' . $org_depart_id[$org_counter] . ' " data-distrId=" ' . $org_distr_id[$org_counter] . ' " value=" ' . $org_helper_id[$org_counter] . ' "><label for="' . $org . '">' . $org . '</label><br />';
            }
            echo '</div>';
            echo '<label for="comment" style="margin: 30px 0">Комментарий к запросу:</label>';
            echo '<textarea type="text" name="comment" cols="40" rows="6" style="width: 100% !important; margin-bottom:30px;"></textarea>';
            echo '<input id="sms" type="text" name="sms" hidden>';
            echo '<input id="checked" type="text" name="checked" value="" hidden>';
            echo '<input style="border: 2px solid #e43d3c !important;" class="btn btn-primary btn-submit-second button" id="cnfrm" @click="check" value="Отправить запрос">';
        @endphp
    </form>
</div>
<script>
    window.onload = () => {
        let arr_checked = [];
        let arr_checked_depart = [];
        let arr_checked_distr = [];
        let arr_checked_org = [];
        document.addEventListener('input', (e) => {
                let departs = document.querySelectorAll('.depart');
                let distrs = document.querySelectorAll('.distr');
                let orgs = document.querySelectorAll('.org');
                if (e.target.dataset.checker === 'org') {
                    for (let org of orgs) {
                        if (org.checked === true) {
                            org.dataset.org = 1;
                            arr_checked_org.push(org);
                        }
                    }
                }
                if (e.target.dataset.checker === 'depart') {
                    for (let depart of departs) {
                        if (depart.checked === true) {
                            arr_checked_depart.push(depart);
                        }
                        if (e.target.checked === false) {
                            for (let org of orgs) {
                                if (e.target.value === org.dataset.departid) {
                                    org.dataset.org = 0;
                                    org.checked = false;
                                }
                            }
                        }
                    }
                    for (let distr of distrs) {
                        if (distr.checked === true) {
                            arr_checked_distr.push(distr);
                        }
                    }
                }
                if (e.target.dataset.checker === 'distr') {
                    for (let distr of distrs) {
                        if (distr.checked === true) {
                            arr_checked_distr.push(distr);
                        }
                        if (e.target.checked === false) {
                            for (let org of orgs) {
                                if (e.target.value === org.dataset.distrid) {
                                    org.dataset.org = 0;
                                    org.checked = false;
                                }
                            }
                        }
                    }
                    for (let depart of departs) {
                        if (depart.checked === true) {
                            arr_checked_depart.push(depart);
                        }
                    }

                }

                if (arr_checked_depart.length > 0 && arr_checked_distr.length > 0) {
                    for (let i = 0; i < arr_checked_depart.length; i++) {
                        for (let k = 0; k < arr_checked_distr.length; k++) {
                            for (let org of orgs) {
                                if (org.dataset.departid === arr_checked_depart[i].value && org.dataset.distrid === arr_checked_distr[k].value) {
                                    arr_checked_org.push(org);
                                }
                                if (org.dataset.org === '1') {
                                    arr_checked_org.push(org);
                                } else {
                                    org.checked = false;
                                }
                            }
                        }
                    }
                }
                if (arr_checked_depart.length !== 0 && arr_checked_distr.length === 0) {
                    for (let i = 0; i < arr_checked_depart.length; i++) {
                        for (let org of orgs) {
                            if (arr_checked_depart[i].value === org.dataset.departid) {
                                org.checked = true;
                                arr_checked_org.push(org);
                            }
                        }
                    }
                }
                if (arr_checked_depart.length === 0 && arr_checked_distr.length !== 0) {
                    for (let k = 0; k < arr_checked_distr.length; k++) {
                        for (let org of orgs) {
                            if (arr_checked_distr[k].value === org.dataset.distrid) {
                                org.checked = true;
                                arr_checked_org.push(org);
                            }
                        }
                    }
                }
                for (let checked_org of arr_checked_org) {
                    checked_org.checked = true;
                }

                for (let checked_org of arr_checked_org) {
                    arr_checked.push(checked_org.value);
                }
                checked.value = arr_checked.join();
                arr_checked_org = [];
                arr_checked_depart = [];
                arr_checked_distr = [];
                arr_checked = [];
            }
        )
    }
    //
    periodicity.addEventListener('input', (e) => {
        let start = document.getElementById("datetimepicker-s");
        let finish = document.getElementById("datetimepicker-f");
        if (e.target.value > 0) {
            start.disabled = true;
            start.hidden = true;
            finish.disabled = true;
            finish.hidden = true;
            dtp_s.hidden = true;
            dtp_f.hidden = true;
        }
    })
    //
    let success = document.getElementById("prev");
    let sms = document.getElementById("sms");
    let hours = <?php echo $hours ?>;
    let time_delay = <?php echo $time_delay ?>;
    let minutes = <?php echo $minutes ?>;
    cnfrm.onclick = function () {
        if (minutes < time_delay) {
            if (confirm('Прошло меньше ' + minutes + ' мин (прошло: ' + success.innerHTML + ' мин) с момента последней отправки смс, подождите или создайте сейчас без смс.')) {
                sms.value = 'yes';
                form.submit()
            } else {
                sms.value = 'no';
                form.submit()
            }
        } else {
            sms.value = 'yes';
            form.submit();
        }
    }
    //
    jQuery.datetimepicker.setLocale('ru');
    jQuery('#datetimepicker-s, #datetimepicker-f').datetimepicker({
        dayOfWeekStart: 1,
        defaultDate: new Date(),
        timepicker: true,
        format: 'Y-m-d H:i:s',
        lang: 'ru',
        minDate: '-1970/01/01',
        maxDate: '+1970/01/14',
        minTime: '8:00',
        maxTime: '19:05',
        formatTime: 'H:i',
        step: 5
    });
</script>
@include('layouts.footer')
