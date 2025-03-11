<div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
    <div class="modal-content editModal">
        <div class="modal-header">
            <h5 class="modal-title" id="modalScrollableTitle">Edit Client</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <?= $this->Form->create(null, ['method' => 'post', 'action' => 'Client/editMasterClient','id'=>'clientForm']) ?>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label" for="basic-icon-default-firstname">First Name</label>
                        <div class="input-group input-group-merge">
                            <span id="basic-icon-default-firstname2" class="input-group-text"><i class="bx bx-user"></i></span>
                            <input type="text" class="form-control" id="editFirstName" name="first_name" placeholder="First Name" aria-label="Rajiv Doe" value="<?= $editMasterClientsData[0]->first_name ?>" aria-describedby="basic-icon-default-fullname2" autocomplete="off" required>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label" for="basic-icon-default-lastname">Last Name</label>
                        <div class="input-group input-group-merge">
                            <span id="basic-icon-default-lastname2" class="input-group-text"><i class="bx bx-user"></i></span>
                            <input type="text" class="form-control" id="editLastName" name="last_name" placeholder="Last Name" aria-label="Rajiv Doe" value="<?= $editMasterClientsData[0]->last_name ?>" aria-describedby="basic-icon-default-fullname2" autocomplete="off">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label" for="basic-icon-default-email">Email</label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="bx bx-envelope"></i></span>
                            <input type="email" id="editEmail" name="email" class="form-control" placeholder="Email" aria-label="Rajiv.doe@example.com" value="<?= $editMasterClientsData[0]->email ?>" aria-describedby="basic-icon-default-email2" onblur="editEmailCheck()" autocomplete="off" required>
                        </div>
                        <span class="mt-1" style="color: red;" id="editEmailError">
                        </span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label" for="basic-icon-default-phone">Phone No</label>
                        <div class="input-group input-group-merge">
                            <span id="basic-icon-default-phone2" class="input-group-text"><i class="bx bx-phone"></i></span>
                            <input type="text" id="editContactNo" name="contact_no" class="form-control phone-mask phoneFormat" placeholder="Phone Number" aria-label="" value="<?= $editMasterClientsData[0]->contact_no ?>" aria-describedby="basic-icon-default-phone2" autocomplete="off" onkeyup="formatPhone(this)">
                        </div>
                        <span class="mt-1" style="color: red;" id="phoneError">
                        </span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="exampleFormControlTextarea1" class="form-label">Address</label>
                        <textarea class="form-control" id="editAddress" name="address" rows="3" placeholder="Address..."><?= $editMasterClientsData[0]->address ?></textarea>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label" for="basic-icon-default-company">Organization Name</label>
                        <div class="input-group input-group-merge">
                            <span id="basic-icon-default-company2" class="input-group-text"><i class="bx bx-buildings"></i></span>
                            <input type="text" id="editOrganisation" name="organisation" class="form-control" placeholder="Organization Name" aria-label="Actiknow Pvt. Ltd.." aria-describedby="basic-icon-default-company2" value="<?= $editMasterClientsData[0]->organisation_name ?>">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label" for="basic-icon-default-company">Referring Affiliate's Name</label>
                        <select id="editAffiliate" class="editSingleExample" name="referrer_affiliate" style="width: 100%">
                            <option value="0">Please Select</option>
                            <?php
                            foreach ($masterClients as $val) :
                                if ($val->affiliate_client == 1) :
                            ?>
                                    <option value="<?= $val->id ?>" <?= $editMasterClientsData[0]->referrer_affiliate == $val->id ? 'selected' : '' ?>>
                                        <?= ucfirst($val->first_name) ?> <?= ucfirst($val->last_name) ?>
                                    </option>
                            <?php endif;
                            endforeach ?>
                        </select>
                    </div>
                </div>
                
                 </div>
                 <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label" for="basic-icon-default-company">Is an affiliate?</label>
                        <div class="col-md mt-2">
                            <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" class="btn-check" name="affiliate" id="editAffiliateYes" value='1' <?= $editMasterClientsData[0]->affiliate_client == 1 ? 'checked' : '' ?>>
                                <label class="btn btn-outline-primary" for="btnradio1" onclick="editAffiliateYesNo('yes')">Yes</label>
                                <input type="radio" class="btn-check" name="affiliate" id="editAffiliateNo" value='0' <?= $editMasterClientsData[0]->affiliate_client == 0 ? 'checked' : '' ?>>
                                <label class="btn btn-outline-primary" for="btnradio2" onclick="editAffiliateYesNo('no')">No</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label" for="basic-icon-default-company">Is there an override partner?</label>
                        <div class="col-md mt-2">
                            <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" class="btn-check" name="override" id="editOverrideYes" value='1' <?= $editMasterClientsData[0]->override_partner == 1 ? 'checked' : '' ?>>
                                <label class="btn btn-outline-primary" for="btnradio3" onclick="editOverrideYesNo('yes')">Yes</label>
                                <input type="radio" class="btn-check" name="override" id="editOverrideNo" value='0' <?= $editMasterClientsData[0]->override_partner == 0 ? 'checked' : '' ?>>
                                <label class="btn btn-outline-primary" for="btnradio4" onclick="editOverrideYesNo('no')">No</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label" for="basic-icon-default-company">Store Owner?</label>
                        <div class="col-md mt-2">
                        <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                                    <input type="radio" class="btn-check" name="store_own" id="editstoreYes" value=1 <?= $editMasterClientsData[0]->has_store == 1 ? 'checked' : '' ?>>
                                    <label class="btn btn-outline-primary" for="btnradio5"
                                    onclick="checkStore('yes')">Yes</label>
                                    <input type="radio" class="btn-check" name="store_own" id="editstoreNo" value=0
                                    <?= $editMasterClientsData[0]->has_store == 0 ? 'checked' : '' ?>>
                                    <label class="btn btn-outline-primary" for="btnradio6"
                                    onclick="checkStore('no')">No</label>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="editAffiliateYesNo">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="defaultSelect" class="form-label">Select Plan</label>
                            <select id="editPlan" class="form-select" name="plan">
                                <option value="0">Please Select Plan</option>
                                <?php foreach ($plan as $planVal) : ?>
                                    <option value="<?= $planVal->id ?>" <?= $editMasterClientsData[0]->client_plan == $planVal->id ? 'selected' : '' ?>>
                                        <?= $planVal->plan_name ?>
                                    </option>
                                <?php endforeach ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="html5-tel-input" class="col-form-label">Affiliate ID</label>
                            <div class="input-group input-group-merge">
                                
                                <input class="form-control" type="text" name="ssn" id="editSSN" value="<?= $editMasterClientsData[0]->ssn ?>" placeholder="Affiliate ID">
                            </div>
                        </div>
                    </div>
                </div>
                <!-- <div class="row">

                    
                </div> -->
            </div>
            <div class="mb-3" id="editOverrideYesNo">
                <div class="row">
                    <div class="col-md-6">
                        <label for="defaultSelect" class="form-label">Override Partner's Name</label>
                        <select id="" class="editMultiple" name="override_partner[]" multiple="multiple" style="width: 100%">
                        <?php foreach($overrideClients as $val) { ?>
                            <option value="<?= $val->id;?>" <?= (in_array($val->id,$editOverridePartnerData))?'selected':'';?>><?= $val->first_name .' '.$val->last_name;?></option>
                        <?php } ?>
                           
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="basic-icon-default-phone">Override Percentage</label>
                        <div class="input-group input-group-merge">
                            <span id="basic-icon-default-company2" class="input-group-text">%</span>
                            <input class="form-control" type="number" placeholder="Override Percentage" name="override_percentage" id="" value="<?= $editMasterClientsData[0]->override_percentage ?>" autocomplete="off">
                        </div>
                    </div>
                </div>
            </div>
            <input type="hidden" id="editId" name="editId" value="<?= $editMasterClientsData[0]->id ?>">
            <div class="text-end">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    Close
                </button>
                <button type="submit" class="btn btn-primary edtsbmt">Edit Client</button>
            </div>
            <?= $this->Form->end() ?>
        </div>

    </div>
</div>

<?= $this->Html->script('client') ?>