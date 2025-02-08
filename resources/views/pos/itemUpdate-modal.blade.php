<!-- Edit Item Modal -->
<div class="modal fade" id="editItemModal" tabindex="-1" aria-labelledby="editItemModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editItemModalLabel">Edit Cart Item</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="editItemId">
                
                <div class="form-group">
                    <label for="editItemName">Item Name</label>
                    <input type="text" id="editItemName" class="form-control">
                </div>

                <div class="form-group">
                    <label for="editItemQuantity">Quantity</label>
                    <input type="number" id="editItemQuantity" class="form-control">
                </div>

                <div class="form-group">
                    <label for="editItemPrice">Price</label>
                    <input type="number" id="editItemPrice" class="form-control" step="0.01">
                </div>

                <div class="form-group">
                    <label for="editTotalPrice">Price</label>
                    <input type="number" id="editTotalPrice" class="form-control" step="0.01" readonly>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger" id="deleteFromCart">Delete</button>
                <button type="button" class="btn btn-primary" id="updateItem">Update</button>
            </div>
        </div>
    </div>
</div>

<script>
    
    document.addEventListener("DOMContentLoaded", function () {
        // Select the elements correctly
        const newQuantity = document.getElementById("editItemQuantity");
        const newPrice = document.getElementById("editItemPrice");
        const totalAmount = document.getElementById("editTotalPrice");

        // Add event listeners safely
        if (newQuantity) {
            newQuantity.addEventListener("input", calculateTotalPriceUpdateItem);
        }

        if (newPrice) {
            newPrice.addEventListener("input", calculateTotalPriceUpdateItem);
        }
    });

    function calculateTotalPriceUpdateItem() {
        // Get values
        const newQuantity = document.getElementById("editItemQuantity").value;
        const newPrice = document.getElementById("editItemPrice").value;
        const totalAmount = document.getElementById("editTotalPrice");

        // Convert values safely
        const quantity = parseInt(newQuantity) || 1;
        const price = parseFloat(newPrice) || 0;

        // Calculate total
        const finalTotal = quantity * price;

        // Update total field
        totalAmount.value = finalTotal.toFixed(2);
    }

    
</script>
