<!-- Payment Receipt Modal -->
<div class="modal fade" id="paymentReceiptModal" tabindex="-1" aria-labelledby="paymentReceiptModalLabel" aria-hidden="true">
    <div class="modal-dialog receipt-modal-dialog">
        <div class="modal-content receipt-modal-content">
            <div class="modal-header">
                <h5 class="modal-title w-100 text-center" id="paymentReceiptModalLabel">Payment Successful</h5>
            </div>
            <div class="modal-body d-flex flex-column justify-content-center align-items-center">
                <p class="text-center fs-4">Your payment was successfully processed.</p>
                <div class="d-flex flex-column align-items-center">
                    <button type="button" class="btn btn-secondary btn-lg mb-3 w-100" id="noReceipt" onclick="location.reload();">No Receipt</button>
                    <button type="button" class="btn btn-primary btn-lg mb-3 w-100" id="printReceipt">Print Receipt</button>
                    <button type="button" class="btn btn-info btn-lg mb-3 w-100" id="printA4">Print A4</button>
                    <button type="button" class="btn btn-success btn-lg mb-3 w-100" id="downloadInvoice">Download Invoice</button>
                    <button type="button" class="btn btn-warning btn-lg mb-3 w-100" id="emailInvoice" style="display: none;">Email Invoice</button>
                </div>
            </div>
        </div>
    </div>
</div>
