<div class="mb-3">
    <label class="form-label">Ten menu</label>
    <input name="name" value="{{ old('name', $menu->name ?? '') }}" class="form-control" required>
</div>
<div class="mb-3">
    <label class="form-label">Route Laravel</label>
    <input name="route" value="{{ old('route', $menu->route ?? '') }}" class="form-control" placeholder="home, shop, cart, checkout, contact">
</div>
<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label">Type</label>
        <input name="type" value="{{ old('type', $menu->type ?? 'link') }}" class="form-control" required>
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Menu cha</label>
        <select name="parent_id" class="form-select">
            <option value="">Khong co</option>
            @foreach($parents as $parent)
                <option value="{{ $parent->id }}" {{ old('parent_id', $menu->parent_id ?? null) == $parent->id ? 'selected' : '' }}>{{ $parent->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Thu tu</label>
        <input type="number" name="order" value="{{ old('order', $menu->order ?? 0) }}" class="form-control" min="0">
    </div>
</div>
