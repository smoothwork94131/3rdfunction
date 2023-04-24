@extends('layouts.admin')
@section('styles')

<link href="{{asset('assets/admin/css/jsTree.min.css')}}" rel="stylesheet" />
<link href="{{asset('assets/admin/css/product.css')}}" rel="stylesheet" />

@endsection
@section('content')

<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading">
                    <a class="add-btn" href="{{ route('admin-prod-index') }}"><i class="fas fa-arrow-left"></i> {{ __('Back') }}</a>
                </h4>
                <ul class="links">
                    <li>
                        <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                    </li>
                    <li>
                        <a href="javascript:;">{{ __('Products') }} </a>
                    </li>
                    <li>
                        <a href="{{ route('admin-prod-index') }}">{{ __('All Products') }}</a>
                    </li>
                    <li>
                        <a href="{{ route('admin-prod-existing') }}">{{ __('Add Existing Product') }}</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="add-product-content">
        <div class="row">
            <div class="col-lg-4">
                <div class="tree_container">
                    <div class="tree_title">Category</div>
                    <div id="jstree"></div>    
                </div>
            </div>
            <div class="col-lg-8">
                <div class="table_container">
                    <div class="table_header">
                        <div class="table_title">Parts List</div>
                        <div class="category_container">
                            <label>Select Home Category: </label>
                            <select class="form-control" id="homecategory_list">
                                <option></option>
                                @foreach ($homecategories as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="table_content">
                        <table id="product_table" class="table product_table" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>NO</th>
                                    <th>Thumbnail</th>
                                    <th>Name</th>
                                    <th>Price</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')

<script src="{{asset('assets/admin/js/jstree.min.js')}}"></script>
<script type="text/javascript">
    $("#jstree").jstree({
        "core": {
            'data': {
                'type': 'POST',
                'dataType':'json',
                'url': function(node) {
                    return "{{ route('admin-prod-existing_category') }}"; 
                },
                'data': function(node) {
                    var param  = Object.keys(node).includes("original")?node.original.param : '';
                    return {
                        '_token': '{{ csrf_token() }}',
                        'parent': node.id,
                        'param': param
                    };
                },
                'success': function (data) {
                    // console.log(data);
                }
            }
        }
    });

    $('#jstree').on("changed.jstree", function (e, data) {
        var type = Object.keys(data.node).includes("original") ? data.node.original.param : ''

        if(type == '' || type != 'group') {
            return;
        }
        else {
            $.ajax({
                'type': 'POST',
                'url': "{{ route('admin-prod-existing_category') }}",
                'data' : {
                    '_token': '{{ csrf_token() }}',
                    'parent': data.node.id,
                    'param': type
                },
                'success': function(response) {
                    product_table.clear();
                    product_table.rows.add(response);
                    product_table.draw();
                }
            });
        }
    });

    var product_table = $('.product_table').DataTable({
        "paging": false,
        "ordering": false,
        "info": false,
        "searching": false,
        'columns': [
            { 'data': 'top' },
            { 
                'data': 'thumbnail',
                'render': function(data, type, row, meta) {
                    var img = data ? "{{ asset('assets/images/thumbnails') }}" + "/" + data : "{{ asset('assets/images/noimage.png') }}";
                    return '<img style="width:50px; height: 50px;" src="' + img + '" alt="Image">';
                }
            },
            { 'data': 'name' },
            { 'data': 'price' },
            { 'data': 'id' }
        ],
        "fnCreatedRow": function(nRow, aData, iDataIndex ) {
            var data = JSON.stringify(aData);
            var html = `
                <button class='add-btn' onclick='attachProduct(${data})'>Attach</button>
            `;
            $('td:eq(4)', nRow).html(html);
        },
        "lengthMenu": [[50, 100, 150, 200, -1], [50, 100, 150, 200, "All"]]
    });

    var attachProduct = (data) => {
        var category_id = $("#homecategory_list").val();
        console.log(category_id);
        if(!category_id || category_id == "") {
            toastr.warning("Please select the home category.");
            return;
        }

        if(data.photo == "" || data.thumbnail == "") {
            toastr.warning("No Image for this product.");
            return
        }

        $.ajax({
            'type': 'POST',
            'dataType': 'json',
            'url': "{{ route('admin-prod-attach') }}",
            'data' : {
                '_token': '{{ csrf_token() }}',
                'data': data,
                'category_id': category_id
            },
            'success': function(response) {
                console.log(response);
                if(response.result == true) {
                    toastr.success("Saved Successfully.");
                }
                else {
                    toastr.error("Some Went Wrong during Save.");   
                }
            }
        })
    }

</script>
@endsection
