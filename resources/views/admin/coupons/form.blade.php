@csrf

<div class="row">
    <div class="col-md-6 mb-3">
        <label>Code</label>
        <input type="text" name="code" value="{{ old('code', $coupon->code) }}" class="form-control" required>
    </div>
    <div class="col-md-6 mb-3">
        <label>Type</label>
        <select name="type" class="form-control" required>
            <option value="fixed" {{ old('type', $coupon->type ?: 'fixed') === 'fixed' ? 'selected' : '' }}>Fixed amount</option>
            <option value="percent" {{ old('type', $coupon->type) === 'percent' ? 'selected' : '' }}>Percent</option>
        </select>
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-3">
        <label>Value</label>
        <input type="number" name="value" value="{{ old('value', $coupon->value) }}" step="0.01" min="0" class="form-control" required>
    </div>
    <div class="col-md-4 mb-3">
        <label>Minimum order</label>
        <input type="number" name="minimum_order" value="{{ old('minimum_order', $coupon->minimum_order ?? 0) }}" step="0.01" min="0" class="form-control">
    </div>
    <div class="col-md-4 mb-3">
        <label>Usage limit</label>
        <input type="number" name="usage_limit" value="{{ old('usage_limit', $coupon->usage_limit) }}" min="1" class="form-control">
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label>Starts at</label>
        <input type="datetime-local" name="starts_at" value="{{ old('starts_at', optional($coupon->starts_at)->format('Y-m-d\TH:i')) }}" class="form-control">
    </div>
    <div class="col-md-6 mb-3">
        <label>Expires at</label>
        <input type="datetime-local" name="expires_at" value="{{ old('expires_at', optional($coupon->expires_at)->format('Y-m-d\TH:i')) }}" class="form-control">
    </div>
</div>

<div class="form-check mb-3">
    <input type="hidden" name="is_active" value="0">
    <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" {{ old('is_active', $coupon->exists ? $coupon->is_active : true) ? 'checked' : '' }}>
    <label class="form-check-label" for="is_active">Active</label>
</div>

<button type="submit" class="btn btn-success">Save</button>
<a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary">Back</a>
