$(document).ready(function() {

    $('.datepicker').datepicker({
        startDate: 'now',
        language: 'es',
        format: 'dd/mm/yyyy',
        daysOfWeekDisabled: '0'
    });

    $('.timepicker').timepicker({
        showInputs: false,
        showMeridian: false,
        defaultTime: 'current'
    });

    $('#quantity').on('input',function(e){

        $('#estimated_value').val(
        $('#quantity').val()*$('#price_unit').val());

    });

    $('#price_unit').on('input',function(e){
        $('#estimated_value').val(
            $('#quantity').val()*$('#price_unit').val());
    });

    $('#price_unit_attention').on('input',function(e){
        $('#estimated_value_attention').val(
            $('#quantity_attention').val()*$('#price_unit_attention').val());
    });

    $('#quantity_attention').on('input',function(e){

        $('#estimated_value_attention').val(
            $('#quantity_attention').val()*$('#price_unit_attention').val());

    });




    $('#eliminar_gasto').on('click',function(e){
        bootbox.confirm("Estas seguro?", function (result) {

            if (result) {
                $.ajax({
                    type: "DELETE",
                    url: "/api/expenses/"+expense_id,
                    error: function (result) {
                        console.log(result.responseText);
                    },
                    success: function(data) {
                        console.log(data);

                        toastr.success('Se Elimino correctamente!', '');

                        setTimeout(function(){
                               window.location.href = '/frontend/gastos';
                        }, 1000);

                        return false;
                    }
                });
            }
            });
    });

});


$('#edit_expense').validator().on('submit', function (e) {
    if (e.isDefaultPrevented()) {
    } else {
        var data_form = $('#edit_expense').serialize();
        console.log(data_form);

        $.ajax({
            type: "PUT",
            url: "/api/expenses/"+expense_id,
            data: data_form,
            error: function (result) {
                console.log(result.responseText);
            },
            success: function(data) {
                console.log(data);

                $('#marketing-modal').modal('hide');

                toastr.success('Se modifico correctamente!', '');

                setTimeout(function(){
                    window.location.href = '/frontend/detalle/'+expense_id;
                }, 1000);

                return false;
            }
        });
    }

    return false;
});


$('#form_note').validator().on('submit', function (e) {
    if (e.isDefaultPrevented()) {
        // handle the invalid form...
    } else {
        var data_form = $('#form_note').serialize();
        console.log(data_form);

        $.ajax({
            type: "POST",
            url: "/api/buy_orders",
            data: data_form,
            success: function(data) {
                 console.log(data);

                $('#note-modal').modal('hide');

                toastr.success('Se agrego el detalle correctamente!', '');

                setTimeout(function(){
                   // window.location.href = '/frontend/detalle/'+expense_id;
                }, 1000);

                return false;
            }
        });
    }

    return false;
});

$('#form_attention').validator().on('submit', function (e) {
    if (e.isDefaultPrevented()) {
        // handle the invalid form...
    } else {
        var data_form = $('#form_attention').serialize();
        console.log(data_form);

        $.ajax({
            type: "POST",
            url: "/api/request_attentions",
            data: data_form,
            success: function(data) {
                console.log(data);

                $('#attention-modal').modal('hide');

                toastr.success('Se agrego el detalle correctamente!', '');

                setTimeout(function(){
                    window.location.href = '/frontend/detalle/'+expense_id;
                }, 1000);

                return false;
            }
        });
    }

    return false;
});




$('#form_entertainment').validator().on('submit', function (e) {
    if (e.isDefaultPrevented()) {
        // handle the invalid form...
    } else {
        var data_form = $('#form_entertainment').serialize();
        console.log(data_form);

        $.ajax({
            type: "POST",
            url: "/api/entertainments",
            data: data_form,
            success: function(data) {
                console.log(data);

                $('#entertainment-modal').modal('hide');

                toastr.success('Se agrego el detalle correctamente!', '');

                setTimeout(function(){
                    window.location.href = '/frontend/detalle/'+expense_id;
                }, 1000);

                return false;
            }
        });
    }

    return false;
});



