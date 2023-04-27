@extends('layouts.front')

@section('styles')
    <link rel="stylesheet" href="{{asset('assets/front/css/custom.css')}}">
@endsection

@section('content')

    @if (\Session::has('success'))
    <div class="success alert-success">
        <ul>
            <li>{!! \Session::get('success') !!}</li>
        </ul>
    </div>
    @endif

    @if (\Session::has('error'))
    <div class="danger alert-danger">
        <ul>
            <li>{!! \Session::get('error') !!}</li>
        </ul>
    </div>
    @endif

    <section class="hero-area" style="background-color: white">
        <div class="container custom-container">
            <div class="row">
                <div class="col-12 remove-padding s-top-block">
                    <div>
                        3RD FUNCTION VALVES FOR EVERY SIZE TRACTOR
                    </div>
                </div>
            </div>
        </div>
    </section>

    @foreach($products as $product)
    <section class="trending slider-buttom-category grid-display">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 remove-padding">
                    <div class="section-top">
                        <h2 class="section-title">
                            <img src="{{asset('assets/images/logo60px.png')}}" width="50" height="50"> 
                            <span class="main">{{ $product["category_name"] }}</span> 
                            <span class="title-underline"></span>
                        </h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 remove-padding">
                    <div class="trending-item-slider">
                        @foreach($product['products'] as $prod)
                        @include('includes.product.slider-product', ['category_slug' => $product["category_slug"], 'prod' => $prod])
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endforeach

    <section class="hero-area" style="background-color: white">
        <div class="container custom-container">
            <div class="row">
                <div class="col-12 remove-padding">
                    <div class='promotions'>
                        <div class='section-name'>
                            Promotions
                        </div>
                        @if($domain_name == 'mahindra')
                        <a href="https://www.tractorbrothers.com/new-models/mahindra-264"><img src="{{asset('assets/images/promotions/fb199527-5e08-422f-b7a9-1502503048c6.jpg')}}" /></a>
                        <a href="https://www.tractorbrothers.com/new-models/kioti-263"><img src="{{asset('assets/images/promotions/8f7fba18-131a-4367-907f-c8c404488e79.jpg')}}" /></a>
                        @elseif($domain_name == 'kioti')
                        <a href="https://www.tractorbrothers.com/new-models/kioti-263"><img src="{{asset('assets/images/promotions/8f7fba18-131a-4367-907f-c8c404488e79.jpg')}}" /></a>
                        <a href="https://www.tractorbrothers.com/new-models/mahindra-264"><img src="{{asset('assets/images/promotions/fb199527-5e08-422f-b7a9-1502503048c6.jpg')}}" /></a>
                        @else
        
                        @endif
                        <a href="https://www.tractorbrothers.com/new-models/woods-265"><img src="{{asset('assets/images/promotions/5f721233-577e-4e0b-9983-c1b8ad89e8d7.jpg')}}"/></a>
                        <a href="https://www.tractorbrothers.com/new-models/husqvarna-157"><img src="{{asset('assets/images/promotions/8c1b7eaa-f63c-4f1e-9a98-046264d4418e.jpg')}}" /></a>
                    </div>
                </div>    
            </div>
        </div>
    </section>
    
    @if($ps->small_banner == 1)
        <!-- Banner Area One Start -->
        <section class="banner-section">
            <div class="container">
                @foreach($top_small_banners->chunk(2) as $chunk)
                    <div class="row">
                        @foreach($chunk as $img)
                            <div class="col-lg-6 remove-padding">
                                <div class="left">
                                    <a class="banner-effect" href="{{ $img->link }}" target="_blank">
                                        <img src="{{asset('assets/images/banners/'.$img->photo)}}" alt="">
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </section>
        <!-- Banner Area One Start -->
    @endif

@endsection

@section('scripts')
    <script>
        function listView(){
            $('.grid-display').removeClass('d-none');
            $('.list-display').removeClass('d-none');
            $('button.grid').removeClass('btn-success');
            $('button.list').removeClass('btn-success');
            $('button.list').addClass('btn-success');
            $('.grid-display').addClass('d-none');
        }

        function gridView(){
            $('.grid-display').removeClass('d-none');
            $('.list-display').removeClass('d-none');
            $('button.grid').removeClass('btn-success');
            $('button.list').removeClass('btn-success');
            $('button.grid').addClass('btn-success');
            $('.list-display').addClass('d-none');
        }

    </script>
@endsection