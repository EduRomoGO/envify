'use strict';

function initMap({locations}) {
    // var myLatLng = {lat: -25.363, lng: 131.044};

    // Create a map object and specify the DOM element for display.
    var map = new google.maps.Map(document.getElementById('map'), {
      center: {
	      	lat: parseFloat(locations[0].location.lat),
	      	lng: parseFloat(locations[0].location.lng)
      },
      scrollwheel: false,
      zoom: 6
    });

    
    locations.forEach(function (location) {
    	var marker, infoWindow;

		var contentString = '<div id="content">'+ location.name + '</div>';

		var infowindow = new google.maps.InfoWindow({
			content: contentString
		});

    	marker = new google.maps.Marker({
	      map: map,
	      position: {
	      	lat: parseFloat(location.location.lat),
	      	lng: parseFloat(location.location.lng)
	      }
	      // label: 'hola',
	      // title: 'Hello World!'
	    });

		marker.addListener('click', function() {
			infowindow.open(map, marker);
			// $('.hotels').append('<article class="hotel">'+
			// 		'hola'+
			// 	'</article>');
			getHotelsByCoords(parseFloat(location.location.lat), parseFloat(location.location.lng));
		});
    });

    // Create a marker and set its position.
    // var marker = new google.maps.Marker({
    //   map: map,
    //   position: myLatLng,
    //   title: 'Hello World!'
    // });
}





function getPayload ({keywords}) {
	let payload = '';

	keywords.forEach(function (keyword) {
		payload += 'keywords[]=' + keyword + '&';
	});

	payload = payload.substring(0, payload.length - 1);

	console.log(payload);

	return payload;
}


function getDestinations ({keywords}) {

	function success (res) {
		console.log('dest');
		console.log(res);
		// res = [
		// 	{
		// 		name: 'malibu',
	 //            location: {
	 //                lat: -25.363,
	 //                lng: 131.044
	 //            },
	 //            hotels: []
		// 	},
		// 	{
		// 		name: 'marbella',
	 //            location: {
	 //                lat: -27.363,
	 //                lng: 133.044
	 //            },
	 //            hotels: []
		// 	}
		// ];
		initMap({locations: res});
		$('#map').show();
		$('#my-dropzone').hide();
	}

	$.ajax({
		type: 'POST',
		url: 'http://localhost:8000/locations',
		// url: 'https://e8bdd753.ngrok.io/locations',
		data: getPayload({keywords}),
		// data: 'keywords[]=sea&keywords[]=mountain',
		context: document.body,
		success,
	    timeout: 30000
	});
}

$(function() {
  $('#map').hide();

  var method = 'POST';

  // method = 'GET';
  var myDropzone = new Dropzone("#my-dropzone", { method, url: "http://4f210dea.ngrok.io/images"});
  myDropzone.on("success", function(file, res) {
  	console.log(file);
  	console.log('uploaded');
  	console.log(res);
  	// res = ['beach', 'sun', 'chair', 'ball'];
  	getDestinations({keywords: res});
  	// drawMap();
  	// $('#map').show();
  });
});

function preFetchedHotelsCallback(res){
	// Get the data of the hotels (just with name, location, NO IMAGE, NO DESCRIPTION)
    // Show map with hotels?
    // initMap({locations: res});
    for (var hotel in res) {
        updateHotelCard(hotel);
    }
}

function clearHotels() {
    $('.hotels').html('');
}

function updateHotelCard(res) {
	if (!res.hasOwnProperty('name')) {
		return '';
	}

    var description;
    if (res.hasOwnProperty('description')) {
        description = res.description;
    } else {
        description = '';
    }

    var image;
    if (res.hasOwnProperty('image')) {
        image = res.image;
    } else {
        image = '';
    }

	if ($('#hotelid' + res.id).length) {
		$('#hotelpreview' + res.id).attr('src', image);
		$('#hoteldescription' + res.id).html(description);
	} else {

        $('.hotels').append('<article class="hotel" id="hotelid' + res.id + '">'+
            '<div class="hotel-name">' + res.name + '</div>'+
            '<img class="hotel-preview" id="hotelpreview' + res.id + '" src="' + image + '">'+
            '<div class="hotel-description" id="hoteldescription' + res.id + '">' + description + '</div>'+
            '</article>');
	}
}

function getHotelInfo(id) {
    $.ajax({
        type: 'GET',
        url: 'http://localhost:8000/hotel/' + id,
        context: document.body,
        timeout: 30000
    }).done(function(res) {
		console.log('Fetched detailed hotel', res);

        updateHotelCard(res);

		// $('.hotels').append('<article class="hotel" id="hotelid' + res.id + '">'+
		// 						'<div class="hotel-name">' + res.name + '</div>'+
		// 						'<img class="hotel-preview" src="'+ res.image +'">'+
		// 						'<div class="hotel-description">' + res.description + '</div>'+
		// 		'</article>');
    });
}

function getHotelsByCoords(lat, lng) {
	console.log('Fetching hotels in... lat: '  + lat + ' / lng: ' + lng);

    $.ajax({
        type: 'GET',
        url: 'http://localhost:8000/hotels/' + lat + '/' + lng,
        context: document.body,
        timeout: 30000
    }).done(function(res) {
    	clearHotels();
    	$('html, body').animate({scrollTop: '+=450px'}, 800);
		preFetchedHotelsCallback(res);

		for (var hotel in res) {
            console.log('hotel', res[hotel]);
            getHotelInfo(res[hotel].code);
		}
	});
}
