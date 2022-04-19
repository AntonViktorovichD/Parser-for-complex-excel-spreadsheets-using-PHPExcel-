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
echo '<div id="depart_checkboxes" class="cols">';
foreach ($depart_helper as $depart_counter => $depart) {
    echo '<input type="checkbox" class="depart" id=" ' . $depart . ' " v-model="checkedDeparts" data-checker="depart" @change="getStatus($event)" value=" ' . $depart_helper_id[$depart_counter] . ' " data-value=" ' . $depart_helper_id[$depart_counter] . ' "><label for="' . $depart . '">' . $depart . '</label><br />';
}
echo '</div>';
$distr_helper = DB::table('distr_helper')->pluck('title');
$distr_helper = (json_decode(json_encode($distr_helper, JSON_UNESCAPED_UNICODE), true));
$distr_helper_id = DB::table('distr_helper')->pluck('id');
$distr_helper_id = (json_decode(json_encode($distr_helper_id, JSON_UNESCAPED_UNICODE), true));
echo '<h3>Районы:</h3>';
echo '<div id="distr_checkboxes" class="cols">';
foreach ($distr_helper as $distr_counter => $distr) {
    echo '<input type="checkbox" class="distr" id=" ' . $distr . '" v-model="checkedDistrs" data-checker="distr" @change="getStatus($event)" value=" ' . $distr_helper_id[$distr_counter] . ' " data-value=" ' . $distr_helper_id[$distr_counter] . ' "><label for="' . $distr . '">' . $distr . '</label><br />';
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
echo '<div id="org_checkboxes checkboxes" class="cols">';
foreach ($org_helper as $org_counter => $org) {
    $org = preg_replace('#"#', '&quot', $org);
    echo '<input type="checkbox" class="org" id=" ' . $org . '" v-model="checkedOrg" @change="getStatus($event)" data-checker="org" data-departId=" ' . $org_depart_id[$org_counter] . ' " data-distrId=" ' . $org_distr_id[$org_counter] . ' " @change="getOrgStatus($event)" value=" ' . $org_helper_id[$org_counter] . ' "><label for="' . $org . '">' . $org . '</label><br />';
}
echo '</div>';
?>

<script src="/js/vue.global.js"></script>
<script>
    const depart = Vue.createApp({
        data() {
            return {
                checkedDeparts: [],
            }
        },
        methods: {
            getStatus: function (e) {
                for (let i = 0; i <= '<?php echo $depart_counter; ?>'; i++) {
                    if (e.target.dataset.checker == 'depart') {
                        departOrg = document.querySelectorAll('.org');
                        departOrg.forEach(function (depart) {
                            if (depart.dataset.departid == e.target.dataset.value) {
                                depart.checked = e.target.checked;
                            }
                        })
                    }
                }
            }
        }
    })
    depart.mount('#depart_checkboxes')
    const distr = Vue.createApp({
        data() {
            return {
                checkedDistrs: [],
            }
        },
        methods: {
            getStatus: function (e) {
                for (let i = 0; i <= '<?php echo $distr_counter; ?>'; i++) {
                    if (e.target.dataset.checker == 'distr') {
                        distrOrg = document.querySelectorAll('.org');
                        distrOrg.forEach(function (distr) {
                            if (distr.dataset.distrid == e.target.dataset.value) {
                                distr.checked = e.target.checked;
                            }
                        })
                    }
                }
            }
        }
    })
    distr.mount('#distr_checkboxes')
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
        }
    })
    orgs.mount('#org_checkboxes')
</script>
</body>
</html>
