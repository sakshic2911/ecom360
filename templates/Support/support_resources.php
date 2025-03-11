<style>
    .text-nowrap {
  white-space: normal!important;
}
</style>
<!-- Content -->
 <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
     id="layout-navbar">
     <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
         <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
             <i class="bx bx-menu bx-sm"></i>
         </a>
     </div>
     <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
         <div class="row d-flex align-items-center">
             <h4 class="fw-bold m-0 p-0">Resources</h4>
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
    <div class="row">
         <div class="col-md-12">
             <ul class="nav nav-pills flex-column flex-md-row mb-3">
                 <li class="nav-item">
                     <a class="nav-link "
                     href="<?= DROPSHIPPING ?>/support-library"><i class="bx bx-detail me-1"></i> Categories</a>
                 </li>
                 
                 <li class="nav-item ">
                     <a class="nav-link active" href="javascript:void(0);" ><i class="bx bx-detail me-1"></i>
                     Resources</a>
                 </li>
                
             </ul>
         </div>
     </div>
     <!-- Data Table -->
     <div class="card">
         <div class="card-header ign-items-center">
         <?= $this->Form->create(null, ['method' => 'post']) ?> 
            <div class="row ">
                <div class="col-md-3">
                  <h5 class="mb-0">Resources</h5>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label" for="basic-icon-default-brand">Category </label>
                        <select class="form-select js-example-basic-multiple-store"  name="category_id" style="width: 100%" required id="category" onchange="this.form.submit()">
                            <option value="">Please Select</option>
                            <?php foreach ($category as $val) : ?>
                            <option value="<?= $val->id ?>"   <?php if ($category_id == $val->id) echo 'selected'; else echo ''; ?>><?= ($val->name) ?>
                            </option>
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-5">
                    <?php if(($loginUser->user_type==1 && $permission==2) || $loginUser->user_type==0) { ?>
                        <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal"
                                data-bs-target="#modalScrollable">
                        <span class="tf-icons bx bx-plus"></span>&nbsp; Add Resource
                        </button>
                    <?php } ?>
                </div>
            </div>
         <?= $this->Form->end();?>
         </div>
         <?= $this->Flash->render('error') ?>
         <?= $this->Flash->render('success') ?>
         <div class="table text-nowrap">
             <div class="container">
                <div class="table-responsive">
                    <table id="ScrollableWithAction" class="display table table-sm" style="width:100%">
                        <thead>
                            <tr>
                                <?php if($category_id >0 ){ ?>
                                  <th>Ranking</th>     
                                <?php } ?>  
                                 <th>For</th>                       
                                <th class="ss-width">Store Type</th>                       
                                <th class="ss-width">Category</th>
                                <th class="ss-width">Title / Question</th>                       
                                <th class="ss-width">Description</th>
                                <th>Embed Code</th>
                                <th class="ss-width">URL</th>
                                <th>Tags</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            <?php
                                foreach ($resourceData as $val) :
                                ?>
                            <tr>
                                <?php
                                    if ($category_id > 0) {
                                        if (($loginUser->user_type == 1 && $permission == 2) || $loginUser->user_type == 0) {
                                            echo '<td><input type="text" name="rank" id="ranking' . $val->cat_id . '" value="' . $val->ranking . '" class="form-control" onblur="updateRanking(' . $val->cat_id . ',\'resourcescategoryTbl\')"></td>';
                                        } else {
                                            echo '<td>' . $val->ranking . '</td>';
                                        }
                                    }
                                ?>    
                                <td><?= $val->for_type ?></td>
                                <td><?= $val->store_names ?></td>
                                <td><?= $val->category_names ?></td>
                                 <!-- For question -->
                                 <td>
                                    <?php if(strlen($val->question) > 20) { ?>                                 
                                            <span id="question<?= $val->id ?>"><?= substr($val->question, 0, 20); ?></span>
                                            <span id="question<?= $val->id ?>-dots">....</span>
                                            <div id="question<?= $val->id ?>-more" style="display: none;">
                                                 <?= substr($val->question, 20); ?>
                                            </div>
                                            <a href="javascript:void(0);" id="question<?= $val->id ?>-toggle" onclick="toggleReadMore('question<?= $val->id ?>')">Read more</a>
                                       <?php } else { ?>
                                        <span><?= !empty($val->question) ? $val->question : '----'; ?></span>
                                    <?php } ?>
                                </td>
                                <td>
                                <?php if (strlen($val->description) > 20) { ?>
                                        <span id="description<?= $val->id ?>"><?= substr($val->description, 0, 20); ?></span>
                                        <span id="description<?= $val->id ?>-dots">....</span>
                                        <div id="description<?= $val->id ?>-more" style="display: none;">
                                            <?= substr($val->description, 20); ?>
                                        </div>
                                        <a href="javascript:void(0);" id="description<?= $val->id ?>-toggle" onclick="toggleReadMore('description<?= $val->id ?>')">Read more</a>
                                <?php } else { ?>
                                    <?= !empty($val->description) ? $val->description : '----'; ?>
                                <?php } ?>
                                </td>                                <td>
                                <?php if(!empty($val->embed_code)):?>
                                    <a href="javascript:void(0);" title="Open Tags List"onclick="openEmbedCode(<?= $val->id ?>,'Support/openEmbedCode')">
                                    <i class='bx bx-low-vision me-1 clr-theme'></i>
                                    </a>
                                <?php endif; ?> 
                                </td>
                                <td>
                                    <?php if (strlen($val->url) > 20): ?>
                                            <span id="url<?= $val->id ?>"><?= substr($val->url, 0, 20); ?></span>
                                            <span id="url<?= $val->id ?>-dots">....</span>
                                            <div id="url<?= $val->id ?>-more" style="display: none;">
                                              <?= substr($val->url, 20); ?>
                                            </div>
                                            <a href="javascript:void(0);" id="url<?= $val->id ?>-toggle" onclick="toggleReadMore('url<?= $val->id ?>')">Read more</a>
                                    <?php else: ?>
                                        <span><?= !empty($val->url) ? $val->url : '----'; ?></span>
                                    <?php endif; ?> 
                                </td>                                <td>
                                    <a href="javascript:void(0);" title="Open Tags List" onclick="showTags(<?= $val->id ?>,'Support/showTag','List of Tags','Tags')">
                                    <i class='bx bx-low-vision me-1 clr-theme'></i>
                                    </a>
                                </td>
                                <td>
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault"
                                            <?= $val->status == 1 ? 'checked' : 'unchecked' ?>
                                            <?= ($loginUser->user_type==1 && $permission==1)?'onclick="return false"':'onclick="activeInactiveResource('.$val->id.','.$val->status.',\'Support/activeInactiveResource\')"';?> />

                                    </div>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <?php if($loginUser->user_type==1 && $permission==1) { $d = 'View';} else{ $d= 'Edit';} ?>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="javascript:void(0);"
                                                onclick="editResourceData(<?= $val->id ?>,'<?= $d;?>')"><i
                                                    class="bx bx-edit-alt me-1"></i>
                                                    <?= $d;?></a>
                                        <?php if(($loginUser->user_type==1 && $permission==2) || $loginUser->user_type==0) { ?>
                                            <a class="dropdown-item" href="javascript:void(0);"
                                            onclick="deleteSupportData(<?= $val->id ?>,'Support/deleteSupport','supportresourceTbl')"><i
                                                    class="bx bx-trash me-1"></i>
                                                Delete</a>
                                                <?php } ?>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php
                                endforeach;
                                ?>
                        </tbody>
                    </table>
                </div>
             </div>
         </div>
     </div>
     <!--/ Data Table -->
 </div>
 <!-- / Content -->

