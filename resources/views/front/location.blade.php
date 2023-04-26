@extends('layouts.front')
@section('content')
    <div class="breadcrumb-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <ul class="pages">
                        <li>
                            <a href="{{ route('front.index') }}">
                                {{ $langg->lang17 }}
                            </a>
                        </li>
                        <li>
                            <a>
                                Locations
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('front.location', $location_id) }}">
                                {{ $locations->street . " " . $locations->city . " " . $locations->zip_code }}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <section class="sub-categori">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12 col-md-12">
                    <div class="map-page-title">Locations</div>
                    <div id="map" style="width:100%; height:500px;"></div>
                    <div class="map_other_info">
                        <div class="address-container">
                            <div class="address-title">
                                Contact Info
                            </div>
                            <div class="address-info">
                                <div class="address-mark">{{ $locations->name }}</div>
                            </div>
                            <div class="address-info">
                                <div class="address-mark">Address:</div>
                                <div>{{ $locations->street . " " . $locations->city . " " . $locations->zip_code }}</div>
                            </div>
                            <div class="address-info">
                                <div class="address-mark">Phone:</div>
                                <div style="color: #F05223">{{ $locations->phone_number }}</div>
                            </div>
                        </div>
                        @if($locations->hours)
                        <div class="address-container">
                            <div class="address-title">
                                Hours
                            </div>
                            @php
                                $hours = $locations->hours;
                                $hours = json_decode($hours);
                            @endphp
                            @foreach($hours as $hour)
                            <div class="address-info">
                                <div class="address-mark" style="width: 100px;">{{ $hour->date}}</div>
                                <div>{{ $hour->time}}</div>
                            </div>
                            @endforeach
                        </div>
                        @endif
                        @if($locations->facebook_url && $locations->youtube_url)
                        <div class="address-container">
                            <div class="address-title">
                                Get Connected
                            </div>
                            <div class="address-info">
                                <a href="{{ $locations->facebook_url }}"><i class="fab fa-fw fa-facebook"></i></a>
                                <a href="{{ $locations->youtube_url }}"><i class="fab fa-fw fa-youtube"></i></a>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
<script type="text/javascript">
    function initMap() {
        var geocoder = new google.maps.Geocoder();

        var address = "{{ $locations->street . " " . $locations->city . " " . $locations->zip_code }}";
        geocoder.geocode({ 'address': address }, function(results, status) {
            if (status == 'OK') {
                var lat = results[0].geometry.location.lat();
                var lng = results[0].geometry.location.lng();
                
                const myLatLng = { lat: lat, lng: lng };
                const map = new google.maps.Map(document.getElementById("map"), {
                    zoom: 18,
                    center: myLatLng,
                });

                new google.maps.Marker({
                    position: myLatLng,
                    map,
                    title: "Welcome to Tractor Brothers!",
                });

            } else {
                const myLatLng = { lat: 22.2734719, lng: 70.7512559 };
                const map = new google.maps.Map(document.getElementById("map"), {
                    zoom: 5,
                    center: myLatLng,
                });

                new google.maps.Marker({
                    position: myLatLng,
                    map,
                    title: "Welcome to Tractor Brothers!",
                });
            }
        });
    }

    window.initMap = initMap;
</script>
  
<script type="text/javascript" src="https://maps.google.com/maps/api/js?key={{ env('GOOGLE_MAP_KEY') }}&callback=initMap" ></script>
@endsection