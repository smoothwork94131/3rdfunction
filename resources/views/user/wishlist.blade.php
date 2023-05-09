@extends('layouts.front')

@section('content')

    <section class="user-dashbord">
        <div class="container">
            <div class="row">
                @include('includes.user-dashboard-sidebar')
                <div class="col-lg-8">
                    <div class="user-profile-details">
                        <div class="account-info">
                            <div class="header-area">
                                <h4 class="title">
                                    {{ $langg->lang168 }}
                                </h4>
                            </div>
                            <div class="edit-info-area">
                                <div class="body">
                                    @if (count($wishlists))
                                        @include('includes.filter')
                                        <div id="ajaxContent">
                                            <div class="row wish-list-area">
                                                @foreach ($wishlists as $wishlist)
                                                    <div class="col-lg-6">
                                                        <div class="single-wish">
                                                            <span class="remove wishlist-remove" data-href="{{ route('user-wishlist-remove', $wishlist->wish_id) }}">
                                                                <i class="fas fa-times"></i>
                                                            </span>
                                                            <div class="left">
                                                                @if($wishlist->series == "products")
                                                                <img src="{{ $wishlist->photo ? asset('assets/images/products/' . $wishlist->photo) : asset('assets/images/noimage.png') }}" alt="">
                                                                @else
                                                                <img src="{{ $wishlist->photo ? asset('assets/images/products_home/' . $wishlist->photo) : asset('assets/images/noimage.png') }}" alt="">
                                                                @endif
                                                            </div>
                                                            <div class="right">
                                                                <h4 class="title">
                                                                    <a href="{{ route('front.product', $wishlist->slug) }}">
                                                                        {{ $wishlist->name }}
                                                                    </a>
                                                                </h4>
                                                                <ul class="stars">
                                                                    <div class="ratings">
                                                                        <div class="empty-stars"></div>
                                                                        <div class="full-stars"
                                                                            style="width:{{ App\Models\Rating::ratings($wishlist->id) }}%">
                                                                        </div>
                                                                    </div>
                                                                </ul>
                                                                <div class="price">
                                                                    {{ $wishlist->price }}<small>
                                                                        <del>{{ $wishlist->price }}</del>
                                                                    </small>
                                                                </div>
                                                                <div class="series">
                                                                    @if($wishlist->series != "products")
                                                                    {{ strtoupper($wishlist->series) }}
                                                                    @endif
                                                                </div>
                                                                <div class="model">
                                                                    @if($wishlist->series != "products")
                                                                    {{ strtoupper($wishlist->model) }}
                                                                    @endif
                                                                </div>
                                                                <div class="section">
                                                                    @if($wishlist->series != "products")
                                                                    {{ strtoupper($wishlist->section_name) }}
                                                                    @endif
                                                                </div>
                                                                <div class="group">
                                                                    @if($wishlist->series != "products")
                                                                    {{ strtoupper($wishlist->group_name) }}
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach

                                            </div>

                                            <div class="page-center category">
                                                {{-- {{ $wishlists->appends(['sort' => $sort])->links() }} --}}
                                            </div>


                                        </div>
                                    @else
                                        <div class="page-center">
                                            <h4 class="text-center">{{ $langg->lang60 }}</h4>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script type="text/javascript">
        $("#sortby").on('change', function() {
            var sort = $("#sortby").val();
            window.location = "{{ url('/user/wishlists') }}?sort=" + sort;
        });
    </script>
@endsection
