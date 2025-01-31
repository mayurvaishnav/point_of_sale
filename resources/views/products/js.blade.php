<script>
    document.addEventListener('DOMContentLoaded', function () {
        const productPrice = document.getElementById('price');
        const taxRateSelect = document.getElementById('tax_rate');
        const taxIncludedYes = document.getElementById('taxIncludedYes');
        const taxIncludedNo = document.getElementById('taxIncludedNo');
        const sellingPrice = document.getElementById('selling_price');

        function calculateSellingPrice() {
            const price = parseFloat(productPrice.value) || 0;
            // Get the selected option and its data-rate attribute
            const selectedOption = taxRateSelect.options[taxRateSelect.selectedIndex];
            const tax = parseFloat(selectedOption.getAttribute('data-rate')) || 0;
            const taxIncluded = taxIncludedYes.checked;

            let total;
            if (taxIncluded) {
                total = price;
            } else {
                total = price + (price * (tax / 100));
            }

            sellingPrice.value = total.toFixed(2);
        }

        productPrice.addEventListener('input', calculateSellingPrice);
        taxRateSelect.addEventListener('change', calculateSellingPrice);
        taxIncludedYes.addEventListener('change', calculateSellingPrice);
        taxIncludedNo.addEventListener('change', calculateSellingPrice);
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

        // Select2 for the categories dropdown
        $('#categories').select2({
            placeholder: 'Select categories'
        });
        
        // Select2 for the tax_rate dropdown
        $('#tax_rate').select2({
            placeholder: 'Select tax rate'
        });
        
        // Select2 for the suppliers dropdown
        $('#suppliers').select2({
            placeholder: 'Select suppliers'
        });
    });
</script>