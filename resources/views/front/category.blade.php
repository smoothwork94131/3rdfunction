@extends('layouts.front')

@section('styles')
<style>
    .switch-section {
        margin: 20px 0;
    }

    .custom-control-label::before {
        position: absolute;
        top: .25rem;
        left: -1.5rem;
        display: block!important;
        width: 1rem;
        height: 1rem!important;
        pointer-events: none;
        content: ""!important;
        background-color: #fff;
        border: #adb5bd solid 1px;
    }

    .custom-control-input {
        position: absolute;
        left: 0;
        z-index: -1;
        width: 1rem!important;
        height: 1.25rem!important;
        opacity: 0;
    }

    .custom-control {
        position: relative;
        z-index: 1!important;
        display: block;
        min-height: 1.5rem;
    }

    .dropdown-toggle:after {
        display:none;
    }

    #list_view {
        background-color: #fff;
        padding: 10px;
    }

    .dropdown-item {
        cursor: pointer;
    }
</style>
@endsection
@section('content')
    @if ($group)
    <section class="sub-categori">
        <div class="container-fluid" style="padding-left: 5%; padding-right: 5%">
            <div class="breadcrumb-area">
                <div class="section-top" style="display: block; padding-left: 0px;">
                    @php 
                        $route = "front.partsbymodel" ;
                        $index = 1 ;
                    @endphp
                    <div>
                        <ul class="pages parts-by-model-title">
                            <li>
                                <a href="{{ route('front.index') }}">
                                    {{ $langg->lang17 }}
                                </a>
                            </li>
                            <li>
                                <a href="{{ route($route) }}">
                                    Category
                                </a>
                            </li>
                            @php
                                $route = route("$route") ;
                            @endphp
                            @foreach($slug_list as $key =>$item)
                                @php
                                    if(strstr($item, "/")) {    
                                        $slug_list[$key] = str_replace("/", ":::", $item) ;
                                    }

                                    $path = $item ;
                                    if(strstr($path, "/")) {
                                        $path = str_replace("/", ":::", $path) ;
                                    }
                                    $route = $route."/".$path ;  
                                @endphp
                                <li>
                                    @if(count($slug_list) == $index) 
                                        <a>
                                            {{$item}}
                                        </a>
                                    @else
                                        <a href = "{{$route}}">
                                            {{$item}}
                                        </a>
                                    @endif
                                </li>
                                @php
                                    $index++ ;
                                @endphp
                            @endforeach
                        </ul>
                    </div>
                    <h2 class="section-title remove-padding">
                        {{$group->group_name }}
                        <span class="title-underline"></span>
                    </h2>
                </div>
            </div>
            <div class="parts-container">
                <div class="group-table d-desktop">
                    <div class="group-schematics">
                        @if($group->image && file_exists(public_path('assets/images/group/'.$group->image)))
                            <img src="{{asset('assets/images/group/'.$group->image)}}">
                        @else
                            @if(file_exists(public_path('assets/images/group/'.$group->group_Id.'.png')))
                            <img src="{{asset('assets/images/group/'.$group->group_Id.'.png')}}">
                            @else
                            <img src="{{asset('assets/images/noimage.png')}}" style="min-width: 100px;">
                            @endif
                        @endif
                    </div>
                    <div class="parts-table">
                        <p style="text-align: center; margin-bottom: 0px; font-size: 12px;">
                            Parts not listed are not available at this time
                        </p>
                        <table id="product_table" class="table product_table" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                @if($refno_flag)
                                <th style="text-align:center;">NO</th>
                                @endif
                                @if($thumbnail_flag)
                                <th style="text-align:center;"></th>
                                @endif
                                <th style="text-align:center;">Name</th>
                                <th style="text-align:center;">Price</th>
                                <th style="text-align:center;">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($prods as $key=>$prod)
                                @php 
                                    $path = $prod->name ;
                                    if(strstr($path, "/")) {
                                        $path = str_replace("/", ":::", $path) ;
                                    }
                                    $slug_list['prod_name'] = $path ;
                                @endphp
                                <tr>
                                    @if($refno_flag)
                                    <td style="text-align:center;">
                                        {{ $prod->top }}
                                    </td>
                                    @endif
                                    @if($thumbnail_flag)
                                    <td style="text-align:center;">
                                        <img style="width:30px; height: 30px;" src="{{ $prod->thumbnail ? asset('assets/images/thumbnails/'.$prod->thumbnail):asset('assets/images/noimage.png') }}" alt="">
                                    </td>
                                    @endif
                                    <td style="text-align:center;">
                                        <a href="{{route('front.product', $slug_list)}}">{{ $prod->name }}</a>
                                    </td>
                                    <td style="text-align:center;">
                                        ${{ $prod->price }}
                                    </td>
                                    <td style="text-align:center;">
                                        <div class="dropdown">
                                            <a class="btn-floating btn-lg black dropdown-toggle"type="button" id="dropdownMenu3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-primary">
                                                @if(Auth::guard('web')->check())
                                                    <span class="dropdown-item add-to-wish" data-href="{{ route('user-wishlist-add',$prod->id) }}"><i class="icofont-heart-alt"></i>&nbsp;&nbsp;Add to Wish</span>
                                                @else
                                                    <span class="dropdown-item" data-toggle="modal" id="wish-btn" data-target="#comment-log-reg"><i class="icofont-heart-alt"></i>&nbsp;&nbsp;Add to Wish</span>
                                                @endif
                                                <span class="dropdown-item quick-view" data-href="{{ route('product.iquick',['db' => $db, 'id' => $prod->id]) }}" data-toggle="modal" data-target="#quickview"><i class="icofont-eye"></i>&nbsp;&nbsp;Quick View</span>
                                                <span class="dropdown-item add-to-cart add-to-cart-btn" data-href="{{ route('product.cart.add',['db' => $db, 'id' => $prod->id]) }}"><i class="icofont-cart"></i>&nbsp;&nbsp;{{ $langg->lang56 }}</span>
                                                <span class="dropdown-item add-to-cart-quick" style="width: 100%;" data-href="{{ route('product.cart.quickadd',['db' => $db, 'id' => $prod->id]) }}"><i class="icofont-dollar"></i>&nbsp;&nbsp;{{ $langg->lang251 }}</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
    
                <div class="group-table d-mobile">
                    <div style='margin-bottom: 15px; text-align: center;'>
                        <button type="button" class="btn btn-primary" style="background: #F05223; border: 1px solid #F05223" data-toggle="modal" data-target="#prod_img_modal">View Schematic Diagram</button>
                    </div>
                    <p style="text-align: center; margin-bottom: 0px; font-size: 12px;">
                        Parts not listed are not available at this time
                    </p>
                    <table id="product_table" class="table product_table" cellspacing="0" width="100%" style="font-size: 12px;">
                        <thead>
                            <tr>
                                @if($refno_flag)
                                <th style="text-align:center;">NO</th>
                                @endif
                                @if($thumbnail_flag)
                                <th style="text-align:center;"></th>
                                @endif
                                <th style="text-align:center;">Name</th>
                                <th style="text-align:center;">Price</th>
                                <th style="text-align:center;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($prods as $key=>$prod)
                            @php 
                                $path = $prod->name ;
                                if(strstr($path, "/")) {
                                    $path = str_replace("/", ":::", $path) ;
                                }
                                $slug_list['prod_name'] = $path ;
                            @endphp
                            <tr>
                                @if($refno_flag)
                                <td style="text-align:center;">
                                    {{ $prod->top }}
                                </td>
                                @endif
                                @if($thumbnail_flag)
                                <td style="text-align:center;">
                                    <img style="width:30px; height: 30px;" src="{{ $prod->thumbnail ? asset('assets/images/thumbnails/'.$prod->thumbnail):asset('assets/images/noimage.png') }}" alt="">
                                </td>
                                @endif
                                <td style="text-align:center;">
                                    <a href="{{route('front.product', $slug_list)}}">{{ $prod->name }}</a>
                                </td>
                                <td style="text-align:center;">
                                    ${{ $prod->price }}
                                </td>
                                <td style="text-align:center;">
                                    <div class="dropdown">
                                        <a class="btn-floating btn-lg black dropdown-toggle"type="button" id="dropdownMenu3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-primary">
                                            @if(Auth::guard('web')->check())
                                                <span class="dropdown-item add-to-wish" data-href="{{ route('user-wishlist-add',$prod->id) }}"><i class="icofont-heart-alt"></i>&nbsp;&nbsp;Add to Wish</span>
                                            @else
                                                <span class="dropdown-item" data-toggle="modal" id="wish-btn" data-target="#comment-log-reg"><i class="icofont-heart-alt"></i>&nbsp;&nbsp;Add to Wish</span>
                                            @endif
                                            <span class="dropdown-item quick-view" data-href="{{ route('product.iquick',['db' => $db, 'id' => $prod->id]) }}" data-toggle="modal" data-target="#quickview"><i class="icofont-eye"></i>&nbsp;&nbsp;Quick View</span>
                                            <span class="dropdown-item add-to-cart add-to-cart-btn" data-href="{{ route('product.cart.add',['db' => $db, 'id' => $prod->id]) }}"><i class="icofont-cart"></i>&nbsp;&nbsp;{{ $langg->lang56 }}</span>
                                            <span class="dropdown-item add-to-cart-quick" style="width: 100%;" data-href="{{ route('product.cart.quickadd',['db' => $db, 'id' => $prod->id]) }}"><i class="icofont-dollar"></i>&nbsp;&nbsp;{{ $langg->lang251 }}</span>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="modal fade" id="prod_img_modal" role="dialog" >
            <div class="modal-dialog modal-lg" style='width: 100%; top: 10%; left: 0%; margin: 0px;'>
                <div class="modal-content">
                    <div class="modal-header" style='padding: 5px;'>
                        <button type="button" 
                            class="close" 
                            data-dismiss="modal" 
                            style='right: 15px;top: 15px; background: transparent;z-index:100;'>&times;</button>
                    </div>
                    <div class="modal-body">
                        @if(file_exists(public_path('assets/images/group/'.$group->image)))
                        <img src="{{asset('assets/images/group/'.$group->image)}}" style='width: 100%;'>
                        @else
                        <img src="{{asset('assets/images/group/'.$group->group_Id.'.png')}}" style='width: 100%;'>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    @else
    <section class="sub-categori">
        <div class="container">
        @php 
            $route = "front.commonparts" ;
            $index = 1 ;
        @endphp
            <div class="col-lg-12 breadcrumb-area">
                <ul class="pages parts-by-model-title">
                    <li>
                        <a href="{{ route('front.index') }}">
                            {{ $langg->lang17 }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route($route) }}">
                            Category
                        </a>
                    </li>
                    @php
                        $route = route("$route") ;
                    @endphp
                    @foreach($slug_list as $key =>$item)
                        @php
                            $path = $item ;
                            if(strstr($path, "/")) {
                                $path = str_replace("/", ":::", $path) ;
                            }
                            $route = $route."/".$path ;
                            if(strstr($item, "/")) {    
                                $slug_list[$key] = str_replace("/", ":::", $item) ;
                            }
                        @endphp
                        <li>
                            @if(count($slug_list) == $index) 
                                <a>
                                    {{$item}}
                                </a>
                            @else
                                <a href = "{{$route}}">
                                    {{$item}}
                                </a>
                            @endif
                            
                        </li>
                        @php
                            $index++ ;
                        @endphp
                    @endforeach
                    
                </ul>
            </div>
            <table id="product_table" class="table product_table" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th width='10%' class='th-img' style="text-align:center;"></th>
                    <th style="text-align:center;">Name</th>
                    <th style="text-align:center;">Model</th>
                    <th class='th-group' style="text-align:center;">Group</th>
                    <th class='th-part' style="text-align:center;">Part</th>
                    <th class='th-price' style="text-align:center;">Price</th>
                    <th class='th-action' style="text-align:center;">Action</th>
                </tr>
                </thead>
                <tbody>
                
                @foreach($prods as $key=>$prod)
                @php 
                    $path = $prod->name ;
                    if(strstr($path, "/")) {
                        $path = str_replace("/", ":::", $path) ;
                    }
                    $slug_list['prod'] = $path ;
                @endphp
                    <tr>
                        <td class='td-img' style="text-align:center;">
                            <img  src="{{ $prod->thumbnail ? asset('assets/images/thumbnails/'.$prod->thumbnail):asset('assets/images/noimage.png') }}" alt="">
                        </td>
                        <td style="text-align:center;">
                            <a href="{{route('front.commonparts', $slug_list)}}">{{ $prod->name }}</a>
                        </td>
                        <td style="text-align:center;">
                            {{ $prod->subcategory_id }}
                        </td>
                        <td class='td-group' style="text-align:center;">
                            {{ $prod->parent }}
                        </td>
                        <td class='td-part' style="text-align:center;">
                            {{ $prod->sku }}
                        </td>
                        <td class='td-price' style="text-align:center;">
                            ${{ $prod->price }}
                        </td >  
                        <td class='td-action' style="text-align:center;">
                            <div class="dropdown">
                                <a class="btn-floating btn-lg black dropdown-toggle"type="button" id="dropdownMenu3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v"></i>
                                </a>
                                <div class="dropdown-menu dropdown-primary">
                                    @if(Auth::guard('web')->check())
                                        <span class="dropdown-item add-to-wish" data-href="{{ route('user-wishlist-add',$prod->id) }}"><i class="icofont-heart-alt"></i>&nbsp;&nbsp;Add to Wish</span>
                                    @else
                                        <span class="dropdown-item" data-toggle="modal" id="wish-btn" data-target="#comment-log-reg"><i class="icofont-heart-alt"></i>&nbsp;&nbsp;Add to Wish</span>
                                    @endif
                                    <span class="dropdown-item quick-view" data-href="{{ route('product.iquick',['db' => $db, 'id' => $prod->id]) }}" data-toggle="modal" data-target="#quickview"><i class="icofont-eye"></i>&nbsp;&nbsp;Quick View</span>
                                    <span class="dropdown-item add-to-cart add-to-cart-btn" data-href="{{ route('product.cart.add',['db' => $db, 'id' => $prod->id]) }}"><i class="icofont-cart"></i>&nbsp;&nbsp;{{ $langg->lang56 }}</span>
                                    <span class="dropdown-item add-to-cart-quick" style="width: 100%;" data-href="{{ route('product.cart.quickadd',['db' => $db, 'id' => $prod->id]) }}"><i class="icofont-dollar"></i>&nbsp;&nbsp;{{ $langg->lang251 }}</span>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </section>
    @endif
    <!-- SubCategori Area End -->
@endsection


@section('scripts')
<script>
    $(document).ready(function () {
        $('.product_table').DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "searching": false,
            "lengthMenu": [[50, 100, 150, 200, -1], [50, 100, 150, 200, "All"]]
        });
    });
</script>
@endsection