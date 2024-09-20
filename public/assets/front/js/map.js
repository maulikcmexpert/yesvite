
function initMap() {
    var map = new google.maps.Map(document.getElementById('map'), {
        center: {lat: -33.8688, lng: 151.2195},
        zoom: 13
    });
    /*var options = {
     types: ['(cities)']
     }*/
    var card = document.getElementById('pac-card');
    var input = document.getElementById('txtAddress');
    var types = document.getElementById('type-selector');
    var strictBounds = document.getElementById('strict-bounds-selector');

    map.controls[google.maps.ControlPosition.TOP_RIGHT].push(card);

    var autocomplete = new google.maps.places.Autocomplete(input);
    autocomplete.bindTo('bounds', map);

    var infowindow = new google.maps.InfoWindow();
    var marker = new google.maps.Marker({
        map: map,
        anchorPoint: new google.maps.Point(0, -29)
    });

    autocomplete.addListener('place_changed', function () {
        infowindow.close();
        marker.setVisible(false);
        var place = autocomplete.getPlace();
        if (place) {
            $("#latitude").val(place.geometry.location.lat);
            $("#longitude").val(place.geometry.location.lng);
        }

        if (!place.geometry) {
            window.alert("No details available for input: '" + place.name + "'");
            return;
        }

        if (place.geometry.viewport) {
            map.fitBounds(place.geometry.viewport);
        } else {
            map.setCenter(place.geometry.location);
            map.setZoom(17);
        }
        marker.setPosition(place.geometry.location);
        marker.setVisible(true);

        var address = '';
        if (place.address_components) {
            address = [
                (place.address_components[0] && place.address_components[0].short_name || ''),
                (place.address_components[1] && place.address_components[1].short_name || ''),
                (place.address_components[2] && place.address_components[2].short_name || '')
            ].join(' ');

            var typeValShort = [];
            var typeValLong = [];
            var typeField = [];
            var addr_fields = ["premise", "route", "administrative_area_level_2", "locality", "administrative_area_level_1", "country", "postal_code"];
            var addr_inputs = ["txtStreet1", "txtStreet2", "txtCity", "txtCity", "txtState", "txtCountry", "txtPostalCode"];

            for (var i = 0; i < place.address_components.length; i++) {
                for (var j = 0; j < place.address_components[i].types.length; j++) {

                    typeField.push(place.address_components[i].types[j]);
                    typeValLong.push(place.address_components[i].long_name);
                }
            }
            for (var k = 0; k < addr_fields.length; k++) {
                var indexKey = $.inArray(addr_fields[k], typeField);
                if (indexKey != -1) {
                    $("#" + addr_inputs[k]).val(typeValLong[indexKey]);
                    var selectVal = $("#" + addr_inputs[k] + " option").filter(function () {
                        return $(this).html() == typeValLong[indexKey];
                    }).val();
                    if (selectVal) {
                        $("#" + addr_inputs[k]).val(selectVal);
                    }
                } else {
                    $("#" + addr_inputs[k]).val("");
                }
            }
        }

    });
}