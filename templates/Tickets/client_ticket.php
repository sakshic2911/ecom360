
 <!-- Content -->
 <?= $this->Html->css("assets/css/kanban") ?>
 <?= $this->Html->css("assets/css/custom") ?>
 <style>
    .focusedRow
{
    font-weight: bold;
    text-shadow: 1px 1px 0px #EEE;
    color: #111;   
}
    </style>

<?php
$brand = $_SESSION['brand_data'];
$storeTypeInfo = $_SESSION['storeType'] ?? '';
$brands = array();$orderId = 0;
if($brand == 'yes')
{
    $brands = $_SESSION['brands'];
    $orderId = $_SESSION['order_id'];
}
?>

 <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
     id="layout-navbar" style="background-color:#eef2f9 !important;">
     <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
         <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
             <i class="bx bx-menu bx-sm"></i>
         </a>
     </div>
     <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
         <div class="row d-flex align-items-center">
             <h4 class="fw-bold m-0 p-0">Ticket List</h4>
         </div>
         <ul class="navbar-nav flex-row align-items-center ms-auto">

            <!-- User -->
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                 <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                     <div class="avatar avatar-online">
                     <?= $this->Html->image("ECOM360/avatars/$loginUser->image", ["class" => "w-px-30 h-auto rounded-circle", "alt" => "user"]) ?>
                     </div>
                 </a>
                 <ul class="dropdown-menu dropdown-menu-end">
                     <li>
                         <a class="dropdown-item" href="#">
                             <div class="d-flex">
                                 <div class="flex-shrink-0 me-3">
                                     <div class="avatar avatar-online">
                                     <?= $this->Html->image("ECOM360/avatars/$loginUser->image", ["class" => "w-px-30 h-auto rounded-circle", "alt" => "user"]) ?>
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

 <div class="container-xxl flex-grow-1 container-p-y">

     <!-- start -->
     <div class="row ms-2">
         <div class="col-md-12">
             <ul class="nav nav-pills flex-column flex-md-row mb-3">
                 <li class="nav-item">
                     <a class="nav-link active" href="javascript:void(0);" style="background-color:#73a154 !important;"><i class="bx bx-detail me-1"></i> Tickets</a>
                 </li>
                 <?php if($loginUser->user_type == 2): ?> 
                 <li class="nav-item">
                     <a class="nav-link" href="<?= ECOM360 ?>/client-archive-tickets"><i class="bx bx-detail me-1"></i>
                         Archived Tickets</a>
                 </li>
                 <?php endif;?>
             </ul>
         </div>
     </div>
     <!-- End -->

     <!-- Data Table -->
     <div class="card">
         <div class="card-header">
         <?= $this->Form->create(null, ['action' => 'clientTicket','method'=>'post']) ?>
             <div class="row align-items-center">
                
                 
                 <div class="col-lg-3 col-sm-12 mb-2">
                
                    
                 </div>
                 
                <div class="col-lg-7 col-sm-12">
                    
                </div>
                
                <div class="col-lg-2 col-sm-12">
                    <div class="mt-4 text-end">
                        <button type="button" class="btn btn-primary w-100" data-bs-toggle="modal"
                            data-bs-target="#basicTModal">
                            Add Ticket
                        </button>
                    </div>
                </div>
             </div>
             <?= $this->Form->end() ?>
         </div>
         <?= $this->Flash->render('saveTickets') ?>
         <?= $this->Flash->render('errorMsg') ?>
         <?= $this->Flash->render('fieldErr') ?>

         <div class="table-responsive text-nowrap">
             <div class="container">
                 <table id="exampleT" class="display table table-sm text-center" style="width:100%">
                     <thead>
                         <tr>
                             
                             <!-- <th>Store Name</th> -->
                             <th>Issue</th>
                             <th>Title</th>
                             <th>ID</th>
                             <th>Status</th>
                             <th>Created At</th>
                             <!-- <th>Action</th> -->
                             <th>Rating</th>
                         </tr>
                     </thead>
                     <tbody id="myTable" class="table-border-bottom-0">
                         <?php
                         $status = ["Pending","In-progress","Completed","Closed","Closed"];
                            foreach ($tickets as $t) :
                                if($t->seen===0 && $t->client_id != $loginUser->id)
                                $class = "focusedRow";
                                else
                                $class="";

                            $ticketStatus = $status[$t->status];
                            ?>

                         <tr style="cursor: pointer;" class="<?= $class;?>">
                             <!-- <td><?= $t->store_name ?></td> -->
                             <td onclick="kanban(<?= $t->id ?>,<?= $t->store_id ?>)"><?= $issues[$t->issue_type]; ?></td>
                             <td onclick="kanban(<?= $t->id ?>,<?= $t->store_id ?>)"><?= $t->title?></td>
                             <td onclick="kanban(<?= $t->id ?>,<?= $t->store_id ?>)"><?= $t->ticket_identity?></td>
                             <td onclick="kanban(<?= $t->id ?>,<?= $t->store_id ?>)"><?= $ticketStatus;?></td>
                             <td data-sort="<?= $t->add_date ? date('YmdHis', strtotime($t->add_date)) : '' ?>" onclick="kanban(<?= $t->id ?>,<?= $t->store_id ?>)"><?= $t->add_date; ?>
                            <td>
                                <span id="ticketRating<?= $t->id ?>">
                                <?php if($ticketStatus == 'Closed' && empty($t->rating)) :?>
                                    <i class="bx bx-sad smileIcon sadIcon" data-rating="Not Satisfied" data-id="<?= $t->id ?>"></i> 
                                    <i class="bx bx-meh smileIcon mehIcon" data-rating="Neutral" data-id="<?= $t->id ?>"></i> 
                                    <i class="bx bx-smile smileIcon happyIcon" data-rating="Satisfied" data-id="<?= $t->id ?>"></i>
                                <?php else: ?>
                                    <?= $t->rating ?? '---' ?>
                                <?php endif;?>    
                                </span>
                            </td>

                         </tr>

                         <?php
                            endforeach;
                            ?>
                     </tbody>
                 </table>
             </div>
         </div>
     </div>
     <!--/ Data Table -->
     <!-- <script async
   crossorigin
   type="module"
   id="engagementWidget"
   src="https://cdn.chatwidgets.net/widget/livechat/bundle.js"
   data-env="portal-api"
   data-instance="ZGK_0M8Seo0SG4am"
   data-container="#engagement-widget-container"></script> -->
 </div>
 <!-- / Content -->

 <!-- Modal -->
 <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="kanban-modal-label-1"
                aria-hidden="true" id="kanban-modal-1">

            </div>

