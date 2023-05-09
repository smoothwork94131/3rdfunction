@extends('layouts.front')

@section('content')

    <section class="user-dashbord">
        <div class="container">
            <div class="row">
                @include('includes.user-dashboard-sidebar')
                <div class="col-lg-8">
                    <!-- @include('includes.form-success') -->
                    <div class="user-profile-details my-tractor-content">
                        <div class="order-history" >
                            <div class="header-area d-flex align-items-center justify-content-between">
                                <h4 class="title">{{ $langg->lang230 }}</h4>
                                <div class='add-btn'>
                                    <button class='btn btn-primary' onclick="$('#my_tractor_modal').modal();">
                                        <i class='fa fa-plus'></i>New
                                    </button>
                                </div>
                            </div>

                            <div class="my-tractor-list">
                                <div class="row">
                                    <div class="col-12">
                                        @if (\Session::has('success'))
                                            <div class="alert alert-success">
                                                <button type="button" class="close" data-dismiss="alert">×</button>
                                                <ul>
                                                    <li>{!! \Session::get('success') !!}</li>
                                                </ul>
                                            </div>
                                        @endif
                                        @if ($errors->any())
                                            <div class="alert alert-danger">
                                                <button type="button" class="close" data-dismiss="alert">×</button>
                                                <ul>
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class='row'>
                                    <div class='col-12'>
                                        <table id="tractor_table" class="table product_table" cellspacing="0" width="100%" style="font-size: 12px;">
                                            <thead>
                                                <tr>
                                                    <th style="text-align:center;">Name</th>
                                                    <th style="text-align:center;">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @forelse ($user_tractors as $user_tractor)
                                            <tr>
                                                <td style="text-align:center;">
                                                    {{ $user_tractor->product->name }}
                                                </td>
                                                <td style="text-align:center;">
                                                    <div class="dropdown">
                                                        <a class="btn-floating btn-sm black"type="button" id="dropdownMenu3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                            <i class="fas fa-ellipsis-v"></i>
                                                        </a>
                                                        <div class="dropdown-menu dropdown-primary">
                                                            <a href="javascript:void(0)" onClick="editTractor({{$user_tractor->id}}, '{{$user_tractor->category_id}}', '{{$user_tractor->product_id}}', '{{$user_tractor->hours}}', '{{$user_tractor->hour_per_week}}', '{{$user_tractor->start_date}}', '{{$user_tractor->end_date}}')" class="dropdown-item edit-tractor"><i class="fa fa-edit"></i>&nbsp;&nbsp;Edit Tractor</a>
                                                            <a href="{{route('user-remove-my-tractor', ['id' => $user_tractor->id])}}" class="dropdown-item trash-tractor"><i class="fa fa-trash"></i>&nbsp;&nbsp;Delete Tractor</a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="2" style="text-align: center;">No DATA</td>
                                            </tr>
                                            @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="my_tractor_modal"  role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id='my_tractor_form' action="{{route('user-save-my-tractor')}}" method="POST">
                        @csrf
                        <div class="modal-header d-block text-center">
                            <h4 class="modal-title d-inline-block">Add New Tracktor</h4>
                        </div>

                        <div class="modal-body">
                            <input type="hidden" id="tractor_id" name="tractor_id" value="">
                            <div class="form-group">
                                <label class='name'>Series</label>
                                <select class="form-control value" name="category_id" id="category_id" onchange='changeCategory(event)'>
                                    @foreach($categories as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label class='name'>Model</label>
                                <select class="form-control value" name="product_id" id="product_id">
                                    @foreach($tractors as $key => $item)
                                    <option value={{$item->id}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="name">Current Hours</label>
                                <input type="text" class="form-control value" name="hours" id="hours" />
                            </div>
                            <div class="form-group">
                                <label class="name">Hours used per week</label>
                                <input type="text" class="form-control value" name="hour_per_week" id="hour_per_week" />
                            </div>
                            <div class="form-group">
                                <label class="name">Season Start Date</label>
                                <input type="text" class="form-control value" name="start_date" id="start_date" placeholder="MM/DD/YYYY"/>
                            </div>
                            <div class="form-group">
                                <label class="name">Season End Date</label>
                                <input type="text" class="form-control value" name="end_date" id="end_date" placeholder="MM/DD/YYYY"/>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-end">
                            <button type="submit" class='btn btn-success'>Save</button>
                            <button class='btn btn-danger' data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('scripts')
    <script type="text/javascript">
        @php
            if($user_tractors->count() > 0) {
        @endphp
            $(document).ready(function () {
                $('.product_table').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "searching": false,
                    "lengthMenu": [[50, 100, 150, 200, -1], [50, 100, 150, 200, "All"]]
                });
            });
        @php
        }
        @endphp
        
        function changeCategory(event) {
            var value = event.target.value ;
            getTractor(value);  
        }

        function getTractor(category_id) {
            $.ajax({
                url: "{{route('user-get-my-tractor-model')}}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                async:false,
                data:{
                    category_id: category_id
                },
                type:'post',
                success:function(result) {
                    var html = "" ;
                    for(key in result) {
                        console.log(result[key])
                        html+="<option value='"+ result[key].id +"'>"+result[key].name+"</option>" ;
                    }
                    $("#my_tractor_form #product_id").html(html) ;
                    return true;
                },error:function() {
                    return false;
                }
            }) ;
        }

        function editTractor(id, category_id, product_id, hours, hour_per_week, start_date, end_date) {
            getTractor(category_id);

            $("#tractor_id").val(id);
            $("#category_id").val(category_id);
            $("#product_id").val(product_id);
            $("#hours").val(hours);
            $("#hour_per_week").val(hour_per_week);
            $("#start_date").val(start_date);
            $("#end_date").val(end_date);

            $("#my_tractor_modal").modal();
        }
    </script>

@endsection