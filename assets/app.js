/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';
import './datatable';
import {Tooltip,Toast,Popover} from 'bootstrap';

// start the Stimulus application
import './bootstrap';
// import Modal from 'bootstrap/js/src/modal';

// require('bootstrap');
// import 'datatables.net';
// import 'datatables.net-bs4';


// $("#datatable").DataTable();
  // require jQuery normally
  // const $ = require('jquery');
//   import $ from 'jquery';
//   window.$ =$ ;
// create global $ and jQuery variables

import jquery from 'jquery';
import 'jquery-ui';
// import autocomplete from 'jquery-ui';
// import 'autocomplete';
// import jquery-ui from 'jquery-ui';
global.$ = global.jQuery = $;
require('jquery-ui/ui/widgets/datepicker.js');
require('daterangepicker/daterangepicker.js');
require('daterangepicker/daterangepicker.css');
require('jquery-validation/dist/jquery.validate.min.js');
require('jquery-validation/dist/additional-methods.js');
require('jquery-ui/ui/widgets/autocomplete.js');
require('sweetalert/dist/sweetalert.min.js');
require('@fortawesome/fontawesome-free/css/all.min.css');
require('@fortawesome/fontawesome-free/js/all.js');


// require('bootstrap-datepicker/js/bootstrap-datepicker');
// require('bootstrap-datepicker/js/locales/bootstrap-datepicker.fr');
// require('bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css');

