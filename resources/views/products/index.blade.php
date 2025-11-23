<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Products</title>
</head>
<body class="p-4">
    <div class="container">
        <div class="d-flex justify-content-between mb-3">
            <h1>Products</h1>
            <a href="{{ route('products.create') }}" class="btn btn-primary">New Product</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Store</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $p)
                <tr>
                    <td>{{ $p->id }}</td>
                    <td><a href="{{ route('products.show', $p) }}">{{ $p->name }}</a></td>
                    <td>{{ optional($p->store)->name }}</td>
                    <td>{{ optional($p->category)->name }}</td>
                    <td>{{ number_format($p->price,0,',','.') }}</td>
                    <td>{{ $p->stock }}</td>
                    <td class="text-end">
                        <a href="{{ route('products.edit', $p) }}" class="btn btn-sm btn-secondary">Edit</a>
                        <form action="{{ route('products.destroy', $p) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Delete product?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{ $products->links() }}
    </div>
</body>
</html>
