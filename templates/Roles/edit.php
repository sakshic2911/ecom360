<div class="modal-dialog" role="document">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="exampleModalLabel1">Edit Roles</h5>
                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
             </div>
             <div class="modal-body">
                 <?= $this->Form->create(null, ['method' => 'post', 'action' => 'edit_roles','id'=>'roleForm']) ?>
                 <div class="mb-3">
                     <label class="form-label" for="basic-icon-default-rolename">Role Name</label>
                     <div class="input-group input-group-merge">
                         <span id="basic-icon-default-rolename" class="input-group-text"><i class="bx bx-user"></i></span>
                         <input type="text" class="form-control" name="role_name" id="roleName" placeholder="Role" aria-label="Role" aria-describedby="basic-icon-default-fullname2" minlength="3" maxlength="20" value="<?= $roleData[0]->roleName;?>" required>
                     </div>
                 </div>
                 <div>
                     <label class="form-label" for="basic-icon-default-rolename">Permissions </label>
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
                                 <?php $foldername = null; $sno =1; ?>
                                    <?php foreach ($organizedMenu  as $menu) : ?>
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
                                                    <i class="fab fa-angular fa-lg text-danger me-3"></i> <strong><?= ($val['menu_name'] == 'Onboarding Clients') ? 'Walmart Onboarding' : $val['menu_name'] ?></strong>
                                                </td>
                                                <td>
                                                    <div class="form-check form-check-inline mt-3">
                                                        <input class="form-check-input" type="radio" name="<?= $val['id'] . '/' . $val['menu_name'] ?>" id="<?= $val['menu_name'] ?>1" value='1'
                                                        <?= ($val['permission'] == 1)?'checked':'';?>
                                                        />
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-check form-check-inline mt-3">
                                                        <input class="form-check-input" type="radio" name="<?= $val['id'] . '/' . $val['menu_name'] ?>" id="<?= $val['menu_name'] ?>2" value='2' <?= ($val['permission'] == 2)?'checked':'';?>/>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-check form-check-inline mt-3">
                                                        <input class="form-check-input" type="radio" name="<?= $val['id'] . '/' . $val['menu_name'] ?>" id="<?= $val['menu_name'] ?>3" value='3' <?= ($val['permission'] == 3)?'checked':'';?> />
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach ?>
                                     <?php endforeach ?>
                                 </tbody>
                             </table>
                         </div>
                     </div>
                     <input type="hidden" name="roleId" id="roleUpdateId" value="<?= $roleData[0]->roleId;?>">
                     <div class="text-end">
                         <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                             Close
                         </button>
                         <button type="submit" class="btn btn-primary me-2 edtsbmt">Edit Role</button>
                     </div>
                     <?= $this->Form->end() ?>
                 </div>
             </div>
         </div>
     </div>
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