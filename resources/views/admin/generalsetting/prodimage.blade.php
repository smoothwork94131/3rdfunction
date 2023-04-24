@extends('layouts.admin')
@section('content')


    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">{{ __('Product Default Image') }}</h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="javascript:;">{{ __('General Settings') }}</a>
                        </li>
                        <li>
                            <a href="{{ route('admin-gs-prodimage') }}">{{ __('Product Default Image') }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="add-logo-area">
            <div class="gocover"
                 style="background: url({{asset('assets/images/'.$gs->admin_loader)}}) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>
            <div class="row justify-content-center">
                <div class="col-lg-6">

                    @include('includes.admin.form-both')

                    <form class="uplogo-form" id="geniusform" action="{{ route('admin-gs-update') }}" method="POST"
                          enctype="multipart/form-data">
                        {{csrf_field()}}
                        <div class="currrent-logo">
                            <h4 class="title">
                                {{ __('Current Product Default Image') }} :
                            </h4>
                            <img src="{{ $gs->prod_image ? asset('assets/images/products/'.$gs->prod_image):asset('assets/images/noimage.png')}}"
                                 alt="">
                        </div>
                        <div class="set-logo">
                            <h4 class="title">
                                {{ __('Set New Product Default Image') }} :
                            </h4>
                            <input class="img-upload1" type="file" name="prodimage">
                        </div>
                        <div class="submit-area">
                            <button type="submit" class="submit-btn">{{ __('Save') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection