@extends('layouts.admin', ['hideSideBar' => true])

@section('title', 'Add New Discount')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/discounts/create.css') }}">
@endpush

@section('content')
    <div class="main-container">
        <header>
            <h1>Add New Discount</h1>
            <a href="{{ route('admin.discounts.index') }}" id="btn-back" class="view-btn btn-back">Back to List</a>
        </header>

        <div class="form-container">
            <form onsubmit="confirmModal(event, 'Create Discount', 'Are you sure to create discount?')"
                action="{{ route('admin.discounts.store') }}" method="POST" class="discount-form">
                @csrf

                <div class="form-group">
                    <label for="code">Discount Code <span class="required">*</span></label>
                    <input type="text" name="code" id="code" value="{{ old('code') }}"
                        placeholder="e.g. SUMMER20, FREESHIP" required class="form-input">
                </div>

                <div class="form-group">
                    <label for="type">Discount Type <span class="required">*</span></label>
                    <select name="type" id="type" class="form-input" required>
                        <option value="fixed" {{ old('type') == 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
                        <option value="percentage" {{ old('type') == 'percentage' ? 'selected' : '' }}>Percentage
                        </option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="value">Value <span class="required">*</span></label>
                    <div class="input-with-suffix">
                        <input type="number" name="value" id="value" value="{{ old('value') }}"
                            placeholder="e.g. 10 (for 10 VND or 10%)" min="0" step="0.01" required
                            class="form-input">
                        <span class="suffix" id="value-suffix">%</span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="min_order_value">Minimum Order Value</label>
                    <div class="input-with-suffix">
                        <input type="number" name="min_order_value" id="min_order_value"
                            value="{{ old('min_order_value') }}" placeholder="e.g. 100000" min="0" step="0.01"
                            class="form-input">
                        <span class="suffix">VND</span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="max_discount_amount">Maximum Discount Amount (for percentage type)</label>
                    <div class="input-with-suffix">
                        <input type="number" name="max_discount_amount" id="max_discount_amount"
                            value="{{ old('max_discount_amount') }}" placeholder="e.g. 50000" min="0" step="0.01"
                            class="form-input">
                        <span class="suffix">VND</span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="usage_limit">Usage Limit (total uses)</label>
                    <input type="number" name="usage_limit" id="usage_limit" value="{{ old('usage_limit') }}"
                        placeholder="e.g. 100 (leave empty for unlimited)" min="0" class="form-input">
                </div>

                <div class="form-group">
                    <label for="expires_at">Expires At</label>
                    <input type="datetime-local" name="expires_at" id="expires_at" value="{{ old('expires_at') }}"
                        class="form-input">
                </div>

                <div class="form-group-with-checkbox">
                    <input type="checkbox" name="is_active" id="is_active" value="1"
                        {{ old('is_active', true) ? 'checked' : '' }} class="form-checkbox">
                    <label for="is_active" class="form-checkbox-label">Is Active</label>
                </div>

                <div class="form-actions">
                    <button type="submit" class="view-btn btn-save">Create Discount</button>
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
            const maxDiscountAmountInput = document.getElementById('max_discount_amount');
            const maxDiscountAmountGroup = maxDiscountAmountInput ? maxDiscountAmountInput.closest('.form-group') :
                null;

            // 1. Logic change suffix value by type
            function updateValueSuffix() {
                if (!discountType || !valueSuffix) return;

                if (discountType.value === 'percentage') {
                    valueSuffix.textContent = '%';
                    if (maxDiscountAmountGroup) maxDiscountAmountGroup.style.display = 'block';
                } else {
                    valueSuffix.textContent = 'VND';
                    if (maxDiscountAmountGroup) maxDiscountAmountGroup.style.display = 'none';
                }
            }

            if (discountType) {
                discountType.addEventListener('change', updateValueSuffix);
                updateValueSuffix();
            }

            // 2. Logic check before go back to list (Button Back)
            const btnBack = document.getElementById('btn-back');
            if (btnBack) {
                btnBack.addEventListener('click', function(e) {
                    // Get all value
                    const fields = [
                        'code', 'value', 'min_order_value',
                        'max_discount_amount', 'usage_limit', 'expires_at'
                    ];

                    // Check input value
                    const isFormDirty = fields.some(id => {
                        const input = document.getElementById(id);
                        return input && input.value.trim() !== '';
                    });

                    // Has value case
                    if (isFormDirty) {
                        e.preventDefault();
                        confirmModal(e, 'Back', 'Are you sure to clear all input and back to list?');
                    }
                });
            }
        });
    </script>
@endpush
