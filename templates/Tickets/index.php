<?= $this->Html->css("assets/css/kanban") ?>
<style>
    .clr-theme {
    color: #186cff;
}
.text-yellow{
    color:#979714;
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
             <h4 class="fw-bold m-0 p-0">Tickets</h4>
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
                     <a class="nav-link active" href="javascript:void(0);" style="background-color:#186cff !important;"><i class="bx bx-detail me-1"></i> Tickets</a>
                 </li>
                 <li class="nav-item">
                     <a class="nav-link" href="<?= ECOM360 ?>/archive-tickets"><i class="bx bx-detail me-1"></i>
                         Archived Tickets</a>
                 </li>
             </ul>
         </div>
     </div>
    <main class="main" id="top">
        <div class="container-fluid" data-layout="container-fluid">
            <div class="content">
                <div class="row mb-2">
                    <div class="col-lg-12 mb-1 order-0">
                        <div class="card">
                            <div class="ms-2 me-2 p-2">
                                <?= $this->Form->create(null, ['action' => 'tickets']) ?>
                                <div class="row align-items-center">
                                    <?php if ($loginUser->user_type != 2) : ?>
                                    <div class="col-lg-2 col-sm-12">
                                        <div class="mb-1">
                                            <label class="form-label">Client Name</label>
                                            <div class="input-group input-group-merge">
                                                <select class="js-example-client"             name="client_id" required>
                                                    <option value="0">Please Select</option>
                                                    <?php
                                                        foreach ($client as $clientVal) :
                                                        ?>
                                                    <option value="<?= $clientVal->id ?>" <?php
                                                     if (isset($clientId)) {
                                                         if ($clientId == $clientVal->id) {
                                                         echo 'selected';
                                                        }
                                                        }
                                                    ?>>
                                                        <?= $clientVal->first_name ?>
                                                        <?= $clientVal->last_name ?></option>
                                                    <?php
                                                        endforeach;
                                                        ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                    endif;
                                    ?>
                                   
                                    <div class="col-lg-2 col-sm-12">
                                        <div class="mb-1 w-100">
                                            <label for="html5-date-input" class="form-label">Date Added</label>
                                            <input class="form-control" type="date" name="date" id="date" onclick="this.showPicker()"
                                                value="<?php if (isset($filterDate)) echo $filterDate; ?>">
                                        </div>
                                    </div>
                                   <div class="col-lg-2 col-sm-12">
                                        <div class="mb-1 w-100">
                                            <label class="form-label">Status</label>
                                            <div class="input-group input-group-merge">
                                                <select class="form-control" id="mark" name="mark">
                                                    <option value="0">Please Select</option>
                                                    <option value="Unanswered" <?php if (isset($mark)) {
                                                                if ($mark == 'Unanswered') echo 'selected';
                                                            } ?>>Unanswered </option>
                                                    <option value="pending_response" <?php if (isset($mark)) {
                                                        if ($mark == 'pending_response') echo 'selected';
                                                            } ?>>Pending Response</option>
                                                    <option value="important" <?php if (isset($mark)) {
                                                                if ($mark == 'important') echo 'selected';
                                                            } ?>>Important</option>
                                                    <option value="flag" <?php if (isset($mark)) {
                                                                if ($mark == 'flag') echo 'selected';
                                                            } ?>>Flagged</option>
                                                </select>                                                
                                            </div>
                                        </div>
                                    </div>
                                    <?php //if($loginUser->user_type==0){
                                         if(($loginUser->user_type==1 ) || $loginUser->user_type==0) {  ?>
                                    <div class="col-lg-2 col-sm-12">
                                        <div class="mb-1 w-100">
                                            <label class="form-label">Assign To</label>
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
                                    </div>
                                    <div class="col-lg-2 col-sm-12">
                                        <div class="mt-4 text-end">
                                        <?php if(($loginUser->user_type==1 ) || $loginUser->user_type==0) : ?> 
                                            <button type="button" class="btn btn-primary w-100" data-bs-toggle="modal"
                                                data-bs-target="#basicModal">
                                                Add Ticket
                                            </button>
                                            <?php endif;?>
                                        </div>
                                    </div>
                                </div>    
                                    <hr>
                                <div class="row align-items-center">
                                    <div class="col-lg-2 col-sm-12">
                                        <!-- Account manager filter -->
                                        <div class="mb-1 w-100">
                                            <label class="form-label">Account Manager</label>
                                            <div class="input-group input-group-merge">
                                                <select id="account_manager" class="form-control js-example-client"
                                                    aria-label=".form-select-lg example" name="account_manager">
                                                    <option value="0">Select Account Manager</option>
                                                    <option value="none_assigned" <?= ($accountManagerID == 'none_assigned') ? 'selected' : '' ?>>None Assigned</option>
                                                    <?php foreach($accountManagers as $am) { ?>
                                                        <option value="<?= $am->id;?>" <?= $accountManagerID == $am->id? 'selected' : '' ?>>
                                                        <?= ucfirst($am->first_name).' '.ucfirst($am->last_name);?>
                                                        </option>
                                                    <?php }?>
                                                    
                                                </select>                                          
                                            </div>
                                        </div>
                                    </div>   
                                    <div class="col-lg-2 col-sm-12">
                                        <!-- Account manager filter -->
                                        <div class="mb-1 w-100">
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
                                    <div class="col-lg-3 col-sm-12">
                                        <div class="mb-1 w-100">
                                            <label class="form-label">By Ticket Id/Title</label>
                                            <div class="input-group input-group-merge">
                                                <select id="watcher_id" class="form-control js-example-client" name="ticket_identity">
                                                    <option value="0">Select Option</option>
                                                    <?php foreach($ticketData as $t) { ?>
                                                        <option value="<?= $t->id;?>" <?= $ticketId == $t->id ? 'selected' : '' ?>>
                                                        <?= $t->title.'('.$t->ticket_identity.')';?>
                                                        </option>
                                                    <?php } ?>
                                                </select>                                          
                                            </div>
                                        </div>
                                    </div>    
                                    <div class="col-lg-2 col-sm-12">
                                            <div class="mt-4"> 
                                                <button type="submit" class="btn btn-secondary">
                                                    Apply
                                                </button>
                                            <!-- </div> -->
                                        <!-- </div>
                                        <div class="col-lg-2 col-sm-12"> -->
                                            <!-- <div class="mt-4 text-end"> -->
                                                <a href="<?= ECOM360 ?>/tickets" class="btn btn-info">
                                                    Clear
                                                </a>
                                            </div>
                                    </div>
                                </div>
                                <?= $this->Form->end() ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?= $this->Flash->render('fieldErr') ?>
                <?= $this->Flash->render('saveTickets') ?>
                <div class="kanban-container scrollbar New-Scroll me-n3">
                    <?php if(in_array(1,$issue)) { ?>
                    <div class="kanban-column ticketDrag" ondrop="drop(event)" ondragover="allowDrop(event)" data-issue="1">
                        <div class="kanban-column-header">
                            <h5 class="fs-0 mb-0">General Support 
                                <span class="text-500">(<?= $ticket1 ?>)</span>
                            </h5>
                        </div>
                        
                        <?php
                        if ($ticket1 > 0) :
                            echo '<div class="kanban-items-container scrollbar">';
                            foreach ($ticketData as $ticketValue) :
                                if($ticketValue->issue_type==1 && $ticketValue->status == 0){

                                // $date = date('Y-m-d H:i:s');
                                // $datetime1 = new DateTime($ticketValue->created_at);
                                // $datetime2 = new DateTime($date);
                                // $interval = $datetime1->diff($datetime2);
                                // $due = $interval->d.' days ago';
                                $last_due ='Not yet';
                                $due = date('m/d/Y',strtotime($ticketValue->created_at));
                                if($ticketValue->last_response!='')
                                $last_due = date('m/d/Y',strtotime($ticketValue->last_response));

                                $style = "";
                        ?>
                        
                            <div class="kanban-item" ondragstart="dragStart(event)" ondrag="dragging(event)" draggable="true" id="support_<?= $ticketValue->id;?>">
                                <div class="card kanban-item-card hover-actions-trigger">
                                <div class="card-body" style="<?= $style;?>">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                        <div>
                                            
                                        <?php
                                        $mark_flag_icon = $mark_important_icon = "";
                                         if($ticketValue->mark_flag ==1){
                                            $mark_flag_icon ='<i class="bx bxs-flag-alt text-yellow"></i>';
                                        }
                                        if($ticketValue->mark_important ==1){
                                            $mark_important_icon ='<i class="bx bxs-star text-primary"></i>';
                                        }
                                        echo  $mark_flag_icon."  ".$mark_important_icon ;                                            // LabelsData is a helper class
                                            foreach ($this->LabelsData->labelData($ticketValue->id) as $lableVal) :
                                                if ($lableVal->label_name == 'New') {

                                                    $className = "success";
                                                } else if ($lableVal->label_name == 'Goal') {

                                                    $className = "primary";
                                                } else if ($lableVal->label_name == 'Enhancement') {

                                                    $className = "info";
                                                } else if ($lableVal->label_name == 'Bug') {

                                                    $className = "danger";
                                                } else if ($lableVal->label_name == 'Documentation') {

                                                    $className = "secondary";
                                                } else if ($lableVal->label_name == 'Helper') {

                                                    $className = "warning";
                                                }
                                            ?>
                                    <span class="badge py-1 me-1 mb-1 badge-soft-<?= $className ?>">
                                        <?= $lableVal->label_name ?>
                                    </span>
                                                
                                    <?php
                                        endforeach;

                                            if($ticketValue->seen===0 && $ticketValue->client_id == $ticketValue->sender_id)
                                            {
                                                $icon = '<i class="menu-icon tf-icons bx bxs-comment-dots clr-theme"></i>';
                                            }
                                            else if($ticketValue->seen===0 && $ticketValue->client_id != $ticketValue->sender_id)
                                            {
                                                $icon = '<i class="menu-icon tf-icons bx bxs-comment-dots" style="color:#000;"></i>';
                                            }
                                            else
                                            {
                                                $icon = "";
                                            }
                                    ?>
                                                    </div>
                                                    <div>
                                        <?= $icon; ?>
                                    </div>
                                            
                                    </div>
                                        <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                            <b>Client Name :</b> <?= $ticketValue->user; ?>
                                        </p>
                                        <?php if($ticketValue->store_specific == 3 || $ticketValue->store) { ?>
                                        <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)"> 
                                             <b>Store Name :</b> 
                                             <?php if($ticketValue->store_specific == 3)
                                             {
                                                echo "Store Not Listed In Portal";
                                             }else {
                                             echo $ticketValue->store."-(".$ticketValue->store_status.")";
                                             }
                                             
                                            ?>
                                         </p>
                                         <?php } ?>
                                        <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)"> 
                                            <b>Title :</b> <?= $ticketValue->title; ?>
                                        </p>
                                        <?php if($ticketValue->staff) { ?>
                                        <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                            <b>Assigned to :</b> <?= $ticketValue->staff; ?>
                                        </p>
                                        <?php } ?>
                                        <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                            <b>Manager :</b> <?php if($ticketValue->account_manager != ""){ echo  $ticketValue->account_manager; }else{ echo "-"; } ?>
                                        </p>
                                        <!-- watchers -->
                                        <?php if (count($ticketValue->watchers) > 0) : ?>
                                        <?php $watcherList = array_slice($ticketValue->watchers, 0, 1);?> 
                                            <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                            <b>Watchers :</b> <?php foreach ($watcherList as $val) : ?>
                                                                <span class="product-item"><?= $val->watcher_name; ?></span>
                                                            <?php endforeach;  
                                                            if(count($ticketValue->watchers) > 1): ?> 
                                                            <p class="product-item" style="margin-left:50px">+<?= count($ticketValue->watchers) - 1?> more</p>
                                                            <?php endif; ?> 
                                            </p>
                                        <?php endif; ?>
                                        <div class="row">
                                        <div class="col-md-6 p-0">
                                            <div class="kanban-item-footer justify-content-center">
                                                <div class="text-500 z-index-2">
                                                    <span title="">Ticket Initiated:</span>
                                                </div>
                                                <p class="card-text">
                                                    <?= $due;?>
                                                </p>
                                            </div>                                         
                                        </div>
                                        <div class="col-md-6 p-0">
                                            <div class="kanban-item-footer justify-content-center">
                                                <div class="text-500 z-index-2">
                                                    <span title="">Last Response:</span>
                                                </div>
                                                <p class="card-text">
                                                    <?= $last_due;?>
                                                </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>     
                        
                        <?php
                                }
                            endforeach; 

                            $ticketData1 = array_reverse($ticketData);
                            foreach ($ticketData1 as $ticketValue) :
                                if($ticketValue->issue_type==1 && $ticketValue->status == 1){

                                
                                $last_due ='Not yet';
                                $due = date('m/d/Y',strtotime($ticketValue->created_at));
                                if($ticketValue->last_response!='')
                                $last_due = date('m/d/Y',strtotime($ticketValue->last_response));

                                $style = "background-color:#f7f782;";
                        ?>
                        

                            <div class="kanban-item" ondragstart="dragStart(event)" ondrag="dragging(event)" draggable="true" id="support_<?= $ticketValue->id;?>">
                                <div class="card kanban-item-card hover-actions-trigger">
                                    <div class="card-body" style="<?= $style;?>">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                        <div>
                                        <?php
                                         $mark_flag_icon = $mark_important_icon = "";
                                         if($ticketValue->mark_flag ==1){
                                            $mark_flag_icon ='<i class="bx bxs-flag-alt text-yellow"></i>';
                                        }
                                        if($ticketValue->mark_important ==1){
                                            $mark_important_icon ='<i class="bx bxs-star text-primary"></i>';
                                        }
                                        echo  $mark_flag_icon."  ".$mark_important_icon ;      
                                            // LabelsData is a helper class
                                            foreach ($this->LabelsData->labelData($ticketValue->id) as $lableVal) :
                                                if ($lableVal->label_name == 'New') {

                                                    $className = "success";
                                                } else if ($lableVal->label_name == 'Goal') {

                                                    $className = "primary";
                                                } else if ($lableVal->label_name == 'Enhancement') {

                                                    $className = "info";
                                                } else if ($lableVal->label_name == 'Bug') {

                                                    $className = "danger";
                                                } else if ($lableVal->label_name == 'Documentation') {

                                                    $className = "secondary";
                                                } else if ($lableVal->label_name == 'Helper') {

                                                    $className = "warning";
                                                }
                                            ?>
                                    <span class="badge py-1 me-1 mb-1 badge-soft-<?= $className ?>">
                                        <?= $lableVal->label_name ?>
                                    </span>
                                                
                                    <?php
                                            endforeach;

                                            if($ticketValue->seen===0 && $ticketValue->client_id == $ticketValue->sender_id)
                                            {
                                                $icon = '<i class="menu-icon tf-icons bx bxs-comment-dots clr-theme"></i>';
                                            }
                                            else if($ticketValue->seen===0 && $ticketValue->client_id != $ticketValue->sender_id)
                                            {
                                                $icon = '<i class="menu-icon tf-icons bx bxs-comment-dots" style="color:#000;"></i>';
                                            }
                                            else
                                            {
                                                $icon = "";
                                            }
                                        
                                                        ?>
                                                    </div>
                                                    <div>
                                        <?= $icon; ?>
                                    </div>
                                            
                                    </div>
                                        <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                            <b>Client Name :</b> <?= $ticketValue->user; ?>
                                        </p>
                                         <?php if($ticketValue->store_specific == 3 || $ticketValue->store) { ?>
                                        <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)"> 
                                             <b>Store Name :</b> 
                                             <?php if($ticketValue->store_specific == 3)
                                             {
                                                echo "Store Not Listed In Portal";
                                             }else {
                                             echo $ticketValue->store."-(".$ticketValue->store_status.")";
                                             }
                                             
                                            ?>
                                         </p>
                                         <?php } ?>
                                        <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)"> 
                                            <b>Title :</b> <?= $ticketValue->title; ?>
                                        </p>
                                        <?php if($ticketValue->staff) { ?>
                                        <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                            <b>Assigned to :</b> <?= $ticketValue->staff; ?>
                                        </p>
                                        <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                            <b>Manager :</b> <?php if($ticketValue->account_manager != ""){ echo  $ticketValue->account_manager; }else{ echo "-"; } ?>
                                        </p>
                                        <?php } ?>
                                        <!-- watchers -->
                                        <?php if (count($ticketValue->watchers) > 0) : ?>
                                        <?php $watcherList = array_slice($ticketValue->watchers, 0, 1);?> 
                                            <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                            <b>Watchers :</b> <?php foreach ($watcherList as $val) : ?>
                                                                <span class="product-item"><?= $val->watcher_name; ?></span>
                                                            <?php endforeach;  
                                                            if(count($ticketValue->watchers) > 1): ?> 
                                                            <p class="product-item" style="margin-left:50px">+<?= count($ticketValue->watchers) - 1?> more</p>
                                                            <?php endif; ?> 
                                            </p>
                                        <?php endif; ?>
                                        <div class="row">
                                        <div class="col-md-6 p-0">
                                            <div class="kanban-item-footer justify-content-center">
                                                <div class="text-500 z-index-2">
                                                    <span title="">Ticket Initiated:</span>
                                                </div>
                                                <p class="card-text">
                                                    <?= $due;?>
                                                </p>
                                            </div>                                         
                                        </div>
                                        <div class="col-md-6 p-0">
                                            <div class="kanban-item-footer justify-content-center">
                                                <div class="text-500 z-index-2">
                                                    <span title="">Last Response:</span>
                                                </div>
                                                <p class="card-text">
                                                    <?= $last_due;?>
                                                </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>     
                        
                        <?php
                                }
                            endforeach; ?>
                            </div>
                            <?php 
                        else :
                            ?>
                        <div class="kanban-items-container scrollbar ">
                            <div class="kanban-item" >
                                <div class="card kanban-item-card hover-actions-trigger">
                                    <div class="card-body">
                                        <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal">
                                            No Data...
                                        </p>
                                        <div class="kanban-item-footer justify-content-end  cursor-default">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php } if(in_array(2,$issue)) {  ?>
                    <div class="kanban-column ticketDrag" ondrop="drop(event)" ondragover="allowDrop(event)" data-issue="2">
                        <div class="kanban-column-header">
                            <h5 class="fs-0 mb-0">Billing <span class="text-500">(<?= $ticket2 ?>)</span>
                            </h5>
                        </div>
                        
                        <?php
                        if ($ticket2 > 0) :
                            echo '<div class="kanban-items-container scrollbar ">';
                            foreach ($ticketData as $ticketValue) :
                                if($ticketValue->issue_type==2 && $ticketValue->status == 0) {

                                // $date = date('Y-m-d H:i:s');
                                // $datetime1 = new DateTime($ticketValue->created_at);
                                // $datetime2 = new DateTime($date);
                                // $interval = $datetime1->diff($datetime2);
                                // $due = $interval->d.' days ago';

                                $last_due ='Not yet';
                                $due = date('m/d/Y',strtotime($ticketValue->created_at));
                                if($ticketValue->last_response!='')
                                $last_due = date('m/d/Y',strtotime($ticketValue->last_response));

                                // if($ticketValue->status==1)
                                // $style = "background-color:#f7f782;";
                                // else
                                $style = "";
                        ?>
                        
                            <div class="kanban-item" ondragstart="dragStart(event)" ondrag="dragging(event)" draggable="true" id="support_<?= $ticketValue->id;?>">
                                <div class="card kanban-item-card hover-actions-trigger">
                                    <div class="card-body" style="<?= $style;?>">

                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                            <div>
                                                
                                         <?php
                                          $mark_flag_icon = $mark_important_icon = "";
                                          if($ticketValue->mark_flag ==1){
                                             $mark_flag_icon ='<i class="bx bxs-flag-alt text-yellow"></i>';
                                         }
                                         if($ticketValue->mark_important ==1){
                                             $mark_important_icon ='<i class="bx bxs-star text-primary"></i>';
                                         }
                                         echo  $mark_flag_icon."  ".$mark_important_icon ;  
                                                // LabelsData is a helper class
                                                foreach ($this->LabelsData->labelData($ticketValue->id) as $lableVal) :
                                                    if ($lableVal->label_name == 'New') {

                                                        $className = "success";
                                                    } else if ($lableVal->label_name == 'Goal') {

                                                        $className = "primary";
                                                    } else if ($lableVal->label_name == 'Enhancement') {

                                                        $className = "info";
                                                    } else if ($lableVal->label_name == 'Bug') {

                                                        $className = "danger";
                                                    } else if ($lableVal->label_name == 'Documentation') {

                                                        $className = "secondary";
                                                    } else if ($lableVal->label_name == 'Helper') {

                                                        $className = "warning";
                                                    }
                                                ?>
                                        <span class="badge py-1 me-1 mb-1 badge-soft-<?= $className ?>">
                                            <?= $lableVal->label_name ?>
                                        </span>
                                                   
                                        <?php
                                                endforeach;

                                                if($ticketValue->seen===0 && $ticketValue->client_id == $ticketValue->sender_id)
                                                {
                                                    $icon = '<i class="menu-icon tf-icons bx bxs-comment-dots clr-theme"></i>';
                                                }
                                                else if($ticketValue->seen===0 && $ticketValue->client_id != $ticketValue->sender_id)
                                                {
                                                    $icon = '<i class="menu-icon tf-icons bx bxs-comment-dots" style="color:#000;"></i>';
                                                }
                                                else
                                                {
                                                    $icon = "";
                                                }
                                            
                                                            ?>
                                                        </div>
                                                        <div>
                                        <?= $icon; ?>
                                    </div>
                                                
                                        </div>
                                        <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                             <b>Client Name :</b> <?= $ticketValue->user; ?>
                                         </p>
                                         <?php if($ticketValue->store_specific == 3 || $ticketValue->store) { ?>
                                        <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)"> 
                                             <b>Store Name :</b> 
                                             <?php if($ticketValue->store_specific == 3)
                                             {
                                                echo "Store Not Listed In Portal";
                                             }else {
                                             echo $ticketValue->store."-(".$ticketValue->store_status.")";
                                             }
                                             
                                            ?>
                                         </p>
                                         <?php } ?>
                                         
                                         <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)"> 
                                             <b>Title :</b> <?= $ticketValue->title; ?>
                                         </p>
                                         <?php if($ticketValue->staff) { ?>
                                         <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                             <b>Assigned to :</b> <?= $ticketValue->staff; ?>
                                         </p>
                                         <?php } ?>
                                         <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                            <b>Manager :</b> <?php if($ticketValue->account_manager != ""){ echo  $ticketValue->account_manager; }else{ echo "-"; } ?>
                                        </p>
                                        <!-- watchers -->
                                        <?php if (count($ticketValue->watchers) > 0) : ?>
                                        <?php $watcherList = array_slice($ticketValue->watchers, 0, 1);?> 
                                            <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                            <b>Watchers :</b> <?php foreach ($watcherList as $val) : ?>
                                                                <span class="product-item"><?= $val->watcher_name; ?></span>
                                                            <?php endforeach;  
                                                            if(count($ticketValue->watchers) > 1): ?> 
                                                            <p class="product-item" style="margin-left:50px">+<?= count($ticketValue->watchers) - 1?> more</p>
                                                            <?php endif; ?> 
                                            </p>
                                        <?php endif; ?>
                                         <div class="row">
                                            <div class="col-md-6 p-0">
                                                <div class="kanban-item-footer justify-content-center">
                                                    <div class="text-500 z-index-2">
                                                        <span title="">Ticket Initiated:</span>
                                                    </div>
                                                    <p class="card-text">
                                                        <?= $due;?>
                                                    </p>
                                                </div>                                         
                                            </div>
                                            <div class="col-md-6 p-0">
                                                <div class="kanban-item-footer justify-content-center">
                                                    <div class="text-500 z-index-2">
                                                        <span title="">Last Response:</span>
                                                    </div>
                                                    <p class="card-text">
                                                        <?= $last_due;?>
                                                    </p>
                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                            </div>
                            
                       
                        <?php
                                }
                            endforeach; 

                            $ticketData1 = array_reverse($ticketData);
                            foreach ($ticketData1 as $ticketValue) :
                                if($ticketValue->issue_type==2 && $ticketValue->status == 1) {

                                // $date = date('Y-m-d H:i:s');
                                // $datetime1 = new DateTime($ticketValue->created_at);
                                // $datetime2 = new DateTime($date);
                                // $interval = $datetime1->diff($datetime2);
                                // $due = $interval->d.' days ago';

                                $last_due ='Not yet';
                                $due = date('m/d/Y',strtotime($ticketValue->created_at));
                                if($ticketValue->last_response!='')
                                $last_due = date('m/d/Y',strtotime($ticketValue->last_response));

                                $style = "background-color:#f7f782;";
                        ?>
                        
                            <div class="kanban-item" ondragstart="dragStart(event)" ondrag="dragging(event)" draggable="true" id="support_<?= $ticketValue->id;?>">
                                <div class="card kanban-item-card hover-actions-trigger">
                                    <div class="card-body" style="<?= $style;?>">

                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                            <div>
                                         <?php
                                          $mark_flag_icon = $mark_important_icon = "";
                                          if($ticketValue->mark_flag ==1){
                                             $mark_flag_icon ='<i class="bx bxs-flag-alt text-yellow"></i>';
                                         }
                                         if($ticketValue->mark_important ==1){
                                             $mark_important_icon ='<i class="bx bxs-star text-primary"></i>';
                                         }
                                         echo  $mark_flag_icon."  ".$mark_important_icon ; 
                                         
                                                // LabelsData is a helper class
                                                foreach ($this->LabelsData->labelData($ticketValue->id) as $lableVal) :
                                                    if ($lableVal->label_name == 'New') {

                                                        $className = "success";
                                                    } else if ($lableVal->label_name == 'Goal') {

                                                        $className = "primary";
                                                    } else if ($lableVal->label_name == 'Enhancement') {

                                                        $className = "info";
                                                    } else if ($lableVal->label_name == 'Bug') {

                                                        $className = "danger";
                                                    } else if ($lableVal->label_name == 'Documentation') {

                                                        $className = "secondary";
                                                    } else if ($lableVal->label_name == 'Helper') {

                                                        $className = "warning";
                                                    }
                                                ?>
                                        <span class="badge py-1 me-1 mb-1 badge-soft-<?= $className ?>">
                                            <?= $lableVal->label_name ?>
                                        </span>
                                                   
                                        <?php
                                                endforeach;

                                                if($ticketValue->seen===0 && $ticketValue->client_id == $ticketValue->sender_id)
                                                {
                                                    $icon = '<i class="menu-icon tf-icons bx bxs-comment-dots clr-theme"></i>';
                                                }
                                                else if($ticketValue->seen===0 && $ticketValue->client_id != $ticketValue->sender_id)
                                                {
                                                    $icon = '<i class="menu-icon tf-icons bx bxs-comment-dots" style="color:#000;"></i>';
                                                }
                                                else
                                                {
                                                    $icon = "";
                                                }
                                            
                                                            ?>
                                                        </div>
                                                        <div>
                                        <?= $icon; ?>
                                    </div>
                                                
                                        </div>
                                        <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                             <b>Client Name :</b> <?= $ticketValue->user; ?>
                                         </p>
                                         <?php if($ticketValue->store_specific == 3 || $ticketValue->store) { ?>
                                        <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)"> 
                                             <b>Store Name :</b> 
                                             <?php if($ticketValue->store_specific == 3)
                                             {
                                                echo "Store Not Listed In Portal";
                                             }else {
                                             echo $ticketValue->store."-(".$ticketValue->store_status.")";
                                             }
                                             
                                            ?>
                                         </p>
                                         <?php } ?>
                                         <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)"> 
                                             <b>Title :</b> <?= $ticketValue->title; ?>
                                         </p>
                                         <?php if($ticketValue->staff) { ?>
                                         <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                             <b>Assigned to :</b> <?= $ticketValue->staff; ?>
                                         </p>
                                         <?php } ?>
                                         <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                            <b>Manager :</b> <?php if($ticketValue->account_manager != ""){ echo  $ticketValue->account_manager; }else{ echo "-"; } ?>
                                        </p>
                                        <!-- watchers -->
                                        <?php if (count($ticketValue->watchers) > 0) : ?>
                                        <?php $watcherList = array_slice($ticketValue->watchers, 0, 1);?> 
                                            <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                            <b>Watchers :</b> <?php foreach ($watcherList as $val) : ?>
                                                                <span class="product-item"><?= $val->watcher_name; ?></span>
                                                            <?php endforeach;  
                                                            if(count($ticketValue->watchers) > 1): ?> 
                                                            <p class="product-item" style="margin-left:50px">+<?= count($ticketValue->watchers) - 1?> more</p>
                                                            <?php endif; ?> 
                                            </p>
                                        <?php endif; ?>
                                         <div class="row">
                                            <div class="col-md-6 p-0">
                                                <div class="kanban-item-footer justify-content-center">
                                                    <div class="text-500 z-index-2">
                                                        <span title="">Ticket Initiated:</span>
                                                    </div>
                                                    <p class="card-text">
                                                        <?= $due;?>
                                                    </p>
                                                </div>                                         
                                            </div>
                                            <div class="col-md-6 p-0">
                                                <div class="kanban-item-footer justify-content-center">
                                                    <div class="text-500 z-index-2">
                                                        <span title="">Last Response:</span>
                                                    </div>
                                                    <p class="card-text">
                                                        <?= $last_due;?>
                                                    </p>
                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                            </div>
                            
                       
                        <?php
                                }
                            endforeach; ?>
                             </div>
                             <?php
                        else :
                            ?>
                        <div class="kanban-items-container scrollbar ">
                            <div class="kanban-item">
                                <div class="card kanban-item-card hover-actions-trigger">
                                    <div class="card-body">
                                        <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal">
                                            No Data...
                                        </p>
                                        <div class="kanban-item-footer justify-content-end  cursor-default">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php } if(in_array(3,$issue)) {  ?>        
                    <div class="kanban-column ticketDrag" ondrop="drop(event)" ondragover="allowDrop(event)" data-issue="3">
                        <div class="kanban-column-header">
                            <h5 class="fs-0 mb-0">Refer a friend <span
                                    class="text-500">(<?= $ticket3 ?>)</span></h5>
                        </div>

                        <?php
                        if ($ticket3 > 0) :
                            echo '<div class="kanban-items-container scrollbar ">';
                            foreach ($ticketData as $ticketValue) :
                                if($ticketValue->issue_type==3 && $ticketValue->status == 0){

                               // $date = date('Y-m-d H:i:s');
                                // $datetime1 = new DateTime($ticketValue->created_at);
                                // $datetime2 = new DateTime($date);
                                // $interval = $datetime1->diff($datetime2);
                                // $due = $interval->d.' days ago';

                                $last_due ='Not yet';
                                $due = date('m/d/Y',strtotime($ticketValue->created_at));
                                if($ticketValue->last_response!='')
                                $last_due = date('m/d/Y',strtotime($ticketValue->last_response));

                                // if($ticketValue->status==1)
                                // $style = "background-color:#f7f782;";
                                // else
                                $style = "";
                        ?>
                       
                            <div class="kanban-item" ondragstart="dragStart(event)" ondrag="dragging(event)" draggable="true" id="support_<?= $ticketValue->id;?>">
                                <div class="card kanban-item-card hover-actions-trigger">
                                    <div class="card-body" style="<?= $style;?>">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                            <div>
                                         <?php
                                          $mark_flag_icon = $mark_important_icon = "";
                                          if($ticketValue->mark_flag ==1){
                                             $mark_flag_icon ='<i class="bx bxs-flag-alt text-yellow"></i>';
                                         }
                                         if($ticketValue->mark_important ==1){
                                             $mark_important_icon ='<i class="bx bxs-star text-primary"></i>';
                                         }
                                         echo  $mark_flag_icon."  ".$mark_important_icon ;  
                                                // LabelsData is a helper class
                                                foreach ($this->LabelsData->labelData($ticketValue->id) as $lableVal) :
                                                    if ($lableVal->label_name == 'New') {

                                                        $className = "success";
                                                    } else if ($lableVal->label_name == 'Goal') {

                                                        $className = "primary";
                                                    } else if ($lableVal->label_name == 'Enhancement') {

                                                        $className = "info";
                                                    } else if ($lableVal->label_name == 'Bug') {

                                                        $className = "danger";
                                                    } else if ($lableVal->label_name == 'Documentation') {

                                                        $className = "secondary";
                                                    } else if ($lableVal->label_name == 'Helper') {

                                                        $className = "warning";
                                                    }
                                                ?>
                                        <span class="badge py-1 me-1 mb-1 badge-soft-<?= $className ?>">
                                            <?= $lableVal->label_name ?>
                                        </span>
                                                   
                                        <?php
                                                endforeach;

                                                if($ticketValue->seen===0 && $ticketValue->client_id == $ticketValue->sender_id)
                                                {
                                                    $icon = '<i class="menu-icon tf-icons bx bxs-comment-dots clr-theme"></i>';
                                                }
                                                else if($ticketValue->seen===0 && $ticketValue->client_id != $ticketValue->sender_id)
                                                {
                                                    $icon = '<i class="menu-icon tf-icons bx bxs-comment-dots" style="color:#000;"></i>';
                                                }
                                                else
                                                {
                                                    $icon = "";
                                                }
                                
                                                ?>
                                            </div>
                                            <div>
                                        <?= $icon; ?>
                                    </div>
                                                
                                        </div>
                                        <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                             <b>Client Name :</b> <?= $ticketValue->user; ?>
                                         </p>
                                         <?php if($ticketValue->store_specific == 3 || $ticketValue->store) { ?>
                                        <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)"> 
                                             <b>Store Name :</b> 
                                             <?php if($ticketValue->store_specific == 3)
                                             {
                                                echo "Store Not Listed In Portal";
                                             }else {
                                             echo $ticketValue->store."-(".$ticketValue->store_status.")";
                                             }
                                             
                                            ?>
                                         </p>
                                         <?php } ?>
                                         <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)"> 
                                             <b>Title :</b> <?= $ticketValue->title; ?>
                                         </p>
                                         <?php if($ticketValue->staff) { ?>
                                         <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                             <b>Assigned to :</b> <?= $ticketValue->staff; ?>
                                         </p>
                                         <?php } ?>
                                         <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                            <b>Manager :</b> <?php if($ticketValue->account_manager != ""){ echo  $ticketValue->account_manager; }else{ echo "-"; } ?>
                                        </p>
                                        <!-- watchers -->
                                        <?php if (count($ticketValue->watchers) > 0) : ?>
                                        <?php $watcherList = array_slice($ticketValue->watchers, 0, 1);?> 
                                            <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                            <b>Watchers :</b> <?php foreach ($watcherList as $val) : ?>
                                                                <span class="product-item"><?= $val->watcher_name; ?></span>
                                                            <?php endforeach;  
                                                            if(count($ticketValue->watchers) > 1): ?> 
                                                            <p class="product-item" style="margin-left:50px">+<?= count($ticketValue->watchers) - 1?> more</p>
                                                            <?php endif; ?> 
                                            </p>
                                        <?php endif; ?>
                                         <div class="row">
                                            <div class="col-md-6 p-0">
                                                <div class="kanban-item-footer justify-content-center">
                                                    <div class="text-500 z-index-2">
                                                        <span title="">Ticket Initiated:</span>
                                                    </div>
                                                    <p class="card-text">
                                                        <?= $due;?>
                                                    </p>
                                                </div>                                         
                                            </div>
                                            <div class="col-md-6 p-0">
                                                <div class="kanban-item-footer justify-content-center">
                                                    <div class="text-500 z-index-2">
                                                        <span title="">Last Response:</span>
                                                    </div>
                                                    <p class="card-text">
                                                        <?= $last_due;?>
                                                    </p>
                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                            </div>
                            
                        
                        <?php
                                }
                            endforeach; 
                            $ticketData1 = array_reverse($ticketData);
                            foreach ($ticketData1 as $ticketValue) :
                                if($ticketValue->issue_type==3 && $ticketValue->status == 1){


                                $last_due ='Not yet';
                                $due = date('m/d/Y',strtotime($ticketValue->created_at));
                                if($ticketValue->last_response!='')
                                $last_due = date('m/d/Y',strtotime($ticketValue->last_response));

                                $style = "background-color:#f7f782;";
                        ?>
                       
                            <div class="kanban-item" ondragstart="dragStart(event)" ondrag="dragging(event)" draggable="true" id="support_<?= $ticketValue->id;?>">
                                <div class="card kanban-item-card hover-actions-trigger">
                                    <div class="card-body" style="<?= $style;?>">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                            <div>
                                         <?php
                                          $mark_flag_icon = $mark_important_icon = "";
                                          if($ticketValue->mark_flag ==1){
                                             $mark_flag_icon ='<i class="bx bxs-flag-alt text-yellow"></i>';
                                         }
                                         if($ticketValue->mark_important ==1){
                                             $mark_important_icon ='<i class="bx bxs-star text-primary"></i>';
                                         }
                                         echo  $mark_flag_icon."  ".$mark_important_icon ; 
                                                // LabelsData is a helper class
                                                foreach ($this->LabelsData->labelData($ticketValue->id) as $lableVal) :
                                                    if ($lableVal->label_name == 'New') {

                                                        $className = "success";
                                                    } else if ($lableVal->label_name == 'Goal') {

                                                        $className = "primary";
                                                    } else if ($lableVal->label_name == 'Enhancement') {

                                                        $className = "info";
                                                    } else if ($lableVal->label_name == 'Bug') {

                                                        $className = "danger";
                                                    } else if ($lableVal->label_name == 'Documentation') {

                                                        $className = "secondary";
                                                    } else if ($lableVal->label_name == 'Helper') {

                                                        $className = "warning";
                                                    }
                                                ?>
                                        <span class="badge py-1 me-1 mb-1 badge-soft-<?= $className ?>">
                                            <?= $lableVal->label_name ?>
                                        </span>
                                                   
                                        <?php
                                                endforeach;

                                                if($ticketValue->seen===0 && $ticketValue->client_id == $ticketValue->sender_id)
                                                {
                                                    $icon = '<i class="menu-icon tf-icons bx bxs-comment-dots clr-theme"></i>';
                                                }
                                                else if($ticketValue->seen===0 && $ticketValue->client_id != $ticketValue->sender_id)
                                                {
                                                    $icon = '<i class="menu-icon tf-icons bx bxs-comment-dots" style="color:#000;"></i>';
                                                }
                                                else
                                                {
                                                    $icon = "";
                                                }
                                
                                                ?>
                                            </div>
                                            <div>
                                        <?= $icon; ?>
                                    </div>
                                                
                                        </div>
                                        <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                             <b>Client Name :</b> <?= $ticketValue->user; ?>
                                         </p>
                                         <?php if($ticketValue->store_specific == 3 || $ticketValue->store) { ?>
                                        <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)"> 
                                             <b>Store Name :</b> 
                                             <?php if($ticketValue->store_specific == 3)
                                             {
                                                echo "Store Not Listed In Portal";
                                             }else {
                                             echo $ticketValue->store."-(".$ticketValue->store_status.")";
                                             }
                                             
                                            ?>
                                         </p>
                                         <?php } ?>
                                         <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)"> 
                                             <b>Title :</b> <?= $ticketValue->title; ?>
                                         </p>
                                         <?php if($ticketValue->staff) { ?>
                                         <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                             <b>Assigned to :</b> <?= $ticketValue->staff; ?>
                                         </p>
                                         <?php } ?>
                                         <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                            <b>Manager :</b> <?php if($ticketValue->account_manager != ""){ echo  $ticketValue->account_manager; }else{ echo "-"; } ?>
                                        </p>
                                        <!-- watchers -->
                                        <?php if (count($ticketValue->watchers) > 0) : ?>
                                        <?php $watcherList = array_slice($ticketValue->watchers, 0, 1);?> 
                                            <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                            <b>Watchers :</b> <?php foreach ($watcherList as $val) : ?>
                                                                <span class="product-item"><?= $val->watcher_name; ?></span>
                                                            <?php endforeach;  
                                                            if(count($ticketValue->watchers) > 1): ?> 
                                                            <p class="product-item" style="margin-left:50px">+<?= count($ticketValue->watchers) - 1?> more</p>
                                                            <?php endif; ?> 
                                            </p>
                                        <?php endif; ?>
                                         <div class="row">
                                            <div class="col-md-6 p-0">
                                                <div class="kanban-item-footer justify-content-center">
                                                    <div class="text-500 z-index-2">
                                                        <span title="">Ticket Initiated:</span>
                                                    </div>
                                                    <p class="card-text">
                                                        <?= $due;?>
                                                    </p>
                                                </div>                                         
                                            </div>
                                            <div class="col-md-6 p-0">
                                                <div class="kanban-item-footer justify-content-center">
                                                    <div class="text-500 z-index-2">
                                                        <span title="">Last Response:</span>
                                                    </div>
                                                    <p class="card-text">
                                                        <?= $last_due;?>
                                                    </p>
                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                            </div>
                            
                        
                        <?php
                                }
                            endforeach; 
                            ?>
                    </div>
                    <?php
                        else :
                            ?>
                        <div class="kanban-items-container scrollbar ">
                            <div class="kanban-item">
                                <div class="card kanban-item-card hover-actions-trigger">
                                    <div class="card-body">
                                        <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal">
                                            No Data...
                                        </p>
                                        <div class="kanban-item-footer justify-content-end  cursor-default">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php } if(in_array(4,$issue)) {  ?>        
                    <div class="kanban-column ticketDrag" ondrop="drop(event)" ondragover="allowDrop(event)" data-issue="4">
                        <div class="kanban-column-header">
                            <h5 class="fs-0 mb-0">Onboarding a new store <span
                                    class="text-500">(<?= $ticket4 ?>)</span></h5>
                        </div>

                        <?php
                        if ($ticket4 > 0) :
                            echo '<div class="kanban-items-container scrollbar ">';
                            foreach ($ticketData as $ticketValue) :
                                if($ticketValue->issue_type==4 && $ticketValue->status == 0){

                                // $date = date('Y-m-d H:i:s');
                                // $datetime1 = new DateTime($ticketValue->created_at);
                                // $datetime2 = new DateTime($date);
                                // $interval = $datetime1->diff($datetime2);
                                // $due = $interval->d.' days ago';

                                $last_due ='Not yet';
                                $due = date('m/d/Y',strtotime($ticketValue->created_at));
                                if($ticketValue->last_response!='')
                                $last_due = date('m/d/Y',strtotime($ticketValue->last_response));

                                // if($ticketValue->status==1)
                                // $style = "background-color:#f7f782;";
                                // else
                                $style = "";
                        ?>
                        
                            <div class="kanban-item" ondragstart="dragStart(event)" ondrag="dragging(event)" draggable="true" id="support_<?= $ticketValue->id;?>">
                                <div class="card kanban-item-card hover-actions-trigger">
                                    <div class="card-body" style="<?= $style;?>">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                            <div>
                                         <?php
                                          $mark_flag_icon = $mark_important_icon = "";
                                          if($ticketValue->mark_flag ==1){
                                             $mark_flag_icon ='<i class="bx bxs-flag-alt text-yellow"></i>';
                                         }
                                         if($ticketValue->mark_important ==1){
                                             $mark_important_icon ='<i class="bx bxs-star text-primary"></i>';
                                         }
                                         echo  $mark_flag_icon."  ".$mark_important_icon ;  
                                                // LabelsData is a helper class
                                                foreach ($this->LabelsData->labelData($ticketValue->id) as $lableVal) :
                                                    if ($lableVal->label_name == 'New') {

                                                        $className = "success";
                                                    } else if ($lableVal->label_name == 'Goal') {

                                                        $className = "primary";
                                                    } else if ($lableVal->label_name == 'Enhancement') {

                                                        $className = "info";
                                                    } else if ($lableVal->label_name == 'Bug') {

                                                        $className = "danger";
                                                    } else if ($lableVal->label_name == 'Documentation') {

                                                        $className = "secondary";
                                                    } else if ($lableVal->label_name == 'Helper') {

                                                        $className = "warning";
                                                    }
                                                ?>
                                        <span class="badge py-1 me-1 mb-1 badge-soft-<?= $className ?>">
                                            <?= $lableVal->label_name ?>
                                        </span>
                                                   
                                        <?php
                                                endforeach;

                                                if($ticketValue->seen===0 && $ticketValue->client_id == $ticketValue->sender_id)
                                                {
                                                    $icon = '<i class="menu-icon tf-icons bx bxs-comment-dots clr-theme"></i>';
                                                }
                                                else if($ticketValue->seen===0 && $ticketValue->client_id != $ticketValue->sender_id)
                                                {
                                                    $icon = '<i class="menu-icon tf-icons bx bxs-comment-dots" style="color:#000;"></i>';
                                                }
                                                else
                                                {
                                                    $icon = "";
                                                }
                                
                                                ?>
                                            </div>
                                            <div>
                                    <?= $icon; ?>
                                </div>
                                                
                                        </div>
                                        <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                             <b>Client Name :</b> <?= $ticketValue->user; ?>
                                         </p>
                                         <?php if($ticketValue->store_specific == 3 || $ticketValue->store) { ?>
                                        <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)"> 
                                             <b>Store Name :</b> 
                                             <?php if($ticketValue->store_specific == 3)
                                             {
                                                echo "Store Not Listed In Portal";
                                             }else {
                                             echo $ticketValue->store."-(".$ticketValue->store_status.")";
                                             }
                                             
                                            ?>
                                         </p>
                                         <?php } ?>
                                         <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)"> 
                                             <b>Title :</b> <?= $ticketValue->title; ?>
                                         </p>
                                         <?php if($ticketValue->staff) { ?>
                                         <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                             <b>Assigned to :</b> <?= $ticketValue->staff; ?>
                                         </p>
                                         <?php } ?>
                                         <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                            <b>Manager :</b> <?php if($ticketValue->account_manager != ""){ echo  $ticketValue->account_manager; }else{ echo "-"; } ?>
                                        </p>
                                        <!-- watchers -->
                                        <?php if (count($ticketValue->watchers) > 0) : ?>
                                        <?php $watcherList = array_slice($ticketValue->watchers, 0, 1);?> 
                                            <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                            <b>Watchers :</b> <?php foreach ($watcherList as $val) : ?>
                                                                <span class="product-item"><?= $val->watcher_name; ?></span>
                                                            <?php endforeach;  
                                                            if(count($ticketValue->watchers) > 1): ?> 
                                                            <p class="product-item" style="margin-left:50px">+<?= count($ticketValue->watchers) - 1?> more</p>
                                                            <?php endif; ?> 
                                            </p>
                                        <?php endif; ?>
                                         <div class="row">
                                            <div class="col-md-6 p-0">
                                                <div class="kanban-item-footer justify-content-center">
                                                    <div class="text-500 z-index-2">
                                                        <span title="">Ticket Initiated:</span>
                                                    </div>
                                                    <p class="card-text">
                                                        <?= $due;?>
                                                    </p>
                                                </div>                                         
                                            </div>
                                            <div class="col-md-6 p-0">
                                                <div class="kanban-item-footer justify-content-center">
                                                    <div class="text-500 z-index-2">
                                                        <span title="">Last Response:</span>
                                                    </div>
                                                    <p class="card-text">
                                                        <?= $last_due;?>
                                                    </p>
                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                            </div>
                           
                        
                        <?php
                                }
                            endforeach; 
                            $ticketData1 = array_reverse($ticketData);
                            foreach ($ticketData1 as $ticketValue) :
                                if($ticketValue->issue_type==4 && $ticketValue->status == 1){

                                $last_due ='Not yet';
                                $due = date('m/d/Y',strtotime($ticketValue->created_at));
                                if($ticketValue->last_response!='')
                                $last_due = date('m/d/Y',strtotime($ticketValue->last_response));

                                $style = "background-color:#f7f782;";
                        ?>
                        
                            <div class="kanban-item" ondragstart="dragStart(event)" ondrag="dragging(event)" draggable="true" id="support_<?= $ticketValue->id;?>">
                                <div class="card kanban-item-card hover-actions-trigger">
                                    <div class="card-body" style="<?= $style;?>">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                            <div>
                                         <?php
                                          $mark_flag_icon = $mark_important_icon = "";
                                          if($ticketValue->mark_flag ==1){
                                             $mark_flag_icon ='<i class="bx bxs-flag-alt text-yellow"></i>';
                                         }
                                         if($ticketValue->mark_important ==1){
                                             $mark_important_icon ='<i class="bx bxs-star text-primary"></i>';
                                         }
                                         echo  $mark_flag_icon."  ".$mark_important_icon ; 
                                                // LabelsData is a helper class
                                                foreach ($this->LabelsData->labelData($ticketValue->id) as $lableVal) :
                                                    if ($lableVal->label_name == 'New') {

                                                        $className = "success";
                                                    } else if ($lableVal->label_name == 'Goal') {

                                                        $className = "primary";
                                                    } else if ($lableVal->label_name == 'Enhancement') {

                                                        $className = "info";
                                                    } else if ($lableVal->label_name == 'Bug') {

                                                        $className = "danger";
                                                    } else if ($lableVal->label_name == 'Documentation') {

                                                        $className = "secondary";
                                                    } else if ($lableVal->label_name == 'Helper') {

                                                        $className = "warning";
                                                    }
                                                ?>
                                        <span class="badge py-1 me-1 mb-1 badge-soft-<?= $className ?>">
                                            <?= $lableVal->label_name ?>
                                        </span>
                                                   
                                        <?php
                                                endforeach;

                                                if($ticketValue->seen===0 && $ticketValue->client_id == $ticketValue->sender_id)
                                            {
                                                $icon = '<i class="menu-icon tf-icons bx bxs-comment-dots clr-theme"></i>';
                                            }
                                            else if($ticketValue->seen===0 && $ticketValue->client_id != $ticketValue->sender_id)
                                            {
                                                $icon = '<i class="menu-icon tf-icons bx bxs-comment-dots" style="color:#000;"></i>';
                                            }
                                            else
                                            {
                                                $icon = "";
                                            }
                                
                                                ?>
                                            </div>
                                            <div>
                                    <?= $icon; ?>
                                </div>
                                                
                                        </div>
                                        <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                             <b>Client Name :</b> <?= $ticketValue->user; ?>
                                         </p>
                                         <?php if($ticketValue->store_specific == 3 || $ticketValue->store) { ?>
                                        <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)"> 
                                             <b>Store Name :</b> 
                                             <?php if($ticketValue->store_specific == 3)
                                             {
                                                echo "Store Not Listed In Portal";
                                             }else {
                                             echo $ticketValue->store."-(".$ticketValue->store_status.")";
                                             }
                                             
                                            ?>

                                             <!-- <= $ticketValue->store; ?>-(<= $ticketValue->store_status;?>) -->
                                         </p>
                                         <?php } ?>
                                         <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)"> 
                                             <b>Title :</b> <?= $ticketValue->title; ?>
                                         </p>
                                         <?php if($ticketValue->staff) { ?>
                                         <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                             <b>Assigned to :</b> <?= $ticketValue->staff; ?>
                                         </p>
                                         <?php } ?>
                                         <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                            <b>Manager :</b> <?php if($ticketValue->account_manager != ""){ echo  $ticketValue->account_manager; }else{ echo "-"; } ?>
                                        </p>
                                        <!-- watchers -->
                                        <?php if (count($ticketValue->watchers) > 0) : ?>
                                        <?php $watcherList = array_slice($ticketValue->watchers, 0, 1);?> 
                                            <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                            <b>Watchers :</b> <?php foreach ($watcherList as $val) : ?>
                                                                <span class="product-item"><?= $val->watcher_name; ?></span>
                                                            <?php endforeach;  
                                                            if(count($ticketValue->watchers) > 1): ?> 
                                                            <p class="product-item" style="margin-left:50px">+<?= count($ticketValue->watchers) - 1?> more</p>
                                                            <?php endif; ?> 
                                            </p>
                                        <?php endif; ?>
                                         <div class="row">
                                            <div class="col-md-6 p-0">
                                                <div class="kanban-item-footer justify-content-center">
                                                    <div class="text-500 z-index-2">
                                                        <span title="">Ticket Initiated:</span>
                                                    </div>
                                                    <p class="card-text">
                                                        <?= $due;?>
                                                    </p>
                                                </div>                                         
                                            </div>
                                            <div class="col-md-6 p-0">
                                                <div class="kanban-item-footer justify-content-center">
                                                    <div class="text-500 z-index-2">
                                                        <span title="">Last Response:</span>
                                                    </div>
                                                    <p class="card-text">
                                                        <?= $last_due;?>
                                                    </p>
                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                            </div>
                           
                        
                        <?php
                                }
                            endforeach; 
                            ?>
                            </div>
                            <?php
                        else :
                            ?>
                        <div class="kanban-items-container scrollbar ">
                            <div class="kanban-item">
                                <div class="card kanban-item-card hover-actions-trigger">
                                    <div class="card-body">
                                        <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal">
                                            No Data...
                                        </p>
                                        <div class="kanban-item-footer justify-content-end  cursor-default">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php } if(in_array(5,$issue)) {  ?>        
                    <div class="kanban-column ticketDrag" ondrop="drop(event)" ondragover="allowDrop(event)" data-issue="5">
                        <div class="kanban-column-header">
                            <h5 class="fs-0 mb-0">Portal Support <span
                                    class="text-500">(<?= $ticket5 ?>)</span></h5>
                        </div>

                        <?php
                        if ($ticket5 > 0) :
                            echo '<div class="kanban-items-container scrollbar ">';
                            foreach ($ticketData as $ticketValue) :
                                if($ticketValue->issue_type==5 && $ticketValue->status == 0){

                                // $date = date('Y-m-d H:i:s');
                                // $datetime1 = new DateTime($ticketValue->created_at);
                                // $datetime2 = new DateTime($date);
                                // $interval = $datetime1->diff($datetime2);
                                // $due = $interval->d.' days ago';

                                $last_due ='Not yet';
                                $due = date('m/d/Y',strtotime($ticketValue->created_at));
                                if($ticketValue->last_response!='')
                                $last_due = date('m/d/Y',strtotime($ticketValue->last_response));

                                // if($ticketValue->status==1)
                                // $style = "background-color:#f7f782;";
                                // else
                                $style = "";
                        ?>
                       
                            <div class="kanban-item" ondragstart="dragStart(event)" ondrag="dragging(event)" draggable="true" id="support_<?= $ticketValue->id;?>">
                                <div class="card kanban-item-card hover-actions-trigger">
                                    <div class="card-body" style="<?= $style;?>">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                            <div>
                                         <?php
                                          $mark_flag_icon = $mark_important_icon = "";
                                          if($ticketValue->mark_flag ==1){
                                             $mark_flag_icon ='<i class="bx bxs-flag-alt text-yellow"></i>';
                                         }
                                         if($ticketValue->mark_important ==1){
                                             $mark_important_icon ='<i class="bx bxs-star text-primary"></i>';
                                         }
                                         echo  $mark_flag_icon."  ".$mark_important_icon ;  
                                                // LabelsData is a helper class
                                                foreach ($this->LabelsData->labelData($ticketValue->id) as $lableVal) :
                                                    if ($lableVal->label_name == 'New') {

                                                        $className = "success";
                                                    } else if ($lableVal->label_name == 'Goal') {

                                                        $className = "primary";
                                                    } else if ($lableVal->label_name == 'Enhancement') {

                                                        $className = "info";
                                                    } else if ($lableVal->label_name == 'Bug') {

                                                        $className = "danger";
                                                    } else if ($lableVal->label_name == 'Documentation') {

                                                        $className = "secondary";
                                                    } else if ($lableVal->label_name == 'Helper') {

                                                        $className = "warning";
                                                    }
                                                ?>
                                        <span class="badge py-1 me-1 mb-1 badge-soft-<?= $className ?>">
                                            <?= $lableVal->label_name ?>
                                        </span>
                                                   
                                        <?php
                                                endforeach;

                                                if($ticketValue->seen===0 && $ticketValue->client_id == $ticketValue->sender_id)
                                            {
                                                $icon = '<i class="menu-icon tf-icons bx bxs-comment-dots clr-theme"></i>';
                                            }
                                            else if($ticketValue->seen===0 && $ticketValue->client_id != $ticketValue->sender_id)
                                            {
                                                $icon = '<i class="menu-icon tf-icons bx bxs-comment-dots" style="color:#000;"></i>';
                                            }
                                            else
                                            {
                                                $icon = "";
                                            }
                                
                                                ?>
                                            </div>
                                            <div>
                                        <?= $icon; ?>
                                    </div>
                                                
                                        </div>
                                        <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                             <b>Client Name :</b> <?= $ticketValue->user; ?>
                                         </p>
                                         <?php if($ticketValue->store_specific == 3 || $ticketValue->store) { ?>
                                        <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)"> 
                                             <b>Store Name :</b> 
                                             <?php if($ticketValue->store_specific == 3)
                                             {
                                                echo "Store Not Listed In Portal";
                                             }else {
                                             echo $ticketValue->store."-(".$ticketValue->store_status.")";
                                             }
                                             
                                            ?>
                                         </p>
                                         <?php } ?>
                                         <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)"> 
                                             <b>Title :</b> <?= $ticketValue->title; ?>
                                         </p>
                                         <?php if($ticketValue->staff) { ?>
                                         <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                             <b>Assigned to :</b> <?= $ticketValue->staff; ?>
                                         </p>
                                         <?php } ?>
                                         <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                            <b>Manager :</b> <?php if($ticketValue->account_manager != ""){ echo  $ticketValue->account_manager; }else{ echo "-"; } ?>
                                        </p>
                                        <!-- watchers -->
                                        <?php if (count($ticketValue->watchers) > 0) : ?>
                                        <?php $watcherList = array_slice($ticketValue->watchers, 0, 1);?> 
                                            <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                            <b>Watchers :</b> <?php foreach ($watcherList as $val) : ?>
                                                                <span class="product-item"><?= $val->watcher_name; ?></span>
                                                            <?php endforeach;  
                                                            if(count($ticketValue->watchers) > 1): ?> 
                                                            <p class="product-item" style="margin-left:50px">+<?= count($ticketValue->watchers) - 1?> more</p>
                                                            <?php endif; ?> 
                                            </p>
                                        <?php endif; ?>
                                         <div class="row">
                                            <div class="col-md-6 p-0">
                                                <div class="kanban-item-footer justify-content-center">
                                                    <div class="text-500 z-index-2">
                                                        <span title="">Ticket Initiated:</span>
                                                    </div>
                                                    <p class="card-text">
                                                        <?= $due;?>
                                                    </p>
                                                </div>                                         
                                            </div>
                                            <div class="col-md-6 p-0">
                                                <div class="kanban-item-footer justify-content-center">
                                                    <div class="text-500 z-index-2">
                                                        <span title="">Last Response:</span>
                                                    </div>
                                                    <p class="card-text">
                                                        <?= $last_due;?>
                                                    </p>
                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                            </div>
                           
                       
                        <?php
                                }
                            endforeach; 
                            $ticketData1 = array_reverse($ticketData);
                            foreach ($ticketData1 as $ticketValue) :
                                if($ticketValue->issue_type==5 && $ticketValue->status == 1){

                                // $date = date('Y-m-d H:i:s');
                                // $datetime1 = new DateTime($ticketValue->created_at);
                                // $datetime2 = new DateTime($date);
                                // $interval = $datetime1->diff($datetime2);
                                // $due = $interval->d.' days ago';

                                $last_due ='Not yet';
                                $due = date('m/d/Y',strtotime($ticketValue->created_at));
                                if($ticketValue->last_response!='')
                                $last_due = date('m/d/Y',strtotime($ticketValue->last_response));

                                $style = "background-color:#f7f782;";
                        ?>
                       
                            <div class="kanban-item" ondragstart="dragStart(event)" ondrag="dragging(event)" draggable="true" id="support_<?= $ticketValue->id;?>">
                                <div class="card kanban-item-card hover-actions-trigger">
                                    <div class="card-body" style="<?= $style;?>">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                            <div>
                                         <?php
                                                $mark_flag_icon = $mark_important_icon = "";
                                                if($ticketValue->mark_flag ==1){
                                                    $mark_flag_icon ='<i class="bx bxs-flag-alt text-yellow"></i>';
                                                }
                                                if($ticketValue->mark_important ==1){
                                                    $mark_important_icon ='<i class="bx bxs-star text-primary"></i>';
                                                }
                                                echo  $mark_flag_icon."  ".$mark_important_icon ; 
                                                // LabelsData is a helper class
                                                foreach ($this->LabelsData->labelData($ticketValue->id) as $lableVal) :
                                                    if ($lableVal->label_name == 'New') {

                                                        $className = "success";
                                                    } else if ($lableVal->label_name == 'Goal') {

                                                        $className = "primary";
                                                    } else if ($lableVal->label_name == 'Enhancement') {

                                                        $className = "info";
                                                    } else if ($lableVal->label_name == 'Bug') {

                                                        $className = "danger";
                                                    } else if ($lableVal->label_name == 'Documentation') {

                                                        $className = "secondary";
                                                    } else if ($lableVal->label_name == 'Helper') {

                                                        $className = "warning";
                                                    }
                                                ?>
                                        <span class="badge py-1 me-1 mb-1 badge-soft-<?= $className ?>">
                                            <?= $lableVal->label_name ?>
                                        </span>
                                                   
                                        <?php
                                                endforeach;

                                                if($ticketValue->seen===0 && $ticketValue->client_id == $ticketValue->sender_id)
                                            {
                                                $icon = '<i class="menu-icon tf-icons bx bxs-comment-dots clr-theme"></i>';
                                            }
                                            else if($ticketValue->seen===0 && $ticketValue->client_id != $ticketValue->sender_id)
                                            {
                                                $icon = '<i class="menu-icon tf-icons bx bxs-comment-dots" style="color:#000;"></i>';
                                            }
                                            else
                                            {
                                                $icon = "";
                                            }
                                
                                                ?>
                                            </div>
                                            <div>
                                        <?= $icon; ?>
                                    </div>
                                                
                                        </div>
                                        <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                             <b>Client Name :</b> <?= $ticketValue->user; ?>
                                         </p>
                                         <?php if($ticketValue->store_specific == 3 || $ticketValue->store) { ?>
                                        <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)"> 
                                             <b>Store Name :</b> 
                                             <?php if($ticketValue->store_specific == 3)
                                             {
                                                echo "Store Not Listed In Portal";
                                             }else {
                                             echo $ticketValue->store."-(".$ticketValue->store_status.")";
                                             }
                                             
                                            ?>

                                         </p>
                                         <?php } ?>
                                         <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)"> 
                                             <b>Title :</b> <?= $ticketValue->title; ?>
                                         </p>
                                         <?php if($ticketValue->staff) { ?>
                                         <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                             <b>Assigned to :</b> <?= $ticketValue->staff; ?>
                                         </p>
                                         <?php } ?>
                                         <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                            <b>Manager :</b> <?php if($ticketValue->account_manager != ""){ echo  $ticketValue->account_manager; }else{ echo "-"; } ?>
                                        </p>
                                        <!-- watchers -->
                                        <?php if (count($ticketValue->watchers) > 0) : ?>
                                        <?php $watcherList = array_slice($ticketValue->watchers, 0, 1);?> 
                                            <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                            <b>Watchers :</b> <?php foreach ($watcherList as $val) : ?>
                                                                <span class="product-item"><?= $val->watcher_name; ?></span>
                                                            <?php endforeach;  
                                                            if(count($ticketValue->watchers) > 1): ?> 
                                                            <p class="product-item" style="margin-left:50px">+<?= count($ticketValue->watchers) - 1?> more</p>
                                                            <?php endif; ?> 
                                            </p>
                                        <?php endif; ?>
                                         <div class="row">
                                            <div class="col-md-6 p-0">
                                                <div class="kanban-item-footer justify-content-center">
                                                    <div class="text-500 z-index-2">
                                                        <span title="">Ticket Initiated:</span>
                                                    </div>
                                                    <p class="card-text">
                                                        <?= $due;?>
                                                    </p>
                                                </div>                                         
                                            </div>
                                            <div class="col-md-6 p-0">
                                                <div class="kanban-item-footer justify-content-center">
                                                    <div class="text-500 z-index-2">
                                                        <span title="">Last Response:</span>
                                                    </div>
                                                    <p class="card-text">
                                                        <?= $last_due;?>
                                                    </p>
                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                            </div>
                           
                       
                        <?php
                                }
                            endforeach; 
                            ?>
                            </div>
                            <?php
                        else :
                            ?>
                        <div class="kanban-items-container scrollbar ">
                            <div class="kanban-item">
                                <div class="card kanban-item-card hover-actions-trigger">
                                    <div class="card-body">
                                        <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal">
                                            No Data...
                                        </p>
                                        <div class="kanban-item-footer justify-content-end  cursor-default">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php } if(in_array(6,$issue)) {  ?>        
                    <div class="kanban-column ticketDrag" ondrop="drop(event)" ondragover="allowDrop(event)" data-issue="6">
                        <div class="kanban-column-header">
                            <h5 class="fs-0 mb-0">Inventory management <span
                                    class="text-500">(<?= $ticket6 ?>)</span></h5>
                        </div>

                        <?php
                        if ($ticket6 > 0) :
                            echo '<div class="kanban-items-container scrollbar ">';
                            foreach ($ticketData as $ticketValue) :
                                if($ticketValue->issue_type==6 && $ticketValue->status== 0){

                                // $date = date('Y-m-d H:i:s');
                                // $datetime1 = new DateTime($ticketValue->created_at);
                                // $datetime2 = new DateTime($date);
                                // $interval = $datetime1->diff($datetime2);
                                // $due = $interval->d.' days ago';

                                $last_due ='Not yet';
                                $due = date('m/d/Y',strtotime($ticketValue->created_at));
                                if($ticketValue->last_response!='')
                                $last_due = date('m/d/Y',strtotime($ticketValue->last_response));

                                // if($ticketValue->status==1)
                                // $style = "background-color:#f7f782;";
                                // else
                                $style = "";
                        ?>
                        
                            <div class="kanban-item" ondragstart="dragStart(event)" ondrag="dragging(event)" draggable="true" id="support_<?= $ticketValue->id;?>">
                                <div class="card kanban-item-card hover-actions-trigger">
                                    <div class="card-body" style="<?= $style;?>">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                            <div>
                                         <?php
                                          $mark_flag_icon = $mark_important_icon = "";
                                          if($ticketValue->mark_flag ==1){
                                             $mark_flag_icon ='<i class="bx bxs-flag-alt text-yellow"></i>';
                                         }
                                         if($ticketValue->mark_important ==1){
                                             $mark_important_icon ='<i class="bx bxs-star text-primary"></i>';
                                         }
                                         echo  $mark_flag_icon."  ".$mark_important_icon ;  
                                                // LabelsData is a helper class
                                                foreach ($this->LabelsData->labelData($ticketValue->id) as $lableVal) :
                                                    if ($lableVal->label_name == 'New') {

                                                        $className = "success";
                                                    } else if ($lableVal->label_name == 'Goal') {

                                                        $className = "primary";
                                                    } else if ($lableVal->label_name == 'Enhancement') {

                                                        $className = "info";
                                                    } else if ($lableVal->label_name == 'Bug') {

                                                        $className = "danger";
                                                    } else if ($lableVal->label_name == 'Documentation') {

                                                        $className = "secondary";
                                                    } else if ($lableVal->label_name == 'Helper') {

                                                        $className = "warning";
                                                    }
                                                ?>
                                        <span class="badge py-1 me-1 mb-1 badge-soft-<?= $className ?>">
                                            <?= $lableVal->label_name ?>
                                        </span>
                                                   
                                        <?php
                                                endforeach;

                                                if($ticketValue->seen===0 && $ticketValue->client_id == $ticketValue->sender_id)
                                            {
                                                $icon = '<i class="menu-icon tf-icons bx bxs-comment-dots clr-theme"></i>';
                                            }
                                            else if($ticketValue->seen===0 && $ticketValue->client_id != $ticketValue->sender_id)
                                            {
                                                $icon = '<i class="menu-icon tf-icons bx bxs-comment-dots" style="color:#000;"></i>';
                                            }
                                            else
                                            {
                                                $icon = "";
                                            }
                                
                                                ?>
                                            </div>
                                            <div>
                                        <?= $icon; ?>
                                    </div>
                                                
                                        </div>
                                        <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                             <b>Client Name :</b> <?= $ticketValue->user; ?>
                                         </p>
                                         <?php if($ticketValue->store_specific == 3 || $ticketValue->store) { ?>
                                        <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)"> 
                                             <b>Store Name :</b> 
                                             <?php if($ticketValue->store_specific == 3)
                                             {
                                                echo "Store Not Listed In Portal";
                                             }else {
                                             echo $ticketValue->store."-(".$ticketValue->store_status.")";
                                             }
                                             
                                            ?>
                                         </p>
                                         <?php } ?>
                                         <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)"> 
                                             <b>Title :</b> <?= $ticketValue->title; ?>
                                         </p>
                                         <?php if($ticketValue->staff) { ?>
                                         <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                             <b>Assigned to :</b> <?= $ticketValue->staff; ?>
                                         </p>
                                         <?php } ?>
                                         <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                            <b>Manager :</b> <?php if($ticketValue->account_manager != ""){ echo  $ticketValue->account_manager; }else{ echo "-"; } ?>
                                        </p>
                                        <!-- watchers -->
                                        <?php if (count($ticketValue->watchers) > 0) : ?>
                                        <?php $watcherList = array_slice($ticketValue->watchers, 0, 1);?> 
                                            <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                            <b>Watchers :</b> <?php foreach ($watcherList as $val) : ?>
                                                                <span class="product-item"><?= $val->watcher_name; ?></span>
                                                            <?php endforeach;  
                                                            if(count($ticketValue->watchers) > 1): ?> 
                                                            <p class="product-item" style="margin-left:50px">+<?= count($ticketValue->watchers) - 1?> more</p>
                                                            <?php endif; ?> 
                                            </p>
                                        <?php endif; ?>
                                         <div class="row">
                                            <div class="col-md-6 p-0">
                                                <div class="kanban-item-footer justify-content-center">
                                                    <div class="text-500 z-index-2">
                                                        <span title="">Ticket Initiated:</span>
                                                    </div>
                                                    <p class="card-text">
                                                        <?= $due;?>
                                                    </p>
                                                </div>                                         
                                            </div>
                                            <div class="col-md-6 p-0">
                                                <div class="kanban-item-footer justify-content-center">
                                                    <div class="text-500 z-index-2">
                                                        <span title="">Last Response:</span>
                                                    </div>
                                                    <p class="card-text">
                                                        <?= $last_due;?>
                                                    </p>
                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                            </div>
                           
                        
                        <?php
                                }
                            endforeach; 
                            $ticketData1 = array_reverse($ticketData);
                            foreach ($ticketData1 as $ticketValue) :
                                if($ticketValue->issue_type==6 && $ticketValue->status == 1){

                                $last_due ='Not yet';
                                $due = date('m/d/Y',strtotime($ticketValue->created_at));
                                if($ticketValue->last_response!='')
                                $last_due = date('m/d/Y',strtotime($ticketValue->last_response));

                                $style = "background-color:#f7f782;";
                        ?>
                        
                            <div class="kanban-item" ondragstart="dragStart(event)" ondrag="dragging(event)" draggable="true" id="support_<?= $ticketValue->id;?>">
                                <div class="card kanban-item-card hover-actions-trigger">
                                    <div class="card-body" style="<?= $style;?>">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                            <div>
                                         <?php
                                          $mark_flag_icon = $mark_important_icon = "";
                                          if($ticketValue->mark_flag ==1){
                                             $mark_flag_icon ='<i class="bx bxs-flag-alt text-yellow"></i>';
                                         }
                                         if($ticketValue->mark_important ==1){
                                             $mark_important_icon ='<i class="bx bxs-star text-primary"></i>';
                                         }
                                         echo  $mark_flag_icon."  ".$mark_important_icon ; 
                                                // LabelsData is a helper class
                                                foreach ($this->LabelsData->labelData($ticketValue->id) as $lableVal) :
                                                    if ($lableVal->label_name == 'New') {

                                                        $className = "success";
                                                    } else if ($lableVal->label_name == 'Goal') {

                                                        $className = "primary";
                                                    } else if ($lableVal->label_name == 'Enhancement') {

                                                        $className = "info";
                                                    } else if ($lableVal->label_name == 'Bug') {

                                                        $className = "danger";
                                                    } else if ($lableVal->label_name == 'Documentation') {

                                                        $className = "secondary";
                                                    } else if ($lableVal->label_name == 'Helper') {

                                                        $className = "warning";
                                                    }
                                                ?>
                                        <span class="badge py-1 me-1 mb-1 badge-soft-<?= $className ?>">
                                            <?= $lableVal->label_name ?>
                                        </span>
                                                   
                                        <?php
                                                endforeach;

                                                if($ticketValue->seen===0 && $ticketValue->client_id == $ticketValue->sender_id)
                                            {
                                                $icon = '<i class="menu-icon tf-icons bx bxs-comment-dots clr-theme"></i>';
                                            }
                                            else if($ticketValue->seen===0 && $ticketValue->client_id != $ticketValue->sender_id)
                                            {
                                                $icon = '<i class="menu-icon tf-icons bx bxs-comment-dots" style="color:#000;"></i>';
                                            }
                                            else
                                            {
                                                $icon = "";
                                            }
                                
                                                ?>
                                            </div>
                                            <div>
                                        <?= $icon; ?>
                                    </div>
                                                
                                        </div>
                                        <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                             <b>Client Name :</b> <?= $ticketValue->user; ?>
                                         </p>
                                         <?php if($ticketValue->store_specific == 3 || $ticketValue->store) { ?>
                                        <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)"> 
                                             <b>Store Name :</b> 
                                             <?php if($ticketValue->store_specific == 3)
                                             {
                                                echo "Store Not Listed In Portal";
                                             }else {
                                             echo $ticketValue->store."-(".$ticketValue->store_status.")";
                                             }
                                             
                                            ?>
                                         </p>
                                         <?php } ?>
                                         <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)"> 
                                             <b>Title :</b> <?= $ticketValue->title; ?>
                                         </p>
                                         <?php if($ticketValue->staff) { ?>
                                         <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                             <b>Assigned to :</b> <?= $ticketValue->staff; ?>
                                         </p>
                                         <?php } ?>
                                         <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                            <b>Manager :</b> <?php if($ticketValue->account_manager != ""){ echo  $ticketValue->account_manager; }else{ echo "-"; } ?>
                                        </p>
                                        <!-- watchers -->
                                        <?php if (count($ticketValue->watchers) > 0) : ?>
                                        <?php $watcherList = array_slice($ticketValue->watchers, 0, 1);?> 
                                            <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                            <b>Watchers :</b> <?php foreach ($watcherList as $val) : ?>
                                                                <span class="product-item"><?= $val->watcher_name; ?></span>
                                                            <?php endforeach;  
                                                            if(count($ticketValue->watchers) > 1): ?> 
                                                            <p class="product-item" style="margin-left:50px">+<?= count($ticketValue->watchers) - 1?> more</p>
                                                            <?php endif; ?> 
                                            </p>
                                        <?php endif; ?>
                                         <div class="row">
                                            <div class="col-md-6 p-0">
                                                <div class="kanban-item-footer justify-content-center">
                                                    <div class="text-500 z-index-2">
                                                        <span title="">Ticket Initiated:</span>
                                                    </div>
                                                    <p class="card-text">
                                                        <?= $due;?>
                                                    </p>
                                                </div>                                         
                                            </div>
                                            <div class="col-md-6 p-0">
                                                <div class="kanban-item-footer justify-content-center">
                                                    <div class="text-500 z-index-2">
                                                        <span title="">Last Response:</span>
                                                    </div>
                                                    <p class="card-text">
                                                        <?= $last_due;?>
                                                    </p>
                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                            </div>
                           
                        
                        <?php
                                }
                            endforeach; 
                            ?>
                            </div>
                            <?php
                        else :
                            ?>
                        <div class="kanban-items-container scrollbar ">
                            <div class="kanban-item">
                                <div class="card kanban-item-card hover-actions-trigger">
                                    <div class="card-body">
                                        <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal">
                                            No Data...
                                        </p>
                                        <div class="kanban-item-footer justify-content-end  cursor-default">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php //}if(in_array(7,$issue)) {  ?>        
                    <!-- <div class="kanban-column ticketDrag" ondrop="drop(event)" ondragover="allowDrop(event)" data-issue="7">
                        <div class="kanban-column-header">
                            <h5 class="fs-0 mb-0">TeamViewer/device is offline <span
                                    class="text-500">(<?= $ticket7 ?>)</span></h5>
                        </div>

                        <?php
                        if ($ticket7 > 0) :
                            echo '<div class="kanban-items-container scrollbar ">';
                            foreach ($ticketData as $ticketValue) :
                                if($ticketValue->issue_type==7 && $ticketValue->status== 0){

                                // $date = date('Y-m-d H:i:s');
                                // $datetime1 = new DateTime($ticketValue->created_at);
                                // $datetime2 = new DateTime($date);
                                // $interval = $datetime1->diff($datetime2);
                                // $due = $interval->d.' days ago';

                                $last_due ='Not yet';
                                $due = date('m/d/Y',strtotime($ticketValue->created_at));
                                if($ticketValue->last_response!='')
                                $last_due = date('m/d/Y',strtotime($ticketValue->last_response));

                                // if($ticketValue->status==1)
                                // $style = "background-color:#f7f782;";
                                // else
                                $style = "";
                        ?>
                        
                            <div class="kanban-item" ondragstart="dragStart(event)" ondrag="dragging(event)" draggable="true" id="support_<?= $ticketValue->id;?>">
                                <div class="card kanban-item-card hover-actions-trigger">
                                    <div class="card-body" style="<?= $style;?>">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                            <div>
                                         <?php
                                                // LabelsData is a helper class
                                                foreach ($this->LabelsData->labelData($ticketValue->id) as $lableVal) :
                                                    if ($lableVal->label_name == 'New') {

                                                        $className = "success";
                                                    } else if ($lableVal->label_name == 'Goal') {

                                                        $className = "primary";
                                                    } else if ($lableVal->label_name == 'Enhancement') {

                                                        $className = "info";
                                                    } else if ($lableVal->label_name == 'Bug') {

                                                        $className = "danger";
                                                    } else if ($lableVal->label_name == 'Documentation') {

                                                        $className = "secondary";
                                                    } else if ($lableVal->label_name == 'Helper') {

                                                        $className = "warning";
                                                    }
                                                ?>
                                        <span class="badge py-1 me-1 mb-1 badge-soft-<?= $className ?>">
                                            <?= $lableVal->label_name ?>
                                        </span>
                                                   
                                        <?php
                                                endforeach;

                                                if($ticketValue->seen===0 && $ticketValue->client_id == $ticketValue->sender_id)
                                        {
                                                $icon = '<i class="menu-icon tf-icons bx bxs-comment-dots clr-theme"></i>';
                                            }
                                            else if($ticketValue->seen===0 && $ticketValue->client_id != $ticketValue->sender_id)
                                            {
                                                $icon = '<i class="menu-icon tf-icons bx bxs-comment-dots" style="color:#000;"></i>';
                                            }
                                            else
                                            {
                                                $icon = "";
                                            }
                                
                                                ?>
                                            </div>
                                            <div>
                                        <?= $icon; ?>
                                    </div>
                                                
                                        </div>
                                        <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                             <b>Client Name :</b> <?= $ticketValue->user; ?>
                                         </p>
                                         <?php if($ticketValue->store) { ?>
                                        <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)"> 
                                             <b>Store Name :</b> <?= $ticketValue->store; ?>
                                         </p>
                                         <?php } ?>
                                         <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)"> 
                                             <b>Title :</b> <?= $ticketValue->title; ?>
                                         </p>
                                         <?php if($ticketValue->staff) { ?>
                                         <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                             <b>Assigned to :</b> <?= $ticketValue->staff; ?>
                                         </p>
                                         <?php } ?>
                                         <div class="row">
                                            <div class="col-md-6 p-0">
                                                <div class="kanban-item-footer justify-content-center">
                                                    <div class="text-500 z-index-2">
                                                        <span title="">Ticket Initiated:</span>
                                                    </div>
                                                    <p class="card-text">
                                                        <?= $due;?>
                                                    </p>
                                                </div>                                         
                                            </div>
                                            <div class="col-md-6 p-0">
                                                <div class="kanban-item-footer justify-content-center">
                                                    <div class="text-500 z-index-2">
                                                        <span title="">Last Response:</span>
                                                    </div>
                                                    <p class="card-text">
                                                        <?= $last_due;?>
                                                    </p>
                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                            </div>
                           
                        
                        <?php
                                }
                            endforeach; 
                            $ticketData1 = array_reverse($ticketData);
                            foreach ($ticketData1 as $ticketValue) :
                                if($ticketValue->issue_type==7 && $ticketValue->status == 1){

                                $last_due ='Not yet';
                                $due = date('m/d/Y',strtotime($ticketValue->created_at));
                                if($ticketValue->last_response!='')
                                $last_due = date('m/d/Y',strtotime($ticketValue->last_response));

                                $style = "background-color:#f7f782;";
                        ?>
                        
                            <div class="kanban-item" ondragstart="dragStart(event)" ondrag="dragging(event)" draggable="true" id="support_<?= $ticketValue->id;?>">
                                <div class="card kanban-item-card hover-actions-trigger">
                                    <div class="card-body" style="<?= $style;?>">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                            <div>
                                         <?php
                                                // LabelsData is a helper class
                                                foreach ($this->LabelsData->labelData($ticketValue->id) as $lableVal) :
                                                    if ($lableVal->label_name == 'New') {

                                                        $className = "success";
                                                    } else if ($lableVal->label_name == 'Goal') {

                                                        $className = "primary";
                                                    } else if ($lableVal->label_name == 'Enhancement') {

                                                        $className = "info";
                                                    } else if ($lableVal->label_name == 'Bug') {

                                                        $className = "danger";
                                                    } else if ($lableVal->label_name == 'Documentation') {

                                                        $className = "secondary";
                                                    } else if ($lableVal->label_name == 'Helper') {

                                                        $className = "warning";
                                                    }
                                                ?>
                                        <span class="badge py-1 me-1 mb-1 badge-soft-<?= $className ?>">
                                            <?= $lableVal->label_name ?>
                                        </span>
                                                   
                                        <?php
                                                endforeach;

                                                if($ticketValue->seen===0 && $ticketValue->client_id == $ticketValue->sender_id)
                                            {
                                                $icon = '<i class="menu-icon tf-icons bx bxs-comment-dots clr-theme"></i>';
                                            }
                                            else if($ticketValue->seen===0 && $ticketValue->client_id != $ticketValue->sender_id)
                                            {
                                                $icon = '<i class="menu-icon tf-icons bx bxs-comment-dots" style="color:#000;"></i>';
                                            }
                                            else
                                            {
                                                $icon = "";
                                            }
                                
                                                ?>
                                            </div>
                                            <div>
                                        <?= $icon; ?>
                                    </div>
                                                
                                        </div>
                                        <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                             <b>Client Name :</b> <?= $ticketValue->user; ?>
                                         </p>
                                         <?php if($ticketValue->store) { ?>
                                        <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)"> 
                                             <b>Store Name :</b> <?= $ticketValue->store; ?>
                                         </p>
                                         <?php } ?>
                                         <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)"> 
                                             <b>Title :</b> <?= $ticketValue->title; ?>
                                         </p>
                                         <?php if($ticketValue->staff) { ?>
                                         <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                             <b>Assigned to :</b> <?= $ticketValue->staff; ?>
                                         </p>
                                         <?php } ?>
                                         <div class="row">
                                            <div class="col-md-6 p-0">
                                                <div class="kanban-item-footer justify-content-center">
                                                    <div class="text-500 z-index-2">
                                                        <span title="">Ticket Initiated:</span>
                                                    </div>
                                                    <p class="card-text">
                                                        <?= $due;?>
                                                    </p>
                                                </div>                                         
                                            </div>
                                            <div class="col-md-6 p-0">
                                                <div class="kanban-item-footer justify-content-center">
                                                    <div class="text-500 z-index-2">
                                                        <span title="">Last Response:</span>
                                                    </div>
                                                    <p class="card-text">
                                                        <?= $last_due;?>
                                                    </p>
                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                            </div>
                           
                        
                        <?php
                                }
                            endforeach; 
                            ?>
                            </div>
                            <?php
                        else :
                            ?>
                        <div class="kanban-items-container scrollbar ">
                            <div class="kanban-item">
                                <div class="card kanban-item-card hover-actions-trigger">
                                    <div class="card-body">
                                        <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal">
                                            No Data...
                                        </p>
                                        <div class="kanban-item-footer justify-content-end  cursor-default">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div> -->
                    <?php }if(in_array(8,$issue)) {  ?>        
                    <div class="kanban-column ticketDrag" ondrop="drop(event)" ondragover="allowDrop(event)" data-issue="8">
                        <div class="kanban-column-header">
                            <h5 class="fs-0 mb-0">E-comm 360/E-comm Tax Service <span
                                    class="text-500">(<?= $ticket8 ?>)</span></h5>
                        </div>

                        <?php
                        if ($ticket8 > 0) :
                            echo '<div class="kanban-items-container scrollbar ">';
                            foreach ($ticketData as $ticketValue) :
                                if($ticketValue->issue_type==8 && $ticketValue->status== 0){

                                // $date = date('Y-m-d H:i:s');
                                // $datetime1 = new DateTime($ticketValue->created_at);
                                // $datetime2 = new DateTime($date);
                                // $interval = $datetime1->diff($datetime2);
                                // $due = $interval->d.' days ago';

                                $last_due ='Not yet';
                                $due = date('m/d/Y',strtotime($ticketValue->created_at));
                                if($ticketValue->last_response!='')
                                $last_due = date('m/d/Y',strtotime($ticketValue->last_response));

                                // if($ticketValue->status==1)
                                // $style = "background-color:#f7f782;";
                                // else
                                $style = "";
                        ?>
                        
                            <div class="kanban-item" ondragstart="dragStart(event)" ondrag="dragging(event)" draggable="true" id="support_<?= $ticketValue->id;?>">
                                <div class="card kanban-item-card hover-actions-trigger">
                                    <div class="card-body" style="<?= $style;?>">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                            <div>
                                         <?php
                                          $mark_flag_icon = $mark_important_icon = "";
                                          if($ticketValue->mark_flag ==1){
                                             $mark_flag_icon ='<i class="bx bxs-flag-alt text-yellow"></i>';
                                         }
                                         if($ticketValue->mark_important ==1){
                                             $mark_important_icon ='<i class="bx bxs-star text-primary"></i>';
                                         }
                                         echo  $mark_flag_icon."  ".$mark_important_icon ;  
                                                // LabelsData is a helper class
                                                foreach ($this->LabelsData->labelData($ticketValue->id) as $lableVal) :
                                                    if ($lableVal->label_name == 'New') {

                                                        $className = "success";
                                                    } else if ($lableVal->label_name == 'Goal') {

                                                        $className = "primary";
                                                    } else if ($lableVal->label_name == 'Enhancement') {

                                                        $className = "info";
                                                    } else if ($lableVal->label_name == 'Bug') {

                                                        $className = "danger";
                                                    } else if ($lableVal->label_name == 'Documentation') {

                                                        $className = "secondary";
                                                    } else if ($lableVal->label_name == 'Helper') {

                                                        $className = "warning";
                                                    }
                                                ?>
                                        <span class="badge py-1 me-1 mb-1 badge-soft-<?= $className ?>">
                                            <?= $lableVal->label_name ?>
                                        </span>
                                                   
                                        <?php
                                                endforeach;

                                                if($ticketValue->seen===0 && $ticketValue->client_id == $ticketValue->sender_id)
                                            {
                                                $icon = '<i class="menu-icon tf-icons bx bxs-comment-dots clr-theme"></i>';
                                            }
                                            else if($ticketValue->seen===0 && $ticketValue->client_id != $ticketValue->sender_id)
                                            {
                                                $icon = '<i class="menu-icon tf-icons bx bxs-comment-dots" style="color:#000;"></i>';
                                            }
                                            else
                                            {
                                                $icon = "";
                                            }
                                
                                                ?>
                                            </div>
                                            <div>
                                        <?= $icon; ?>
                                    </div>
                                                
                                        </div>
                                        <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                             <b>Client Name :</b> <?= $ticketValue->user; ?>
                                         </p>
                                         <?php if($ticketValue->store_specific == 3 || $ticketValue->store) { ?>
                                        <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)"> 
                                             <b>Store Name :</b> 
                                             <?php if($ticketValue->store_specific == 3)
                                             {
                                                echo "Store Not Listed In Portal";
                                             }else {
                                             echo $ticketValue->store."-(".$ticketValue->store_status.")";
                                             }
                                             
                                            ?>
                                         </p>
                                         <?php } ?>
                                         <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)"> 
                                             <b>Title :</b> <?= $ticketValue->title; ?>
                                         </p>
                                         <?php if($ticketValue->staff) { ?>
                                         <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                             <b>Assigned to :</b> <?= $ticketValue->staff; ?>
                                         </p>
                                         <?php } ?>
                                         <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                            <b>Manager :</b> <?php if($ticketValue->account_manager != ""){ echo  $ticketValue->account_manager; }else{ echo "-"; } ?>
                                        </p>
                                        <!-- watchers -->
                                        <?php if (count($ticketValue->watchers) > 0) : ?>
                                        <?php $watcherList = array_slice($ticketValue->watchers, 0, 1);?> 
                                            <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                            <b>Watchers :</b> <?php foreach ($watcherList as $val) : ?>
                                                                <span class="product-item"><?= $val->watcher_name; ?></span>
                                                            <?php endforeach;  
                                                            if(count($ticketValue->watchers) > 1): ?> 
                                                            <p class="product-item" style="margin-left:50px">+<?= count($ticketValue->watchers) - 1?> more</p>
                                                            <?php endif; ?> 
                                            </p>
                                        <?php endif; ?>
                                         <div class="row">
                                            <div class="col-md-6 p-0">
                                                <div class="kanban-item-footer justify-content-center">
                                                    <div class="text-500 z-index-2">
                                                        <span title="">Ticket Initiated:</span>
                                                    </div>
                                                    <p class="card-text">
                                                        <?= $due;?>
                                                    </p>
                                                </div>                                         
                                            </div>
                                            <div class="col-md-6 p-0">
                                                <div class="kanban-item-footer justify-content-center">
                                                    <div class="text-500 z-index-2">
                                                        <span title="">Last Response:</span>
                                                    </div>
                                                    <p class="card-text">
                                                        <?= $last_due;?>
                                                    </p>
                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                            </div>
                           
                        
                        <?php
                                }
                            endforeach; 
                            $ticketData1 = array_reverse($ticketData);
                            foreach ($ticketData1 as $ticketValue) :
                                if($ticketValue->issue_type==8 && $ticketValue->status == 1){

                                $last_due ='Not yet';
                                $due = date('m/d/Y',strtotime($ticketValue->created_at));
                                if($ticketValue->last_response!='')
                                $last_due = date('m/d/Y',strtotime($ticketValue->last_response));

                                $style = "background-color:#f7f782;";
                        ?>
                        
                            <div class="kanban-item" ondragstart="dragStart(event)" ondrag="dragging(event)" draggable="true" id="support_<?= $ticketValue->id;?>">
                                <div class="card kanban-item-card hover-actions-trigger">
                                    <div class="card-body" style="<?= $style;?>">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                            <div>
                                         <?php
                                                // LabelsData is a helper class
                                                foreach ($this->LabelsData->labelData($ticketValue->id) as $lableVal) :
                                                    if ($lableVal->label_name == 'New') {

                                                        $className = "success";
                                                    } else if ($lableVal->label_name == 'Goal') {

                                                        $className = "primary";
                                                    } else if ($lableVal->label_name == 'Enhancement') {

                                                        $className = "info";
                                                    } else if ($lableVal->label_name == 'Bug') {

                                                        $className = "danger";
                                                    } else if ($lableVal->label_name == 'Documentation') {

                                                        $className = "secondary";
                                                    } else if ($lableVal->label_name == 'Helper') {

                                                        $className = "warning";
                                                    }
                                                ?>
                                        <span class="badge py-1 me-1 mb-1 badge-soft-<?= $className ?>">
                                            <?= $lableVal->label_name ?>
                                        </span>
                                                   
                                        <?php
                                                endforeach;

                                                if($ticketValue->seen===0 && $ticketValue->client_id == $ticketValue->sender_id)
                                            {
                                                $icon = '<i class="menu-icon tf-icons bx bxs-comment-dots clr-theme"></i>';
                                            }
                                            else if($ticketValue->seen===0 && $ticketValue->client_id != $ticketValue->sender_id)
                                            {
                                                $icon = '<i class="menu-icon tf-icons bx bxs-comment-dots" style="color:#000;"></i>';
                                            }
                                            else
                                            {
                                                $icon = "";
                                            }
                                
                                                ?>
                                            </div>
                                            <div>
                                        <?= $icon; ?>
                                    </div>
                                                
                                        </div>
                                        <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                             <b>Client Name :</b> <?= $ticketValue->user; ?>
                                         </p>
                                         <?php if($ticketValue->store_specific == 3 || $ticketValue->store) { ?>
                                        <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)"> 
                                             <b>Store Name :</b> 
                                             <?php if($ticketValue->store_specific == 3)
                                             {
                                                echo "Store Not Listed In Portal";
                                             }else {
                                             echo $ticketValue->store."-(".$ticketValue->store_status.")";
                                             }
                                             
                                            ?>
                                         </p>
                                         <?php } ?>
                                         <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)"> 
                                             <b>Title :</b> <?= $ticketValue->title; ?>
                                         </p>
                                         <?php if($ticketValue->staff) { ?>
                                         <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                             <b>Assigned to :</b> <?= $ticketValue->staff; ?>
                                         </p>
                                         <?php } ?>
                                         <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                            <b>Manager :</b> <?php if($ticketValue->account_manager != ""){ echo  $ticketValue->account_manager; }else{ echo "-"; } ?>
                                        </p>
                                        <!-- watchers -->
                                        <?php if (count($ticketValue->watchers) > 0) : ?>
                                        <?php $watcherList = array_slice($ticketValue->watchers, 0, 1);?> 
                                            <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                            <b>Watchers :</b> <?php foreach ($watcherList as $val) : ?>
                                                                <span class="product-item"><?= $val->watcher_name; ?></span>
                                                            <?php endforeach;  
                                                            if(count($ticketValue->watchers) > 1): ?> 
                                                            <p class="product-item" style="margin-left:50px">+<?= count($ticketValue->watchers) - 1?> more</p>
                                                            <?php endif; ?> 
                                            </p>
                                        <?php endif; ?>
                                         <div class="row">
                                            <div class="col-md-6 p-0">
                                                <div class="kanban-item-footer justify-content-center">
                                                    <div class="text-500 z-index-2">
                                                        <span title="">Ticket Initiated:</span>
                                                    </div>
                                                    <p class="card-text">
                                                        <?= $due;?>
                                                    </p>
                                                </div>                                         
                                            </div>
                                            <div class="col-md-6 p-0">
                                                <div class="kanban-item-footer justify-content-center">
                                                    <div class="text-500 z-index-2">
                                                        <span title="">Last Response:</span>
                                                    </div>
                                                    <p class="card-text">
                                                        <?= $last_due;?>
                                                    </p>
                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                            </div>
                           
                        
                        <?php
                                }
                            endforeach; 
                            ?>
                            </div>
                            <?php
                        else :
                            ?>
                        <div class="kanban-items-container scrollbar ">
                            <div class="kanban-item">
                                <div class="card kanban-item-card hover-actions-trigger">
                                    <div class="card-body">
                                        <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal">
                                            No Data...
                                        </p>
                                        <div class="kanban-item-footer justify-content-end  cursor-default">
                                        
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php } if(in_array(10,$issue)) {  ?>        
                    <div class="kanban-column ticketDrag" ondrop="drop(event)" ondragover="allowDrop(event)" data-issue="10">
                        <div class="kanban-column-header">
                            <h5 class="fs-0 mb-0">Walmart Stores <span
                                    class="text-500">(<?= $ticket10 ?>)</span></h5>
                        </div>

                        <?php
                        if ($ticket10 > 0) :
                            echo '<div class="kanban-items-container scrollbar ">';
                            foreach ($ticketData as $ticketValue) :
                                if($ticketValue->issue_type==10 && $ticketValue->status== 0){

                                // $date = date('Y-m-d H:i:s');
                                // $datetime1 = new DateTime($ticketValue->created_at);
                                // $datetime2 = new DateTime($date);
                                // $interval = $datetime1->diff($datetime2);
                                // $due = $interval->d.' days ago';

                                $last_due ='Not yet';
                                $due = date('m/d/Y',strtotime($ticketValue->created_at));
                                if($ticketValue->last_response!='')
                                $last_due = date('m/d/Y',strtotime($ticketValue->last_response));

                                // if($ticketValue->status==1)
                                // $style = "background-color:#f7f782;";
                                // else
                                $style = "";
                        ?>
                        
                            <div class="kanban-item" ondragstart="dragStart(event)" ondrag="dragging(event)" draggable="true" id="support_<?= $ticketValue->id;?>">
                                <div class="card kanban-item-card hover-actions-trigger">
                                    <div class="card-body" style="<?= $style;?>">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                            <div>
                                         <?php
                                          $mark_flag_icon = $mark_important_icon = "";
                                          if($ticketValue->mark_flag ==1){
                                             $mark_flag_icon ='<i class="bx bxs-flag-alt text-yellow"></i>';
                                         }
                                         if($ticketValue->mark_important ==1){
                                             $mark_important_icon ='<i class="bx bxs-star text-primary"></i>';
                                         }
                                         echo  $mark_flag_icon."  ".$mark_important_icon ;  
                                                // LabelsData is a helper class
                                                foreach ($this->LabelsData->labelData($ticketValue->id) as $lableVal) :
                                                    if ($lableVal->label_name == 'New') {

                                                        $className = "success";
                                                    } else if ($lableVal->label_name == 'Goal') {

                                                        $className = "primary";
                                                    } else if ($lableVal->label_name == 'Enhancement') {

                                                        $className = "info";
                                                    } else if ($lableVal->label_name == 'Bug') {

                                                        $className = "danger";
                                                    } else if ($lableVal->label_name == 'Documentation') {

                                                        $className = "secondary";
                                                    } else if ($lableVal->label_name == 'Helper') {

                                                        $className = "warning";
                                                    }
                                                ?>
                                        <span class="badge py-1 me-1 mb-1 badge-soft-<?= $className ?>">
                                            <?= $lableVal->label_name ?>
                                        </span>
                                                   
                                        <?php
                                                endforeach;

                                                if($ticketValue->seen===0 && $ticketValue->client_id == $ticketValue->sender_id)
                                            {
                                                $icon = '<i class="menu-icon tf-icons bx bxs-comment-dots clr-theme"></i>';
                                            }
                                            else if($ticketValue->seen===0 && $ticketValue->client_id != $ticketValue->sender_id)
                                            {
                                                $icon = '<i class="menu-icon tf-icons bx bxs-comment-dots" style="color:#000;"></i>';
                                            }
                                            else
                                            {
                                                $icon = "";
                                            }
                                
                                                ?>
                                            </div>
                                            <div>
                                        <?= $icon; ?>
                                    </div>
                                                
                                        </div>
                                        <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                             <b>Client Name :</b> <?= $ticketValue->user; ?>
                                         </p>
                                         <?php if($ticketValue->store_specific == 3 || $ticketValue->store) { ?>
                                        <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)"> 
                                             <b>Store Name :</b> 
                                             <?php if($ticketValue->store_specific == 3)
                                             {
                                                echo "Store Not Listed In Portal";
                                             }else {
                                             echo $ticketValue->store."-(".$ticketValue->store_status.")";
                                             }
                                             
                                            ?>
                                         </p>
                                         <?php } ?>
                                         <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)"> 
                                             <b>Title :</b> <?= $ticketValue->title; ?>
                                         </p>
                                         <?php if($ticketValue->staff) { ?>
                                         <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                             <b>Assigned to :</b> <?= $ticketValue->staff; ?>
                                         </p>
                                         <?php } ?>
                                         <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                            <b>Manager :</b> <?php if($ticketValue->account_manager != ""){ echo  $ticketValue->account_manager; }else{ echo "-"; } ?>
                                        </p>
                                        <!-- watchers -->
                                        <?php if (count($ticketValue->watchers) > 0) : ?>
                                        <?php $watcherList = array_slice($ticketValue->watchers, 0, 1);?> 
                                            <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                            <b>Watchers :</b> <?php foreach ($watcherList as $val) : ?>
                                                                <span class="product-item"><?= $val->watcher_name; ?></span>
                                                            <?php endforeach;  
                                                            if(count($ticketValue->watchers) > 1): ?> 
                                                            <p class="product-item" style="margin-left:50px">+<?= count($ticketValue->watchers) - 1?> more</p>
                                                            <?php endif; ?> 
                                            </p>
                                        <?php endif; ?>
                                         <div class="row">
                                            <div class="col-md-6 p-0">
                                                <div class="kanban-item-footer justify-content-center">
                                                    <div class="text-500 z-index-2">
                                                        <span title="">Ticket Initiated:</span>
                                                    </div>
                                                    <p class="card-text">
                                                        <?= $due;?>
                                                    </p>
                                                </div>                                         
                                            </div>
                                            <div class="col-md-6 p-0">
                                                <div class="kanban-item-footer justify-content-center">
                                                    <div class="text-500 z-index-2">
                                                        <span title="">Last Response:</span>
                                                    </div>
                                                    <p class="card-text">
                                                        <?= $last_due;?>
                                                    </p>
                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                            </div>
                           
                        
                        <?php
                                }
                            endforeach; 
                            $ticketData1 = array_reverse($ticketData);
                            foreach ($ticketData1 as $ticketValue) :
                                if($ticketValue->issue_type==10 && $ticketValue->status == 1){

                                $last_due ='Not yet';
                                $due = date('m/d/Y',strtotime($ticketValue->created_at));
                                if($ticketValue->last_response!='')
                                $last_due = date('m/d/Y',strtotime($ticketValue->last_response));

                                $style = "background-color:#f7f782;";
                        ?>
                        
                            <div class="kanban-item" ondragstart="dragStart(event)" ondrag="dragging(event)" draggable="true" id="support_<?= $ticketValue->id;?>">
                                <div class="card kanban-item-card hover-actions-trigger">
                                    <div class="card-body" style="<?= $style;?>">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                            <div>
                                         <?php
                                                // LabelsData is a helper class
                                                foreach ($this->LabelsData->labelData($ticketValue->id) as $lableVal) :
                                                    if ($lableVal->label_name == 'New') {

                                                        $className = "success";
                                                    } else if ($lableVal->label_name == 'Goal') {

                                                        $className = "primary";
                                                    } else if ($lableVal->label_name == 'Enhancement') {

                                                        $className = "info";
                                                    } else if ($lableVal->label_name == 'Bug') {

                                                        $className = "danger";
                                                    } else if ($lableVal->label_name == 'Documentation') {

                                                        $className = "secondary";
                                                    } else if ($lableVal->label_name == 'Helper') {

                                                        $className = "warning";
                                                    }
                                                ?>
                                        <span class="badge py-1 me-1 mb-1 badge-soft-<?= $className ?>">
                                            <?= $lableVal->label_name ?>
                                        </span>
                                                   
                                        <?php
                                                endforeach;

                                                if($ticketValue->seen===0 && $ticketValue->client_id == $ticketValue->sender_id)
                                            {
                                                $icon = '<i class="menu-icon tf-icons bx bxs-comment-dots clr-theme"></i>';
                                            }
                                            else if($ticketValue->seen===0 && $ticketValue->client_id != $ticketValue->sender_id)
                                            {
                                                $icon = '<i class="menu-icon tf-icons bx bxs-comment-dots" style="color:#000;"></i>';
                                            }
                                            else
                                            {
                                                $icon = "";
                                            }
                                
                                                ?>
                                            </div>
                                            <div>
                                        <?= $icon; ?>
                                    </div>
                                                
                                        </div>
                                        <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                             <b>Client Name :</b> <?= $ticketValue->user; ?>
                                         </p>
                                         <?php if($ticketValue->store_specific == 3 || $ticketValue->store) { ?>
                                        <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)"> 
                                             <b>Store Name :</b> 
                                             <?php if($ticketValue->store_specific == 3)
                                             {
                                                echo "Store Not Listed In Portal";
                                             }else {
                                             echo $ticketValue->store."-(".$ticketValue->store_status.")";
                                             }
                                             
                                            ?>
                                         </p>
                                         <?php } ?>
                                         <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)"> 
                                             <b>Title :</b> <?= $ticketValue->title; ?>
                                         </p>
                                         <?php if($ticketValue->staff) { ?>
                                         <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                             <b>Assigned to :</b> <?= $ticketValue->staff; ?>
                                         </p>
                                         <?php } ?>
                                         <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                            <b>Manager :</b> <?php if($ticketValue->account_manager != ""){ echo  $ticketValue->account_manager; }else{ echo "-"; } ?>
                                        </p>
                                        <!-- watchers -->
                                        <?php if (count($ticketValue->watchers) > 0) : ?>
                                        <?php $watcherList = array_slice($ticketValue->watchers, 0, 1);?> 
                                            <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                            <b>Watchers :</b> <?php foreach ($watcherList as $val) : ?>
                                                                <span class="product-item"><?= $val->watcher_name; ?></span>
                                                            <?php endforeach;  
                                                            if(count($ticketValue->watchers) > 1): ?> 
                                                            <p class="product-item" style="margin-left:50px">+<?= count($ticketValue->watchers) - 1?> more</p>
                                                            <?php endif; ?> 
                                            </p>
                                        <?php endif; ?>
                                         <div class="row">
                                            <div class="col-md-6 p-0">
                                                <div class="kanban-item-footer justify-content-center">
                                                    <div class="text-500 z-index-2">
                                                        <span title="">Ticket Initiated:</span>
                                                    </div>
                                                    <p class="card-text">
                                                        <?= $due;?>
                                                    </p>
                                                </div>                                         
                                            </div>
                                            <div class="col-md-6 p-0">
                                                <div class="kanban-item-footer justify-content-center">
                                                    <div class="text-500 z-index-2">
                                                        <span title="">Last Response:</span>
                                                    </div>
                                                    <p class="card-text">
                                                        <?= $last_due;?>
                                                    </p>
                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                            </div>
                           
                        
                        <?php
                                }
                            endforeach; 
                            ?>
                            </div>
                            <?php
                        else :
                            ?>
                        <div class="kanban-items-container scrollbar ">
                            <div class="kanban-item">
                                <div class="card kanban-item-card hover-actions-trigger">
                                    <div class="card-body">
                                        <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal">
                                            No Data...
                                        </p>
                                        <div class="kanban-item-footer justify-content-end  cursor-default">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php } if (in_array(13,$issue)) { ?>
                    <div class="kanban-column ticketDrag" ondrop="drop(event)" ondragover="allowDrop(event)" data-issue="13">
                        <div class="kanban-column-header">
                            <h5 class="fs-0 mb-0">Amazon Questions <span
                                    class="text-500">(<?= $ticket13 ?>)</span></h5>
                        </div>

                        <?php
                        if ($ticket13 > 0) :
                            echo '<div class="kanban-items-container scrollbar ">';
                            foreach ($ticketData as $ticketValue) :
                                if($ticketValue->issue_type==13 && $ticketValue->status== 0){

                                $last_due ='Not yet';
                                $due = date('m/d/Y',strtotime($ticketValue->created_at));
                                if($ticketValue->last_response!='')
                                $last_due = date('m/d/Y',strtotime($ticketValue->last_response));

                                $style = "";
                        ?>
                        
                            <div class="kanban-item" ondragstart="dragStart(event)" ondrag="dragging(event)" draggable="true" id="support_<?= $ticketValue->id;?>">
                                <div class="card kanban-item-card hover-actions-trigger">
                                    <div class="card-body" style="<?= $style;?>">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                            <div>
                                         <?php
                                          $mark_flag_icon = $mark_important_icon = "";
                                          if($ticketValue->mark_flag ==1){
                                             $mark_flag_icon ='<i class="bx bxs-flag-alt text-yellow"></i>';
                                         }
                                         if($ticketValue->mark_important ==1){
                                             $mark_important_icon ='<i class="bx bxs-star text-primary"></i>';
                                         }
                                         echo  $mark_flag_icon."  ".$mark_important_icon ;  
                                                // LabelsData is a helper class
                                                foreach ($this->LabelsData->labelData($ticketValue->id) as $lableVal) :
                                                    if ($lableVal->label_name == 'New') {

                                                        $className = "success";
                                                    } else if ($lableVal->label_name == 'Goal') {

                                                        $className = "primary";
                                                    } else if ($lableVal->label_name == 'Enhancement') {

                                                        $className = "info";
                                                    } else if ($lableVal->label_name == 'Bug') {

                                                        $className = "danger";
                                                    } else if ($lableVal->label_name == 'Documentation') {

                                                        $className = "secondary";
                                                    } else if ($lableVal->label_name == 'Helper') {

                                                        $className = "warning";
                                                    }
                                                ?>
                                        <span class="badge py-1 me-1 mb-1 badge-soft-<?= $className ?>">
                                            <?= $lableVal->label_name ?>
                                        </span>
                                                   
                                        <?php
                                                endforeach;

                                                if($ticketValue->seen===0 && $ticketValue->client_id == $ticketValue->sender_id)
                                            {
                                                $icon = '<i class="menu-icon tf-icons bx bxs-comment-dots clr-theme"></i>';
                                            }
                                            else if($ticketValue->seen===0 && $ticketValue->client_id != $ticketValue->sender_id)
                                            {
                                                $icon = '<i class="menu-icon tf-icons bx bxs-comment-dots" style="color:#000;"></i>';
                                            }
                                            else
                                            {
                                                $icon = "";
                                            }
                                
                                                ?>
                                            </div>
                                            <div>
                                        <?= $icon; ?>
                                    </div>
                                                
                                        </div>
                                        <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                             <b>Client Name :</b> <?= $ticketValue->user; ?>
                                         </p>
                                         <?php if($ticketValue->store_specific == 3 || $ticketValue->store) { ?>
                                        <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)"> 
                                             <b>Store Name :</b> 
                                             <?php if($ticketValue->store_specific == 3)
                                             {
                                                echo "Store Not Listed In Portal";
                                             }else {
                                             echo $ticketValue->store."-(".$ticketValue->store_status.")";
                                             }
                                             
                                            ?>
                                         </p>
                                         <?php } ?>
                                         <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)"> 
                                             <b>Title :</b> <?= $ticketValue->title; ?>
                                         </p>
                                         <?php if($ticketValue->staff) { ?>
                                         <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                             <b>Assigned to :</b> <?= $ticketValue->staff; ?>
                                         </p>
                                         <?php } ?>
                                         <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                            <b>Manager :</b> <?php if($ticketValue->account_manager != ""){ echo  $ticketValue->account_manager; }else{ echo "-"; } ?>
                                        </p>
                                        <!-- watchers -->
                                        <?php if (count($ticketValue->watchers) > 0) : ?>
                                        <?php $watcherList = array_slice($ticketValue->watchers, 0, 1);?> 
                                            <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                            <b>Watchers :</b> <?php foreach ($watcherList as $val) : ?>
                                                                <span class="product-item"><?= $val->watcher_name; ?></span>
                                                            <?php endforeach;  
                                                            if(count($ticketValue->watchers) > 1): ?> 
                                                            <p class="product-item" style="margin-left:50px">+<?= count($ticketValue->watchers) - 1?> more</p>
                                                            <?php endif; ?> 
                                            </p>
                                        <?php endif; ?>
                                         <div class="row">
                                            <div class="col-md-6 p-0">
                                                <div class="kanban-item-footer justify-content-center">
                                                    <div class="text-500 z-index-2">
                                                        <span title="">Ticket Initiated:</span>
                                                    </div>
                                                    <p class="card-text">
                                                        <?= $due;?>
                                                    </p>
                                                </div>                                         
                                            </div>
                                            <div class="col-md-6 p-0">
                                                <div class="kanban-item-footer justify-content-center">
                                                    <div class="text-500 z-index-2">
                                                        <span title="">Last Response:</span>
                                                    </div>
                                                    <p class="card-text">
                                                        <?= $last_due;?>
                                                    </p>
                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                            </div>
                           
                        
                        <?php
                                }
                            endforeach; 
                            $ticketData1 = array_reverse($ticketData);
                            foreach ($ticketData1 as $ticketValue) :
                                if($ticketValue->issue_type==13 && $ticketValue->status == 1){

                                $last_due ='Not yet';
                                $due = date('m/d/Y',strtotime($ticketValue->created_at));
                                if($ticketValue->last_response!='')
                                $last_due = date('m/d/Y',strtotime($ticketValue->last_response));

                                $style = "background-color:#f7f782;";
                        ?>
                        
                            <div class="kanban-item" ondragstart="dragStart(event)" ondrag="dragging(event)" draggable="true" id="support_<?= $ticketValue->id;?>">
                                <div class="card kanban-item-card hover-actions-trigger">
                                    <div class="card-body" style="<?= $style;?>">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                            <div>
                                         <?php
                                                // LabelsData is a helper class
                                                foreach ($this->LabelsData->labelData($ticketValue->id) as $lableVal) :
                                                    if ($lableVal->label_name == 'New') {

                                                        $className = "success";
                                                    } else if ($lableVal->label_name == 'Goal') {

                                                        $className = "primary";
                                                    } else if ($lableVal->label_name == 'Enhancement') {

                                                        $className = "info";
                                                    } else if ($lableVal->label_name == 'Bug') {

                                                        $className = "danger";
                                                    } else if ($lableVal->label_name == 'Documentation') {

                                                        $className = "secondary";
                                                    } else if ($lableVal->label_name == 'Helper') {

                                                        $className = "warning";
                                                    }
                                                ?>
                                        <span class="badge py-1 me-1 mb-1 badge-soft-<?= $className ?>">
                                            <?= $lableVal->label_name ?>
                                        </span>
                                                   
                                        <?php
                                                endforeach;

                                                if($ticketValue->seen===0 && $ticketValue->client_id == $ticketValue->sender_id)
                                            {
                                                $icon = '<i class="menu-icon tf-icons bx bxs-comment-dots clr-theme"></i>';
                                            }
                                            else if($ticketValue->seen===0 && $ticketValue->client_id != $ticketValue->sender_id)
                                            {
                                                $icon = '<i class="menu-icon tf-icons bx bxs-comment-dots" style="color:#000;"></i>';
                                            }
                                            else
                                            {
                                                $icon = "";
                                            }
                                
                                                ?>
                                            </div>
                                            <div>
                                        <?= $icon; ?>
                                    </div>
                                                
                                        </div>
                                        <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                             <b>Client Name :</b> <?= $ticketValue->user; ?>
                                         </p>
                                         <?php if($ticketValue->store_specific == 3 || $ticketValue->store) { ?>
                                        <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)"> 
                                             <b>Store Name :</b> 
                                             <?php if($ticketValue->store_specific == 3)
                                             {
                                                echo "Store Not Listed In Portal";
                                             }else {
                                             echo $ticketValue->store."-(".$ticketValue->store_status.")";
                                             }
                                             
                                            ?>
                                         </p>
                                         <?php } ?>
                                         <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)"> 
                                             <b>Title :</b> <?= $ticketValue->title; ?>
                                         </p>
                                         <?php if($ticketValue->staff) { ?>
                                         <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                             <b>Assigned to :</b> <?= $ticketValue->staff; ?>
                                         </p>
                                         <?php } ?>
                                         <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                            <b>Manager :</b> <?php if($ticketValue->account_manager != ""){ echo  $ticketValue->account_manager; }else{ echo "-"; } ?>
                                        </p>
                                        <!-- watchers -->
                                        <?php if (count($ticketValue->watchers) > 0) : ?>
                                        <?php $watcherList = array_slice($ticketValue->watchers, 0, 1);?> 
                                            <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                            <b>Watchers :</b> <?php foreach ($watcherList as $val) : ?>
                                                                <span class="product-item"><?= $val->watcher_name; ?></span>
                                                            <?php endforeach;  
                                                            if(count($ticketValue->watchers) > 1): ?> 
                                                            <p class="product-item" style="margin-left:50px">+<?= count($ticketValue->watchers) - 1?> more</p>
                                                            <?php endif; ?> 
                                            </p>
                                        <?php endif; ?>
                                         <div class="row">
                                            <div class="col-md-6 p-0">
                                                <div class="kanban-item-footer justify-content-center">
                                                    <div class="text-500 z-index-2">
                                                        <span title="">Ticket Initiated:</span>
                                                    </div>
                                                    <p class="card-text">
                                                        <?= $due;?>
                                                    </p>
                                                </div>                                         
                                            </div>
                                            <div class="col-md-6 p-0">
                                                <div class="kanban-item-footer justify-content-center">
                                                    <div class="text-500 z-index-2">
                                                        <span title="">Last Response:</span>
                                                    </div>
                                                    <p class="card-text">
                                                        <?= $last_due;?>
                                                    </p>
                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                            </div>
                           
                        
                        <?php
                                }
                            endforeach; 
                            ?>
                            </div>
                            <?php
                        else :
                            ?>
                        <div class="kanban-items-container scrollbar ">
                            <div class="kanban-item">
                                <div class="card kanban-item-card hover-actions-trigger">
                                    <div class="card-body">
                                        <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal">
                                            No Data...
                                        </p>
                                        <div class="kanban-item-footer justify-content-end  cursor-default">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div> 
                    <?php } if (in_array(12,$issue)) { ?>
                    <div class="kanban-column ticketDrag" ondrop="drop(event)" ondragover="allowDrop(event)" data-issue="12">
                        <div class="kanban-column-header">
                            <h5 class="fs-0 mb-0">Store Transfer 
                                <span class="text-500">(<?= $ticket12 ?>)</span>
                            </h5>
                        </div>

                        <?php
                        if ($ticket12 > 0) :
                            echo '<div class="kanban-items-container scrollbar ">';
                            foreach ($ticketData as $ticketValue) :
                                if($ticketValue->issue_type==12 && $ticketValue->status == 0){
                                $last_due ='Not yet';
                                $due = date('m/d/Y',strtotime($ticketValue->created_at));
                                if($ticketValue->last_response!='')
                                $last_due = date('m/d/Y',strtotime($ticketValue->last_response));
                                $style = "";
                        ?>
                        
                            <div class="kanban-item" ondragstart="dragStart(event)" ondrag="dragging(event)" draggable="true" id="support_<?= $ticketValue->id;?>">
                                <div class="card kanban-item-card hover-actions-trigger">
                                    <div class="card-body" style="<?= $style;?>">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                            <div>
                                         <?php
                                          $mark_flag_icon = $mark_important_icon = "";
                                          if($ticketValue->mark_flag ==1){
                                             $mark_flag_icon ='<i class="bx bxs-flag-alt text-yellow"></i>';
                                         }
                                         if($ticketValue->mark_important ==1){
                                             $mark_important_icon ='<i class="bx bxs-star text-primary"></i>';
                                         }
                                         echo  $mark_flag_icon."  ".$mark_important_icon ;  
                                                // LabelsData is a helper class
                                                foreach ($this->LabelsData->labelData($ticketValue->id) as $lableVal) :
                                                    if ($lableVal->label_name == 'New') {

                                                        $className = "success";
                                                    } else if ($lableVal->label_name == 'Goal') {

                                                        $className = "primary";
                                                    } else if ($lableVal->label_name == 'Enhancement') {

                                                        $className = "info";
                                                    } else if ($lableVal->label_name == 'Bug') {

                                                        $className = "danger";
                                                    } else if ($lableVal->label_name == 'Documentation') {

                                                        $className = "secondary";
                                                    } else if ($lableVal->label_name == 'Helper') {

                                                        $className = "warning";
                                                    }
                                                ?>
                                        <span class="badge py-1 me-1 mb-1 badge-soft-<?= $className ?>">
                                            <?= $lableVal->label_name ?>
                                        </span>
                                                   
                                        <?php
                                                endforeach;

                                                if($ticketValue->seen===0 && $ticketValue->client_id == $ticketValue->sender_id)
                                            {
                                                $icon = '<i class="menu-icon tf-icons bx bxs-comment-dots clr-theme"></i>';
                                            }
                                            else if($ticketValue->seen===0 && $ticketValue->client_id != $ticketValue->sender_id)
                                            {
                                                $icon = '<i class="menu-icon tf-icons bx bxs-comment-dots" style="color:#000;"></i>';
                                            }
                                            else
                                            {
                                                $icon = "";
                                            }
                                
                                                ?>
                                            </div>
                                            <div>
                                        <?= $icon; ?>
                                    </div>
                                                
                                        </div>
                                        <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                             <b>Client Name :</b> <?= $ticketValue->user; ?>
                                         </p>
                                         <?php if($ticketValue->store_specific == 3 || $ticketValue->store) { ?>
                                        <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)"> 
                                             <b>Store Name :</b> 
                                             <?php if($ticketValue->store_specific == 3)
                                             {
                                                echo "Store Not Listed In Portal";
                                             }else {
                                             echo $ticketValue->store."-(".$ticketValue->store_status.")";
                                             }
                                             
                                            ?>
                                         </p>
                                         <?php } ?>
                                         <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)"> 
                                             <b>Title :</b> <?= $ticketValue->title; ?>
                                         </p>
                                         <?php if($ticketValue->staff) { ?>
                                         <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                             <b>Assigned to :</b> <?= $ticketValue->staff; ?>
                                         </p>
                                         <?php } ?>
                                         <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                            <b>Manager :</b> <?php if($ticketValue->account_manager != ""){ echo  $ticketValue->account_manager; }else{ echo "-"; } ?>
                                        </p>
                                        <!-- watchers -->
                                        <?php if (count($ticketValue->watchers) > 0) : ?>
                                        <?php $watcherList = array_slice($ticketValue->watchers, 0, 1);?> 
                                            <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                            <b>Watchers :</b> <?php foreach ($watcherList as $val) : ?>
                                                                <span class="product-item"><?= $val->watcher_name; ?></span>
                                                            <?php endforeach;  
                                                            if(count($ticketValue->watchers) > 1): ?> 
                                                            <p class="product-item" style="margin-left:50px">+<?= count($ticketValue->watchers) - 1?> more</p>
                                                            <?php endif; ?> 
                                            </p>
                                        <?php endif; ?>
                                         <div class="row">
                                            <div class="col-md-6 p-0">
                                                <div class="kanban-item-footer justify-content-center">
                                                    <div class="text-500 z-index-2">
                                                        <span title="">Ticket Initiated:</span>
                                                    </div>
                                                    <p class="card-text">
                                                        <?= $due;?>
                                                    </p>
                                                </div>                                         
                                            </div>
                                            <div class="col-md-6 p-0">
                                                <div class="kanban-item-footer justify-content-center">
                                                    <div class="text-500 z-index-2">
                                                        <span title="">Last Response:</span>
                                                    </div>
                                                    <p class="card-text">
                                                        <?= $last_due;?>
                                                    </p>
                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                            </div>
                           
                        
                        <?php
                                }
                            endforeach; 
                            $ticketData1 = array_reverse($ticketData);
                            foreach ($ticketData1 as $ticketValue) :
                                if($ticketValue->issue_type==12 && $ticketValue->status == 1){

                                $last_due ='Not yet';
                                $due = date('m/d/Y',strtotime($ticketValue->created_at));
                                if($ticketValue->last_response!='')
                                $last_due = date('m/d/Y',strtotime($ticketValue->last_response));

                                $style = "background-color:#f7f782;";
                        ?>
                        
                            <div class="kanban-item" ondragstart="dragStart(event)" ondrag="dragging(event)" draggable="true" id="support_<?= $ticketValue->id;?>">
                                <div class="card kanban-item-card hover-actions-trigger">
                                    <div class="card-body" style="<?= $style;?>">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                            <div>
                                         <?php
                                                // LabelsData is a helper class
                                                foreach ($this->LabelsData->labelData($ticketValue->id) as $lableVal) :
                                                    if ($lableVal->label_name == 'New') {

                                                        $className = "success";
                                                    } else if ($lableVal->label_name == 'Goal') {

                                                        $className = "primary";
                                                    } else if ($lableVal->label_name == 'Enhancement') {

                                                        $className = "info";
                                                    } else if ($lableVal->label_name == 'Bug') {

                                                        $className = "danger";
                                                    } else if ($lableVal->label_name == 'Documentation') {

                                                        $className = "secondary";
                                                    } else if ($lableVal->label_name == 'Helper') {

                                                        $className = "warning";
                                                    }
                                                ?>
                                        <span class="badge py-1 me-1 mb-1 badge-soft-<?= $className ?>">
                                            <?= $lableVal->label_name ?>
                                        </span>
                                                   
                                        <?php
                                                endforeach;

                                                if($ticketValue->seen===0 && $ticketValue->client_id == $ticketValue->sender_id)
                                            {
                                                $icon = '<i class="menu-icon tf-icons bx bxs-comment-dots clr-theme"></i>';
                                            }
                                            else if($ticketValue->seen===0 && $ticketValue->client_id != $ticketValue->sender_id)
                                            {
                                                $icon = '<i class="menu-icon tf-icons bx bxs-comment-dots" style="color:#000;"></i>';
                                            }
                                            else
                                            {
                                                $icon = "";
                                            }
                                
                                                ?>
                                            </div>
                                            <div>
                                        <?= $icon; ?>
                                    </div>
                                                
                                        </div>
                                        <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                             <b>Client Name :</b> <?= $ticketValue->user; ?>
                                         </p>
                                         <?php if($ticketValue->store_specific == 3 || $ticketValue->store) { ?>
                                        <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)"> 
                                             <b>Store Name :</b> 
                                             <?php if($ticketValue->store_specific == 3)
                                             {
                                                echo "Store Not Listed In Portal";
                                             }else {
                                             echo $ticketValue->store."-(".$ticketValue->store_status.")";
                                             }
                                             
                                            ?>
                                         </p>
                                         <?php } ?>
                                         <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)"> 
                                             <b>Title :</b> <?= $ticketValue->title; ?>
                                         </p>
                                         <?php if($ticketValue->staff) { ?>
                                         <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                             <b>Assigned to :</b> <?= $ticketValue->staff; ?>
                                         </p>
                                         <?php } ?>
                                         <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                            <b>Manager :</b> <?php if($ticketValue->account_manager != ""){ echo  $ticketValue->account_manager; }else{ echo "-"; } ?>
                                        </p>
                                        <!-- watchers -->
                                        <?php if (count($ticketValue->watchers) > 0) : ?>
                                        <?php $watcherList = array_slice($ticketValue->watchers, 0, 1);?> 
                                            <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                            <b>Watchers :</b> <?php foreach ($watcherList as $val) : ?>
                                                                <span class="product-item"><?= $val->watcher_name; ?></span>
                                                            <?php endforeach;  
                                                            if(count($ticketValue->watchers) > 1): ?> 
                                                            <p class="product-item" style="margin-left:50px">+<?= count($ticketValue->watchers) - 1?> more</p>
                                                            <?php endif; ?> 
                                            </p>
                                        <?php endif; ?>
                                         <div class="row">
                                            <div class="col-md-6 p-0">
                                                <div class="kanban-item-footer justify-content-center">
                                                    <div class="text-500 z-index-2">
                                                        <span title="">Ticket Initiated:</span>
                                                    </div>
                                                    <p class="card-text">
                                                        <?= $due;?>
                                                    </p>
                                                </div>                                         
                                            </div>
                                            <div class="col-md-6 p-0">
                                                <div class="kanban-item-footer justify-content-center">
                                                    <div class="text-500 z-index-2">
                                                        <span title="">Last Response:</span>
                                                    </div>
                                                    <p class="card-text">
                                                        <?= $last_due;?>
                                                    </p>
                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                            </div>
                           
                        
                        <?php
                                }
                            endforeach; 
                            ?>
                            </div>
                            <?php
                        else :
                            ?>
                        <div class="kanban-items-container scrollbar ">
                            <div class="kanban-item">
                                <div class="card kanban-item-card hover-actions-trigger">
                                    <div class="card-body">
                                        <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal">
                                            No Data...
                                        </p>
                                        <div class="kanban-item-footer justify-content-end  cursor-default">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php } if(in_array(11,$issue)) { ?>
                        <div class="kanban-column ticketDrag" ondrop="drop(event)" ondragover="allowDrop(event)" data-issue="11">
                            <div class="kanban-column-header">
                                <h5 class="fs-0 mb-0">Case management <span
                                        class="text-500">(<?= $ticket11 ?>)</span></h5>
                            </div>

                            <?php
                            if ($ticket11 > 0) :
                                echo '<div class="kanban-items-container scrollbar ">';
                                foreach ($ticketData as $ticketValue) :
                                    if($ticketValue->issue_type==11 && $ticketValue->status== 0){

                                    // $date = date('Y-m-d H:i:s');
                                    // $datetime1 = new DateTime($ticketValue->created_at);
                                    // $datetime2 = new DateTime($date);
                                    // $interval = $datetime1->diff($datetime2);
                                    // $due = $interval->d.' days ago';

                                    $last_due ='Not yet';
                                    $due = date('m/d/Y',strtotime($ticketValue->created_at));
                                    if($ticketValue->last_response!='')
                                    $last_due = date('m/d/Y',strtotime($ticketValue->last_response));

                                    // if($ticketValue->status==1)
                                    // $style = "background-color:#f7f782;";
                                    // else
                                    $style = "";
                            ?>
                        
                            <div class="kanban-item" ondragstart="dragStart(event)" ondrag="dragging(event)" draggable="true" id="support_<?= $ticketValue->id;?>">
                                <div class="card kanban-item-card hover-actions-trigger">
                                    <div class="card-body" style="<?= $style;?>">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                            <div>
                                         <?php
                                          $mark_flag_icon = $mark_important_icon = "";
                                          if($ticketValue->mark_flag ==1){
                                             $mark_flag_icon ='<i class="bx bxs-flag-alt text-yellow"></i>';
                                         }
                                         if($ticketValue->mark_important ==1){
                                             $mark_important_icon ='<i class="bx bxs-star text-primary"></i>';
                                         }
                                         echo  $mark_flag_icon."  ".$mark_important_icon ;  
                                                // LabelsData is a helper class
                                                foreach ($this->LabelsData->labelData($ticketValue->id) as $lableVal) :
                                                    if ($lableVal->label_name == 'New') {

                                                        $className = "success";
                                                    } else if ($lableVal->label_name == 'Goal') {

                                                        $className = "primary";
                                                    } else if ($lableVal->label_name == 'Enhancement') {

                                                        $className = "info";
                                                    } else if ($lableVal->label_name == 'Bug') {

                                                        $className = "danger";
                                                    } else if ($lableVal->label_name == 'Documentation') {

                                                        $className = "secondary";
                                                    } else if ($lableVal->label_name == 'Helper') {

                                                        $className = "warning";
                                                    }
                                                ?>
                                        <span class="badge py-1 me-1 mb-1 badge-soft-<?= $className ?>">
                                            <?= $lableVal->label_name ?>
                                        </span>
                                                   
                                        <?php
                                                endforeach;

                                            if($ticketValue->seen===0 && $ticketValue->client_id == $ticketValue->sender_id)
                                            {
                                                $icon = '<i class="menu-icon tf-icons bx bxs-comment-dots clr-theme"></i>';
                                            }
                                            else if($ticketValue->seen===0 && $ticketValue->client_id != $ticketValue->sender_id)
                                            {
                                                $icon = '<i class="menu-icon tf-icons bx bxs-comment-dots" style="color:#000;"></i>';
                                            }
                                            else
                                            {
                                                $icon = "";
                                            }
                                
                                                ?>
                                            </div>
                                            <div>
                                        <?= $icon; ?>
                                    </div>
                                                
                                        </div>
                                        <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                             <b>Client Name :</b> <?= $ticketValue->user; ?>
                                         </p>
                                         <?php if($ticketValue->store_specific == 3 || $ticketValue->store) { ?>
                                        <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)"> 
                                             <b>Store Name :</b> 
                                             <?php if($ticketValue->store_specific == 3)
                                             {
                                                echo "Store Not Listed In Portal";
                                             }else {
                                             echo $ticketValue->store."-(".$ticketValue->store_status.")";
                                             }
                                             
                                            ?>
                                         </p>
                                         <?php } ?>
                                         <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)"> 
                                             <b>Title :</b> <?= $ticketValue->title; ?>
                                         </p>
                                         <?php if($ticketValue->staff) { ?>
                                         <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                             <b>Assigned to :</b> <?= $ticketValue->staff; ?>
                                         </p>
                                         <?php } ?>
                                         <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                            <b>Manager :</b> <?php if($ticketValue->account_manager != ""){ echo  $ticketValue->account_manager; }else{ echo "-"; } ?>
                                        </p>
                                        <!-- watchers -->
                                        <?php if (count($ticketValue->watchers) > 0) : ?>
                                        <?php $watcherList = array_slice($ticketValue->watchers, 0, 1);?> 
                                            <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                            <b>Watchers :</b> <?php foreach ($watcherList as $val) : ?>
                                                                <span class="product-item"><?= $val->watcher_name; ?></span>
                                                            <?php endforeach;  
                                                            if(count($ticketValue->watchers) > 1): ?> 
                                                            <p class="product-item" style="margin-left:50px">+<?= count($ticketValue->watchers) - 1?> more</p>
                                                            <?php endif; ?> 
                                            </p>
                                        <?php endif; ?>
                                         <div class="row">
                                            <div class="col-md-6 p-0">
                                                <div class="kanban-item-footer justify-content-center">
                                                    <div class="text-500 z-index-2">
                                                        <span title="">Ticket Initiated:</span>
                                                    </div>
                                                    <p class="card-text">
                                                        <?= $due;?>
                                                    </p>
                                                </div>                                         
                                            </div>
                                            <div class="col-md-6 p-0">
                                                <div class="kanban-item-footer justify-content-center">
                                                    <div class="text-500 z-index-2">
                                                        <span title="">Last Response:</span>
                                                    </div>
                                                    <p class="card-text">
                                                        <?= $last_due;?>
                                                    </p>
                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                            </div>
                           
                        
                            <?php
                                }
                                endforeach; 
                                $ticketData1 = array_reverse($ticketData);
                                foreach ($ticketData1 as $ticketValue) :
                                    if($ticketValue->issue_type==11 && $ticketValue->status == 1){

                                    $last_due ='Not yet';
                                    $due = date('m/d/Y',strtotime($ticketValue->created_at));
                                    if($ticketValue->last_response!='')
                                    $last_due = date('m/d/Y',strtotime($ticketValue->last_response));

                                    $style = "background-color:#f7f782;";
                            ?>
                        
                            <div class="kanban-item" ondragstart="dragStart(event)" ondrag="dragging(event)" draggable="true" id="support_<?= $ticketValue->id;?>">
                                <div class="card kanban-item-card hover-actions-trigger">
                                    <div class="card-body" style="<?= $style;?>">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                            <div>
                                         <?php
                                                // LabelsData is a helper class
                                                foreach ($this->LabelsData->labelData($ticketValue->id) as $lableVal) :
                                                    if ($lableVal->label_name == 'New') {

                                                        $className = "success";
                                                    } else if ($lableVal->label_name == 'Goal') {

                                                        $className = "primary";
                                                    } else if ($lableVal->label_name == 'Enhancement') {

                                                        $className = "info";
                                                    } else if ($lableVal->label_name == 'Bug') {

                                                        $className = "danger";
                                                    } else if ($lableVal->label_name == 'Documentation') {

                                                        $className = "secondary";
                                                    } else if ($lableVal->label_name == 'Helper') {

                                                        $className = "warning";
                                                    }
                                                ?>
                                        <span class="badge py-1 me-1 mb-1 badge-soft-<?= $className ?>">
                                            <?= $lableVal->label_name ?>
                                        </span>
                                                   
                                        <?php
                                                endforeach;

                                            if($ticketValue->seen===0 && $ticketValue->client_id == $ticketValue->sender_id)
                                            {
                                                $icon = '<i class="menu-icon tf-icons bx bxs-comment-dots clr-theme"></i>';
                                            }
                                            else if($ticketValue->seen===0 && $ticketValue->client_id != $ticketValue->sender_id)
                                            {
                                                $icon = '<i class="menu-icon tf-icons bx bxs-comment-dots" style="color:#000;"></i>';
                                            }
                                            else
                                            {
                                                $icon = "";
                                            }
                                
                                                ?>
                                            </div>
                                            <div>
                                        <?= $icon; ?>
                                    </div>
                                                
                                        </div>
                                        <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                             <b>Client Name :</b> <?= $ticketValue->user; ?>
                                         </p>
                                         <?php if($ticketValue->store_specific == 3 || $ticketValue->store) { ?>
                                        <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)"> 
                                             <b>Store Name :</b> 
                                             <?php if($ticketValue->store_specific == 3)
                                             {
                                                echo "Store Not Listed In Portal";
                                             }else {
                                             echo $ticketValue->store."-(".$ticketValue->store_status.")";
                                             }
                                             
                                            ?>
                                         </p>
                                         <?php } ?>
                                         <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)"> 
                                             <b>Title :</b> <?= $ticketValue->title; ?>
                                         </p>
                                         <?php if($ticketValue->staff) { ?>
                                         <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                             <b>Assigned to :</b> <?= $ticketValue->staff; ?>
                                         </p>
                                         <?php } ?>
                                         <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                            <b>Manager :</b> <?php if($ticketValue->account_manager != ""){ echo  $ticketValue->account_manager; }else{ echo "-"; } ?>
                                        </p>
                                        <!-- watchers -->
                                        <?php if (count($ticketValue->watchers) > 0) : ?>
                                        <?php $watcherList = array_slice($ticketValue->watchers, 0, 1);?> 
                                            <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                            <b>Watchers :</b> <?php foreach ($watcherList as $val) : ?>
                                                                <span class="product-item"><?= $val->watcher_name; ?></span>
                                                            <?php endforeach;  
                                                            if(count($ticketValue->watchers) > 1): ?> 
                                                            <p class="product-item" style="margin-left:50px">+<?= count($ticketValue->watchers) - 1?> more</p>
                                                            <?php endif; ?> 
                                            </p>
                                        <?php endif; ?>
                                         <div class="row">
                                            <div class="col-md-6 p-0">
                                                <div class="kanban-item-footer justify-content-center">
                                                    <div class="text-500 z-index-2">
                                                        <span title="">Ticket Initiated:</span>
                                                    </div>
                                                    <p class="card-text">
                                                        <?= $due;?>
                                                    </p>
                                                </div>                                         
                                            </div>
                                            <div class="col-md-6 p-0">
                                                <div class="kanban-item-footer justify-content-center">
                                                    <div class="text-500 z-index-2">
                                                        <span title="">Last Response:</span>
                                                    </div>
                                                    <p class="card-text">
                                                        <?= $last_due;?>
                                                    </p>
                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                            </div>
                           
                        
                            <?php
                                }
                            endforeach; 
                            ?>
                            </div>
                            <?php
                            else :
                                ?>
                            <div class="kanban-items-container scrollbar ">
                                <div class="kanban-item">
                                    <div class="card kanban-item-card hover-actions-trigger">
                                        <div class="card-body">
                                            <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal">
                                                No Data...
                                            </p>
                                            <div class="kanban-item-footer justify-content-end  cursor-default">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    <?php }?>             
                    <!-- Closed Column -->
                  <?php if (!empty($issue)) { ?>
                    <div class="kanban-column ticketDrag" ondrop="drop(event)" ondragover="allowDrop(event)" data-issue="20">
                        <div class="kanban-column-header">
                            <h5 class="fs-0 mb-0">Closed <span class="text-500">(<?= $closed ?>)</span></h5>
                        </div>

                        <?php
                        if ($closed > 0) :
                            echo '<div class="kanban-items-container scrollbar ">';
                            foreach ($ticketData as $ticketValue) :
                                if($ticketValue->status==3){

                                $date = date('Y-m-d H:i:s');
                                $datetime1 = new DateTime($ticketValue->updated_at);
                                $datetime2 = new DateTime($date);
                                $interval = $datetime1->diff($datetime2);

                                $due = $interval->d.' days ago';
                        ?>
                        
                            <div class="kanban-item">
                                <div class="card kanban-item-card hover-actions-trigger">
                                    <div class="card-body">
                                        <div class="mb-2">
                                            <?php
                                             $mark_flag_icon = $mark_important_icon = "";
                                             if($ticketValue->mark_flag ==1){
                                                $mark_flag_icon ='<i class="bx bxs-flag-alt text-yellow"></i>';
                                            }
                                            if($ticketValue->mark_important ==1){
                                                $mark_important_icon ='<i class="bx bxs-star text-primary"></i>';
                                            }
                                            echo  $mark_flag_icon."  ".$mark_important_icon ;  
                                                    // LabelsData is a helper class
                                                    foreach ($this->LabelsData->labelData($ticketValue->id) as $lableVal) :
                                                        if ($lableVal->label_name == 'New') {

                                                            $className = "success";
                                                        } else if ($lableVal->label_name == 'Goal') {

                                                            $className = "primary";
                                                        } else if ($lableVal->label_name == 'Enhancement') {

                                                            $className = "info";
                                                        } else if ($lableVal->label_name == 'Bug') {

                                                            $className = "danger";
                                                        } else if ($lableVal->label_name == 'Documentation') {

                                                            $className = "secondary";
                                                        } else if ($lableVal->label_name == 'Helper') {

                                                            $className = "warning";
                                                        }
                                                    ?>
                                            <span class="badge py-1 me-1 mb-1 badge-soft-<?= $className ?>">
                                                <?= $lableVal->label_name ?>
                                            </span>
                                            <?php
                                                    endforeach;
                                                    ?>
                                        </div>
                                        <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                             <b>Client Name :</b> <?= $ticketValue->user; ?>
                                         </p>
                                         <?php if($ticketValue->store_specific == 3 || $ticketValue->store) { ?>
                                        <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)"> 
                                             <b>Store Name :</b>
                                             <?php if($ticketValue->store_specific == 3)
                                             {
                                                echo "Store Not Listed In Portal";
                                             }else {
                                             echo $ticketValue->store."-(".$ticketValue->store_status.")";
                                             }
                                             
                                            ?>
                                         </p>
                                         <?php } ?>
                                         <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)"> 
                                             <b>Title :</b> <?= $ticketValue->title; ?>
                                         </p>
                                         <?php if($ticketValue->staff) { ?>
                                         <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                             <b>Assigned to :</b> <?= $ticketValue->staff; ?>
                                         </p>
                                         <?php } ?>
                                         <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                            <b>Manager :</b> <?php if($ticketValue->account_manager != ""){ echo  $ticketValue->account_manager; }else{ echo "-"; } ?>
                                        </p>
                                        <!-- watchers -->
                                        <?php if (count($ticketValue->watchers) > 0) : ?>
                                        <?php $watcherList = array_slice($ticketValue->watchers, 0, 1);?> 
                                            <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal" onclick="kanban(<?= $ticketValue->id ?>)">
                                            <b>Watchers :</b> <?php foreach ($watcherList as $val) : ?>
                                                                <span class="product-item"><?= $val->watcher_name; ?></span>
                                                            <?php endforeach;  
                                                            if(count($ticketValue->watchers) > 1): ?> 
                                                            <p class="product-item" style="margin-left:50px">+<?= count($ticketValue->watchers) - 1?> more</p>
                                                            <?php endif; ?> 
                                            </p>
                                        <?php endif; ?>
                                         <div class="kanban-item-footer justify-content-end cursor-default">
                                             <div class="text-500 z-index-2"><span class="me-2" title="">Ticket Closed:</span></div>
                                             <p class="card-text">
                                                 <?= $due;?>
                                             </p>
                                         </div>
                                    </div>
                                </div>
                            </div>
                            
                       
                        <?php
                                }
                            endforeach; ?>
                            </div>
                            <?php
                        else :
                            ?>
                        <div class="kanban-items-container scrollbar ">
                            <div class="kanban-item">
                                <div class="card kanban-item-card hover-actions-trigger">
                                    <div class="card-body">
                                        <p class="mb-0 fw-medium font-sans-serif stretched-link" data-bs-toggle="modal">
                                            No Data...
                                        </p>
                                        <div class="kanban-item-footer justify-content-end  cursor-default">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php } ?>
                </div>
            </div>
             
                <!-- Start Engagement Widget Script -->
                <!-- <script async
   crossorigin
   type="module"
   id="engagementWidget"
   src="https://cdn.chatwidgets.net/widget/livechat/bundle.js"
   data-env="portal-api"
   data-instance="ZGK_0M8Seo0SG4am"
   data-container="#engagement-widget-container"></script> -->
