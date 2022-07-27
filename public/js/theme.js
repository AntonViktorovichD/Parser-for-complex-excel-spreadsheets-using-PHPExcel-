/* Copyright (C) inf-serv GmbH, inf-serv Proprietary Use License (/license) */

/* Copyright (C) inf-serv GmbH, inf-serv Proprietary Use License (/license) */

jQuery(function($) {

    var config = $('html').data('config') || {},
        navbar_height = $('.tm-navbar-container').outerHeight();

    // First block viewport height
    $('.tm-navbar-container:not(.tm-navbar-absolute) + .uk-height-viewport').css({'height': 'calc(100vh - '+ navbar_height +'px)'});

    // Social buttons
    $('article[data-permalink]').socialButtons(config);

    // fit footer
    (function(main, meta, fn){

        if (!main.length) return;

        fn = function() {

            main.css('min-height','');

            meta = document.body.getBoundingClientRect();

            if (meta.height <= window.innerHeight) {
                main.css('min-height', (main.outerHeight() + (window.innerHeight - meta.height))+'px');
            }

            return fn;
        };

        UIkit.$win.on('load resize', fn());

    })($('#tm-main'))

});