<!-- Add Resource -->
<div class="modal fade" id="modalScrollable" tabindex="-1" aria-hidden="true">
     <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="modalScrollableTitle">Add Resource</h5>
                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
             </div>
             <div class="modal-body">
                 <?= $this->Form->create(null, ['method' => 'post', 'action' => 'Support/addResource', 'enctype' => 'multipart/form-data' ]) ?>
                
                 <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label class="form-label" for="basic-icon-default-brand">For </label>
                            <select class="form-control" name="for" style="width: 100%" required onchange="showstore(this.value,'add')">
                                <option value="">Please Select</option>
                                <option value="Client">Client</option>
                                <option value="Staff">Staff</option>
                                <option value="Both">Both</option>                           
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12" id="add_store_type_div" style="display:none;">
                        <div class="mb-3">
                                <label class="form-label" for="basic-icon-default-lastname">Store Type</label>
                                <div class="input-group input-group-merge">
                                    <select class="form-select js-example-basic-multiple-store" name="store_type[]" multiple="multiple" style="width: 100%" id="add_store_type">
                                        <?php foreach ($storeName as $val) : ?>
                                        <option value="<?= $val->id ?>"><?= $val->store_name ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                         <div class="mb-3">
                             <label class="form-label" for="basic-icon-default-brand">Category </label>
                             <select class="form-select js-example-basic-multiple-store"  multiple="multiple" name="category[]" style="width: 100%" required id="category">
                                 <option value="">Please Select</option>
                                 <?php foreach ($category as $val) : ?>
                                    <option value="<?= $val->id ?>"><?= ($val->name) ?>
                                    </option>
                                 <?php endforeach ?>
                             </select>
                         </div>
                    </div>

                     <div class="col-md-12">
                         <div class="mb-3">
                             <label class="form-label" for="basic-icon-default-product">Title / Question</label>
                             <div class="input-group input-group-merge">
                                 <input type="text"  name="question" class="form-control" placeholder="Enter Title / Question" autocomplete="off" required>
                             </div>
                         </div>
                     </div>
                     <div class="col-sm-12">
                            <div class="mb-3">
                                <label class="form-label">Response</label>
                                <div class="col-md d-flex ">
                                    <div class="form-check form-check-inline mb-1">
                                        <input class="form-check-input" type="checkbox" name="type_text" value="text"  onclick="Showinputs('add'), handleCheckboxClick('addtext')" id="addtext" required >
                                        <label class="form-check-label" for="addtext"><small>Text</small></label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="type_embed" value="embed" onclick="Showinputs('add'), handleCheckboxClick('addembed')" id="addembed" required >
                                        <label class="form-check-label" for="addembed"><small> Embed Code</small></label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="type_url" value="url" onclick="Showinputs('add'), handleCheckboxClick('addurl')" id="addurl" required >
                                        <label class="form-check-label" for="addurl"><small>URL</small></label>
                                    </div>
                                </div>
                            </div>
                        </div>

                     <div id="addtextInput" style="display:none;">
                        <div class="col-md-12" >
                            <div class="mb-3">
                                <label class="form-label" for="basic-icon-default-product">Description</label>
                                <div class="input-group input-group-merge">
                                    <textarea  name="description" class="form-control description" id="summernoteEditor" placeholder="Enter Description" autocomplete="off"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                     <div class="col-md-12" id="addembedInput" style="display:none;">
                         <div class="mb-3">
                             <label class="form-label" for="basic-icon-default-product">Embed Code</label>
                             <div class="input-group input-group-merge">
                                 <textarea  name="embed_code" class="form-control embed_code" placeholder="Enter Embed Code" autocomplete="off"></textarea>
                             </div>
                         </div>
                     </div>   
                     
                     <div class="col-md-12" id="addurlInput" style="display:none;">
                         <div class="mb-3">
                             <label class="form-label" for="basic-icon-default-product">URL</label>
                             <div class="input-group input-group-merge">
                                 <input type="text"  name="url" class="form-control url" placeholder="Enper URL" autocomplete="off" >
                             </div>
                         </div>
                     </div>
                     
                     <div class="col-md-12" >
                         <div class="mb-3">
                             <label class="form-label" for="basic-icon-default-product">Tags <span class="small fw-semibold">(Separate tags with commas and use line breaks)</span></label>
                             <div class="input-group input-group-merge">
                                 <textarea  name="tag" class="form-control newTag" placeholder="Enter Tags" autocomplete="off" id="newTag"></textarea>
                            </div>
                            <ul id="tagList" class="tagList">
                            </ul>  
                            <input type="hidden" id="tagValues" name="tags" class="tagValues"/>
                         </div>
                     </div>      
                 </div>
                <div class="col-sm-12">
                    <div class="text-end">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            Close
                        </button>
                        <button type="submit" class="btn btn-primary">Add Resource</button>
                    </div>
                </div>
                 </div>
                 <?= $this->Form->end() ?>
             </div>
         </div>
     </div>
