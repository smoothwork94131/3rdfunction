@if (Session::has('cart'))
    <div class="dropdownmenu-wrapper">
        <div class="dropdown-cart-header">
            <span class="item-no">
                <span class="cart-quantity">
                    {{ Session::has('cart') ? count(Session::get('cart')->items) : '0' }}
                </span> {{ $langg->lang4 }}
            </span>
        </div>
        <ul class="dropdown-cart-products">
            @foreach (Session::get('cart')->items as $product)
                <li class="product cremove{{ ($product['db'] ?? 'products') . $product['item']->id . $product['size'] . $product['color'] . str_replace(str_split(' ,'), '', $product['values']) }}">
                    <a href="{{ $product['db'] == 'products' ? route('front.homeproduct', $product['item']->slug) : route('front.product', ['category' => $product['category'], 'series' => $product['db'], 'model' => $product['item']->subcategory_id, 'section' => $product['section'], 'group' => $product['item']->category_id, 'prod_name' => $product['item']->name]) }}">
                        <div class="product-details">
                            <div class='img'>
                                @if($product['db'] == 'products')
                                <img src="{{ $product['item']->photo ? (filter_var($product['item']->photo, FILTER_VALIDATE_URL) ? $product['item']->photo : asset('assets/images/products_home/' . $product['item']->photo)) : asset('assets/images/noimage.png') }}" alt="product">
                                @else
                                <img src="{{ $product['item']->photo ? (filter_var($product['item']->photo, FILTER_VALIDATE_URL) ? $product['item']->photo : asset('assets/images/products/' . $product['item']->photo)) : asset('assets/images/noimage.png') }}" alt="product">
                                @endif
                            </div>
                            <div class="content">
                                <h6 class="product-title">
                                    {{ mb_strlen($product['item']->name, 'utf-8') > 45 ? mb_substr($product['item']->name, 0, 45, 'utf-8') . '...' : $product['item']->name }}
                                </h6>

                                <span class="cart-product-info">
                                    <span class="cart-product-qty"
                                        id="cqt{{ $product['item']->id . $product['size'] . $product['color'] . str_replace(str_split(' ,'), '', $product['values']) }}">{{ $product['qty'] }}</span><span>{{ $product['item']->measure }}</span>
                                    x <span
                                        id="prct{{ $product['item']->id . $product['size'] . $product['color'] . str_replace(str_split(' ,'), '', $product['values']) }}">{{ App\Models\Product::convertPrice($product['item']->price) }}</span>
                                </span>
                            </div>

                            <div class="cart-remove"
                                data-class="cremove{{ ($product['db'] ?? 'products') . $product['item']->id . $product['size'] . $product['color'] . str_replace(str_split(' ,'), '', $product['values']) }}"
                                data-href="{{ route('product.cart.remove', ($product['db'] ?? 'products') . $product['item']->id . $product['size'] . $product['color'] . str_replace(str_split(' ,'), '', $product['values'])) }}"
                                title="Remove Product">
                                <i class="icofont-close"></i>
                            </div>
                        </div>
                    </a>
                </li><!-- End .product -->
            @endforeach
        </ul><!-- End .cart-product -->

        <div class="dropdown-cart-total">
            <span>{{ $langg->lang6 }}</span>
            <span class="cart-total-price">
                <span
                    class="cart-total">{{ Session::has('cart') ? App\Models\Product::convertPrice(Session::get('cart')->totalPrice) : '0.00' }}
                </span>
            </span>
        </div><!-- End .dropdown-cart-total -->

        <div class="dropdown-cart-action">
            <a href="{{ route('front.cart') }}" class="mybtn1">{{ $langg->lang5 }}</a>
        </div><!-- End .dropdown-cart-total -->
    </div>
@else
    <p class="mt-1 pl-3 text-left">{{ $langg->lang8 }}</p>
@endif
