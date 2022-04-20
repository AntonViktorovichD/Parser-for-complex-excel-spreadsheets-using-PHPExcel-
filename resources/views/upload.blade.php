<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Laravel</title>
    <style>
        .cols {
            columns: 3;
            margin: 50px 150px;
        }
    </style>
</head>
<body>
{{ $ulerror }}

<form method="post" action="/ul" enctype="multipart/form-data">
    @csrf
    <input type="file" name="userfile">
    <input type="text" name="filename">
    <input type="submit">
</form>
<?php
$depart_helper = DB::table('depart_helper')->pluck('title');
$depart_helper = (json_decode(json_encode($depart_helper, JSON_UNESCAPED_UNICODE), true));
$depart_helper_id = DB::table('depart_helper')->pluck('id');
$depart_helper_id = (json_decode(json_encode($depart_helper_id, JSON_UNESCAPED_UNICODE), true));
echo '<h3>Типы Учреждений:</h3>';
echo '<div id="v-model-multiple-checkboxes" >';
//echo '<div id="depart_checkboxes" class="cols">';
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
echo '<div id="org_checkboxes" class="cols">';
foreach ($org_helper as $org_counter => $org) {
    $org = preg_replace('#"#', '&quot', $org);
    echo '<input type="checkbox" class="org" id=" ' . $org . '" v-model="checkedOrg" @change="getStatus($event)" data-checker="org" data-departId=" ' . $org_depart_id[$org_counter] . ' " data-distrId=" ' . $org_distr_id[$org_counter] . ' " @change="getOrgStatus($event)" value=" ' . $org_helper_id[$org_counter] . ' "><label for="' . $org . '">' . $org . '</label><br />';
}
echo '</div>';
?>

<script src="/js/vue.global.js"></script>
<script>
    const event = Vue.createApp({
        data() {
            return {
                arr_checked_depart: [],
                arr_checked_distr: [],
            }
        },
        methods: {
            getStatus: function (e) {
                orgns = document.querySelectorAll('.org');
                for (let k = 0; k < orgns.length; k++) {
                    orgns[k].checked = false;
                }
                depart = document.querySelectorAll('.depart');
                if (e.target.dataset.checker == 'depart') {
                    this.arr_checked_depart = [];
                    for (let i = 0; i < depart.length; i++) {
                        if (depart[i].checked == true) {
                            this.arr_checked_depart.push(depart[i]);
                        }
                    }
                }
                distr = document.querySelectorAll('.distr');
                if (e.target.dataset.checker == 'distr') {
                    this.arr_checked_distr = [];
                    for (let i = 0; i < distr.length; i++) {
                        if (distr[i].checked == true) {
                            this.arr_checked_distr.push(distr[i]);
                        }
                    }
                }
                if (this.arr_checked_depart.length !== 0 && this.arr_checked_distr.length !== 0) {
                    for (let i = 0; i < this.arr_checked_depart.length; i++) {
                        for (let j = 0; j < this.arr_checked_distr.length; j++) {
                            for (let k = 0; k < orgns.length; k++) {
                                if (orgns[k].dataset.departid === this.arr_checked_depart[i].value && orgns[k].dataset.distrid === this.arr_checked_distr[j].value) {
                                    orgns[k].checked = true;
                                }
                            }
                        }
                    }
                } else if (this.arr_checked_depart.length !== 0 && this.arr_checked_distr.length === 0) {
                    for (let i = 0; i < this.arr_checked_depart.length; i++) {
                        for (let k = 0; k < orgns.length; k++) {
                            if (orgns[k].dataset.departid === this.arr_checked_depart[i].value) {
                                orgns[k].checked = true;
                            }
                        }
                    }
                } else if (this.arr_checked_depart.length === 0 && this.arr_checked_distr.length !== 0) {
                    for (let j = 0; j < this.arr_checked_distr.length; j++) {
                        for (let k = 0; k < orgns.length; k++) {
                            if (orgns[k].dataset.distrid === this.arr_checked_distr[j].value) {
                                orgns[k].checked = true;
                            }
                        }
                    }
                }
            }
        }
    })
    event.mount('#checkboxes');

    const orgs = Vue.createApp({
        data() {
            return {
                checkedOrgs: []
            }
        },
        methods: {
            getStatus: function (e) {
                if (e.target.dataset.checker == 'org') {
                    orgDepart = document.querySelectorAll('.depart');
                    orgDistr = document.querySelectorAll('.distr');
                    orgDepart.forEach(function (depart_org) {
                        if (depart_org.dataset.value == e.target.dataset.departid) {
                            depart_org.checked = e.target.checked;
                        }
                    })
                    orgDistr.forEach(function (distr_org) {
                        if (distr_org.dataset.value == e.target.dataset.distrid) {
                            distr_org.checked = e.target.checked;
                        }
                    })
                }
            }
        },
    })
    orgs.mount('#org_checkboxes')

</script>
</body>
</html>