</div>


 <!-- Edit Resource -->
 <div class="modal fade" id="editResource" tabindex="-1" aria-hidden="true">

 </div>

 <div class="modal fade" id="showTaglist" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title tagtitle" id="modalScrollableTitle"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">               
                    <div class="row">
                    <table id="example" class="display table table-sm text-center" style="width:100%">
                     <thead>
                         <tr>
                         <th class="taghed"></th>
                         
                         </tr>
                     </thead>
                     <tbody class="table-border-bottom-0 taglist">                        
                         
                     </tbody>
                    </table>
                    </div>
                    
                </div>

            </div>
    </div>
 </div>

 <div class="modal fade" id="embedcodeModal" tabindex="-1" aria-labelledby="embedcodeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="reload()"></button>
        </div>
        <div class="modal-body text-center" id="embedCodeData">
        
        </div>
        </div>
    </div>
</div>

<!-- <script type="text/javascript" src="//cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script> -->
<!-- jquey -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
 <script type="text/javascript">

var checkboxStates = {};

function handleCheckboxClick(clickedCheckboxId) {
    var allCheckboxIds = ['addtext', 'addembed', 'addurl'];
    allCheckboxIds.forEach(function (checkboxId) {
        var currentCheckbox = document.getElementById(checkboxId);

        if (checkboxId !== clickedCheckboxId) {
            if (document.getElementById(clickedCheckboxId).checked) {
                currentCheckbox.removeAttribute('required');
                checkboxStates[checkboxId] = false;

            } else {
                if (checkboxStates[clickedCheckboxId]) {
                    currentCheckbox.setAttribute('required', 'required');
                } else {
                    currentCheckbox.removeAttribute('required');
                }
            }
        } else {
            checkboxStates[checkboxId] = !checkboxStates[checkboxId];
        }
    });
}
</script>

<script type="text/javascript">
    const TagListManager = {
        tagList: [],
        newTagInput: document.getElementById('newTag'),
        tagListContainer: document.getElementById('tagList'),
        tagValuesInput: document.getElementById('tagValues'),

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
            if (e.keyCode === 188 || e.keyCode === 13) {
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

    TagListManager.init();

    $(document).ready(function() {
        $('#modalScrollable').on('shown.bs.modal', function () {
            $('#summernoteEditor').summernote({
                height: 200,
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
});
    
</script>
