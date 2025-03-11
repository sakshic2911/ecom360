/**
 * Main
 */

"use strict";

let menu, animate;

(function () {
    // Initialize menu
    //-----------------

    let layoutMenuEl = document.querySelectorAll("#layout-menu");
    layoutMenuEl.forEach(function (element) {
        menu = new Menu(element, {
            orientation: "vertical",
            closeChildren: false,
        });
        // Change parameter to true if you want scroll animation
        window.Helpers.scrollToActive((animate = false));
        window.Helpers.mainMenu = menu;
    });

    // Initialize menu togglers and bind click on each
    let menuToggler = document.querySelectorAll(".layout-menu-toggle");
    menuToggler.forEach((item) => {
        item.addEventListener("click", (event) => {
            event.preventDefault();
            window.Helpers.toggleCollapsed();
        });
    });

    // Display menu toggle (layout-menu-toggle) on hover with delay
    let delay = function (elem, callback) {
        let timeout = null;
        elem.onmouseenter = function () {
            // Set timeout to be a timer which will invoke callback after 300ms (not for small screen)
            if (!Helpers.isSmallScreen()) {
                timeout = setTimeout(callback, 300);
            } else {
                timeout = setTimeout(callback, 0);
            }
        };

        elem.onmouseleave = function () {
            // Clear any timers set to timeout
            document
                .querySelector(".layout-menu-toggle")
                .classList.remove("d-block");
            clearTimeout(timeout);
        };
    };
    if (document.getElementById("layout-menu")) {
        delay(document.getElementById("layout-menu"), function () {
            // not for small screen
            if (!Helpers.isSmallScreen()) {
                document
                    .querySelector(".layout-menu-toggle")
                    .classList.add("d-block");
            }
        });
    }

    // Display in main menu when menu scrolls
    let menuInnerContainer = document.getElementsByClassName("menu-inner"),
        menuInnerShadow =
            document.getElementsByClassName("menu-inner-shadow")[0];
    if (menuInnerContainer.length > 0 && menuInnerShadow) {
        menuInnerContainer[0].addEventListener("ps-scroll-y", function () {
            if (this.querySelector(".ps__thumb-y").offsetTop) {
                menuInnerShadow.style.display = "block";
            } else {
                menuInnerShadow.style.display = "none";
            }
        });
    }

    // Init helpers & misc
    // --------------------

    // Init BS Tooltip
    const tooltipTriggerList = [].slice.call(
        document.querySelectorAll('[data-bs-toggle="tooltip"]')
    );
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Accordion active class
    const accordionActiveFunction = function (e) {
        if (e.type == "show.bs.collapse" || e.type == "show.bs.collapse") {
            e.target.closest(".accordion-item").classList.add("active");
        } else {
            e.target.closest(".accordion-item").classList.remove("active");
        }
    };

    const accordionTriggerList = [].slice.call(
        document.querySelectorAll(".accordion")
    );
    const accordionList = accordionTriggerList.map(function (
        accordionTriggerEl
    ) {
        accordionTriggerEl.addEventListener(
            "show.bs.collapse",
            accordionActiveFunction
        );
        accordionTriggerEl.addEventListener(
            "hide.bs.collapse",
            accordionActiveFunction
        );
    });

    // Auto update layout based on screen size
    window.Helpers.setAutoUpdate(true);

    // Toggle Password Visibility
    window.Helpers.initPasswordToggle();

    // Speech To Text
    window.Helpers.initSpeechToText();

    // Manage menu expanded/collapsed with templateCustomizer & local storage
    //------------------------------------------------------------------

    // If current layout is horizontal OR current window screen is small (overlay menu) than return from here
    if (window.Helpers.isSmallScreen()) {
        return;
    }

    // If current layout is vertical and current window screen is > small

    // Auto update menu collapsed/expanded based on the themeConfig
    window.Helpers.setCollapsed(true, false);
})();