$('#form_campaign').validator().on('submit', function (e) {
    if (e.isDefaultPrevented()) {
        // handle the invalid form...
    } else {
        var data_form = $('#form_campaign').serialize();
        console.log(data_form);

        $.ajax({
            type: "POST",
            url: "/api/medical_campaigns",
            data: data_form,
            success: function(data) {
                console.log(data);

                $('#campaign-modal').modal('hide');

                toastr.success('Se agrego el detalle correctamente!', '');

                setTimeout(function(){
                    window.location.href = '/frontend/detalle/'+expense_id;
                }, 1000);

                return false;
            }
        });
    }

    return false;
});




function loadNotes(page) {
    $.ajax({
        type: "GET",
        url: "/api/buy_orders?expense_id=" + expense_id +"&page=" + page,
        contentType: "application/json; charset=utf-8",
        data: "{}",
        dataType: "json",
        success: function(result) {
            displayNotePagination(result);
            displayAllNotes(result.data);
        },
        "error": function(result) {
            console.log(result.responseText);
        }
    });
}

function loadEntertainments(page) {
    $.ajax({
        type: "GET",
        url: "/api/entertainments?expense_id=" + expense_id +"&page=" + page,
        contentType: "application/json; charset=utf-8",
        data: "{}",
        dataType: "json",
        success: function(result) {
            displayNotePagination(result);
            displayAllNotes(result.data);
        },
        "error": function(result) {
            console.log(result.responseText);
        }
    });
}


function loadCampaigns(page) {
    $.ajax({
        type: "GET",
        url: "/api/medical_campaigns?expense_id=" + expense_id +"&page=" + page,
        contentType: "application/json; charset=utf-8",
        data: "{}",
        dataType: "json",
        success: function(result) {
            displayNotePagination(result);
            displayAllNotes(result.data);
        },
        "error": function(result) {
            console.log(result.responseText);
        }
    });
}



function loadAttentions(page) {
    $.ajax({
        type: "GET",
        url: "/api/request_attentions?expense_id=" + expense_id +"&page=" + page,
        contentType: "application/json; charset=utf-8",
        data: "{}",
        dataType: "json",
        success: function(result) {
            displayNotePagination(result);
            displayAllNotes(result.data);
        },
        "error": function(result) {
            console.log(result.responseText);
        }
    });
}



function displayNotePagination(result) {

    $('#note_pagination').empty();

    for (var i = 0; i < result.last_page; i++) {
        var itemHtml = ["<li>",
            "<a href='#' onClick='loadNotes(" + (i + 1) + ");return false;' >" + (i + 1) + "</a>",
            "</li>"
        ].join("\n");

        $("#note_pagination").append(itemHtml);

    }
}

function displayAllNotes(list) {

    $('#list_note').empty();
    $.each(list, function(index, element) {
        displayNote(index, element);
    });
}

