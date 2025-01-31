<script>
    document.addEventListener('DOMContentLoaded', function () {
        const productPrice = document.getElementById('price');
        const taxRate = document.getElementById('tax_rate_id');
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

    $(document).ready(function() {
        // Function to toggle the visibility of the stock information section
        function toggleStockInformation() {
            if ($('#stockableYes').is(':checked')) {
                $('#stockInformation').show();
            } else {
                $('#stockInformation').hide();
            }
        }

        // Initial check on page load
        toggleStockInformation();

        // Event listeners for the radio buttons
        $('#stockableYes, #stockableNo').change(function() {
            toggleStockInformation();
        });
    });
</script>