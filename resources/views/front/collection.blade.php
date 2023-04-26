@extends('layouts.front')

@section('styles')
<style>
    
</style>
@endsection
@section('content')
    <section class="sub-categori">
        <div class="container">
            <div class="breadcrumb-area">
                <div class="section-top">
                    <div>
                        <ul class="pages parts-by-model-title">
                            <li>
                                <a href="{{ route('front.index') }}">
                                    {{ $langg->lang17 }}
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    Category
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            
        </div>
        <div class="container">
            <div class="row">
                @if (count($categories) > 0)
                    @foreach ($categories as $key => $category)
                    <div class="col-lg-3 col-md-4 col-6 remove-padding">
                        <a class="item" href="{{ route('front.category', $category->slug) }}">
                            <div class="item-img">
                                <img class="img-fluid" src="{{ $category->photo ? asset('assets/images/categories/'.$category->photo):asset('assets/images/noimage.png') }}" alt="">
                            </div>
                            <div class="info">
                                <h5 class="name">{{ $category->name }}</h5>
                            </div>
                        </a>
                    </div>
                    @endforeach
                @else
                <div class="col-lg-12">
                    <div class="page-center">
                        <h4 class="text-center">{{ $langg->lang60 }}</h4>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </section>
@endsection


@section('scripts')
<script>
    
</script>
@endsection