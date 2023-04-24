<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if (isset($page->meta_tag) && isset($page->meta_description))
        <meta name="keywords" content="{{ $page->meta_tag }}">
        <meta name="description" content="{{ $page->meta_description }}">
        <title>{{ $gs->title }}</title>
    @elseif(isset($blog->meta_tag) && isset($blog->meta_description))
        <meta name="keywords" content="{{ $blog->meta_tag }}">
        <meta name="description" content="{{ $blog->meta_description }}">
        <title>{{ $gs->title }}</title>
    @elseif(isset($productt))
        <meta property="og:title" content="{{ $productt->name }}" />
        <meta property="og:description"
            content="{{ $productt->meta_description != null ? $productt->meta_description : strip_tags($productt->description) }}" />
        <meta property="og:image" content="{{ asset('assets/images/thumbnails/' . $productt->thumbnail) }}" />
        <meta name="author" content="Davehansen.com">
        <title>{{ substr($productt->name, 0, 11) . '-' }}{{ $gs->title }}</title>
    @else
        <meta name="keywords" content="{{ $seo->meta_keys }}">
        <meta name="author" content="Davehansen.com">
        <title>{{ $gs->title }}</title>
    @endif

    <meta http-equiv="cache-control" content="max-age=0" />
    <meta http-equiv="cache-control" content="no-cache" />
    <meta http-equiv="expires" content="0" />
    <meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
    <meta http-equiv="pragma" content="no-cache" />

    <!-- favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/images/' . $gs->favicon) }}" />

    @if ($langg->rtl == '1')
        <!-- stylesheet -->
        <link rel="stylesheet" href="{{ asset('assets/front/css/rtl/all.css') }}">

        <!--Updated CSS-->
        <link rel="stylesheet"
            href="{{ asset('assets/front/css/rtl/styles.php?color=' . str_replace('#', '', $gs->colors) . '&amp;' . 'header_color=' . str_replace('#', '', $gs->header_color) . '&amp;' . 'footer_color=' . str_replace('#', '', $gs->footer_color) . '&amp;' . 'copyright_color=' . str_replace('#', '', $gs->copyright_color) . '&amp;' . 'category_color=' . str_replace('#', '', $gs->category_color) . '&amp;' . 'menu_color=' . str_replace('#', '', $gs->menu_color) . '&amp;' . 'menu_bg_color=' . str_replace('#', '', $gs->menu_bg_color) . '&amp;' . 'menu_hover_color=' . str_replace('#', '', $gs->menu_hover_color)) }}">
    @else
        <!-- stylesheet -->
        <link rel="stylesheet" href="{{ asset('assets/front/css/all.css') }}">

        <!--Updated CSS-->
        <link rel="stylesheet"
            href="{{ asset('assets/front/css/style.php?color=' . str_replace('#', '', $gs->colors) . '&amp;' . 'header_color=' . str_replace('#', '', $gs->header_color) . '&amp;' . 'footer_color=' . str_replace('#', '', $gs->footer_color) . '&amp;' . 'copyright_color=' . str_replace('#', '', $gs->copyright_color) . '&amp;' . 'category_color=' . str_replace('#', '', $gs->category_color) . '&amp;' . 'menu_color=' . str_replace('#', '', $gs->menu_color) . '&amp;' . 'menu_bg_color=' . str_replace('#', '', $gs->menu_bg_color) . '&amp;' . 'menu_hover_color=' . str_replace('#', '', $gs->menu_hover_color)) }}">
    @endif

    <link rel="stylesheet" href="{{ asset('assets/front/css/custom.css') }}">

    @yield('styles')

</head>

