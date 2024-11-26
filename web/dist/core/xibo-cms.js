var timelineForm, lastForm, gridTimeouts = [],
    buttonsTemplate;

function XiboInitialise(scope) {
    null != scope && "" != scope || (scope = " "), $(scope + " .XiboGrid").each((function() {
        var e = $(this).data().gridName,
            t = $(this).find(".XiboFilter form");
        if (null != e) {
            var a;
            try {
                null == (a = JSON.parse(localStorage.getItem(e))) && (localStorage.setItem(e, JSON.stringify(t.serializeArray())), a = JSON.parse(localStorage.getItem(e)))
            } catch (e) {
                a = []
            }
            const n = new URL(window.location.href);
            var o = new URLSearchParams(n.search.slice(1));
            $.each(a, (function(e, a) {
                var n = a.name.replace(/\[\]/, "\\\\[\\\\]");
                try {
                    var i = t.find("input[name=" + n + "], select[name=" + n + "]");
                    null !== o.get(n) ? i.val(o.get(n)) : i.length > 0 && i.val(a.value)
                } catch (e) {
                    console.log("Error populating form saved value with selector input[name=" + a.name + "], select[name=" + a.name + "]")
                }
            }))
        }
        var n = _.debounce((function() {
            null != e && localStorage.setItem(e, JSON.stringify(t.serializeArray())), $(this).closest(".XiboGrid").find("table[role='grid']").DataTable().ajax.reload()
        }), 500);
        $(this).find(".XiboFilter form").on("keydown", (function(e) {
            if (13 == e.keyCode) return e.preventDefault(), !1
        })), $(this).find(".XiboFilter form input").on("keyup", n), $(this).find(".XiboFilter form input, .XiboFilter form select").on("change", n)
    })), $(scope + " .XiboFormButton").click((function() {
        var e = $(this).data("eventStart"),
            t = $(this).data("eventEnd");
        if (void 0 !== e && void 0 !== t) {
            var a = {
                eventStart: e,
                eventEnd: t
            };
            XiboFormRender($(this), a)
        } else XiboFormRender($(this));
        return !1
    })), $(scope + " .XiboCustomFormButton").click((function() {
        return XiboCustomFormRender($(this)), !1
    })), $(scope + " .XiboRedirectButton").click((function() {
        window.location = $(this).attr("href")
    })), $(scope + " .XiboHoverButton").hover((function(e) {
        return XiboHoverRender($(this).attr("href"), e.pageX, e.pageY), !1
    }), (function() {
        return !1
    })), $(scope + " .XiboForm").validate({
        submitHandler: XiboFormSubmit,
        errorElement: "span",
        highlight: function(e) {
            $(e).closest(".form-group").removeClass("has-success").addClass("has-error")
        },
        success: function(e) {
            $(e).closest(".form-group").removeClass("has-error").addClass("has-success")
        },
        invalidHandler: function(e, t) {
            $(this).closest(".modal-dialog").find(".saving").remove(), $(this).closest(".modal-dialog").find(".save-button").removeClass("disabled")
        }
    }), $(scope + " .XiboAjaxSubmit").click((function() {
        return $.ajax({
            type: "post",
            url: $(this).attr("href"),
            cache: !1,
            dataType: "json",
            success: XiboSubmitResponse
        }), !1
    })), $(scope + " .XiboAutoForm").submit((function() {
        return XiboFormSubmit(this), !1
    })), $(scope + " .XiboTextForm").validate({
        submitHandler: XiboFormSubmit,
        errorElement: "span",
        highlight: function(e) {
            $(e).closest(".form-group").removeClass("has-success").addClass("has-error")
        },
        success: function(e) {
            $(e).closest(".form-group").removeClass("has-error").addClass("has-success")
        }
    }), $(scope + " .XiboHelpButton").click((function() {
        var e = $(this).attr("href");
        return window.open(e), !1
    })), $(scope + " div.morrisChart").each((function() {
        var data = eval($(this).attr("id"));
        "line" == data.type ? new Morris.Line(data.data) : "donut" == data.type ? new Morris.Donut(data.data) : "bar" == data.type && new Morris.Bar(data.data)
    })), $(scope + " .dropdown-menu").on("click", (function(e) {
        $(this).hasClass("dropdown-menu-form") && e.stopPropagation()
    })), $(scope + " .dateTimePicker").each((function() {
        var e = $("#" + $(this).data().linkCombined).val();
        $(this).find(".dateTimePickerDate").datetimepicker({
            format: bootstrapDateFormatDateOnly,
            autoclose: !0,
            language,
            minView: 2,
            calendarType
        }).change((function() {
            var e = $("#" + $(this).data().linkCombined),
                t = $(this).val();
            if ("" === t) e.val("");
            else {
                t = moment(t, jsDateFormat.replace(jsTimeFormat, "").replace(" ", ""));
                var a = "" === e.val() ? moment() : moment(e.val(), systemDateFormat);
                a.isValid() || (a = moment()), a.year(t.year()), a.month(t.month()), a.date(t.date()), e.val(a.format(systemDateFormat))
            }
        })), void 0 !== e && "" !== e && $(this).find(".dateTimePickerDate").datetimepicker("update", moment(e, systemDateFormat).format(systemDateFormat)), $(this).find(".dateTimePickerTime").timepicker({
            timeFormat,
            step: 15
        }).change((function() {
            var e = $(this).val(),
                t = $("#" + $(this).data().linkCombined);
            if ("" === e) t.val("");
            else {
                e = moment($(this).val(), jsTimeFormat);
                var a = "" === t.val() ? moment() : moment(t.val(), systemDateFormat);
                a.isValid() || (a = moment()), a.hour(e.hour()), a.minute(e.minute()), a.second(e.second()), t.val(a.format(systemDateFormat))
            }
        })), void 0 !== e && "" !== e && $(this).find(".dateTimePickerTime").timepicker("setTime", moment(e, systemDateFormat).toDate())
    })), $(scope + " .datePicker").each((function() {
        $(this).datetimepicker({
            format: bootstrapDateFormatDateOnly,
            autoclose: !0,
            language,
            calendarType,
            minView: 2,
            todayHighlight: !0
        });
        var e = $(this).closest("form").find("#" + $(this).data().linkField).val();
        null != e && "" != e && $(this).datetimepicker("update", e)
    })), $(scope + " .dateMonthPicker").each((function() {
        $(this).datetimepicker({
            format: "MM yyyy",
            autoclose: !0,
            language,
            calendarType,
            startView: 3,
            minView: 3
        });
        var e = $(this).closest("form").find("#" + $(this).data().linkField).val();
        null != e && "" != e && $(this).datetimepicker("update", e)
    })), $(scope + " .timePicker").each((function() {
        $(this).timepicker({
            timeFormat,
            step: 15
        }).change((function() {
            var e = moment($(this).val(), jsTimeFormat);
            $(this).closest("form").find("#" + $(this).data().linkField).val(e.format(systemTimeFormat))
        }));
        var e = $(this).closest("form").find("#" + $(this).data().linkField).val();
        null != e && "" != e && $(this).timepicker("setTime", e)
    })), $(scope + " .selectPicker select.form-control").select2({
        dropdownParent: $(scope).hasClass("modal") ? $(scope) : $("body"),
        templateResult: function(e) {
            if (!e.id) return e.text;
            var t = $(e.element);
            return void 0 !== t.data().content ? $(t.data().content) : e.text
        }
    }), $(scope + " .pagedSelect select.form-control").each((function() {
        let e = $(this).data("anchorElement");
        makePagedSelect($(this), void 0 !== e && "" !== e ? $(e) : $("body"))
    })), $(scope + " .localSelect select.form-control").each((function() {
        makeLocalSelect($(this), $(scope).hasClass("modal") ? $(scope) : $("body"))
    })), $(scope + " span.notification-date").each((function() {
        $(this).html(moment($(this).html(), "X").fromNow())
    })), $(scope + " input.bootstrap-switch-target").each((function() {
        $(this).bootstrapSwitch()
    })), $(scope + " input[data-role=tagsInputInline], " + scope + " input[data-role=tagsInputForm], " + scope + " select[multiple][data-role=tagsInputForm]").tagsinput(), $(scope + " .tags-with-value").each((function() {
        tagsWithValues($(this).closest("form").attr("id"))
    })), $(scope).hasClass("modal") && $(scope).on("hide.bs.modal", (function(e) {
        "bs.modal" === e.namespace && ($(scope).find(".dateTimePicker .dateTimePickerDate, .dateTimePicker, .dateMonthPicker").datetimepicker("remove"), $.each($(scope).find(".dateTimePicker .dateTimePickerTime, .timePicker"), (function(e, t) {
            $(t).timepicker("remove")
        })))
    }))
}

