@extends('layouts.front')

@section('content')
<!-- Breadcrumb Area Start -->
<div class="breadcrumb-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <ul class="pages">
                    <li>
                        <a href="{{route('front.index')}}">{{ $langg->lang17 }}</a>
                    </li>
                    <li>
                        <a href="javascript:;">{{ $langg->lang58 }}</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- Breadcrumb Area End -->

<!-- SubCategori Area Start -->
<section class="sub-categori">
    <div class="container">
        <table id="product_table" class="product_table table" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th width='10%' class='th-img'></th>
                <th>Manufacturer</th>
                <th>SKU</th>
                <th>Name</th>
                <th>Price</th>
                <th style="text-align:center;" class='th-action'>Action</th>
            </tr>
            </thead>
            <tbody>
            
            @foreach($products as $key=>$prod)
                <tr>
                    <td class='td-img'>
                        <img src="{{ $prod["photo"] ? asset('assets/images/products/'.$prod["photo"]):asset('assets/images/noimage.png') }}" alt="">
                    </td>
                    <td>
                        <a href="{{'/collections/'.$prod["category_slug"]}}">{{ $prod["category_name"] }}</a>
                    </td>
                    <td>
                        {{ $prod["sku"] }}
                    </td>
                    <td class='td-group'>
                        <a href="{{'/collections/'.$prod["category_slug"].'/products/'.$prod["slug"]}}">{{ $prod["name"] }}</a>
                    </td>
                    <td class='td-price'>
                        ${{ $prod["price"] }}
                    </td >  
                    <td style="text-align:center;" class='td-action'>
                        <div class="dropdown">
                            <a class="btn-floating btn-lg black" type="button" id="dropdownMenu3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v"></i>
                            </a>
                            <div class="dropdown-menu dropdown-primary">
                                @if(Auth::guard('web')->check())
                                    <span class="dropdown-item add-to-wish" data-href="{{ route('user-wishlist-add', $prod['id']) }}"><i class="icofont-heart-alt"></i>&nbsp;&nbsp;Add to Wish</span>
                                @else
                                    <span class="dropdown-item" data-toggle="modal" id="wish-btn" data-target="#comment-log-reg"><i class="icofont-heart-alt"></i>&nbsp;&nbsp;Add to Wish</span>
                                @endif
                                <span class="dropdown-item quick-view" data-href="{{ route('product.iquick',['db' => $prod['category_slug'], 'id' => $prod['id']]) }}" data-toggle="modal" data-target="#quickview"><i class="icofont-eye"></i>&nbsp;&nbsp;Quick View</span>
                                <span class="dropdown-item add-to-cart add-to-cart-btn" data-href="{{ route('product.cart.add',['db' => $prod['category_slug'], 'id' => $prod['id']]) }}"><i class="icofont-cart"></i>&nbsp;&nbsp;{{ $langg->lang56 }}</span>
                                <span class="dropdown-item add-to-cart-quick" style="width: 100%;" data-href="{{ route('product.cart.quickadd',['db' => $prod['category_slug'], 'id' => $prod['id']]) }}"><i class="icofont-dollar"></i>&nbsp;&nbsp;{{ $langg->lang251 }}</span>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</section>
<!-- SubCategori Area End -->
@endsection
<style>
    .cart span i {
        margin-top: 15px;
    }

</style>
@section('scripts')
<script type="text/javascript">
    var search_table = $('#product_table').DataTable({
        paging: true,
        ordering: false,
        info: false,
        searching: false,
        lengthMenu: [[20, 100, 150, 200, -1], [20, 100, 150, 200, "All"]],
    });
</script>

@endsection
