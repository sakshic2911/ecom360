<div class="modal-dialog modal-xl mt-6" role="document">
    <div class="modal-content border-0">
        <div class="position-absolute top-0 end-0 mt-3 me-3 z-index-1"><button
                class="btn-close btn btn-sm btn-circle d-flex flex-center transition-base" data-bs-dismiss="modal"
                aria-label="Close"></button></div>
                <!-- onClick="window.location.reload(true)" -->
        <div class="modal-body p-0">
            <div class="bg-light rounded-top-lg py-3 ps-4 pe-6">
                <div class="row">
                    <div class="col-md-6">
                    <p class="fs--2 mb-0"><a class="link-600 fw-semi-bold"
                    href="#!">#<?= $ticketData[0]->ticket_identity ?>#</a></p>
                <h4 class="mb-1" id="kanban-modal-label-1">
                    <?= $ticketData[0]->title ?>
                </h4>
                <p class="fs--2 mb-0">Added by <a class="link-600 fw-semi-bold"
                        href="#!"><?= ucfirst($ticketData[0]->name) ?></a></p>
                    </div>
                    <div class="col-md-3">
                    <?php if(($loginUser->user_type==1 && $dptPermission==1) || $loginUser->user_type==0) : ?>
                        <h6 class="mt-3">Assign To</h6>
                               
                               <select id="support_user" class="js-example-client"
                                   aria-label=".form-select-lg example"
                                   onchange="assignStaff(this,<?= $ticketData[0]->id ?>)">
                                   <option value="0">----Select Staff-----</option>
                                <?php foreach($supportStaff as $s) { ?>
                                   <option value="<?= $s->id;?>" <?= $ticketData[0]->support_staff == $s->id ? 'selected' : '' ?>>
                                       <?= ucfirst($s->first_name).' '.ucfirst($s->last_name);?>
                                    </option>
                                   <?php } ?>
                               </select>
                               <?php endif;?>
                    </div>
                    <div class="col-md-3 mt-4">
                    <?php if(($loginUser->user_type==1 && $permission==2) || $loginUser->user_type==0) : ?>
                         <button type="button" class="btn btn-primary w-100" onclick="openInternalTicket(<?php if($ticketData[0]->store_id != ''){ echo $ticketData[0]->store_id;}else echo 0; ?>,<?= $ticketData[0]->client_id ?>,'ticket',<?= $ticketData[0]->issue_type ?>,<?= $ticketData[0]->support_staff ?>)">
                                    Create Internal Ticket
                        </button>
                     <?php endif;?>
                     <?php if($loginUser->user_type==2 && ($ticketData[0]->status == 0 || $ticketData[0]->status == 1)) : ?>
                         <!-- <button type="button" class="btn btn-primary w-100" onclick="openTicketByClient()">
                         No chat response received
                        </button> -->
                     <?php endif;?>
                    </div>
                </div>
            </div>
            <?php         
                $isDisabled = !((($loginUser->user_type == 1 && $permission == 2) || $loginUser->user_type == 0));
            ?>
            <div class="p-4">
                <div class="row">
                    <div class="col-lg-9">
                        <!-- Label -->
                        <div class="d-flex"><span class="fa-stack ms-n1 me-3"><i
                                    class="fas fa-circle fa-stack-2x text-200"></i><i
                                    class="fa-inverse fa-stack-1x text-primary fas fa-tag"
                                    data-fa-transform="shrink-2"></i></span>
                            <div class="flex-1">
                                <h5 class="mb-2 fs-0">Labels</h5>
                                <div class="d-flex">
                                    <div id="addLabel">
                                        <?php
                                        $New = true;
                                        $Goal = true;
                                        $Enhancement = true;
                                        $Bug = true;
                                        $Documentation = true;
                                        $Helper = true;
                                        if (count($labelData) > 0) :
                                            foreach ($labelData as $lableVal) :
                                                if ($lableVal->label_name == 'New') {
                                                    $New = false;
                                                    $className = "success";
                                                } else if ($lableVal->label_name == 'Goal') {
                                                    $Goal = false;
                                                    $className = "primary";
                                                } else if ($lableVal->label_name == 'Enhancement') {
                                                    $Enhancement = false;
                                                    $className = "info";
                                                } else if ($lableVal->label_name == 'Bug') {
                                                    $Bug = false;
                                                    $className = "danger";
                                                } else if ($lableVal->label_name == 'Documentation') {
                                                    $Documentation = false;
                                                    $className = "secondary";
                                                } else if ($lableVal->label_name == 'Helper') {
                                                    $Helper = false;
                                                    $className = "warning";
                                                }
                                        ?>
                                        <span id="label<?= $lableVal->id  ?>"
                                            class="badge me-1 py-2 badge-soft-<?= $className ?>"><?= $lableVal->label_name ?>
                                        </span>
                                       <?php if($loginUser->user_type === 0) { ?>
                                        <a onclick="removeLable(<?= $lableVal->id ?>,'<?= $lableVal->label_name ?>')"
                                            id="deleteLabel<?= $lableVal->id ?>">
                                            <span class="fas fa-trash-alt me-1"
                                                style="cursor:pointer; height:1em;width: 0.5em;"
                                                title="Remove <?= $lableVal->label_name ?> Label">
                                            </span>
                                        </a>

                                        <?php
                                        } endforeach;
                                        endif; ?>
                                    </div>
                                    <?php if($loginUser->user_type === 0) { ?>
                                    <div class="dropdown dropend"><button
                                            class="btn btn-sm btn-secondary px-2 fsp-75 bg-400 border-400 dropdown-toggle dropdown-caret-none"
                                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span
                                                class="fas fa-plus"></span></button>
                                        <div class="dropdown-menu">
                                            <h6 class="dropdown-header py-0 px-3 mb-0">Select Label</h6>
                                            <div class="dropdown-divider"></div>
                                            <div class="px-3" id="addRemoveLabels">
                                                <?php
                                                if ($New) :
                                                ?>
                                                <button class="badge-soft-success dropdown-item rounded-1 mb-2"
                                                    type="button" onclick="selectLabel(<?= $ticketData[0]->id ?>,'New')"
                                                    id="New">
                                                    New
                                                </button>
                                                <?php endif ?>
                                                <?php
                                                if ($Goal) :
                                                ?>
                                                <button class="badge-soft-primary dropdown-item rounded-1 mb-2"
                                                    type="button"
                                                    onclick="selectLabel(<?= $ticketData[0]->id ?>,'Goal')" id="Goal">
                                                    Goal
                                                </button>
                                                <?php
                                                endif;
                                                ?>
                                                <?php
                                                if ($Enhancement) :
                                                ?>
                                                <button class="badge-soft-info dropdown-item rounded-1 mb-2"
                                                    type="button"
                                                    onclick="selectLabel(<?= $ticketData[0]->id ?>,'Enhancement')"
                                                    id="Enhancement">
                                                    Enhancement
                                                </button>
                                                <?php
                                                endif;
                                                ?>
                                                <?php
                                                if ($Bug) :
                                                ?>
                                                <button class="badge-soft-danger dropdown-item rounded-1 mb-2"
                                                    type="button" onclick="selectLabel(<?= $ticketData[0]->id ?>,'Bug')"
                                                    id="Bug">
                                                    Bug
                                                </button>
                                                <?php
                                                endif;
                                                ?>
                                                <?php
                                                if ($Documentation) :
                                                ?>
                                                <button class="badge-soft-secondary dropdown-item rounded-1 mb-2"
                                                    type="button"
                                                    onclick="selectLabel(<?= $ticketData[0]->id ?>,'Documentation')"
                                                    id="Documentation">
                                                    Documentation
                                                </button>
                                                <?php
                                                endif;
                                                ?>
                                                <?php
                                                if ($Helper) :
                                                ?>
                                                <button class="badge-soft-warning dropdown-item rounded-1 mb-2"
                                                    type="button"
                                                    onclick="selectLabel(<?= $ticketData[0]->id ?>,'Helper')"
                                                    id="Helper">
                                                    Helper
                                                </button>
                                                <?php
                                                endif;
                                                ?>
                                            </div>
                                            <div class="dropdown-divider"></div>
                                            <!-- <div class="px-3"><button
                                                    class="btn btn-sm d-block w-100 btn-outline-secondary border-400">Create
                                                    Label
                                                </button>
                                            </div> -->
                                        </div>
                                    </div>
                                    <?php } ?>

                                </div>
                                <hr class="my-4" />
                            </div>
                        </div>
                        <!-- Status and department -->
                        <div class="d-flex"><span class="fa-stack ms-n1 me-3"><i
                                    class="fas fa-circle fa-stack-2x text-200"></i><i
                                    class="fa-inverse fa-stack-1x text-primary fas fa-check"
                                    data-fa-transform="shrink-2"></i></span>
                            <div class="flex-1">
                                <div class="row">
                                <div class="col-md-6">
                                <h5 class="mb-2 fs-0">Status</h5>
                                <?php $status = ["Pending","In-progress","Completed","Closed","Closed"];
                                if($loginUser->user_type===2) { ?>
                                <p> <?= $status[$ticketData[0]->status];?></p>
                                <?php } else{ ?>
                                <select id="statusId" class="form-select form-select-sm"
                                    aria-label=".form-select-sm example" <?= $isDisabled ? 'disabled' : ''; ?>
                                    onchange="changeStatus(<?= $ticketData[0]->id ?>)">
                                    <option value="0">Please Select</option>
                                    <option value="1" <?= $ticketData[0]->status == 1 ? 'selected' : '' ?>>In-Progress
                                    </option>
                                    <option value="3" <?= $ticketData[0]->status == 3 ? 'selected' : '' ?>>Closed
                                    </option>
                                </select>
                                <?php } ?>
                                </div>
                                <div class="col-md-6">
                                <?php 
                                if(($loginUser->user_type==1 && $lgPermission==1) || $loginUser->user_type==0) { ?> 
                                <h5 class="mb-2 fs-0">Department</h5>
                               
                                <select id="issue_type" class="form-select form-select-sm"
                                    aria-label=".form-select-sm example"
                                    onchange="changeIssue(<?= $ticketData[0]->id ?>)">
                                    <option value="0">Select Issue</option>
                                    <option value="1" <?= $ticketData[0]->issue_type == 1 ? 'selected' : '' ?>>General Support</option>
                                    <option value="2" <?= $ticketData[0]->issue_type == 2 ? 'selected' : '' ?>>Billing</option>
                                    <option value="3" <?= $ticketData[0]->issue_type == 3 ? 'selected' : '' ?>>Refer a friend</option>
                                    <option value="4" <?= $ticketData[0]->issue_type == 4 ? 'selected' : '' ?>>Onboarding a new store</option>
                                    <option value="5" <?= $ticketData[0]->issue_type == 5 ? 'selected' : '' ?>>Portal Support</option>
                                    <option value="6" <?= $ticketData[0]->issue_type == 6 ? 'selected' : '' ?>>Inventory management</option>
                                    <option value="7" <?= $ticketData[0]->issue_type == 7 ? 'selected' : '' ?>>TeamViewer/device is offline</option>
                                    <option value="8" <?= $ticketData[0]->issue_type == 8 ? 'selected' : '' ?>>E-comm 360/E-comm Tax Service</option>
                                    <!-- <option value="9" <?= $ticketData[0]->issue_type == 9 ? 'selected' : '' ?>>Multilogin</option> -->                                    
                                    <option value="10" <?= $ticketData[0]->issue_type == 10 ? 'selected' : '' ?>>Walmart Stores</option>
                                    <?php if($loginUser->user_type == 1 ||$loginUser->user_type == 0): ?>
                                    <option value="11" <?= $ticketData[0]->issue_type == 11 ? 'selected' : '' ?>>Case management</option>
                                    <?php endif; ?>
                                    <option value="12" <?= $ticketData[0]->issue_type == 12 ? 'selected' : '' ?>>Store Transfer</option>
                                    <option value="13" <?= $ticketData[0]->issue_type == 13 ? 'selected' : '' ?>>Amazon Questions</option>
                                </select>
                                <?php } ?>
                                </div>
                                </div>
                                <hr class="my-4" />
                            </div>
                        </div>
                        <!-- Description -->
                        <div class="d-flex"><span class="fa-stack ms-n1 me-3">
                                <svg class="svg-inline--fa fa-circle fa-w-16 fa-stack-2x text-200" aria-hidden="true"
                                    focusable="false" data-prefix="fas" data-icon="circle" role="img"
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="">
                                    <path fill="currentColor"
                                        d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8z">
                                    </path>
                                </svg>
                                <!-- <i class="fas fa-circle fa-stack-2x text-200"></i> Font Awesome fontawesome.com -->
                                <svg class="svg-inline--fa fa-align-left fa-w-14 fa-inverse fa-stack-1x text-primary"
                                    data-fa-transform="shrink-2" aria-hidden="true" focusable="false" data-prefix="fas"
                                    data-icon="align-left" role="img" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 448 512" data-fa-i2svg="" style="transform-origin: 0.4375em 0.5em;">
                                    <g transform="translate(224 256)">
                                        <g transform="translate(0, 0)  scale(0.875, 0.875)  rotate(0 0 0)">
                                            <path fill="currentColor"
                                                d="M12.83 352h262.34A12.82 12.82 0 0 0 288 339.17v-38.34A12.82 12.82 0 0 0 275.17 288H12.83A12.82 12.82 0 0 0 0 300.83v38.34A12.82 12.82 0 0 0 12.83 352zm0-256h262.34A12.82 12.82 0 0 0 288 83.17V44.83A12.82 12.82 0 0 0 275.17 32H12.83A12.82 12.82 0 0 0 0 44.83v38.34A12.82 12.82 0 0 0 12.83 96zM432 160H16a16 16 0 0 0-16 16v32a16 16 0 0 0 16 16h416a16 16 0 0 0 16-16v-32a16 16 0 0 0-16-16zm0 256H16a16 16 0 0 0-16 16v32a16 16 0 0 0 16 16h416a16 16 0 0 0 16-16v-32a16 16 0 0 0-16-16z"
                                                transform="translate(-224 -256)"></path>
                                        </g>
                                    </g>
                                </svg>
                                <!-- <i class="fa-inverse fa-stack-1x text-primary fas fa-align-left" data-fa-transform="shrink-2"></i> Font Awesome fontawesome.com --></span>
                            <div class="flex-1">
                                <h5 class="mb-2 fs-0">Description</h5>
                                <p class="text-word-break fs--1">
                                    <?= $ticketData[0]->description ?>

                                </p>
                                <hr class="my-4">
                            </div>
                        </div>
                        <!-- Attachment -->
                        <div class="d-flex">
                            <span class="fa-stack ms-n1 me-3"><svg
                                    class="svg-inline--fa fa-circle fa-w-16 fa-stack-2x text-200" aria-hidden="true"
                                    focusable="false" data-prefix="fas" data-icon="circle" role="img"
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="">
                                    <path fill="currentColor"
                                        d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8z">
                                    </path>
                                </svg>
                                <svg class="svg-inline--fa fa-paperclip fa-w-14 fa-inverse fa-stack-1x text-primary"
                                    data-fa-transform="shrink-2" aria-hidden="true" focusable="false" data-prefix="fas"
                                    data-icon="paperclip" role="img" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 448 512" data-fa-i2svg="" style="transform-origin: 0.4375em 0.5em;">
                                    <g transform="translate(224 256)">
                                        <g transform="translate(0, 0)  scale(0.875, 0.875)  rotate(0 0 0)">
                                            <path fill="currentColor"
                                                d="M43.246 466.142c-58.43-60.289-57.341-157.511 1.386-217.581L254.392 34c44.316-45.332 116.351-45.336 160.671 0 43.89 44.894 43.943 117.329 0 162.276L232.214 383.128c-29.855 30.537-78.633 30.111-107.982-.998-28.275-29.97-27.368-77.473 1.452-106.953l143.743-146.835c6.182-6.314 16.312-6.422 22.626-.241l22.861 22.379c6.315 6.182 6.422 16.312.241 22.626L171.427 319.927c-4.932 5.045-5.236 13.428-.648 18.292 4.372 4.634 11.245 4.711 15.688.165l182.849-186.851c19.613-20.062 19.613-52.725-.011-72.798-19.189-19.627-49.957-19.637-69.154 0L90.39 293.295c-34.763 35.56-35.299 93.12-1.191 128.313 34.01 35.093 88.985 35.137 123.058.286l172.06-175.999c6.177-6.319 16.307-6.433 22.626-.256l22.877 22.364c6.319 6.177 6.434 16.307.256 22.626l-172.06 175.998c-59.576 60.938-155.943 60.216-214.77-.485z"
                                                transform="translate(-224 -256)"></path>
                                        </g>
                                    </g>
                                </svg>
                            </span>
                            <div class="flex-1">
                                <div class="d-flex justify-content-between mb-2">
                                    <h5 class="mb-0 fs-0">Attachments</h5>
                                    <div class="dropdown">
                                        <button
                                            class="btn btn-falcon-default btn-sm dropdown-toggle dropdown-caret-none fs--2"
                                            data-bs-toggle="modal" data-bs-target="#imgBasicAtcModal" type="button"
                                            aria-haspopup="true" aria-expanded="false"> <svg
                                                class="svg-inline--fa fa-plus fa-w-14" aria-hidden="true"
                                                focusable="false" data-prefix="fas" data-icon="plus" role="img"
                                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"
                                                data-fa-i2svg="">
                                                <path fill="currentColor"
                                                    d="M416 208H272V64c0-17.67-14.33-32-32-32h-32c-17.67 0-32 14.33-32 32v144H32c-17.67 0-32 14.33-32 32v32c0 17.67 14.33 32 32 32h144v144c0 17.67 14.33 32 32 32h32c17.67 0 32-14.33 32-32V304h144c17.67 0 32-14.33 32-32v-32c0-17.67-14.33-32-32-32z">
                                                </path>
                                            </svg></button>
                                    </div>
                                </div>
                                <?php
                                foreach ($ticketData as $document) :
                                    if ($document->docType == 2) :
                                ?>
                                <div class="d-flex align-items-center mb-3">

                                    <div class="bg-attachment me-1">

                                        <?= $this->Html->image("tickets_file/" . $document->document, ['height' => '80']) ?>
                                    </div>

                                    <div class=" flex-1 fs--2">
                                        <h6 class="mb-1"> <a class="text-decoration-none" href="javascript:void(0)" onclick="getTicketDoc(`<?= $document->document ?>`)"
                                               ><?= $document->document ?></a>
                                        </h6>
                                        <a class="link-600 fw-semi-bold"
                                            href="removeDoc/<?= $document->document ?>">Remove</a>

                                    </div>
                                </div>
                                <?php
                                    endif;
                                endforeach;
                                ?>

                                <?php
                                foreach ($ticketData as $document) :
                                    if ($document->docType == 1) :
                                ?>
                                <div class="d-flex align-items-center mb-3">

                                    <div class="bg-attachment me-1">
                                        <?= $this->Html->image("PDF_icon.jpg", ['height' => '50', 'width' => '80']) ?>
                                    </div>
                                    <div class="flex-1 fs--2">
                                        <h6 class="mb-1">
                                            <a class="text-decoration-none"
                                                href="javascript:void(0)" onclick="getTicketDoc(`<?=$document->document ?>`)">
                                                <?= $document->document ?>
                                            </a>
                                        </h6>
                                        <a class="link-600 fw-semi-bold"
                                            href="Tickets/removeDoc/<?= $document->document ?>">Remove</a>

                                    </div>
                                </div>
                                <?php
                                    endif;
                                endforeach;
                                ?>
                                <hr class="my-4">
                            </div>
                        </div>                     
                        <!-- Comments -->
                        <?php if($loginMaster && $loginMaster->role_id != 13 || $loginUser->user_type == 2) { ?>
                        <div class="d-flex" style="overflow-wrap: anywhere;"><span class="fa-stack ms-n1 me-3"><i
                                    class="fas fa-circle fa-stack-2x text-200"></i><i
                                    class="fa-inverse fa-stack-1x text-primary far fa-comment"
                                    data-fa-transform="shrink-2"></i></span>
                            <div class="flex-1">
                                <h5 class="mb-3 fs-0">Comments</h5>
                                <?php if($ticketData[0]->status < 3) { ?>
                                <?php if(($loginUser->user_type==1 && $permission==2) || in_array($loginUser->user_type,[0,2])) : ?>      
                                <div class="d-flex">
                                    <div class="avatar avatar-l me-2">
                                        <?php
                                        if ($loginUser->image != null)
                                            echo $this->Html->image("ECOM360/avatars/$loginUser->image", ["class" => "rounded-circle"]);
                                        else
                                            echo $this->Html->image("ECOM360/avatars/s49847avatar.png", ["class" => "rounded-circle"]);

                                        ?>
                                    </div>
                                    <div class="flex-1 fs--1">
                                        <div class="position-relative border rounded-1 mb-3">
                                            <?php if($loginUser->user_type != 2 || ($loginUser->user_type == 2 && ($ticketData[0]->status == 1 ||  $ticketData[0]->issue_type == 13))): ?>
                                            <?= $this->Form->create() ?>
                                            <textarea class="ckeditor form-control border-0 rounded-bottom-0 resize-none"
                                                rows="3" contentEditable="true" id="commentMessageTicket">
                                                </textarea>
                                                <div class="newAtcTicket">
                                                
                                                </div>
                                               
                                            <div class="d-flex flex-between-center bg-light rounded-bottom p-2 mt-1">
                                            <div class="dropdown">
                                            <button
                                                class="btn btn-falcon-default btn-sm dropdown-toggle dropdown-caret-none fs--2"
                                                type="button"
                                                onclick="showImgMoal()"> 
                                                <svg
                                                class="svg-inline--fa fa-plus fa-w-14" aria-hidden="true"
                                                focusable="false" data-prefix="fas" data-icon="plus" role="img"
                                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"
                                                data-fa-i2svg="">
                                                <path fill="currentColor"
                                                    d="M416 208H272V64c0-17.67-14.33-32-32-32h-32c-17.67 0-32 14.33-32 32v144H32c-17.67 0-32 14.33-32 32v32c0 17.67 14.33 32 32 32h144v144c0 17.67 14.33 32 32 32h32c17.67 0 32-14.33 32-32V304h144c17.67 0 32-14.33 32-32v-32c0-17.67-14.33-32-32-32z">
                                                </path>
                                            </svg></button>
                                    </div>

                                                <button class="btn btn-sm btn-primary" type="button"
                                                    onclick="commentAddShowTicket(<?= $ticketData[0]->id ?>,<?= $loginUser->id ?>)">Save</button>
                                                  
                                            </div>
                                            <?= $this->Form->end() ?>
                                            <?php endif;?>
                                        </div>
                                    </div>
                                </div>
                                <?php endif ?>
                                <?php } ?>
                                <div id="showCommentTicket">
                                    <?php
                                    if (count($commentData) > 0) {
                                        foreach ($commentData as $commVal) {
                                    ?>
                                    <div class="d-flex mb-3">
                                        <div class="avatar avatar-l">
                                            <?php
                                                    if ($commVal->image != null)
                                                        echo $this->Html->image("ECOM360/avatars/$commVal->image", ["class" => "rounded-circle"]);
                                                    else
                                                        echo $this->Html->image("ECOM360/avatars/s49847avatar.png", ["class" => "rounded-circle"]);

                                                    ?>
                                        </div>
                                        <div class="flex-1 ms-2 fs--1">
                                            <div class="mb-1 bg-200 rounded-3 p-2">
                                                <span class="fw-semi-bold"
                                                    style="color:#696cff;"><?= ucfirst($commVal->first_name) ?>
                                                    <?= ucfirst($commVal->last_name) ?>: </span>
                                                <?= str_replace('<pre>','<p>',$commVal->comment_notes) ?>
                                        </div>
                                            &emsp;<a href="#"><?= $commVal->cmt_time; ?> EST</a>
                                        </div>
                                    </div>
                                    <?php
                                        }
                                    }
                                    ?>
                                </div>
                                <hr class="my-4" />
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                    <div class="col-lg-3">
                        <?php if(($loginUser->user_type==1 && $permission==2) || $loginUser->user_type==0) : ?>
                            <div class="d-flex align-items-center mb-3">
                                <button class="btn btn-sm btn-primary" type="button"
                                    onclick="EditTicketWatcher(<?= $ticketData[0]->id ?>)">
                                    <span class="fas fa-pen me-2"></span>Edit Watcher
                                </button>
                            </div> 
                        <?php endif;?>  
                    <h6 class="mt-5 mt-lg-0">Store Name</h6>
                             <ul class="nav flex-lg-column fs--1">
                                 <li class="nav-item me-2 me-lg-0"><a class="nav-link nav-link-card-details" href="#!">
                                    <?= $ticketData[0]->store_name.' - '.$ticketData[0]->store_status ?></a></li>
                             </ul>

                             <h6 class="mt-5 mt-lg-0">Manager</h6>
                             <ul class="nav flex-lg-column fs--1">
                                 <li class="nav-item me-2 me-lg-0"><a class="nav-link nav-link-card-details" href="#!">
                                    <?= $ticketData[0]->account_manager ?></a></li>
                             </ul>
                             <h6 class="mt-5 mt-lg-0">Ticket Initiated</h6>
                             <ul class="nav flex-lg-column fs--1">
                                 <li class="nav-item me-2 me-lg-0"><a class="nav-link nav-link-card-details" href="#!">
                                    <?= $ticketData[0]->created ?></a></li>
                             </ul>

                        <!-- <h6 class="mt-3">Actions</h6>
                        <ul class="nav flex-lg-column fs--1">
                            <li class="nav-item me-2 me-lg-0"><a class="nav-link nav-link-card-details"
                            href="javascript:void(0);" onclick="deleteTicket(<?= $ticketData[0]->id ?>, 'Tickets/deleteTicket')">
                            <span class="fas fa-trash-alt me-2"></span>Remove</a>
                            </li>
                        </ul> -->
                        <?php if($loginUser->user_type != 2): ?>
                        <ul class="nav flex-lg-column fs--1 mt-3">
                                 <li class="nav-item me-2 me-lg-0">
                                    <a class="nav-link nav-link-card-details" href="#">
                                       <input class="form-check-input" type="checkbox" name="amazon_approved" value="1" id="defaultCheck4" <?= (!empty($ticketData[0]) && $ticketData[0]->mark_important == 1)?'checked':''; ?> <?= $isDisabled ? 'disabled' : ''; ?> onclick="markupdate(<?= $ticketData[0]->id;?>,'mark_important')"> <span class="mb-2 fs-0">Mark as important</span>
                                    </a>
                                 </li>
                             </ul>

                             <ul class="nav flex-lg-column fs--1 mt-3">
                                 <li class="nav-item me-2 me-lg-0">
                                    <a class="nav-link nav-link-card-details" href="#"> 
                                       <input class="form-check-input" type="checkbox" name="amazon_approved" value="1" id="defaultCheck4" <?= (!empty($ticketData[0]) && $ticketData[0]->mark_flag == 1)?'checked':''; ?> <?= $isDisabled ? 'disabled' : ''; ?> onclick="markupdate(<?= $ticketData[0]->id;?>,'mark_flag')"> <span class="mb-2 fs-0">Mark a Flag</span>
                                    </a>
                                 </li>
                             </ul>
                              <!-- watcher list -->
                            <div id="watcherDiv">   
                                <?php if(!empty($watchers) > 0): ?>    
                                    <h6 class="mt-5 mt-lg-0">Watchers</h6>
                                    <ul class="nav flex-lg-column fs--1" id="ticket_watcher_list">
                                        <?php foreach($watchers as $watcher): ?>
                                        <li class="nav-item me-2 me-lg-0"><a class="nav-link nav-link-card-details" href="#!">
                                        <?= $watcher->watcher_name ?></a></li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?> 
                            </div>
                            <?php endif; ?> 

                            <?php if ($loginUser->user_type != 2) { ?>
                                <?php if (!empty($storeid) && !empty($ticketData[0])) { 
                                    $source = 'Active'; 

                                    switch ($ticketData[0]->onboarding_status) {
                                        case 'Onboarding':
                                            $source = 'Launch';
                                            break;
                                        case 'Active':
                                        case 'Paused':
                                        case 'Closing in Progress':
                                        case 'Closed':
                                        case 'Suspended':
                                        case 'Deactivated':
                                            $source = 'Active';
                                            break;
                                        case 'Active-Launch':
                                            $source = 'Launch';
                                            break;
                                        default:
                                            $source = 'Unknown'; // Fallback in case no matching case
                                            break;
                                    }
                                ?>

                                    <!-- <button class="btn btn-falcon-default btn-sm dropdown-toggle dropdown-caret-none fs--2" type="button" onclick="showImgMoal()">  -->
                                    <button type="button" class="btn btn-primary w-100" 
                                        onclick="kanbanLaunchDetail(<?= $ticketData[0]->client_id ?>, 'Approved', <?= $ticketData[0]->store_id; ?>, '<?= $source; ?>')">
                                        View Client Chat
                                    </button>
                                <?php } ?>   
                            <?php } ?>
    

                             <?php if (!empty($storeid)) { ?>
                                <ul class="nav flex-lg-column fs--1 mt-3">
                                    <li class="nav-item me-2 me-lg-0">
                                        <a class="nav-link fw-semi-bold" href="<?= ECOM360 ?>/order-details/<?= $storeid[0]->id; ?>" target="_blank">
                                            <span class="mb-2 fs-0 fw-semi-bold text-decoration-underline">View Client Orders</span>
                                        </a>
                                    </li>
                                </ul>
                            <?php } ?>                    
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>
<!-- <script type="text/javascript" src="//cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script> -->

<!-- Watcher modal -->
<div class="modal fade" tabindex="-1" aria-hidden="true" id="ticketWatcherModal">
</div>

<!-- Open Ticket By Client modal -->
<div class="modal fade" tabindex="-1" aria-labelledby="openTicketByClientModalLabel" aria-hidden="true" id="openTicketByClientModal">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1">Submit a Ticket</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?= $this->Form->create(null, [
                    'method' => 'post',
                    'enctype' => 'multipart/form-data',
                    'action' => 'addTicket'
                ])
                ?>
                <div class="mb-3">
                    <label>Title</label>
                    <input type="text" name="title" class="form-control" value="No chat response received" readonly/>
                    <input type="hidden" name="issue_type" class="form-control" value="1" readonly/>
                    <input type="hidden" name="store_id" class="form-control" value="<?php if($ticketData[0]->store_id != ''){ echo $ticketData[0]->store_id;}else echo 0; ?>" readonly/>
                </div>
                <div class="mb-3">
                    <label>Comments</label>
                    <textarea name="description" class="form-control resize-none" rows="3" contentEditable="false" readonly>My chat has not been responded to within 72 hours. Can we please evaluate why I have not received a response? Thank you.</textarea>
                </div>
                
                <button type="submit" class="btn btn-primary w-100">
                    Submit
                </button>
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>

