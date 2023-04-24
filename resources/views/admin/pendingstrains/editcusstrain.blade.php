@extends('layouts.admin')
@section('styles')

@endsection
@section('content')
    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">{{ __("Edit Strain") }} <a class="add-btn"
                                                                   href="{{ route('admin-pendingstrain-index') }}"><i
                                    class="fas fa-arrow-left"></i> {{ __("Back") }}</a></h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __("Dashboard") }} </a>
                        </li>
                        <li>
                            <a href="{{ route('admin-pendingstrain-index') }}">{{ __("Pending Strains") }}</a>
                        </li>
                        <li>
                            <a href="{{ route('admin-pendingstrain-cusaccept', array('id' => $data->id)) }}">{{ __("Accept Strain") }}</a>
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
                            <form id="geniusform" action="{{route('admin-pendingstrain-cusstore')}}" method="POST"
                                  enctype="multipart/form-data">
                                {{csrf_field()}}
                                @include('includes.admin.form-both')

                                @if($data->strain_id)
                                    <input type="hidden" name="strain_id" value="{{ $data->strain_id }}">
                                @endif
                                <input type="hidden" name="pending_strain_id" value="{{ $data->id }}">

                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Strain Name') }}* </h4>
                                            <p class="sub-heading">(In Any Language)</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-7">
                                        <input type="text" class="input-field"
                                               placeholder="{{ __('Enter Strain Name') }}" name="name" required=""
                                               value="{{ $data->name }}">
                                        @if($data->strain_id)
                                        <p class="text text-info">
                                            <strong>Default Name: </strong>{{$strain->name}}
                                        <p>
                                        @endif
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Strain Effect') }}* </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-7">
                                        <textarea class="form-control" name="effect" rows="5">{{ $data->effect }}</textarea>
                                        @if($data->strain_id)
                                        <p class="text text-info">
                                            <strong>Default Effect: </strong>{{$strain->effect}}
                                        <p>
                                        @endif
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
                                            <textarea name="description" class="form-control" rows="5">{{$data->description}}</textarea>
                                            @if($data->strain_id)
                                            <p class="text text-info">
                                                <strong>Default Description: </strong>{{$strain->description}}
                                            <p>
                                            @endif
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
                                        <textarea class="form-control" name="parent" rows="5">{{ $data->parent }}</textarea>
                                        @if($data->strain_id)
                                        <p class="text text-info">
                                            <strong>Default Parent: </strong>{{$strain->parent}}
                                        <p>
                                        @endif
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Strain Galleries') }}* </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-7">
                                        <div class="row">
                                            @foreach($data->galleries as $key=>$val)
                                                <div class="col-sm-2 mb-3">
                                                    <div class="img gallery-img">
                                                        <span class="remove-img"><i class="fas fa-times"></i>
                                                            <input type="hidden" value="{{ $val->id }}">
                                                        </span>
                                                        <a href="{{asset($val->path)}}" id="href_id_{{ $val->id }}"
                                                           target="_blank">
                                                            <img src="{{asset($val->path)}}"
                                                                 id="image_id_{{ $val->id }}" alt="gallery image">
                                                        </a>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        @if($data->strain_id)
                                        <p class="text text-info">
                                            <strong>Default Galleries: </strong>
                                        <p>
                                            @if(count($strain->galleries) == 0)
                                                <div class="row justify-content-center">
                                                    <h3>{{ __("No Images Found.") }}</h3>
                                                </div>
                                            @else
                                                @foreach($strain->galleries as $key=>$val)
                                                    <div class="col-sm-2 mb-3">
                                                        <div class="img gallery-img">
                                                            <a href="{{asset($val->path)}}" id="href_id_{{ $val->id }}"
                                                            target="_blank">
                                                                <img src="{{asset($val->path)}}"
                                                                    id="image_id_{{ $val->id }}" alt="gallery image">
                                                            </a>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                        @endif
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="left-area">

                                        </div>
                                    </div>
                                    <div class="col-lg-7 text-center">
                                        <button class="addProductSubmit-btn" type="submit">{{ __("Accept") }}</button>
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
<script>
 $(document).on('click', '.remove-img', function () {
            var id = $(this).find('input[type=hidden]').val();
            $(this).parent().parent().remove();

            $.ajax({
                type: "post",
                url: "{{ route('admin-pendingstrain-gallery-remove') }}",
                data: {
                    _token: $("[name='_token']").val(),
                    gallery_id: id,
                },
                success: function (response) {
                    if (response == 'success') {
                        document.location.reload(true);
                    }
                }
            });
        });
</script>
@endsection