function dataTableProcessing(e, t, a) {
    a ? $(e.target).closest(".widget").children(".widget-title").append(' <span class="saving fa fa-cog fa-spin"></span>') : $(e.target).closest(".widget").closest(".widget").find(".saving").remove()
}

function dataTableDraw(e, t) {
    var a = $("#" + e.target.id),
        o = a.find("ul.dropdown-menu li[data-commit-url]");
    if (o.length > 0) {
        null == a.data().initialised && a.find("tbody").off("click", "tr").on("click", "tr", (function() {
            $(this).toggleClass("selected"), a.data().initialised = !0
        }));
        var n = Handlebars.compile($("#multiselect-button-template").html()),
            i = [];
        $(o).each((function() {
            (function(e, t, a) {
                for (var o in e)
                    if (e[o].id == a) return !0;
                return !1
            })(i, 0, $(this).data("id")) || i.push({
                id: $(this).data("id"),
                gridId: e.target.id,
                text: $(this).data("text")
            })
        }));
        var s = n({
            selectAll: translations.selectAll,
            withSelected: translations.withselected,
            buttons: i
        });
        a.closest(".dataTables_wrapper").find(".dataTables_info").prepend(s), a.closest(".dataTables_wrapper").find(".dataTables_info li.XiboMultiSelectFormButton").click((function() {
            XiboMultiSelectFormRender(this)
        })), a.closest(".dataTables_wrapper").find(".dataTables_info button.select-all").click((function() {
            var e = a.find("tbody tr");
            a.find("tbody tr.selected").length > e.length / 2 ? e.removeClass("selected") : e.addClass("selected")
        }))
    }
    XiboInitialise("#" + e.target.id)
}

