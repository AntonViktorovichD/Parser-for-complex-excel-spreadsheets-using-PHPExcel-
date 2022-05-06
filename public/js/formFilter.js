// РљРѕРґ РґР»СЏ РѕР±СЂР°Р±РѕС‚РєРё С„РѕСЂРјС‹ РїРѕ СѓС‡СЂРµР¶РґРµРЅРёСЏРј
(function ($) {
    $(document).ready(function () {
        // Р‘Р»РѕРє С‚РёРїРѕРІ СѓС‡СЂРµР¶РґРµРЅРёР№
        var uchrtype_block = '<div class="row-fluid newblock"><label class="fabrikLabel control-label fabrikTip">РўРёРї СѓС‡СЂРµР¶РґРµРЅРёСЏ</label><div class="controls" id="uchrtypeboxes"></div></div>';
        // Р‘Р»РѕРє С‡РµРєР±РѕРєСЃРѕРІ
        var dist_block = '<div class="row-fluid newblock"><label class="fabrikLabel control-label fabrikTip">Р Р°Р№РѕРЅ</label><div class="controls" id="distboxes"></div></div>';
        // Р”РѕР±Р°РІР»СЏРµРј Р±Р»РѕРєРё РЅР° СЃС‚СЂР°РЅРёС†Сѓ
        var rows = $("fieldset > .row-fluid");
        $("fieldset > .row-fluid")[(rows.length - 2)].innerHTML = uchrtype_block + dist_block + '<div class="row-fluid">' + $("fieldset > .row-fluid")[(rows.length - 2)].innerHTML + '</div>';
        var checkboxes_type = '<label class="checkbox"><input class="uchrtype" onChange="uploadUchr()" type="checkbox" value="0"><span>РЈРїСЂР°РІР»РµРЅРёСЏ СЃРѕС†РёР°Р»СЊРЅРѕР№ Р·Р°С‰РёС‚С‹ РЅР°СЃРµР»РµРЅРёСЏ</span></label>';
        checkboxes_type += '<label class="checkbox"><input class="uchrtype" onChange="uploadUchr()" type="checkbox" value="1"><span>Р¦РµРЅС‚СЂС‹ СЃРѕС†РёР°Р»СЊРЅРѕРіРѕ РѕР±СЃР»СѓР¶РёРІР°РЅРёСЏ РЅР°СЃРµР»РµРЅРёСЏ</span></label>';
        checkboxes_type += '<label class="checkbox"><input class="uchrtype" onChange="uploadUchr()" type="checkbox" value="2"><span>РЈС‡СЂРµР¶РґРµРЅРёСЏ СЃС‚Р°С†РёРѕРЅР°СЂРЅРѕРіРѕ С‚РёРїР°</span></label>';
        checkboxes_type += '<label class="checkbox"><input class="uchrtype" onChange="uploadUchr()" type="checkbox" value="3"><span>Р”РµС‚СЃРєРёРµ СѓС‡СЂРµР¶РґРµРЅРёСЏ</span></label>';
        checkboxes_type += '<label class="checkbox"><input class="uchrtype" onChange="uploadUchr()" type="checkbox" value="4"><span>РћСЃС‚Р°Р»СЊРЅС‹Рµ СѓС‡СЂРµР¶РґРµРЅРёСЏ</span></label>';
        $("#uchrtypeboxes")[0].innerHTML = checkboxes_type;

        // РџРѕР»СѓС‡Р°РµРј РґР°РЅРЅС‹Рµ Рѕ СЂР°Р№РѕРЅР°С…
        $.ajax({
            type: 'post',
            url: 'plugins/fabrik_form/php/scripts/dataForForm.php',
            async: false,
            dataType: "json",
            data: {
                'function': 'loadDist'
            },
            success: function (response) {
                // Р”РѕР±Р°РІР»СЏРµРј РґРѕРїРѕР»РЅРёС‚РµР»СЊРЅС‹Рµ Р°С‚СЂРёР±СѓС‚С‹ Рє СѓС‡СЂРµР¶РґРµРЅРёСЏРј РґР»СЏ РґР°Р»СЊРЅРµР№С€РµР№ РёС… РѕР±СЂР°Р±РѕС‚РєРё РІ С„СѓРЅРєС†РёСЏС…
                for (var i = 0; i < response.length; i++) {
                    $('input[type="checkbox"][value="' + response[i].id + '"]')[0].setAttribute('dist', response[i].parent_id);
                    $('input[type="checkbox"][value="' + response[i].id + '"]')[0].setAttribute('uchrtype', response[i].type);
                    $('input[type="checkbox"][value="' + response[i].id + '"]')[0].addClass('uchr');
                }
            }
        });
        $.ajax({
            type: 'post',
            url: 'plugins/fabrik_form/php/scripts/dataForForm.php',
            async: false,
            dataType: "json",
            data: {
                'function': 'loadRegion'
            },
            success: function (response) {
                // РЎРѕР·РґР°РµРј С‡РµРєР±РѕРєСЃС‹ СЂР°Р№РѕРЅРѕРІ
                var dist_options = '';
                var cols = 4; // РљРѕР»РёС‡РµСЃС‚РІРѕ СЃС‚РѕР»Р±С†РѕРІ
                for (var row = 0; row < Math.ceil(response.length / cols); row++) {
                    dist_options += '<div class="row-fluid" data-role="fabrik-rowopts">';
                    for (var i = cols * row; i < (cols * row + cols); i++) {
                        if (response[i] !== undefined) {
                            // РЎРѕР·РґР°РµРј checkbox Рё label РґР»СЏ РєР°Р¶РґРѕРіРѕ id СѓС‡СЂРµР¶РґРµРЅРёСЏ
                            dist_options += '<div class="span3"><label class="checkbox"><input type="checkbox" class="dist" onChange="uploadUchr()" value="' + response[i].id + '"><span>' + response[i].title + '</span></label></div>';
                        }
                    }
                    dist_options += '</div>';
                }
                $('#distboxes')[0].innerHTML = dist_options;
            }
        });
        window.uploadUchr;
        uploadUchr = function () {
            var uchr = $('.uchr');
            for (var i = 0; i < uchr.length; i++) {
                uchr[i].checked = false;
            } // РћС‡РёС‰Р°РµРј С‡РµРєР±РѕРєСЃС‹ СѓС‡СЂРµР¶РґРµРЅРёР№

            var uchrtype = $('.uchrtype'); 		// РњР°СЃСЃРёРІ РІСЃРµС… С‡РµРєР±РѕРєСЃРѕРІ С‚РёРїРѕРІ СѓС‡СЂРµР¶РґРµРЅРёР№
            var dist = $('.dist');				// РњР°СЃСЃРёРІ РІСЃРµС… С‡РµРєР±РѕРєСЃРѕРІ СЂР°Р№РѕРЅРѕРІ
            var checked_dist = new Array();		// РњР°СЃСЃРёРІ РІС‹Р±СЂР°РЅРЅС‹С… С‡РµРєР±РѕРєСЃРѕРІ СЂР°Р№РѕРЅРѕРІ
            var checked_uchrtype = new Array(); // РњР°СЃСЃРёРІ РІС‹Р±СЂР°РЅРЅС‹С… С‡РµРєР±РѕРєСЃРѕРІ СЂР°Р№РѕРЅРѕРІ
            for (var i = 0; i < dist.length; i++) {
                if (dist[i].checked === true) {
                    checked_dist.push(dist[i]); 		// Р—Р°РїРѕР»РЅСЏРµРј РјР°СЃСЃРёРІ РІС‹Р±СЂР°РЅРЅС‹РјРё С‡РµРєР±РѕРєСЃР°РјРё
                }
            }
            for (var i = 0; i < uchrtype.length; i++) {
                if (uchrtype[i].checked === true) {
                    checked_uchrtype.push(uchrtype[i]);	// Р—Р°РїРѕР»РЅСЏРµРј РјР°СЃСЃРёРІ РІС‹Р±СЂР°РЅРЅС‹РјРё С‡РµРєР±РѕРєСЃР°РјРё
                }
            }

            // РџСЂРѕРІРµСЂСЏРµРј РєР°РєРёРµ С„РёР»СЊС‚СЂСѓСЋС‰РёРµ С‡РµРєР±РѕРєСЃС‹ СѓСЃС‚Р°РЅРѕРІР»РµРЅС‹ Рё РІ СЃРѕРѕС‚РІРµС‚СЃС‚РІРёРё СЃ СЌС‚РёРј СѓСЃС‚Р°РЅР°РІР»РёРІР°РµРј РЅРµРѕР±С…РѕРґРёРјС‹Рµ С‡РµРєР±РѕРєСЃС‹
            if (checked_dist.length != 0 && checked_uchrtype != 0) {
                for (var j = 0; j < checked_uchrtype.length; j++) {
                    for (var k = 0; k < checked_dist.length; k++) {
                        var checkbox = $('input[type="checkbox"][uchrtype="' + checked_uchrtype[j].value + '"][dist="' + checked_dist[k].value + '"]');
                        for (var i = 0; i < checkbox.length; i++) {
                            checkbox[i].checked = true; // РЈСЃС‚Р°РЅР°РІР»РёРІР°РµРј РІС‹Р±СЂР°РЅРЅС‹Р№ С‡РµРєР±РѕРєСЃ Р°РєС‚РёРІРЅС‹Рј
                        }
                    }
                }
            } else if (checked_dist.length != 0 && checked_uchrtype == 0) {
                for (var k = 0; k < checked_dist.length; k++) {
                    var checkbox = $('input[type="checkbox"][dist="' + checked_dist[k].value + '"]');
                    for (var i = 0; i < checkbox.length; i++) {
                        checkbox[i].checked = true; // РЈСЃС‚Р°РЅР°РІР»РёРІР°РµРј РІС‹Р±СЂР°РЅРЅС‹Р№ С‡РµРєР±РѕРєСЃ Р°РєС‚РёРІРЅС‹Рј
                    }
                }
            } else if (checked_dist.length == 0 && checked_uchrtype != 0) {
                for (var j = 0; j < checked_uchrtype.length; j++) {
                    var checkbox = $('input[type="checkbox"][uchrtype="' + checked_uchrtype[j].value + '"]')
                    for (var i = 0; i < checkbox.length; i++) {
                        checkbox[i].checked = true; // РЈСЃС‚Р°РЅР°РІР»РёРІР°РµРј РІС‹Р±СЂР°РЅРЅС‹Р№ С‡РµРєР±РѕРєСЃ Р°РєС‚РёРІРЅС‹Рј
                    }
                }
            }
        }
    });
})(jQuery);