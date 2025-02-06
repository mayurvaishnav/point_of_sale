<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentModalLabel">Select Payment Method</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="paymentForm">
                    <!-- Pre-filled total amount -->
                    <div class="form-group">
                        <label for="totalAmount">Total Amount</label>
                        <input type="text" class="form-control" id="amountPaid" name="amount_paid" value="{{ $cart->getTotalCart()->total }}">
                    </div>

                    <!-- Payment Method Buttons -->
                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-primary btn-lg payment-option" data-method="CASH">Cash</button>
                        <button type="button" class="btn btn-success btn-lg payment-option" data-method="CREDIT_CARD">Credit Card</button>
                        <button type="button" class="btn btn-warning btn-lg payment-option" data-method="CUSTOMER_ACCOUNT">Customer Account</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
