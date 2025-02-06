<div class="modal fade" id="editCustomerModal" tabindex="-1" role="dialog" aria-labelledby="editCustomerModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCustomerModalLabel">Update Customer</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editCustomerForm" action="{{ route('orders.updateCustomer', $order) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="customerSelect">Select Customer</label>
                        <select class="form-control select2" name="customer_id" id="customerSelect">
                            <option value="">-- Select Customer --</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}" {{ $customer->id == $order->customer_id ? 'selected' : '' }}>
                                    {{ $customer->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-info">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>
