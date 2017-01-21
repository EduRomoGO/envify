'use strict';

console.log('hi');


$(function() {
  // Now that the DOM is fully loaded, create the dropzone, and setup the
  // event listeners
  var myDropzone = new Dropzone("#my-dropzone", { method: 'POST', url: "http://c292aebc.ngrok.io/test"});
  myDropzone.on("success", function(file, res) {
  	console.log(file);
  	console.log('uploaded');
  });
});