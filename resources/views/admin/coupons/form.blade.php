<div class="grid grid-cols-2 gap-4">

    <div>
        <label class="block">Name</label>
        <input type="text" name="name" class="w-full border p-2 rounded"
            value="{{ old('name', $coupon->name ?? '') }}">
    </div>

    <div>
        <label class="block">Code</label>
        <input type="text" name="code" class="w-full border p-2 rounded"
            value="{{ old('code', $coupon->code ?? '') }}">
    </div>

    <div>
        <label class="block">Type</label>
        <select name="type" class="w-full border p-2 rounded">
            <option value="fixed" {{ old('type', $coupon->type ?? '') == 'fixed' ? 'selected' : '' }}>Fixed</option>
            <option value="percentage" {{ old('type', $coupon->type ?? '') == 'percentage' ? 'selected' : '' }}>Percentage</option>
        </select>
    </div>

    <div>
        <label class="block">Value</label>
        <input type="number" step="0.01" name="value" class="w-full border p-2 rounded"
            value="{{ old('value', $coupon->value ?? 0) }}">
    </div>

    <div>
        <label class="block">Min Order Amount</label>
        <input type="number" step="0.01" name="min_order_amount" class="w-full border p-2 rounded"
            value="{{ old('min_order_amount', $coupon->min_order_amount ?? 0) }}">
    </div>

    <div>
        <label class="block">Usage Limit</label>
        <input type="number" name="usage_limit" class="w-full border p-2 rounded"
            value="{{ old('usage_limit', $coupon->usage_limit ?? '') }}">
    </div>

    <div>
        <label class="block">Usage Limit Per User</label>
        <input type="number" name="usage_limit_per_user" class="w-full border p-2 rounded"
            value="{{ old('usage_limit_per_user', $coupon->usage_limit_per_user ?? '') }}">
    </div>

    <div>
        <label class="block">Start Date</label>
        <input type="datetime-local" name="start_date" class="w-full border p-2 rounded"
            value="{{ old('start_date', isset($coupon) ? $coupon->start_date->format('Y-m-d\TH:i') : '') }}">
    </div>

    <div>
        <label class="block">Expiration Date</label>
        <input type="datetime-local" name="expiration_date" class="w-full border p-2 rounded"
            value="{{ old('expiration_date', isset($coupon) ? $coupon->expiration_date->format('Y-m-d\TH:i') : '') }}">
    </div>

</div>
