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
                        <label for="totalAmount">Subtotal Amount</label>
                        <input type="number" class="form-control" id="totalAmount" name="amount_paid" value="{{ $cart->getTotalCart()->total }}" readonly>
                    </div>
                    <div class="form-group">
                        <label for="totalAmount">Discount Amount</label>
                        <input type="number" class="form-control" id="discountAmount" name="amount_paid" value="0">
                    </div>
                    <div class="form-group">
                        <label for="totalAmount">Total Amount</label>
                        <input type="number" class="form-control" id="amountPaid" name="amount_paid" value="{{ $cart->getTotalCart()->total }}" readonly>
                    </div>
                    <div class="form-group">
                        <label for="orderNote">Note</label>
                        <textarea class="form-control" id="orderNote" name="order_note" rows="3">{{ old('order_note', $cart->orderNote ?? '') }}</textarea>
                    </div>

                    <!-- Payment Method Buttons -->
                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-primary btn-lg payment-option" data-method="CASH">Cash</button>
                        <button type="button" class="btn btn-success btn-lg payment-option" data-method="CREDIT_CARD">Credit Card</button>
                        <button type="button" id="customerAccountPay" class="btn btn-warning btn-lg payment-option" data-method="CUSTOMER_ACCOUNT" style="{{ $cart->customer ? '' : 'display: none;' }}">Customer Account</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    
    document.addEventListener('DOMContentLoaded', function () {
        const discount = document.getElementById('discountAmount');
        const totalAmount = document.getElementById('totalAmount');
        const amountPaid = document.getElementById('amountPaid');

        

        discount.addEventListener('input', calculateTotalPrice);
        totalAmount.addEventListener('change', calculateTotalPrice);

        calculateTotalPrice();
    });

    function calculateTotalPrice() {
        const discount = document.getElementById('discountAmount');
        const totalAmount = document.getElementById('totalAmount');
        const amountPaid = document.getElementById('amountPaid');
        
        
        const total = parseFloat(totalAmount.value) || 0;
        const discountValue = parseFloat(discount.value) || 0;

        let finalTotal = total - discountValue;

        amountPaid.value = finalTotal.toFixed(2);
    }
</script>