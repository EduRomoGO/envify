'use strict';

function initMap() {
    var myLatLng = {lat: -25.363, lng: 131.044};

    // Create a map object and specify the DOM element for display.
    var map = new google.maps.Map(document.getElementById('map'), {
      center: myLatLng,
      scrollwheel: false,
      zoom: 2
    });

    // Create a marker and set its position.
    var marker = new google.maps.Marker({
      map: map,
      position: myLatLng,
      title: 'Hello World!'
    });
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
		initMap();
		$('#map').show();
	}

	$.ajax({
		type: 'POST',
		url: 'https://84ba01d1.ngrok.io/locations',
		data: getPayload({keywords}),
		// data: 'keywords[]=sea&keywords[]=mountain',
		context: document.body,
		success
	});
}


$(function() {
  // Now that the DOM is fully loaded, create the dropzone, and setup the
  // event listeners

  $('#map').hide();

  var method = 'POST';

  // method = 'GET';
  var myDropzone = new Dropzone("#my-dropzone", { method, url: "http://c292aebc.ngrok.io/images"});
  myDropzone.on("success", function(file, res) {
  	console.log(file);
  	console.log('uploaded');
  	console.log(res);
  	res = ['beach', 'sun', 'chair', 'ball'];
  	getDestinations({keywords: res});
  	// drawMap();
  	// $('#map').show();
  });
});
