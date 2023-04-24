@extends('layouts.admin')

@section('content')
    <input type="hidden" id="headerdata" value="{{ __("VENDOR") }}">
    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">{{ __("Locations") }}</h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __("Dashboard") }} </a>
                        </li>
                        <li>
                            <a href="javascript:;">{{ __("Vendors") }}</a>
                        </li>
                        <li>
                            <a href="{{ route('admin-vendor-locations') }}">{{ __("Locations") }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="product-area">
            <div class="row">
                <div class="col-lg-12">

                    <!-- <div class="heading-area">
                        <h4 class="title">
                            {{-- __("Vendor Registration") --}} :
                        </h4>
                        <div class="action-list">
                            <select class="process select1 vdroplinks {{-- $gs->reg_vendor == 1 ? 'drop-success' : 'drop-danger' --}}">
                                <option data-val="1"
                                        value="{{--route('admin-gs-regvendor',1)--}}" {{-- $gs->reg_vendor == 1 ? 'selected' : '' --}}>{{-- __("Activated") --}}</option>
                                <option data-val="0"
                                        value="{{--route('admin-gs-regvendor',0)--}}" {{-- $gs->reg_vendor == 0 ? 'selected' : '' --}}>{{-- __("Deactivated") --}}</option>
                            </select>
                        </div>
                    </div> -->


                    <div class="mr-table allproduct">
                        @include('includes.admin.form-success')
                        <div class="table-responsiv">
                            <table id="geniustable" class="table table-hover dt-responsive" cellspacing="0"
                                   width="100%">
                                <thead>
                                <tr>
                                    <th>{{ __("Store Name") }}</th>
                                    <th>{{ __("Location Name") }}</th>
                                    <th>{{ __("Address") }}</th>
                                    {{-- <!-- <th>{{ __("Status") }}</th> --> --}}
                                    <th>{{ __("Contact Info") }}</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('scripts')

    {{-- DATA TABLE --}}

    <script type="text/javascript">

        var table = $('#geniustable').DataTable({
            ordering: false,
            processing: true,
            serverSide: true,
            ajax: '{{ route('admin-vendor-locations-datatables') }}',
            columns: [
                {data: 'shop_name', name: 'shop_name'},
                {data: 'name', name: 'name'},
                {data: 'address', name: 'address'},
                {data: 'contact_info', name: 'contact_info'},
                // {data: 'status', searchable: false, orderable: false},
            ],
            language: {
                processing: '<img src="{{asset('assets/images/'.$gs->admin_loader)}}">'
            },
            drawCallback: function (settings) {
                $('.select').niceSelect();
            }
        });

        $('.select1').niceSelect();

    </script>


    <script type="text/javascript">

        $(document).on('click', '.verify', function () {
            if (admin_loader == 1) {
                $('.submit-loader').show();
            }
            $('#verify-modal .modal-content .modal-body').html('').load($(this).attr('data-href'), function (response, status, xhr) {
                if (status == "success") {
                    if (admin_loader == 1) {
                        $('.submit-loader').hide();
                    }
                }
            });
        });


    </script>

    {{-- DATA TABLE --}}

@endsection   