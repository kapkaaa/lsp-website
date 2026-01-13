@if($products->count() > 0)
<table class="table table-hover table-striped">
    <thead>
        <tr>
            <th width="5%">No</th>
            <th>Image</th>
            <th>Product Name</th>
            <th>Brand</th>
            <th>Type</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Variants</th>
            <th width="15%">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($products as $index => $product)
        <tr>
            <td>{{ $products->firstItem() + $index }}</td>
            <td>
                @if($product->productDetails->first()?->photos->first())
                    <img src="{{ $product->productDetails->first()->photos->first()->photo_url }}"
                         alt="{{ $product->name }}"
                         class="img-thumbnail-list"
                         style="width:50px; height:auto;">
                @else
                    <span class="text-muted">No Image</span>
                @endif
            </td>
            <td><strong>{{ $product->name }}</strong></td>
            <td>{{ $product->brand?->name ?? '-' }}</td>
            <td>{{ $product->type?->name ?? '-' }}</td>
            <td>Rp {{ number_format($product->selling_price, 0, ',', '.') }}</td>
            <td>
                @php
                    $totalStock = $product->getTotalStock();
                    $availableStock = $product->getAvailableStock();
                @endphp
                <span class="badge {{ $availableStock == 0 ? 'badge-danger' : ($availableStock < 10 ? 'badge-warning' : 'badge-success') }}">
                    {{ $availableStock }} / {{ $totalStock }} pcs
                </span>
            </td>
            <td>
                <span class="badge badge-info">{{ $product->productDetails->count() }} variants</span>
            </td>
            <td>
                <div class="btn-group">
                    <a href="{{ route('admin.products.show', $product->id) }}" class="btn btn-sm btn-info" title="View">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-warning" title="Edit">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" style="display:inline;">
                        @csrf @method('DELETE')
                        <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete(this.form)" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
<div class="card-footer clearfix">
    {{ $products->appends(request()->query())->links('pagination::bootstrap-4') }}
</div>
@else
<div class="text-center text-muted p-3">No products found</div>
@endif