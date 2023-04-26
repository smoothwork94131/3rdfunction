@extends('layouts.front')
@section('content')
<div class="breadcrumb-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <ul class="pages">
                    <li><a href="{{ route('front.index') }}">{{ $langg->lang17 }}</a></li>
                    @php
                    $index = 1;
                    $route = '';
                    if ($page != 'product') {
                    $route = route('front.' . $page);
                    }
                    @endphp
                    @foreach ($slug_list as $key => $item)
                    @php

                    $path = $item;
                    if (strstr($path, '/')) {
                    $path = str_replace('/', ':::', $path);
                    }
                    $route = $route . '/' . $path;

                    @endphp
                    <li>
                        @if (count($slug_list) == $index)
                        <a>
                            {{ $item }}
                        </a>
                        @else
                        <a href="{{ $route }}">
                            {{ $item }}
                        </a>
                        @endif

                    </li>
                    @php
                    $index++;
                    @endphp
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Product Details Area Start -->
<section class="product-details-page">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-5 col-md-12">
                        <div class="xzoom-container">
                            <img class="xzoom5" id="xzoom-magnific" style="width: 100%;" 
                                src="{{ filter_var($productt->photo, FILTER_VALIDATE_URL) ? $productt->photo : ($productt->photo ? asset('assets/images/products/' . $productt->photo) : asset('assets/images/noimage.png')) }}" 
                                xoriginal="{{ filter_var($productt->photo, FILTER_VALIDATE_URL) ? $productt->photo : ($productt->photo ? asset('assets/images/products/' . $productt->photo) : asset('assets/images/noimage.png')) }}" />
                            <div class="xzoom-thumbs">
                                <div class="all-slider">
                                    <a href="{{ filter_var($productt->photo, FILTER_VALIDATE_URL) ? $productt->photo : ($productt->photo ? asset('assets/images/products/' . $productt->photo) : asset('assets/images/products/' . $gs->prod_image)) }}">
                                        <img class="xzoom-gallery5" width="80" src="{{ filter_var($productt->photo, FILTER_VALIDATE_URL) ? $productt->photo : ($productt->photo ? asset('assets/images/products/' . $productt->photo) : asset('assets/images/noimage.png')) }}" title="The description goes here">
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <div class="right-area">
                            <div class="product-info">
                                <h4 class="product-name">{{ $productt->name }}</h4>
                                <div class="info-meta-1">
                                    <ul>
                                        <li class="product-isstook">
                                            <p>
                                                <i class="icofont-check-circled"></i>
                                                {{ $gs->show_stock == 0 ? '' : $productt->stock }}
                                                {{ $langg->lang79 }}
                                            </p>
                                        </li>
                                        <li>
                                            <div class="ratings">
                                                <div class="empty-stars"></div>
                                                <div class="full-stars"></div>
                                            </div>
                                        </li>
                                        <li class="review-count">
                                            <p>{{ $langg->lang80 }}</p>
                                        </li>
                                        @if ($productt->product_condition != 0)
                                        <li>
                                            <div class="{{ $productt->product_condition == 2 ? 'mybadge' : 'mybadge1' }}">
                                                {{ $productt->product_condition == 2 ? 'New' : 'Used' }}
                                            </div>
                                        </li>
                                        @endif
                                    </ul>
                                </div>

                                <div class="product-model">
                                    <small>Model #:  <?php echo $productt->subcategory_id; ?></small>
                                </div>
                                <div class="product-part">
                                    <small>Part #:  <?php echo $productt->sku; ?></small>
                                </div>

                                <div class="product-price">
                                    <p class="title">{{ $langg->lang87 }} :</p>
                                    <p class="price"><span id="sizeprice">{{ $productt->price }}</span>
                                    </p>
                                    @if ($productt->youtube != null)
                                    <a href="{{ $productt->youtube }}" class="video-play-btn mfp-iframe">
                                        <i class="fas fa-play"></i>
                                    </a>
                                    @endif
                                </div>

                                <div class="info-meta-2">
                                    <ul>
                                        @if ($productt->type == 'License')
                                        @if ($productt->platform != null)
                                        <li>
                                            <p>{{ $langg->lang82 }}: <b>{{ $productt->platform }}</b></p>
                                        </li>
                                        @endif

                                        @if ($productt->region != null)
                                        <li>
                                            <p>{{ $langg->lang83 }}: <b>{{ $productt->region }}</b></p>
                                        </li>
                                        @endif

                                        @if ($productt->licence_type != null)
                                        <li>
                                            <p>{{ $langg->lang84 }}: <b>{{ $productt->licence_type }}</b>
                                            </p>
                                        </li>
                                        @endif
                                        @endif

                                    </ul>
                                </div>

                                @if (!empty($productt->size))
                                <div class="product-size">
                                    <p class="title">{{ $langg->lang88 }} :</p>
                                    <ul class="siz-list">
                                        @php
                                        $is_first = true;
                                        @endphp
                                        @foreach ($productt->size as $key => $data1)
                                        <li class="{{ $is_first ? 'active' : '' }}">
                                            <span class="box">{{ $data1 }}
                                                <input type="hidden" class="size" value="{{ $data1 }}">
                                                <input type="hidden" class="size_qty" value="{{ $productt->size_qty[$key] }}">
                                                <input type="hidden" class="size_key" value="{{ $key }}">
                                                <input type="hidden" class="size_price" value="{{ round($productt->size_price[$key] * $curr->value, 2) }}">
                                            </span>
                                        </li>
                                        @php
                                        $is_first = false;
                                        @endphp
                                        @endforeach
                                        <li>
                                    </ul>
                                </div>
                                @endif

                                @if (!empty($productt->color))
                                <div class="product-color">
                                    <p class="title">{{ $langg->lang89 }} :</p>
                                    <ul class="color-list">
                                        @php
                                        $is_first = true;
                                        @endphp
                                        @foreach ($productt->color as $key => $data1)
                                        <li class="{{ $is_first ? 'active' : '' }}">
                                            <span class="box" data-color="{{ $productt->color[$key] }}" style="background-color: {{ $productt->color[$key] }}"></span>
                                        </li>
                                        @php
                                        $is_first = false;
                                        @endphp
                                        @endforeach

                                    </ul>
                                </div>
                                @endif

                                @if (!empty($productt->size))
                                <input type="hidden" id="stock" value="{{ $productt->size_qty[0] }}">
                                @else
                                @php
                                $stck = (string) $productt->stock;
                                @endphp
                                @if ($stck != null)
                                <input type="hidden" id="stock" value="{{ $stck }}">
                                @elseif($productt->type != 'Physical')
                                <input type="hidden" id="stock" value="0">
                                @else
                                <input type="hidden" id="stock" value="">
                                @endif
                                @endif


                                <input type="hidden" id="product_id" value="{{ $productt->id }}">
                                <input type="hidden" id="db" value="{{ $db ?? 'products' }}">
                                <input type="hidden" id="curr_pos" value="{{ $gs->currency_format }}">
                                <input type="hidden" id="curr_sign" value="{{ $curr->sign }}">


                                <div class="info-meta-3">
                                    <ul class="meta-list">
                                        @if ($productt->product_type != 'affiliate')
                                        <li class="d-block count {{ $productt->type == 'Physical' ? '' : 'd-none' }}">
                                            <div class="qty" style="display: flex; align-items: center;">
                                                <label style="font-size: 14px; margin-right: 10px; margin-top: 3px;">Quantity:
                                                </label>
                                                <ul style="display:flex; align-items-center: center;">
                                                    <li>
                                                        <span class="qtminus">
                                                            <i class="icofont-minus"></i>
                                                        </span>
                                                    </li>
                                                    <li>
                                                        <!-- <span class="qttotal">1</span> -->
                                                        <input type="text" id="qttotal" class="qttotal" value="1" step="1" min="0" style="margin-right: 3px; width: 50px;" />
                                                    </li>
                                                    <li>
                                                        <span class="qtplus">
                                                            <i class="icofont-plus"></i>
                                                        </span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </li>
                                        @endif

                                        @if (!empty($productt->attributes))
                                        @php
                                        $attrArr = json_decode($productt->attributes, true);
                                        @endphp
                                        @endif
                                        @if (!empty($attrArr))
                                        <div class="product-attributes my-4">
                                            <div class="row">
                                                @foreach ($attrArr as $attrKey => $attrVal)
                                                @if (array_key_exists('details_status', $attrVal) &&
                                                $attrVal['details_status'] == 1)
                                                <div class="col-lg-6">
                                                    <div class="form-group mb-2">
                                                        <strong for="" class="text-capitalize">{{ str_replace('_', ' ',
                                                            $attrKey) }}
                                                            :</strong>
                                                        <div class="">
                                                            @foreach ($attrVal['values'] as $optionKey => $optionVal)
                                                            <div class="custom-control custom-radio">
                                                                <input type="hidden" class="keys" value="">
                                                                <input type="hidden" class="values" value="">
                                                                <input type="radio" id="{{ $attrKey }}{{ $optionKey }}" name="{{ $attrKey }}" class="custom-control-input product-attr" data-key="{{ $attrKey }}" data-price="{{ round($attrVal['prices'][$optionKey] * $curr->value, 2) }}" value="{{ $optionVal }}" {{ $loop->first ? 'checked'
                                                                : '' }}>
                                                                <label class="custom-control-label" for="{{ $attrKey }}{{ $optionKey }}">{{ $optionVal
                                                                    }}
                                                                    : {{ $curr->sign }}
                                                                    {{ number_format($productt->showRealPrice() +
                                                                    $attrVal['prices'][$optionKey] * $curr->value, '2',
                                                                    '.', '') }}
                                                                </label>
                                                            </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                                @endforeach
                                            </div>
                                        </div>
                                        @endif

                                        <li class="addtocart">
                                            <a href="javascript:;" id="addcrt"><i class="icofont-cart"></i>{{
                                                $langg->lang90 }}</a>
                                        </li>

                                        <li class="addtocart">
                                            <a id="qaddcrt" href="javascript:;">
                                                <i class="icofont-cart"></i>{{ $langg->lang251 }}
                                            </a>
                                        </li>
                                        
                                        @if (Auth::guard('web')->check())
                                        <li class="favorite">
                                            <a href="javascript:;" class="add-to-wish" data-href="{{ route('user-wishlist-add', $productt->id) }}"><i class="icofont-heart-alt"></i></a>
                                        </li>
                                        @else
                                        <li class="favorite">
                                            <a href="javascript:;" data-toggle="modal" data-target="#comment-log-reg"><i class="icofont-heart-alt"></i></a>
                                        </li>
                                        @endif

                                    </ul>
                                </div>

                                @if ($productt->ship != null)
                                <p class="estimate-time">{{ $langg->lang86 }}: <b> {{ $productt->ship }}</b></p>
                                @endif
                                @if ($gs->is_report)
                                {{-- PRODUCT REPORT SECTION --}}

                                @if (Auth::guard('web')->check())
                                <div class="report-area">
                                    <a href="javascript:;" data-toggle="modal" data-target="#report-modal"><i class="fas fa-flag"></i> {{ $langg->lang776 }}</a>
                                </div>
                                @else
                                <div class="report-area">
                                    <a href="javascript:;" data-toggle="modal" data-target="#comment-log-reg"><i class="fas fa-flag"></i>
                                        {{ $langg->lang776 }}</a>
                                </div>
                                @endif

                                {{-- PRODUCT REPORT SECTION ENDS --}}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div id="product-details-tab">
                            <div class="top-menu-area">
                                <ul class="tab-menu">
                                    <li><a href="#tabs-1">{{ $langg->lang92 }}</a></li>
                                    <li><a href="#tabs-2">{{ $langg->lang93 }}</a></li>
                                    <li><a href="#tabs-3">{{ $langg->lang94 }}({{ '0' }})</a>
                                    </li>

                                </ul>
                            </div>
                            <div class="tab-content-wrapper">
                                <div id="tabs-1" class="tab-content-area">
                                    <p>{!! $productt->description !!}</p>
                                </div>
                                <div id="tabs-2" class="tab-content-area">
                                    <p>{!! $productt->policy !!}</p>
                                </div>
                                <div id="tabs-3" class="tab-content-area">
                                    <div class="heading-area">
                                        <h4 class="title">
                                            {{ $langg->lang96 }}
                                        </h4>
                                        <div class="reating-area">
                                            <div class="stars"><span id="star-rating">{{
                                                    App\Models\Rating::rating($productt->id) }}</span>
                                                <i class="fas fa-star"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="replay-area">
                                        <div id="reviews-section">

                                            <p>{{ $langg->lang97 }}</p>
                                        </div>
                                        @if (Auth::guard('web')->check())
                                        <div class="review-area">
                                            <h4 class="title">{{ $langg->lang98 }}</h4>
                                            <div class="star-area">
                                                <ul class="star-list">
                                                    <li class="stars" data-val="1">
                                                        <i class="fas fa-star"></i>
                                                    </li>
                                                    <li class="stars" data-val="2">
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                    </li>
                                                    <li class="stars" data-val="3">
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                    </li>
                                                    <li class="stars" data-val="4">
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                    </li>
                                                    <li class="stars active" data-val="5">
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                        <i class="fas fa-star"></i>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="write-comment-area">
                                            <div class="gocover" style="background: url({{ asset('assets/images/' . $gs->loader) }}) no-repeat scroll center center rgba(45, 45, 45, 0.5);">
                                            </div>
                                            <form id="reviewform" action="{{ route('front.review.submit') }}" data-href="{{ route('front.reviews', $productt->id) }}" method="POST">
                                                @include('includes.admin.form-both')
                                                {{ csrf_field() }}
                                                <input type="hidden" id="rating" name="rating" value="5">
                                                <input type="hidden" name="user_id" value="{{ Auth::guard('web')->user()->id }}">
                                                <input type="hidden" name="product_id" value="{{ $productt->id }}">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <textarea name="review" placeholder="{{ $langg->lang99 }}" required=""></textarea>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <button class="submit-btn" type="submit">{{ $langg->lang100
                                                            }}</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        @else
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <br>
                                                <h5 class="text-center"><a href="javascript:;" data-toggle="modal" data-target="#comment-log-reg" class="btn login-btn mr-1">{{
                                                        $langg->lang101 }}</a>
                                                    {{ $langg->lang102 }}
                                                </h5>
                                                <br>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Tranding Item Area End -->
