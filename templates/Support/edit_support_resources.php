<div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
         <div class="modal-content editPro">
             <div class="modal-header">
                 <h5 class="modal-title" id="modalScrollableTitle">Edit Resource</h5>
                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
             </div>
             <div class="modal-body">
                <?= $this->Form->create(null, ['action' => 'Support/editSupportResources','id'=>'editResource', 'enctype' => 'multipart/form-data']) ?>
                 <div class="row">
                 <div class="col-md-12">
                        <div class="mb-3">
                            <label class="form-label" for="basic-icon-default-brand">For </label>
                            <select class="form-control" name="for_type" style="width: 100%" required onchange="showstore(this.value,'edit')">
                                <option value="">Please Select</option>
                                <option value="Client" <?= ($resorceData->for_type=='Client')?'selected':'';?> >Client</option>
                                <option value="Staff" <?= ($resorceData->for_type=='Staff')?'selected':'';?>>Staff</option>
                                <option value="Both" <?= ($resorceData->for_type=='Both')?'selected':'';?>>Both</option>                           
                            </select>
                        </div>
                    </div>
                    <?php  $disp = (($resorceData->for_type == 'Client' || $resorceData->for_type == 'Both') ? '' : 'none'); ?>
                    <div class="col-md-12" id="edit_store_type_div" style="display:<?= $disp ?>">
                        <div class="mb-3">
                                <label class="form-label" for="basic-icon-default-lastname">Store Type</label>
                                <div class="input-group input-group-merge">
                                    <select class="form-select js-example-basic-multiple-store1" name="store_type[]" multiple="multiple" style="width: 100%" id="edit_store_type">
                                        <?php foreach ($storeName as $val) : ?>
                                        <option value="<?= $val->id ?>" <?php
                                            foreach ($storeNameData as $value) {
                                                if ($value->store_type_id == $val->id) echo 'selected';
                                                else echo '';
                                            } ?>><?= $val->store_name ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                        </div>
                    </div>
                 <div class="col-md-12">
                         <div class="mb-3">
                             <label class="form-label" for="basic-icon-default-brand">Category </label>
                             <select class="form-select js-example-basic-multiple-store1"  multiple="multiple" name="category[]" style="width: 100%" required id="category">
                                 <option value="">Please Select</option>
                                 <?php
                                    foreach ($category as $val) :
                                    ?>
                                 <option value="<?= $val->id ?>" <?php
                                            foreach ($categoryData as $value) {
                                                if ($value->category_id == $val->id) echo 'selected';
                                                else echo '';
                                            } ?>><?= ($val->name) ?>
                                  </option>
                                 <?php
                                    endforeach ?>
                             </select>
                         </div>
                    </div>

                     <div class="col-md-12">
                         <div class="mb-3">
                             <label class="form-label" for="basic-icon-default-product">Title / Question</label>
                             <div class="input-group input-group-merge">
                                 <input type="text"  name="question" class="form-control" placeholder="Enter Title / Question" autocomplete="off" required value="<?= $resorceData->question ?>" required>
                             </div>
                         </div>
                     </div>
                     <?php  $textInput = (($resorceData->description != '') ? '' : 'none');
                      $embedInput = (($resorceData->embed_code != '') ? '' : 'none');
                      $urlInput = (($resorceData->url != '') ? '' : 'none'); ?>
                     <div class="col-sm-12">
                            <div class="mb-3">
                                <label class="form-label">Response</label>
                                <div class="col-md d-flex ">
                                    <div class="form-check form-check-inline mb-1">
                                        <input class="form-check-input" type="checkbox" name="type_text" value="text" onclick="Showinputs('edit'), edithandleCheckboxClick('edittext')" id="edittext" <?php if( $resorceData->description != ""){ echo "checked"; } ?> >
                                        <label class="form-check-label" for="edittext"><small>Text</small></label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="type_embed" value="vimeo" onclick="Showinputs('edit'), edithandleCheckboxClick('editembed')" id="editembed" <?php if($resorceData->embed_code != '') { echo "checked"; } ?>>
                                        <label class="form-check-label" for="editembed"><small>Embed Code</small></label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="type_url" value="url" onclick="Showinputs('edit'), edithandleCheckboxClick('editurl')" id="editurl" <?php if($resorceData->url != '') { echo "checked"; } ?>>
                                        <label class="form-check-label" for="editurl"><small>URL</small></label>
                                    </div>
                                </div>
                            </div>
                        </div>

                     <div id="edittextInput" style="display:<?= $textInput ?>">
                        <div class="col-md-12" >
                            <div class="mb-3">
                                <label class="form-label" for="basic-icon-default-product">Description</label>
                                <div class="input-group input-group-merge">
                                    <textarea  name="description" class="form-control description ckeditor" placeholder="Enter Description" autocomplete="off" id="Message"><?php echo $resorceData->description;?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                     <div class="col-md-12" id="editembedInput" style="display:<?= $embedInput ?>">
                         <div class="mb-3">
                             <label class="form-label" for="basic-icon-default-product">Embed Code</label>
                             <div class="input-group input-group-merge">
                                 <textarea  name="embed_code" class="form-control embed_code" placeholder="Enter Embed Code" autocomplete="off"><?php echo $resorceData->embed_code;?></textarea>
                             </div>
                         </div>
                     </div>

                     <div class="col-md-12" id="editurlInput" style="display:<?= $urlInput ?>">
                         <div class="mb-3">
                             <label class="form-label" for="basic-icon-default-product">URL</label>
                             <div class="input-group input-group-merge">
                                 <input type="text"  name="url" class="form-control url" placeholder="Enter URL" autocomplete="off" value="<?= $resorceData->url; ?>">
                             </div>
                         </div>
                     </div>
                     <div class="col-md-12" >
                         <div class="mb-3">
                             <label class="form-label" for="basic-icon-default-product">Tags <span class="small fw-semibold">(Separate tags with commas and use line breaks)</span></label>
                             <div class="input-group input-group-merge">
                                 <textarea  name="tag" class="form-control newTag" placeholder="Enter Tags" autocomplete="off" id="newTag<?= $resorceData->id ?>"></textarea>
                                    </div>
                                 <ul id="tagList<?= $resorceData->id ?>" class="tagList">
                                </ul>  
                                <input type="hidden" id="tagValues<?= $resorceData->id ?>" class="tagValues" name="tags" value="<?php echo $resorceData->tags; ?>"/>
                         </div>
                     </div>
                     <input type="hidden" value="<?= $resorceData->id ?>" name="editId">
                 </div>
                    
                     <div class="col-sm-12">
                        <div class="text-end">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            Close
                        </button>
                        <button type="submit" class="btn btn-primary edtsbmt">Edit Resource</button>
                        </div>
                     </div>

                 </div>
                 <?= $this->Form->end() ?>
             </div>

         </div>
     </div>
     <?php 
     $tagsString = $resorceData->tags;
     if($tagsString !=""){
        $tagsArray = explode(',', $tagsString);
     }else{
        $tagsArray = "";
     }
     ?>

    <script type="text/javascript">
        if (typeof TagListManager<?= $resorceData->id ?> === 'undefined') {
            const TagListManager<?= $resorceData->id ?> = {
                tagList: <?php echo json_encode($tagsArray); ?>,
                newTagInput: document.getElementById('newTag<?= $resorceData->id ?>'),
                tagListContainer: document.getElementById('tagList<?= $resorceData->id ?>'),
                tagValuesInput: document.getElementById('tagValues<?= $resorceData->id ?>'),

                init() {
                    this.render();

                    this.newTagInput.addEventListener('keyup', this.handleKeyPress.bind(this));

                    this.tagListContainer.addEventListener("click", this.handleTagRemove.bind(this));
                },

                render() {
                    this.tagListContainer.innerHTML = '';
                    const tagValues = this.tagList.map(tag => {
                        const listItem = document.createElement('li');
                        const tagWithoutComma = tag.replace(/,/g, '');
                        listItem.innerHTML = `${tagWithoutComma}<span class="rmTag">&times;</span>`;
                        this.tagListContainer.appendChild(listItem);
                        return tagWithoutComma;
                    });

                    this.tagValuesInput.value = tagValues.join(', ');
                },

                handleKeyPress(e) {
                    if (e.keyCode === 13 || e.keyCode === 188) {
                        const newTag = this.newTagInput.value;
                        if (newTag.trim() !== '') {
                            this.tagList.push(newTag);
                            this.newTagInput.value = '';
                            this.render();
                        }
                    }
                },

                handleTagRemove(e) {
                    if (e.target.className === 'rmTag') {
                        const listItem = e.target.parentElement;
                        const index = Array.from(listItem.parentNode.children).indexOf(listItem);
                        this.tagList.splice(index, 1);
                        this.render();
                    }
                }
            };

            TagListManager<?= $resorceData->id ?>.init(); 
        }
        
    </script>

    <script>

        function edithandleCheckboxClick(clickedCheckboxId) {
            var allCheckboxIds = ['edittext', 'editembed', 'editurl'];
            const setRequiredAttribute = (elementId, required) => {
                const element = document.getElementById(elementId);
                if (element) {
                    if (required) {
                        element.setAttribute('required', 'true');
                    } else {
                        element.removeAttribute('required');
                    }
                }
            };
            var edittext =  document.getElementById('edittext').checked;
            var editembed =  document.getElementById('editembed').checked;
            var editurl =  document.getElementById('editurl').checked;
                if(edittext || editembed || editurl ){
                setRequiredAttribute('edittext', false);
                setRequiredAttribute('editembed', false);
                setRequiredAttribute('editurl', false);
            }else{
                setRequiredAttribute('edittext', true);
                setRequiredAttribute('editembed', true);
                setRequiredAttribute('editurl', true);
            }
        }
        </script>



    