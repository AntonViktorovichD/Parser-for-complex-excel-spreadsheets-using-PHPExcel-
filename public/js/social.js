/* Copyright (C) inf-serv GmbH, http://www.gnu.org/licenses/gpl.html GNU/GPL */

!function (t) {
    var e = {};
    t.fn.socialButtons = function (a) {
        return a = t.extend({wrapper: '<div class="tm-socialbuttons uk-clearfix">'}, a), a.twitter || a.plusone || a.facebook ? (a.twitter && !e.twitter && (e.twitter = t.getScript("//platform.twitter.com/widgets.js")), a.plusone && !e.plusone && (e.plusone = t.getScript("//apis.google.com/js/plusone.js")), window.FB || !a.facebook || e.facebook || (t("body").append('<div id="fb-root"></div>'), function (t, e, a) {
            var o, i = t.getElementsByTagName(e)[0];
            t.getElementById(a) || (o = t.createElement(e), o.id = a, o.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.0", i.parentNode.insertBefore(o, i))
        }(document, "script", "facebook-jssdk"), e.facebook = !0), this.each(function () {
            var e = t(this).data("permalink"), o = t(a.wrapper).appendTo(this);
            a.twitter && o.append('<div><a href="http://twitter.com/share" class="twitter-share-button" data-url="' + e + '" data-count="none">Tweet</a></div>'), a.plusone && o.append('<div><div class="g-plusone" data-size="medium" data-annotation="none" data-href="' + e + '"></div></div>'), a.facebook && o.append('<div><div class="fb-like" data-href="' + e + '" data-layout="button_count" data-action="like" data-width="100" data-show-faces="false" data-share="false"></div></div>')
        })) : this
    }, t(function () {
        if (window.MooTools && Element.prototype.hide) {
            var e = Element.prototype.hide;
            Element.prototype.hide = function () {
                return t(this).is('[class*="uk-"]') ? this : e.apply(this, [])
            }
        }
    })
}(jQuery);