<body>
    @if ($gs->is_loader == 1)
        <div class="preloader" id="preloader" style="background: #FFF;">
        </div>
    @endif

    @if ($gs->is_popup == 1)
        @if (isset($visited))
            <div style="display:none">
                <img src="{{ asset('assets/images/' . $gs->popup_background) }}">
            </div>
            <!--  Starting of subscribe-pre-loader Area   -->
            <div class="subscribe-preloader-wrap" id="subscriptionForm" style="display: none;">
                <div class="subscribePreloader__thumb"
                    style="background-image: url({{ asset('assets/images/' . $gs->popup_background) }});">
                    <span class="preload-close"><i class="fas fa-times"></i></span>
                    <div class="subscribePreloader__text text-center">
                        <h1>{{ $gs->popup_title }}</h1>
                        <p>{{ $gs->popup_text }}</p>
                        <form action="{{ route('front.subscribe') }}" id="subscribeform" method="POST">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <input type="email" name="email" placeholder="{{ $langg->lang741 }}" required="">
                                <button id="sub-btn" type="submit">{{ $langg->lang742 }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!--  Ending of subscribe-pre-loader Area   -->
        @endif
    @endif

    <section class="top-header row top-menu" style="margin: 0px;">
        <nav class="navbar navbar-expand-md navbar-dark col-md-9 col-sm-9 col-lm-9" style='padding: 0px;'>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link root-link" href="/" target="">
                            Home
                        </a>
                    </li>
                    <li class="dropdown nav-item ">
                        <a class="nav-link root-link" target="" data-toggle="dropdown">
                            New Models<span style='padding-left: 5px;'><i
                                    class="fa fa-angle-down arrow-down"></i></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class='nav-item'><a href="https://www.tractorbrothers.com/new-models">New Models</a>
                            </li>
                            <li class='nav-item'><a href="https://www.tractorbrothers.com/factory-promotions">Factory
                                    Promotions</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link root-link"
                            href="https://www.tractorbrothers.com/search/inventory/availability/In%20Stock"
                            target="">
                            Inventory
                        </a>
                    </li>

                    <li class="dropdown nav-item dropbtn">
                        <a class="nav-link root-link" data-toggle="dropdown" style='cursor:pointer'>
                            Parts Finder<i class="fa fa-angle-down ml-2 mt-1"></i>
                        </a>
                        <ul class='dropdown-content'>
                            <div class="categories_menu" style='width: 600px;!important'>
                                <div class="categories_title">
                                    <h2 class="categori_toggle"> Categories </h2>
                                </div>
                                <div class="categories_menu_inner products">
                                    @foreach ($eccategories as $product)
                                        <div class="categories_menu">
                                            <div class="categories_title" data-type="category"
                                                data-category="{{ $product->name }}"
                                                data-url="{{ route('front.groups') }}"
                                                data-domain="{{ $domain_name }}" data-status="0"
                                                data-token="{{ csrf_token() }}">
                                                <h2 class="categori_toggle"> {{ $product->name }}
                                                    <i class="fa fa-angle-down arrow-down"
                                                        style='margin-left: 5px'></i>
                                                </h2>
                                            </div>
                                            <div class="categories_menu_inner models"
                                                style="max-height: 300px; overflow-y: auto;">
                                                loading...
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </ul>
                    </li>
                    <li class="dropdown nav-item">
                        <a class="nav-link root-link" target="" data-toggle="dropdown">
                            Services<span style='padding-left: 5px;'><i
                                    class="fa fa-angle-down arrow-down"></i></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class='nav-item'><a href="https://www.tractorbrothers.com/services">Services</a></li>
                            <li class='nav-item'><a href="https://www.tractorbrothers.com/servicereq">Service Quote
                                    Request</a></li>
                        </ul>
                    </li>
                    <li class="dropdown nav-item">
                        <a class="nav-link root-link" target="" data-toggle="dropdown">
                            Company Info<span style='padding-left: 5px;'><i
                                    class="fa fa-angle-down arrow-down"></i></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class='nav-item'><a href="https://www.tractorbrothers.com/aboutus">Company Info</a>
                            </li>
                            <li class='nav-item'><a href="https://www.tractorbrothers.com/contactus">Contact Us</a>
                            </li>
                            <li class='nav-item'><a href="https://www.tractorbrothers.com/locations">Maps&Hours</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
        <div class="header-tool header-base-tool ml-auto col-md-3 col-sm-3 col-lm-3">
            <div class="map_hour hidden-xs">
                <a href="https://www.tractorbrothers.com/locations">Maps &amp; Hours</a>
            </div>
            <div class="cart">
                <a href="/carts" class="cart-icon" title="Cart">
                    <span><i class="fa fa-shopping-cart"></i></span>
                </a>
                <span class="cart-quantity header-cart-count"
                    id="cart-count">{{ Session::has('cart') ? count(Session::get('cart')->items) : '0' }}</span>
                <div class="my-dropdown-menu header-cart-items" id="cart-items">
                    @include('load.cart')
                </div>
            </div>
        </div>
        <div class="header-tool header-min-tool ml-auto col-md-12 col-sm-12 col-lm-12;"
            style='height: 60px; line-height: 60px;'>
            <div class="map_hour hidden-xs" style='text-align: left; '>
                <a href="https://www.tractorbrothers.com/locations">Maps &amp; Hours</a>
            </div>
            <div style='display: flex; justify-content: flex-end; '>
                <div class="cart">
                    <a onclick='showMobileSearchField(this)'>
                        <span><i class="fa fa-search"></i></span>
                    </a>
                </div>
                <div class="cart" style='margin-left: 10px'>
                    <a href="/carts" class="cart-icon" title="Cart">
                        <span><i class="fa fa-shopping-cart"></i></span>
                    </a>
                    <span class="cart-quantity header-cart-count"
                        id="cart-count">{{ Session::has('cart') ? count(Session::get('cart')->items) : '0' }}</span>
                </div>
            </div>
        </div>
    </section>

    <div class="top-gap"></div>
    <!-- Top Header Area End -->
    <!-- Logo Header Area Start -->

    <section class="logo-header">
        <div class="container-fluid">
            <div class="row" style='padding: 0px;'>
                <div class="col-lg-3 col-sm-3 remove-padding logo-div">
                    <div class="logo">
                        <a href="{{ route('front.index') }}">
                            <img src="{{ asset('assets/front/images/logo.png') }}" alt=""
                                class="img-responsive center-block logo-img">
                        </a>
                    </div>
                </div>
                <div class=" col-sm-6 col-lg-7 locations-div">
                    <div class="header-locations">
                        <div>
                            <i class="fa fa-map-marker" aria-hidden="true"><span class="sr-only" role="presentation"
                                    aria-hidden="true" tabindex="-1">Map</span><span class="sr-only"
                                    role="presentation" aria-hidden="true" tabindex="-1">Map</span></i>
                            <ul class="header-links">
                                <li>
                                    <a href="{{ route('front.location', '36478') }}"
                                        title="View Map &amp; Hours for Greensburg">
                                        <span class="city">Greensburg</span>, <span class="region">PA</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="tel:+1(724) 691-0200" title="Call Us">
                                        <span>(724) 691-0200</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div>
                            <i class="fa fa-map-marker" aria-hidden="true"><span class="sr-only" role="presentation"
                                    aria-hidden="true" tabindex="-1">Map</span><span class="sr-only"
                                    role="presentation" aria-hidden="true" tabindex="-1">Map</span></i>
                            <ul class="header-links">
                                <li>
                                    <a href="{{ route('front.location', '37100') }}"
                                        title="View Map &amp; Hours for Butler">
                                        <span class="city">Butler</span>, <span class="region">PA</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="tel:+1(724) 482-6288" title="Call Us">
                                        <span>(724) 482-6288</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div>
                            <i class="fa fa-map-marker" aria-hidden="true"><span class="sr-only" role="presentation"
                                    aria-hidden="true" tabindex="-1">Map</span><span class="sr-only"
                                    role="presentation" aria-hidden="true" tabindex="-1">Map</span></i>
                            <ul class="header-links">
                                <li>
                                    <a href="{{ route('front.location', '37101') }}"
                                        title="View Map &amp; Hours for Stoneboro">
                                        <span class="city">Stoneboro</span>, <span class="region">PA</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="tel:+1(724) 253-2035" title="Call Us">
                                        <span>(724) 253-2035</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-sm-3 socials-div business-info-socialmedia" align="center">
                    <div class="social-media">
                        <a>
                            <button class='btn' style='background: #F05223'
                                onclick="showDesktopSearchField(this)"><i class='fa fa-search'></i></button>
                            <div class='desktop-search-field'>
                                <div class='search-table'>
                                    <div class='sel-drop' onclick='searchSelTableGroup()'>
                                        <div class='name'>{{ $eccategories[0]->name }}</div>
                                        <div class='icon'>
                                            <i class="fa fa-angle-down ml-2 mt-1"></i>
                                        </div>
                                    </div>
                                    <div class='dropdown'>
                                        @foreach ($eccategories as $item)
                                            <div class='item' onclick="selSearchTableItem('{{ $item->name }}')">
                                                {{ $item->name }}</div>
                                        @endforeach
                                    </div>
                                </div>

                                <div style='margin-top: 10px;'>
                                    <div class='search-field'>
                                        <input class='search-input form-control'
                                            onkeyup="totalSearch(event, 'desktop')"
                                            value="{{ $eccategories[0]->name }}" />
                                        <div class='icon'><i class='fa fa-search'
                                                onclick=" event.keyCode = 1200 ; totalSearch(event, 'desktop')"></i>
                                        </div>
                                    </div>
                                    <div class='search-dropdown'>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="social-media">
                        <a href="https://www.facebook.com/TractorBros" target="_blank">
                            <button class='btn' style='background: #3C63A4; border: none;'><i
                                    class='fab fa-facebook'></i></button>
                        </a>
                    </div>
                    <div class="social-media">
                        <a href="https://www.youtube.com/channel/UCPWjtRtVVMzes0AkXk24z7A/videos" target="_blank">
                            <button class='btn' style='background: #E20606'><i
                                    class='fab fa-youtube'></i></button>
                        </a>
                    </div>
                </div>
                <div class='tiny-menu col-md-6 col-sm-6' align='right' style='width: 50%;'>
                    <button
                        style="color: white;
                        border-color: rgba(255, 255, 255, .1);
                        margin-top: 10px;
                        background-color: #F05223;"
                        class="navbar-toggler" type="button" data-toggle="collapse"
                        data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                        <i class='fa fa-list'></i>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <section class="top-header row bottom-menu" style="margin: 0px; border: none; position: relative;">
        <div class='mobile-search-field'>
            <div class='search-table'>
                <div class='sel-drop' onclick='searchSelTableGroup()'>
                    <div class='name'>{{ $eccategories[0]->name }}</div>
                    <div class='icon'>
                        <i class="fa fa-angle-down ml-2 mt-1"></i>
                    </div>
                </div>
                <div class='dropdown'>
                    @foreach ($eccategories as $item)
                        <div class='item' onclick="selSearchTableItem('{{ $item->name }}')">{{ $item->name }}
                        </div>
                    @endforeach
                </div>
            </div>
            <div style='margin-top: 10px;'>
                <div class='search-field'>
                    <input class='search-input form-control' onkeyup="totalSearch(event, 'mobile')" />
                    <div class='icon' onclick="event.keyCode = 1200 ;  totalSearch(event, 'mobile')"><i
                            class='fa fa-search'></i></div>
                </div>
                <div class='search-dropdown'>
                </div>
            </div>

        </div>
        <nav class="navbar navbar-expand-md navbar-light col-md-9 col-sm-9 col-lm-9" style='padding: 0px; '>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link root-link" href="/" target="">
                            Home
                        </a>
                    </li>
                    <li class="dropdown nav-item">
                        <a class="nav-link root-link" target="" data-toggle="collapse"
                            data-target="#collapse_new_model">
                            New Modals<span style='padding-left: 5px;'><i
                                    class="fa fa-angle-down arrow-down"></i></span>
                        </a>
                        <ul class="collapse" id="collapse_new_model" style='position: unset !important'>
                            <li class='nav-item'><a href="https://www.tractorbrothers.com/new-models">New Models</a>
                            </li>
                            <li class='nav-item'><a href="https://www.tractorbrothers.com/factory-promotions">Factory
                                    Promotions</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link root-link"
                            href="https://www.tractorbrothers.com/search/inventory/availability/In%20Stock"
                            target="">
                            Inventory
                        </a>
                    </li>
                    <li class="dropdown nav-item dropbtn">
                        <a class="nav-link root-link" data-toggle="collapse" data-target="#collapse_parts"
                            style='cursor:pointer'>
                            Parts Finder<i class="fa fa-angle-down ml-2 mt-1"></i>
                        </a>
                        <ul class='dropdown-menu' style='position: unset !important'>
                            <div class="categories_menu">
                                <div class="categories_title">
                                    <h2 class="categori_toggle"> Categories </h2>
                                </div>
                                <div class="categories_menu_inner products">
                                    @foreach ($eccategories as $product)
                                        <div class="categories_menu">
                                            <div class="categories_title" data-type="category"
                                                data-category="{{ $product->name }}"
                                                data-url="{{ route('front.groups') }}" data-status="0"
                                                data-token="{{ csrf_token() }}">
                                                <h2 class="categori_toggle"> {{ $product->name }}
                                                    <i class="fa fa-angle-down arrow-down"
                                                        style='margin-left: 5px'></i>
                                                </h2>
                                            </div>
                                            <div class="categories_menu_inner models"
                                                style="max-height: 300px; overflow-y: auto;">
                                                loading...
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </ul>
                    </li>
                    <li class="dropdown nav-item">
                        <a class="nav-link root-link" target="" data-toggle="collapse"
                            data-target="#collapse_services">
                            Services<span style='padding-left: 5px;'><i
                                    class="fa fa-angle-down arrow-down"></i></span>
                        </a>
                        <ul class="collapse" id="collapse_services" style='position: unset !important'>
                            <li class='nav-item'><a href="https://www.tractorbrothers.com/services">Services</a></li>
                            <li class='nav-item'><a href="https://www.tractorbrothers.com/servicereq">Service Quote
                                    Request</a></li>
                        </ul>
                    </li>
                    <li class="dropdown nav-item">
                        <a class="nav-link root-link" data-toggle="collapse" data-target="#collapse_info">
                            Company Info<span style='padding-left: 5px;'><i
                                    class="fa fa-angle-down arrow-down"></i></span>
                        </a>
                        <ul class="collapse" id="collapse_info" style='position: unset !important'>
                            <li class='nav-item'><a href="https://www.tractorbrothers.com/aboutus">Company Info</a>
                            </li>
                            <li class='nav-item'><a href="https://www.tractorbrothers.com/contactus">Contact Us</a>
                            </li>
                            <li class='nav-item'><a href="https://www.tractorbrothers.com/locations">Maps&Hours</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </section>
    <div class='about-header'>
        <div class='content'>
            <div style="width: 10%;"></div>
            <div class="site-info">
                @if ($domain_name == 'mahindra')
                    <span class="site_url">4myMahindra.com</span>
                @elseif($domain_name == 'kioti')
                    <span class="site_url">4myKioti.com</span>
                @endif

                <span class="phone_num"><span id="phone_num_desc">FOR ASSISTANCE CALL:</span> <a
                        href="tel:+1(724) 691-0200">(724) 691-0200</a> </span>
            </div>
            <div class='sign-form'>
                @guest
                    <span><a href="{{ route('user.login') }}"><i class='fas fa-fw fa-2xl fa-user'></i></a></span>
                @else
                    <div class="dropdown">
                        <a class="btn-floating btn-lg black" type="button" id="usr_loc_dropdown" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            <img class='avatar'
                                src="{{ auth()->user()->photo ? asset('assets/images/users/' . auth()->user()->photo) : asset('assets/front/images/User.png') }}" />
                        </a>
                        <div class="dropdown-menu dropdown-primary">
                            <a class='dropdown-item' href="{{ route('user-profile') }}"><i
                                    class='fas fa-fw fa-2xl fa-user'></i>Profile</a>
                            <a class='dropdown-item' href="{{ route('user-logout') }}"><i
                                    class='fas fa-sign-out-alt'></i>Logout</a>
                        </div>
                    </div>
                @endguest
            </div>
        </div>
    </div>
    <!-- Logo Header Area End -->

    @yield('content')
    <!-- Footer Area Start -->
    <footer class="footer" id="footer">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 col-lg-4 footer-item">
                    <div class="footer-widget">
                        <div class="footer-info-area">
                            <!-- <div class="footer-logo">
                            <a href="{{ route('front.index') }}" class="logo-link">
                                <img src="{{ asset('assets/images/' . $gs->footer_logo) }}" alt="">
                            </a>
                        </div> -->
                            <div class="text">
                                <!-- <p style="color: {{ $gs->footer_text_color }}">
                                {!! $gs->footer !!}
                            </p>
                             -->
                                <p class='title'>Tractor Brothers - Greensburg</p>
                                <p>113 Hartman Road<br>Greensburg PA 15601</p>
                                <p><a href='https://www.tractorbrothers.com/locations/36478'>(Map & Hours)</a></p>
                                <p>(724) 691-0200</p>
                                <p><a href="https://www.facebook.com/TractorBros" class='btn btn-primary'>
                                        <i class="fab fa-facebook"></i>
                                    </a>
                                    <a href="https://www.youtube.com/channel/UCPWjtRtVVMzes0AkXk24z7A/videos"
                                        class='btn btn-danger'>
                                        <i class="fab fa-youtube"></i>
                                    </a>
                                </p>
                            </div>
                            <div class="sub_text">
                            </div>
                        </div>
                        <div class="fotter-social-links">
                            <ul>
                                @if (App\Models\Socialsetting::find(1)->f_status == 1)
                                    <li>
                                        <a href="{{ App\Models\Socialsetting::find(1)->facebook }}" class="facebook"
                                            target="_blank">
                                            <i class="fab fa-facebook-f"></i>
                                        </a>
                                    </li>
                                @endif

                                @if (App\Models\Socialsetting::find(1)->g_status == 1)
                                    <li>
                                        <a href="{{ App\Models\Socialsetting::find(1)->gplus }}" class="google-plus"
                                            target="_blank">
                                            <i class="fab fa-google-plus-g"></i>
                                        </a>
                                    </li>
                                @endif

                                @if (App\Models\Socialsetting::find(1)->t_status == 1)
                                    <li>
                                        <a href="{{ App\Models\Socialsetting::find(1)->twitter }}" class="twitter"
                                            target="_blank">
                                            <i class="fab fa-twitter"></i>
                                        </a>
                                    </li>
                                @endif

                                @if (App\Models\Socialsetting::find(1)->l_status == 1)
                                    <li>
                                        <a href="{{ App\Models\Socialsetting::find(1)->linkedin }}" class="linkedin"
                                            target="_blank">
                                            <i class="fab fa-linkedin-in"></i>
                                        </a>
                                    </li>
                                @endif

                                @if (App\Models\Socialsetting::find(1)->d_status == 1)
                                    <li>
                                        <a href="{{ App\Models\Socialsetting::find(1)->dribble }}" class="dribbble"
                                            target="_blank">
                                            <i class="fab fa-dribbble"></i>
                                        </a>
                                    </li>
                                @endif

                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 footer-item">
                    <div class="footer-widget info-link-widget">
                        <!-- <h4 class="title" style="color: {{ $gs->footer_text_color }}">
                        {{ $langg->lang21 }}
                    </h4>
                    <ul class="link-list">
                        <li>
                            <a href="{{ route('front.index') }}" style="color: {{ $gs->footer_text_color }}">
                                <i class="fas fa-angle-double-right" style="color: {{ $gs->footer_text_color }}"></i>{{ $langg->lang22 }}
                            </a>
                        </li>

                        @foreach (DB::table('pages')->where('footer', '=', 1)->get() as $data)
<li>
                                <a href="{{ route('front.page', $data->slug) }}" style="color: {{ $gs->footer_text_color }}">
                                    <i class="fas fa-angle-double-right" style="color: {{ $gs->footer_text_color }}"></i>{{ $data->title }}
                                </a>
                            </li>
@endforeach

                        <li>
                            <a href="{{ route('front.contact') }}" style="color: {{ $gs->footer_text_color }}">
                                <i class="fas fa-angle-double-right" style="color: {{ $gs->footer_text_color }}"></i>{{ $langg->lang23 }}
                            </a>
                        </li>
                    </ul> -->
                        <div class="footer-info-area">
                            <!-- <div class="footer-logo">
                        <a href="{{ route('front.index') }}" class="logo-link">
                            <img src="{{ asset('assets/images/' . $gs->footer_logo) }}" alt="">
                        </a>
                    </div> -->
                            <div class="text">
                                <!-- <p style="color: {{ $gs->footer_text_color }}">
                                {!! $gs->footer !!}
                            </p>
                            -->
                                <p class='title'>Tractor Brothers - Butler</p>
                                <p>520 Evans City Road<br>Butler, PA 16001</p>

                                <p><a href='https://www.tractorbrothers.com/locations/37100'>(Map & Hours)</a></p>
                                <p>(724) 482-6288</p>

                            </div>
                            <div class="sub_text">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 footer-item">
                    <div class="footer-widget recent-post-widget">
                        <!-- <h4 class="title" style="color: {{ $gs->footer_text_color }}">
                        {{ $langg->lang24 }}
                    </h4>
                    <ul class="post-list">
                        @foreach (App\Models\Blog::orderBy('created_at', 'desc')->limit(3)->get() as $blog)
<li>
                                <div class="post">
                                    <div class="post-img">
                                        <img style="width: 73px; height: 59px;"
                                             src="{{ asset('assets/images/blogs/' . $blog->photo) }}" alt="">
                                    </div>
                                    <div class="post-details">
                                        <a href="{{ route('front.blogshow', $blog->id) }}">
                                            <h4 class="post-title" style="color: {{ $gs->footer_text_color }}">
                                                {{ mb_strlen($blog->title, 'utf-8') > 45 ? mb_substr($blog->title, 0, 45, 'utf-8') . ' ..' : $blog->title }}
                                            </h4>
                                        </a>
                                        <p class="date" style="color: {{ $gs->footer_text_color }}">
                                            {{ date('M d - Y', strtotime($blog->created_at)) }}
                                        </p>
                                    </div>
                                </div>
                            </li>
@endforeach
                    </ul> -->
                        <div class="footer-info-area">
                            <!-- <div class="footer-logo">
                        <a href="{{ route('front.index') }}" class="logo-link">
                            <img src="{{ asset('assets/images/' . $gs->footer_logo) }}" alt="">
                        </a>
                    </div> -->
                            <div class="text">
                                <!-- <p style="color: {{ $gs->footer_text_color }}">
                                {!! $gs->footer !!}
                            </p>
                            -->
                                <p class='title'>Tractor Brothers - Stoneboro</p>
                                <p>4352 Greenville Sandy Lake Road<br>Stoneboro, PA 161531</p>

                                <p><a href='https://www.tractorbrothers.com/locations/37101'>(Map & Hours)</a></p>
                                <p>(724) 253-2035</p>

                            </div>
                            <div class="sub_text">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="copy-bg">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="content">
                            <p style="color: {{ $gs->footer_text_color }}">{!! $gs->copyright !!}</p>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="content">
                            <p style="color: {{ $gs->footer_text_color }}">{!! $gs->copyright !!}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- Footer Area End -->

    <!-- Back to Top Start -->
    <div class="bottomtotop">
        <i class="fas fa-chevron-right"></i>
    </div>
    <!-- Back to Top End -->

    <!-- LOGIN MODAL -->
    <div class="modal fade" id="comment-log-reg" tabindex="-1" role="dialog"
        aria-labelledby="comment-log-reg-Title" aria-hidden="true">
        <div class="modal-dialog  modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <nav class="comment-log-reg-tabmenu">
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <a class="nav-item nav-link login active" id="nav-log-tab1" data-toggle="tab"
                                href="#nav-log1" role="tab" aria-controls="nav-log" aria-selected="true">
                                {{ $langg->lang197 }}
                            </a>
                            <a class="nav-item nav-link" id="nav-reg-tab1" data-toggle="tab" href="#nav-reg1"
                                role="tab" aria-controls="nav-reg" aria-selected="false">
                                {{ $langg->lang198 }}
                            </a>
                        </div>
                    </nav>
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-log1" role="tabpanel"
                            aria-labelledby="nav-log-tab1">
                            <div class="login-area">
                                <div class="header-area">
                                    <h4 class="title">{{ $langg->lang172 }}</h4>
                                </div>
                                <div class="login-form signin-form">
                                    @include('includes.admin.form-login')
                                    <form class="mloginform" action="{{ route('user.login.submit') }}"
                                        method="POST">
                                        {{ csrf_field() }}
                                        <div class="form-input">
                                            <input type="email" name="email"
                                                placeholder="{{ $langg->lang173 }}" required="">
                                            <i class="icofont-user-alt-5"></i>
                                        </div>
                                        <div class="form-input">
                                            <input type="password" class="Password" name="password"
                                                placeholder="{{ $langg->lang174 }}" required="">
                                            <i class="icofont-ui-password"></i>
                                        </div>
                                        <div class="form-forgot-pass">
                                            <div class="left">
                                                <input type="checkbox" name="remember" id="mrp"
                                                    {{ old('remember') ? 'checked' : '' }}>
                                                <label for="mrp">{{ $langg->lang175 }}</label>
                                            </div>
                                            <div class="right">
                                                <a href="javascript:;" id="show-forgot">
                                                    {{ $langg->lang176 }}
                                                </a>
                                            </div>
                                        </div>
                                        <input type="hidden" name="modal" value="1">
                                        <input class="mauthdata" type="hidden" value="{{ $langg->lang177 }}">
                                        <button type="submit" class="submit-btn">{{ $langg->lang178 }}</button>
                                        @if (App\Models\Socialsetting::find(1)->f_check == 1 || App\Models\Socialsetting::find(1)->g_check == 1)
                                            <div class="social-area">
                                                <h3 class="title">{{ $langg->lang179 }}</h3>
                                                <p class="text">{{ $langg->lang180 }}</p>
                                                <ul class="social-links">
                                                    @if (App\Models\Socialsetting::find(1)->f_check == 1)
                                                        <li>
                                                            <a href="{{ route('social-provider', 'facebook') }}">
                                                                <i class="fab fa-facebook-f"></i>
                                                            </a>
                                                        </li>
                                                    @endif
                                                    @if (App\Models\Socialsetting::find(1)->g_check == 1)
                                                        <li>
                                                            <a href="{{ route('social-provider', 'google') }}">
                                                                <i class="fab fa-google-plus-g"></i>
                                                            </a>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        @endif
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-reg1" role="tabpanel" aria-labelledby="nav-reg-tab1">
                            <div class="login-area signup-area">
                                <div class="header-area">
                                    <h4 class="title">{{ $langg->lang181 }}</h4>
                                </div>
                                <div class="login-form signup-form">
                                    @include('includes.admin.form-login')
                                    <form class="mregisterform" action="{{ route('user-register-submit') }}"
                                        method="POST">
                                        {{ csrf_field() }}

                                        <div class="form-input">
                                            <input type="text" class="User Name" name="name"
                                                placeholder="{{ $langg->lang182 }}" required="">
                                            <i class="icofont-user-alt-5"></i>
                                        </div>

                                        <div class="form-input">
                                            <input type="email" class="User Name" name="email"
                                                placeholder="{{ $langg->lang183 }}" required="">
                                            <i class="icofont-email"></i>
                                        </div>

                                        <div class="form-input">
                                            <input type="text" class="User Name" name="phone"
                                                placeholder="{{ $langg->lang184 }}" required="">
                                            <i class="icofont-phone"></i>
                                        </div>

                                        <div class="form-input">
                                            <input type="text" class="User Name" name="address"
                                                placeholder="{{ $langg->lang185 }}" required="">
                                            <i class="icofont-location-pin"></i>
                                        </div>

                                        <div class="form-input">
                                            <input type="password" class="Password" name="password"
                                                placeholder="{{ $langg->lang186 }}" required="">
                                            <i class="icofont-ui-password"></i>
                                        </div>

                                        <div class="form-input">
                                            <input type="password" class="Password" name="password_confirmation"
                                                placeholder="{{ $langg->lang187 }}" required="">
                                            <i class="icofont-ui-password"></i>
                                        </div>


                                        @if ($gs->is_capcha == 1)
                                            <ul class="captcha-area">
                                                <li>
                                                    <p><img class="codeimg1"
                                                            src="{{ asset('assets/images/capcha_code.png') }}"
                                                            alt=""> <i
                                                            class="fas fa-sync-alt pointer refresh_code "></i></p>
                                                </li>
                                            </ul>

                                            <div class="form-input">
                                                <input type="text" class="Password" name="codes"
                                                    placeholder="{{ $langg->lang51 }}" required="">
                                                <i class="icofont-refresh"></i>
                                            </div>
                                        @endif

                                        <input class="mprocessdata" type="hidden" value="{{ $langg->lang188 }}">
                                        <button type="submit" class="submit-btn">{{ $langg->lang189 }}</button>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- LOGIN MODAL ENDS -->

    <!-- FORGOT MODAL -->
    <div class="modal fade" id="forgot-modal" tabindex="-1" role="dialog" aria-labelledby="comment-log-reg-Title"
        aria-hidden="true">
        <div class="modal-dialog  modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="login-area">
                        <div class="header-area forgot-passwor-area">
                            <h4 class="title">{{ $langg->lang191 }} </h4>
                            <p class="text">{{ $langg->lang192 }} </p>
                        </div>
                        <div class="login-form">
                            @include('includes.admin.form-login')
                            <form id="mforgotform" action="{{ route('user-forgot-submit') }}" method="POST">
                                {{ csrf_field() }}
                                <div class="form-input">
                                    <input type="email" name="email" class="User Name"
                                        placeholder="{{ $langg->lang193 }}" required="">
                                    <i class="icofont-user-alt-5"></i>
                                </div>
                                <div class="to-login-page">
                                    <a href="javascript:;" id="show-login">
                                        {{ $langg->lang194 }}
                                    </a>
                                </div>
                                <input class="fauthdata" type="hidden" value="{{ $langg->lang195 }}">
                                <button type="submit" class="submit-btn">{{ $langg->lang196 }}</button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- FORGOT MODAL ENDS -->


    <!-- VENDOR LOGIN MODAL -->
    <div class="modal fade" id="vendor-login" tabindex="-1" role="dialog" aria-labelledby="vendor-login-Title"
        aria-hidden="true">
        <div class="modal-dialog  modal-dialog-centered modal-lg" style="transition: .5s;" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <nav class="comment-log-reg-tabmenu">
                        <div class="nav nav-tabs" id="nav-tab1" role="tablist">
                            <!-- <a class="nav-item nav-link login active" id="nav-log-tab11" data-toggle="tab" href="#nav-log11" role="tab" aria-controls="nav-log" aria-selected="true">
       {{ $langg->lang234 }}
                            </a> -->
                            <a class="nav-item nav-link login active" id="nav-reg-tab11" data-toggle="tab"
                                href="#nav-reg11" role="tab" aria-controls="nav-reg" aria-selected="false"
                                style="width: 100%">
                                {{ $langg->lang235 }}
                            </a>
                        </div>
                    </nav>
                    <div class="tab-content" id="nav-tabContent">
                        <!-- <div class="tab-pane fade" id="nav-log11" role="tabpanel" aria-labelledby="nav-log-tab">
    <div class="login-area">
    <div class="login-form signin-form">
    @include('includes.admin.form-login')
                        <form class="mloginform" action="{{ route('user.login.submit') }}" method="POST">
    {{ csrf_field() }}
                        <div class="form-input">
                          <input type="email" name="email" placeholder="{{ $langg->lang173 }}" required="">
    <i class="icofont-user-alt-5"></i>
    </div>
    <div class="form-input">
    <input type="password" class="Password" name="password" placeholder="{{ $langg->lang174 }}" required="">
    <i class="icofont-ui-password"></i>
    </div>
    <div class="form-forgot-pass">
    <div class="left">
    <input type="checkbox" name="remember"  id="mrp1" {{ old('remember') ? 'checked' : '' }}>
    <label for="mrp1">{{ $langg->lang175 }}</label>
    </div>
    <div class="right">
    <a href="javascript:;" id="show-forgot1">
    {{ $langg->lang176 }}
                        </a>
                      </div>
                    </div>
                    <input type="hidden" name="modal"  value="1">
                     <input type="hidden" name="vendor"  value="1">
                    <input class="mauthdata" type="hidden"  value="{{ $langg->lang177 }}">
    <button type="submit" class="submit-btn">{{ $langg->lang178 }}</button>
     @if (App\Models\Socialsetting::find(1)->f_check == 1 || App\Models\Socialsetting::find(1)->g_check == 1)
<div class="social-area">
                        <h3 class="title">{{ $langg->lang179 }}</h3>
     <p class="text">{{ $langg->lang180 }}</p>
     <ul class="social-links">
     @if (App\Models\Socialsetting::find(1)->f_check == 1)
<li>
                          <a href="{{ route('social-provider', 'facebook') }}">
     <i class="fab fa-facebook-f"></i>
     </a>
     </li>
@endif
                    @if (App\Models\Socialsetting::find(1)->g_check == 1)
<li>
                          <a href="{{ route('social-provider', 'google') }}">
     <i class="fab fa-google-plus-g"></i>
     </a>
     </li>
@endif
                            </ul>
                        </div>
@endif
                        </form>
                      </div>
                    </div>
                </div> -->
                        <div class="tab-pane fade show active" id="nav-reg11" role="tabpanel"
                            aria-labelledby="nav-reg-tab">
                            <div class="login-area signup-area">
                                <div class="login-form signup-form">
                                    @include('includes.admin.form-login')
                                    <form class="mregisterform" action="{{ route('user-register-submit') }}"
                                        method="POST">
                                        {{ csrf_field() }}

                                        <div class="row">

                                            <div class="col-lg-6">
                                                <div class="form-input">
                                                    <input type="text" class="User Name" name="name"
                                                        placeholder="{{ $langg->lang182 }}" required="">
                                                    <i class="icofont-user-alt-5"></i>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="form-input">
                                                    <input type="email" class="User Name" name="email"
                                                        placeholder="{{ $langg->lang183 }}" required="">
                                                    <i class="icofont-email"></i>
                                                </div>

                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-input">
                                                    <input type="text" class="User Name" name="phone"
                                                        placeholder="{{ $langg->lang184 }}" required="">
                                                    <i class="icofont-phone"></i>
                                                </div>

                                            </div>
                                            <div class="col-lg-6">

                                                <div class="form-input">
                                                    <input type="text" class="User Name" name="address"
                                                        placeholder="{{ $langg->lang185 }}" required="">
                                                    <i class="icofont-location-pin"></i>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="form-input">
                                                    <input type="text" class="User Name" name="shop_name"
                                                        placeholder="{{ $langg->lang238 }}" required="">
                                                    <i class="icofont-cart-alt"></i>
                                                </div>

                                            </div>
                                            <div class="col-lg-6">

                                                <div class="form-input">
                                                    <input type="text" class="User Name" name="owner_name"
                                                        placeholder="{{ $langg->lang239 }}" required="">
                                                    <i class="icofont-cart"></i>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">

                                                <div class="form-input">
                                                    <input type="text" class="User Name" name="shop_number"
                                                        placeholder="{{ $langg->lang240 }}" required="">
                                                    <i class="icofont-shopping-cart"></i>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">

                                                <div class="form-input">
                                                    <input type="text" class="User Name" name="shop_address"
                                                        placeholder="{{ $langg->lang241 }}" required="">
                                                    <i class="icofont-opencart"></i>
                                                </div>
                                            </div>

                                            <div class="col-lg-12">

                                                <div class="form-input">
                                                    <select name="category_id" required="">
                                                        <option value="" selected disabled>Category</option>
                                                        @foreach ($categories as $data)
                                                            <option value="{{ $data->id }}">{{ $data->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <i class="fas fa-sitemap"></i>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">

                                                <div class="form-input">
                                                    <input type="text" class="User Name" name="reg_number"
                                                        placeholder="{{ $langg->lang242 }}" required="">
                                                    <i class="icofont-ui-cart"></i>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">

                                                <div class="form-input">
                                                    <input type="text" class="User Name" name="shop_message"
                                                        placeholder="{{ $langg->lang243 }}" required="">
                                                    <i class="icofont-envelope"></i>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="form-input">
                                                    <input type="password" class="Password" name="password"
                                                        placeholder="{{ $langg->lang186 }}" required="">
                                                    <i class="icofont-ui-password"></i>
                                                </div>

                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-input">
                                                    <input type="password" class="Password"
                                                        name="password_confirmation"
                                                        placeholder="{{ $langg->lang187 }}" required="">
                                                    <i class="icofont-ui-password"></i>
                                                </div>
                                            </div>

                                            @if ($gs->is_capcha == 1)
                                                <div class="col-lg-6">


                                                    <ul class="captcha-area">
                                                        <li>
                                                            <p>
                                                                <img class="codeimg1"
                                                                    src="{{ asset('assets/images/capcha_code.png') }}"
                                                                    alt=""> <i
                                                                    class="fas fa-sync-alt pointer refresh_code "></i>
                                                            </p>

                                                        </li>
                                                    </ul>


                                                </div>

                                                <div class="col-lg-6">

                                                    <div class="form-input">
                                                        <input type="text" class="Password" name="codes"
                                                            placeholder="{{ $langg->lang51 }}" required="">
                                                        <i class="icofont-refresh"></i>

                                                    </div>


                                                </div>
                                            @endif

                                            <input type="hidden" name="vendor" value="1">
                                            <input class="mprocessdata" type="hidden"
                                                value="{{ $langg->lang188 }}">
                                            <button type="submit" class="submit-btn">{{ $langg->lang189 }}</button>

                                        </div>


                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- VENDOR LOGIN MODAL ENDS -->

    <!-- Product Quick View Modal -->

    <div class="modal fade" id="quickview" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog quickview-modal modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="submit-loader">
                    <img src="{{ asset('assets/images/' . $gs->loader) }}" alt="">
                </div>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container quick-view-modal">

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Product Quick View Modal -->

    <!-- Ask Age View Modal -->
    <div class="modal fade" id="askage" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog askage-modal modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container askage-view-modal">

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Ask Age View Modal -->

    <!-- Order Tracking modal Start-->
    <div class="modal fade" id="track-order-modal" tabindex="-1" role="dialog"
        aria-labelledby="order-tracking-modal" aria-hidden="true">
        <div class="modal-dialog  modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title"><b>{{ $langg->lang772 }}</b></h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="order-tracking-content">
                        <form id="track-form" class="track-form">
                            {{ csrf_field() }}
                            <input type="text" id="track-code" placeholder="{{ $langg->lang773 }}"
                                required="">
                            <button type="submit" class="mybtn1">{{ $langg->lang774 }}</button>
                            <a href="#" data-toggle="modal" data-target="#order-tracking-modal"></a>
                        </form>
                    </div>

                    <div>
                        <div class="submit-loader d-none">
                            <img src="{{ asset('assets/images/' . $gs->loader) }}" alt="">
                        </div>
                        <div id="track-order">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Order Tracking modal End -->

    <script type="text/javascript">
        var mainurl = "{{ url('/') }}";
        var gs = {!! json_encode($gs) !!};
        var langg = {!! json_encode($langg) !!};
    </script>

    <!-- jquery -->
    <script src="{{ asset('assets/front/js/jquery.js') }}"></script>
    <script src="{{ asset('assets/front/js/vue.js') }}"></script>
    <script src="{{ asset('assets/front/jquery-ui/jquery-ui.min.js') }}"></script>
    <!-- popper -->
    <script src="{{ asset('assets/front/js/popper.min.js') }}"></script>
    <!-- bootstrap -->
    <script src="{{ asset('assets/front/js/bootstrap.min.js') }}"></script>
    <!-- plugin js-->
    <script src="{{ asset('assets/front/js/plugin.js') }}"></script>
    <!-- <script src="{{ asset('assets/front/js/owl.carousel.min.js') }}"></script> -->

    <script src="{{ asset('assets/front/js/xzoom.min.js') }}"></script>
    <script src="{{ asset('assets/front/js/jquery.hammer.min.js') }}"></script>
    <script src="{{ asset('assets/front/js/setup.js') }}"></script>

    <script src="{{ asset('assets/front/js/toastr.js') }}"></script>
    <!-- main -->
    <script src="{{ asset('assets/front/js/main.js') }}"></script>
    <!-- custom -->
    <script src="{{ asset('assets/front/js/custom.js') }}"></script>

    {!! $seo->google_analytics !!}

    @if ($gs->is_talkto == 1)
        <!--Start of Tawk.to Script-->
        {!! $gs->talkto !!}
        <!--End of Tawk.to Script-->
    @endif

    @yield('scripts')

</body>
</html>