<script>

function kanbanLaunchDetail(id,category,store,source) {
    $("#userID").val(id);
    // console.log(source);
    $.ajax({
        url: "<?= $this->Url->build(['controller' => 'Client', 'action' => 'launchDetail']) ?>",
        method: "GET",
        data: {
            id: id,
            category: category,
            store: store,
            source: source,
            call : 'tickets'
        },

        success: function(res) {
            $('#kanban-modal-launchDetail').html(res);
            $('#kanban-modal-launchDetail').modal('show');
            $('#kanban-modal-launchDetail').on('shown.bs.modal', function () {
                $('#commentMessage').summernote({
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
                    callbacks: {
                        onPaste: function(e) {
                            
                            let clipboardData = ((e.originalEvent || e).clipboardData || window.clipboardData);
                            let pastedData = clipboardData.getData('text/html') || clipboardData.getData('text/plain');
                            let cleanData = $('<div>').html(pastedData);

                            cleanData.find('[style]').each(function() {
                                let style = $(this).attr('style');
                                
                                // Remove background-color and color styles
                                style = style.replace(/background-color[^;]+;?/g, '');
                                style = style.replace(/background[^;]+;?/g, '');
                                style = style.replace(/color[^;]+;?/g, '');
                                
                                $(this).attr('style', style.trim());
                                
                                // If no styles are left, remove the style attribute
                                if ($(this).attr('style') === '') {
                                    $(this).removeAttr('style');
                                }
                            });

                            
                            // Replace the pasted content with cleaned data
                            setTimeout(function() {
                                document.execCommand('insertHTML', false, cleanData.html());
                            }, 10);
                            
                            e.preventDefault(); // Prevent the original paste action
                        }
                    }
                });
            });
        }
    })
}

    function openTicketByClient(){
        $('#openTicketByClientModal').modal('show');
    }