// import * as $ from 'jquery';
var arr_of_ids = [];
$(document).ready(function () {
    var arr_of_ids = [];
    $(document).on('click','.my_modal',function(e){
        
        // e.preventDefault();
        var container = $('#create_modal');
    var path=$(this).attr("data-href");
        $.ajax({
     url : path,
    //  data: {val:2},//tes données à envoyer
    // dataType: 'json',
    //  type : POST,
     success : function(data){
        
     
         $('#create_modal').html(data); // Utiliser un id serait plus précis        
$('#create_modal').modal("show");
}
     });
     $('#create_modal').html("");
    });
    
    $('.js-datetimePicker').datepicker({
        format: 'yyyy-mm-dd'
    });
    // $('.input-daterange input').each(function () {
    //     $(this).datepicker({
    //         format: 'dd/mm/YYYY'
    //     });
    // });

    // $('#search_medicament').click( function (){alert('hello')});

   
    //search medicament
    
    // var path = "{{ path('app_list_medicaments') }}";
// var path= '{{ path('user_enable_change', {'id': user.id}) }}';
    if ($('#search_medicament').length > 0) {
        //Add Parcel
        $('#search_medicament')
            .autocomplete({
                source: function (request, response) {
                    $.getJSON(
                        '/list-medicaments',
                        {
                            // customer_id: $('#selected_customer').val(),
                            term: request.term
                        },
                        response
                        
                    );
                    // var path= "{{ path('app_list_medicaments', { term: request.term}) }}";
                    // $.ajax({
                    //     type: 'GET',
                    //     // cache: false,
                    //     url: path,
                    //     async: true,
                    //     // url: '/sell/list-medicaments',
                    //     data: {},
                    //     success: function (response) {
                    //     }
                    // });
                },
                minLength: 2,
                response: function (event, ui) {
                    console.log('response');
                    if (ui.content.length == 0) {
                        swal('no_products_found');
                    }
                
                },
                select: function (event, ui) {
                transaction_parcel_row(ui.item.medicament_id);
                },
            })
            .autocomplete('instance')._renderItem = function (ul, item) {
                console.log(item);
                var medicament_id = parseInt(item.medicament_id);
                var disable_id=medicament_id;
                var disabled_id=parseInt(disable_id);
                // var product = item.products;
                var name = item.name != null ? item.name : '';
               
                var string = '<div>' + name + '</div>';
                // string += ' (' + sku + ')' + '<br> ' + price + '</div>';
                if (arr_of_ids.includes(disabled_id)) {
                    return $('<li class="ui-state-disabled">')
                        .append(string)
                        .appendTo(ul);
                } else {
                    return $('<li>')
                        .append(string)
                        .appendTo(ul);
                }
            }
           
    }

    function transaction_parcel_row(medicament_id) {
        var row_index = parseInt($('#product_row_index').val());
        var count = parseInt($('#count').val());
        var is_edit=parseInt($('#edit_dropshipping_lines').val());

        $.ajax({
            method: 'GET',
            // url: '/dropshipping/get-product-row',
            url: '/get-product-row',
            data: {row_index: row_index, medicament_id: medicament_id, is_edit: is_edit, count: count},
            dataType: 'html',
            success: function (result) {
                // console.log("success");
                $('table#stpl_table tbody').append(result);
                update_table_total();
                update_status_payment();
                update_price();
               disable_selected();
               
    
                $('#product_row_index').val(row_index + 1);
            }
        });
    }

    update_price();

    function update_price(){
        $('table#stpl_table tbody').find('.product_row').each(function(e){
            var tr=$(this);
            
       var qty_carton=parseFloat(tr.find('.spl_qty_carton').val());
       var qty_boite=parseFloat(tr.find('.spl_qty_boite').val());
       var qty_plaquette=parseFloat(tr.find('.spl_qty_plaquette').val());

       var price_carton=parseFloat(tr.find('.product_price_carton').val());
       var price_boite=parseFloat(tr.find('.product_price_boite').val());
       var price_plaquette=parseFloat(tr.find('.product_price_plaquette').val());

    if(tr.find('.spl_qty_carton').val()==''){
        var qty_carton=parseFloat(0);
        tr.find('.spl_qty_carton').val(qty_carton);
    }
    if(tr.find('.spl_qty_boite').val()==''){
        var qty_boite=parseFloat(0);
        tr.find('.spl_qty_boite').val(qty_boite);
    }
    if(tr.find('.spl_qty_plaquette').val()==''){
        var qty_plaquette=parseFloat(0);
        tr.find('.spl_qty_plaquette').val(qty_plaquette);
    }


    if(tr.find('.product_price_carton').val()==''){
        var price_carton=parseFloat(0.00);
        tr.find('.product_price_carton').val(price_carton);
    }
    if(tr.find('.product_price_boite').val()==''){
        var price_boite=parseFloat(0.00);
        tr.find('.product_price_boite').val(price_boite);
    }
    if(tr.find('.product_price_plaquette').val()==''){
        var price_plaquette=parseFloat(0.00);
        tr.find('.product_price_plaquette').val(price_plaquette);
    }
 
        //   var the_total=$(this).find('input.product_price_total').val();
        // var this_total = parseFloat(the_total);
        var carton=qty_carton*price_carton;
        var boite=qty_boite*price_boite
        var plaquette=qty_plaquette*price_plaquette;
       var price_total=(carton + boite + plaquette);
       var total=price_total.toFixed(2);
    //    .toFixed(2);
       tr.find('.product_price_total').val(total);
     
        });
    }

    $(document).on('change','.input_number',function(){
    //    var tr= $(this).closest('tr');
    update_price();
    update_table_total();
      
    update_status_payment();
  
    });


    disable_selected();

    function disable_selected(){
        $('table#stpl_table tbody tr').each(function (e) {
            var product_id = $(this).find('input.product_id').val();
            // var variation_id = __read_number($(this).find('input.variation_id'));
            // var disable_id=''+product_id+variation_id;
            var disabled_id=parseInt(product_id)
            if (disabled_id === undefined || arr_of_ids.indexOf(disabled_id) !== -1) {
                return;
            }
            arr_of_ids.push(disabled_id);
            // var tr_obj = $(this);
            // final_total(tr_obj);
       
        });
        
        
    }


    update_table_total();
    function update_table_total() {
    var table_total = 0;
    $('table#stpl_table tbody tr').each(function () {
        var the_total=$(this).find('input.product_price_total').val();
        var this_total = parseFloat(the_total);
        // console.log(this_total.toFixed(2));
        // var the_total= this_total.toFixed(2);
        if (this_total) {
            table_total += this_total;
        }
    });
    $('input#total_amount').val(table_total.toFixed(2));
    $('span#total_amount_transaction').text(table_total.toFixed(2));
    $('payment_amount').val(table_total);
    update_status_payment();
  
}


// var transaction_create = $('form#transaction_create');
// //    var transaction_form_validator = transaction_create.validate();
//     //  transaction_form_edit = $('form#edit_dropshipping_form');
//     // transaction_form_validator_edit = transaction_form_edit.validate();

//     $(document).on('click','button#submit_transaction',function(e) {
//     e.preventDefault();

//         //Check if parcel is present or not.
//         if ($('table#stpl_table tbody').find('.product_row').length <= 0) {
//             // toastr.warning(LANG.no_products_added);
//             swal('no_products_added');
//             return false;
//         }

//         if (transaction_form.valid()) {
//             window.onbeforeunload = null;
//             $(this).attr('disabled', true);
//             transaction_create.submit();
//         }
//     });

    var transaction_create = $('form#transaction_create');
     $('form#transaction_create')
        .submit(function (e) {
            // e.preventDefault();

        // if (transaction_create.valid()) {
            // window.onbeforeunload = null;
            // $(this).attr('disabled', true);
            // // $(this).attr('disabled', true);
            // $(".status_payment").attr('disabled', false);
            // transaction_create.submit();
        // }
         
        })  
             .validate({
                rules: {
                    // client: {
                    //     required: true
                    // },
                    status: {
                        required: true
                    }
                
                },
                messages: {
                    // client: {
                    //     remote: 'required',
                    // },
                    status: {
                        // remote: LANG.required,
                         remote: 'required',
                    }
                   
                },
                 submitHandler: function (transaction_create) { // for demo
                    if ($('table#stpl_table tbody').find('.product_row').length <= 0) {
                        // toastr.warning(LANG.no_products_added);
                        swal('no product added');
                        return false;
                    }
                    // alert('submit');
                    $(this).attr('disabled', true);
                    // $(this).attr('disabled', true);
                    $(".status_payment").attr('disabled', false);
                    transaction_create.submit();
            }
              
                
            });


            
    var transaction_edit = $('form#transaction_edit');
    $('form#transaction_edit')
       .submit(function (e) {

       }) 
       .validate({
        rules: {
            // client: {
            //     required: true
            // },
            status: {
                required: true
            }
        
        },
        messages: {
            // client: {
            //     remote: 'required',
            // },
            status: {
                // remote: LANG.required,
                 remote: 'required',
            }
           
        },
         submitHandler: function (transaction_edit) { // for demo
            if ($('table#stpl_table tbody').find('.product_row').length <= 0) {
                // toastr.warning(LANG.no_products_added);
                swal('no product added');
                return false;
            }
            // alert('submit');
            $(this).attr('disabled', true);
            // $(this).attr('disabled', true);
            $(".status_payment").attr('disabled', false);
            transaction_edit.submit();
    }
      
        
    });

             
    var form_contact = $('form.form_contact');
    $('form.form_contact')
       .submit(function (e) {
       }) 
       .validate({
        rules: {
            // client: {
            //     required: true
            // },
            name: {
                required: true
            }
        
        },
        messages: {
            // client: {
            //     remote: 'required',
            // },
            name: {
                // remote: LANG.required,
                 remote: 'required',
            }
           
        },
         submitHandler: function (form_contact) { // for demo
            
            form_contact.submit();
    }
      
        
    });

            
    var form_medicament = $('form.form_medicament');
    $('form.form_medicament')
       .submit(function (e) {
       }) 
       .validate({
        rules: {
            // client: {
            //     required: true
            // },
            name: {
                required: true
            }
        
        },
        messages: {
            // client: {
            //     remote: 'required',
            // },
            name: {
                // remote: LANG.required,
                 remote: 'required',
            }
           
        },
         submitHandler: function (form_medicament) { // for demo
            
            form_medicament.submit();
    }
   
    });
    var form_login = $('form.form_login');
    $('form.form_login')
       .submit(function (e) {
       }) 
       .validate({
        rules: {
            
            name: {
                required: true
            }
        
        },
        messages: {
           
            name: {
                 remote: 'required',
            }
           
        },
         submitHandler: function (form_login) { // for demo
            
            form_login.submit();
    }

});

var form_register = $('form.form_register');
$('form.form_register')
   .submit(function (e) {
   }) 
   .validate({
    rules: {
        
        name: {
            required: true
        }
    
    },
    messages: {
       
        name: {
             remote: 'required',
        }
       
    },
     submitHandler: function (form_register) { // for demo
        
        form_register.submit();
}

});
    
       function show_payment(){
        var status=$("#status").val();
        
        if (status == 'final') {
            $('#add_payment').show();
                }
                else{
            $('#add_payment').hide(); 
            $("#payment").val('0.00'); 
                }
       }
       
       show_payment();
        $(document).on('change','#status',function(){
            // alert('changed');
            show_payment();
            // update_status_payment
        });

        // $('#add_payment').hide(); 

        $(document).on('change','input#payment',function(){
            update_status_payment();
        });

        // update_status_payment();

        function update_status_payment(){
            var total_amount=$('input#total_amount').val();
            var t_amount=parseFloat(total_amount);
            var total_paid=parseFloat($('input#payment').val());
            var t_paid=total_paid.toFixed(2);
            // alert(total_amount);
            // alert(t_paid);
            if( t_paid >= t_amount ){
                // alert ('paid');
                $("#status_payment").val('paid');
            }
            else{
                // alert('due')
                $("#status_payment").val('due'); 
            }
      
        } 

        // function isNumber(e){
        //     e = e || window.event;
        //     var charCode = e.which ? e.which : e.keyCode;
        //     return /\d/.test(String.fromCharCode(charCode));
        // }

        //Input number
    //     $(document).on('click', '.input-number .sh-quantity-up, .input-number .sh-quantity-down', function () {
    //     var input = $(this)
    //         .closest('.input-number')
    //         .find('input');
    //     var qty = (input).val();
    //     var step = 1;
    //     if (input.data('step')) {
    //         step = input.data('step');
    //     }
    //     var min = parseFloat(input.data('min'));
    //     var max = parseFloat(input.data('max'));

    //     if ($(this).hasClass('sh-quantity-up')) {
    //         //if max reached return false
    //         if (typeof max != 'undefined' && qty + step > max) {
    //             return false;
    //         }

    //         // __write_number(input, qty + step, false, 0);
    //         input.val(qty + step);
    //         input.change();
    //     } else if ($(this).hasClass('sh-quantity-down')) {
    //         //if max reached return false
    //         if (typeof min != 'undefined' && qty - step < min) {
    //             return false;
    //         }

    //         // __write_number(input, qty - step, false, 0);
    //         input.val(qty-step);
    //         input.change();
    //     }
    // });

      //Remove row on click on remove row
      $('table#stpl_table tbody').on('click', '.spl_remove_row', function () {
        $(this)
            .parents('tr')
            .remove();
        $('input#row_count').val(parseInt($('input#row_count').val()) - 1);
    });

    //search
    $(document).on('change','#search_sell',function(){
        var input_search=$(this).val();
        window.location.href='/sell/?input_search='+input_search;
        // $.ajax({
        //     method: 'GET',
        //     // url: '/dropshipping/get-product-row',
        //     url: '/sell',
        //     data: {input_search: input_search},
        //     dataType: 'html',
        //     success: function (result) {
        //         // console.log("success");
        //      console.log(result);
               
    
        //     }
        // });
    });
    
    $(document).on('change','#search_purchasse',function(){
        var input_search=$(this).val();
        window.location.href='/purchasse/?input_search='+input_search;
        // $.ajax({
        //     method: 'GET',
        //     // url: '/dropshipping/get-product-row',
        //     url: '/purchasse',
        //     data: {input_search: input_search},
        //     dataType: 'html',
        //     success: function (result) {
        //         // console.log("success");
        //      console.log(result);
               
    
        //     }
        // });
    });

    $(document).on('change','#search_client',function(){
        var input_search=$(this).val();
        window.location.href='/client/?input_search='+input_search;
        // $.ajax({
        //     method: 'GET',
        //     // url: '/dropshipping/get-product-row',
        //     url: '/client',
        //     data: {input_search: input_search},
        //     dataType: 'html',
        //     success: function (result) {
        //         // console.log("success");
        //      console.log(result);
               
    
        //     }
        // });
    });

    $(document).on('change','#search_fournisseur',function(){
        var input_search=$(this).val();
        window.location.href='/fournisseur/?input_search='+input_search;
        // $.ajax({
        //     method: 'GET',
        //     // url: '/dropshipping/get-product-row',
        //     url: '/fournisseur',
        //     data: {input_search: input_search},
        //     dataType: 'html',
        //     success: function (result) {
        //         // console.log("success");
        //      console.log(result);
               
    
        //     }
        // });
    });

    $(document).on('change','#search_med',function(){
        var input_search=$(this).val();
        window.location.href='/medicament/?input_search='+input_search;
        // $.ajax({
        //     method: 'GET',
        //     // url: '/dropshipping/get-product-row',
        //     url: '/medicament',
        //     data: {input_search: input_search},
        //     dataType: 'html',
        //     success: function (result) {
        //         // console.log("success");
        //      console.log(result);
               
    
        //     }
        // });
    });


      $('input[name="daterange"]').daterangepicker({
    opens: 'left'
  }, function(start, end, label) {
    var format_start=start.format('YYYY-MM-DD');
    var format_end=end.format('YYYY-MM-DD');
    console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
// $.ajax({
//     url
// });  
// var route = "{{ path('app_home', {'slug': 'my-blog-post'})|escape('js') }}";
// location.href = route;
// var url= "{{ path('SampleBundle_route') }}";
// alert(url);

// window.location.href = "{{ route('app_home')}}";
window.location.href='/?start='+format_start+'&end='+format_end;

});


$(document).on('click','#daterangepicker',function(){
    alert($(this).val());
    // location.href = '/app_dev.php/test';
});
});


