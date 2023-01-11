@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-3 col-sm-6 col-12">
                            <div class="card">
                                <div class="card-header">
                                    Shop
                                </div>
                                <div class="card-body">
                                    <p class="card-text"><h2 class="total-shop">{{ $total_shop }}</h2></p>
                                    <a href="{{ route('shop.index') }}" class="card-link">View All</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6 col-12">
                            <div class="card">
                                <div class="card-header">
                                    Product
                                </div>
                                <div class="card-body">
                                    <p class="card-text"><h2 class="total-product">{{ $total_product }}</h2></p>
                                    <a href="{{ route('product.index') }}" class="card-link">View All</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
    <script type="text/javascript">
        setInterval(function() {
            $.ajax({
                url: '/ajax/counter',
                type: 'POST',
                dataType: 'json'
            })
            .done(function(result) {
                if(result){
                    jQuery('.total-shop').html(result.shop);
                    jQuery('.total-product').html(result.product);
                }
            });
        }, 900000);
    </script>
@endpush