{{-- resources/views/admin/shipping-rates/create.blade.php --}}
@extends('layouts.admin')

@section('page_title', 'Add Shipping Rate')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.shipping-rates.index') }}">Shipping Rates</a></li>
    <li class="breadcrumb-item active">Create</li>
@endsection

@section('main_content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary">
                    <h3 class="card-title text-white">
                        <i class="fas fa-plus"></i> Add New Shipping Rate
                    </h3>
                </div>
                <form action="{{ route('admin.shipping-rates.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="region">
                                Region / Destination <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('region') is-invalid @enderror" 
                                   id="region" 
                                   name="region" 
                                   value="{{ old('region') }}" 
                                   placeholder="e.g., Jakarta, Bandung, Jawa Barat" 
                                   required>
                            @error('region')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="text-muted">
                                <i class="fas fa-info-circle"></i> 
                                Enter city or province name (must be in Java island)
                            </small>
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
                                       value="{{ old('price_per_kg') }}" 
                                       placeholder="0" 
                                       min="0"
                                       step="1000"
                                       required>
                                @error('price_per_kg')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <small class="text-muted">
                                <i class="fas fa-info-circle"></i> 
                                Enter price in Rupiah (e.g., 24000 for Rp 24,000)
                            </small>
                        </div>

                        <div class="alert alert-info">
                            <h6><i class="fas fa-lightbulb"></i> Shipping Calculation:</h6>
                            <ul class="mb-0">
                                <li>1 kg = 3 kaos</li>
                                <li>Example: 5 kaos = 2 kg (ceil(5/3))</li>
                                <li>Total cost = weight × price per kg</li>
                            </ul>
                        </div>

                        <div id="calculatorPreview" class="card card-outline card-success" style="display:none;">
                            <div class="card-header">
                                <h5 class="card-title">
                                    <i class="fas fa-calculator"></i> Price Preview
                                </h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-sm">
                                    <tr>
                                        <td>1-3 kaos (1 kg):</td>
                                        <td class="text-right"><strong id="price1kg">Rp 0</strong></td>
                                    </tr>
                                    <tr>
                                        <td>4-6 kaos (2 kg):</td>
                                        <td class="text-right"><strong id="price2kg">Rp 0</strong></td>
                                    </tr>
                                    <tr>
                                        <td>7-9 kaos (3 kg):</td>
                                        <td class="text-right"><strong id="price3kg">Rp 0</strong></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Save
                        </button>
                        <a href="{{ route('admin.shipping-rates.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-map-marked-alt"></i> Available Regions
                    </h3>
                </div>
                <div class="card-body">
                    <h6>Current Shipping Rates:</h6>
                    <table class="table table-sm table-striped">
                        <thead>
                            <tr>
                                <th>Region</th>
                                <th class="text-right">Price/Kg</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Jakarta</td>
                                <td class="text-right">Rp 24.000</td>
                            </tr>
                            <tr>
                                <td>Depok</td>
                                <td class="text-right">Rp 24.000</td>
                            </tr>
                            <tr>
                                <td>Bekasi</td>
                                <td class="text-right">Rp 25.000</td>
                            </tr>
                            <tr>
                                <td>Tangerang</td>
                                <td class="text-right">Rp 25.000</td>
                            </tr>
                            <tr>
                                <td>Bogor</td>
                                <td class="text-right">Rp 27.000</td>
                            </tr>
                            <tr>
                                <td>Jawa Barat</td>
                                <td class="text-right">Rp 31.000</td>
                            </tr>
                            <tr>
                                <td>Jawa Tengah</td>
                                <td class="text-right">Rp 39.000</td>
                            </tr>
                            <tr>
                                <td>Jawa Timur</td>
                                <td class="text-right">Rp 47.000</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="callout callout-warning">
                <h5><i class="fas fa-exclamation-triangle"></i> Important Notes:</h5>
                <ul class="mb-0">
                    <li>Only serve shipping to Java island</li>
                    <li>Price is per kilogram</li>
                    <li>1 kg = 3 t-shirts (kaos)</li>
                    <li>Minimum weight is 1 kg</li>
                </ul>
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
        
        if (price > 0) {
            $('#calculatorPreview').show();
            $('#price1kg').text('Rp ' + (price * 1).toLocaleString('id-ID'));
            $('#price2kg').text('Rp ' + (price * 2).toLocaleString('id-ID'));
            $('#price3kg').text('Rp ' + (price * 3).toLocaleString('id-ID'));
        } else {
            $('#calculatorPreview').hide();
        }
    });
});
</script>
@endpush