<!-- Content -->
<?= $this->Html->css("assets/css/kanban") ?>
<style>
   .focusedRow
{
   font-weight: bold;
   text-shadow: 1px 1px 0px #EEE;
   color: #111;   
}
   </style>
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
                    <?= $this->Html->image("dropshipping/avatars/$loginUser->image", ["class" => "w-px-30 h-auto rounded-circle", "alt" => "user"]) ?>
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="#">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar avatar-online">
                                    <?= $this->Html->image("dropshipping/avatars/$loginUser->image", ["class" => "w-px-30 h-auto rounded-circle", "alt" => "user"]) ?>
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
<div class="row ms-2">
         <div class="col-md-12">
             <ul class="nav nav-pills flex-column flex-md-row mb-3">
                 <li class="nav-item">
                     <a class="nav-link" href="<?= DROPSHIPPING ?>/tickets"><i class="bx bx-detail me-1"></i> Tickets</a>
                 </li>
                 
                 <li class="nav-item">
                     <a class="nav-link active" href="javascript:void(0);" style="background-color:#186cff !important;"><i class="bx bx-detail me-1"></i>
                         Archived Tickets</a>
                 </li>
                
             </ul>
         </div>
     </div>
    <!-- Data Table -->
    <div class="card">
        <div class="card-header">
        <?= $this->Form->create(null, ['method'=>'post']) ?>
            <div class="row align-items-center">
                <?php if ($loginUser->user_type != 2) : //onchange="fetchStore(this,'storeId')"?>
                    <div class="col-lg-2 col-sm-12">
                        <div class="mb-2">
                            <label class="form-label">Client Name</label>
                            <div class="input-group input-group-merge">
                                <select class="js-example-client" name="client_id" required>
                                    <option value="0">Please Select</option>
                                    <?php foreach ($client as $clientVal) : ?>
                                    <option value="<?= $clientVal->id ?>" <?php
                                        if (isset($clientId)) {
                                            if ($clientId == $clientVal->id) {
                                                echo 'selected';
                                            }
                                        }
                                    ?>>
                                        <?= $clientVal->first_name ?>
                                        <?= $clientVal->last_name ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="col-lg-2 col-sm-12 mb-2">
                    <label>Date Opened</label>
                    <input type="date" name="start_date" id="start_date" class="form-control" onclick="this.showPicker()" value="<?=$start_date?>"/>
                </div>

                <div class="col-lg-2 col-sm-12 mb-2">
                    <label>Date Closed</label>
                    <input type="date" name="end_date" id="end_date" class="form-control" onclick="this.showPicker()" value="<?=$end_date?>"/>
                </div>

                <?php if(($loginUser->user_type==1 && $lgPermission==1) || $loginUser->user_type==0) {  ?>
                    <div class="col-lg-2 col-sm-12">
                        <div class="mb-2 w-100">
                            <label class="form-label">Assignee</label>
                            <div class="input-group input-group-merge">
                            <select id="support_user" class="form-control js-example-client"
                                aria-label=".form-select-lg example" name="assign_to">
                                <option value="0">Select Staff</option>
                                <?php foreach($supportStaff as $s) { ?>
                                    <option value="<?= $s->id;?>" <?= $assignto == $s->id ? 'selected' : '' ?>>
                                    <?= ucfirst($s->first_name).' '.ucfirst($s->last_name);?>
                                    </option>
                                <?php } ?>
                            </select>                                          
                            </div>
                        </div>
                    </div>
                <?php } ?>

                <div class="col-lg-2 col-sm-12">
                    <div class="mb-2">
                        <label class="form-label">Department</label>
                        <div class="input-group input-group-merge">
                            <select class="js-example-client" name="department_id" required>
                                <!-- <option value="0">Please Select</option> -->
                                <?php foreach ($issues as $dkey => $dep) : ?>
                                <option value="<?= $dkey ?>" <?php
                                    if (isset($departmentId)) {
                                        if ($departmentId == $dkey) {
                                            echo 'selected';
                                        }
                                    }
                                ?>>
                                    <?= empty($dep) ? "Please Select" : $dep ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-lg-2 col-sm-12">
                    <!-- Account manager filter -->
                    <div class="mb-2 w-100">
                        <label class="form-label">Watcher</label>
                        <div class="input-group input-group-merge">
                            <select id="watcher_id" class="form-control js-example-client" name="watcher_id">
                                <option value="0">Select Watcher</option>
                                <?php foreach($supportStaff as $s) { ?>
                                    <option value="<?= $s->id;?>" <?= $watcherId == $s->id ? 'selected' : '' ?>>
                                    <?= ucfirst($s->first_name).' '.ucfirst($s->last_name);?>
                                    </option>
                                <?php } ?>
                            </select>                                          
                        </div>
                    </div>
                </div>
                
               <div class="col-lg-2 col-sm-12">
                   <div class="mt-4">
                       <button type="submit" class="btn btn-secondary w-100">
                           Apply filter
                       </button>
                   </div>
               </div>

               <div class="col-lg-2 col-sm-12">
                   <div class="mt-4">
                       <a href="<?= DROPSHIPPING ?>/archive-tickets" class="btn btn-info">
                            Clear
                        </a>
                   </div>
               </div>
               
               <!-- <div class="col-lg-2 col-sm-12">
                   <div class="mt-4 text-end">
                       <button type="button" class="btn btn-primary w-100" data-bs-toggle="modal"
                           data-bs-target="#basicModal">
                           Add Ticket
                       </button>
                   </div>
               </div> -->
            </div>
            <?= $this->Form->end() ?>
        </div>
        <?= $this->Flash->render('saveTickets') ?>
        <?= $this->Flash->render('errorMsg') ?>
        <div class="table-responsive text-nowrap">
            <div class="container">
                <table id="example1" class="display table table-sm" style="width:100%">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Client Name</th>
                            <th>Department</th>
                            <th>Opened  Date</th>
                            <th>Closed Date</th>   
                            <th>Title</th>
                                                    
                        </tr>
                    </thead>
                    <tbody id="myTable" class="table-border-bottom-0">
                        <?php
                           foreach ($tickets as $t) :
                            $class="";
                           ?>
                        <tr style="cursor: pointer;" class="<?= $class;?>" onclick="kanban(<?= $t->id ?>)">
                            <td></td>
                            <td><?= $t->client ?></td>
                            <td><?= $issues[$t->issue_type]; ?></td>
                            <td data-sort="<?= $t->created_date ? date('YmdHis', strtotime($t->created_date)) : '' ?>"><?= $t->created_date?></td>
                            <td data-sort="<?= $t->closed_date ? date('YmdHis', strtotime($t->closed_date)) : '' ?>"><?= $t->closed_date?></td>   
                            <td><?= $t->title?></td>
                                                    
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