function dataTableButtonsColumn(e, t, a, o) {
    return "display" != t ? "" : (null == buttonsTemplate && (buttonsTemplate = Handlebars.compile($("#buttons-template").html())), buttonsTemplate({
        buttons: e.buttons
    }))
}

function dataTableTickCrossColumn(e, t, a) {
    return "display" != t ? e : "<span class='fa " + (1 == e ? "fa-check" : 0 == e ? "fa-times" : "fa-exclamation") + "'></span>"
}

function dataTableTickCrossInverseColumn(e, t, a) {
    return "display" != t ? e : "<span class='fa " + (1 == e ? "fa-times" : 0 == e ? "fa-check" : "fa-exclamation") + "'></span>"
}

function dataTableDateFromIso(e, t, a) {
    return "display" !== t && "export" !== t ? e : null == e ? "" : moment(e, systemDateFormat).format(jsDateFormat)
}

function dataTableDateFromUnix(e, t, a) {
    return "display" !== t && "export" !== t ? e : null == e || 0 == e ? "" : moment(e, "X").tz ? moment(e, "X").tz(timezone).format(jsDateFormat) : moment(e, "X").format(jsDateFormat)
}

function dataTableTimeFromSeconds(e, t, a) {
    if ("display" !== t && "export" !== t) return e;
    if (null == e || 0 == e) return "";
    var o = moment.duration(1e3 * e),
        n = Math.floor(o.asHours());
    return (n < 10 ? "0" + n : n) + moment.utc(o.asMilliseconds()).format(":mm:ss")
}

function dataTableSpacingPreformatted(e, t, a) {
    return "display" !== t ? e : null === e || "" === e ? "" : '<span class="spacing-whitespace-pre">' + e + "</span>"
}

