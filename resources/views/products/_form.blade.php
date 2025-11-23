@csrf

<div class="mb-3">
    <label class="form-label">Store</label>
    <select name="store_id" class="form-select">
        @foreach($stores as $s)
            <option value="{{ $s->id }}" {{ (old('store_id', $product->store_id ?? '') == $s->id) ? 'selected' : '' }}>{{ $s->name }}</option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label class="form-label">Category</label>
    <select name="category_id" class="form-select">
        @foreach($categories as $c)
            <option value="{{ $c->id }}" {{ (old('category_id', $product->category_id ?? '') == $c->id) ? 'selected' : '' }}>{{ $c->name }}</option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label class="form-label">Name</label>
    <input type="text" name="name" value="{{ old('name', $product->name ?? '') }}" class="form-control">
</div>

<div class="mb-3">
    <label class="form-label">Description</label>
    <textarea name="description" class="form-control">{{ old('description', $product->description ?? '') }}</textarea>
</div>

<div class="mb-3">
    <label class="form-label">Price</label>
    <input type="number" name="price" value="{{ old('price', $product->price ?? 0) }}" class="form-control">
</div>

<div class="mb-3">
    <label class="form-label">Stock</label>
    <input type="number" name="stock" value="{{ old('stock', $product->stock ?? 0) }}" class="form-control">
</div>

<div class="mb-3">
    <label class="form-label">Main Image</label>
    <input type="file" name="main_image" class="form-control">
    @if(!empty($product->main_image))
        <img src="{{ $product->main_image }}" alt="" style="max-width:120px;margin-top:8px">
    @endif
</div>