function displayNote(index, element) {
    //console.log(element);
    console.log(element.expense.expense_type_id);
    var itemHtml = null;

        var formatArrayA = [];
        formatArrayA.push('1');
        formatArrayA.push('2');
        formatArrayA.push('3');
        formatArrayA.push('4');
    if(($.inArray(element.expense.expense_type_id, formatArrayA) > 0))
    {
        console.log('formato 1,2,3,4');
        itemHtml = ["<tr>",
            //  "<i class='fa fa-fw fa-file-text-o'></i>",
            "<td>",
            index+1,
            "</td>",
            "<td>",
            element.code,
            "</td>",
            "<td>",
            element.cost_center,
            "</td>",
            "<td>",
            element.book_account,
            "</td>",
            "<td>",
            element.active,
            "</td>",
            "<td>",
            element.expenditure,
            "</td>",
            "<td>",
            element.inventory,
            "</td>",
            "<td>",
            element.quantity,
            "</td>",
            "<td>",
            element.price_unit,
            "</td>",
            "<td>",
            element.description,
            "</td>",
            "<td>",
            element.estimated_value,
            "</td>",
            "<td>",
            element.destination,
            "</td>",
            "<td>",
            moment(element.delivery_date, 'YYYY-MM-DD').format('DD-MM-YY'),
            "</td>",
            "<td>",
            "<div class='tools'>",
            //  "<i class='fa fa-edit'></i>",
            "<i class='fa fa-trash-o' onClick='deleteNote(\"" + element.id + "\")'></i>",
            "</div>",
            "</td>",
            "</tr>"
        ].join("\n");

    }

    var formatArrayB = [];
        formatArrayB.push('5');
        formatArrayB.push('6');
        formatArrayB.push('8');
      //  console.log(formatArrayB);
    if(($.inArray(element.expense.expense_type_id,formatArrayB) > 0))
    {
        console.log('formato 5 y 6');

        var cost_unit = element.estimated_value*1.0/element.qty_doctors;
        var max_value = null;

        switch(element.entertainment_type)
        {
            case 'Refrigerio':
                max_value= 25;break;
            case 'Desayuno':
                max_value= 40;break;
            case 'Almuerzo':
                max_value= 100;break;
            case 'Cena':
                max_value= 150;break;
            default :
                max_value= 150;break;
        }
        itemHtml = ["<tr>",
            //  "<i class='fa fa-fw fa-file-text-o'></i>",
            "<td>",
            index+1,
            "</td>",
            "<td>",
            element.consultor,
            "</td>",
            "<td>",
            element.entertainment_type,
            "</td>",
            "<td>",
            moment(element.delivery_date, 'YYYY-MM-DD').format('DD-MM-YY'),
            "</td>",
            "<td>",
            element.place,
            "</td>",
            "<td>",
            element.qty_doctors,
            "</td>",
            "<td>",
            element.estimated_value,
            "</td>",
            "<td>",
            cost_unit.toFixed(2),
            "</td>",
            "<td>",
            max_value,
            "</td>",
            "<td>",
            (cost_unit > max_value)?'MAL':'OK',
            "</td>",
            "<td>",
            "<div class='tools'>",
            //  "<i class='fa fa-edit'></i>",
            "<i class='fa fa-trash-o' onClick='deleteEntertainment(\"" + element.id + "\")'></i>",
            "</div>",
            "</td>",
            "</tr>"
        ].join("\n");
    }

    var formatArrayC = [];
        formatArrayC.push('7');
        formatArrayC.push('9');
        formatArrayC.push('15');

    if(($.inArray(element.expense.expense_type_id, formatArrayC) > 0))
    {
        console.log('formato 7,9,15');

        itemHtml = ["<tr>",
            //  "<i class='fa fa-fw fa-file-text-o'></i>",
            "<td>",
            index+1,
            "</td>",
            "<td>",
            element.consultor,
            "</td>",
            "<td>",
            moment(element.delivery_date, 'YYYY-MM-DD').format('DD-MM-YY'),
            "</td>",
            "<td>",
            element.place,
            "</td>",
            "<td>",
            element.cmp,
            "</td>",
            "<td>",
            element.doctor,
            "</td>",
            "<td>",
            element.estimated_value,
            "</td>",
            "<td>",
            "<div class='tools'>",
            //  "<i class='fa fa-edit'></i>",
            "<i class='fa fa-trash-o' onClick='deleteCampaigns(\"" + element.id + "\")'></i>",
            "</div>",
            "</td>",
            "</tr>"
        ].join("\n");
    }

        var formatArrayD = [];
        formatArrayD.push('16');
        formatArrayD.push('17');

    if(($.inArray(element.expense.expense_type_id, formatArrayD) > 0))
    {
        console.log('formato 16 y 17');

        itemHtml = ["<tr>",
            //  "<i class='fa fa-fw fa-file-text-o'></i>",
            "<td>",
            index+1,
            "</td>",
            "<td>",
            element.promotora,
            "</td>",
            "<td>",
            element.client_code,
            "</td>",
            "<td>",
            element.client,
            "</td>",
            "<td>",
            element.description,
            "</td>",
            "<td>",
            element.price_unit,
            "</td>",
            "<td>",
            element.quantity,
            "</td>",
            "<td>",
            element.estimated_value,
            "</td>",
            "<td>",
            element.reason,
            "</td>",
            "<td>",
            "<div class='tools'>",
            //  "<i class='fa fa-edit'></i>",
            "<i class='fa fa-trash-o' onClick='deleteAttention(\"" + element.id + "\")'></i>",
            "</div>",
            "</td>",
            "</tr>"
        ].join("\n");
    }



    $("#list_note").append(itemHtml);
}