<!-- End Engagement Widget Script -->
                          
            <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="kanban-modal-label-1"
                aria-hidden="true" id="kanban-modal-1">

            </div>
            <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="kanban-modal-label-1"
                aria-hidden="true" id="kanban-modal-launchDetail">

            </div>

        </div>
    </main>
</div>
<!-- / Content -->




<!-- Add Tickets Modal -->
<div class="modal fade" id="basicModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1">Add Ticket</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?= $this->Form->create(null, [
                    'id' => 'addTicketForm',
                    'method' => 'post',
                    'enctype' => 'multipart/form-data',
                    'action' => 'Tickets/addTicket',
                    'onsubmit' => 'this.querySelector("[name=\'submitButton\']").disabled = true; return true;'
                ])
                ?>
                <?php
                if ($loginUser->user_type != 2) :
                    //
                ?>
                <div class="mb-3">
                    <label class="form-label" for="basic-icon-default-email">For Client</label>
                    <select class="js-example-ticket-client" name="client_id" onchange="fetchStoreData(this,'addTicketStore')" style="width: 100%" required>
                        <option value="0">Select Client</option>
                        <?php
                            foreach ($client as $clientVal) :
                            ?>
                        <option value="<?= $clientVal->id ?>"><?= $clientVal->first_name ?>
                            <?= $clientVal->last_name ?></option>
                        <?php
                            endforeach;
                            ?>
                    </select>
                </div>
                <?php
                endif;
                ?>

                <div class="mb-3">
                    <label for="defaultSelect" class="form-label">Issue Type</label>
                    <select id="defaultSelect" name="issue_type" class="form-select" onchange="departmentIssue(this)" required>
                        <option value="">Select Issue</option>
                        <option value="1">General Support</option>
                        <option value="2">Billing</option>
                        <option value="3">Refer a friend</option>
                        <option value="4">Onboarding a new store</option>
                        <option value="5">Portal Support</option>
                        <option value="6">Inventory management</option>
                        <!-- <option value="7">TeamViewer/device is offline</option> -->
                        <option value="8">E-comm 360/E-comm Tax Service</option>
                        <!-- <option value="9">Multilogin</option> -->
                        <option value="10">Walmart Stores</option>
                        <?php if($loginUser->user_type == 1 || $loginUser->user_type == 0): ?>
                            <option value="11">Case management</option>
                            <?php endif; ?>
                        <option value="12">Store Transfer</option>
                        <option value="13">Amazon Questions</option>
                    </select>
                </div>
                <!-- <div class="mb-1 storeRadio">
                    <label class="form-label">Is the Ticket</label><br>
                    
                        <div class="form-check form-check-inline mb-1">
                            <input class="form-check-input" type="radio" id="inlineRadio1"
                                name="store_specific" value="yes" onclick="storeYesNo('yes')">
                            <label class="form-check-label fw-semibold"
                                for="inlineRadio1" onclick="storeYesNo('yes')">Store Specific</label>
                        </div>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" id="inlineRadio2"
                                name="store_specific" value="no" onclick="storeYesNo('no')">
                            <label class="form-check-label fw-semibold"
                                for="inlineRadio2" onclick="storeYesNo('no')">Not Store Specific</label>
                        </div>  
                        
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" id="inlineRadio3"
                                name="store_specific" value="yesno" onclick="storeYesNo('yesno')">
                            <label class="form-check-label fw-semibold" for="inlineRadio3" onclick="storeYesNo('yesno')">
                             Store Specific - Store Not Listed In Portal
                            </label>
                        </div>  

                </div> -->
                <div class="mb-3 showStore">
                    <label class="form-label" for="basic-icon-default-email">For Store</label>
                    <?php if ($loginUser->user_type == 2) : ?>
                    <select class="form-select inventStore" name="store_id" style="width: 100%" required>
                        <option value="0">Select Store</option>
                        <?php foreach ($clientStoreData as $storeValue) : ?>
                        <option value="<?= $storeValue->id ?>"><?= $storeValue->store_name ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php else : ?>
                    <select class="form-select" id="addTicketStore" name="store_id" style="width: 100%" required>
                        
                    </select>
                    <div id="storeError" style="color: red; display: none;">Please select a valid store.</div>
                    <?php
                    endif;
                    ?>
                </div>
                <div class="mb-3">
                    <!-- for watcher -->
                     <label for="watchers" class="form-label">Watchers</label>
                     <select id="watchers" name="watchers[]" class="js-example-basic-multiple form-select w-100" style="width: 100%" multiple>
                     </select>
                </div>
                <div class="mb-3">
                    <label for="defaultInput" class="form-label">Title</label>
                    <input id="defaultInput" class="form-control" type="text" name="title" placeholder="Title"
                        autocomplete="off" required>
                </div>
                
                <div class="mb-3">
                    <label for="exampleFormControlTextarea1" class="form-label">Description</label>
                    <textarea class="ckeditor form-control" id="" rows="3" name="description"></textarea>
                </div>
                <div class="mb-3">
                    <label for="formFileMultiple" class="form-label">Upload Supporting Attachments</label>
                    <input class="form-control" type="file" id="" name="file[]" multiple>
                </div>
                <div class="text-end">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" name="submitButton" class="btn btn-primary">Save Ticket</button>
                </div>
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" aria-hidden="true" id="openInternalTicket">