</section>
<!-- Product Details Area End -->

{{-- MESSAGE MODAL --}}
<div class="message-modal">
    <div class="modal" id="vendorform" tabindex="-1" role="dialog" aria-labelledby="vendorformLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="vendorformLabel">{{ $langg->lang118 }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid p-0">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="contact-form">
                                    <form id="emailreply1">
                                        {{ csrf_field() }}
                                        <ul>
                                            <li>
                                                <input type="text" class="input-field" id="subj1" name="subject" placeholder="{{ $langg->lang119 }}" required="">
                                            </li>
                                            <li>
                                                <textarea class="input-field textarea" name="message" id="msg1" placeholder="{{ $langg->lang120 }}" required=""></textarea>
                                            </li>
                                            <input type="hidden" name="type" value="Ticket">
                                        </ul>
                                        <button class="submit-btn" id="emlsub" type="submit">{{ $langg->lang118
                                            }}</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- MESSAGE MODAL ENDS --}}

@if ($gs->is_report)
@if (Auth::check())
{{-- REPORT MODAL SECTION --}}
<div class="modal fade" id="report-modal" tabindex="-1" role="dialog" aria-labelledby="report-modal-Title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="gocover" style="background: url({{ asset('assets/images/' . $gs->loader) }}) no-repeat scroll center center rgba(45, 45, 45, 0.5);">
                </div>

                <div class="login-area">
                    <div class="header-area forgot-passwor-area">
                        <h4 class="title">{{ $langg->lang777 }}</h4>
                        <p class="text">{{ $langg->lang778 }}</p>
                    </div>
                    <div class="login-form">

                        <form id="reportform" action="{{ route('product.report') }}" method="POST">

                            @include('includes.admin.form-login')

                            {{ csrf_field() }}
                            <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                            <input type="hidden" name="product_id" value="{{ $productt->id }}">
                            <div class="form-input">
                                <input type="text" name="title" class="User Name" placeholder="{{ $langg->lang779 }}" required="">
                                <i class="icofont-notepad"></i>
                            </div>

                            <div class="form-input">
                                <textarea name="note" class="User Name" placeholder="{{ $langg->lang780 }}" required=""></textarea>
                            </div>

                            <button type="submit" class="submit-btn">{{ $langg->lang196 }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- REPORT MODAL SECTION ENDS --}}
@endif
@endif

@endsection


@section('scripts')
<script type="text/javascript">
    $(document).on("submit", "#emailreply1", function() {
        var token = $(this).find('input[name=_token]').val();
        var subject = $(this).find('input[name=subject]').val();
        var message = $(this).find('textarea[name=message]').val();
        var $type = $(this).find('input[name=type]').val();
        $('#subj1').prop('disabled', true);
        $('#msg1').prop('disabled', true);
        $('#emlsub').prop('disabled', true);
        $.ajax({
            type: 'post'
            , url: "{{ URL::to('/user/admin/user/send/message') }}"
            , data: {
                '_token': token
                , 'subject': subject
                , 'message': message
                , 'type': $type
            }
            , success: function(data) {
                $('#subj1').prop('disabled', false);
                $('#msg1').prop('disabled', false);
                $('#subj1').val('');
                $('#msg1').val('');
                $('#emlsub').prop('disabled', false);
                if (data == 0)
                    toastr.error("Oops Something Goes Wrong !!");
                else
                    toastr.success("Message Sent !!");
                $('.close').click();
            }

        });
        return false;
    });

</script>
@endsection
