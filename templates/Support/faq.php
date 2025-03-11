 <!-- Content -->
 <style>
        .bg {
            background-image: url(./img/bg.png);
            background-position: 50%;
            background-repeat: no-repeat;
            background-size: contain;
            height: 15rem;
            margin-top: 1.5rem;
        }
        .bg-color{
            background-color: #f5f5f9;
        }
        .ss-logo {
            width: 50px;
            height: 50px;
            box-shadow: 0 1px 2px 0 rgba(48, 48, 48, .30), 0 1px 3px 1px rgba(48, 48, 48, .15);
            border-radius: 50%;
        }

        .ss-logo img {
            height: 35px;
        }

        h1 {
            font-size: 2rem;
            font-weight: 400;
            line-height: 2.5rem;
            margin-bottom: 1.625rem;
            color: #0842a0!important;
        }

        .input-shadow {
            box-shadow: 0 1px 2px 0 rgba(48, 48, 48, .30), 0 1px 3px 1px rgba(48, 48, 48, .15);
            border-radius: 10px;
        }
        .accordion-item{
            margin-bottom: 5px;
            border: 1px solid #ccc!important;
            border-radius: 10px!important;
        }
        .accordion-button{
            box-shadow: none;
        }
        .accordion-button:not(.collapsed){
            background-color: transparent;
            box-shadow: none;
            border-color: inherit;
        }
        .flex-column {
            flex-direction: column!important;
        }
        .bdr-radius{
            border-radius: 10px!important;
        }
        .bdr-lb{
            border-top-left-radius: 10px;
            border-bottom-left-radius: 10px;
        }
        .bdr-rb{
            border-top-right-radius: 10px;
            border-bottom-right-radius: 10px;
        }
        .heading{
            font-size: 25px;
            font-weight: 700;
            color: #0842a0!important;
        }
        .label-hasUnderline:after {
            margin-top: 3px;
            content: "";
            margin-bottom: 1rem;
        }
        .label-hasUnderline:after, .label-hasUnderline:before {
            display: block;
            width: 70px;
            height: 2px;
            border-radius: 2px;
            background: #ff5f02;
        }
        .nav-link i{
            font-size: 16px;
            font-weight: 700;
        }
        .nav-link{
            font-size: 14px;
            width: 100%;
            padding: 5px 20px;
            text-align: left;
            border-radius: 5px;
            color: #0842a0;
            margin: 5px 0px;
        }
        .nav-link:focus, .nav-link:hover{
            background-color: #0842a0;
            color: #fff !important;
        }
        .nav-link.active{
            background-color: #0842a0;
            color: #fff;
        }
    </style>
 <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
     id="layout-navbar">
     <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
         <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
             <i class="bx bx-menu bx-sm"></i>
         </a>
     </div>
     <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
         <div class="row d-flex align-items-center">
             <h4 class="fw-bold m-0 p-0">Library </h4>
         </div>
         <ul class="navbar-nav flex-row align-items-center ms-auto">

             <!-- User -->
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                 <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                     <div class="avatar avatar-online">
                     <?= $this->Html->image("dropshipping/avatars/$loginUser->image", ["class" => "w-px-30 h-auto rounded-circle", "alt" => "user"]) ?>
                     </div>
                 </a>
                 <ul class="dropdown-menu dropdown-menu-end">
                     <li>
                         <a class="dropdown-item" href="#">
                             <div class="d-flex">
                                 <div class="flex-shrink-0 me-3">
                                     <div class="avatar avatar-online">
                                     <?= $this->Html->image("dropshipping/avatars/$loginUser->image", ["class" => "w-px-30 h-auto rounded-circle", "alt" => "user"]) ?>
                                     </div>
                                 </div>
                                 <div class="flex-grow-1">
                                 <span class="fw-semibold d-block"><?= $loginUser->first_name.' '.$loginUser->last_name;?></span>
                                 </div>
                             </div>
                         </a>
                     </li>
                     
                 </ul>
            </li>
            <!--/ User -->
         </ul>
     </div>
 </nav>
 <div class="container-xxl flex-grow-1 container-p-y vh-100">
     <div class="row">
         <div class="col-md-12">
             <div class="card mb-4">
                 <div class="row justify-content-center bg">
                    <div class="col-md-5">
                        <div class="text-center mt-2">
                            <h1 class="fw-semibold">How can we help you?</h1>
                            <div class="input-group mb-3 input-shadow ronded border">
                                <button class="input-group-text bg-transparent border-0 fw-semibold" id="basic-addon1">
                                    <b><i class="bx bx-search"></i></b>
                                </button>
                                <input type="text" class="form-control border-0 shadow-none fw-semibold" placeholder="Describe your Issue"
                                    aria-label="Describe your Issue" id="search_data" aria-describedby="basic-addon1" onkeyup="searchdata()">
                            </div>
                        </div>
                    </div>
                </div>
                 <?= $this->Flash->render('success') ?>
                 <?= $this->Flash->render('error') ?>
                 <div class="card-body">                 
                    <div class="row d-flex justify-content-center">
                        <div class="col-md-2 bg-color bdr-lb shadow py-3">
                            <ul class="nav flex-column border-0" id="categoryTab" role="tablist">
                                <?php $i=1;
                                foreach($category as $categories){ ?>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link <?php if($i == 1) echo 'active'; ?> fw-semibold" id="<?= $categories->id; ?>" data-bs-toggle="tab" data-bs-target="#tab-pane-<?= $categories->id ?>"
                                        type="button" role="tab" aria-controls="tab-pane-<?= $categories->id?>" aria-selected="true">
                                        <span class="fw-semibold"><?= $categories->name; ?></span>
                                    </button>
                                </li>
                                <?php $i++; } ?>                                            
                            </ul>
                        </div>
                        <div class="col-md-8 bg-white shadow bdr-rb py-3">
                            <div class="px-4">
                                <div class="tab-content" id="myTabContent">
                                <?php 
                                    $sno =1;
                                    foreach($category as $cat)
                                    { ?>
                                        <div class="tab-pane fade  <?php if($sno == 1) echo 'show active'; ?>" id="tab-pane-<?= $cat->id ?>" role="tabpanel" aria-labelledby="about-tab"
                                            tabindex="0">
                                            <?php $sno ++; ?>
                                            <h1 class="label-hasUnderline fw-bold"><?= $cat->name; ?></h1>
                                           <?php foreach($faqlist as $faq){ 
                                            if ($faq->category_name == $cat->name){?>
                                            <div class="accordion accordion-flush" id="about1">
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header">
                                                        <button class="accordion-button collapsed bdr-radius fw-semibold border-bottom" type="button" data-bs-toggle="collapse"
                                                            data-bs-target="#flush-collapse<?= $faq->id.$faq->category_id ?>" aria-expanded="false"
                                                            aria-controls="flush-collapse<?= $faq->id.$faq->category_id ?>">
                                                            <?= $faq->question; ?>
                                                        </button>
                                                    </h2>
                                                    <div id="flush-collapse<?= $faq->id.$faq->category_id ?>" class="accordion-collapse collapse"
                                                        data-bs-parent="#about1">
                                                        <div class="accordion-body">
                                                            <div class="card-body">
                                                            <?php if($faq->embed_code !=''){
                                                                echo $faq->embed_code;
                                                                } 
                                                                if($faq->url != ''){
                                                                    echo "<h5>URL</h5>";
                                                                    if (strpos($faq->url, "https://") === 0 || strpos($faq->url, "http://") === 0){
                                                                      echo "<p><a target='_blank' href='$faq->url'> $faq->url</a></p>";
                                                                    }else{
                                                                        echo "<p><a target='_blank' href=' http://$faq->url'> $faq->url</a></p>";
                                                                    }
                                                                } 
                                                                if($faq->description != ''){
                                                                  echo "<h5>Description</h5>";
                                                                  echo "<p>".$faq->description."</p>";
                                                                } ?>      
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>                                               
                                            </div>
                                            <?php } } ?>
                                        </div>
                                <?php } ?> 
                                </div>
                            </div>
                        </div>
                    </div>                             
                 </div>
             </div>
             <?= $this->Form->end() ?>
         </div>
     </div>
 </div>
 <!-- / Content -->

 <script>
    
    function searchdata()
    {
        let searchdata = $('#search_data').val();
        $.ajax({
                url: "<?= $this->Url->build(['controller' => 'Support', 'action' => 'faq']) ?>",
                type: 'get',
                data: { searchdata: searchdata, key:'searchdata'},
                success: function(res){
                    data = JSON.parse(res);
                    let faqlist = data.faqlist;
                    let category = data.category;
                    let searchHtml = ''; 
                    let categoryHtml = '';
                    let sno = 1; 
                    if(category.length >0){               
                        category.forEach((cat, index) =>{
                            let isActive = index === 0 ? 'show active' : '';
                            searchHtml += `<div class="tab-pane fade ${isActive}" id="tab-pane-${cat.id}" role="tabpanel" aria-labelledby="about-tab"
                                tabindex="0">
                                <h1 class="label-hasUnderline fw-bold">${cat.name}</h1>`;
                                faqlist.forEach(faq => {
                                    if (faq.category_name === cat.name) {
                                        searchHtml +=`<div class="accordion accordion-flush" id="about1">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button collapsed bdr-radius fw-semibold border-bottom" type="button" data-bs-toggle="collapse"
                                                data-bs-target="#flush-collapse${faq.id}${faq.category_id}" aria-expanded="false"
                                                aria-controls="flush-collapse${faq.id}${faq.category_id}">
                                                ${faq.question}
                                            </button>
                                        </h2>
                                        <div id="flush-collapse${faq.id}${faq.category_id}" class="accordion-collapse collapse"
                                            data-bs-parent="#about1">
                                            <div class="accordion-body">
                                                <div class="card-body">
                                                ${faq.embed_code !== '' ? faq.embed_code : ''}
                                                ${faq.url !== '' ? `<h5>URL</h5>` + (faq.url.startsWith("https://") || faq.url.startsWith("http://")
                                                    ? `<p><a target='_blank' href="${faq.url}">${faq.url}</a></p>`
                                                    : `<p><a target='_blank' href="http://${faq.url}">${faq.url}</a></p>`)
                                                    : ''}
                                                ${faq.description !== '' ? `<h5>Description</h5><p>${faq.description}</p>` : ''}
                                                </div>
                                            </div>
                                        </div>
                                    </div>                                               
                                </div>`;
                                } 
                            });
                        searchHtml += `</div>`;
                       }); 
                    }else{
                        searchHtml = "<h4>Data Not Found</h4>";
                    }
                            
                    category.forEach((categories, index) =>{
                        let isActive = index === 0 ? 'show active' : '';
                        categoryHtml += `<li class="nav-item" role="presentation">
                                    <button class="nav-link ${isActive} fw-semibold" id="${categories.id}" data-bs-toggle="tab" data-bs-target="#tab-pane-${categories.id}"
                                        type="button" role="tab" aria-controls="tab-pane-${categories.id}" aria-selected="true">
                                        <span class="fw-semibold">${categories.name}</span>
                                    </button>
                                </li>`;
                    });        
                    $('#myTabContent').html(searchHtml); 
                    $('#categoryTab').html(categoryHtml); 
                }, 
        });
    }
    document.addEventListener("DOMContentLoaded", function() {
    const urlParams = new URLSearchParams(window.location.search);
    let categoryId = urlParams.get('category');
    if (categoryId) {
        categoryId = window.atob(categoryId);
        // Remove 'show active' from any other tabs
        document.querySelectorAll('.tab-pane').forEach(tab => {
            tab.classList.remove('show', 'active');
        });

        // Add 'show active' to the target tab
        const targetTab = document.getElementById('tab-pane-' + categoryId);
        if (targetTab) {
            targetTab.classList.add('show', 'active');
        }

        // Activate the corresponding tab button
        const targetTabButton = document.querySelector('[data-bs-target="#tab-pane-' + categoryId + '"]');
        if (targetTabButton) {
            document.querySelectorAll('.nav-link').forEach(tabButton => {
                tabButton.classList.remove('active');
            });
            targetTabButton.classList.add('active');
        }
    }
});

</script>