</div>
<!-- Add Image 'action' => 'Tickets/addMoreFile',-->

<div class="modal fade" id="imgBasicModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1">Upload File</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?= $this->Form->create(null, [
                    'id' => 'addFile'
                ])
                ?>
                <div class="mb-3">
                    <label for="formFileMultiple" class="form-label">Upload Supporting Attachment</label>
                    <input class="form-control" type="file" id="" name="files[]">
                    <input type="hidden" name="category" value="0"/>
                </div>
                <div class="text-end">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                    <!-- <button type="submit" class="btn btn-primary" onclick="commentAddFile()">File Upload</button> -->
                    <button type="submit" class="btn btn-primary" id="fileUploadButton">File Upload</button>
                </div>
                <input type="hidden" name="ticketID" id="ticketID">
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>

<!-- Add Image 'kanban/draggable/draggable.bundle.legacy.js'-->

<div class="modal fade" id="imgBasicAtcModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1">Upload File</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?= $this->Form->create(null, [
                    'method' => 'post',
                    'enctype' => 'multipart/form-data',
                    'action' => 'Tickets/addAtcFile'
                ])
                ?>
                <div class="mb-3">
                    <label for="formFileMultiple" class="form-label">Upload Supporting Attachments</label>
                    <input class="form-control" type="file" id="" name="files[]" multiple>
                    <input type="hidden" name="category" value="0"/>
                </div>
                <div class="text-end">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" class="btn btn-primary">File Upload</button>
                </div>
                <input type="hidden" name="ticketID" id="ticketAtcID">
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalScrollable" tabindex="-1" aria-hidden="true">
     
 </div>

