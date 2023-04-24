@extends('layouts.admin')

@section('content')
    <input type="hidden" id="headerdata" value="{{ __("STRAIN") }}">
    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">{{ __("STRAINS") }}</h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __("Dashboard") }} </a>
                        </li>
                        <li>
                            <a href="{{ route('admin-strain-index') }}">{{ __("Strain") }}</a>
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
                        @include('includes.admin.form-error-session')

                        <div class="table-responsiv">
                            <table id="geniustable" class="table table-hover dt-responsive" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>{{ __("Name") }}</th>
                                    <th>{{ __("THUMBNAILS") }}</th>
                                    <th>{{ __("EFFECT") }}</th>
                                    <th>{{ __("DESCRIPTION") }}</th>
                                    <th>{{ __("PARENT") }}</th>
                                    <th>{{ __("ACTION") }}</th>
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

    <div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="modal1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header d-block text-center">
                    <h4 class="modal-title d-inline-block">{{ __("Confirm Delete") }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <p class="text-center">{{ __("You are about to delete this Product") }}.</p>
                    <p class="text-center">{{ __("Do you want to proceed?") }}</p>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ __("Cancel") }}</button>
                    <a class="btn btn-danger btn-ok">{{ __("Delete") }}</a>
                </div>
            </div>
        </div>
    </div>

    {{-- DELETE MODAL ENDS --}}


    {{-- GALLERY MODAL --}}


    {{-- GALLERY MODAL ENDS --}}
@endsection

@section('scripts')
    <script type="text/javascript">
        var table = $('#geniustable').DataTable({
            ordering: true,
            processing: true,
            serverSide: true,
            ajax: '{{ route('admin-strain-datatables') }}',
            columns: [
                {data: 'name', name: 'name'},
                {data: 'thumb', name: 'thumb'},
                {data: 'effect', name: 'effect'},
                {data: 'description', name: 'description'},
                {data: 'parent', name: 'parent'},
                {data: 'action', searchable: false, orderable: false}
            ],
            language: {
                processing: '<img src="{{asset('assets/images/'.$gs->admin_loader)}}">'
            },
            drawCallback: function (settings) {
                $('.select').niceSelect();
            }
        });

        $(function () {
            $(".btn-area").append(
                '<div class="col-sm-2 table-contents">' +
                    '<form id="form_excel" name="form_excel" method="POST">'+
                        '<input type="file" id="excel" name="excel" style="display:none;" accept=".xls,.xlsx">'+
                    '</form>'+
                    '<a href="javascript:void(0)" class="add-btn" id="upload_trigger">' +
                        '<i class="fas fa-upload"></i> <span class="remove-mobile">{{ __("Import from Excel") }}<span>' +
                    '</a>' +
                '</div>' +
                '<div class="col-sm-2 table-contents">' +
                    '<a class="add-btn" href="{{route('admin-strain-create')}}">' +
                    '<i class="fas fa-plus"></i> <span class="remove-mobile">{{ __("Add New Strain") }}<span>' +
                    '</a>' +
                '</div>'
            );

            $("#upload_trigger").on('click', function() {
                $('#excel').click();
            });

            $("#excel").on('change', function(event) {
                var file = event.target.files;
                let myForm = document.getElementById('form_excel');
                var formData = new FormData(myForm);
                formData.append("excel_file", file[0]);

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: "{{ route('admin-strain-excel-upload') }}",
                    method: "POST",
                    data: formData,
                    dataType: 'JSON',
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
                        if(data.status == 'success') {
                            document.location.href = data.url;
                        }
                        else {
                            var message = data.message;
                            toastr.success(message);
                        }
                    }
                });
            })
        });

        
    </script>

@endsection   