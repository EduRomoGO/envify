'use strict';

function initMap() {
    var myLatLng = {lat: -25.363, lng: 131.044};

    // Create a map object and specify the DOM element for display.
    var map = new google.maps.Map(document.getElementById('map'), {
      center: myLatLng,
      scrollwheel: false,
      zoom: 4
    });

    // Create a marker and set its position.
    var marker = new google.maps.Marker({
      map: map,
      position: myLatLng,
      title: 'Hello World!'
    });
}

function drawMap () {
	initMap();
}


$(function() {
  // Now that the DOM is fully loaded, create the dropzone, and setup the
  // event listeners

  $('#map').hide();

  var method = 'POST';

  method = 'GET';
  var myDropzone = new Dropzone("#my-dropzone", { method, url: "http://c292aebc.ngrok.io/test"});
  myDropzone.on("success", function(file, res) {
  	console.log(file);
  	console.log('uploaded');
  	console.log(res);
  	drawMap();
  	$('#map').show();
  });
});
