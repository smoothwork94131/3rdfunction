@extends('layouts.admin')

@section('content')
    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">{{ __("Edit Campaign Option") }} 
                        <a class="add-btn" href="{{ route('admin-campaign-detail') }}">
                            <i class="fas fa-arrow-left"></i> {{ __("Back") }}
                        </a>
                    </h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __("Dashboard") }} </a>
                        </li>
                        <li>
                            <a href="{{ route('admin-campaign-detail') }}">{{ __("Purchase Campaign") }}</a>
                        </li>
                        <li>
                            <a href="{{ route('admin-campaign-detail-edit', array('id' => $data->id)) }}">{{ __("Edit Campaign Option") }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="add-product-content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="product-description">
                        <div class="body-area">
                            <div class="gocover" style="background: url({{asset('assets/images/'.$gs->admin_loader)}}) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>
                            <form id="geniusform" action="{{route('admin-campaign-detail-update', $data->id)}}" method="POST" enctype="multipart/form-data">
                                {{csrf_field()}}
                                
                                @include('includes.admin.form-both')

                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Number of Text') }}* </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-7">
                                        <input step="1" type="number" class="input-field" placeholder="{{ __('Enter Number of Text') }}" value="{{ $data->number_texts }}" name="number_texts" required="">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Price') }}* </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-7">
                                        <input step="1" type="number" class="input-field" placeholder="{{ __('Enter Price') }}" value="{{ $data->price }}" name="price" required="">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="left-area">

                                        </div>
                                    </div>
                                    <div class="col-lg-7 text-center">
                                        <button class="addProductSubmit-btn" type="submit">{{ __("Save") }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection