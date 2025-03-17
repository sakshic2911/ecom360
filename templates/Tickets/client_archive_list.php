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
<div class="row ms-2">
         <div class="col-md-12">
             <ul class="nav nav-pills flex-column flex-md-row mb-3">
                 <li class="nav-item">
                     <a class="nav-link" href="<?= ECOM360 ?>/tickets"><i class="bx bx-detail me-1"></i> Tickets</a>
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
        <div class="card-header"></div>
        <div class="table-responsive text-nowrap">
            <div class="container">
                <table id="example1" class="display table table-sm" style="width:100%">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Issue</th>
                            <th>Title</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody id="myTable" class="table-border-bottom-0">
                        <?php foreach ($tickets as $t) : $class=""; ?>
                        <tr style="cursor:pointer" class="<?= $class;?>" onclick="kanban(<?= $t->id ?>,<?= $t->store_id ?>)">
                            <td></td>
                            <td><?= $issues[$t->issue_type]; ?></td>
                            <td><?= $t->title?></td> 
                            <td data-sort="<?= $t->add_date ? date('YmdHis', strtotime($t->add_date)) : '' ?>"><?= $t->add_date; ?>
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

<!-- Modal -->
<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="kanban-modal-label-1" aria-hidden="true" id="kanban-modal-1">

</div>


<?= $this->Html->script(['kanban/anchorjs/anchor.min.js', 'kanban/draggable/draggable.bundle.legacy.js', 'kanban/fontawesome/all.min.js', 'kanban/lodash/lodash.min.js', 'assets_js/kanban.js']) ?>

<script>
function kanban(id, storeId) {
   $.ajax({
       url: "<?= $this->Url->build(['controller' => 'Tickets', 'action' => 'archiveDetail']) ?>",
       method: "GET",
       data: {
           id: id,
           storeId: storeId
       },
       success: function(res) {
           $('#kanban-modal-1').html(res);
           $('#kanban-modal-1').modal('show');
       }
   })
}
</script>