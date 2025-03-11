<!-- Content -->

<div class="container-xxl">
    <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner py-4">
            <!-- Forgot Password -->
            <div class="card">
                <div class="card-body">
                    <!-- Logo -->
                    <div class="app-brand justify-content-center">
                        <a href="<?= DROPSHIPPING ?>/forgot-password" class="app-brand-link gap-2">
                            <?= $this->Html->image("dropshipping/Ecom360logo.png", ["alt" => "", "height" => "55px"]) ?>
                        </a>
                        <!-- <span class="app-brand-text demo menu-text fw-bolder ms-2">DropShipping Direct</span> -->
                    </div>
                    <!-- /Logo -->
                    <h4 class="mb-2">Forgot Password? 🔒</h4>
                    <p class="mb-4">Enter your email and we'll send you instructions to reset your password</p>
                    <?= $this->Flash->render('emailNotFound') ?>
                    <?= $this->Form->create(null, ['method' => 'post', 'action' => 'Users/forgotPassword']) ?>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="text" class="form-control" id="email" name="email" placeholder="Enter your email" autofocus />
                    </div>
                    <button type="submit" class="btn btn-primary d-grid w-100">Submit</button>
                    <?= $this->Form->end() ?>
                    <div class="text-center">
                        <a href="<?= DROPSHIPPING ?>/" class="d-flex align-items-center justify-content-center">
                            <i class="bx bx-chevron-left scaleX-n1-rtl bx-sm"></i>
                            Back to login
                        </a>
                    </div>
                </div>
            </div>
            <!-- /Forgot Password -->
        </div>
    </div>
</div>

<!-- / Content -->