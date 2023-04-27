@extends('layouts.front')

@section('styles')
<style>
    
</style>
@endsection
@section('content')
    <section class="sub-categori">
        <div class="container">
            <div class="breadcrumb-area">
                <div class="section-top">
                    <div>
                        <ul class="pages parts-by-model-title">
                            <li>
                                <a href="{{ route('front.index') }}">
                                    {{ $langg->lang17 }}
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('front.collection') }}">
                                    Category
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    {{ $category->name }}
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            
        </div>
        <div class="container">
            <div class="row">
                @if (count($products) > 0)
                    @foreach ($products as $key => $prod)
                    <div class="col-lg-3 col-md-6 col-12 margin-custome-0">
                        <a href="{{ route('front.product', ['category_slug' => $category->slug, 'product_slug' => $prod->slug]) }}" class="prod-item item">            
                            <div class="prod-init">
                                <div class="prod-top">
                                    <h2 class="prod-name"  style="color: {{ $colorsetting_style2 && $colorsetting_style2->title_color? $colorsetting_style2->title_color: '#333333' }}">
                                    {{ $prod->showName() }}
                                </h2>
                                </div> 
                                
                                <p class="prod-details" style="color: {{ $colorsetting_style2 && $colorsetting_style2->detail_color? $colorsetting_style2->detail_color: '#333333' }}">
                                    @php
                                        $str=$prod->showDetails();					
                                        if (strlen($str) > 60)
                                        {
                                            $str2 = substr($str, 0, 57);
                                            $str2 = $str2.'...';
                                        }
                                        else
                                        {
                                            $str2 = $str;
                                        }						
                                    @endphp
                                    <?php 
                                        echo $str2;
                                    ?>
                                </p>
                                
                                @if ($prod->showParent() && $prod->showParent() != '<br>')
                                    <p class="prod-details" style="color: {{ $colorsetting_style2 && $colorsetting_style2->sub_detail_color? $colorsetting_style2->sub_detail_color : '#333333' }}">	
                                        <small>Parents: <?php echo $prod->showParent();  ?></small>
                                    </p>
                                @endif
                    
                                <p class="prod-price" style="color: {{ $colorsetting_style2 && $colorsetting_style2->price_color? $colorsetting_style2->price_color: '#333333' }}">
                                    {{ $prod->showPrice() }} 
                                    <del><small>{{ $prod->showPreviousPrice() }}</small></del>
                                </p>
                    
                                
                            </div>
                    
                            <div class="prod-effect item">
                                <div class="extra-list">
                                    <ul>
                                        <li>
                                            @if(Auth::guard('web')->check())
                                            <span class="add-to-wish" data-href="{{ route('user-wishlist-add',$prod->id) }}" data-toggle="tooltip" data-placement="right" title="{{ $langg->lang54 }}" data-placement="right" style="color:{{ $colorsetting_style2 && $colorsetting_style2->buttons_color? $colorsetting_style2->buttons_color: 'green' }};"><i class="icofont-heart-alt"></i>
                                            </span>
                                            @else
                    
                                            <span rel-toggle="tooltip" title="{{ $langg->lang54 }}" data-toggle="modal" id="wish-btn" data-target="#comment-log-reg" data-placement="right" style="color:{{ $colorsetting_style2 && $colorsetting_style2->buttons_color? $colorsetting_style2->buttons_color: 'green' }};">
                                                <i class="icofont-heart-alt"></i>
                                            </span>
                    
                                            @endif
                                        </li>
                                        <li>
                                        <span class="quick-view" rel-toggle="tooltip" title="{{ $langg->lang55 }}" href="javascript:;" data-href="{{ route('product.quick',$prod->id) }}" data-toggle="modal" data-target="#quickview" data-placement="right" style="color:{{ $colorsetting_style2 && $colorsetting_style2->buttons_color? $colorsetting_style2->buttons_color: 'green' }};"> <i class="icofont-eye"></i>
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
                                            <div class="full-stars" style="width:{{App\Models\Rating::ratings($prod->id)}}%"></div>
                                        </div>
                                    </div>
                                    <h5 class="name">
                                    {{ $prod->setCurrency() }} <del><small>{{ $prod->showPreviousPrice() }}</small></del>	
                                    {{ $prod->showName() }}
                                    @if ($prod->showParent() && $prod->showParent() != '<br>')
                                        <p class="prod-details" style="color: {{ $colorsetting_style2 && $colorsetting_style2->sub_detail_color? $colorsetting_style2->sub_detail_color : '#333333' }}">	
                                            <small>Parents: <?php echo $prod->showParent();  ?></small>
                                        </p>
                                    @endif
                                    </h5>
                                    <div class="cart-area">
                                    @if($prod->product_type == "affiliate")
                                        <span class="add-to-cart-btn affilate-btn"
                                            data-href="{{ route('affiliate.product', $prod->slug) }}"><i class="icofont-cart"></i>
                                            {{ $langg->lang251 }}
                                        </span>
                                    @else
                                        @if($prod->emptyStock())
                                        <span class="add-to-cart-btn cart-out-of-stock">
                                            <i class="icofont-close-circled"></i> {{ $langg->lang78 }}
                                        </span>
                                        @else
                                        <span class="add-to-cart add-to-cart-btn" data-href="{{ route('product.cart.add', ['db' => 'products', 'id' => $prod->id]) }}"  style="background-color:{{ $colorsetting_style2 && $colorsetting_style2->buttons_color? $colorsetting_style2->buttons_color: 'green' }};">
                                            <i class="icofont-cart"></i> {{ $langg->lang56 }}
                                        </span>
                                        <span class="add-to-cart-quick add-to-cart-btn"
                                            data-href="{{ route('product.cart.quickadd', ['db' => 'products', 'id' => $prod->id]) }}" style="background-color:{{ $colorsetting_style2 && $colorsetting_style2->buttons_color? $colorsetting_style2->buttons_color: 'green' }};">
                                            <i class="icofont-cart"></i> {{ $langg->lang251 }}
                                        </span>
                                        @endif
                                    @endif
                                    </div>
                            </div>
                            <img class="prod-image" style="max-width:125px; max-height: 150px;"  src="{{ $prod->thumbnail ? asset('assets/images/thumbnails/'.$prod->thumbnail):asset('assets/images/products/'.$gs->prod_image) }}" alt="">
                        </a>
                    </div>
                    @endforeach
                @else
                <div class="col-lg-12">
                    <div class="page-center">
                        <h4 class="text-center">{{ $langg->lang60 }}</h4>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </section>
@endsection


@section('scripts')
<script>
    
</script>
@endsection