$('.js-example-client').select2({
dropdownParent: $('#kanban-modal-1 .modal-content'),
placeholder: 'Select Client',
width: 'resolve',
});

function selectLabel(id, labels) {
    console.log(id, labels);
    let colClass = '';
    if (labels == 'New') {
        colClass = 'success';
    } else if (labels == 'Goal') {
        colClass = 'primary';
    } else if (labels == 'Enhancement') {
        colClass = 'info';
    } else if (labels == 'Bug') {
        colClass = 'danger';
    } else if (labels == 'Documentation') {
        colClass = 'secondary';
    } else if (labels == 'Helper') {
        colClass = 'warning';
    }
    $.ajax({
        url: "<?= $this->Url->build(['controller' => 'Tickets', 'action' => 'addLabelData']) ?>",
        method: "GET",
        data: {
            ticket_id: id,
            label: labels
        },
        success: function(res) {
            let labelData = JSON.parse(res);
            // console.log(labelData);

            $('#addLabel').append(
                `<span class="badge me-1 py-2 badge-soft-${colClass}" id="label${labelData.id}" >${labelData.label_name}</span><a id="deleteLabel${labelData.id}" onclick="removeLable(${labelData.id},${labels})"><span class="fas fa-trash-alt me-1" style="cursor:pointer; height:1em;width: 0.5em;" title="Remove ${labelData.label_name} Label"></span></a>`
            ) ? $(`#${labels}`).css("display", "none") : $(`#${labels}`).css("display", "");
        }
    })
}

