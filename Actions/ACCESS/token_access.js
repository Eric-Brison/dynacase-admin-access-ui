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
        "drawCallback": function (settings, json)
        {
            console.log("init", arguments);
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
            $('.token-id', row).html($("<input/>").attr("readonly","readonly").val(data.token));
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
    $(".token-add").button({
        "icon":"ui-icon-circle-plus"
    });

    $token.on("click", ".token-delete", function ()
    {
        var $dialog = $("#token-deletion-confirm");
        var $tr=$(this).closest("tr");
        var token=$tr.find(".token-id input").val();

        $tr.addClass("token-to-delete");
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
                        var url="?app=ACCESS&action=TOKEN_METHOD&method=delete&token="+token
                        $.getJSON(url).done(function (data) {

                            tokenTable.draw();
                            if (data && data.message) {
                                $('<div/>').html(data.message).dialog({
                                    open: function( event, ui ) {
                                        $(event.target).parent().find(".ui-dialog-titlebar").hide();
                                        window.setTimeout(function () {
                                            $(event.target).dialog("destroy");
                                        }, 2000);
                                    }
                                });
                            }

                        }).fail(function (response) {
                            $('<div/>').html(response.responseText).dialog();
                        });
                        $(this).dialog("close");
                    }
                }
            ]

        });
    });
});