<?= $this->Html->script(['kanban/anchorjs/anchor.min.js', 'kanban/fontawesome/all.min.js', 'kanban/lodash/lodash.min.js', 'assets_js/kanban.js']) ?>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
 function departmentIssue(selectElement) {
    var departmentId = selectElement.value;
    
    if (departmentId) {
        $.ajax({
            url: "<?= $this->Url->build(['controller' => 'Tickets', 'action' => 'getDepartmentStaff']) ?>",
            type: 'GET',
            data: { department_id: departmentId },
            dataType : 'json',
            success: function(response) {
                updateWatchersDropdown(response);
            },
            error: function() {
                console.error('An error occurred while fetching watchers.');
            }
        });
    }
}
 
function updateWatchersDropdown(data) {
    var watchersDropdown = $('#watchers');
    watchersDropdown.empty();  // Clear the existing options

    if (data.length > 0) {
        data.forEach(function(watcher) {
            watchersDropdown.append('<option value="' + watcher.Users.id + '">' + watcher.Users.first_name + ' ' + watcher.Users.last_name + '</option>');
        });
    } else {
        watchersDropdown.append('<option value="">No watchers available</option>');
    }
    watchersDropdown.trigger('change');
}

function kanban(id) {
    $("#ticketID").val(id);
    $("#ticketAtcID").val(id);
    $.ajax({
        url: "<?= $this->Url->build(['controller' => 'Tickets', 'action' => 'kanbanModal']) ?>",
        method: "GET",
        data: {
            id: id
        },
        success: function(res) {
            $('#kanban-modal-1').html(res);
            $('#kanban-modal-1').modal('show');
            // CKEDITOR.replace( 'commentMessage' );
            $('#kanban-modal-1').on('shown.bs.modal', function () {
                $('#commentMessageTicket').summernote({
                    height: 200,
                    dialogsInBody: true,
                    toolbar: [
                        ['style', ['style']],
                        ['font', ['bold', 'italic', 'underline', 'clear']],
                        ['fontname', ['fontname']],
                        ['fontsize', ['fontsize']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['height', ['height']],
                        ['insert', ['link', 'picture', 'video']],
                        ['view', ['fullscreen', 'codeview']]
                    ],
                });
            });
        }
    })
}

function fetchStore(clientData, id) {
    let clientId = clientData.value;
    console.log('id', clientId)
    $.ajax({
        url: "<?= $this->Url->build(['controller' => 'Order', 'action' => 'selectStore']) ?>",
        method: 'GET',
        data: {
            clientId: clientId
        },
        success: function(res) {
            let resData = JSON.parse(res);
            let output = ['<option value="">Select Store</option>'];
            let isFirstElement = true;

            resData.forEach(element => {
                const selected = isFirstElement ? 'selected' : '';
                output.push(`<option value="${element.id}" ${selected}>${element.store_name}</option>`);
                isFirstElement = false;
            });

            // Add the "No Store" option
            if (isFirstElement) {                
                output.push(`<option value="0" selected>No Store</option>`);
            }
            if (id == 'storeId')
                $('#storeId').html(output.join(''));
            else
                $('#addTicketStore').html(output.join(''));
        }
    })
}


$(document).ready(function() {
    $('#addFile').submit(function(e) {
        // Disable submit button to prevent multiple submissions
        $('#fileUploadButton').prop('disabled', true);
        
        e.preventDefault();   
        var formData = new FormData(this);

        $.ajax({
            url: "<?= $this->Url->build(['controller' => 'Tickets', 'action' => 'addMoreFile']) ?>",
            type: 'POST',
            data: formData,
            success: function (res) {

                $('#imgBasicModal').modal('hide');
                let commentData = JSON.parse(res);
                var design = '<a class="text-decoration-none" href="javascript:void(0);" onclick="getTicketDoc(`'+commentData.file+'`)">'+commentData.file+'</a>';
                // var inputFile = `<input type="hidden" name="atcInput[]" value="<a class="text-decoration-none" href="<?= ECOM360;?>/img/tickets_file/`+commentData.file+`" target="_blank">`+commentData.file+`</a>"/>`;
                $('.newAtc').append(design);
                // $('.newAtc').append(inputFile);
            },
            complete: function () {
                $('#fileUploadButton').prop('disabled', false);
            },
            cache: false,
            contentType: false,
            processData: false
        });
    });
});


function dragStart(event) {
  event.dataTransfer.setData("Text", event.target.id);
}

function dragging(event) {
//   document.getElementById("demo").innerHTML = "The p element is being dragged";
}

function allowDrop(event) {
  event.preventDefault();
}

function drop(event) {
    event.preventDefault();
    var cname = event.target;
    
    var arr = [];
    var nodeArr = [];
    for(var n in cname){
    a = cname.parentNode.className;
    cname = cname.parentNode;
    if(a.search('ticketDrag') >= 0)
    {
        arr.push(a);
        nodeArr.push(cname);
        break;
    }
    arr.push(a);
    nodeArr.push(cname);
    }
    // console.log(cname)
    var first = nodeArr.find(t => $(t)[0]['className'].search('ticketDrag') >= 0)
    // console.log(first);
  
    const data = event.dataTransfer.getData("Text");
    $(first).append(document.getElementById(data));
  
    var issue = $(first).attr('data-issue');
    var ticketId = data.split("_");

    $.ajax({
            url: "<?= $this->Url->build(['controller' => 'Tickets', 'action' => 'ticketStatus']) ?>",
            method: "GET",
            data: {
                ticketId: ticketId[1],
                status: issue,
                type: 'issue_type',
                category: 0
            },
            success: function(res) {
                location.reload();
            }
        });
}

</script>