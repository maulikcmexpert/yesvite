function initMap() {
    // Initialize the map
    var map = new google.maps.Map(document.getElementById("map"), {
        center: { lat: -33.8688, lng: 151.2195 },
        zoom: 13,
    });

    // Elements for autocomplete and additional options
    var card = document.getElementById("pac-card");
    var input = document.getElementById("address1");
    map.controls[google.maps.ControlPosition.TOP_RIGHT].push(card);
    $(input).on("blur", function () {
        if ($(this).val().trim() === "") {
            $("#latitude").val("");
            $("#longitude").val("");
        }
    });
    // Initialize autocomplete and bind it to the map's bounds
    var autocomplete = new google.maps.places.Autocomplete(input);
    autocomplete.bindTo("bounds", map);

    // Info window and marker for the selected location
    var infowindow = new google.maps.InfoWindow();
    var marker = new google.maps.Marker({
        map: map,
        anchorPoint: new google.maps.Point(0, -29),
    });

    // When a place is selected, update the map and form
    autocomplete.addListener("place_changed", function () {
        infowindow.close();
        marker.setVisible(false);
        var place = autocomplete.getPlace();

        // Check if the place has geometry data
        if (!place.geometry) {
            window.alert(
                "No details available for input: '" + place.name + "'"
            );
            return;
        }

        // Update the map view and marker position
        if (place.geometry.viewport) {
            map.fitBounds(place.geometry.viewport);
        } else {
            map.setCenter(place.geometry.location);
            map.setZoom(17); // Zoom in if there's no viewport
        }

        marker.setPosition(place.geometry.location);
        marker.setVisible(true);

        // Update latitude and longitude inputs
        if (place.geometry.location) {
            $("#latitude").val(place.geometry.location.lat());
            $("#longitude").val(place.geometry.location.lng());
        }
        console.log(place);
        // Handle address components and update respective form fields
        if (place.address_components) {
            var addr_fields = [
                "street_number",
                "route",
                "administrative_area_level_2",
                "locality",
                "administrative_area_level_1",
                "postal_code",
            ];
            var addr_inputs = [
                "address1",
                "address1",
                "city",
                "city",
                "state",
                "zipcode",
            ];

            var typeField = [],
                typeValLong = [];
            console.log(place.name);
            let addressadded = false;
            if (place.formatted_address != undefined) {
                $("#address1").val(place.formatted_address);
                $("#address1").next().addClass("floatingfocus");
            } else {
                $("#address1").val(place.name);
                $("#address1").next().addClass("floatingfocus");
            }

            // Initialize variables to empty string
            let locationName = "";
            let state = "";
            let city = "";
            let zipcode = "";
            let latitude = "";
            let longitude = "";

            // Loop through address components to find the relevant data
            addressData.address_components.forEach((component) => {
                if (component.types.includes("locality")) {
                    city = component.long_name;
                }
                if (component.types.includes("administrative_area_level_1")) {
                    state = component.long_name;
                }
                if (component.types.includes("postal_code")) {
                    zipcode = component.long_name;
                }
            });

            // Get latitude and longitude from geometry object
            latitude = addressData.geometry.location.lat;
            longitude = addressData.geometry.location.lng;

            if (city != "") {
                $("#city").val(city);
                $("#city").next().addClass("floatingfocus");
            } else {
                $("#city").val("");
                $("#city").next().removeClass("floatingfocus");
            }
            if (state != "") {
                $("#state").val(place);
                $("#state").next().addClass("floatingfocus");
            } else {
                $("#state").val("");
                $("#state").next().removeClass("floatingfocus");
            }
            if (zipcode != "") {
                $("#zipcode").val(zipcode);
                $("#zipcode").next().addClass("floatingfocus");
            } else {
                $("#zipcode").val("");
                $("#zipcode").next().removeClass("floatingfocus");
            }

            $("#latitude").val(latitude);
            $("#longitude").val(longitude);
            // Loop through address components and match with input fields
            // place.address_components.forEach(function (component) {
            //     component.types.forEach(function (type) {
            //         typeField.push(type);
            //         typeValLong.push(component.long_name);

            //         // Capture street_number and route separately
            //         if (type === "street_number") {
            //             streetNumber = component.long_name;
            //         }
            //         if (type === "route") {
            //             route = component.long_name;
            //         }
            //     });
            // });

            // var fullAddress =
            //     (streetNumber != undefined && streetNumber
            //         ? streetNumber + " "
            //         : "") + route;

            // console.log(typeField);
            // console.log(typeValLong);
            // addr_fields.forEach(function (field, index) {
            //     var fieldIndex = typeField.indexOf(field);
            //     if (field === "street_number" || field === "route") {
            //         if (index === 0 && fullAddress) {
            //             // First 'address1' field
            //             $("#" + addr_inputs[index]).val(fullAddress);
            //             $("#" + addr_inputs[index])
            //                 .next()
            //                 .addClass("floatingfocus");
            //         }
            //     } else if (fieldIndex !== -1) {
            //         $("#" + addr_inputs[index]).val(typeValLong[fieldIndex]);
            //         $("#" + addr_inputs[index])
            //             .next()
            //             .addClass("floatingfocus");

            //         // If it's a select input, find matching option and set its value
            //         var selectVal = $("#" + addr_inputs[index] + " option")
            //             .filter(function () {
            //                 return $(this).html() == typeValLong[fieldIndex];
            //             })
            //             .val();
            //         if (selectVal) {
            //             $("#" + addr_inputs[index]).val(selectVal);
            //         }
            //     } else {
            //         $("#" + addr_inputs[index]).val("");
            //         $("#" + addr_inputs[index])
            //             .next()
            //             .removeClass("floatingfocus");
            //     }
            // });
        }
    });
}
