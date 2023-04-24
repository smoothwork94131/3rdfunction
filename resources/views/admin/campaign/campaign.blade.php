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
                    <h4 class="heading">{{ __('Text Campaigns') }}</h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="javascript:;">{{ __('Messages') }}</a>
                        </li>
                        <li>
                            <a href="javascript:;">{{ __('Text Campaign') }}</a>
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
                                        <th>{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- DELETE MODAL --}}

    <div class="modal fade" id="approve_modal" tabindex="-1" role="dialog" aria-labelledby="modal1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="approveform" action="{{route('admin-campaign-approve')}}" method="POST">
                    {{csrf_field()}}
                    <input type="hidden" id="approve_id" name="approve_id" value="">
                    
                    <div class="modal-header d-block text-center">
                        <h4 class="modal-title d-inline-block">{{ __("Confirm Approved") }}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                
                    <!-- Modal body -->
                    <div class="modal-body">
                        <p class="text-center">{{ __("You are about to approve this text Campaign.") }}</p>
                        <p class="text-center">{{ __("Do you want to proceed?") }}</p>
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-default" data-dismiss="modal">{{ __("Cancel") }}</button>
                        <button type="submit" class="btn btn-danger btn-ok">{{ __("Approved") }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- DELETE MODAL ENDS --}}


    {{-- GALLERY MODAL --}}

    <div class="modal fade" id="deny_modal" tabindex="-1" role="dialog" aria-labelledby="modal1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="approveform" action="{{route('admin-campaign-deny')}}" method="POST">
                    {{csrf_field()}}
                    <input type="hidden" id="deny_id" name="deny_id" value="">
                    
                    <div class="modal-header d-block text-center">
                        <h4 class="modal-title d-inline-block">{{ __("Send Message") }}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                
                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <h4 class="heading">{{ __('Message') }}* </h4>
                                <input type="text" class="input-field" placeholder="{{ __('Enter Message') }}" name="message" required="">
                            </div>
                        </div>
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-default" data-dismiss="modal">{{ __("Cancel") }}</button>
                        <button type="submit" class="btn btn-danger btn-ok">{{ __("Deny") }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- GALLERY MODAL ENDS --}}
@endsection



@section('scripts')

    <script type="text/javascript">

        var table = $('#geniustable').DataTable({
            ordering: false,
            processing: true,
            serverSide: true,
            ajax: '{{ route('admin-campaign-datatables') }}',
            columns: [
                {data: 'name', name: 'name'},
                {data: 'email', name: 'email'},
                {data: 'number_texts', name: 'number_texts'},
                {data: 'price', name: 'price'},
                {data: 'message', name: 'message'},
                {data: 'link', name: 'link'},
                {data: 'action', searchable: false, orderable: false}

            ],
            language: {
                processing: '<img src="{{asset('assets/images/'.$gs->admin_loader)}}">'
            },
            drawCallback: function (settings) {
                $('.select').niceSelect();
            }
        });

        
        $(document).on("click", ".approved_link", function () {

            var flag = $(this).data('flag')
            var id = $(this).data('id');
            
            $("#approve_id").val(id);
            $("#approve_modal").modal();
        });

        $(document).on("click", ".deny_link", function () {
            var flag = $(this).data('flag')
            var id = $(this).data('id');

            $("#deny_id").val(id);
            $("#deny_modal").modal();
        });

    </script>


@endsection   