@extends('layouts.admin')

@section('content')
    <input type="hidden" id="headerdata" value="{{ __("VERIFICATION") }}">
    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">{{ __("Verification Pending") }}</h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __("Dashboard") }} </a>
                        </li>
                        <li>
                            <a href="{{ route('admin-verification-pending') }}">{{ __("Verification Pending") }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="product-area">
            <div class="row">
                <div class="col-lg-12">
                    <div class="mr-table allproduct">

                        @include('includes.admin.form-success-session')

                        <div class="table-responsiv">
                            <table id="geniustable" class="table table-hover dt-responsive" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>{{ __("Name") }}</th>
                                    <th>{{ __("Email") }}</th>
                                    <th>{{ __("License1") }}</th>
                                    <th>{{ __("License2") }}</th>
                                    <th>{{ __("License3") }}</th>
                                    <th>{{ __("Driver License") }}</th>
                                    <th>{{ __("Billing License") }}</th>
                                    <th>{{ __("Action") }}</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- EXPIRE date MODAL --}}

    <div class="modal fade" id="expire-modal" tabindex="-1" role="dialog" aria-labelledby="modal1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="expire-form" action="{{route('admin-verification-update-approve')}}" method="POST" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <div class="modal-header d-block text-center">
                        <h4 class="modal-title d-inline-block">{{ __("Expire Date") }}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        <input type="hidden" id="user_id" name="user_id" value="">
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="left-area">
                                    <h4 class="heading">{{ __('Expire Date') }}* </h4>
                                </div>
                            </div>
                            <div class="col-lg-7">
                                <input type="text" class="input-field" placeholder="{{ __('Expire Date') }}" name="expire_date" id="expire_date" required="">
                            </div>
                        </div>
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-default" data-dismiss="modal">{{ __("Cancel") }}</button>
                        <button type="submit" class="btn btn-danger">{{ __("Save") }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- EXPIRE MODAL ENDS --}}
@endsection

@section('scripts')
    

    {{-- DATA TABLE --}}

    <script type="text/javascript">

        $('#expire_date').datepicker({
            format: 'mm/dd/yyyy',
            autoclose: true
        });

        var table = $('#geniustable').DataTable({
            ordering: false,
            processing: true,
            serverSide: true,
            ajax: '{{ route('admin-verification-pending-datatables') }}',
            columns: [
                {data: 'name', name: 'name'},
                {data: 'email', name: 'email'},
                {data: 'license1', name: 'license1'},
                {data: 'license2', name: 'license2'},
                {data: 'license3', name: 'license3'},
                {data: 'driver_license', name: 'driver_license'},
                {data: 'bill_license', name: 'bill_license'},
                {data: 'action', searchable: false, orderable: false}
            ],
            language: {
                processing: '<img src="{{asset('assets/images/spinner.gif')}}">'
            },
            drawCallback: function (settings) {
                $('.select').niceSelect();
            }
        });

        $(document).on('click', '.update-approve', function () {
            var id = $(this).data('id');
            $("#user_id").val(id);
            $("#expire_date").val("");
            $("#expire-modal").modal();
            // document.location.href = link;
        });
    </script>

@endsection   