<!-- Add Tickets Modal -->
<div class="modal fade" id="basicTModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1">Add Ticket</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?= $this->Form->create(null, [
                    'method' => 'post',
                    'enctype' => 'multipart/form-data',
                    'action' => 'addTicket'
                ])
                ?>
             
                
             <div class="mb-3">
                    <label for="defaultSelect" class="form-label">Issue Type</label>
                    <select id="defaultSelect" name="issue_type" class="form-select" onchange="checkIssue(this)" required>
                        <option value="">Select Issue</option>
                        <!-- <option value="1">General Support</option>
                        <option value="2">Billing</option>
                        <option value="3">Refer a friend</option>
                        <option value="4">Onboarding a new store</option>
                        <option value="5">Portal Support</option>
                        <option value="6">Inventory management</option> -->
                        <!-- <option value="7">TeamViewer/device is offline</option> -->
                        <!-- <option value="8">E-comm 360/E-comm Tax Service</option> -->
                        <!-- <option value="9">Multilogin</option> -->
                        <!-- <option value="10">Walmart Stores</option>
                        <option value="12">Store Transfer</option> -->
                        <option value="13">Amazon Questions</option>
                    </select>
                </div>
                <!-- <div class="mb-1 storeRadio">
                    <label class="form-label">Is the Ticket</label><br>
                        <div class="form-check form-check-inline mb-1">
                            <input class="form-check-input" type="radio" id="inlineRadio1"
                                name="store_specific" value="yes" onclick="storeYesNo('yes')">
                            <label class="form-check-label fw-semibold"
                                for="inlineRadio1" onclick="storeYesNo('yes')">Store Specific</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" id="inlineRadio2"
                                name="store_specific" value="no" onclick="storeYesNo('no')">
                            <label class="form-check-label fw-semibold"
                                for="inlineRadio2" onclick="storeYesNo('no')">Not Store Specific</label>
                        </div>   
                        
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" id="inlineRadio3"
                                name="store_specific" value="yesno" onclick="storeYesNo('yesno')">
                            <label class="form-check-label fw-semibold" for="inlineRadio3" onclick="storeYesNo('yesno')">
                             Store Specific - Store Not Listed In Portal
                            </label>
                        </div> 
                </div> -->
                <!-- <div class="mb-3 showStore" style="display:none;"> -->
                <div class="mb-3 showStore">
                    <label class="form-label" for="basic-icon-default-email">For Store</label>
                    
                    <select class="form-select inventStore" name="store_id" style="width: 100%" required>
                        <?php    if (!empty($clientStoreData)) : ?>
                        <?php foreach ($clientStoreData as $storeValue) : ?>                        
                        <option value="<?= $storeValue->id ?>"><?= $storeValue->store_name ?></option>
                        <?php endforeach; ?>
                        <?php  else :?>
                            <option value="0">No Store</option>
                        <?php endif; ?>
                    </select>
                    
                </div>
                <div class="mb-3">
                    <label for="defaultInput" class="form-label">Title</label>
                    <input id="defaultInput" class="form-control" type="text" name="title" placeholder="Title"
                        autocomplete="off" required>
                </div>
                <div class="mb-3">
                    <label for="exampleFormControlTextarea1" class="form-label">Description</label>
                    <textarea class="form-control" id="" rows="3" name="description"></textarea>
                </div>
                <div class="mb-3">
                    <label for="formFileMultiple" class="form-label">Upload Suppoting Attachments</label>
                    <input class="form-control" type="file" id="" name="file[]" multiple>
                </div>
                <div class="text-end">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" class="btn btn-primary">Save Ticket</button>
                </div>
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>

