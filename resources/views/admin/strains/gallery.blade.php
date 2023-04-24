@extends('layouts.admin')

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{asset('assets/admin/css/jquery.Jcrop.min.css')}}">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.2/croppie.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css"
          integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">
@endsection
@section('content')

    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">{{ __("Strain Gallery") }} <a class="add-btn"
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
                            <a href="{{ route('admin-strain-gallery', array('id'=>$strain_id)) }}">{{ __("Import CSV Product") }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="add-product-content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="product-description" id="strain_gallery">
                        @include('includes.admin.form-success-session')
                        @include('includes.admin.form-error-session')
                        <div class="body-area">
                            <div class="top-area">
                                <div class="row">
                                    <div class="col-sm-12 text-center">
                                        <div class="upload-img-btn">
                                            <form method="POST" id="form-gallery">
                                                {{ csrf_field() }}
                                                <input type="hidden" name="strain_id" id="strain_id"
                                                       value="{{ $strain_id }}">
                                                <input type="file" name="gallery" class="hidden" id="uploadgallery"
                                                       accept="image/*" multiple>
                                                <label for="image-upload" id="prod_gallery"><i
                                                            class="icofont-upload-alt"></i>{{ __("Upload File") }}
                                                </label>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 text-center">(
                                        <small>{{ __("You can upload multiple Images.") }}</small> )
                                    </div>
                                    <div class="col-sm-12 text-center">
                                        <div class="submit-loader" id="spinner_loader">
                                            <img src="{{asset('assets/images/spinner.gif')}}" alt="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="gallery-images">
                                <div class="selected-image">
                                    @if(count($data) == 0)
                                        <div class="row justify-content-center">
                                            <h3>{{ __("No Images Found.") }}</h3>
                                        </div>
                                    @else
                                        <div class="row">
                                            @foreach($data as $key=>$val)
                                                <div class="col-sm-2 mb-3">
                                                    <div class="img gallery-img">
                                                        
                                                        <span class="remove-img">
                                                            <a href="{{ route('admin-strain-gallery-remove', $val->id) }}">
                                                                <i class="fas fa-times"></i>
                                                                <input type="hidden" value="{{ $val->id }}">
                                                            </a>
                                                        </span>
                                                        <a href="{{asset($val->path)}}" id="href_id_{{ $val->id }}" target="_blank">
                                                            <img src="{{asset($val->path)}}" id="image_id_{{ $val->id }}" alt="gallery image">
                                                        </a>
                                                    </div>
                                                    <div class="custom-control custom-radio text-center">
                                                        @if($val->index == 1)
                                                            <input type="radio" class="custom-control-input"
                                                                   id="main_logo_{{$val->id}}" name="main_logo"
                                                                   value="{{ $val->id }}" checked>
                                                        @else
                                                            <input type="radio" class="custom-control-input"
                                                                   id="main_logo_{{$val->id}}" name="main_logo"
                                                                   value="{{ $val->id }}">
                                                        @endif
                                                        <label class="custom-control-label"
                                                               for="main_logo_{{$val->id}}">Strain Main Gallery</label>
                                                    </div>
                                                </div>
                                            @endforeach
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

    <div class="modal" id="myModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Crop Image And Upload</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <!-- Modal body -->
                <div class="modal-body">
                    <div id="resizer" class="text-center"></div>
                    <button class="btn rotate float-lef" data-deg="90">
                        <i class="fas fa-undo"></i></button>
                    <button class="btn rotate float-right" data-deg="-90">
                        <i class="fas fa-redo"></i></button>
                    <hr>
                    <button class="btn btn-block btn-dark" id="upload">
                        Crop And Upload
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        var base_url = "{{ url('/') }}";
        var croppie = null;
        var el = document.getElementById('resizer');

        $("#spinner_loader").css('display', 'none');

        $(document).on('click', '#prod_gallery', function () {
            $('#uploadgallery').click();
        });

        $.base64ImageToBlob = function (str) {
            // extract content type and base64 payload from original string
            var pos = str.indexOf(';base64,');
            var type = str.substring(5, pos);
            var b64 = str.substr(pos + 8);

            // decode base64
            var imageContent = atob(b64);

            // create an ArrayBuffer and a view (as unsigned 8-bit)
            var buffer = new ArrayBuffer(imageContent.length);
            var view = new Uint8Array(buffer);

            // fill the view, using the decoded base64
            for (var n = 0; n < imageContent.length; n++) {
                view[n] = imageContent.charCodeAt(n);
            }

            // convert ArrayBuffer to Blob
            var blob = new Blob([buffer], {type: type});

            return blob;
        }

        $.getImage = function (input, croppie) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    croppie.bind({
                        url: e.target.result,
                    });
                }
                reader.readAsDataURL(input.files[0]);
            }
        }


        $("#uploadgallery").change(function () {
            
            $("#myModal").modal();

            croppie = new Croppie(el, {
                viewport: {
                    width: 300,
                    height: 300,
                    type: 'rectangle'
                },
                boundary: {
                    width: 350,
                    height: 350
                },
                enableOrientation: true
            });
            $.getImage(event.target, croppie);

            // $("#form-gallery").submit();
        });

        $("#upload").on("click", function () {
            croppie.result('base64').then(function (base64) {
                $("#myModal").modal("hide");
                $("#spinner_loader").css('display', 'block');

                let myForm = document.getElementById('form-gallery');
                var formData = new FormData(myForm);
                formData.append("profile_picture", $.base64ImageToBlob(base64));

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: "{{ route('admin-strain-gallery-upload') }}",
                    method: "POST",
                    data: formData,
                    dataType: 'JSON',
                    // contentType: 'multipart/form-data',
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
                        if(data) {
                            location.reload();
                        }
                    }
                });
            });
        });

        // To Rotate Image Left or Right
        $(".rotate").on("click", function () {
            croppie.rotate(parseInt($(this).data('deg')));
        });

        $('#myModal').on('hidden.bs.modal', function (e) {
            setTimeout(function () {
                croppie.destroy();
            }, 100);
        })

        $("[id^='main_logo_']").on('click', function () {
            var gallery_id = $(this).val();
            var strain_id = $("#strain_id").val();

            $.ajax({
                type: "post",
                url: "{{ route('admin-strain-gallery-logo') }}",
                data: {
                    _token: $("[name='_token']").val(),
                    gallery_id: gallery_id,
                    strain_id: strain_id
                },
                dataType: 'json',
                success: function (response) {
                    if (response && response.message == 'success') {

                        console.log(response);

                        var exist_id = response.exist_id;
                        var gallery_id = response.gallery_id;
                        var exist_path = response.exist_path;
                        var new_path = response.new_path;

                        $("href_id_" + exist_id).attr('href', base_url + '/' + exist_path);
                        $("image_id_" + exist_id).attr('src', base_url + '/' + exist_path);

                        $("href_id_" + gallery_id).attr('href', base_url + '/' + new_path);
                        $("image_id_" + gallery_id).attr('src', base_url + '/' + new_path);

                        // document.location.reload(true);
                    }
                }
            });
        });
    </script>

    <script src="{{asset('assets/admin/js/jquery.Jcrop.min.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
            integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
            crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.2/croppie.min.js"></script>
@endsection