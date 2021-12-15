//check with Catalog Service
var buildButtons = function() {
  let bibId = $("#hiddenInstanceId").val();
  if (bibId) {
    if ($(".getit").length > 0) {
      $(".getit").each(function(index,button) {
        $(this).append("<div class=\"buttons\"><div class=\"gifm-loading\"></div><i class=\"updates\"></i></div>");
      });
      let messageIncrement = 0;
      let startTimer = function() {
      patience = setInterval(function() {
        let messages = ["This title has a lot of volumes, so this may take a while.",
                  "We're still working on creating the Get It For Me buttons.",
                  "Please stay on the page. We're still working on it",
                  "Please stay on the page while we retrieve the information."];
        if (messageIncrement >= messages.length) {
            messageIncrement = 0;
        }
        $(".getit div.buttons .updates").text(messages[messageIncrement]);
          messageIncrement++;
        }, 15000);
      };

      $.ajax({
        url: gifmBase+"catalog-access/get-buttons",
        data: {"bibId": bibId,"catalogName":catalogName},
        beforeSend: function() {
          startTimer();
        }
      }).done(function(data) {
        if (data.meta.status === "ERROR") {
          $(".getit div.buttons i").text(data.meta.message);
        } else {
          $(".getit .buttons").html("");
          $.each(data.payload.HashMap,function(mfhd,buttonPresentation) {
            if (buttonPresentation && buttonPresentation.buttons) {
              $.each(buttonPresentation.buttons,function(index,button) {
                let buttonHtml = '<a target="_blank" class="'+button.cssClasses+'" href="https://'+button.linkHref+'">'+button.linkText+'</a>';
                if (button.itemKey) {
                  $("#getit_"+button.itemKey+" .buttons").append(buttonHtml);
                } else {
                  $("#getit_purchase .buttons").append(buttonHtml);
                }
              });
            }
          });
        }
      }).fail(function(xhr,status,error) {
        $(".getit div.buttons").html("<i>Error retrieving holding details</i>");
      }).always(function() {
        clearInterval(patience);
      });
    }
  }
};

$(document).ready(function() {
  buildButtons();
  $(document).ajaxSuccess(function( event, xhr, settings ) {
    if (settings.data && settings.data=='tab=holdings') {
      buildButtons();
    }
  });
});