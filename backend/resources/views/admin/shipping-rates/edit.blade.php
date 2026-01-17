@extends('layouts.admin')

@section('page_title', 'Edit Shipping Rate')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.shipping-rates.index') }}">Shipping Rates</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('main_content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-warning">
                    <h3 class="card-title">
                        <i class="fas fa-edit"></i> Edit Shipping Rate
                    </h3>
                </div>
                <form action="{{ route('admin.shipping-rates.update', $shippingRate->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group">
                            <label for="region">
                                Region / Destination <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('region') is-invalid @enderror" 
                                   id="region" 
                                   name="region" 
                                   value="{{ old('region', $shippingRate->region) }}" 
                                   placeholder="e.g., Jakarta, Bandung, Jawa Barat" 
                                   required>
                            @error('region')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="price_per_kg">
                                Price per Kg <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="number" 
                                       class="form-control @error('price_per_kg') is-invalid @enderror" 
                                       id="price_per_kg" 
                                       name="price_per_kg" 
                                       value="{{ old('price_per_kg', $shippingRate->price_per_kg) }}" 
                                       placeholder="0" 
                                       min="0"
                                       step="1000"
                                       required>
                                @error('price_per_kg')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <h6><i class="fas fa-info-circle"></i> Current Usage:</h6>
                            <p class="mb-0">
                                This shipping rate is used in <strong>{{ $shippingRate->orders->count() }}</strong> orders.
                            </p>
                        </div>

                        <div id="calculatorPreview" class="card card-outline card-success">
                            <div class="card-header">
                                <h5 class="card-title">
                                    <i class="fas fa-calculator"></i> Price Preview
                                </h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-sm">
                                    <tr>
                                        <td>1-3 kaos (1 kg):</td>
                                        <td class="text-right"><strong id="price1kg">Rp {{ number_format($shippingRate->price_per_kg, 0, ',', '.') }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>4-6 kaos (2 kg):</td>
                                        <td class="text-right"><strong id="price2kg">Rp {{ number_format($shippingRate->price_per_kg * 2, 0, ',', '.') }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>7-9 kaos (3 kg):</td>
                                        <td class="text-right"><strong id="price3kg">Rp {{ number_format($shippingRate->price_per_kg * 3, 0, ',', '.') }}</strong></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-save"></i> Update
                        </button>
                        <a href="{{ route('admin.shipping-rates.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-bar"></i> Usage Statistics
                    </h3>
                </div>
                <div class="card-body">
                    <h6>Shipping Rate Details:</h6>
                    <table class="table table-sm">
                        <tr>
                            <th width="40%">Region:</th>
                            <td>{{ $shippingRate->region }}</td>
                        </tr>
                        <tr>
                            <th>Current Price:</th>
                            <td>Rp {{ number_format($shippingRate->price_per_kg, 0, ',', '.') }}/kg</td>
                        </tr>
                        <tr>
                            <th>Total Orders:</th>
                            <td>
                                <span class="badge badge-info">
                                    {{ $shippingRate->orders->count() }} orders
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Created:</th>
                            <td>{{ $shippingRate->created_at->format('d M Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Last Updated:</th>
                            <td>{{ $shippingRate->updated_at->format('d M Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="callout callout-warning">
                <h5><i class="fas fa-exclamation-triangle"></i> Warning:</h5>
                <p class="mb-0">
                    Changing the price will not affect existing orders. 
                    Only new orders will use the updated price.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
$(document).ready(function() {
    // Price calculator preview
    $('#price_per_kg').on('input', function() {
        let price = parseInt($(this).val()) || 0;
        
        $('#price1kg').text('Rp ' + (price * 1).toLocaleString('id-ID'));
        $('#price2kg').text('Rp ' + (price * 2).toLocaleString('id-ID'));
        $('#price3kg').text('Rp ' + (price * 3).toLocaleString('id-ID'));
    });
});
</script>
@endpush