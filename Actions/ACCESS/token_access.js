// Token Access
$(document).ready(function ()
{
    "use strict";

    var $token = $('.token');

    // Setup - add a text input to each footer cell
    $('.token thead th').each(function ()
    {
        var title = $(this).text();
        if (title) {
            $(this).html('<input type="text" placeholder="' + title + '" />');
        }
    });

    $(".token-view-createform").button({
        "icon": "ui-icon-circle-plus"
    }).on("click", function ()
    {
        $(this).hide();
        $(".token-form-add").show("slow");
    });

    $(".token-add-key").button({
        "icon": "ui-icon-plus"
    }).on("click", function ()
    {
        var $tr = $(this).closest("table").find("tfoot tr");
        var $tbody = $(this).closest("table").find("tbody");

        $tbody.append($tr.clone(true));

    });

    $token.dataTable({

        "dom": 'i<"ui-state-default attributesHeader"p>er',
        "paging": true,
        "ordering": false,
        "autoWidth": false,
        "heigth": "200px",
        "language": {
            "search": ""
        },
        processing: true,
        serverSide: true,
        ajax: {
            url: "?app=ACCESS&action=TOKEN_DATA",
            type: "POST"
        },
        columns: [
            { data: 'button', "class": "token-button" },
            { data: 'token', "class": "token-id" },
            { data: 'user', "class": "token-user" },
            { data: 'expire', "class": "token-expire" },
            { data: 'expendable', "class": "token-expendable" },
            { data: 'context', "class": "token-context" }
        ],
        "drawCallback": function (settings)
        {
            // Add delete button
            $(".token-button a", settings.nTBody).button({
                icon: "ui-icon-trash",
                "classes": { "ui-button": "token-delete" }
            });
        },
        "rowCallback": function (row, data)
        {
            if (data.hasExpired) {
                $('.token-expire', row).addClass("token-expired");
            }
            $('.token-id', row).html($("<input/>").attr("readonly", "readonly").attr("size", "40").val(data.token));
            if (data.token === $token.data("addedToken")) {
                $(row).addClass("token-added");
            }
        }
    });
    var tokenTable = $token.DataTable();
    // Apply the search


    // Apply the search
    tokenTable.columns().eq(0).each(function (colIdx)
    {
        $('input', tokenTable.column(colIdx).header()).on('keypress', function (e)
        {
            if (e.keyCode === 13) {
                tokenTable
                    .column(colIdx)
                    .search(this.value)
                    .draw();
            }
        });
    });
    $(".token th input").button();
    $(".token-form-add input, .token-form-add button").button();
    $(".token-add").button({
        "icon": "ui-icon-circle-plus"
    }).button("disable").on("click", function ()
    {
        $(".token-form-add").submit();
    });

    $token.on("click", ".token-delete", function ()
    {
        var $dialog = $("#token-deletion-confirm");
        var $tr = $(this).closest("tr");
        var token = $tr.find(".token-id input").val();

        $tr.addClass("token-to-delete").removeClass("token-added");
        $dialog.attr("title", token);

        $dialog.dialog({
            resizable: false,
            height: "auto",
            width: 400,
            modal: true,
            buttons: [
                {
                    text: $dialog.data("close"),
                    icon: "ui-icon-close",
                    "click": function ()
                    {
                        $tr.removeClass("token-to-delete");
                        $(this).dialog("destroy");
                    }
                },
                {
                    text: $dialog.data("confirm"),

                    icon: "ui-icon-trash",
                    "click": function ()
                    {
                        var url = "?app=ACCESS&action=TOKEN_METHOD&method=delete&token=" + token;
                        $.getJSON(url).done(function (data)
                        {

                            tokenTable.draw();
                            if (data && data.message) {
                                $('<div/>').html(data.message).dialog({
                                    "open": function (event, ui)
                                    {
                                        $(event.target).parent().find(".ui-dialog-titlebar").hide();
                                        window.setTimeout(function ()
                                        {
                                            $(event.target).dialog("destroy");
                                        }, 2000);
                                    }
                                });
                            }

                        }).fail(function (response)
                        {
                            var $div = $('<div/>').html(response.responseText);
                            $div.find("link").remove();
                            $div.dialog();
                        });
                        $(this).dialog("close");
                    }
                }
            ]

        });
    });

    $(".token-form-add").hide().on("submit", function (event)
    {
        event.preventDefault();
        $.ajax(
            {
                type: "POST",
                dataType: "json",
                data: $(this).serialize(),
                url: $(this).attr("action")
            }).done(function (data)
        {
            tokenTable.draw();
            if (data && data.message) {
                $(".lastToken--id").text(data.token);
                $(".lastToken").show();
                $token.data("addedToken", data.token);
                $('<div/>').html(data.message).dialog({
                    "open": function (event, ui)
                    {
                        $(event.target).parent().find(".ui-dialog-titlebar").hide();
                        window.setTimeout(function ()
                        {
                            $(event.target).dialog("destroy");
                        }, 3000);
                    }
                });
            }
        }).fail(function (response)
        {
            var $div = $('<div/>');
            try {
                var jsonResponse = JSON.parse(response.responseText);
                if (jsonResponse.error) {
                    $div.text(jsonResponse.error);
                }
            } catch (e) {

                $div.html(response.responseText);
                $div.find("link").remove();
            }
            $div.dialog();
        });
    });


    $("#iuser").on("change", function ()
    {
        if ($(this).val()) {
            $(".token-add").button("enable");
        } else {
            $(".token-add").button("disable");
        }
    });

    $(".lastToken").hide();

    $(".token-infinity").on("click", function (event)
    {
        var $input = $("input[name=expireinfinite]");
        var value = $input.val();
        event.preventDefault();

        if (value === "false") {
            $input.val("true");
            $(this).addClass("token-infinity--selected");
            $("input[name=expiredate], input[name=expiretime]").button("disable");
        } else {
            $input.val("false");
            $(this).removeClass("token-infinity--selected");
            $("input[name=expiredate], input[name=expiretime]").button("enable");

        }
    });

    $("input[type=date]").each(function ()
    {
        if (!this.valueAsDate) {
            var datepickerFr = {
                closeText: "Fermer",
                prevText: "Précédent",
                nextText: "Suivant",
                currentText: "Aujourd'hui",
                monthNames: ["janvier", "février", "mars", "avril", "mai", "juin",
                    "juillet", "août", "septembre", "octobre", "novembre", "décembre"],
                monthNamesShort: ["janv.", "févr.", "mars", "avr.", "mai", "juin",
                    "juil.", "août", "sept.", "oct.", "nov.", "déc."],
                dayNames: ["dimanche", "lundi", "mardi", "mercredi", "jeudi", "vendredi", "samedi"],
                dayNamesShort: ["dim.", "lun.", "mar.", "mer.", "jeu.", "ven.", "sam."],
                dayNamesMin: ["D", "L", "M", "M", "J", "V", "S"],
                weekHeader: "Sem.",
                dateFormat: "yy-mm-dd",
                firstDay: 1,
                isRTL: false,
                showMonthAfterYear: false,
                yearSuffix: ""
            };

            if ($(".token-form-add").attr("lang") === "fr") {
                $(this).datepicker(datepickerFr);
            } else {
                $(this).datepicker({
                    dateFormat: "yy-mm-dd"
                });
            }
        }
    });
    $("input[type=time]").each(function ()
    {
        if (!this.valueAsDate) {
            $(this).attr("maxlength", 5);
        }
    });
});