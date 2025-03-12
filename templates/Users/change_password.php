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
             <h4 class="fw-bold m-0 p-0">Change Password</h4>
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
                     <a class="nav-link" href="<?= ECOM360 ?>/account-setting"><i class="bx bx-user me-1"></i>
                         Account</a>
                 </li>
                 <li class="nav-item">
                     <a class="nav-link active" href="javascript:void(0);"><i class="bx bx bxs-key me-1"></i> Change
                         Password</a>
                 </li>
                 <?php if($user->user_type == 1) :?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= ECOM360 ?>/manage-notification"><i class="bx bx bxs-cog me-1"></i>
                        Manage Notification</a>
                    </li>
                 <?php endif;?>
             </ul>
             <div class="card mb-4">
                 <h5 class="card-header">Change password</h5>
                 <?= $this->Flash->render('passChangeErr') ?>
                 <?= $this->Flash->render('passChange') ?>
                 <hr class="my-0" />
                 <div class="card-body">
                     <?= $this->Form->create(null, ['action' => 'Users/changePassword']) ?>
                     <div class="col-md-6 offset-md-3">
                         <div class="mb-3 col-md-12 form-password-toggle">
                             <div class="d-flex justify-content-between">
                                 <label class="form-label" for="password">Old Password</label>
                             </div>
                             <div class="input-group input-group-merge">
                                 <input type="password" id="oldPassword" class="form-control" name="old_password"
                                     placeholder="Old Password" aria-describedby="password" autocomplete="off"
                                     onblur="oldPasswordMatch()" required>
                                 <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                             </div>
                             <span id="passErr" style="color: red;"></span>
                         </div>

                         <div class="mb-3 col-md-12 form-password-toggle">
                             <div class="d-flex justify-content-between">
                                 <label class="form-label" for="password">New Password</label>
                             </div>
                             <div class="input-group input-group-merge">
                                 <input type="password" id="newPassword" class="form-control" name="new_password"
                                     placeholder="New Password" aria-describedby="password" autocomplete="off">
                                 <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                             </div>
                         </div>

                         <div class="mb-3 col-md-12 form-password-toggle">
                             <div class="d-flex justify-content-between">
                                 <label class="form-label" for="password">Re Enter New Password</label>
                             </div>
                             <div class="input-group input-group-merge">
                                 <input type="password" id="conPassword" class="form-control" name="confirm_password"
                                     placeholder="Confirm New Password" aria-describedby="password" autocomplete="off">
                                 <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                             </div>
                         </div>
                     </div>
                     <div class="col-md-6 offset-md-3 mt-2">
                         <button type="submit" class="btn btn-primary me-2">Save changes</button>
                         <!-- <button type="reset" class="btn btn-outline-secondary">Cancel</button> -->
                     </div>
                     <?= $this->Form->end() ?>
                 </div>
             </div>
         </div>
     </div>
 </div>
 <!-- / Content -->

 <script>
function oldPasswordMatch() {
    let oldPass = $('#oldPassword').val();
    const token = $('input[name="_csrfToken"]').attr('value');
    // Both syntax are used for get token
    //  'X-CSRF-Token': <?= json_encode($this->request->getAttribute('csrfToken')); ?>,
    $.ajax({
        url: `${baseUrl}/Users/oldPasswordMatch`,
        method: 'put',
        headers: {
            'X-CSRF-Token': token,
        },
        data: {
            password: oldPass
        },
        success: function(res) {
            if (res == 0) {
                $('#passErr').text(`* Old Password doesn't match.`);
                // $('#oldPassword').val('');
            } else {
                $('#passErr').text(``);
                $('#oldPassword').val(oldPass);
            }
        }
    });
}
 </script>