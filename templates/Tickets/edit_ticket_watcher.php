<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel1">Edit Watcher</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <?= $this->Form->create(null, ['method' => 'post','id' => 'TicketwatcherForm']) ?>
            <div class="mb-3">                
                <label for="form-select" class="form-label">Watchers</label>
                <select class="form-select form-control-lg" id="form-select" name="watchers[]" multiple="multiple" style="width: 100%">
                    <?php
                     foreach ($watcherData as $val) : ?>
                        <?php
                        $selected = in_array($val->Users['id'], array_column($ticketWatchers, 'watcher_id')) ? 'selected' : '';
                        ?>
                        <option value="<?= $val->Users['id'] ?>" <?= $selected ?>>
                            <?= ucfirst($val->Users['first_name']) ?> <?= ucfirst($val->Users['last_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <input type="hidden" name="editId" value="<?= $ticketID ?>">

            <div class="text-end">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#form-select').select2({
            dropdownParent: $('#ticketWatcherModal .modal-content'),
            placeholder: 'Select an option',
            width: 'resolve'
        });

        //submit form
        $('#TicketwatcherForm').on('submit', function(e) {
            e.preventDefault();
            //disable submit button and change text with loader
            $(this).find('[type="submit"]').text('Processing...').prop('disabled', true);
            // add loader also
            $(this).find('[type="submit"]').append(`<div class="spinner-border spinner-border-sm ms-2" role="status" aria-hidden="true"></div>`);

            $.ajax({
                type: 'POST',
                url: `${baseUrl}/Tickets/editTicketWatcher`, 
                data: $(this).serialize(),
                dataType: 'json', 
                success: function(response) {
                    $('#ticketWatcherModal').modal('hide');

                    if (response.watchers && response.watchers.length > 0) {
                        if ($('#ticket_watcher_list').length === 0) {
                            $('#watcherDiv').append(`
                                <h6 class="mt-5 mt-lg-0">Watchers</h6>
                                <ul class="nav flex-lg-column fs--1" id="ticket_watcher_list"></ul>
                            `);
                        } else {
                            // Empty the current watcher list
                            $('#ticket_watcher_list').empty();
                        }            
                    }
                    // Iterate over the response data to create new list items
                    response.watchers.forEach(function(watcher) {
                        $('#ticket_watcher_list').append(`
                            <li class="nav-item me-2 me-lg-0">
                                <a class="nav-link nav-link-card-details" href="#!">
                                    ${watcher.watcher_name}
                                </a>
                            </li>
                        `);
                    });

                },
                error: function(xhr, status, error) {
                    // Handle error
                    console.error('Form submission failed:', status, error);
                }
            });
        });
    });
</script>