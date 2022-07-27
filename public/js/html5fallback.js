(function (e, t, n) {
    typeof Object.create != "function" && (Object.create = function (e) {
        function t() {
        }

        return t.prototype = e, new t
    });
    var r = {
        init: function (n, r) {
            var i = this;
            i.elem = r, i.$elem = e(r), r.H5Form = i, i.options = e.extend({}, e.fn.h5f.options, n), i.field = t.createElement("input"), i.checkSupport(i), r.nodeName.toLowerCase() === "form" && i.bindWithForm(i.elem, i.$elem)
        }, bindWithForm: function (e, t) {
            var r = this, i = !!t.attr("novalidate"), s = e.elements, o = s.length;
            r.options.formValidationEvent === "onSubmit" && t.on("submit", function (e) {
                i = !!t.attr("novalidate");
                var s = this.H5Form.donotValidate != n ? this.H5Form.donotValidate : !1;
                !s && !i && !r.validateForm(r) ? (e.preventDefault(), this.donotValidate = !1) : t.find(":input").each(function () {
                    r.placeholder(r, this, "submit")
                })
            }), t.on("focusout focusin", function (e) {
                r.placeholder(r, e.target, e.type)
            }), t.on("focusout change", r.validateField), t.find("fieldset").on("change", function () {
                r.validateField(this)
            }), r.browser.isFormnovalidateNative || t.find(":submit[formnovalidate]").on("click", function () {
                r.donotValidate = !0
            });
            while (o--) {
                var u = s[o];
                r.polyfill(u), r.autofocus(r, u)
            }
        }, polyfill: function (e) {
            if (e.nodeName.toLowerCase() === "form") return !0;
            var t = e.form.H5Form;
            t.placeholder(t, e), t.numberType(t, e)
        }, checkSupport: function (e) {
            e.browser = {}, e.browser.isRequiredNative = "required" in e.field, e.browser.isPatternNative = "pattern" in e.field, e.browser.isPlaceholderNative = "placeholder" in e.field, e.browser.isAutofocusNative = "autofocus" in e.field, e.browser.isFormnovalidateNative = "formnovalidate" in e.field, e.field.setAttribute("type", "email"), e.browser.isEmailNative = e.field.type == "email", e.field.setAttribute("type", "url"), e.browser.isUrlNative = e.field.type == "url", e.field.setAttribute("type", "number"), e.browser.isNumberNative = e.field.type == "number", e.field.setAttribute("type", "range"), e.browser.isRangeNative = e.field.type == "range"
        }, validateForm: function () {
            var e = this, t = e.elem, n = t.elements, r = n.length, i = !0;
            t.isValid = !0;
            for (var s = 0; s < r; s++) {
                var o = n[s];
                o.isRequired = !!o.required, o.isDisabled = !!o.disabled, o.isDisabled || (i = e.validateField(o), t.isValid && !i && e.setFocusOn(o), t.isValid = i && t.isValid)
            }
            return e.options.doRenderMessage && e.renderErrorMessages(e, t), t.isValid
        }, validateField: function (t) {
            var r = t.target || t;
            if (r.form === n) return null;
            var i = r.form.H5Form, s = e(r), o = !1, u = !!e(r).attr("required"), a = !!s.attr("disabled");
            r.isDisabled || (o = !i.browser.isRequiredNative && u && i.isValueMissing(i, r), isPatternMismatched = !i.browser.isPatternNative && i.matchPattern(i, r)), r.validityState = {
                valueMissing: o,
                patterMismatch: isPatternMismatched,
                valid: r.isDisabled || !o && !isPatternMismatched
            }, i.browser.isRequiredNative || (r.validityState.valueMissing ? s.addClass(i.options.requiredClass) : s.removeClass(i.options.requiredClass)), i.browser.isPatternNative || (r.validityState.patterMismatch ? s.addClass(i.options.patternClass) : s.removeClass(i.options.patternClass));
            if (!r.validityState.valid) {
                s.addClass(i.options.invalidClass);
                var f = i.findLabel(s);
                f.addClass(i.options.invalidClass)
            } else {
                s.removeClass(i.options.invalidClass);
                var f = i.findLabel(s);
                f.removeClass(i.options.invalidClass)
            }
            return r.validityState.valid
        }, isValueMissing: function (r, i) {
            var s = e(i), o = /^(input|textarea|select)$/i, u = /^submit$/i, a = s.val(),
                f = i.type !== n ? i.type : i.tagName.toLowerCase(), l = /^(checkbox|radio|fieldset)$/i;
            if (!l.test(f) && !u.test(f)) {
                if (a === "") return !0;
                if (!r.browser.isPlaceholderNative && s.hasClass(r.options.placeholderClass)) return !0
            } else if (l.test(f)) {
                if (f === "checkbox") return !s.is(":checked");
                var c;
                f === "fieldset" ? c = s.find("input") : c = t.getElementsByName(i.name);
                for (var h = 0; h < c.length; h++) if (e(c[h]).is(":checked")) return !1;
                return !0
            }
            return !1
        }, matchPattern: function (t, r) {
            var i = e(r),
                s = !t.browser.isPlaceholderNative && i.attr("placeholder") && i.hasClass(t.options.placeholderClass) ? "" : i.attr("value"),
                o = i.attr("pattern"), u = i.attr("type");
            if (s !== "") if (u === "email") {
                var a = !0;
                if (i.attr("multiple") === n) return !t.options.emailPatt.test(s);
                s = s.split(t.options.mutipleDelimiter);
                for (var f = 0; f < s.length; f++) {
                    a = t.options.emailPatt.test(s[f].replace(/[ ]*/g, ""));
                    if (!a) return !0
                }
            } else {
                if (u === "url") return !t.options.urlPatt.test(s);
                if (u === "text" && o !== n) return usrPatt = new RegExp("^(?:" + o + ")$"), !usrPatt.test(s)
            }
            return !1
        }, placeholder: function (t, r, i) {
            var s = e(r), o = {placeholder: s.attr("placeholder")}, u = /^(focusin|submit)$/i,
                a = /^(input|textarea)$/i, f = /^password$/i, l = t.browser.isPlaceholderNative;
            !l && a.test(r.nodeName) && !f.test(r.type) && o.placeholder !== n && (r.value === "" && !u.test(i) ? (r.value = o.placeholder, s.addClass(t.options.placeholderClass)) : r.value === o.placeholder && u.test(i) && (r.value = "", s.removeClass(t.options.placeholderClass)))
        }, numberType: function (t, n) {
            var r = e(n);
            node = /^input$/i, type = r.attr("type");
            if (node.test(n.nodeName) && (type == "number" && !t.browser.isNumberNative || type == "range" && !t.browser.isRangeNative)) {
                var i = parseInt(r.attr("min")), s = parseInt(r.attr("max")), o = parseInt(r.attr("step")),
                    u = parseInt(r.attr("value")), a = r.prop("attributes"), f = e("<select>"), l;
                i = isNaN(i) ? -100 : i;
                for (var c = i; c <= s; c += o) l = e("<option>").attr("value", c).text(c), (u == c || u > c && u < c + o) && l.attr("selected", ""), f.append(l);
                e.each(a, function () {
                    f.attr(this.name, this.value)
                }), r.replaceWith(f)
            }
        }, autofocus: function (n, r) {
            var i = e(r), s = !!i.attr("autofocus"), o = /^(input|textarea|select|fieldset)$/i, u = /^submit$/i,
                a = n.browser.isAutofocusNative;
            !a && o.test(r.nodeName) && !u.test(r.type) && s && e(t).ready(function () {
                n.setFocusOn(r)
            })
        }, findLabel: function (t) {
            var n = e('label[for="' + t.attr("id") + '"]');
            if (n.length <= 0) {
                var r = t.parent(), i = r.get(0).tagName.toLowerCase();
                i == "label" && (n = r)
            }
            return n
        }, setFocusOn: function (t) {
            t.tagName.toLowerCase() === "fieldset" ? e(t).find(":first").focus() : e(t).focus()
        }, renderErrorMessages: function (t, n) {
            var r = n.elements, i = r.length, s = {};
            s.errors = new Array;
            while (i--) {
                var o = e(r[i]), u = t.findLabel(o);
                o.hasClass(t.options.requiredClass) && (s.errors[i] = u.text().replace("*", "") + t.options.requiredMessage), o.hasClass(t.options.patternClass) && (s.errors[i] = u.text().replace("*", "") + t.options.patternMessage)
            }
            s.errors.length > 0 && Joomla.renderMessages(s)
        }
    };
    e.fn.h5f = function (e) {
        return this.each(function () {
            var t = Object.create(r);
            t.init(e, this)
        })
    }, e.fn.h5f.options = {
        invalidClass: "invalid",
        requiredClass: "required",
        requiredMessage: " is required.",
        placeholderClass: "placeholder",
        patternClass: "pattern",
        patternMessage: " doesn't match pattern.",
        doRenderMessage: !1,
        formValidationEvent: "onSubmit",
        emailPatt: /^[a-zA-Z0-9.!#$%&вЂљГ„Гґ*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/,
        urlPatt: /[a-z][\-\.+a-z]*:\/\//i
    }, e(function () {
        e("form").h5f({doRenderMessage: !0, requiredClass: "musthavevalue"})
    })
})(jQuery, document);
