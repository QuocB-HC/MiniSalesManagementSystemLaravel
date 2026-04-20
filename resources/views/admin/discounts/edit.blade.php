@extends('layouts.admin', ['hideSideBar' => true])

@section('title', 'Edit Discount: ' . $discount->code)

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/discounts/create.css') }}">
@endpush

@section('content')
    <div class="main-container create-container">
            <header  class="form-header">
                <h1>Edit Discount</h1>
                <a href="{{ route('admin.discounts.index') }}" class="view-btn btn-back">Back to List</a>
            </header>

            <div class="form-container">
                <form onsubmit="confirmModal(event, 'Update Discount', 'Are you sure to update discount?')"
                   action="{{ route('admin.discounts.update', $discount->id) }}" method="POST" class="discount-form">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="code">Discount Code <span class="required">*</span></label>
                        <input type="text" name="code" id="code" value="{{ old('code', $discount->code) }}"
                            placeholder="e.g. SUMMER20, FREESHIP" required class="form-input">
                    </div>

                    <div class="form-group">
                        <label for="type">Discount Type <span class="required">*</span></label>
                        <select name="type" id="type" class="form-input" required>
                            <option value="fixed" {{ old('type', $discount->type) == 'fixed' ? 'selected' : '' }}>Fixed
                                Amount</option>
                            <option value="percentage" {{ old('type', $discount->type) == 'percentage' ? 'selected' : '' }}>
                                Percentage
                            </option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="value">Value <span class="required">*</span></label>
                        <div class="input-with-suffix">
                            <input type="number" name="value" id="value"
                                value="{{ old('value', $discount->value) }}" placeholder="e.g. 10 (for 10 VND or 10%)"
                                min="0" step="0.01" required class="form-input">
                            <span class="suffix" id="value-suffix">%</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="min_order_value">Minimum Order Value</label>
                        <div class="input-with-suffix">
                            <input type="number" name="min_order_value" id="min_order_value"
                                value="{{ old('min_order_value', $discount->min_order_value) }}" placeholder="e.g. 100000"
                                min="0" step="0.01" class="form-input">
                            <span class="suffix">VND</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="max_discount_amount">Maximum Discount Amount (for percentage type)</label>
                        <div class="input-with-suffix">
                            <input type="number" name="max_discount_amount" id="max_discount_amount"
                                value="{{ old('max_discount_amount', $discount->max_discount_amount) }}"
                                placeholder="e.g. 50000" min="0" step="0.01" class="form-input">
                            <span class="suffix">VND</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="usage_limit">Usage Limit (total uses)</label>
                        <input type="number" name="usage_limit" id="usage_limit"
                            value="{{ old('usage_limit', $discount->usage_limit) }}"
                            placeholder="e.g. 100 (leave empty for unlimited)" min="0" class="form-input">
                    </div>

                    <div class="form-group">
                        <label for="expires_at">Expires At</label>
                        <input type="datetime-local" name="expires_at" id="expires_at"
                            value="{{ old('expires_at', $discount->expires_at ? \Carbon\Carbon::parse($discount->expires_at)->format('Y-m-d\TH:i') : '') }}"
                            class="form-input">
                    </div>

                    <div class="form-group-with-checkbox">
                        <input type="checkbox" name="is_active" id="is_active" value="1"
                            {{ old('is_active', $discount->is_active) ? 'checked' : '' }} class="form-checkbox">
                        <label for="is_active" class="form-checkbox-label">Is Active</label>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="view-btn btn-save">Update Discount</button>
                        <button type="reset" class="view-btn btn-reset">Reset Form</button>
                    </div>
                </form>
            </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const discountType = document.getElementById('type');
            const valueSuffix = document.getElementById('value-suffix');
            const maxDiscountAmountGroup = document.getElementById('max_discount_amount').closest('.form-group');

            function updateValueSuffix() {
                if (discountType.value === 'percentage') {
                    valueSuffix.textContent = '%';
                    maxDiscountAmountGroup.style.display = 'block'; // Show max discount for percentage
                } else {
                    valueSuffix.textContent = 'VND';
                    maxDiscountAmountGroup.style.display = 'none'; // Hide max discount for fixed
                }
            }

            discountType.addEventListener('change', updateValueSuffix);
            updateValueSuffix(); // Initial call to set the correct suffix and visibility on page load
        });
    </script>
@endpush