function dataTableCreateTags(e, t) {
    if ("display" !== t) return e.tags;
    var a = "";
    return void 0 !== typeof e.tags && null != e.tags && (e.tags.split(",").forEach((e => a += '<li class="btn btn-sm btn-default btn-tag">' + e + "</span></li>")), a += "</div>"), a
}

function dataTableCreatePermissions(e, t) {
    if ("display" !== t) return e;
    var a = "";
    if (null != typeof e && null != e) {
        var o = e.split(",");
        a += '<div class="permissionsDiv">';
        for (var n = 0; n < o.length; n++) "" != o[n] && (a += '<li class="badge">' + o[n] + "</span></li>");
        a += "</div>"
    }
    return a
}

function dataTableCreateTagEvents(e, t) {
    var a = $("#" + e.target.id),
        o = e.target.id,
        n = e.data.form;
    a.off("click"), a.on("click", ".btn-tag", (function(e) {
        var t = $(this).text();
        n.find("#tags").val(), "playlistLibraryMedia" == o ? (n.find("#filterMediaTag").val(), n.find("#filterMediaTag").tagsinput("add", t, {
            allowDuplicates: !1
        })) : "displayGroupDisplays" == o ? (n.find("#dynamicCriteriaTags").val(), n.find("#dynamicCriteriaTags").tagsinput("add", t, {
            allowDuplicates: !1
        })) : n.find("#tags").tagsinput("add", t, {
            allowDuplicates: !1
        }), a.DataTable().ajax.reload()
    }))
}

function dataTableConfigureRefresh(e, t, a) {
    for (var o = a > 10 ? a : 10, n = gridTimeouts.length - 1; n >= 0; n--) gridTimeouts[n].label === e && (clearTimeout(gridTimeouts[n].timer), gridTimeouts.splice(n, 1));
    gridTimeouts.push({
        label: e,
        timer: setTimeout((function() {
            t.reload()
        }), 1e3 * o)
    })
}

function dataTableAddButtons(e, t, a) {
    (a = void 0 === a || a) ? new $.fn.dataTable.Buttons(e, {
        buttons: [{
            extend: "colvis",
            text: function(e, t, a) {
                return e.i18n("buttons.colvis")
            }
        }, {
            extend: "print",
            text: function(e, t, a) {
                return e.i18n("buttons.print")
            },
            exportOptions: {
                orthogonal: "export",
                format: {
                    body: function(e, t, a, o) {
                        return null === e || "" === e || "null" === e ? "" : e
                    }
                }
            }
        }, {
            extend: "csv",
            exportOptions: {
                orthogonal: "export",
                format: {
                    body: function(e, t, a, o) {
                        return null === e || "" === e ? "" : e
                    }
                }
            }
        }]
    }): new $.fn.dataTable.Buttons(e, {
        buttons: [{
            extend: "colvis",
            text: function(e, t, a) {
                return e.i18n("buttons.colvis")
            }
        }]
    }), e.buttons(0, null).container().prependTo(t), $(".ColVis_MasterButton").addClass("btn")
}

