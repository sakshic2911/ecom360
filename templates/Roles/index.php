 <!-- Content -->
 <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">
     <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
         <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
             <i class="bx bx-menu bx-sm"></i>
         </a>
     </div>
     <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
         <div class="row d-flex align-items-center">
             <h4 class="fw-bold m-0 p-0">Roles</h4>
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

     <!-- Data Table -->
     <div class="card">
         <div class="card-header d-flex justify-content-between align-items-center">
             <h5 class="mb-0">Roles</h5>
             <?php if(($user->user_type==1 && $permission==2) || $user->user_type==0) { ?>
             <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#basicModal">
                 <span class="tf-icons bx bx-user-circle"></span>&nbsp; Add Roles
             </button>
             <?php } ?>
         </div>
         <?= $this->Flash->render('roleSave') ?>
         <?= $this->Flash->render('roleUpdate') ?>
         <div class="table-responsive text-nowrap">
             <div class="container">
                 <table id="example1" class="display table table-sm" style="width:100%">
                     <thead>
                         <tr>
                             <th>Role Name</th>
                             <th>Actions</th>
                         </tr>
                     </thead>
                     <tbody class="table-border-bottom-0">
                         <?php foreach ($roleName as $value) : ?>
                             <tr>
                                 <td><i class="fab fa-angular fa-lg text-danger me-3"></i> <strong><?= ucfirst($value->role_name) ?></strong></td>
                                 
                                 <td>
                                     <div class="dropdown">
                                         <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                             <i class="bx bx-dots-vertical-rounded"></i>
                                         </button>
                                         <?php if($user->user_type==1 && $permission==1) { $d = 'View';} else{ $d= 'Edit';} ?>
                                         <div class="dropdown-menu">
                                             <a class="dropdown-item" href="javascript:void(0);" onclick="editRoles(<?= $value->id ?>,'<?= $d;?>')"><i class="bx bx-edit-alt me-1"></i>
                                             <?= $d;?></a>
                                             <!-- <a class="dropdown-item" href="javascript:void(0);" onclick="deleteRole(<?= $value->id ?>)"><i class="bx bx-trash me-1"></i>
                                                 Delete</a> -->
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


 <!-- Add Role -->
 <div class="modal fade" id="basicModal" tabindex="-1" aria-hidden="true">
     <div class="modal-dialog" role="document">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="exampleModalLabel1">Add Roles</h5>
                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
             </div>
             <div class="modal-body">
                 <?= $this->Form->create(null, ['method' => 'post', 'action' => 'add_roles']) ?>
                 <div class="mb-3">
                     <label class="form-label" for="basic-icon-default-rolename">Role Name</label>
                     <div class="input-group input-group-merge">
                         <span id="basic-icon-default-rolename" class="input-group-text"><i class="bx bx-user"></i></span>
                         <input type="text" class="form-control" name="role_name" id="basic-icon-default-rolename" placeholder="Role" aria-label="Role" aria-describedby="basic-icon-default-fullname2" minlength="3" maxlength="20" required>
                     </div>
                     <span id="roleError">

                     </span>
                 </div>
                 <div>
                     <label class="form-label" for="basic-icon-default-rolename">Permissions</label>
                     <div class="table-responsive text-nowrap">
                         <div class="container">
                             <table id="example1" class="display table table-sm" style="width:100%">
                                 <thead>
                                     <tr>
                                         <th></th>
                                         <th>Screen Name</th>
                                         <th>View</th>
                                         <th>Edit</th>
                                         <th> N/A</th>
                                     </tr>
                                 </thead>
                                 <tbody class="table-border-bottom-0">
                                 <?php $foldername = null;
													$sno =1; ?>
                                    <?php  foreach ($organizedMenu as $menu) : ?>
                                        <?php if ($menu['folder'] != $foldername): ?>
                                                <tr class="folder-row toggle-button">
                                                    <td><strong><?= $menu['folder']; ?></strong></td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                </tr>
                                           <?php $foldername = $menu['folder'];
                                            $sno =1; ?>
                                        <?php endif; ?>
                                        <?php foreach ($menu['items'] as $val): ?>
                                            <tr class="menu-row">
                                                <td></td>
                                                <td>
                                                    <i class="fab fa-angular fa-lg text-danger me-3"></i> <?= ($val->menu_name == 'Onboarding Clients') ? 'Walmart Onboarding' : $val->menu_name ?>
                                                </td>
                                                <td>
                                                    <div class="form-check form-check-inline mt-3">
                                                        <input class="form-check-input" type="radio" name="<?= $val->id . '/' . $val->menu_name ?>" id="<?= $val->menu_name ?>" value='1' />
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-check form-check-inline mt-3">
                                                        <input class="form-check-input" type="radio" name="<?= $val->id . '/' . $val->menu_name ?>" id="<?= $val->menu_name ?>" value='2' />

                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-check form-check-inline mt-3">
                                                        <input class="form-check-input" type="radio" name="<?= $val->id . '/' . $val->menu_name ?>" id="<?= $val->menu_name ?>" value='3' checked />

                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach ?>
                                     <?php endforeach ?>
                                 </tbody>
                             </table>
                         </div>
                     </div>
                     <div class="text-end">
                         <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                             Close
                         </button>
                         <button type="submit" class="btn btn-primary me-2">Add Role</button>
                     </div>
                     <?= $this->Form->end() ?>
                 </div>
             </div>
             <!-- <div class="modal-footer">
                 <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                     Close
                 </button>
             </div> -->
         </div>
     </div>
 </div>

 <!-- Edit Role -->

 <div class="modal fade" id="editRole" tabindex="-1" aria-hidden="true">
     
 </div>


 <script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>

 <script>
     // Soft Delete Role Name
     function deleteRole(id) {
         if (confirm('Do you realy delete this role.')) {
             $.ajax({
                 url: "<?= $this->Url->build(['controller' => 'Roles', 'action' => 'delete']) ?>",
                 method: "get",
                 data: {
                     id: id
                 },
                 success: function(res) {
                     if (res == 1) {
                         location.reload();
                     }
                 }
             })

         } else {
             location.reload();
         }
     }

    
 </script>
    <script>
        $(document).ready(function () {
            $('.table').on('click', '.folder-row', function () {
                var menuRows = $(this).nextUntil('.folder-row');

                if (menuRows.is(':visible')) {
                    menuRows.hide();
                    $(this).removeClass('folder-expanded');
                } else {
                    $('.menu-row').hide();
                    $('.folder-row').removeClass('folder-expanded');
                    menuRows.show();
                    $(this).addClass('folder-expanded');
                }
            });
        });
    </script>


