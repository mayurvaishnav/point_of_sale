<!-- Add/Update Note Modal -->
<div class="modal fade" id="updateNoteModal" tabindex="-1" aria-labelledby="updateNoteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateNoteModalLabel">Order Note</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="orderNote">Note:</label>
                    <textarea class="form-control" id="updateOrderNote" name="order_note" rows="5">{{ old('order_note', $cart->orderNote ?? '') }}</textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="note-update-button">Update</button>
            </div>
        </div>
    </div>
</div>