function XiboFormRender(sourceObj, data) {
    var formUrl = "";
    return "string" == typeof sourceObj || sourceObj instanceof String ? formUrl = sourceObj : (formUrl = sourceObj.attr("href"), sourceObj.removeAttr("href")), null == formUrl || (bootbox.hideAll(), (formUrl.indexOf("region/form/timeline") > -1 || formUrl.indexOf("playlist/form/timeline") > -1) && (timelineForm = {
        url: formUrl,
        data
    }), lastForm = formUrl, $.ajax({
        type: "get",
        url: formUrl,
        cache: !1,
        dataType: "json",
        data,
        success: function(response) {
            if (("object" == typeof sourceObj || sourceObj instanceof Object) && sourceObj.attr("href", lastForm), response.success) {
                var dialogTitle = "";
                null != response.dialogTitle && "" != response.dialogTitle && (dialogTitle = response.dialogTitle);
                var id = (new Date).getTime(),
                    dialog = bootbox.dialog({
                        message: response.html,
                        title: dialogTitle,
                        animate: !1
                    }).attr("id", id);
                if (dialog.data("extra", response.extra), "" !== response.buttons) {
                    var footer = $("<div>").addClass("modal-footer");
                    dialog.find(".modal-content").append(footer);
                    var i = 0,
                        count = Object.keys(response.buttons).length;
                    $.each(response.buttons, (function(index, value) {
                        i++;
                        var extrabutton = $('<button id="dialog_btn_' + i + '" class="btn">').html(index);
                        $("button:contains('Help')").hide();
                        i === count ? extrabutton.addClass("btn-primary save-button") : extrabutton.addClass("btn-default"), extrabutton.click((function(e) {
                            e.preventDefault();
                            let $button = $(this);
                            if ($button.hasClass("save-button")) {
                                if ($button.hasClass("disabled")) return !1;
                                $button.append(' <span class="saving fa fa-cog fa-spin"></span>'), $button.addClass("disabled")
                            }
                            return value.indexOf("DialogClose") > -1 && (lastForm.indexOf("playlist/widget/form") > -1 || lastForm.indexOf("playlist/form/library/assign") > -1) && null != timelineForm ? XiboFormRender(timelineForm.url, timelineForm.value) : eval(value), !1
                        })), footer.append(extrabutton)
                    }))
                }
                $("input[type=text]", dialog).not(".dateControl").eq(0).focus(), $("input[type=text]", dialog).each((function(e, t) {
                    formRenderDetectSpacingIssues(t), $(t).on("keyup", _.debounce((function() {
                        formRenderDetectSpacingIssues(t)
                    }), 500))
                })), "" != response.fieldActions && $.each(response.fieldActions, (function(e, t) {
                    if ("init" == t.trigger) {
                        var a = $("#" + t.field).val();
                        ("not" == t.operation ? a != t.value : "is:checked" == t.operation ? t.value == $("#" + t.field).is(":checked") : a == t.value) && $.each(t.actions, (function(e, t) {
                            var a = $(e);
                            a.data("initActioned") || a.css(t).data("initActioned", !0)
                        }))
                    } else $("#" + t.field).on(t.trigger, (function() {
                        var e = $(this).val();
                        ("not" == t.operation ? e != t.value : "is:checked" == t.operation ? t.value == $("#" + t.field).is(":checked") : e == t.value) && $.each(t.actions, (function(e, t) {
                            $(e).css(t)
                        }))
                    }))
                })), $('a[data-toggle="tab"]', dialog).on("shown.bs.tab", (function(e) {
                    1 === $(e.target).data().enlarge ? $(e.target).closest(".modal").addClass("modal-big") : $(e.target).closest(".modal").removeClass("modal-big")
                })), $('a[data-toggle="tab"]', dialog).each((function() {
                    1 === $(this).data().enlarge && $(this).closest("li").hasClass("active") && $(this).closest(".modal").addClass("modal-big")
                })), XiboInitialise("#" + dialog.attr("id")), "" !== response.callBack && void 0 !== response.callBack && eval(response.callBack)(dialog)
            } else {
                if (response.login) return LoginBox(response.message), !1;
                null == response.message ? SystemMessage(response) : SystemMessage(response.message)
            }
            return !1
        },
        error: function(e) {
            SystemMessage(e.responseText)
        }
    })), !1
}

function XiboCustomFormRender(e) {
    var t;
    return t = e.attr("href"), e.removeAttr("href"), null == t || (lastForm = t, $.ajax({
        type: "get",
        url: t,
        cache: !1,
        dataType: "json",
        success: function(t) {
            if (("object" == typeof e || e instanceof Object) && e.attr("href", lastForm), t.success) {
                var a = {
                    id: (new Date).getTime(),
                    buttons: t.buttons,
                    data: t.data,
                    title: t.dialogTitle,
                    message: t.html,
                    extra: t.extra
                };
                "" !== t.callBack && void 0 !== t.callBack && window[t.callBack](a)
            } else {
                if (t.login) return LoginBox(t.message), !1;
                null == t.message ? SystemMessage(t) : SystemMessage(t.message)
            }
            return !1
        },
        error: function(e) {
            SystemMessage(e.responseText)
        }
    })), !1
}