function deleteNote(id) {

    bootbox.confirm("Estas seguro?", function (result) {

        if (result) {
            $.ajax({
                type: "DELETE",
                url: "/api/buy_orders/" + id+"?_token="+$('#token').val(),
                contentType: "application/json; charset=utf-8",
                dataType: "json",
                success: function (result) {
                    console.log("ok");
                    setTimeout(function(){
                        window.location.href = '/frontend/detalle/'+expense_id;
                    }, 1000);
                    return false;

                },
                "error": function (result) {
                    console.log("fail");
                }
            });
        }
    });
}




function deleteEntertainment(id) {

    bootbox.confirm("Estas seguro?", function (result) {

        if (result) {
            $.ajax({
                type: "DELETE",
                url: "/api/entertainments/" + id+"?_token="+$('#token').val(),
                contentType: "application/json; charset=utf-8",
                dataType: "json",
                success: function (result) {
                    console.log("ok");
                    setTimeout(function(){
                        window.location.href = '/frontend/detalle/'+expense_id;
                    }, 1000);

                    return false;

                },
                "error": function (result) {
                    console.log("fail");
                }
            });
        }
    });
}


function deleteCampaigns(id) {

    bootbox.confirm("Estas seguro?", function (result) {

        if (result) {
            $.ajax({
                type: "DELETE",
                url: "/api/medical_campaigns/" + id+"?_token="+$('#token').val(),
                contentType: "application/json; charset=utf-8",
                dataType: "json",
                success: function (result) {
                    console.log("ok");
                    setTimeout(function(){
                        window.location.href = '/frontend/detalle/'+expense_id;
                    }, 1000);

                    return false;

                },
                "error": function (result) {
                    console.log("fail");
                }
            });
        }
    });
}

function deleteAttention(id) {

    bootbox.confirm("Estas seguro?", function (result) {

        if (result) {
            $.ajax({
                type: "DELETE",
                url: "/api/request_attentions/" + id+"?_token="+$('#token').val(),
                contentType: "application/json; charset=utf-8",
                dataType: "json",
                success: function (result) {
                    console.log("ok");
                    setTimeout(function(){
                        window.location.href = '/frontend/detalle/'+expense_id;
                    }, 1000);

                    return false;

                },
                "error": function (result) {
                    console.log("fail detele");
                }
            });
        }
    });
}

function deleteExpenseAmount(expense_amount_id,expense_id){

    bootbox.confirm("Estas seguro?", function (result) {

        if (result) {
            $.ajax({
                type: "GET",
                url: "/frontend/cost_center/delete/" + expense_amount_id,
                contentType: "application/json; charset=utf-8",
                dataType: "json",
                success: function (result) {
                    console.log("ok");
                    setTimeout(function(){
                        window.location.href = '/frontend/detalle/'+expense_id;
                    }, 1000);

                    return false;

                },
                "error": function (result) {
                    console.log("fail detele");
                }
            });
        }
    });

}
