 <!-- Content -->

 <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">
     <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
         <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
             <i class="bx bx-menu bx-sm"></i>
         </a>
     </div>
     <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
         <div class="row d-flex align-items-center">
             <h4 class="fw-bold m-0 p-0">Master List</h4>
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
                                     <span class="fw-semibold d-block"><?= $user->first_name . ' ' . $user->last_name; ?></span>
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
     <!-- Data Table -->
     <div class="card">
         <div class="card-header align-items-center row">
                <div class="col-lg-2 col-sm-12 mb-2">
                    <h5 class="mb-0">Master List</h5>
                </div>
                <?= $this->Form->create(null, ['method' => 'post']) ?>
                <div class="row align-items-end mt-2">
                    <div class="col-lg-2 col-sm-12 mb-2">
                        <label for="from">From:</label>
                        <input type="date" id="from" name="from_date" class="form-control" value="<?= $fromDt?>" onclick="this.showPicker()">
                    </div>
                    <div class="col-lg-2 col-sm-12 mb-2">
                        <label for="to">To:</label>
                        <input type="date" id="to" name="to_date" class="form-control" value="<?= $toDt ?>" onclick="this.showPicker()">
                    </div>
                    <div class="col-lg-2 col-sm-12 mb-2">
                        <div class="mt-4 text-end">
                            <button type="submit" class="btn btn-secondary w-100" onclick="this.form.submit()">
                                Apply
                            </button>
                        </div>
                    </div>              
                    <div class="col-lg-2 col-sm-12 mb-2">
                        <div class="mt-4 text-end">
                            <a href="<?= ECOM360 ?>/accountholders" class="btn btn-info w-100">
                                Clear
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-2 col-sm-12 mb-2 text-end offset-lg-2">
                        <?php if (($user->user_type == 1 && $permission == 2) || $user->user_type == 0) { ?>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalScrollable">
                                <span class="tf-icons bx bx bxs-user-plus"></span>&nbsp; Add Client
                            </button>
                        <?php } ?>
                    </div>
                </div>
                <?= $this->Form->end() ?>
            </div>
         <?= $this->Flash->render('clientData') ?>
         <?= $this->Flash->render('clientEmailError') ?>
         <?= $this->Flash->render('masterUpdate') ?>
         <?= $this->Flash->render('clientErr') ?>
         <?= $this->Flash->render('bulkmanager') ?>
         <div class="container">
            <div class="table text-nowrap table-responsive">
                 <table id="masterClient" class="display table table-sm" style="width:100%">
                     <thead>
                         <tr>
                             <th>Name</th>
                             <th>Email</th>
                             <th>Phone</th>
                             <th class="no-export">Login</th>
                             <th class="no-export">Reason</th>
                             <th>Last Login Activity</th>
                             <th class="no-export">Actions</th>
                         </tr>
                     </thead>
                     <tbody class="table-border-bottom-0">
                         <?php foreach ($masterClients as $val) : ?>
                             <tr>                               
                                 <td><i class="fab fa-angular fa-lg text-danger me-3"></i>
                                     <strong><?= ucfirst($val->first_name) ?> <?= ucfirst($val->last_name) ?></strong>
                                 </td>
                                 <td><?= $val->email ?></td>
                                 <td><?= $val->contact_no ?></td> 
                                 <td>
                                     <div class="form-check form-switch mb-2">
                                         <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault" <?= $val->status == 1 ? 'checked' : 'unchecked' ?> <?= ($user->user_type == 1 && $permission == 1) ? 'onclick="return false"' : 'onclick="openreasonBox(' . $val->id . ',' . $val->status . ',\'Users/activeInactiveStaff\')"'; ?> />

                                     </div>
                                 </td>
                                 <td>
                                     <?php if ($val->status == 0) { ?>
                                         <a href="javascript:void(0);" title="Show Deactivation Reason" onclick="showDeactivation(<?= $val->id ?>,'Client/showDeactivation','Deactivation Reason','Reason')"><i class='bx bx-low-vision me-1 clr-theme'></i>
                                         </a>
                                     <?php } ?>
                                 </td>                               
                                 <td data-sort="<?= $val->last_login ? date('YmdHis', strtotime($val->last_login)) : '' ?>">
                                 <?= ($val->last_login == '0000-00-00 00:00:00' || empty($val->last_login)) ? '-' : date('m/d/Y H:i:s',strtotime($val->last_login))."EST"; ?>
                                 </td>
                                 <td>
                                     <div class="dropdown">
                                         <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                             <i class="bx bx-dots-vertical-rounded"></i>
                                         </button>
                                         <?php
                                           $d = 'Edit'; ?>
                                             <a class="" href="Client/loginClient/<?= $val->id ?>" title="Client Login" style="cursor: pointer;"><i class="bx bx-right-arrow-alt"></i></a>
                                         <div class="dropdown-menu">
                                             <a class="dropdown-item" id="editClientSelect" href="javascript:void(0);" onclick="editMasterClient(<?= $val->id ?>, 'Client/editMasterClient','<?= $d; ?>')"><i class="bx bx-edit-alt me-1"></i>
                                                 <?= $d; ?></a>
                                             
                                                 <a class="dropdown-item" href="javascript:void(0);" onclick="deleteStaff(<?= $val->id ?>,'Users/internalStaffDelete')"><i class="bx bx-trash me-1"></i>
                                                     Delete</a>
                                           
                                                 <!-- <a class="dropdown-item" href="<?= ECOM360 ?>/Client/getLogs/<?= base64_encode($val->id) ?>"><i class="bx bx-align-left me-1"></i>
                                                     See Activity log</a> -->
                                            
                                         </div>
                                     </div>
                                 </td>

                             </tr>
                         <?php endforeach ?>
                     </tbody>
                 </table>
             </div>
         </div>
     </div>
     <!--/ Data Table -->
 </div>
 <!-- / Content -->

 <!-- Add Client Modal -->

 <div class="modal fade" id="modalScrollable" tabindex="-1" aria-hidden="true">
     <div class="modal-dialog modal-lg" role="document">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="modalScrollableTitle">Add Client</h5>
                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
             </div>
             <div class="modal-body">
                 <?= $this->Form->create(null, ['method' => 'post', 'action' => 'add-master-client']) ?>

                 <div class="row">
                     <div class="col-md-6">
                         <div class="mb-3">
                             <label class="form-label" for="basic-icon-default-firstname">First Name</label>
                             <div class="input-group input-group-merge">
                                 <span id="basic-icon-default-firstname2" class="input-group-text"><i class="bx bx-user"></i></span>
                                 <input type="text" class="form-control" id="firstName" name="first_name" placeholder="First Name" aria-describedby="basic-icon-default-fullname2" autocomplete="off" required>
                             </div>
                         </div>
                     </div>
                     <div class="col-md-6">
                         <div class="mb-3">
                             <label class="form-label" for="basic-icon-default-lastname">Last Name</label>
                             <div class="input-group input-group-merge">
                                 <span id="basic-icon-default-lastname2" class="input-group-text"><i class="bx bx-user"></i></span>
                                 <input type="text" class="form-control" id="lastName" name="last_name" placeholder="Last Name" aria-describedby="basic-icon-default-fullname2" autocomplete="off">
                             </div>
                         </div>
                     </div>
                 </div>
                 <div class="row">
                     <div class="col-md-6">
                         <div class="mb-3">
                             <label class="form-label" for="basic-icon-default-email">Email</label>
                             <div class="input-group input-group-merge">
                                 <span class="input-group-text"><i class="bx bx-envelope"></i></span>
                                 <input type="email" id="email" name="email" class="form-control" placeholder="Email" aria-label="Rajiv.doe@example.com" aria-describedby="basic-icon-default-email2" onblur="emailCheck(null)" autocomplete="off" required>
                             </div>
                             <span class="mt-1" style="color: red;" id="emailError">

                             </span>
                         </div>
                     </div>
                     <div class="col-md-6">
                         <div class="mb-3">
                             <label class="form-label" for="basic-icon-default-phone">Phone No</label>
                             <div class="input-group input-group-merge">
                                 <span id="basic-icon-default-phone2" class="input-group-text"><i class="bx bx-phone"></i></span>
                                 <input type="text" id="contactNo" name="contact_no" class="form-control phone-mask phoneFormat" placeholder="Phone Number" aria-label="" aria-describedby="basic-icon-default-phone2" autocomplete="off" onkeyup="formatPhone(this)">
                             </div>
                             <span class="mt-1" style="color: red;" id="phoneError">

                             </span>
                         </div>
                     </div>
                 </div>

                 <div class="row">
                     <div class="col-md-12">
                         <div class="mb-3">
                             <label for="exampleFormControlTextarea1" class="form-label">Address</label>
                             <textarea class="form-control" id="address" name="address" rows="3" placeholder="Address..."></textarea>
                         </div>
                     </div>
                 </div>

                 <div class="row">
                     <div class="col-md-6">
                         <div class="mb-3">
                             <label class="form-label" for="basic-icon-default-company">Organization Name</label>
                             <div class="input-group input-group-merge">
                                 <span id="basic-icon-default-company2" class="input-group-text"><i class="bx bx-buildings"></i></span>
                                 <input type="text" id="organisation" name="organisation" class="form-control" placeholder="Organization Name" aria-label="Actiknow Pvt. Ltd.." aria-describedby="basic-icon-default-company2">
                             </div>
                         </div>
                     </div>
                     <div class="col-md-6">
                         <div class="mb-3">
                             <label class="form-label" for="basic-icon-default-company">Referring Affiliate's
                                 Name</label>
                             <select id="srch-select" class="js-example-basic-single" name="referrer_affiliate" style="width: 100%">
                                 <option value="0">Please Select</option>
                                 <?php
                                    foreach ($affiliateClients as $val) :
                                    ?>
                                     <option value="<?= $val->id ?>"><?= ucfirst($val->first_name) ?>
                                         <?= ucfirst($val->last_name) ?></option>
                                 <?php
                                    endforeach ?>
                             </select>
                         </div>
                     </div>
                 </div>
                 <div class="row">
                     <div class="col-md-4">
                         <div class="mb-3">
                             <label class="form-label" for="basic-icon-default-company">Is an affiliate?</label>
                             <div class="col-md mt-2">
                                 <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                                     <input type="radio" class="btn-check" name="affiliate" id="affiliateYes" value=1>
                                     <label class="btn btn-outline-primary" for="btnradio1" onclick="affiliateYesNo('yes')">Yes</label>
                                     <input type="radio" class="btn-check" name="affiliate" id="affiliateNo" value=0 checked>
                                     <label class="btn btn-outline-primary" for="btnradio2" onclick="affiliateYesNo('no')">No</label>
                                 </div>
                             </div>
                         </div>
                     </div>
                     <div class="col-md-4">
                         <div class="mb-3">
                             <label class="form-label" for="basic-icon-default-company">Is there an override
                                 partner?</label>
                             <div class="col-md mt-2">
                                 <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                                     <input type="radio" class="btn-check" name="override" id="overrideYes" value=1>
                                     <label class="btn btn-outline-primary" for="btnradio3" onclick="overrideYesNo('yes')">Yes</label>
                                     <input type="radio" class="btn-check" name="override" id="overrideNo" value=0 checked>
                                     <label class="btn btn-outline-primary" for="btnradio4" onclick="overrideYesNo('no')">No</label>
                                 </div>
                             </div>
                         </div>
                     </div>
                     <!-- <div class="col-md-4">
                         <div class="mb-3">
                             <label class="form-label" for="basic-icon-default-company">Store Owner?</label>
                             <div class="col-md mt-2">
                                 <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                                     <input type="radio" class="btn-check" name="store_own" id="storeYes" value=1>
                                     <label class="btn btn-outline-primary" for="btnradio5" onclick="checkStore('yes')">Yes</label>
                                     <input type="radio" class="btn-check" name="store_own" id="storeNo" value=0 checked>
                                     <label class="btn btn-outline-primary" for="btnradio6" onclick="checkStore('no')">No</label>
                                 </div>
                             </div>
                         </div>
                     </div> -->
                 </div>
                 <div id="affiliateYesNo">
                     <div class="row">
                         <div class="col-md-6">
                             <div class="mb-3">
                                 <label for="defaultSelect" class="form-label">Select Plan</label>
                                 <select id="defaultSelect" class="form-select" name="plan">
                                     <option value="0">Please Select Plan</option>
                                     <?php foreach ($plan as $planVal) : ?>
                                         <option value="<?= $planVal->id ?>">
                                             <?= $planVal->plan_name ?>
                                         </option>
                                     <?php endforeach;  ?>
                                 </select>
                             </div>
                         </div>
                         <div class="col-md-6">
                             <div class="mb-3">
                                 <label for="html5-tel-input" class="col-form-label">Affiliate ID</label>
                                 <div class="input-group input-group-merge">
                                     <!-- <span id="basic-icon-default-company2" class="input-group-text">#</span> -->
                                     <input class="form-control" type="text" name="ssn" id="ssn" placeholder="Affiliate ID">
                                 </div>
                             </div>
                         </div>
                     </div>
                     <!-- <div class="row">
                         <div class="col-md-6">
                             <div class="mb-3">
                                 <label class="form-label" for="basic-icon-default-company">Is there an override
                                     partner?</label>
                                 <div class="col-md mt-2">
                                     <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                                         <input type="radio" class="btn-check" name="override" id="overrideYes" value=1>
                                         <label class="btn btn-outline-primary" for="btnradio3" onclick="overrideYesNo('yes')">Yes</label>
                                         <input type="radio" class="btn-check" name="override" id="overrideNo" value=0 checked>
                                         <label class="btn btn-outline-primary" for="btnradio4" onclick="overrideYesNo('no')">No</label>
                                     </div>
                                 </div>
                             </div>
                         </div>
                     </div> -->
                 </div>
                 <div class="mb-3" id="overrideYesNo">
                     <div class="row">
                         <div class="col-md-6">
                             <label for="defaultSelect" class="form-label">Override Partner's Name</label>
                             <select id="" class="js-example-basic-multiple1" name="override_partner[]" multiple="multiple" style="width: 100%">
                                 <?php foreach ($overrideClients as $val) { ?>
                                     <option value="<?= $val->id; ?>"><?= $val->first_name . ' ' . $val->last_name; ?></option>
                                 <?php } ?>

                             </select>
                         </div>
                         <div class="col-md-6">
                             <label class="form-label" for="basic-icon-default-phone">Override Percentage</label>
                             <div class="input-group input-group-merge">
                                 <span id="basic-icon-default-company2" class="input-group-text">%</span>
                                 <input class="form-control" type="number" placeholder="Override Percentage" name="override_percentage" id="" autocomplete="off">
                             </div>
                         </div>
                     </div>
                 </div>
                 <input type="hidden" name="store_client" value="client">
                 <div class="text-end">
                     <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                         Close
                     </button>
                     <button type="submit" class="btn btn-primary">Add Client</button>
                 </div>
                 <?= $this->Form->end() ?>
             </div>

         </div>
     </div>
 </div>

 <!-- Edit Client  -->

 <div class="modal fade" id="editClient" tabindex="-1" aria-hidden="true"></div>

 <!-- Add deactivation message to user -->

 <div class="modal fade" id="reasonModal" tabindex="-1" aria-hidden="true">
     <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="modalScrollableTitle">Select Message</h5>
                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
             </div>
             <div class="modal-body">
                 <?= $this->Form->create(null, ['method' => 'post', 'action' => 'changeLoginStatus']) ?>

                 <div class="row">
                     <?php /*($user->user_type==1 && $permission==1)?'onclick="return false"':'onclick="openreasonBox('.$val->id.','.$val->status.',\'Users/activeInactiveStaff\')"'; />*/ ?>
                     <div class="col-md-12">
                         <div class="mb-3">
                             <label class="form-label" for="basic-icon-default-company">Reason for deactivation
                                 Name</label>
                             <select class="form-control" name="deactivation_reason" style="width: 100%" onchange="showInput(this)">

                                 <?php foreach ($deactivation_templates as $dt) { ?>
                                     <option value="<?= $dt->id; ?>"><?= $dt->templates; ?>
                                     </option>
                                 <?php } ?>
                             </select>
                         </div>
                     </div>

                 </div>
                 <div class="row customReason" style="display:none;">
                     <div class="col-md-12">
                         <div class="mb-3">
                             <label for="html5-tel-input" class="col-form-label">Custom Reason</label>
                             <div class="input-group input-group-merge">

                                 <input class="form-control" type="text" name="custom_reason" id="custom_reason" />
                             </div>
                         </div>
                     </div>
                 </div>
                 <input type="hidden" name="client_id" id="client_id" />

                 <div class="text-end">
                     <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                         Close
                     </button>
                     <button type="submit" class="btn btn-primary">Submit</button>
                 </div>
                 <?= $this->Form->end() ?>
             </div>

         </div>
     </div>
 </div>

 <div class="modal fade" id="showdeactivation" tabindex="-1" aria-hidden="true">
     <div class="modal-dialog" role="document">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title deatitle" id="modalScrollableTitle"></h5>
                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
             </div>
             <div class="modal-body">


                 <div class="row">
                     <table id="example" class="display table table-sm text-center" style="width:100%">
                         <thead>
                             <tr>
                                 <th class="deahed"></th>

                             </tr>
                         </thead>
                         <tbody class="table-border-bottom-0 dealist">



                         </tbody>
                     </table>
                 </div>

             </div>

         </div>
     </div>
 </div>

 <?= $this->Html->script('client') ?>