function XiboRemoteRequest(e, t, a) {
    $.ajax({
        type: "post",
        url: e,
        cache: !1,
        dataType: "json",
        data: t,
        success: a,
        error: function(e) {
            SystemMessage(e.responseText)
        }
    })
}

function formRenderDetectSpacingIssues(e) {
    var t = $(e),
        a = t.val();
    if ("" !== a && (a.startsWith(" ") || a.endsWith(" ") || a.indexOf("  ") > -1)) {
        console.log("Field with strange spacing: " + t.attr("name"));
        var o = $("<span></span>").addClass("fa fa-exclamation-circle spacing-warning-icon").attr("title", translations.spacesWarning);
        t.parent().append(o)
    } else t.parent().find(".spacing-warning-icon").remove()
}

function XiboMultiSelectFormRender(button) {
    var buttonId = $(button).data().buttonId,
        matches = [],
        formOpenCallback = null,
        message;
    $("." + buttonId).each((function() {
        $(this).closest("tr").hasClass("selected") && (matches.push($(this)), 1 === matches.length && (formOpenCallback = $(this).data().formCallback, formConfirm = $(this).data().formConfirm))
    })), message = matches.length > 0 ? translations.multiselectMessage.replace("%1", "" + matches.length).replace("%2", $(button).find("a").html()) : translations.multiselectNoItemsMessage;
    var dialog = bootbox.dialog({
            message,
            title: translations.multiselect,
            animate: !1
        }),
        dialogContent = dialog.find(".modal-body"),
        footer = $("<div>").addClass("modal-footer"),
        extrabutton;
    dialog.find(".modal-content").append(footer), null != formOpenCallback && eval(formOpenCallback)(dialog), matches.length > 0 && (extrabutton = $('<button class="btn">').html(translations.save).addClass("btn-primary save-button"), formConfirm && extrabutton.prop("disabled", !0), extrabutton.click((function() {
        return $(this).append(' <span class="saving fa fa-cog fa-spin"></span>'), window.queue = $.jqmq({
            delay: -1,
            batch: 1,
            callback: function(e) {
                var t = $(e).data();
                void 0 !== dialog.data().commitData && (t = $.extend({}, t, dialog.data().commitData)), $.ajax({
                    type: t.commitMethod,
                    url: t.commitUrl,
                    cache: !1,
                    dataType: "json",
                    data: t,
                    success: function(e, a, o) {
                        e.success ? (dialogContent.append($("<div>").html(t.rowtitle + ": " + translations.success)), queue.next()) : e.login ? LoginBox(e.message) : (dialogContent.append($("<div>").html(t.rowtitle + ": " + translations.failure)), footer.find(".saving").remove(), SystemMessageInline(e.message, footer.closest(".modal")))
                    },
                    error: function(e) {
                        SystemMessage(e, !1)
                    }
                })
            },
            complete: function() {
                footer.find(".saving").parent().remove(), XiboRefreshAllGrids()
            }
        }), $(matches).each((function() {
            queue.add(this)
        })), queue.start(), !1
    })), footer.append(extrabutton)), extrabutton = $('<button class="btn">').html(translations.close).addClass("btn-default"), extrabutton.click((function() {
        return $(this).append(' <span class="saving fa fa-cog fa-spin"></span>'), dialog.modal("hide"), $(".modal").hasClass("in") && $("body").addClass("modal-open"), !1
    })), footer.append(extrabutton)
}

function XiboHelpRender(e) {
    window.open(e)
}

function XiboPing(e, t) {
    $.ajax({
        type: "get",
        url: e,
        cache: !1,
        dataType: "json",
        success: function(e) {
            if (e.success) null != t && $(t).html(e.html), e.clockUpdate && XiboClockUpdate(e.html);
            else if (e.login) return LoginBox(e.message), !1;
            return !1
        }
    })
}

function XiboClockUpdate(e) {
    $("#XiboClock").html(e)
}

