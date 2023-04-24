@extends('layouts.admin')

@section('content')
    <input type="hidden" id="headerdata" value="{{ __("VERIFICATION") }}">
    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">{{ __("Verification Approved") }}</h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __("Dashboard") }} </a>
                        </li>
                        <li>
                            <a href="{{ route('admin-verification-approved') }}">{{ __("Verification Approved") }}</a>
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
@endsection

@section('scripts')
    <script type="text/javascript">

        var table = $('#geniustable').DataTable({
            ordering: false,
            processing: true,
            serverSide: true,
            ajax: '{{ route('admin-verification-approve-datatables') }}',
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

        $(document).on('change', '.droplinks1', function () {
            var link = $(this).val();
            document.location.href = link;
        });
    </script>

@endsection   