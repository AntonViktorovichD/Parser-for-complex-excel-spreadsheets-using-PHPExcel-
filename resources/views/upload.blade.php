@include('layouts.header')
@include('layouts.menu')
<style>

</style>
<link rel="stylesheet" href="/css/jquery.datetimepicker.min.css">
<script src="/js/jquery.js"></script>
<script src="/js/jquery.datetimepicker.full.js"></script>
<div class="container px-4">

    <h1>Создание запроса данных</h1>
    {{ $ulerror }}
    <form method="post" id="form" action="/ul" enctype="multipart/form-data">
        <legend class="legend">Создание запроса из таблицы Excel</legend>
        <div class="">
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

            echo '<h3>Типы Учреждений:</h3>';
            echo '<div id="v-model-multiple-checkboxes" >';
            echo '<div id="checkboxes">';
            echo '<div class="cols">';
            foreach ($depart_helper as $depart_counter => $depart) {
                echo '<input type="checkbox" class="depart" id=" ' . $depart . ' " v-model="checked" data-checker="depart" @change="getStatus($event)" value=" ' . $depart_helper_id[$depart_counter] . ' " data-value=" ' . $depart_helper_id[$depart_counter] . ' "><label for="' . $depart . '">' . $depart . '</label><br />';
            }
            echo '</div>';
            echo '</div>';
            $distr_helper = DB::table('distr_helper')->pluck('title');
            $distr_helper = (json_decode(json_encode($distr_helper, JSON_UNESCAPED_UNICODE), true));
            $distr_helper_id = DB::table('distr_helper')->pluck('id');
            $distr_helper_id = (json_decode(json_encode($distr_helper_id, JSON_UNESCAPED_UNICODE), true));
            echo '<h3>Районы:</h3>';
            echo '<div class="cols">';
            foreach ($distr_helper as $distr_counter => $distr) {
                echo '<input type="checkbox" class="distr" id=" ' . $distr . '" v-model="checked" data-checker="distr" @change="getStatus($event)" value=" ' . $distr_helper_id[$distr_counter] . ' " data-value=" ' . $distr_helper_id[$distr_counter] . ' "><label for="' . $distr . '">' . $distr . '</label><br />';
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
            echo '<h3>Учреждения:</h3>';
            echo '<div class="cols">';
            foreach ($org_helper as $org_counter => $org) {
                $org = preg_replace('#"#', '&quot', $org);
                echo '<input type="checkbox" class="org" name="org[' . $org_helper_id[$org_counter] . ']" id=" ' . $org . '" v-model="checked" @change="getStatus($event)" data-checker="org" data-departId=" ' . $org_depart_id[$org_counter] . ' " data-distrId=" ' . $org_distr_id[$org_counter] . ' " @change="getOrgStatus($event)" value=" ' . $org_helper_id[$org_counter] . ' "><label for="' . $org . '">' . $org . '</label><br />';
            }
            echo '</div>';
            echo '<div class="row-fluid">';
            echo '<div class="control-group fabrikElementContainer plg-textarea">';
            echo '<p><label class="fabrikLabel control-label"> Комментарий к запросу </label></p>';
            echo '<div class="controls">';
            echo '<div class="fabrikElement">';
            echo '<textarea class="fabrikinput inputbox input-block-level" name="comment" cols="40" rows="6"></textarea>';
            echo '<input id="sms" type="text" name="sms" hidden>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '<input style="border: 2px solid #e43d3c !important;" class="btn btn-primary btn-submit-second button" id="cnfrm" @click="check" value="Отправить запрос">';
        @endphp
    </form>
</div>
<script src="/js/vue.global.js"></script>
<script src="/js/vue.upload.js"></script>
<script>
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
</script>
<script>
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
</script>
<script>
    jQuery.datetimepicker.setLocale('ru');
    jQuery('#datetimepicker-s, #datetimepicker-f').datetimepicker({
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
