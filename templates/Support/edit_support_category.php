<div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
         <div class="modal-content editPro">
             <div class="modal-header">
                 <h5 class="modal-title" id="modalScrollableTitle">Edit Category</h5>
                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
             </div>
             <div class="modal-body">
                <?= $this->Form->create(null, ['action' => 'Support/editSupportCategory','id'=>'categoryForm']) ?>
                 <div class="row">
                     <div class="col-md-12">
                         <div class="mb-3">
                             <label class="form-label" for="basic-icon-default-product">Category Name</label>
                             <div class="input-group input-group-merge">
                                 <input type="text"  name="category_name" class="form-control" value="<?= $categoryData->name ?>" autocomplete="off" required>
                             </div>
                         </div>
                     </div>
                     <input type="hidden" value="<?= $categoryData->id ?>" name="editId">
                 </div>
                    
                     <div class="col-sm-12">
                        <div class="text-end">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            Close
                        </button>
                        <button type="submit" class="btn btn-primary edtsbmt">Edit Category</button>
                        </div>
                     </div>

                 </div>
                 <?= $this->Form->end() ?>
             </div>

         </div>
     </div>