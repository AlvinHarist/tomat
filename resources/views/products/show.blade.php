<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Product</title>
</head>
<body class="p-4">
    <div class="container">
        <h1>{{ $product->name }}</h1>
        <p><strong>Store:</strong> {{ optional($product->store)->name }}</p>
        <p><strong>Category:</strong> {{ optional($product->category)->name }}</p>
        <p><strong>Price:</strong> {{ number_format($product->price,0,',','.') }}</p>
        <p><strong>Stock:</strong> {{ $product->stock }}</p>
        <p>{{ $product->description }}</p>
        @if($product->main_image)
            <img src="{{ $product->main_image }}" style="max-width:320px">
        @endif

        <div class="mt-3">
            <a href="{{ route('products.edit', $product) }}" class="btn btn-secondary">Edit</a>
            <a href="{{ route('products.index') }}" class="btn btn-link">Back</a>
        </div>
    </div>
</body>
</html>