function init_amazon_document_table(){
    $("#amazon_document_table").DataTable({
        order: [[0, 'desc']],
        // scrollX: true,
    });
}
// for datatable

$(document).ready(function () {
    console.log("main.js docready start.");
    $("#ScrollableWithAction1").DataTable({
        scrollX: true,
        // initComplete: function() {
        //     console.log("ScrollableWithAction1 table init complete");
        //     $('#loading_message_contaner').hide();
        //     $('#store_checkon_table_container').show();
        //     $('#ScrollableWithAction1_container').show();
        // }
    });
    $("#store_example").DataTable({
        scrollX: true,
        paging: true,
        lengthMenu: [10, 25, 50, 75, 100],
        columnDefs: [ { orderable: false, targets: [0] }],
        dom: 'Blfrtip',
        // scrollX: true,
        buttons: [
            {
                extend: 'excel',
                exportOptions: {
                    columns: ':not(.no-export)'
                },
                text: 'Export Stores',
                className: 'btn btn-primary invert-text-white m-2',
                filename: function () {
                    var date = new Date();
                    return 'store_' + date.getFullYear() + '-' + (date.getMonth() + 1) + '-' + date.getDate();
                }
            }
        ],
    });
    $("#masterClient").DataTable({
        scrollX: true,
        paging: true,
        columnDefs: [ { orderable: false, targets: [0] }],
        lengthMenu: [10, 25, 50, 75, 100],
        dom: 'Blfrtip',
        // scrollX: true,
        buttons: [
            {
                extend: 'excel',
                exportOptions: {
                    columns: ':not(.no-export)'
                },
                text: 'Export Clients',
                className: 'btn btn-primary invert-text-white',
                filename: function () {
                    var date = new Date();
                    return 'clients_' + date.getFullYear() + '-' + (date.getMonth() + 1) + '-' + date.getDate();
                }
            }
        ],
    });
    $("#example1").DataTable({
        // scrollX: true,
    });
    
    $("#barcodeExample").DataTable({
        searching: false, 
        paging: false
    });
    $('#approvedInventory').DataTable({
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excel',
                exportOptions: {
                    columns: ':not(.no-export)'
                },
                text: 'Export Inventory',
                className: 'btn btn-primary invert-text-white',
                filename: function () {
                    var date = new Date();
                    return 'inventory_' + date.getFullYear() + '-' + (date.getMonth() + 1) + '-' + date.getDate();
                }
            }
        ],
    });
    $('#approvedInventoryNew').DataTable({
        scrollX: true,
        // dom: 'Bfrtip',
        // buttons: [
        //     {
        //         extend: 'excel',
        //         exportOptions: {
        //             columns: ':not(.no-export)'
        //         },
        //         text: 'Export Inventory',
        //         className: 'btn btn-primary invert-text-white',
        //         filename: function () {
        //             var date = new Date();
        //             return 'inventory_' + date.getFullYear() + '-' + (date.getMonth() + 1) + '-' + date.getDate();
        //         }
        //     }
        // ],
    });
    $('#pendingInventory').DataTable({
        scrollX: true,
    });
    $('#creditList').DataTable({
        dom: 'Bfrtip',
        // scrollX: true,
        buttons: [
            {
                extend: 'excel',
                exportOptions: {
                    columns: ':not(.no-export)'
                },
                text: 'Export Credit',
                className: 'btn btn-primary invert-text-white',
                filename: function () {
                    var date = new Date();
                    return 'credit' + date.getFullYear() + '-' + (date.getMonth() + 1) + '-' + date.getDate();
                }
            }
        ],
    });
    $("#unassigned_inventories").DataTable({
        scrollX: true,
    });
    $("#buyexample").DataTable({
        scrollX: true,
    });
    $("#exampleT").DataTable({
        // scrollX: true,
        "ordering": false
    });
    $("#example3").DataTable({
        // scrollX: true,
    });
    $("#example2").DataTable({
        // scrollX: true,
    });
    $("#topprqnt").DataTable({
        scrollX: true,
        "aaSorting": [[ 1, "desc" ]]
    });
    $("#bottomprqnt").DataTable({
        scrollX: true,
        "aaSorting": [[ 1, "desc" ]]
    });
    $("#topstqnt").DataTable({
        scrollX: true,
        "aaSorting": [[ 1, "desc" ]]
    });
    $("#bottomstqnt").DataTable({
        scrollX: true,
        "aaSorting": [[ 1, "desc" ]]
    });
    init_amazon_document_table();
    $("#unansweredChatReport").DataTable({
        scrollX: true,
        "aaSorting": [[ 2, "desc" ]]
        // bFilter: false,
        // bInfo: false,
        // bPaginate: false,
        // sScrollY: '100px'
    });
    $("#ScrollableWithAction").DataTable({
        scrollX: true,  
        // bFilter: false,
        // bInfo: false,
        // bPaginate: false,
        // sScrollY: '100px'
    });
    $("#closedTable").DataTable({
        scrollX: true,  
        // bFilter: false,
        // bInfo: false,
        // bPaginate: false,
        // sScrollY: '100px'
    });

    $("#storegroupTbl").DataTable({
        scrollX: true,
    });

    $("#orderTable").DataTable({
        scrollX: true,
        'columnDefs': [ {
            'targets': [0], // column index (start from 0)
            'orderable': false, // set orderable false for selected columns
         }]
    });

    // $("#brandtbl").DataTable({
    //     scrollX: true,
    //     dom: 'Bfrtip',
    //     buttons: [
    //         { extend: 'excelHtml5', text: 'Export Data',className: 'btn-primary' }
    //       ]
    // });

    $("#reportSale1").DataTable({
        scrollX: true,
        searching: false, 
        paging: false,
    });

    $('#salesdatatable').DataTable({
        dom: 'Bfrtip',
        scrollX: true,
        searching: false, 
        paging: false,
        buttons: [
            {
                extend: 'excel',
                exportOptions: {
                    columns: ':visible:not(.no-export)'
                },
                text: 'Download in Excel',
                className: 'btn btn-primary invert-text-white',
                filename: function () {
                    var date = new Date();
                    return 'sales-report' + date.getFullYear() + '-' + (date.getMonth() + 1) + '-' + date.getDate();
                }
            }
        ],
    });

    $('#markupdatatable').DataTable({
        dom: 'Bfrtip',
        scrollX: true,
        buttons: [
            {
                extend: 'excel',
                exportOptions: {
                    columns: ':visible:not(.no-export)'
                },
                text: 'Download',
                className: 'btn btn-primary invert-text-white',
                filename: function () {
                    var date = new Date();
                    return 'markup-report' + date.getFullYear() + '-' + (date.getMonth() + 1) + '-' + date.getDate();
                }
            }
        ],
    });

    $("#ResaleAmazon").DataTable({
        scrollX: true,
        columnDefs: [ { orderable: false, targets: [0] }],
        dom: 'Blfrtip',
        buttons: [
            {
                extend: 'excel',
                exportOptions: {
                    columns: ':not(.no-export)'
                },
                text: 'Export To Excel',
                className: 'btn btn-primary invert-text-white',
                filename: function () {
                    var date = new Date();
                    return 'Resale-Amazon-' + date.getFullYear() + '-' + (date.getMonth() + 1) + '-' + date.getDate();
                }
            }
        ],
    });

    $("#throwAway").DataTable({
        scrollX: true,
    });
    $("#throwAwayh").DataTable({
        scrollX: true,
    });

    $("#donate").DataTable({
        scrollX: true,
    });

    $("#resaleWarehouse").DataTable({
        scrollX: true,
        columnDefs: [ { orderable: false, targets: [0] }],
        dom: 'Blfrtip',
        // scrollX: true,
        buttons: [
            {
                extend: 'excel',
                exportOptions: {
                    columns: ':not(.no-export)'
                },
                text: 'Export To Excel',
                className: 'btn btn-primary invert-text-white',
                filename: function () {
                    var date = new Date();
                    return 'Resale-walmart-' + date.getFullYear() + '-' + (date.getMonth() + 1) + '-' + date.getDate();
                }
            }
        ],
    });


    $("#wholesaleWarehouse").DataTable({
        scrollX: true,
        columnDefs: [ { orderable: false, targets: [0] }],
        dom: 'Blfrtip',
        // scrollX: true,
        buttons: [
            {
                extend: 'excel',
                exportOptions: {
                    columns: ':not(.no-export)'
                },
                text: 'Export To Excel',
                className: 'btn btn-primary invert-text-white',
                filename: function () {
                    var date = new Date();
                    return 'wholesale-' + date.getFullYear() + '-' + (date.getMonth() + 1) + '-' + date.getDate();
                }
            }
        ],
    });

    $("#tbdwarehouse").DataTable({
        scrollX: true,
        columnDefs: [ { orderable: false, targets: [0] }],
        dom: 'Blfrtip',
        // scrollX: true,
        buttons: [
            {
                extend: 'excel',
                exportOptions: {
                    columns: ':not(.no-export)'
                },
                text: 'Export To Excel',
                className: 'btn btn-primary invert-text-white',
                filename: function () {
                    var date = new Date();
                    return 'TBD-Plan-' + date.getFullYear() + '-' + (date.getMonth() + 1) + '-' + date.getDate();
                }
            }
        ],
    });

    

    $("#emailHistoryDT").DataTable({
        // scrollX: true,
        "aoColumns": [
            null,
            null,
            null,
            null,
            { "sType": "date" },
            null
          
          
        ],
        order: [[4, 'desc']]
    });
});

