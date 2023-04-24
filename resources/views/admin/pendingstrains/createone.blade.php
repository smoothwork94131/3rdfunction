@extends('layouts.admin')

@section('styles')

@endsection

@section('content')

    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">{{ __("Add Strain") }} <a class="add-btn"
                                                                  href="{{ route('admin-strain-index') }}"><i
                                    class="fas fa-arrow-left"></i> {{ __("Back") }}</a></h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __("Dashboard") }} </a>
                        </li>
                        <li>
                            <a href="{{ route('admin-strain-index') }}">{{ __("All Strains") }}</a>
                        </li>
                        <li>
                            <a href="{{ route('admin-strain-create') }}">{{ __("Add Strain") }}</a>
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

                            <div class="gocover"
                                 style="background: url({{asset('assets/images/'.$gs->admin_loader)}}) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>

                            <form id="geniusform" action="{{route('admin-pendingstrain-store')}}" method="POST"
                                  enctype="multipart/form-data">
                                {{csrf_field()}}

                                @include('includes.admin.form-both')

                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Strain Name') }}* </h4>
                                            <p class="sub-heading">(In Any Language)</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-7">
                                        <input type="text" class="input-field" placeholder="{{ __('Enter Strain Name') }}" name="name" required="">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Strain Effect') }}* </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-7">
                                        <textarea class="form-control" name="effect" rows="5"></textarea>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="left-area">
                                            <h4 class="heading">
                                                {{ __("Strain Description") }}*
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-7">
                                        <div class="text-editor">
                                            <textarea class="form-control" name="description" rows="5"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Strain Parent') }}* </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-7">
                                        <textarea class="form-control" name="parent" rows="5"></textarea>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="left-area">

                                        </div>
                                    </div>
                                    <div class="col-lg-7 text-center">
                                        <button class="addProductSubmit-btn"
                                                type="submit">{{ __("Create Strain") }}</button>
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

@section('scripts')

@endsection