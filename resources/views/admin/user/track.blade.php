@extends('layouts.admin')

@section('styles')
<style>
    .nice-select:after {
        display: none;
    }
</style>
@endsection

@section('content')
    <input type="hidden" id="headerdata" value="{{ __("CUSTOMER") }}">
    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">{{ __("Customers Tracks") }}</h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __("Dashboard") }} </a>
                        </li>
                        <li>
                            <a href="{{ route('admin-user-track', $flag) }}">{{ __("Customers Tracks") }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="product-area">
            <div class="row">
                <div class="col-lg-12">
                    <div class="mr-table allproduct">
                        <div class="table-responsiv">
                            <table id="geniustable" class="table table-hover dt-responsive" cellspacing="0"
                                   width="100%">
                                <thead>
                                <tr>
                                    <th>{{ __("User") }}</th>
                                    <th>{{ __("Category") }}</th>
                                    <th>{{ __("Product") }}</th>
                                    <th>{{ __("Search Term") }}</th>
                                    <th>{{ __("Date") }}</th>
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

    {{-- DATA TABLE --}}

    <script type="text/javascript">

        var table = $('#geniustable').DataTable({
            ordering: false,
            processing: true,
            serverSide: true,
            ajax: '{{ route('admin-user-trackdatatables', ['flag' => $flag]) }}',
            columns: [
                {data: 'user', name: 'user'},
                {data: 'category', name: 'category'},
                {data: 'product', name: 'product'},
                {data: 'search_term', name: 'search_term'},
                {data: 'date', name: 'date'},
                {data: 'action', name: 'action'}

            ],
            language: {
                processing: '<img src="{{asset('assets/images/'.$gs->admin_loader)}}">'
            },
            drawCallback: function (settings) {
                $('.select').niceSelect();
            }
        });

        $(".btn-area").append(
            '<div class="col-sm-4 table-contents">' +
                '<select class="form-control" id="action_kind">' +
                    '<option value="all" data-href="{{ route('admin-user-track', 'all') }}" {{ $flag == 'all'? 'selected' : '' }}>All</option>'+
                    '<option value="view" data-href="{{ route('admin-user-track', 'view') }}" {{ $flag == 'view'? 'selected' : '' }}>Cart View</option>'+
                    '<option value="quick_view"  data-href="{{ route('admin-user-track', 'quick_view') }}" {{ $flag == 'quick_view'? 'selected' : '' }}>Quick View</option>'+
                    '<option value="compare" data-href="{{ route('admin-user-track', 'compare') }}" {{ $flag == 'compare'? 'selected' : '' }}>Compare</option>'+
                    '<option value="search" data-href="{{ route('admin-user-track', 'search') }}" {{ $flag == 'search'? 'selected' : '' }}>Search</option>'+
                    '<option value="menu_category" data-href="{{ route('admin-user-track', 'menu_category') }}" {{ $flag == 'menu_category'? 'selected' : '' }}>Category Menu</option>'+
                '</select>'+
            '</div>'
        );

        $("#action_kind").on('change', function() {
            var flag = $(this).val();
            var href = $(this).find(':selected').attr('data-href');
            document.location.href = href;
        })

    </script>

    {{-- DATA TABLE --}}

@endsection   