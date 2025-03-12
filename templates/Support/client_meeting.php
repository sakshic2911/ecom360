<?php
$title = ($meetingData[0]['is_checked'] == 1) ? $meetingData[0]['content'] : "";
$description = ($meetingData[1]['is_checked'] == 1) ? $meetingData[1]['content'] : "";
$video_value = ($meetingData[2]['is_checked'] == 1) ? $meetingData[2]['content'] : "";
$video_type = ($meetingData[2]['is_checked'] == 1) ? $meetingData[2]['type'] : 0;
$form_type = ($meetingData[3]['is_checked'] == 1) ? $meetingData[3]['type'] : 0;
?>
<div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
         <div class="modal-content">
             <div class="modal-header">
                 <h1 class="modal-title" id="modalScrollableTitle"><?php echo $title ?></h1>
                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
             </div>
             <div class="modal-body">
                 <?= $this->Form->create(null, ['method' => 'post', 'action' => '../Support/clientMeeting']) ?>
                <input type="hidden"  name="title" class="form-control" value="<?php echo $title; ?>">
                <input type="hidden"  name="description" class="form-control" value=" <?php echo $description; ?>">
                <input type="hidden"  name="video_value" class="form-control" value='<?php echo $video_value; ?>'>
                <input type="hidden"  name="video_type" class="form-control" value="<?php echo $video_type; ?>">
                <input type="hidden"  name="form_type" class="form-control" value="<?php echo $form_type; ?>">
                 <div class="row">
                    <div class="col-md-12">
                         <div class="mb-3">
                             <label class="form-label" for="basic-icon-default-product" style="text-align:justify"><?php echo $description; ?></label>
                         </div>
                    </div>
                    <div class="col-md-12">
                        <?php if($meetingData[2]['is_checked'] == 1 && $meetingData[2]['type'] == 1){
                            echo $meetingData[2]['content']; }else if($meetingData[2]['is_checked'] == 1 && $meetingData[2]['type'] == 2){ ?>
                            <iframe src="img/ECOM360/support/<?php echo $meetingData[2]['content'] ?>" width="100%" height="100%" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
                           <?php  } ?>
                    </div>
                    <div class="col-md-12">
                        <div class="mb-3">
                        <?php if($meetingData[3]['is_checked'] == 1 && $meetingData[3]['type'] == 1){
                                echo $meetingData[3]['content']; 
                                }else if($meetingData[3]['is_checked'] == 1 && $meetingData[3]['type'] == 2){ 
                                      if($meetingData[4]['is_checked'] == 1){ ?>
                                    <label class="form-label" for="basic-icon-default-product">Name</label>
                                    <div class="input-group input-group-merge">
                                        <input type="text"  name="name" class="form-control" placeholder="Name" autocomplete="off" required value="<?= $loginUser->first_name.' '.$loginUser->last_name;?>">
                                    </div>
                               <?php } if($meetingData[5]['is_checked'] == 1){ ?>
                                    <label class="form-label" for="basic-icon-default-product">Email</label>
                                    <div class="input-group input-group-merge">
                                        <input type="email"  name="email" class="form-control" placeholder="Email" autocomplete="off" required value="<?= $loginUser->email;?>">
                                    </div>
                               <?php }  if($meetingData[6]['is_checked'] == 1){ ?>
                                    <label class="form-label" for="basic-icon-default-product">Phone No.</label>
                                    <div class="input-group input-group-merge">
                                        <input type="text"  name="contact" class="form-control" placeholder="Contact" autocomplete="off" required value="<?= $loginUser->contact_no;?>">
                                    </div>
                               <?php }  if($meetingData[7]['is_checked'] == 1){ ?>
                                    <label class="form-label" for="basic-icon-default-product">Message/Comment</label>
                                    <div class="input-group input-group-merge">
                                        <input type="text"  name="message" class="form-control" placeholder="Message" autocomplete="off" required >
                                    </div>
                               <?php } ?>
                                <?php } ?>
                        </div>
                    </div>
                 </div>                    
                <div class="col-sm-12">
                    <div class="text-end">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            Close
                        </button>
                        <?php if($meetingData[3]['is_checked'] == 1 && $meetingData[3]['type'] == 2){ ?>
                           <button type="submit" class="btn btn-primary">Save</button>
                        <?php } ?>
                    </div>
                </div>
                <?= $this->Form->end() ?>
            </div>
             </div>
         </div>
     </div>