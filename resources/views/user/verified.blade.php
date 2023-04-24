@extends('layouts.front')

@section('content')
    <section class="user-dashbord">
        <div class="container">
            <div class="row">
                @include('includes.user-dashboard-sidebar')
                <div class="col-lg-8">
                    @include('includes.form-success')
                    <div class="user-profile-details">
                        <div class="account-info">
                            <div class="header-area">
                                <h4 class="title">
                                    License Verification
                                </h4>
                            </div>
                            <div class="edit-info-area">
                                <div class="body">
                                    <div class="edit-info-area-form">
                                        <div class="gocover" style="background: url({{ asset('assets/images/'.$gs->loader) }}) no-repeat scroll center center rgba(45, 45, 45, 0.5);">
                                        </div>
                                        <form id="verifiedform" action="{{route('user-verified-store')}}" method="POST" enctype="multipart/form-data">
                                            {{ csrf_field() }}

                                            @include('includes.admin.form-both')

                                            <p class="verified-form-head">Verification of your cannabis licensing enables you to buy and sell licensed products to other licensed entities. You will also be able to limit the viewing of your products to only be displayed to other verified OG.Lifers.</p>

                                            @if(!empty($license_data) && $license_data->license1 != null && $license_data->license2 != null)
                                            <input type="hidden" id="license_id" name="license_id" value="{{ $license_data->id }}">
                                            @endif

                                            <!-- this is my verified page -->
                                            @if(!empty($license_data) && $license_data->license1 != null && $license_data->license2 != null)
                                            <input type="hidden" id="license_count" name="license_count" value="3">
                                            @elseif(!empty($license_data) && $license_data->license2 != null)
                                            <input type="hidden" id="license_count" name="license_count" value="2">
                                            @else
                                            <input type="hidden" id="license_count" name="license_count" value="1">
                                            @endif

                                            <div id="normal-license-wrapper">
                                                <div class="license_container">
                                                    <label>License Image 1</label>
                                                    <div class="upload-img" style="justify-content: space-between; padding: 0px 0px 20px;">
                                                        <div class="verified-img">
                                                            @if(!empty($license_data) && $license_data->license1 != null)   
                                                            <img src="{{ asset('assets/images/license/'.$license_data->license1) }}">
                                                            @else
                                                            <img src="">
                                                            @endif
                                                        </div>
                                                        <div>
                                                            <div class="verified-upload-file">
                                                                <input type="file" name="normal_license1" id="normal_license1" class="upload">
                                                                <span class="verified_btn">{{ $langg->lang263 }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @if(!empty($license_data) && $license_data->license2 != null)
                                                <div class="license_container">
                                                    <label>License Image 2</label>
                                                    <div class="upload-img" style="justify-content: space-between; padding: 0px 0px 20px;">
                                                        <div class="verified-img">
                                                            <img src="{{ asset('assets/images/license/'.$license_data->license2) }}">
                                                        </div>
                                                        <div>
                                                            <div class="verified-upload-file mb-2">
                                                                <input type="file" name="normal_license2" id="normal_license2" class="upload">
                                                                <span class="verified_btn">{{ $langg->lang263 }}</span>
                                                            </div>
                                                            <button class="btn btn-danger del_license" type="button" onclick="del_license(this)" style="width: 100%;">
                                                                <i class="fas fa-trash"></i>
                                                                'Delete'
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                                @if(!empty($license_data) && $license_data->license3 != null)
                                                <div class="license_container">
                                                    <label>License Image 3</label>
                                                    <div class="upload-img" style="justify-content: space-between; padding: 0px 0px 20px;">
                                                        <div class="verified-img">
                                                            <img src="{{ asset('assets/images/license/'.$license_data->license3) }}">
                                                        </div>
                                                        <div>
                                                            <div class="verified-upload-file mb-2">
                                                                <input type="file" name="normal_license3" id="normal_license3" class="upload">
                                                                <span class="verified_btn">{{ $langg->lang263 }}</span>
                                                            </div>
                                                            <button class="btn btn-danger del_license" type="button" onclick="del_license(this)" style="width: 100%;">
                                                                <i class="fas fa-trash"></i>
                                                                'Delete'
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                            </div>

                                            <div id="add_license_wrapper" class="mb-3">
                                                @if(!empty($license_data) && $license_data->license1 != null && $license_data->license2 != null)
                                                <button class="btn btn-primary" id="add_license" type="button" disabled>
                                                    <i class="fas fa-plus"></i>
                                                    Add License
                                                </button>
                                                @else
                                                <button class="btn btn-primary" id="add_license" type="button">
                                                    <i class="fas fa-plus"></i>
                                                    Add License
                                                </button>
                                                @endif
                                                
                                            </div>

                                            <div id="driver-license-wrapper">
                                                <label>Driver License</label>
                                                <div class="upload-img" style="justify-content: space-between; padding: 0px 0px 20px;">
                                                    <div class="verified-img">
                                                        @if(!empty($license_data) && $license_data->driver_license != null)      
                                                        <img src="{{ asset('assets/images/license/'.$license_data->driver_license) }}">
                                                        @else
                                                        <img src="">
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <div class="verified-upload-file">
                                                            <input type="file" name="driver_license" class="upload">
                                                            <span class="verified_btn">{{ $langg->lang263 }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div id="utility-bill-wrapper">
                                                <label>Utility Bill</label>
                                                <div class="upload-img" style="justify-content: space-between; padding: 0px 0px 20px;">
                                                    <div class="verified-img">   
                                                        @if(!empty($license_data) && $license_data->bill_license != null)      
                                                        <img src="{{ asset('assets/images/license/'.$license_data->bill_license) }}">
                                                        @else
                                                        <img src="">
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <div class="verified-upload-file">
                                                            <input type="file" name="bill_license" class="upload">
                                                            <span class="verified_btn">{{ $langg->lang263 }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-links">
                                                <button class="submit-btn" type="submit">{{ $langg->lang271 }}</button>
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
    </section>
@endsection
@section('scripts')
<script>
    function del_license(obj) {
        var container_obj = $(obj).parents('.license_container');
        $(container_obj).remove();
    }

    function upload_trigger(obj) {
        var file_obj = $(obj).parent().find('.upload');
        $(file_obj).click();
    }

    function upload_change(obj) {
        var imgpath = $(obj).parent().parent().prev().find('img');
        var file = $(obj);
        readURL(obj, imgpath);
    }

    function readURL(input, imgpath) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                imgpath.attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }


    $(document).ready(function() {
        var license_count = $("#license_count").val();
        
        $('.verified_btn').on('click', function(e) {
            var file_obj = $(this).parent().find('.upload');
            $(file_obj).click();
        });

        $("#add_license").on('click', function() {
            if(license_count == 2) {
                $(this).prop('disabled', true);
            }
            
            license_count++;

            var html = '<div class="license_container">'+
                        '<label>License Image '+ license_count +'</label>'+
                        '<div class="upload-img" style="justify-content: space-between; padding: 0px 0px 20px;">'+
                            '<div class="verified-img">'+
                                '<img src="">'+
                            '</div>'+
                            '<div>'+
                                '<div class="verified-upload-file mb-2">'+
                                    '<input type="file" name="normal_license'+ license_count +'" id="normal_license'+ license_count +'" class="upload" onchange="upload_change(this)">'+
                                    '<span class="verified_btn" onclick="upload_trigger(this)">Upload</span>'+
                                '</div>'+
                                '<button class="btn btn-danger del_license" type="button" onclick="del_license(this)" style="width: 100%;">' +
                                    '<i class="fas fa-trash"></i>' +
                                    'Delete'
                                '</button>' +
                            '</div>'+
                        '</div>' +
                    '</div>';

            $("#normal-license-wrapper").append(html);
        });
    })

</script>
@endsection