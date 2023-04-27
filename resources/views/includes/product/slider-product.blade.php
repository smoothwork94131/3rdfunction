<a href="{{ route('front.product', ['category_slug' => $category_slug, 'product_slug' => $prod->slug]) }}" class="prod-item item">
    <div class="prod-init">
        <div class="prod-top">
            <h2 class="prod-name"
                style="color: {{ $colorsetting_style2 && $colorsetting_style2->title_color ? $colorsetting_style2->title_color : '#333333' }}">
                {{ $prod->showName() }}
            </h2>
        </div>

        <p class="prod-details"
            style="color: {{ $colorsetting_style2 && $colorsetting_style2->detail_color ? $colorsetting_style2->detail_color : '#333333' }}">
            @php
                $str = $prod->showDetails();
                if (strlen($str) > 60) {
                    $str2 = substr($str, 0, 57);
                    $str2 = $str2 . '...';
                } else {
                    $str2 = $str;
                }
            @endphp
            <?php
            echo $str2;
            ?>
        </p>

        @if ($prod->showParent() && $prod->showParent() != '<br>')
            <p class="prod-details"
                style="color: {{ $colorsetting_style2 && $colorsetting_style2->sub_detail_color ? $colorsetting_style2->sub_detail_color : '#333333' }}">
                <small>Parents:
                    <?php echo $prod->showParent(); ?>
                </small>
            </p>
        @endif

        @if ($prod->showEffects())
            @php
                $segments = explode(', ', $prod->showEffects());
                $arrayLength = count($segments);
            @endphp
            <p style="display:flex; align-items: center; ">
                <?php
				$i = 0;
				while ($i < $arrayLength)
				{
					if(!str_contains($segments[$i], 'pain')) {
				?>
                <img src="{{ asset('assets/images/effects/' . $segments[$i] . '.png') }}"
                    alt="Users report feeling {{ $segments[$i] }}."
                    style="margin-right: 3px; margin-left: 3px; font-size: 8px; line-height:10px; object-fit: contain; width: unset; height: 30px;">
                <?php 
					} else { 
					?>
                <img src="{{ asset('assets/images/effects/pain-relief.png') }}" alt="Users report feeling Pain."
                    style="margin-right: 3px; margin-left: 3px; font-size: 8px; line-height:10px; object-fit: contain; width: unset; height: 30px;">
                <?php
					}
					?>
                <?php
						$i++; 
				}
				?>
            </p>
        @endif

        <p class="prod-price"
            style="color: {{ $colorsetting_style2 && $colorsetting_style2->price_color ? $colorsetting_style2->price_color : '#333333' }}">
            {{ $prod->showPrice() }}
            <del><small>{{ $prod->showPreviousPrice() }}</small></del>
        </p>
    </div>

    <div class="prod-effect item">
        <div class="extra-list">
            <ul>
                <li>
                    @if (Auth::guard('web')->check())
                        <span class="add-to-wish" data-href="{{ route('user-wishlist-add', $prod->id) }}"
                            data-toggle="tooltip" data-placement="right" title="{{ $langg->lang54 }}"
                            data-placement="right"
                            style="color:{{ $colorsetting_style2 && $colorsetting_style2->buttons_color ? $colorsetting_style2->buttons_color : 'green' }};"><i
                                class="icofont-heart-alt"></i>
                        </span>
                    @else
                        <span rel-toggle="tooltip" title="{{ $langg->lang54 }}" data-toggle="modal" id="wish-btn"
                            data-target="#comment-log-reg" data-placement="right"
                            style="color:{{ $colorsetting_style2 && $colorsetting_style2->buttons_color ? $colorsetting_style2->buttons_color : 'green' }};">
                            <i class="icofont-heart-alt"></i>
                        </span>
                    @endif
                </li>
                <li>
                    <span class="quick-view" rel-toggle="tooltip" title="{{ $langg->lang55 }}" href="javascript:;"
                        data-href="{{ route('product.quick', $prod->id) }}" data-toggle="modal"
                        data-target="#quickview" data-placement="right"
                        style="color:{{ $colorsetting_style2 && $colorsetting_style2->buttons_color ? $colorsetting_style2->buttons_color : 'green' }};">
                        <i class="icofont-eye"></i>
                    </span>
                </li>

            </ul>
        </div>
    </div>

    <div class="info">
        <div class="stars">
            <div class="ratings">
                <div class="empty-stars">

                </div>
                <div class="full-stars" style="width:{{ App\Models\Rating::ratings($prod->id) }}%"></div>
            </div>
        </div>
        <h5 class="name">
            {{ $prod->setCurrency() }} <del><small>{{ $prod->showPreviousPrice() }}</small></del>
            {{ $prod->showName() }}
            @if ($prod->showParent() && $prod->showParent() != '<br>')
                <p class="prod-details"
                    style="color: {{ $colorsetting_style2 && $colorsetting_style2->sub_detail_color ? $colorsetting_style2->sub_detail_color : '#333333' }}">
                    <small>Parents:
                        <?php echo $prod->showParent(); ?>
                    </small>
                </p>
            @endif
        </h5>

        <div class="cart-area">
            @if ($prod->emptyStock())
                <span class="add-to-cart-btn cart-out-of-stock">
                    <i class="icofont-close-circled"></i> {{ $langg->lang78 }}
                </span>
            @else
                <span class="add-to-cart add-to-cart-btn"
                    data-href="{{ route('product.cart.add', ['db' => 'products', 'id' => $prod->id]) }}"
                    style="background-color:{{ $colorsetting_style2 && $colorsetting_style2->buttons_color ? $colorsetting_style2->buttons_color : 'green' }};">
                    <i class="icofont-cart"></i> {{ $langg->lang56 }}
                </span>
                <span class="add-to-cart-quick add-to-cart-btn"
                    data-href="{{ route('product.cart.quickadd', ['db' => 'products', 'id' => $prod->id]) }}"
                    style="background-color:{{ $colorsetting_style2 && $colorsetting_style2->buttons_color ? $colorsetting_style2->buttons_color : 'green' }};">
                    <i class="icofont-cart"></i> {{ $langg->lang251 }}
                </span>
            @endif
        </div>
    </div>

    <img class="prod-image"
        src="{{ $prod->thumbnail ? asset('assets/images/thumbnails/' . $prod->thumbnail) : asset('assets/images/products/' . $gs->prod_image) }}"
        alt="">
</a>
