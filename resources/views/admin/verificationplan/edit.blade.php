@extends('layouts.load')

@section('content')
    <div class="content-area">

        <div class="add-product-content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="product-description">
                        <div class="body-area">
                            @include('includes.admin.form-error')
                            <form id="geniusformdata" action="{{route('admin-verification-update',$data->id)}}"
                                  method="POST" enctype="multipart/form-data">
                                {{csrf_field()}}

                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __("Title") }} *</h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-7">
                                        <input type="text" class="input-field" name="title"
                                               placeholder="{{ __("Enter Verification Title") }}" required="" value="{{ $data->title }}">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __("Type") }} *</h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-7">
                                    <select name="type" required="">
                                            <option value="">{{ __("Select Type") }}</option>
                                            <option value="0" @if($data->type == 0) selected @endif>Buyer</option>
                                            <option value="1" @if($data->type == 1) selected @endif>Seller</option>
                                        </select>
                                    </div>
                                </div>

                                
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __("Cost ($)") }} *</h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-7">
                                        <input type="number" class="input-field" name="price"
                                               placeholder="{{ __("Enter Verification Cost") }}" required="" value="{{ $data->price }}">
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="left-area">
                                            <h4 class="heading">
                                                {{ __("Details") }} *
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-7">
                                        <textarea class="nic-edit" name="details"
                                                  placeholder="{{ __("Details") }}">{{ $data->details }}</textarea>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="left-area">

                                        </div>
                                    </div>
                                    <div class="col-lg-7">
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

@section('scripts')

    <script type="text/javascript">

        $("#limit").on('change', function () {
            val = $(this).val();
            if (val == 1) {
                $("#limits").show();
                $("#allowed_products").prop("required", true);
            } else {
                $("#limits").hide();
                $("#allowed_products").prop("required", false);

            }
        });

    </script>

@endsection
