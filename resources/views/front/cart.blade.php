@extends('layouts.front')
@section('content')

    <!-- Breadcrumb Area Start -->
    <div class="breadcrumb-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <ul class="pages">
                        <li>
                            <a href="{{ route('front.index') }}">
                                {{ $langg->lang17 }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('front.cart') }}">
                                {{ $langg->lang121 }}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb Area End -->

    <!-- Cart Area Start -->
    <section class="cartpage">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="left-area">
                        <div class="cart-table">
                            <table class="table">
                                @include('includes.form-success')
                                <thead>
                                    <tr>
                                        <th>{{ $langg->lang122 }}</th>
                                        <th class="d-desktop">{{ $langg->lang125 }}</th>
                                        <th class="d-mobile">Price</th>
                                        <th class="d-desktop">{{ $langg->lang126 }}</th>
                                        <th class="d-desktop"><i class="icofont-close-squared-alt"></i></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (Session::has('cart'))
                                        @foreach ($products as $key => $product)
                                            <tr
                                                class="cremove{{ ($product['db'] ?? 'products') . $product['item']->id . $product['size'] . $product['color'] . str_replace(str_split(' ,'), '', $product['values']) }}">
                                                <td class="product-img">
                                                    @if($product['db'] == 'products')
                                                    <div class="item">
                                                        <img src="{{ $product['item']->photo ? asset('assets/images/products/' . $product['item']->photo) : asset('assets/images/noimage.png') }}" alt="">
                                                        <p class="name">
                                                            <a href="{{ route('front.product', $product['item']->slug) }}">
                                                                {{ mb_strlen($product['item']->name, 'utf-8') > 35 ? mb_substr($product['item']->name, 0, 35, 'utf-8') . '...' : $product['item']->name }}
                                                            </a>
                                                        </p>
                                                    </div>
                                                    @else
                                                    <div class="item">
                                                        <img src="{{ $product['item']->photo ? asset('assets/images/products_home/' . $product['item']->photo) : asset('assets/images/noimage.png') }}" alt="">
                                                        <p class="name">
                                                            <a href="{{ route('front.homeproduct', ['category' => $product['category'], 'series' => $product['db'], 'model' => $product['item']->subcategory_id, 'section' => $product['section'], 'group' => $product['item']->category_id, 'prod_name' => $product['item']->name]) }}">
                                                                {{ mb_strlen($product['item']->name, 'utf-8') > 35 ? mb_substr($product['item']->name, 0, 35, 'utf-8') . '...' : $product['item']->name }}
                                                            </a>
                                                        </p>
                                                    </div>
                                                    @endif
                                                </td>

                                                <td class="unit-price quantity">
                                                    <p class="product-unit-price">
                                                        {{ App\Models\Product::convertPrice($product['item']->price) }}
                                                    </p>
                                                    @if ($product['item']->type == 'Physical')
                                                        <div class="qty">
                                                            <ul>
                                                                <input type="hidden" class="db"
                                                                    value="{{ $product['db'] ?? 'products' }}">
                                                                <input type="hidden" class="prodid"
                                                                    value="{{ $product['item']->id }}">
                                                                <input type="hidden" class="itemid"
                                                                    value="{{ ($product['db'] ?? 'products') . $product['item']->id . $product['size'] . $product['color'] . str_replace(str_split(' ,'), '', $product['values']) }}">
                                                                <input type="hidden" class="size_qty"
                                                                    value="{{ $product['size_qty'] }}">
                                                                <input type="hidden" class="size_price"
                                                                    value="{{ $product['item']->price }}">
                                                                <li>
                                                                    <span class="qtminus1 reducing">
                                                                        <i class="icofont-minus"></i>
                                                                    </span>
                                                                </li>
                                                                <li>
                                                                    <span class="qttotal1"
                                                                        id="qty{{ ($product['db'] ?? 'products') . $product['item']->id . $product['size'] . $product['color'] . str_replace(str_split(' ,'), '', $product['values']) }}">{{ $product['qty'] }}</span>
                                                                </li>
                                                                <li>
                                                                    <span class="qtplus1 adding">
                                                                        <i class="icofont-plus"></i>
                                                                    </span>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    @endif
                                                    <br>
                                                    <br>
                                                    <div class="d-mobile">
                                                        <p
                                                            id="prc{{ ($product['db'] ?? 'products') . $product['item']->id . $product['size'] . $product['color'] . str_replace(str_split(' ,'), '', $product['values']) }}">
                                                            {{ App\Models\Product::convertPrice($product['price']) }}
                                                        </p>

                                                        <span class="removecart cart-remove"
                                                            data-class="cremove{{ ($product['db'] ?? 'products') . $product['item']->id . $product['size'] . $product['color'] . str_replace(str_split(' ,'), '', $product['values']) }}"
                                                            data-href="{{ route('product.cart.remove', ($product['db'] ?? 'products') . $product['item']->id . $product['size'] . $product['color'] . str_replace(str_split(' ,'), '', $product['values'])) }}"><i
                                                                class="icofont-ui-delete"></i> </span>
                                                    </div>

                                                </td>

                                                @if ($product['size_qty'])
                                                    <input type="hidden"
                                                        id="stock{{ ($product['db'] ?? 'products') . $product['item']->id . $product['size'] . $product['color'] . str_replace(str_split(' ,'), '', $product['values']) }}"
                                                        value="{{ $product['size_qty'] }}">
                                                @elseif($product['item']->type != 'Physical')
                                                    <input type="hidden"
                                                        id="stock{{ ($product['db'] ?? 'products') . $product['item']->id . $product['size'] . $product['color'] . str_replace(str_split(' ,'), '', $product['values']) }}"
                                                        value="1">
                                                @else
                                                    <input type="hidden"
                                                        id="stock{{ ($product['db'] ?? 'products') . $product['item']->id . $product['size'] . $product['color'] . str_replace(str_split(' ,'), '', $product['values']) }}"
                                                        value="{{ $product['stock'] }}">
                                                @endif



                                                <td class="total-price d-desktop">
                                                    <p
                                                        id="prc{{ ($product['db'] ?? 'products') . $product['item']->id . $product['size'] . $product['color'] . str_replace(str_split(' ,'), '', $product['values']) }}">
                                                        {{ App\Models\Product::convertPrice($product['price']) }}
                                                    </p>
                                                </td>
                                                <td class="d-desktop">
                                                    <span class="removecart cart-remove"
                                                        data-class="cremove{{ ($product['db'] ?? 'products') . $product['item']->id . $product['size'] . $product['color'] . str_replace(str_split(' ,'), '', $product['values']) }}"
                                                        data-href="{{ route('product.cart.remove', ($product['db'] ?? 'products') . $product['item']->id . $product['size'] . $product['color'] . str_replace(str_split(' ,'), '', $product['values'])) }}"><i
                                                            class="icofont-ui-delete"></i> </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @if (Session::has('cart'))
                    <div class="col-lg-4">
                        <div class="right-area">
                            <div class="order-box">
                                <h4 class="title">{{ $langg->lang127 }}</h4>
                                <ul class="order-list">
                                    <li>
                                        <p>
                                            {{ $langg->lang128 }}
                                        </p>
                                        <P>
                                            <b class="cart-total">{{ Session::has('cart') ? App\Models\Product::convertPrice($totalPrice) : '0.00' }}</b>
                                        </P>
                                    </li>
                                    <li>
                                        <p>
                                            {{ $langg->lang129 }}
                                        </p>
                                        <P>
                                            <b class="discount">{{ App\Models\Product::convertPrice(0) }}</b>
                                            <input type="hidden" id="d-val" value="{{ App\Models\Product::convertPrice(0) }}">
                                        </P>
                                    </li>
                                    <li>
                                        <p>
                                            {{ $langg->lang130 }}
                                        </p>
                                        <P>
                                            <b>{{ $tx }}%</b>
                                        </P>
                                    </li>
                                </ul>
                                <div class="total-price">
                                    <p>
                                        {{ $langg->lang131 }}
                                    </p>
                                    <p>
                                        <span
                                            class="main-total">{{ Session::has('cart') ? App\Models\Product::convertPrice($mainTotal) : '0.00' }}</span>
                                    </p>
                                </div>
                                <div class="cupon-box">
                                    <div id="coupon-link">
                                        {{ $langg->lang132 }}
                                    </div>
                                    <form id="coupon-form" class="coupon">
                                        <input type="text" placeholder="{{ $langg->lang133 }}" id="code"
                                            required="" autocomplete="off">
                                        <input type="hidden" class="coupon-total" id="grandtotal"
                                            value="{{ Session::has('cart') ? App\Models\Product::convertPrice($mainTotal) : '0.00' }}">
                                        <button type="submit">{{ $langg->lang134 }}</button>
                                    </form>
                                </div>
                                <a href="{{ route('front.checkout') }}" class="order-btn">
                                    {{ $langg->lang135 }}
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
    <!-- Cart Area End -->
@endsection
