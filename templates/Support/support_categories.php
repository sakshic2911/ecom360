 <!-- Content -->
 <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
     id="layout-navbar">
     <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
         <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
             <i class="bx bx-menu bx-sm"></i>
         </a>
     </div>
     <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
         <div class="row d-flex align-items-center">
             <h4 class="fw-bold m-0 p-0">Resources</h4>
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
    <div class="row">
         <div class="col-md-12">
             <ul class="nav nav-pills flex-column flex-md-row mb-3">
                 <li class="nav-item">
                     <a class="nav-link active"
                         href="javascript:void(0);"><i class="bx bx-detail me-1"></i> Categories</a>
                 </li>
                 
                 <li class="nav-item">
                     <a class="nav-link" href="<?= ECOM360 ?>/support-resources"><i class="bx bx-detail me-1"></i>
                     Resources</a>
                 </li>
                
             </ul>
         </div>
     </div>
     <!-- Data Table -->
     <div class="card">
         <div class="card-header d-flex justify-content-between align-items-center">
             <h5 class="mb-0">Categories</h5>
             <?php if(($loginUser->user_type==1 && $permission==2) || $loginUser->user_type==0) { ?>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                         data-bs-target="#modalScrollable">
                <span class="tf-icons bx bx-plus"></span>&nbsp; Add Category
                </button>
             <?php } ?>
         </div>
         <?= $this->Flash->render('error') ?>
         <?= $this->Flash->render('success') ?>
         <div class="table text-nowrap">
             <div class="container">
                 <table id="ScrollableWithAction" class="display table table-sm" style="width:100%">
                     <thead>
                         <tr>
                            <th>Ranking</th>                       
                            <th>Category Name</th>                       
                            <th>Actions</th>
                         </tr>
                     </thead>
                     <tbody class="table-border-bottom-0">
                         <?php
                            foreach ($categoryData as $val) :
                            ?>
                         <tr>
                            <td>
                                <?php  
                                if (($loginUser->user_type == 1 && $permission == 2) || $loginUser->user_type == 0) { 
                                    echo '<input type="text" name="rank" id="ranking' . $val->id . '" value="' . $val->ranking . '" class="form-control w-25" onblur="updateRanking(' . $val->id . ',\'supportcategoryTbl\')">';
                                } else {
                                    echo $val->ranking;
                                } 
                                ?>
                            </td>                            <td><?= $val->name ?></td>
                             <td>
                                 <div class="dropdown">
                                     <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                         data-bs-toggle="dropdown">
                                         <i class="bx bx-dots-vertical-rounded"></i>
                                     </button>
                                      <?php if($loginUser->user_type==1 && $permission==1) {
                                         $d = 'View';} else{ $d= 'Edit';} ?>
                                     <div class="dropdown-menu">
                                         <a class="dropdown-item" href="javascript:void(0);"
                                             onclick="editCategoryData(<?= $val->id ?>,'<?= $d;?>')"><i
                                                 class="bx bx-edit-alt me-1"></i>
                                                 <?= $d;?></a>
                                            <?php if(($loginUser->user_type==1 && $permission==2) || $loginUser->user_type==0) { ?>
                                         <a class="dropdown-item" href="javascript:void(0);"
                                         onclick="deleteSupportData(<?= $val->id ?>,'Support/deleteSupport','supportcategoryTbl')"><i
                                                 class="bx bx-trash me-1"></i>
                                             Delete</a>
                                             <?php } ?>
                                     </div>
                                 </div>
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
 </div>
 <!-- / Content -->

<!-- Add Category -->
<div class="modal fade" id="modalScrollable" tabindex="-1" aria-hidden="true">
     <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="modalScrollableTitle">Add Category</h5>
                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
             </div>
             <div class="modal-body">
                 <?= $this->Form->create(null, ['method' => 'post', 'action' => 'Support/addCategory']) ?>
                
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label" for="basic-icon-default-product">Category Name</label>
                                <div class="input-group input-group-merge">
                                    <input type="text"  name="category_name" class="form-control" placeholder="Category Name" autocomplete="off" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label" for="basic-icon-default-product">Ranking</label>
                                <div class="input-group input-group-merge">
                                    <input type="text"  name="ranking" class="form-control" placeholder="Ranking" autocomplete="off" required value="<?= $lastRank+1; ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                     <div class="col-sm-12">
                        <div class="text-end">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            Close
                        </button>
                        <button type="submit" class="btn btn-primary">Add Category</button>
                        </div>
                     </div>

                 </div>
                 <?= $this->Form->end() ?>
             </div>
         </div>
     </div>
</div>


 <!-- Edit Category -->
 <div class="modal fade" id="editcategory" tabindex="-1" aria-hidden="true">

 </div>


 <script type="text/javascript">
function editCategoryData(id,type) {
    $.ajax({
        url: "<?= $this->Url->build(['controller' => 'Support', 'action' => 'editSupportCategory']) ?>",
        method: "GET",
        data: {
            id: id
        },
        success: function(res) {
            $("#editcategory").html(res);
            $("#editcategory").modal('show');

            if(type=='View')
            {
                $('#categoryForm input').attr('readonly', 'readonly');
                $('#categoryForm select').attr('disabled', true);
                $('.edtsbmt').css('display','none');
            }
        }
    })
}
</script>