function removeLable(id, labels) {
    // console.log(id, labels);
    $.ajax({
        url: "<?= $this->Url->build(['controller' => 'Tickets', 'action' => 'deleteLabel']) ?>",
        method: "GET",
        data: {
            labelId: id
        },
        success: function(res) {
            if (res == 1) {
                document.getElementById(`label${id}`).style.display = 'none';
                document.getElementById(`deleteLabel${id}`).style.display = 'none';
                // $(`#label${id}`).css("display", "none");
                // $(`#deleteLabel${id}`).css("display", "none");
            }
        }
    })
}

function showImgMoal()
{
    $('#imgBasicModal').modal('show');
}

function commentAddShowTicket(ticketId, userId) {
   let commentMessage = $('#commentMessageTicket').val();
   
//    var editor = CKEDITOR.instances['commentMessage'];
//     var commentMessage = editor.getData();
    <?php if($ticketData[0]->issue_type == 7) { ?>
    let result = commentMessage.replace("After", "<br><br>After");
    let final = result.replace("Thank you" ,"<br><br>Thank you");
    let atc1 = $(".newAtcTicket").html();
    final += atc1;
    commentMessage = final;
    <?php } ?>
    let atc = $(".newAtcTicket").html();
    commentMessage += atc;
    const token = $('input[name="_csrfToken"]').attr('value');
    $.ajax({
        url: "<?= $this->Url->build(['controller' => 'Tickets', 'action' => 'addShowComment']) ?>",
        method: 'POST',
        headers: {
            'X-CSRF-Token': token,
        },
        data: {
            ticketId: ticketId,
            userId: userId,
            commentMessage: commentMessage,
            category: 0
        },
        success: function(res) {
            // console.log(res);
            let commentData = JSON.parse(res);

            $('#showCommentTicket').append(`<div class="d-flex mb-3">
                <div class="avatar avatar-l">
                <?php
                if ($loginUser->image != null)
                    echo $this->Html->image("ECOM360/avatars/$loginUser->image", ["class" => "rounded-circle"]);
                else
                    echo $this->Html->image("ECOM360/avatars/s49847avatar.png", ["class" => "rounded-circle"]);

                ?>
                                    </div>
                                    <div class="flex-1 ms-2 fs--1">
                                        <div class="mb-1 bg-200 rounded-3 p-2">
                                            <span class="fw-semi-bold" style="color:#696cff;"><?= ucfirst($loginUser->first_name) ?> <?= ucfirst($loginUser->last_name) ?>: </span>
                                            ${commentData.comment_notes}
                                            </div>
                                            </div>
                                            </div>`);
            // $('#commentMessage').val('');
            // editor.setData('');
            $('#commentMessageTicket').summernote('reset');
            $('.newAtcTicket').html('');

        }
    })
}