// For Dashboard table

$(document).ready(function () {
    $("#exampledashboard").DataTable({
        paging: false,
        scrollX: true,
        // ordering: false,
        info: false,
    });

    $('#reportSale').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax":{
           url: `${baseUrl}/Report/getData`, // json
           method: "post",// type of method
           beforeSend: function(xhr){
            xhr.setRequestHeader(
                'X-CSRF-Token',csrfToken                
            );
            },  
           error: function(){  
           }
         },

         "columns": [

            { "data": 'client_name' },
            {"data" : 'store_name' },
            {"data":'order_date' },
            {"data":'transaction_type' },
            {"data":'order_id' },
            {"data":'product_details' },
            {"data":'customer_amount' },
            {"data":'promotional_rebates' },
            {"data":'amazon_fees' },
            {"data":'other_fees' },
            {"data":'net_amazon_sale' },
            {"data":'cogs' },
            {"data":'shipping_cost' },
            {"data":'tax' },
            {"data":'total_cost' },
            {"data":'cash_profit' },
            {"data":'roi' },
            {"data":'fba' }

        ],
       });

    //    $('#inventoryPurchaseTable').DataTable({
    //         scrollX: true,
    //         dom: 'Bfrtip',
    //         buttons: [
    //             {
    //                 extend: 'csvHtml5',
    //                 text: 'Export CSV',
    //                 className: 'btn btn-primary invert-text-white',
    //                 exportOptions: {
    //                     columns: function(idx, data, node) {
    //                         // Include all visible columns except the last one (actions), and exclude "Resale Notes" (10), "Upload Purchase Doc" (11)
    //                         return idx !== -1 && $(node).is(':visible') && idx !== 10 && idx !== 11 || idx === 13; // Including Payment Notes
    //                     }
    //                 }
    //             }
    //         ],
    //         columnDefs: [
    //             {
    //                 targets: [0], // Hide the first column
    //                 visible: false
    //             },
    //             {
    //                 targets: [13],  // Hide "Payment Notes"
    //                 visible: false
    //             }
    //         ]
    //     });
});

$(".removeRow").click(function () {
    $(this).closest("tr").remove();
});




