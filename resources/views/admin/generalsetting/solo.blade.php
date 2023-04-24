@extends('layouts.admin')

@section('content')
    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">{{ __('Site Mode') }}</h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="javascript:;">{{ __('General Settings') }}</a>
                        </li>
                        <li>
                            <a href="{{ route('admin-gs-solo') }}">{{ __('Site Mode') }}</a>
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
                            <form action="{{ route('admin-gs-solo-save') }}" id="geniusform" method="POST" enctype="multipart/form-data">
                                {{ csrf_field() }}

                                @include('includes.admin.form-both')


                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading">
                                                {{ __('Site Mode') }}
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="action-list">
                                            <select data-menu="solo" name="solo_mode" class="process select droplinks @if($gs->solo_mode == 0) drop-danger @elseif($gs->solo_mode == 1) drop-warning @else drop-success @endif">
                                                <option data-val="0" value="0" {{ $gs->solo_mode == 0 ? 'selected' : '' }}>{{ __('Normal Mode') }}</option>
                                                <option data-val="1" value="1" {{ $gs->solo_mode == 1 ? 'selected' : '' }}>{{ __('Solo Mode') }}</option>
                                                <option data-val="2" value="2" {{ $gs->solo_mode == 2 ? 'selected' : '' }}>{{ __('Smart Mode') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row justify-content-center"  id="submit_area" style="display: {{ $gs->solo_mode == 1? 'flex': 'none' }}">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __("Category") }}*</h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <select name="category" id="category" @if($gs->solo_mode == 1) required="" @endif>
                                            <option value="">{{ __("Select Category") }}</option>
                                            @foreach($cats as $cat)
                                                @if($gs->solo_mode == 1)
                                                    <option value="{{ $cat->id }}" {{ $gs->solo_category == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                                @else
                                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">

                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <button class="addProductSubmit-btn" type="submit">{{ __('Save') }}</button>
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

<script type="text/javascript">
    $(".droplinks").on('change', function() {
        var solo_mode = $(this).find(':selected').attr('data-val');
        if(solo_mode == 1) {
            $("#submit_area").show();
            $("#category").attr("required", "");
        }
        else {
            $("#category").attr("required", false);
            $("#submit_area").hide();
        }
    })
</script>
@endsection   