function changeStatus(id) {
    let status = $('#statusId').val();
    if (status != 0) {
        $.ajax({
            url: "<?= $this->Url->build(['controller' => 'Tickets', 'action' => 'ticketStatus']) ?>",
            method: "GET",
            data: {
                ticketId: id,
                status: status,
                type:'status',
                category: 0
            },
            success: function(res) {
                // if (res == 1)
                //     location.reload();
            }
        });
    }
}

function changeTemplate(){
    var val = $('#temId').val();
    if(val == 1)
    {
        $('#commentMessage').val('Your separate device that is hosting TeamViewer is showing as offline on our end of TeamViewer. Please ensure that the device is open and has constant connection to the internet. Sleep mode on most devices will temporarily disable internet connection. If the device is open and plugged in, you may need to do one of two things: change the settings so that the device never goes into sleep mode, or look up how to change the sleep mode settings on your device so that sleep mode does not disable the internet connection.\n\nAfter this is done, you will probably have to provide the new password that TeamViewer generated. You will find this underneath the ID on the face of the TeamViewer application.\n\nThank you');
    }
    if(val == 0)
    {
        $('#commentMessage').val('');
    }
}

function changeIssue(id) {
    let issue = $('#issue_type').val();
    if (issue != 0) {
        $.ajax({
            url: "<?= $this->Url->build(['controller' => 'Tickets', 'action' => 'ticketStatus']) ?>",
            method: "GET",
            data: {
                ticketId: id,
                status: issue,
                type: 'issue_type',
                category: 0
            },
            success: function(res) {
                // if (res == 1)
                //     location.reload();
            }
        });
    }
}

