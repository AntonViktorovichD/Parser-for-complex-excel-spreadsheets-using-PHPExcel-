@include('layouts.header')
@include('layouts.menu')
<style>
    .cols {
        columns: 3;
        margin: 50px 150px;
    }
    .uk-panel-box {
        padding: 40px !important;
        margin-left: 35px;
        margin-right: 35px;
    }
    #datetimepicker-f, #datetimepicker-s {
        margin-top: 15px;
    }
    textarea {
        margin-bottom: 50px !important;
    }
</style>
<link rel="stylesheet" href="/css/jquery.datetimepicker.min.css">
<script src="/js/jquery.js"></script>
<script src="/js/jquery.datetimepicker.full.js"></script>
<div id="tm-main" class="tm-block-main uk-block uk-block-default ">
    <div class="uk-container uk-container-center">
        <div class="tm-main uk-grid uk-position-relative" data-uk-grid-match data-uk-grid-margin>
            <div class="tm-main uk-width-medium-1-1 uk-flex-order-last">
                <main id="tm-content" class="tm-content">
                    <div class="page-header">
                        <h2>Создание запроса данных</h2>
                    </div>
                    {{ $ulerror }}
                    <form method="post" action="/ul" enctype="multipart/form-data">
                        <div class="row-fluid nav">
                            <div class="span6 pull-right">
                            </div>
                            <div class="span6">
                            </div>
                        </div>
                        <legend class="legend">Создание запроса из таблицы Excel</legend>
                        @csrf
                        @php
                            echo '<div class="row-fluid">';
                            echo '<label for="reestr_table___label" class="fabrikLabel control-label fabrikTip" title="&lt;ul class=&quot;validation-notices&quot; style=&quot;list-style:none&quot;&gt;&lt;li&gt;&lt;i data-isicon=&quot;true&quot; class=&quot;icon- notempty&quot; &gt;&lt;/i&gt; Это обязательное поле&lt;/li&gt;&lt;/ul&gt;">
                                            <i data-isicon="true" class="icon- small " ></i> Название запроса </label>';
                            echo '<input class="input-xlarge form-control fabrikinput inputbox text" type="text" name="filename">';
                            echo '</div>';
                            echo '<div class="row-fluid">';
                            echo '<input type="radio" name="reg_func" value="v_text">&nbsp text';
                            echo '<input type="radio" name="reg_func" value="v_int">&nbsp int';
                            echo '<input type="radio" name="reg_func" value="v_float">&nbsp float';
                            echo '<input type="radio" name="reg_func" value="v_all">&nbsp all';
                            echo '</div>';
                            echo '<label for="reestr_table___label" class="fabrikLabel control-label fabrikTip validation-notices">
                               Начало сбора данных</label>';
                            echo '<input class="input-xlarge form-control fabrikinput inputbox text" name="created_at" id="datetimepicker-s" type="text">';
                            echo '<div class="row-fluid fabrikLabel">';
                            echo '<label class="control-label">
                              <i data-isicon="true" class="icon- small "></i> Excel файл запроса </label>';
                            echo '<input type="file" name="userfile" accept="..xls,.xlsx">';
                            echo '</div>';
                            echo '<label for="reestr_table___label" class="fabrikLabel control-label fabrikTip">
                              Конец сбора данных</label>';
                            echo '<input class="input-xlarge form-control fabrikinput inputbox text" name="updated_at" id="datetimepicker-f" type="text">';
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
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                            echo '<input style="border: 2px solid #e43d3c !important;" class="btn btn-primary btn-submit-second button" type="submit" @click="check" value="Отправить запрос">';
                        @endphp
                    </form>
                    <script src="/js/vue.global.js"></script>
                    <script src="/js/vue.upload.js"></script>
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
                </main>
            </div>
        </div>
    </div>
</div>
@include('layouts.footer')
