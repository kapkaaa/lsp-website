@extends('layouts.cashier')

@section('title', 'Online Orders')

@section('content_header')
    <h1>Online Orders</h1>
@stop

@section('content')
    <div class="container-fluid">
        <iframe src="{{ route('admin.orders.index') }}" style="width:100%; height:800px; border:none;" id="orders-frame"></iframe>
    </div>
@stop

@section('js')
    <script>
        // Optional: Handle iframe resize or other interactions
        window.addEventListener('load', function() {
            const frame = document.getElementById('orders-frame');
            if (frame) {
                // Adjust height based on content if needed
                frame.onload = function() {
                    // Potentially adjust height based on content
                };
            }
        });
    </script>
@stop