function assignStaff(staffData,id) {
    let issue = staffData.value;
   
    if (issue != 0) {
        $.ajax({
            url: "<?= $this->Url->build(['controller' => 'Tickets', 'action' => 'ticketStatus']) ?>",
            method: "GET",
            data: {
                ticketId: id,
                status: issue,
                type: 'support_staff',
                category: 0
            },
            success: function(res) {
                // if (res == 1)
                //     location.reload();
            }
        });
    }
}


function markupdate(val,col)
{
    $.ajax({
        url: `<?= $this->Url->build(['controller' => 'Tickets', 'action' => 'markupdate']) ?>`,
        method: "POST",
        data: {
            id: val,
            col:col
        },
        beforeSend: function(xhr){
            xhr.setRequestHeader(
                'X-CSRF-Token',csrfToken                
            );
        },
        success: function (res) {
           location.reload()
        },
    });
}


function modalNotClosed() {
    $('#kanban-modal-1').modal({
        backdrop: 'static',
        keyboard: false
    }, 'show');
}
modalNotClosed();

function EditTicketWatcher(id){
    let issue = $('#issue_type').val();
    $.ajax({
        url: `${baseUrl}/Tickets/editTicketWatcher`,
        method: "GET",
        data: {
            ticket_id: id,
            issue_type: issue,
        },
        success: function (res) {            
            $("#ticketWatcherModal").html(res);
            $('#ticketWatcherModal').modal('show');
        },
    })
}