<!-- Add Image 'action' => 'Tickets/addMoreFile',-->

<div class="modal fade" id="imgBasicModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1">Upload File</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?= $this->Form->create(null, [
                    'id' => 'addFile'
                ])
                ?>
                <div class="mb-3">
                    <label for="formFileMultiple" class="form-label">Upload Suppoting Attachment</label>
                    <input class="form-control" type="file" id="files" name="files[]">
                </div>
                <div class="text-end">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                    <button type="button" class="btn btn-primary" onclick="commentAddFile()">File Upload</button>
                </div>
                <input type="hidden" name="ticketID" id="ticketID">
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>

<!-- Add Image -->

<div class="modal fade" id="imgBasicAtcModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1">Upload File</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?= $this->Form->create(null, [
                    'method' => 'post',
                    'enctype' => 'multipart/form-data',
                    'url' => [
                        'controller' => 'Tickets',
                        'action' => 'addAtcFile'
                    ]
                ])
                ?>
                <div class="mb-3">
                    <label for="formFileMultiple" class="form-label">Upload Suppoting Attachments</label>
                    <input class="form-control" type="file" id="" name="files[]" multiple>
                    <input type="hidden" name="category" value="0"/>
                </div>
                <div class="text-end">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" class="btn btn-primary">File Upload</button>
                </div>
                <input type="hidden" name="ticketID" id="ticketAtcID">
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>

 <!-- Modal -->
