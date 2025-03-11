<?php

$login = $_SESSION['loginE'];
$msg = $_SESSION['loginTempError'];
?>
 <!-- Content -->
 <div class="container-xxl">
     <div class="authentication-wrapper authentication-basic container-p-y">
         <div class="authentication-inner">
             <!-- Register -->
             <div class="card">
                 <div class="card-body">
                     <!-- Logo -->
                     <div class="app-brand justify-content-center">
                         <a href="<?= DROPSHIPPING ?>/" class="app-brand-link gap-2">
                             <?= $this->Html->image("dropshipping/Ecom360logo.png", ["alt" => "", "height" => "55px"]) ?>
                         </a>
                         <!-- <span class="app-brand-text demo menu-text fw-bolder ms-2">DropShipping Direct</span> -->
                     </div>
                     <!-- /Logo -->
                     <h4 class="mb-2">Welcome to Ecom 360!</h4>
                     <p class="mb-4">Please enter your credentials to log-in and start using the application</p>
                     <span><?= $this->Flash->render() ?></span>
                     <span><?= $this->Flash->render('newPasswordSet') ?></span>
                     <span><?= $this->Flash->render('loginError') ?></span>
                     <?= $this->Form->create(null, ["id" => "formAuthentication", "class" => "mb-3", "method" => "POST"]) ?>
                     <div class="mb-3">
                         <label for="email" class="form-label">Email or Username</label>
                         <input type="text" class="form-control" id="email" name="email" placeholder="Enter your email or username" autofocus />
                     </div>
                     <div class="mb-3 form-password-toggle">
                         <div class="d-flex justify-content-between">
                             <label class="form-label" for="password">Password</label>
                             <a href="<?= DROPSHIPPING ?>/forgot-password">
                                 <small>Forgot Password?</small>
                             </a>
                         </div>
                         <div class="input-group input-group-merge">
                             <input type="password" id="password" class="form-control" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />
                             <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                         </div>
                     </div>
                     <div class="mb-3">
                         <!-- <div class="form-check">
                             <input class="form-check-input" type="checkbox" id="remember-me" />
                             <label class="form-check-label" for="remember-me"> Remember Me </label>
                         </div> -->
                     </div>
                     <div class="mb-3">
                         <button class="btn btn-primary d-grid w-100" type="submit">Log in</button>
                     </div>
                     <?= $this->Form->end() ?>

                     <!-- <p class="text-center">
                         <span>New on our platform?</span>
                         <span data-bs-toggle="modal" data-bs-target="#tutorialModal">
                             <span>Help me</span>
                        </span>
                     </p> -->
                 </div>
             </div>
             <!-- /Register -->
         </div>
     </div>
 </div>

 <!-- Modal -->
<?php if($login==1) { ?>
    <div class="modal fade show" id="basicModal" tabindex="-1" aria-modal="true" role="dialog" style="display: block; padding-left: 0px;">
    <?php  } else{ ?>
  <div class="modal fade" id="basicModal" tabindex="-1" aria-hidden="true">
    <?php } ?>
     <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="modalScrollableTitle">Login Error</h5>
                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="modalClose()"></button>
             </div>
             <div class="modal-body">
                 <?= $this->Form->create(null, ['id' => 'brandData']) ?>

                 <div class="modal-body">
                    <div class="row">
                    <?= $msg;?>
                    </div>
                     <div class="text-end mt-4">
                     <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" onclick="modalClose()">
                        Close
                    </button>
                     </div>
                 </div>
                 <?= $this->Form->end() ?>
             </div>
         </div>
     </div>
 </div>

 <!-- Start Engagement Widget Script -->
<!-- <script async
   crossorigin
   type="module"
   id="engagementWidget"
   src="https://cdn.chatwidgets.net/widget/livechat/bundle.js"
   data-env="portal-api"
   data-instance="ZIH-oVcfDzcjqdYe"
   data-container="#engagement-widget-container"></script> -->
<!-- End Engagement Widget Script -->
 <!-- / Content -->