<!-- Email Input Modal -->
<div class="modal fade" id="emailInvoiceModal" tabindex="-1" role="dialog" aria-labelledby="emailModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="emailModalLabel">Enter Email Address</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('orders.emailInvoice', $order) }}" method="POST">
                @csrf
                <div class="modal-body">
                    {{-- <label for="email" class="text-left">Customer Email:</label> --}}
                    <input type="email" name="email" class="form-control" required placeholder="Enter email">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Send Invoice</button>
                </div>
            </form>
        </div>
    </div>
</div>