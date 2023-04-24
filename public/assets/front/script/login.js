$(document).ready(function () {
    $("#vendor_input_section").hide();
    
    $("#address").on('change', function () {
        var address = $(this).val();
        var geocoder = new google.maps.Geocoder();

        geocoder.geocode({'address': address}, function (results, status) {

            if (status == google.maps.GeocoderStatus.OK) {
                var latitude = results[0].geometry.location.lat();
                var longitude = results[0].geometry.location.lng();

                var position = "lat:" + latitude + ",lng:" + longitude;
                $("#position").val(position);
            }

            // var myLatLng = {lat: latitude, lng: longitude};

            // var map = new google.maps.Map(document.getElementById('map'), {
            //     zoom: 4,
            //     center: myLatLng
            // });

            // var marker = new google.maps.Marker({
            //     position: myLatLng,
            //     map: map,
            //     title: 'Hello World!'
            // });

        });
    });

    $("#vendor_switch").on('change', function() {
        if($(this).prop("checked") == true) {
            $("#vendor_input_section").show();
        }
        else {
            $("#vendor_input_section").hide();
            $("#vendor_input_section :text").val('');
            $("#vendor_input #shop_message").val('')
        }
    })

    $("#same_user_name").on('change', function() {
        if($(this).prop("checked") == true) {
            var username = $("#name").val();
            if(username == '') {
                toastr.error('please enter User Name first');
                $(this).removeAttr('checked');
            }
            else {
                $("#owner_name").val(username);
            }
        }
        else {
            $("#owner_name").val('');
        }
    });

    $("#same_user_number").on('change', function() {
        if($(this).prop("checked") == true) {
            var phone = $("#phone").val();
            if(phone == '') {
                toastr.error('please enter Phone Number first');
                $(this).removeAttr('checked');
            }
            else {
                $("#shop_number").val(phone);
            }
        }
        else {
            $("#shop_number").val('');
        }
    });

    $("#same_user_address").on('change', function() {
        if($(this).prop("checked") == true) {
            var address = $("#address").val();
            if(address == '') {
                toastr.error('please enter Address first');
                $(this).removeAttr('checked');
            }
            else {
                $("#shop_address").val(address);
            }
        }
        else {
            $("#shop_address").val('');
        }
    });
})