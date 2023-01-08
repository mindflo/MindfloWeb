/* ===================================================================
 * Count - Main JS
 *
 * ------------------------------------------------------------------- */

(function ($) {
  "use strict";

  var cfg = {
      scrollDuration: 800, // smoothscroll duration
      mailChimpURL:
        "https://facebook.us8.list-manage.com/subscribe/post?u=cdb7b577e41181934ed6a6a44&amp;id=e6957d85dc", // mailchimp url
    },
    $WIN = $(window);

  // Add the User Agent to the <html>
  // will be used for IE10 detection (Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.2; Trident/6.0))
  var doc = document.documentElement;
  doc.setAttribute("data-useragent", navigator.userAgent);

  // svg fallback
  if (!Modernizr.svg) {
    $(".home-logo img").attr("src", "images/logo.png");
  }

  /* Preloader
   * -------------------------------------------------- */
  var ssPreloader = function () {
    $("html").addClass("ss-preload");

    $WIN.on("load", function () {
      // will first fade out the loading animation
      $("#loader").fadeOut("slow", function () {
        // will fade out the whole DIV that covers the website.
        $("#preloader").delay(300).fadeOut("slow");
      });

      // for hero content animations
      $("html").removeClass("ss-preload");
      $("html").addClass("ss-loaded");
    });
  };

  /* info toggle
   * ------------------------------------------------------ */
  var ssInfoToggle = function () {
    //open/close lateral navigation
    $(".info-toggle").on("click", function (event) {
      event.preventDefault();
      $("body").toggleClass("info-is-visible");
    });
  };

  /* slick slider
   * ------------------------------------------------------ */
  var ssSlickSlider = function () {
    $(".home-slider").slick({
      arrows: false,
      dots: false,
      autoplay: true,
      autoplaySpeed: 3000,
      fade: true,
      speed: 3000,
    });
  };

  /* placeholder plugin settings
   * ------------------------------------------------------ */
  var ssPlaceholder = function () {
    $("input, textarea, select").placeholder();
  };

  /* final countdown
   * ------------------------------------------------------ */
  var ssFinalCountdown = function () {
    var finalDate = new Date("March 25, 2023 15:37:25").getTime();
    //-date: "Mar 25 2023",

    $(".home-content__clock")
      .countdown(finalDate)
      .on("update.countdown finish.countdown", function (event) {
        var str =
          '<div class="top"><div class="time days">' +
          "%D <span>day%!D</span>" +
          "</div></div>" +
          '<div class="time hours">' +
          "%H <span>H</span></div>" +
          '<div class="time minutes">' +
          "%M <span>M</span></div>" +
          '<div class="time seconds">' +
          "%S <span>S</span></div>";

        $(this).html(event.strftime(str));
      });
  };

  /* AjaxChimp
   * ------------------------------------------------------ */
  var ssAjaxChimp = function () {
    // $('#mc-form').ajaxChimp({
    //     language: 'es',
    //     url: cfg.mailChimpURL
    // });

    // Mailchimp translation
    //
    //  Defaults:
    //	 'submit': 'Submitting...',
    //  0: 'We have sent you a confirmation email',
    //  1: 'Please enter a value',
    //  2: 'An email address must contain a single @',
    //  3: 'The domain portion of the email address is invalid (the portion after the @: )',
    //  4: 'The username portion of the email address is invalid (the portion before the @: )',
    //  5: 'This email address looks fake or invalid. Please enter a real email address'

    $.ajaxChimp.translations.es = {
      submit: "Submitting...",
      0: '<i class="fas fa-check"></i> We have sent you a confirmation email',
      1: '<i class="fas fa-exclamation-triangle"></i> You must enter a valid e-mail address.',
      2: '<i class="fas fa-exclamation-triangle"></i> E-mail address is not valid.',
      3: '<i class="fas fa-exclamation-triangle"></i> E-mail address is not valid.',
      4: '<i class="fas fa-exclamation-triangle"></i> E-mail address is not valid.',
      5: '<i class="fas fa-exclamation-triangle"></i> E-mail address is not valid.',
    };
  };

  /* initialize
   * ------------------------------------------------------ */
  (function ssInit() {
    ssPreloader();
    ssInfoToggle();
    ssSlickSlider();
    ssPlaceholder();
    ssFinalCountdown();
    ssAjaxChimp();
  })();
})(jQuery);

//Sendmail
// Variable to hold request
var request;
$("#mc-form").submit(function (e) {
  debugger;
  // Prevent default posting of form - put here to work in case of errors
  e.preventDefault();

  // Abort any pending request
  if (request) {
    request.abort();
  }
  // setup some local variables
  var $form = $(this);

  // Let's select and cache all the fields
  var $inputs = $form.find("input, select, textarea");

  // Serialize the data in the form
  var serializedData = $form.serialize();

  // Let's disable the inputs for the duration of the Ajax request.
  // Note: we disable elements AFTER the form data has been serialized.
  // Disabled form elements will not be serialized.
  $inputs.prop("disabled", true);

  //collectUserData(serializedData);
  var siteUrl = location.href + "sendmail.php";
  $(".loader").show();
  // Fire off the request to /form.php
  request = $.ajax({
    url: siteUrl,
    type: "post",
    data: serializedData,
  });

  // Callback handler that will be called on success
  request.done(function (response, textStatus, jqXHR) {
    $(".loader").hide();

    $(".email").val("");

    $("#ResponseMessage").show();
    $("#ResponseMessage p").text(
      "Email sent successfully. We will get in touch with you shortly."
    );

    setTimeout(function () {
      $("#ResponseMessage").hide();
    }, 3000);

    // Log a message to the console
    //console.log("Hooray, it worked!");
  });

  // Callback handler that will be called on failure
  request.fail(function (jqXHR, textStatus, errorThrown) {
    // Log the error to the console
    $("#ResponseMessage p").text(
      "There were some issues with sending email. Please contact later."
    );
    console.log("The following error occurred: " + textStatus, errorThrown);
  });

  // Callback handler that will be called regardless
  // if the request failed or succeeded
  request.always(function () {
    // Reenable the inputs
    $(".loader").hide();
    $inputs.prop("disabled", false);
  });
});

function collectUserData(serializedData) {
    debugger;
  var insertService = location.href + "/services/insertService.php";
  $(".loader").show();
  // Fire off the request to /form.php
  request = $.ajax({
    url: siteUrl,
    type: "post",
    data: serializedData,
  });

  // Callback handler that will be called on success
  request.done(function (response, textStatus, jqXHR) {
    $(".loader").hide();
    console.log(
      "Email sent successfully. We will get in touch with you shortly."
    );
  });


  // Callback handler that will be called on failure
  request.fail(function (jqXHR, textStatus, errorThrown) {
    // Log the error to the console
    $("#ResponseMessage p").text(
      "There were some issues with sending email. Please contact later."
    );
    console.log("The following error occurred: " + textStatus, errorThrown);
  });

  // Callback handler that will be called regardless
  // if the request failed or succeeded
  request.always(function () {
    // Reenable the inputs
    $(".loader").hide();
    $inputs.prop("disabled", false);
  });
}
