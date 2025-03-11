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
                    <div class="text-center">
                        <h4 class="mb-2">Change Your Password</h4>
                        <?= $this->Flash->render('passChangeErr') ?>
                        <?= $this->Flash->render('passChange') ?>
                    </div>
                    <?= $this->Form->create(null, ['method' => 'post', 'action' => 'Users/updatePassword']) ?>
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
                    <button type="submit" class="btn btn-primary d-grid w-100">Submit</button>
                    <?= $this->Form->end() ?>
                </div>
            </div>
            <!-- /Forgot Password -->
        </div>
    </div>
</div>

<!-- / Content -->
<script>
function oldPasswordMatch() {
    let oldPass = $('#oldPassword').val();
    const token = $('input[name="_csrfToken"]').attr('value');
    let baseUrl = "<?= DROPSHIPPING ?>";
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
                $('#oldPassword').val('');
            } else {
                $('#passErr').text(``);
                $('#oldPassword').val(oldPass);
            }
        }
    });
}
 </script>