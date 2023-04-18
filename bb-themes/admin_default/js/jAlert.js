(function (s) {
    (s.alerts = {
        verticalOffset: -75,
        horizontalOffset: 0,
        repositionOnResize: !0,
        overlayOpacity: 0.01,
        overlayColor: "#FFF",
        draggable: !0,
        okButton: "&nbsp;OK&nbsp;",
        cancelButton: "&nbsp;Cancel&nbsp;",
        dialogClass: null,
        alert: function (e, t, n) {
            null == t && (t = "Alert"),
                s.alerts._show(t, e, null, "alert", function (e) {
                    n && n(e);
                });
        },
        confirm: function (e, t, n) {
            null == t && (t = "Confirm"),
                s.alerts._show(t, e, null, "confirm", function (e) {
                    n && n(e);
                });
        },
        prompt: function (e, t, n, i) {
            null == n && (n = "Prompt"),
                s.alerts._show(n, e, t, "prompt", function (e) {
                    i && i(e);
                });
        },
        _show: function (e, t, n, i, r) {
            s.alerts._hide(),
                s.alerts._overlay("show"),
                s("BODY").append('<div id="popup_container" class="modal fade show"><div class="modal-dialog modal-dialog-scrollable" role="document">\n' +
                    '<div class="modal-content modal-content-demo"><h5 id="popup_title"></h5><div id="popup_content"><div id="popup_message"></div></div></div></div></div>'),
            s.alerts.dialogClass && s("#popup_container").addClass(s.alerts.dialogClass);
            var a =  "fixed";
            switch (
                (s("#popup_container").css({ position: a, zIndex: 99999, padding: 5, margin: 0 }),
                    s("#popup_title").text(e),
                    s("#popup_content").addClass(i),
                    s("#popup_message").html(t),
                    s("#popup_container").css({ minWidth: s("#popup_container").outerWidth(), maxWidth: s("#popup_container").outerWidth() }),
                    s.alerts._reposition(),
                    s.alerts._maintainPosition(!0),
                    i)
                ) {
                case "alert":
                    s("#popup_message").after('<div id="popup_panel"><input type="button" class="blueBtn" value="' + s.alerts.okButton + '" id="popup_ok" /></div>'),
                        s("#popup_ok").click(function () {
                            s.alerts._hide(), r(!0);
                        }),
                        s("#popup_ok")
                            .focus()
                            .keypress(function (e) {
                                (13 != e.keyCode && 27 != e.keyCode) || s("#popup_ok").trigger("click");
                            });
                    break;
                case "confirm":
                    s("#popup_message").after(
                        '<div id="popup_panel"><input type="button" class="blueBtn" value="' +
                        s.alerts.okButton +
                        '" id="popup_ok" /> <input type="button" class="basicBtn" value="' +
                        s.alerts.cancelButton +
                        '" id="popup_cancel" /></div>'
                    ),
                        s("#popup_ok").click(function () {
                            s.alerts._hide(), r && r(!0);
                        }),
                        s("#popup_cancel").click(function () {
                            s.alerts._hide(), r && r(!1);
                        }),
                        s("#popup_ok").focus(),
                        s("#popup_ok, #popup_cancel").keypress(function (e) {
                            13 == e.keyCode && s("#popup_ok").trigger("click"), 27 == e.keyCode && s("#popup_cancel").trigger("click");
                        });
                    break;
                case "prompt":
                    s("#popup_message")
                        .append('<br /><input type="text" size="20" id="popup_prompt" />')
                        .after(
                            '<div id="popup_panel"><input type="button" class="blueBtn" value="' +
                            s.alerts.okButton +
                            '" id="popup_ok" /> <input type="button" class="basicBtn" value="' +
                            s.alerts.cancelButton +
                            '" id="popup_cancel" /></div>'
                        ),
                        s("#popup_prompt").width(s("#popup_message").width(-10)),
                        s("#popup_ok").click(function () {
                            var e = s("#popup_prompt").val();
                            s.alerts._hide(), r && r(e);
                        }),
                        s("#popup_cancel").click(function () {
                            s.alerts._hide(), r && r(null);
                        }),
                        s("#popup_prompt, #popup_ok, #popup_cancel").keypress(function (e) {
                            13 == e.keyCode && s("#popup_ok").trigger("click"), 27 == e.keyCode && s("#popup_cancel").trigger("click");
                        }),
                    n && s("#popup_prompt").val(n),
                        s("#popup_prompt").focus().select();
            }
            if (s.alerts.draggable)
                try {
                    s("#popup_container").draggable({ handle: s("#popup_title") }), s("#popup_title").css({ cursor: "move" });
                } catch (e) {}
        },
        _hide: function () {
            s("#popup_container").remove(), s.alerts._overlay("hide"), s.alerts._maintainPosition(!1);
        },
        _overlay: function (e) {
            switch (e) {
                case "show":
                    s.alerts._overlay("hide"),
                        s("BODY").append('<div id="popup_overlay"></div>'),
                        s("#popup_overlay").css({ position: "absolute", zIndex: 99998, top: "0px", left: "0px", width: "100%", height: s(document).height(), background: s.alerts.overlayColor, opacity: s.alerts.overlayOpacity });
                    break;
                case "hide":
                    s("#popup_overlay").remove();
            }
        },
        _reposition: function () {
            var e = s(window).height() / 2 - s("#popup_container").outerHeight() / 2 + s.alerts.verticalOffset,
                t = s(window).width() / 2 - s("#popup_container").outerWidth() / 2 + s.alerts.horizontalOffset;
            e < 0 && (e = 0),
            t < 0 && (t = 0),
            s.browser.msie && parseInt(s.browser.version) <= 6 && (e += s(window).scrollTop()),
                s("#popup_container").css({ top: e + "px", left: t + "px" }),
                s("#popup_overlay").height(s(document).height());
        },
        _maintainPosition: function (e) {
            if (s.alerts.repositionOnResize)
                switch (e) {
                    case !0:
                        s(window).bind("resize", s.alerts._reposition);
                        break;
                    case !1:
                        s(window).unbind("resize", s.alerts._reposition);
                }
        },
    }),
        (jAlert = function (e, t, n) {
            s.alerts.alert(e, t, n);
        }),
        (jConfirm = function (e, t, n) {
            s.alerts.confirm(e, t, n);
        }),
        (jPrompt = function (e, t, n, i) {
            s.alerts.prompt(e, t, n, i);
        });
})(jQuery);
