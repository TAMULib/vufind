$(document).ready(function() {
  var $sfxButton = $("#SFXButton");
  if ($sfxButton) {
    var gifmBase = "https://api-dev.library.tamu.edu/get-it/";
    var sfxUrl = $sfxButton.parent().attr("href");
    $.ajax({
      url: gifmBase+"catalog-access/check-full-text",
      data: {"url": encodeURIComponent(sfxUrl)}
    }).done(function(data) {
      if (data.payload.Boolean == true) {
        $("#sfxRow td").children().removeClass("hidden");
      }
    });
  }
});