function XiboFormSubmit(e, t, a) {
    var o = $(e).attr("action");
    for (var n in CKEDITOR.instances) {
        var i = new RegExp(CKEDITOR_DEFAULT_CONFIG.imageDownloadUrl.replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g, "\\$&").replace(":id", "([0-9]+)"), "g"),
            s = CKEDITOR.instances[n].getData().replace(i, (function(e, t) {
                return "[" + t + "]"
            }));
        $("#" + n).val(s)
    }
    return $.ajax({
        type: $(e).attr("method"),
        url: o,
        cache: !1,
        dataType: "json",
        data: $(e).serialize(),
        success: function(t, o, n) {
            XiboSubmitResponse(t, e), null != a && null != a && a(t, e)
        },
        error: function(e, t, a) {
            SystemMessage(e.responseText, !1)
        }
    }), !1
}

function XiboSubmitResponse(response, form) {
    $(form).closest(".modal-dialog").find(".saving").remove();
    var apply = $(form).data("apply");
    return $(form).data("apply", !1), response.success ? ("" != response.message && SystemMessage(response.message, !0), null != apply && apply ? ($(form).data("applyCallback") && eval($(form).data("applyCallback"))(form), $(form).closest(".modal-dialog").find(".form-error").remove(), $("input[type=text]", form).eq(0).focus()) : bootbox.hideAll(), XiboRefreshAllGrids(), apply || (null != $(form).data("nextFormUrl") ? XiboFormRender($(form).data().nextFormUrl.replace(":id", response.id)) : null != lastForm && (lastForm.indexOf("playlist/widget/form") > -1 || lastForm.indexOf("playlist/form/library/assign") > -1) && null != timelineForm && XiboFormRender(timelineForm.url, timelineForm.value))) : response.login ? LoginBox(response.message) : SystemMessageInline(response.message, $(form).closest(".modal")), !1
}

function XiboHoverRender(url, x, y) {
    return $.ajax({
        type: "get",
        url,
        cache: !1,
        dataType: "json",
        success: function(response) {
            if (response.success) {
                var dialogWidth = "500",
                    dialogHeight = "500";
                response.dialogSize && (dialogWidth = response.dialogWidth, dialogHeight = response.dialogHeight), $("body").append('<div class="XiboHover"></div>'), $(".XiboHover").css("position", "absolute").css({
                    display: "none",
                    width: dialogWidth,
                    height: dialogHeight,
                    top: y,
                    left: x
                }).fadeIn("slow").hover((function() {
                    return !1
                }), (function() {
                    return $(".XiboHover").hide().remove(), !1
                })), $(".XiboHover").html(response.html), "" != response.callBack && null != response.callBack && eval(response.callBack)(name), XiboInitialise(".XiboHover")
            } else {
                if (response.login) return LoginBox(response.message), !1;
                null == response.message ? SystemMessage(response) : SystemMessage(response.message)
            }
            return !1
        }
    }), !1
}

function XiboDialogClose() {
    bootbox.hideAll()
}

function XiboDialogApply(e) {
    var t = $(e);
    t.data("apply", !0), t.submit()
}

function XiboSwapDialog(e, t) {
    bootbox.hideAll(), XiboFormRender(e, t)
}

function XiboRefreshAllGrids() {
    $(" .XiboGrid table.dataTable").each((function() {
        $(this).DataTable().ajax.reload(null, !1)
    }))
}

function XiboRedirect(e) {
    window.location.href = e
}

function LoginBox(e) {
    window.location.href = window.location.href, location.reload(!1)
}

function updateUserPref(e) {
    $.ajax({
        type: "post",
        url: userPreferencesUrl,
        cache: !1,
        dataType: "json",
        data: {
            preference: e
        },
        success: function(e) {
            if (e.success) SystemMessage(e.message, !0);
            else {
                if (e.login) return LoginBox(e.message), !1;
                SystemMessage(e.message, e.success)
            }
            return !1
        }
    })
}

function SystemMessage(e, t) {
    if ("" != e && null != e)
        if (t) toastr.success(e);
        else var a = bootbox.dialog({
            message: e,
            title: "Application Message",
            buttons: [{
                label: "Close",
                callback: function() {
                    null != lastForm && lastForm.indexOf("playlist/widget/form") > -1 && null != timelineForm ? XiboFormRender(timelineForm.url, timelineForm.value) : a.modal("hide")
                }
            }],
            animate: !1
        })
}