<!-- Modal -->
<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="kanban-modal-label-1"
               aria-hidden="true" id="kanban-modal-1">

           </div>


<?= $this->Html->script(['kanban/anchorjs/anchor.min.js', 'kanban/draggable/draggable.bundle.legacy.js', 'kanban/fontawesome/all.min.js', 'kanban/lodash/lodash.min.js', 'assets_js/kanban.js']) ?>

<script>
function kanban(id) {
   
   $.ajax({
       url: "<?= $this->Url->build(['controller' => 'Tickets', 'action' => 'archiveDetail']) ?>",
       method: "GET",
       data: {
           id: id
       },
       success: function(res) {
           $('#kanban-modal-1').html(res);
           $('#kanban-modal-1').modal('show');
       }
   })
}

// function commentAddFile() {
  

//   $("#addFile").submit(function(e) {
//   e.preventDefault();    
//   var formData = new FormData(this);

//   $.ajax({
//       url: "<?= $this->Url->build(['controller' => 'Tickets', 'action' => 'addMoreFile']) ?>",
//       type: 'POST',
//       data: formData,
//       success: function (res) {

//           $('#imgBasicModal').modal('hide');
//           let commentData = JSON.parse(res);
//           var design = '<a class="text-decoration-none" href="<?= DROPSHIPPING;?>/img/tickets_file/'+commentData.file+'" target="_blank">'+commentData.file+'</a>';
          
//           $('.newAtc').append(design);
          
//       },
//       cache: false,
//       contentType: false,
//       processData: false
//   });
// });

  
// }
</script>