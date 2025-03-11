<?= $this->Html->script(["assets_vendor_libs/jquery/jquery", "assets_vendor_libs/popper/popper", "assets_vendor/bootstrap", "assets_vendor_libs/perfect-scrollbar/perfect-scrollbar", "assets_vendor/menu", "assets_vendor_libs/apex-charts/apexcharts", "assets_js/main", "assets_js/dashboards-analytics", "assets_vendor_libs/multiselect/jquery.multiselect.js"]) ?>

<!-- for text editor -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.52.2/codemirror.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>


<!-- Place this tag in your head or just before your close body tag. -->
<script async defer src="https://buttons.github.io/buttons.js"></script>
<!-- for Datatables  testing-->
<!-- <script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.min.js"></script> -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
<!-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script> -->
<!-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script> -->
<script type="text/javascript" src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
<!-- <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.colVis.min.js"></script> -->
<!-- <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.flash.min.js"></script> -->
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js"></script>
<!-- <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.print.min.js"></script> -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- Initialize the plugin: -->
<script type="text/javascript">
$(document).ready(function() {

    
       
    $('#example-getting-started').multiselect();
    $('#example-getting-started1').multiselect();
    
    $(".js-example-sendby").select2({
        placeholder: "Select Send By",
        width: "resolve",
    });
    $(".js-example-invoice").select2({
        placeholder: "All Invoice",
        width: "resolve",
    });
    $(".js-example-asin").select2({
        placeholder: "Select ASIN",
        width: "resolve",
    });
     //store group option 
    $(".multi-select-group").select2({
        placeholder: "Select Store Group",
        width: "resolve",
    });
    $(".multi-select-store").select2({
        placeholder: "Select Store",
        width: "resolve",
    });
    $(".multi-chat-group").select2({
        placeholder: "Select Store Group",
        width: "100%",
    });
    //end 
    //Brand store 
    $(".multi-select-brand").select2({
        placeholder: "Select Brand",
        width: "resolve",
    });
    //End

    $('.js-example-basic-multiple').select2({
        dropdownParent: $('#basicModal .modal-content'),
        placeholder: 'Select an option',
        width: 'resolve'
    });

    //verification email
    $('.js-example-basic-verification').select2({
        dropdownParent: $('#modalScrollable .modal-content'),
        placeholder: 'Select an option',
        width: 'resolve'
    });
    $('.js-example-basic-brand').select2({
        dropdownParent: $('#sendBrandApprovalEmail .modal-content'),
        placeholder: 'Select an option',
        width: 'resolve'
    });
    
    $('.js-example-basic-multiple1').select2({
        dropdownParent: $('#modalScrollable .modal-content'),
        placeholder: 'Select an option',
        width: 'resolve'
    });

    $('.product-library').select2({
        dropdownParent: $('#addProduct .modal-content'),
        placeholder: 'Select an option',
        width: 'resolve'
    });
        
    // $('.inventory-purchase').select2({
    //     dropdownParent: $('#modalScrollable .modal-content'),
    //     placeholder: 'Select an option',
    //     width: '100%'
    // });

    // $('.inventory-purchase-sku').select2({
    //     dropdownParent: $('#modalScrollable .modal-content'),
    //     placeholder: 'Select an option',
    //     width: '100%'
    // });
    
    $('.js-example-basic-Reqproduct').select2({
        dropdownParent: $('#requestedTag .modal-content'),
        placeholder: 'Select an option',
        width: 'resolve'
    });

     // by kajal
     $('.js-example-basic-Reqbrand').select2({
            dropdownParent: $('#requestedTag .modal-content'),
            placeholder: 'Select an option',
            width: 'resolve'
        });

    // end

    $('.js-example-basic-multiple-store').select2({
        dropdownParent: $('#modalScrollable .modal-content'),
        placeholder: 'Select an option',
        width: 'resolve'
    });

    $(".js-example-basic-multiple-storeType").select2({
        dropdownParent: $("#editStoreType .modal-content"),
        placeholder: "Select an option",
        width: "resolve",
    });

    $('.js-example-basic-single').select2({
        dropdownParent: $('#modalScrollable .modal-content'),
        placeholder: 'Select an option',
        width: 'resolve',
    });

    $('.js-example-cmn-single').select2({
        dropdownParent: $('#editCmn .modal-content'),
        placeholder: 'Select an option',
        width: 'resolve',
    });


    $('.js-report-client').select2({
        dropdownParent: $('#uploadFile .modal-content'),
        placeholder: 'Select an option',
        width: 'resolve',
    });

    $('.editSingleExample').select2({
        dropdownParent: $('#editClient .editModal'),
        placeholder: 'Select an option',
        width: 'resolve',
    });

    $('.js-example-ticket-client').select2({
        dropdownParent: $('#basicModal .modal-content'),
        placeholder: 'Select an option',
        width: 'resolve',
    });

    $('.js-example-range').select2({
        dropdownParent: $('#downloadFile .modal-content'),
        placeholder: 'Select an option',
        width: 'resolve',
    });
    $('.js-example-year').select2({
        dropdownParent: $('#downloadFile .modal-content'),
        // placeholder: 'Select an option',
        // width: 'resolve',
    });

    $('.js-example').select2({

        placeholder: 'Select an option',
        width: 'resolve',
    });
    $('.js-example-client').select2({

        placeholder: 'Select Client',
        width: 'resolve',
    });
    $('.js-example-clientl').select2({

    placeholder: 'Select Client',
    width: 'resolve',
    });
    $('.js-example-group').select2({

    placeholder: 'Select Group',
    width: 'resolve',
    });


    $('.js-example-store').select2({

        placeholder: 'Select Store',
        width: 'resolve',
    });
    $('.js-example-multiple-issue').select2({
        dropdownParent: $('#addStaff .modal-content'),
        placeholder: 'Select an option',
        width: 'resolve'
    });
    $('.js-example-multiple-issue2').select2({
        dropdownParent: $('#editStaff .modal-content'),
        placeholder: 'Select an option',
        width: 'resolve'
    });

    $('.add-client').select2({
        placeholder: 'Select Client',
        width: 'resolve',
    });

    // by kajal
    $('.js-example-basic-list').select2({
            dropdownParent: $('#clientTag .modal-content'),
            placeholder: 'Select an option',
            width: 'resolve'
        });

    $('.inventory-receive').select2({
            dropdownParent: $('#receiveInventory .modal-content'),
            placeholder: 'Select an option',
            width: 'resolve'
        });

    $('.js-example-multiple-inventory').select2(
        {
            placeholder: 'Select Inventory Id',
            width: 'resolve'
        }
    );
    $('.js-example-inventory').select2(
        {
            placeholder: 'Select Product',
            width: 'resolve'
        }
    );

    $('.js-example-wpid').select2(
        {
            placeholder: 'Select WPID',
            width: 'resolve'
        }
    );
    $('.js-example-walmart').select2(
        {
            placeholder: 'Select Walmart Code',
            width: 'resolve'
        }
    );
    $('.js-example-sku').select2(
        {
            placeholder: 'Select SKU',
            width: 'resolve'
        }
    );

    $('.js-example-multiple-distribution').select2(
        {
            placeholder: 'Select Distribution Plan',
            width: 'resolve'
        }
    );

    $('.js-example-basic-product').select2({
        dropdownParent: $('#clientTag .modal-content'),
        placeholder: 'Select an option',
        width: 'resolve'
    });
    
    $('.js-brand-email').select2({
            dropdownParent: $('#addStoreEmail .modal-content'),
            placeholder: 'Select an option',
            width: 'resolve'
        });

    $('.js-product-email').select2({
            dropdownParent: $('#addStoreEmail .modal-content'),
            placeholder: 'Select an option',
            width: 'resolve'
        });

    // phoneFormat
    $(".phoneFormat").attr("maxlength", "14");

    $('#user-client').click(function() {
        
        if ($('#user-client').is(":checked"))
        {
            $('.client').css('display','flex');
            if($('#user-sale').is(":checked"))
            {
                document.getElementById('affiliateYesNo').style.display = 'none';
            }
            else{
                document.getElementById('affiliateYesNo').style.display = 'none';
                $('.client-affiliate').css('display','flex');
            }
            
        }
        else{
            var checkedNum = $('input[name="user_type[]"]:checked').length;
            if (!checkedNum) {
                alert('You can not leave user type empty');
                $("#user-client").prop("checked", true)
            }
            else{
                $('.client').css('display','none');
                document.getElementById('affiliateYesNo').style.display = '';
                if(!$('#user-sale').is(":checked"))
                { 
                    document.getElementById('affiliateYesNo').style.display = 'none';
                    $('.client-affiliate').css('display','none');
                }
                
            }
        }        
    });

    $('#user-sale').click(function() {
        
        if ($('#user-sale').is(":checked"))
        {
            
            if ($('#user-client').is(":checked"))
            {
                // $('.client').css('display','block');
                // document.getElementById('affiliateYesNo').style.display = 'none';
            }
            else{
                $('.client').css('display','none');
                document.getElementById('affiliateYesNo').style.display = '';
                $('.client-affiliate').css('display','flex');
            }
            
        } 
        else{
            var checkedNum = $('input[name="user_type[]"]:checked').length;
            if (!checkedNum) {
                alert('You can not leave user type empty');
                $("#user-sale").prop("checked", true)
            }
            else{
                
                if (!$('#user-client').is(":checked"))
                {
                    $('.client').css('display','none');
                    $('.client-affiliate').css('display','none');
                    document.getElementById('affiliateYesNo').style.display = 'none';
                    // $('.client-affiliate').css('display','block');
                } 
                
            }
        }       
    });

    $('#user-ride').click(function() {
        
        if ($('#user-ride').is(":checked"))
        {
            if (!$('#user-client').is(":checked"))
            {
                $('.client').css('display','none');
                if (!$('#user-sale').is(":checked"))
                {                    
                    $('.client-affiliate').css('display','none');
                }
               
            }
            
        }
        else{
            var checkedNum = $('input[name="user_type[]"]:checked').length;
            if (!checkedNum) {
                alert('You can not leave user type empty');
                $("#user-ride").prop("checked", true)
                $('.client').css('display','none');
                $('.client-affiliate').css('display','none');
                document.getElementById('affiliateYesNo').style.display = 'none';
            }
            else{
                if (!$('#user-client').is(":checked"))
                {
                    $('.client').css('display','none');
                    if (!$('#user-sale').is(":checked"))
                    {                    
                        $('.client-affiliate').css('display','none');
                    }
                
                }
                 
            }
        }        
    });

    
    
//--------end------------


});
</script>
<!-- by kajal -->
<script type="text/javascript">
    $(document).on('click', '.close', function() {
        $('.note-modal').modal('hide');
    });
    $(document).ready(function() {

    $('.ckeditor').summernote({
        height: 200,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'italic', 'underline', 'clear']],
            ['fontname', ['fontname']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']],
            ['insert', ['link', 'picture', 'video']],
            ['view', ['fullscreen', 'codeview']]
        ],
    });    

    $('.add-client-email').select2({
            dropdownParent: $('#modalScrollable .modal-content'),
            placeholder: 'Select an option',
            width: 'resolve'
        });

        $('.add-sclient-email').select2({
            dropdownParent: $('#sendStoreEmail .modal-content'),
            placeholder: 'Select an option',
            width: 'resolve'
        });

        $('.add-client-store').select2({
            dropdownParent: $('#addStoreEmail .modal-content'),
            placeholder: 'Select an option',
            width: 'resolve'
        });

        // $('.js-example-store-group').select2({
        // dropdownParent: $('#editGroup .modal-content'),
        // placeholder: 'Select an option',
        // width: 'resolve'
        // });

        // $('.add-client-invoice').select2({
        //     dropdownParent: $('#sendInvoiceEmail .modal-content'),
        //     placeholder: 'Select an option',
        //     width: 'resolve'
        // });

        // $('.add-invoice-store').select2({
        //     dropdownParent: $('#sendInvoiceEmail .modal-content'),
        //     placeholder: 'Select an option',
        //     width: 'resolve'
        // });

        // $('.add-client-report').select2({
        //     dropdownParent: $('#uploadReportEmail .modal-content'),
        //     placeholder: 'Select an option',
        //     width: 'resolve'
        // });

        // $('.add-report-store').select2({
        //     dropdownParent: $('#uploadReportEmail .modal-content'),
        //     placeholder: 'Select an option',
        //     width: 'resolve'
        // });

        // $('.addClientId').select2({
        //     dropdownParent: $('#addClientEmail .modal-content'),
        //     placeholder: 'Select an option',
        //     width: 'resolve'
        // });

        // $('.addStore').select2({
        //     dropdownParent: $('#addOrderEmail .modal-content'),
        //     placeholder: 'Select an option',
        //     width: 'resolve'
        // });

    });
 </script>

<script>
  function rightClick(event, id) {
    event.preventDefault();
    window.open("<?= DROPSHIPPING ?>/tickets");
    // alert('id: ' + id);
  }
</script>

 <!-- end -->
<?= $this->Html->script('custom.js') ?>
<?= $this->Html->script('amazonSpApi.js') ?>