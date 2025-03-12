<div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="exampleModalLabel1">Edit Staff
                 </h5>
                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
             </div>
             <div class="modal-body">
                 <?= $this->Form->create(null, ['method' => 'post', 'action' => 'edit-internal-staff', 'enctype' => 'multipart/form-data','id'=>'staffForm']) ?>
                 <div class="row">
                     <div class="col-md-6">
                         <div class="mb-3">
                             <label class="form-label" for="basic-icon-default-firstname">First Name</label>
                             <div class="input-group input-group-merge">
                                 <span id="basic-icon-default-firstname2" class="input-group-text"><i
                                         class="bx bx-user"></i></span>
                                 <input type="text" class="form-control" id="firstName" name="first_name" value="<?= $staffData[0]->first_name;?>"
                                     placeholder="First Name" aria-describedby="basic-icon-default-fullname2" required
                                     autocomplete="off">
                             </div>
                             <input type="hidden" name="staff_id" id="editId" value="<?= $staffData[0]->id;?>">
                         </div>
                     </div>
                     <div class="col-md-6">
                         <div class="mb-3">
                             <label class="form-label" for="basic-icon-default-lastname">Last Name</label>
                             <div class="input-group input-group-merge">
                                 <span id="basic-icon-default-lastname2" class="input-group-text"><i
                                         class="bx bx-user"></i></span>
                                 <input type="text" class="form-control" id="lastName" name="last_name" value="<?= $staffData[0]->last_name;?>"
                                     placeholder="Last Name" aria-describedby="basic-icon-default-fullname2"
                                     autocomplete="off">
                             </div>
                         </div>
                     </div>
                 </div>
                 <div class="mb-3">
                     <label class="form-label" for="basic-icon-default-email">Email</label>
                     <div class="input-group input-group-merge">
                         <span class="input-group-text"><i class="bx bx-envelope"></i></span>
                         <input type="email" id="editEmail" name="email" class="form-control" value="<?= $staffData[0]->email;?>" placeholder="email"
                             aria-label="Rajiv.doe@example.com" aria-describedby="basic-icon-default-email2" required
                             autocomplete="off" onblur="editEmailCheck()">
                     </div>
                     <span class="mt-1" style="color: red;" id="editEmailError">

                     </span>
                     
                 </div>
                 <div class="mb-3">
                     <label class="form-label" for="basic-icon-default-phone">Phone No</label>
                     <div class="input-group input-group-merge">
                         <span id="basic-icon-default-phone2" class="input-group-text"><i
                                 class="bx bx-phone"></i></span>
                         <input type="text" id="contactNo" name="contact_no" value="<?= $staffData[0]->contact_no;?>" class="form-control phone-mask phoneFormat"
                             placeholder="Phone Number" aria-label="658 799 8941"
                             aria-describedby="basic-icon-default-phone2" onkeyup="formatPhone(this)"
                             autocomplete="off">
                     </div>
                 </div>

                 
                 <div class="mb-3">
                     <label for="defaultSelect" class="form-label">Role</label>
                     <select class="form-select" name="role_id" id="roleId" required onchange = "checkRole(this)">
                         <option>Select Role</option>
                         <?php foreach ($roles as $val) : ?>
                         <option value=<?= $val->id ?> <?=($staffData[0]->role_id==$val->id)?'selected':'';?>><?= $val->role_name ?></option>
                         <?php endforeach ?>
                     </select>
                </div>
                <!-- <div class="mb-3 account_manager" style="<?= ($staffData[0]->role_id == 16)?'':'display:none;';?>">
                     <label for="defaultSelect" class="form-label">
                        Account Manager</label>
                     <select id="defaultSelect" name="account_manager[]" class="js-example-multiple-issue2" multiple="multiple" style="width: 100%">
                         <option value="">Select Account Manager</option>
                         <?php foreach ($account_manager as $val) : ?>
                         <option value=<?= $val->id ?> <?=(in_array($val['id'],$staffManagerids))?'selected':'';?>><?= $val->name ?></option>
                         <?php endforeach; ?>
                     </select>
                 </div> -->
                 <div class="mb-3 account_manager" style="<?= ($staffData[0]->role_id == 16)?'':'display:none;';?>">
                     <label for="defaultSelect" class="form-label">
                        Primary Account Manager</label>
                     <select id="primaryAccountManager" class="form-select" name="primary_account_manager" style="width: 100%" onchange = "showTempManager(this,'editTempAccountManager',<?php echo $staffData[0]->id; ?>)">
                         <option value="">Select Primary Account Manager</option>
                         <?php foreach ($account_manager as $val) : ?>
                         <option value=<?= $val->id ?> <?=($staffData[0]->parent_role==$val->id)?'selected':'';?>><?= $val->name ?></option>
                         <?php endforeach; ?>
                     </select>
                   </div>
                  <div class="mb-3 account_manager" style="<?= ($staffData[0]->role_id == 16)?'':'display:none;';?>">
                     <label for="defaultSelect" class="form-label">
                     Temporary Account Manager</label>
                     <select id="editTempAccountManager" class="js-example-multiple-issue2 w-100" multiple="multiple" name="temporary_account_manager[]" style="width: 100%">
                        <?php foreach ($temp_account_manager as $val) : ?>
                         <option value=<?= $val->id ?> <?=(in_array($val['id'],$staffManagerids))?'selected':'';?>><?= $val->name ?></option>
                         <?php endforeach; ?>
                     </select>
                  </div>
                <div class="mb-3 manager_bio" style="<?= ($staffData[0]->role_id == 15)?'':'display:none;';?>">
                     <label for="defaultSelect" class="form-label">Senior Manager</label>
                     <select id="defaultSelect" class="form-select" name="senior_manager">
                         <option value="0">Select Senior Manager</option>
                         <?php foreach ($senior_manager as $val) : ?>
                         <option value=<?= $val->id ?> <?=($staffData[0]->parent_role==$val->id)?'selected':'';?>><?= $val->name ?></option>
                         <?php endforeach; ?>
                     </select>
                 </div>
                <div class="mb-3 manager_bios" style="<?= ($staffData[0]->role_id == 15 || $staffData[0]->role_id == 20)?'':'display:none;';?>">
                     <label for="defaultSelect" class="form-label">Store Type</label>
                        <select class="js-example-multiple-issue2 form-select" multiple="multiple" name="store_type[]" style="width: 100%">
                            <option value="1"  <?=($staffData[0]->amazon==1)?'selected':'';?>>Amazon</option>
                            <option value="2" <?=($staffData[0]->walmart==1)?'selected':'';?>>Walmart</option>
                        </select>
                 </div>
                <div class="mb-3 manager_bios" style="<?= ($staffData[0]->role_id == 15 || $staffData[0]->role_id == 20)?'':'display:none;';?>">
                     <label class="form-label" for="bio">Bio</label>
                     <div class="input-group input-group-merge">
                         <textarea class="form-control" id="manager_bio" rows="3" name="manager_bio"><?=$staffData[0]->manager_bio;?></textarea>
                     </div>
                 </div>
                 <!-- <div class="mb-3 manager_bios" style="<?= ($staffData[0]->role_id == 15 || $staffData[0]->role_id == 20) ? '' : 'display:none;';?>">
                     <label class="form-label" for="bio">Embed Code</label>
                     <div class="input-group input-group-merge">
                         <textarea class="form-control" id="embed_code" rows="3" name="embed_code"><?=$staffData[0]->embed_code;?></textarea>
                     </div>
                 </div> -->
                 <div class="mb-3 manager_bios" style="<?= ($staffData[0]->role_id == 15 || $staffData[0]->role_id == 20)?'':'display:none;';?>">
                    <label class="form-label" for="bio">Client Capacity</label>
                    <div class="input-group input-group-merge">
                        <input type="text" id="store_capacity" class="form-control"
                        placeholder="Store Capacity" name="store_capacity" value="<?=$staffData[0]->store_capacity;?>">
                    </div>
                </div>
                <div class="mb-3 manager_bios" style="<?= ($staffData[0]->role_id == 15 || $staffData[0]->role_id == 20) ? '' : 'display:none;';?>">
                       <label class="form-label" for="bio">Calender Id</label>
                       <div class="input-group input-group-merge">
                           <input type="text" id="calender_id" class="form-control"
                           placeholder="Calender Id" name="calender_id" value="<?=$staffData[0]->calender_id;?>">
                       </div>
                 </div>
                 <div class="row">
                     <div class="col-md-6">
                        <div class="mb-3 manager_bios" style="<?= ($staffData[0]->role_id == 15 || $staffData[0]->role_id == 20)?'':'display:none;';?>">
                            <label class="form-label" for="bio">Store Assign</label>
                            <div class="input-group input-group-merge">
                            <input type="text" id="store_assign" class="form-control"
                                    placeholder="Store Assign" name="store_assign" value="<?=$store_assign_count;?>" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3 manager_bios" style="<?= ($staffData[0]->role_id == 15 || $staffData[0]->role_id == 20)?'':'display:none;';?>">
                            <label class="form-label" for="bio">Active Store</label>
                            <div class="input-group input-group-merge">
                                <input type="text" id="active_store" class="form-control"
                                placeholder="Active Store" name="active_store" value="<?=$active_store_assign_count;?>" readonly>
                            </div>
                        </div> 
                    </div>
                </div>
                
                <div class="mb-1 storeRadio store_per" style="<?= ($staffData[0]->role_id == 16)?'display:none':'';?>">
                   <label class="form-label">Store Permission</label><br>
                       <div class="form-check form-check-inline mb-1">
                           <input class="form-check-input" type="radio" <?= ($staffData[0]->store_permission==1)?'checked':'';?> id="inlineRadio1"
                               name="store_permission" value="1" onclick="StorePermission('no')">
                           <label class="form-check-label fw-semibold"
                               for="inlineRadio1" onclick="StorePermission('no')">See All Store</label>
                       </div>
                       <div class="form-check form-check-inline">
                           <input class="form-check-input" type="radio" id="inlineRadio2"
                               name="store_permission" value="2" <?= ($staffData[0]->store_permission==2)?'checked':'';?> onclick="StorePermission('yes')">
                           <label class="form-check-label fw-semibold"
                               for="inlineRadio2" onclick="StorePermission('yes')">See Selected Store</label>
                       </div>                  
               </div>

               <?php if($staffData[0]->store_permission==2){ ?>
               <div class="mb-3 selectedstore store_per">
                   <!--kajal Code Start -->
                   <label for="defaultSelect" class="form-label">Store Group</label>
                    <select name="selected_store[]" class="js-example-multiple-issue2 allstore" multiple="multiple" style="width: 100%">
                    <option value="0">Select Groups</option>
                    <?php foreach ($groupData as $val) : ?>
                        <option value="<?php echo $val['id']; ?>" <?=(in_array($val['id'],$staffStoreids))?'selected':'';?>>
                        <?php echo $val['group_name']; ?>
                        </option>
                    <?php  endforeach;?>
                    </select>
                   <!--Kajal Code End   -->
               </div>

               <?php } else { ?>
               
               <div class="mb-3 selectedstore" style="display:none;">
                   <label class="form-label" for="basic-icon-default-email">Store Group</label>
                   <select name="selected_store[]" class="js-example-multiple-issue2 allstore" multiple="multiple" style="width: 100%">
                       <option value="">Select Group</option>
                        <?php foreach ($groupData as $val) : ?>
                        <option value=<?= $val->id ?>><?= $val->group_name ?></option>
                        <?php endforeach; ?>
                    </select>
               </div>

               <?php } ?>
                <div class="mb-3">
                    <label for="defaultSelect" class="form-label">Department</label>
                     <select name="issue_type[]" id="issueTypeId" class="js-example-multiple-issue2" multiple="multiple" style="width: 100%">
                     <?php foreach ($staffData as $val) : 
                        $issue[] = $val->issue_type;
                     endforeach;?>
                         <option value="0">Select Department</option>
                         <option value="1" <?=(in_array(1,$issue))?'selected':'';?>>General Support</option>
                         <option value="2" <?=(in_array(2,$issue))?'selected':'';?>>Billing</option>
                         <option value="3" <?=(in_array(3,$issue))?'selected':'';?>>Refer a friend</option>
                         <option value="4" <?=(in_array(4,$issue))?'selected':'';?>>Onboarding a new store</option>
                         <option value="5" <?=(in_array(5,$issue))?'selected':'';?>>Portal Support</option>
                         <option value="6" <?=(in_array(6,$issue))?'selected':'';?>>Inventory management</option>
                         <!-- <option value="7" <?=(in_array(7,$issue))?'selected':'';?>>TeamViewer/device is offline</option> -->
                         <option value="8" <?=(in_array(8,$issue))?'selected':'';?>>E-comm 360/E-comm Tax Service</option>
                         <!-- <option value="9" <?=(in_array(9,$issue))?'selected':'';?>>Multilogin</option> -->
                         <option value="10" <?=(in_array(10,$issue))?'selected':'';?>>Walmart Stores</option>
                         <option value="11" <?=(in_array(11,$issue))?'selected':'';?>>Case management</option>
                         <option value="12" <?=(in_array(12,$issue))?'selected':'';?>>Store Transfer</option>
                         <option value="13" <?=(in_array(13,$issue))?'selected':'';?>>Amazon Questions</option>
                     </select>
                 </div>
                 
                 <div class="mb-3">
                     <label for="formFile" class="form-label">Upload Profile Image</label>
                     <input class="form-control" type="file" name="image" id="formFile">
                     <div class="form-text">Upload accepting img, png, jpeg, jpg and other popular image formats</div>
                 </div>
                 <div id="img" class="mt-2">
                    <img class="rounded-circle" src="<?php echo ECOM360 . '/webroot/img/ECOM360/avatars/'.$val->image ?>" alt="Staff Photo" width="60" height="70">
                </div>
                 <div class="text-end">
                     <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                         Close
                     </button>
                     <button type="submit" class="btn btn-primary edtsbmt">Edit Staff</button>
                 </div>
                 <?= $this->Form->end() ?>
             </div>
         </div>
     </div>