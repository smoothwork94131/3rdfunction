@extends('layouts.admin')
@section('content')

    <div class="content-area">

        <div class="add-product-content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="product-description">
                        <div class="body-area">
                        <div class="row">
                                <div class="col-lg-12">
                                    <div class="mr-table allproduct">
                                        <div class="table-responsiv">
                                            <form action="{{route('admin-tempcart-update',$data->id)}}" method="POST">
                                            {{csrf_field()}}
                                                <table class="table table-hover dt-responsive" cellspacing="0"
                                                width="100%">
                                                <thead>
                                                <tr>
                                                    <th>{{ __('Name') }}</th>
                                                    <th>{{ __('Image') }}</th>
                                                    <th>{{ __('Price') }}</th>
                                                    <th>{{ __('Weight') }}</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach ($cart->items as $key=>$prod)    
                                                <tr>
                                                    <td>{{$prod->item->name}}</td>
                                                    <td>
                                                        <img src="{{ $prod->item->photo ? asset('assets/images/products/'.$prod->item->photo):asset('assets/images/noimage.png') }}"
                                                         alt="">
                                                    </td>
                                                    <td>
                                                        {{$prod->item->price}} * {{$prod->qty}}
                                                    </td>
                                                    <td>
                                                        @if($prod->item->file)
                                                        {{$prod->item->file}}
                                                        @else
                                                        <input type="number" name="{{$key}}" required>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endforeach
                                                </tbody>
                                                </table>
                                                <button type="submit">Add Weights and Notify User</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection