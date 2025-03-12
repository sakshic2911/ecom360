<!DOCTYPE html>
<html lang="en">

<head>
    <script> let baseUrl = "<?= ECOM360 ?>";</script>
    <meta charset="UTF-8" />
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="<?= ECOM360 ?>/img/ECOM360/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= ECOM360 ?>/img/ECOM360/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= ECOM360 ?>/img/ECOM360/favicon/favicon-16x16.png">
    <link rel="manifest" href="<?= ECOM360 ?>/img/ECOM360/favicon/site.webmanifest">
    <link rel="mask-icon" href="<?= ECOM360 ?>/img/ECOM360/favicon/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />

    <!-- Icons. Uncomment required icon fonts -->

    <?= $this->Html->css(["assets/vendor/fonts/boxicons", "assets/vendor/css/core", "assets/vendor/css/theme-default", "assets/css/demo", "assets/css/custom", "assets/vendor/libs/perfect-scrollbar/perfect-scrollbar", "assets/vendor/css/pages/page-auth"]) ?>
    <!-- Helpers -->
    <?= $this->Html->script(["assets_vendor/helpers", "assets_js/config"]) ?>

    <!-- jquery -->
    <!-- <script src="https://code.jquery.com/jquery-3.5.1.js"></script> -->

    <!-- for Datatables  -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">

    <!-- for select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <title>Login :: Ecom 360</title>
</head>

<body data-theme="">
    <?= $this->fetch('content') ?>


    <?= $this->Html->script(["assets_vendor_libs/jquery/jquery", "assets_vendor_libs/popper/popper", "assets_vendor/bootstrap", "assets_vendor_libs/perfect-scrollbar/perfect-scrollbar", "assets_vendor/menu", "assets_vendor_libs/apex-charts/apexcharts", "assets_js/main", "assets_js/dashboards-analytics"]) ?>

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>


    <!-- for Datatables  -->
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>

    <script>
        //let baseUrl = "<?= ECOM360 ?>";
        function modalClose()
        {
            $.ajax({
                url: `${baseUrl}/Users/updateSession`,
                method: "GET",
                success: function (res) {
                location.reload()
                },
            });
        }
    </script>
</body>

</html>