function SystemMessageInline(e, t) {
    "" != e && null != e && (null != t && null != t && 0 != t.length || (t = $(".modal")), t.length <= 0 ? toastr.error(e) : ($(".form-error", t).remove(), $(t).find(".btn").removeClass("disabled"), $("<div/>", {
        class: "well text-danger text-center form-error",
        html: e
    }).appendTo(t.find(".modal-footer"))))
}

function ToggleFilterView(e) {
    "none" == $(e).css("display") ? $(e).fadeIn("slow") : $(e).fadeOut("slow")
}

function makePagedSelect(e, t) {
    e.select2({
        dropdownParent: null == t ? $("body") : $(t),
        ajax: {
            url: e.data("searchUrl"),
            dataType: "json",
            data: function(t) {
                var a = {
                        start: 0,
                        length: 10
                    },
                    o = t.term;
                if (null != o && null != e.data("searchTermTags")) {
                    var n = o.match(/\[([^}]+)\]/),
                        i = "";
                    null != n && (i = n[1], o = o.replace(n[0], ""), a[e.data("searchTermTags")] = i)
                }
                return a[e.data("searchTerm")] = o, void 0 !== e.data("filterOptions") && (a = $.extend({}, a, e.data("filterOptions"))), null != t.page && (a.start = 10 * (t.page - 1)), a
            },
            processResults: function(t, a) {
                var o = [],
                    n = e;
                $.each(t.data, (function(e, t) {
                    o.push({
                        id: t[n.data("idProperty")],
                        text: t[n.data("textProperty")]
                    })
                }));
                var i = a.page || 1;
                return {
                    results: o,
                    pagination: {
                        more: 10 * (i = i > 1 ? i - 1 : i) < t.recordsTotal
                    }
                }
            }
        }
    })
}

function makeLocalSelect(e, t) {
    e.select2({
        dropdownParent: null == t ? $("body") : $(t),
        matcher: function(e, t) {
            if ("" === $.trim(e.term)) return t;
            var a = e.term.match(/\[([^}]+)\]/),
                o = e.term,
                n = "";
            null != a && (n = a[1], o = e.term.replace(a[0], "")), o = o.replace(" ", "").split(","), n = n.replace(" ", "").split(",");
            for (let e = 0; e < o.length; e++) {
                const a = o[e];
                if ("" != a && t.text.indexOf(a) > -1) return t
            }
            for (let e = 0; e < n.length; e++) {
                const a = n[e];
                if ("" != a && null != $(t.element).data("tags") && $(t.element).data("tags").indexOf(a) > -1) return t
            }
            return null
        }
    })
}

function userPreferencesFormSubmit() {
    let e = $("#userPreferences");
    e.find('input[type="checkbox"]').each((function() {
        let e = $(this).is(":checked") ? "on" : "off",
            t = $(this).attr("id");
        $('<input type="hidden">').attr("id", t).attr("name", t).val(e).appendTo($(this).parent()), $(this).attr("disabled", !0)
    })), e.submit()
}
String.prototype.startsWith || (String.prototype.startsWith = function(e, t) {
    return t = t || 0, this.indexOf(e, t) === t
}), String.prototype.endsWith || (String.prototype.endsWith = function(e) {
    return -1 !== this.indexOf(e, this.length - e.length)
}), $(document).delegate('*[data-toggle="lightbox"]', "click", (function(e) {
    e.preventDefault(), $(this).ekkoLightbox()
})), $(document).ready((function() {
    buttonsTemplate = null, window.console || function() {
        var e, t = ["log", "debug", "info", "warn", "error", "assert", "dir", "dirxml", "group", "groupEnd", "time", "timeEnd", "count", "trace", "profile", "profileEnd"],
            a = t.length;
        for (window.console = {}, e = 0; e < a; e++) window.console[t[e]] = function() {}
    }(), setInterval("XiboPing('" + clockUrl + "')", 6e4), setInterval("XiboPing('" + pingUrl + "')", 18e4), XiboInitialise("")
}));