function showChatDiv(id)
{
    var element = document.getElementById("chatDiv");
    element.classList.remove("d-none");
    
    $('.feecheckbox').each(function () {
        $(this).css('display', 'block');        
    });

    $('#cht_'+id).prop("checked",true);
    var bulk = id+',';
    $('#bulkChatId').val(bulk);
}

function openTicket(client_id,store_id,redirect)
    {
        var bulkId = $('#bulkChatId').val();
        $.ajax({
            url: "<?= $this->Url->build(['controller' => 'Client', 'action' => 'chatTicketDetail']) ?>",
            method: "GET",
            data: {
                client_id: client_id,
                store_id: store_id,
                call: redirect,
                chatId: bulkId,
            },
            success: function(res) {
            
                $('#modalScrollable').html(res);
                $("#modalScrollable").modal("show");
            }
        })
        
    }

    function hideCheckbox()
{
    var element = document.getElementById("chatDiv");
    element.classList.add("d-none");

    $('.feecheckbox').each(function () {
        $(this).css('display', 'none');   
        $(this).prop("checked", false);
     
    });

    $('#bulkChatId').val('');
}
function selectAll()
    {
        var bulk='';
        $('.feecheckbox').each(function () {
            $(this).prop("checked", true);      
            bulk += $(this).val()+',';
        });

        $('#bulkChatId').val(bulk);
        $('#selceText').text('Deselect All');
        $("#selceText").attr("onclick","deselectAll()");
    }

    function deselectAll()
    {
        var bulk='';
        $('.feecheckbox').each(function () {
            $(this).prop("checked", false);      
        });

        $('#bulkChatId').val(bulk);
        $('#selceText').text('Select All');
        $("#selceText").attr("onclick","selectAll()");
    }
</script>