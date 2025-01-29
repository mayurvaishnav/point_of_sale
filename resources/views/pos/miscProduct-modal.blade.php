<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productModalLabel">Add Product</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="productForm">
                    @csrf
                    <div class="form-group">
                        <label for="productName">Product Name</label>
                        <input type="text" class="form-control" id="productName" name="product_name" required>
                    </div>

                    <div class="form-group">
                        <label for="productPrice">Price</label>
                        <input type="number" class="form-control" id="productPrice" name="price" required>
                    </div>

                    <div class="form-group">
                        <label for="taxRate">Tax Rate (%)</label>
                        <input type="number" class="form-control" id="taxRate" name="tax_rate" required>
                    </div>

                    <div class="form-group">
                        <label>Tax Included?</label>
                        <div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="taxIncludedYes" name="tax_included" class="custom-control-input" value="1">
                                <label class="custom-control-label" for="taxIncludedYes">Yes</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="taxIncludedNo" name="tax_included" class="custom-control-input" value="0" checked>
                                <label class="custom-control-label" for="taxIncludedNo">No</label>
                            </div>
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="totalPrice">Price to Customer</label>
                        <input type="text" class="form-control" id="totalPrice" readonly>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const productPrice = document.getElementById('productPrice');
        const taxRate = document.getElementById('taxRate');
        const taxIncludedYes = document.getElementById('taxIncludedYes');
        const taxIncludedNo = document.getElementById('taxIncludedNo');
        const totalPrice = document.getElementById('totalPrice');

        function calculateTotalPrice() {
            const price = parseFloat(productPrice.value) || 0;
            const tax = parseFloat(taxRate.value) || 0;
            const taxIncluded = taxIncludedYes.checked;

            let total;
            if (taxIncluded) {
                total = price;
            } else {
                total = price + (price * (tax / 100));
            }

            totalPrice.value = total.toFixed(2);
        }

        productPrice.addEventListener('input', calculateTotalPrice);
        taxRate.addEventListener('input', calculateTotalPrice);
        taxIncludedYes.addEventListener('change', calculateTotalPrice);
        taxIncludedNo.addEventListener('change', calculateTotalPrice);
    });
</script>