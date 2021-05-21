$(document).ready(function() {
  var $sfxButton = $("#SFXButton");
  if ($sfxButton) {
    var gifmBase = "http://localhost:9000/";
    var sfxUrl = $sfxButton.parent().attr("href");
    $.ajax({
      url: gifmBase+"catalog-access/check-full-text",
      data: {"url": encodeURIComponent(sfxUrl)}
    }).done(function(data) {
      console.log(data);
      if (data.payload.Boolean == true) {
        $("#sfxRow td").children().removeClass("hidden");
      }
    });
  }
});