<?php if($brand=='yes') { ?>
    <div class="modal fade show" id="basicModal" tabindex="-1" aria-modal="true" role="dialog" style="display: block; padding-left: 0px;">
<?php  } else{ ?>
    <div class="modal fade" id="basicModal" tabindex="-1" aria-hidden="true">
<?php } ?>
     <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="modalScrollableTitle">Accept Approval</h5>
                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="closeModal()"></button>
             </div>
             <div class="modal-body">
                 <?= $this->Form->create(null, ['id' => 'brandData']) ?>

                 <div class="modal-body">
                 <div class="row">
                    Congratulations! Shipment of following products is on its way to your store.<br>
                    <span class="mt-3"><b>Product:</b> <?= $brands[0]->product_name; ?></span>
                    <span> <b>Quantity: </b> <?= $brands[0]->qty;?></span><br>
                   
                    <strong class="mt-3">***Please note:</strong>
                    <span class="mb-3"><?= $storeTypeInfo ;?> charges a fee for the shipping from the Ecom 360 warehouse to the <?= $storeTypeInfo ;?> warehouse. This fee is imposed by <?= $storeTypeInfo ;?>. If you do not have a balance in your <?= $storeTypeInfo ;?> account, the fee will be charged to your payment method on file in <?= $storeTypeInfo ;?> Seller Central. If you do have a balance in your <?= $storeTypeInfo ;?> account, the fee will be deducted from that balance. This shipping fee is marked from your profit sheet at the end of the month.</span>

                    <span class="mt-12">Inventory quantities of a certain product are tentative and therefore subject to change. For example, there were 100 Keurig items assigned to be sent to <?= $storeTypeInfo ;?> for your store, the warehouse may need to reduce the shipment to 90 of these Keurig items, and the difference in capital will be returned to your available working capital.</span>
                    </div>
                     <div class="text-end mt-4">
                     <button type="button" class="btn btn-primary" onclick="submitData('yes',<?= $brands[0]->brand_id;?>,<?= $orderId;?>)">Acknowledge</button>    
                     
                         
                     </div>
                 </div>
                 <?= $this->Form->end() ?>
             </div>
         </div>
     </div>
 </div>

            <?= $this->Html->script(['kanban/anchorjs/anchor.min.js', 'kanban/draggable/draggable.bundle.legacy.js', 'kanban/fontawesome/all.min.js', 'kanban/lodash/lodash.min.js', 'assets_js/kanban.js']) ?>

 <script>

function kanban(id, storeId) {
    $("#ticketID").val(id);
    $("#ticketAtcID").val(id);
    $.ajax({
        url: "<?= $this->Url->build(['controller' => 'Tickets', 'action' => 'kanbanModal']) ?>",
        method: "GET",
        data: {
            id: id,
            storeId: storeId
        },
        success: function(res) {
            $('#kanban-modal-1').html(res);
            $('#kanban-modal-1').modal('show');
            // CKEDITOR.replace( 'commentMessage' );
            $('#kanban-modal-1').on('shown.bs.modal', function () {
                $('#commentMessageTicket').summernote({
                    height: 200,
                    dialogsInBody: true,
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
            });
        }
    })
}

function commentAddFile() {
   

//    $("#addFile").submit(function(e) {
//    e.preventDefault();    
    var ticketID = document.getElementById("ticketID").value;
    var input = document.getElementById("files");
    file = input.files[0];   
    var formData = new FormData();
    formData.append("files[]", file);
    formData.append("ticketID",ticketID)

   $.ajax({
       url: "<?= $this->Url->build(['controller' => 'Tickets', 'action' => 'addMoreFile']) ?>",
       type: 'POST',
       data: formData,
       success: function (res) {

           $('#imgBasicModal').modal('hide');
           let commentData = JSON.parse(res);
           var design = '<a class="text-decoration-none" href="<?= ECOM360;?>/img/tickets_file/'+commentData.file+'" target="_blank">'+commentData.file+'</a>&emsp;&emsp;&emsp;&emsp;';
           
           $('.newAtc').append(design);
           
       },
       cache: false,
       contentType: false,
       processData: false
   });
// });

   
}

document.querySelectorAll('.smileIcon').forEach(icon => {
    icon.addEventListener('click', function() {
        const rating = this.getAttribute('data-rating');
        const ticketId = this.getAttribute('data-id');
        updateTicketRating(ticketId, rating);
    });
});

function updateTicketRating(ticketId, rating) {
    $.ajax({
        url: "<?= $this->Url->build(['controller' => 'Tickets', 'action' => 'addTicketRating']) ?>",
        method: "POST",
        data: {
            ticketId: ticketId,
            rating: rating
        },
        success: function(res) {
            console.log(res);
            document.getElementById('ticketRating'+ticketId).innerHTML = rating;
        }
    })
}
</script>