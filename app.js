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

    var marker, infoWindow;
    locations.forEach(function (location) {

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
