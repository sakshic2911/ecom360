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
             <h4 class="fw-bold m-0 p-0">Update Details</h4>
         </div>
         <ul class="navbar-nav flex-row align-items-center ms-auto">

             <!-- User -->
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                 <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                     <div class="avatar avatar-online">
                     <?= $this->Html->image("dropshipping/avatars/$user->image", ["class" => "w-px-30 h-auto rounded-circle", "alt" => "user"]) ?>
                     </div>
                 </a>
                 <ul class="dropdown-menu dropdown-menu-end">
                     <li>
                         <a class="dropdown-item" href="#">
                             <div class="d-flex">
                                 <div class="flex-shrink-0 me-3">
                                     <div class="avatar avatar-online">
                                     <?= $this->Html->image("dropshipping/avatars/$user->image", ["class" => "w-px-30 h-auto rounded-circle", "alt" => "user"]) ?>
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
             <?= $this->Form->create(null, ['method' => 'post', 'action' => 'Users/accountSetting', 'enctype' => 'multipart/form-data']) ?>
             <ul class="nav nav-pills flex-column flex-md-row mb-3">
                 <li class="nav-item">
                     <a class="nav-link active" href="javascript:void(0);"><i class="bx bx-user me-1"></i> Account</a>
                 </li>
                 <li class="nav-item">
                     <a class="nav-link" href="<?= DROPSHIPPING ?>/change-password"><i class="bx bx bxs-key me-1"></i>
                         Change Password</a>
                 </li>
                <?php if($user->user_type == 1) :?>
                <li class="nav-item">
                    <a class="nav-link" href="<?= DROPSHIPPING ?>/manage-notification"><i class="bx bx bxs-cog me-1"></i>
                        Manage Notification</a>
                </li>
                <?php endif;?>
             </ul>
             <div class="card mb-4">
                 <h5 class="card-header">Profile Details</h5>
                 <!-- Account -->
                 <div class="card-body">
                     <div class="d-flex align-items-start align-items-sm-center gap-4">
                         <!-- <img src="assets/img/avatars/1.png" alt="user-avatar" class="d-block rounded" height="100" width="100" id="uploadedAvatar" /> -->
                         <?php
                            if ($img != NULL) {
                                echo $this->Html->image("dropshipping/avatars/$img", ["alt" => "user-avatar", "height" => "100", "width" => "100", "class" => "d-block rounded", "id" => "uploadedAvatar"]);
                            } else {
                                echo $this->Html->image("dropshipping/avatars/s49847avatar.png", ["alt" => "user-avatar", "height" => "100", "width" => "100", "class" => "d-block rounded", "id" => "uploadedAvatar"]);
                            }
                            ?>
                         <div class="button-wrapper">
                             <label for="upload" class="btn btn-primary me-2 mb-4" tabindex="0">
                                 <span class="d-none d-sm-block">Upload new photo</span>
                                 <i class="bx bx-upload d-block d-sm-none"></i>
                                 <input type="file" id="upload" name="image" class="account-file-input" hidden
                                     accept=".jpg, .jpeg, .png, .JPG, .JPEG, .PNG" />
                             </label>
                             <!-- <button type="button" class="btn btn-outline-secondary account-image-reset mb-4">
                                 <i class="bx bx-reset d-block d-sm-none"></i>
                                 <span class="d-none d-sm-block">Reset</span>
                             </button> -->

                             <p class="text-muted mb-0">Allowed JPG, GIF or PNG. Max size of 800K</p>
                         </div>
                     </div>
                 </div>
                 <hr class="my-0" />
                 <?= $this->Flash->render('accountUpdate') ?>
                 <?= $this->Flash->render('imgErr') ?>
                 <div class="card-body">
                     <div class="row">
                         <div class="mb-3 col-md-6">
                             <label for="firstName" class="form-label">First Name</label>
                             <input class="form-control" type="text" id="firstName" name="first_name"
                                 value="<?= $userData[0]->first_name ?>" autocomplete="off" placeholder="First Name"
                                 required readonly/>
                         </div>
                         <div class="mb-3 col-md-6">
                             <label for="lastName" class="form-label">Last Name</label>
                             <input class="form-control" type="text" name="last_name" id="lastName"
                                 value="<?= $userData[0]->last_name ? $userData[0]->last_name : '' ?>"
                                 autocomplete="off" placeholder="Last Name" readonly/>
                         </div>
                         <div class="mb-3 col-md-6">
                             <label for="email" class="form-label">E-mail</label>
                             <input class="form-control" type="email" id="email" name="email"
                                 value="<?= $userData[0]->email ?>" placeholder="" readonly />
                         </div>
                         <div class="mb-3 col-md-6">
                             <label class="form-label" for="phoneNumber">Phone Number</label>
                             <div class="input-group input-group-merge">
                                 <span class="input-group-text">US (+1)</span>
                                 <input type="text" id="phoneNumber" name="contact_no" class="form-control phoneFormat"
                                     placeholder="Phone Number"
                                     value="<?= $userData[0]->contact_no ? $userData[0]->contact_no : '' ?>"
                                     autocomplete="off" onkeyup="formatPhone(this)" />
                             </div>
                         </div>
                         <div class="mb-3 col-md-6">
                             <label for="address" class="form-label">Address</label>
                             <input type="text" class="form-control" id="address" name="address" placeholder="Address"
                                 value="<?= $userData[0]->address ? $userData[0]->address : '' ?>" />
                         </div>

                         <?php if($userData[0]->role_id == 15) { ?>
                            <div class="mb-3 col-md-6">
                                <label for="manager_bio" class="form-label">Bio</label>
                               
                                <textarea class="form-control" id="manager_bio" rows="3" name="manager_bio"><?=$userData[0]->manager_bio;?></textarea>
                            </div>
                         <?php } ?>
                     </div>
                     <div class="mt-2">
                         <button type="submit" class="btn btn-primary me-2">Save changes</button>
                        
                     </div>

                 </div>
                 <!-- /Account -->
             </div>
             <?= $this->Form->end() ?>
         </div>
     </div>
 </div>
 <!-- / Content -->

 <script>
'use strict';

function zipCodeValid(obj) {
    let numbers = obj.value.replace(/\D/g, ''),
        char = {
            //  0: '',
            //  3: '',
        };
    obj.value = '';
    for (var i = 0; i < numbers.length; i++) {
        obj.value += (char[i] || '') + numbers[i];
    }
    $("#zipCode").attr("maxlength", "6");
}

document.addEventListener('DOMContentLoaded', function(e) {
    (function() {
        const deactivateAcc = document.querySelector('#formAccountDeactivation');

        // Update/reset user image of account page
        let accountUserImage = document.getElementById('uploadedAvatar');
        const fileInput = document.querySelector('.account-file-input'),
            resetFileInput = document.querySelector('.account-image-reset');
        //  console.log(accountUserImage);
        if (accountUserImage) {
            const resetImage = accountUserImage.src;
            fileInput.onchange = () => {
                if (fileInput.files[0]) {
                    accountUserImage.src = window.URL.createObjectURL(fileInput.files[0]);
                }
            };
            //  resetFileInput.onclick = () => {
            //      fileInput.value = '';
            //      accountUserImage.src = resetImage;
            //  };
        }
    })();
});
 </script>