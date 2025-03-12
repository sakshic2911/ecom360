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
             <h4 class="fw-bold m-0 p-0">Internal Staff List</h4>
         </div>
         <ul class="navbar-nav flex-row align-items-center ms-auto">

             <!-- User -->
             <li class="nav-item navbar-dropdown dropdown-user dropdown">
                 <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                     <div class="avatar avatar-online">
                     <?= $this->Html->image("ECOM360/avatars/$user->image", ["class" => "w-px-30 h-auto rounded-circle", "alt" => "user"]) ?>
                     </div>
                 </a>
                 <ul class="dropdown-menu dropdown-menu-end">
                     <li>
                         <a class="dropdown-item" href="#">
                             <div class="d-flex">
                                 <div class="flex-shrink-0 me-3">
                                     <div class="avatar avatar-online">
                                     <?= $this->Html->image("ECOM360/avatars/$user->image", ["class" => "w-px-30 h-auto rounded-circle", "alt" => "user"]) ?>
                                     </div>
                                 </div>
                                 <div class="flex-grow-1">
                                 <span class="fw-semibold d-block"><?= $user->first_name.' '.$user->last_name;?></span>
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
                    <a class="nav-link active" href="javascript:void(0);"><i class="bx bx-detail me-1"></i> All Internal Staff</a>
                </li>            
            </ul>
        </div>
    </div>   


     <!-- Data Table -->
     <div class="card">
         <div class="card-header">
         <?= $this->Form->create(null, ['action' => 'internal-staff']) ?>
                <div class="row align-items-center">
                    <div class="col-lg-2 col-sm-12">
                       <h5 class="mb-0">All Internal Staff List</h5>
                    </div>  
                    <div class="col-lg-8 col-sm-12">
                        <div class="mb-1">
                            <div class="input-group input-group-merge">
                                <select class="js-example-client" name="role" onchange="this.form.submit()">
                                    <option value="0">Select Role</option>
                                    <?php
                                        foreach ($roles as $rol) :
                                        ?>
                                    <option value="<?= $rol->id ?>" <?php  if ($role == $rol->id) { echo 'selected'; } ?>>
                                        <?= $rol->role_name ?></option>

                                    <?php
                                        endforeach;
                                        ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-sm-12 text-end">
                        <?php if(($user->user_type==1 && $permission==2) || $user->user_type==0) { ?>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStaff">
                                <span class="tf-icons bx bx bxs-user-plus"></span>&nbsp; Add Staff
                            </button>
                        <?php } ?>
                    </div>  
                </div> 


            <?= $this->Form->end() ?>

            

         </div>
         <?= $this->Flash->render('emailError') ?>
         <?= $this->Flash->render('dataSave') ?>
         <?= $this->Flash->render('staffUpdate') ?>
         <div class="table text-nowrap">
             <div class="">
                 <table id="ScrollableWithAction" class="display table table-sm" style="width:100%">
                     <thead>
                         <tr>
                             <th>Name</th>
                             <th>Email</th>
                             <th>Phone</th>
                             <th>Role</th>
                             <th>Last log-in</th>
                             <th>Active</th>
                             <th>Actions</th>
                             
                         </tr>
                     </thead>
                     <tbody class="table-border-bottom-0">
                         <?php  
                         foreach ($staffData as $val) : ?>
                         <tr>
                             <td><i class="fab fa-angular fa-lg text-danger me-3"></i>
                                 <strong><?= ucfirst($val->first_name) ?> <?= ucfirst($val->last_name) ?></strong>
                             </td>
                             <td><?= $val->email ?></td>
                             <td><?= $val->contact_no ?></td>
                             <td><?= $val->role_name ?></td>
                             <td><?= $val->login_time;?></td>
                             <td>
                                 <div class="form-check form-switch mb-2">
                                     <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault"
                                         <?= $val->status == 1 ? "checked" : "unchecked" ?>
                                         <?= ($user->user_type==1 && $permission==1)?'onclick="return false"':'onclick="activeInactiveStaff('.$val->id.','.$val->status.',\'Users/activeInactiveStaff\')"'; ?>/>

                                 </div>
                             </td>
                             <td>
                                 <div class="dropdown">
                                     <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                         data-bs-toggle="dropdown">
                                         <i class="bx bx-dots-vertical-rounded"></i>
                                     </button>
                                     <?php if(($user->user_type==1 && $lgPermission==1) || $user->user_type==0) { ?>

                                     <a class="" href="Users/loginStaff/<?= $val->id ?>" title="Staff Login"
                                         style="cursor: pointer;"><i class="bx bx-right-arrow-alt"></i></a>
                                         <?php } ?>
                                     <?php if($user->user_type==1 && $permission==1) { $d = 'View';} else{ $d= 'Edit';} ?>
                                     <div class="dropdown-menu">
                                         <a class="dropdown-item" 
                                             href="javascript:void(0);" onclick="editStaff(<?= $val->id ?>,'<?= $d;?>')"><i
                                                 class="bx bx-edit-alt me-1"></i>
                                                 <?= $d;?></a>
                                <?php if(($user->user_type==1 && $permission==2) || $user->user_type==0) { ?>
                                         <a class="dropdown-item" href="javascript:void(0);"
                                             onclick="deleteStaff(<?= $val->id ?>,'Users/internalStaffDelete')"><i
                                                 class="bx bx-trash me-1"></i>
                                             Delete</a>
                                            <a class="dropdown-item" 
                                            href="javascript:void(0);" onclick="editUserPermission(<?= $val->id ?>)"><i
                                            class="bx bx-edit-alt me-1"></i>
                                            Edit Permission</a>         
                                             <?php } ?>
                                     </div>
                                 </div>
                             </td>
                         </tr>
                         <?php endforeach; ?>
                     </tbody>
                 </table>
             </div>
         </div>
     </div>
     <!--/ Data Table -->
 </div>
 <!-- / Content -->


 <!-- Add Staff -->
 <div class="modal fade" id="addStaff" tabindex="-1" aria-hidden="true">
     <div class="modal-dialog" role="document">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="exampleModalLabel1">Add Staff</h5>
                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
             </div>
             <div class="modal-body">
                 <?= $this->Form->create(null, ['method' => 'post', 'action' => 'add-internal-staff', 'enctype' => 'multipart/form-data']) ?>
                 <div class="row">
                     <div class="col-md-6">
                         <div class="mb-3">
                             <label class="form-label" for="basic-icon-default-firstname">First Name</label>
                             <div class="input-group input-group-merge">
                                 <span id="basic-icon-default-firstname2" class="input-group-text"><i
                                         class="bx bx-user"></i></span>
                                 <input type="text" class="form-control" id="basic-icon-default-firstname"
                                     placeholder="First Name" name="first_name"
                                     aria-describedby="basic-icon-default-fullname2" autocomplete="off" required>
                             </div>
                         </div>
                     </div>
                     <div class="col-md-6">
                         <div class="mb-3">
                             <label class="form-label" for="basic-icon-default-lastname">Last Name</label>
                             <div class="input-group input-group-merge">
                                 <span id="basic-icon-default-lastname2" class="input-group-text"><i
                                         class="bx bx-user"></i></span>
                                 <input type="text" class="form-control" id="basic-icon-default-lastname"
                                     placeholder="Last Name" name="last_name"
                                     aria-describedby="basic-icon-default-fullname2" autocomplete="off">
                             </div>
                         </div>
                     </div>
                 </div>
                 <div class="mb-3">
                     <label class="form-label" for="basic-icon-default-email">Email</label>
                     <div class="input-group input-group-merge">
                         <span class="input-group-text"><i class="bx bx-envelope"></i></span>
                         <input type="email" id="email" class="form-control" placeholder="Email" name="email"
                             aria-describedby="basic-icon-default-email2" autocomplete="off" onblur="emailCheck(null)"
                             required>
                     </div>
                     <span class="mt-1" style="color: red;" id="emailError">

                     </span>
                 </div>
                 <div class="mb-3">
                     <label class="form-label" for="basic-icon-default-phone">Phone No</label>
                     <div class="input-group input-group-merge">
                         <span id="basic-icon-default-phone2" class="input-group-text"><i
                                 class="bx bx-phone"></i></span>
                         <input type="text" id="basic-icon-default-phone" class="form-control phone-mask phoneFormat"
                             placeholder="Phone Number" name="contact_no" aria-label="658 799 8941"
                             aria-describedby="basic-icon-default-phone2" onkeyup="formatPhone(this)">
                     </div>
                 </div>                 
                 <div class="mb-3">
                     <label for="defaultSelect" class="form-label">Role</label>
                     <select id="defaultSelect" class="form-select" name="role_id" required onchange = "checkRole(this)">
                        <option value="">Select Role</option>
                        <?php foreach ($roles as $val) : ?>
                         <option value=<?= $val->id ?>><?= $val->role_name ?></option>
                         <?php endforeach; ?>
                     </select>
                 </div>
                    <div class="mb-3 account_manager" style="display:none;">
                     <label for="defaultSelect" class="form-label">
                        Primary Account Manager</label>
                     <select class="form-select" name="primary_account_manager" style="width: 100%" onchange = "showTempManager(this,'tempAccountManager',0)">
                         <option value="">Select Primary Account Manager</option>
                         <?php foreach ($account_manager as $val) : ?>
                         <option value=<?= $val->id ?>><?= $val->name ?></option>
                         <?php endforeach; ?>
                     </select>
                   </div>
                  <div class="mb-3 account_manager" style="display:none;">
                     <label for="defaultSelect" class="form-label">
                     Temporary Account Manager</label>
                     <select id="tempAccountManager" class="js-example-multiple-issue w-100" multiple="multiple" name="temporary_account_manager[]" style="width: 100%">
                         <option value="">Select Temporary Account Manager</option>
                         <?php foreach ($account_manager as $val) : ?>
                         <option value=<?= $val->id ?>><?= $val->name ?></option>
                         <?php endforeach; ?>
                     </select>
                  </div>
                 <div class="mb-3 manager_bio" style="display:none;">
                     <label for="defaultSelect" class="form-label">Senior Manager</label>
                     <select class="form-select" name="senior_manager">
                         <option value="0">Select Senior Manager</option>
                         <?php foreach ($senior_manager as $val) : ?>
                            <option value=<?= $val->id ?>><?= $val->name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3 manager_bios" style="display:none;">
                        <label for="defaultSelect" class="form-label">Store Type</label>
                        <select class="js-example-multiple-issue form-select" multiple="multiple" name="store_type[]" style="width: 100%">
                            <option value="1">Amazon</option>
                            <option value="2">Walmart</option>
                        </select>
                    </div>
                    <div class="mb-3 manager_bios" style="display:none;">
                        <label class="form-label" for="bio">Bio</label>
                        <div class="input-group input-group-merge">
                            <textarea class="form-control" id="manager_bio" rows="3" name="manager_bio"></textarea>
                        </div>
                    </div>
                    <div class="mb-3 manager_bios" style="display:none;">
                        <label class="form-label" for="bio">Store Capacity</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="store_capacity" class="form-control"
                            placeholder="Store Capacity" name="store_capacity" >
                        </div>
                    </div>
                  <div class="mb-3 manager_bios" style="display:none;">
                        <label class="form-label" for="bio">Calender Id</label>
                        <div class="input-group input-group-merge">
                            <input type="text" id="calender_id" class="form-control"
                            placeholder="Calender Id" name="calender_id" >
                        </div>
                    </div>
                    <div class="mb-3" id="issue">
                        <label for="" class="form-label">Department</label>
                     <select name="issue_type[]" class="js-example-multiple-issue" multiple="multiple" style="width: 100%">
                         <option value="0">Select Department</option>
                         <option value="1">General Support</option>
                         <option value="2">Billing</option>
                         <option value="3">Refer a friend</option>
                         <option value="4">Onboarding a new store</option>
                         <option value="5">Portal Support</option>
                         <option value="6">Inventory management</option>
                         <!-- <option value="7">TeamViewer/device is offline</option> -->
                         <option value="8">E-comm 360/E-comm Tax Service</option>
                         <option value="8">Multilogin</option>
                         <option value="10">Walmart Stores</option>
                         <option value="11">Case management</option>
                         <option value="12">Store Transfer</option>
                         <option value="13">Amazon Questions</option>
                     </select>
                 </div>
                 <div class="mb-3">
                     <label for="formFile" class="form-label">Upload Profile Image</label>
                     <input class="form-control" type="file" id="formFile" name="image"
                         accept=".jpg, .jpeg, .png, .JPG, .JPEG, .PNG">
                     <div class="form-text">Upload accepting img, png, jpeg, jpg and other popular image formats</div>
                 </div>
                 <div class="text-end">
                     <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                         Close
                     </button>
                     <button type="submit" class="btn btn-primary">Add Staff</button>
                 </div>
                 <?= $this->Form->end() ?>
             </div>
         </div>
     </div>
 </div>

 <!-- Edit Staff -->
 <div class="modal fade" id="editStaff" tabindex="-1" aria-hidden="true">
     
 </div>
 <!-- Edit user Permission -->
 <div class="modal fade" id="editPermission" tabindex="-1" aria-hidden="true">
     
 </div>
