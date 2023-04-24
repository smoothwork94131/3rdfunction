@extends('layouts.admin')

@section('styles')
<style>
    .nice-select:after {
        display: none;
    }
</style>
@endsection

@section('content')
    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">{{ __('Text Campaign Historys') }}</h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="javascript:;">{{ __('Messages') }}</a>
                        </li>
                        <li>
                            <a href="javascript:;">{{ __('Text Campaign History') }}</a>
                        </li>

                    </ul>
                </div>
            </div>
        </div>
        <div class="product-area">
            <div class="row">
                <div class="col-lg-12">
                    <div class="mr-table allproduct">

                        @include('includes.admin.form-success')
                        @include('includes.admin.form-error')

                        <div class="table-responsiv">
                            <table id="geniustable" class="table table-hover dt-responsive" cellspacing="0"
                                   width="100%">
                                <thead>
                                    <tr>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Email') }}</th>
                                        <th>{{ __('Number of Text') }}</th>
                                        <th>{{ __('Price') }}</th>
                                        <th>{{ __('Message') }}</th>
                                        <th>{{ __('Link') }}</th>
                                        <th>{{ __('Status') }}</th>
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
            ajax: '{{ route('admin-campaign-history-datatables') }}',
            columns: [
                {data: 'name', name: 'name'},
                {data: 'email', name: 'email'},
                {data: 'number_texts', name: 'number_texts'},
                {data: 'price', name: 'price'},
                {data: 'message', name: 'message'},
                {data: 'link', name: 'link'},
                {data: 'approved', name: 'approved'},
            ],
            language: {
                processing: '<img src="{{asset('assets/images/'.$gs->admin_loader)}}">'
            },
            drawCallback: function (settings) {
                $('.select').niceSelect();
            }
        });
    </script>
@endsection   