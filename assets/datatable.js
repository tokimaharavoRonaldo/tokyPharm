import 'datatables.net';
import 'datatables.net-bs4';
$(document).ready(function () {
$("#test").click(function(){
alert("hello");
});



$("#datatable").DataTable({

   
    
    // processing: true,
    // serverSide: true,
    // paging: true,
    // sAjaxDataProp: "data", 
    // order:[],
    //     // aaSorting: [[2, 'desc']],
    ajax: {
        "url": "/purchasse",
        // "type": "POST"
    },
    //   columnDefs: [
    //         {
    //             target: [0, 3],
    //             orderable: false
    //             // searchable: false,
    // //         }
    // //     ],
     columns:[ 
            {data: "name"}, 
            {data: "created_at"}, 
            {data: "id"} 
            // {data: 3} 
        ] 
    // 'fnCreateRow':function(nRow,aData,iDataIndex){
    //     $(nRow).attr('id',aData[0])
    // },
    //  scrollY: "75vh",
    //     scrollX: true,
    //     scrollCollapse: true,
      
    // columns: [
    //     { data: 'data' },
    //     { data: 'name' },
    //     { data: 'contact' },
    //     { data: 'medicament' },
    //     { data: 'price' },
    // ],
     
});
});


