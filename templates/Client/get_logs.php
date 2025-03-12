<div class="content-wrapper">
<!-- Content -->

 <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">
     <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
         <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
             <i class="bx bx-menu bx-sm"></i>
         </a>
     </div>
     <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
         <div class="row d-flex align-items-center">
             <h5 class="fw-semibold m-0 p-0"><i class="bx bx-user me-1"></i> Client Name : <?= $userData->first_name.' '.$userData->last_name; ?></h5>
         </div>
         <div class="row d-flex align-items-center ms-auto">
            <?= $this->Form->create(null, ['method' => 'post']) ?> 
                <select id="defaultSelect" name="store_id" class="form-select" onchange="this.form.submit()">
                    <option value="0">Not Store Specific</option>
                    <?php foreach($stores as $s) {  ?>
                    <option value="<?= $s->id;?>" <?=($s->id==$store_id)?'selected':'';?>><?= $s->store_name;?></option>
                    <?php } ?>
                </select>
             <?= $this->form->end(); ?>
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
                <?php if(($loginUser->user_type==1 && $permissions['commPermission']!=3) || $loginUser->user_type==0) : ?>
                    <li class="nav-item">
                        <a class="nav-link active"
                            href="<?= ECOM360 ?>/Client/getLogs/<?= $idd;?>">
                            <i class="bx bx-detail me-1"></i>Communication
                        </a>
                    </li>
                <?php endif; ?>
                <?php if(($loginUser->user_type==1 && $permissions['acpermission']!=3) || $loginUser->user_type==0) :?>    
                    <li class="nav-item">
                        <a class="nav-link " href="<?= ECOM360 ?>/Client/activity/<?= $idd;?>">
                            <i class="bx bx-detail me-1"></i>Activity
                        </a>
                    </li>
                <?php endif;?>
                <?php if(($loginUser->user_type==1 && $permissions['edpermission']!=3) || $loginUser->user_type==0) :?>   
                    <li class="nav-item">
                        <a class="nav-link" href="<?= ECOM360 ?>/Client/editinfo/<?= $idd;?>">
                            <i class="bx bx-detail me-1"></i>Edit Info
                        </a>
                    </li>
                <?php endif;?>
                <?php if($loginUser->role_id == 13 || ($loginUser->user_type==1 && $permissions['spermission']!=3) || $loginUser->user_type==0) { ?>
                    <li class="nav-item">
                        <a class="nav-link " href="<?= ECOM360 ?>/Client/snapshot/<?= $idd;?>">
                            <i class="bx bx-detail me-1"></i>Snapshot
                        </a>
                    </li>
                <?php } ?>
                <?php if(($loginUser->user_type==1 && $permissions['stpermission']!=3) || $loginUser->user_type==0) :?>  
                    <li class="nav-item"> 
                        <a class="nav-link " href="<?= ECOM360 ?>/Client/statement/<?= $idd;?>">
                            <i class="bx bx-detail me-1"></i>Statements
                        </a>
                    </li>
                <?php endif;?>
                <?php if(($loginUser->user_type==1 && $permissions['Invpermission']!=3) || $loginUser->user_type==0) :?>
                    <li class="nav-item">
                        <a class="nav-link " href="<?= ECOM360 ?>/Client/inventory/<?= $idd;?>">
                            <i class="bx bx-detail me-1"></i>Inventory
                        </a>
                    </li>
                <?php endif;?>
                <?php if(($loginUser->user_type==1 && $permissions['Ivpermission']!=3) || $loginUser->user_type==0) :?>
                    <li class="nav-item">
                        <a class="nav-link " href="<?= ECOM360 ?>/Client/invoiceLog/<?= $idd;?>">
                            <i class="bx bx-detail me-1"></i>Invoices
                        </a>
                    </li>
                <?php endif;?>
                <?php if($loginUser->role_id == 13 || ($loginUser->user_type==1 && $permissions['Bdpermission']!=3) || $loginUser->user_type==0) { ?>
                    <li class="nav-item">
                        <a class="nav-link " href="<?= ECOM360 ?>/Client/business/<?= $idd;?>">
                            <i class="bx bx-detail me-1"></i>Business Details
                        </a>
                    </li>
                <?php } ?>
                <?php if(($loginUser->user_type==1 && $permissions['Chtpermission']!=3) || $loginUser->user_type==0) :?>
                    <li class="nav-item">
                        <a class="nav-link " href="<?= ECOM360 ?>/Client/chatdocuments/<?= $idd;?>">
                            <i class="bx bx-detail me-1"></i>Chat Documents
                        </a>
                    </li>
                <?php endif;?>
            </ul>
         </div>
     </div>

     <div class="card">         
         <div class="text-nowrap">
             <div class="container">
                 <table id="example" class="display table table-sm" style="width:100%">
                     <thead>
                         <tr>
                         <th width="1%"></th>
                         <th>Date</th>
                         <th>Time</th>
                         <th>Performed By</th>
                         <th>Title Identity</th>
                         <th>Title</th>
                         <th>Description</th>  
                         </tr>
                     </thead>
                     <tbody class="table-border-bottom-0">
                         <?php foreach ($activityLogs as $val) { ?>
                            <tr>
                                <td></td>
                                <td><?= $val->created_at->format('m/d/Y');?></td>
                                <td><?= $val->created_at->format('H:i:s');?></td>
                                <td><?= $val->user->first_name.' '.$val->user->last_name;?></td>
                                <td><?= $val->ticket->ticket_identity ?? '---';?> </td>
                                <td><?= $val->ticket->title;?> </td>
                                <td><?= $val->task;?></td>
                            </tr>
                         <?php } ?>
                     </tbody>
                 </table>
             </div>
         </div>
     </div>
 </div>
 

 <!-- / Content-->


