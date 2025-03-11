<?= $this->Html->script(["assets_vendor_libs/jquery/jquery", "assets_vendor_libs/popper/popper", "assets_vendor/bootstrap", "assets_vendor_libs/perfect-scrollbar/perfect-scrollbar", "assets_vendor/menu", "assets_vendor_libs/apex-charts/apexcharts", "assets_js/main", "assets_js/dashboards-analytics", "assets_vendor_libs/multiselect/jquery.multiselect.js"]) ?>

<!-- Place this tag in your head or just before your close body tag. -->
<script async defer src="https://buttons.github.io/buttons.js"></script>
<!-- for Datatables testing -->
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<!-- Initialize the plugin: -->
<script type="text/javascript">
    $(document).ready(function() {
        $('#example-getting-started').multiselect();
        $('#example-getting-started1').multiselect();
        $('.js-example-basic-multiple').select2({
            dropdownParent: $('#basicModal .modal-content'),
            placeholder: 'Select an option',
            width: 'resolve'
        });
        $('.js-example-basic-multiple1').select2({
            dropdownParent: $('#modalScrollable .modal-content'),
            placeholder: 'Select an option',
            width: 'resolve'
        });

        $('.js-example-basic-multiple-store').select2({
            dropdownParent: $('#modalScrollable .modal-content'),
            placeholder: 'Select an option',
            width: 'resolve'
        });


        $('.js-example-basic-single').select2({
            dropdownParent: $('#modalScrollable .modal-content'),
            placeholder: 'Select an option',
            width: 'resolve',
        });

        $('.editSingleExample').select2({
            dropdownParent: $('#editClient .editModal'),
            placeholder: 'Select an option',
            width: 'resolve',
        });

        $('.js-example').select2({

            placeholder: 'Select an option',
            width: 'resolve',
        });
        // phoneFormat
        $(".phoneFormat").attr("maxlength", "10");
    });
</script>
<?= $this->Html->script('custom.js') ?>