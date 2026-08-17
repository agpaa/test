/**
* Contact form handler for Web3Forms (https://web3forms.com)
* Replaces php-email-form/validate.js, which expects a plain "OK"
* response body - Web3Forms returns JSON instead.
*/
!(function ($) {
  "use strict";

  $("form.php-email-form").on("submit", function (e) {
    e.preventDefault();

    var this_form = $(this);
    var ferror = false;
    var emailExp = /^[^\s()<>@,;:\/]+@\w[\w.-]+\.[a-z]{2,}$/i;

    this_form.find(".form-group").find("input, textarea").each(function () {
      var i = $(this);
      var rule = i.attr("data-rule");
      if (rule === undefined) return;

      var ierror = false;
      var exp;
      var pos = rule.indexOf(":", 0);
      if (pos >= 0) {
        exp = rule.substr(pos + 1, rule.length);
        rule = rule.substr(0, pos);
      }

      switch (rule) {
        case "required":
          if (i.val() === "") ferror = ierror = true;
          break;
        case "minlen":
          if (i.val().length < parseInt(exp, 10)) ferror = ierror = true;
          break;
        case "email":
          if (!emailExp.test(i.val())) ferror = ierror = true;
          break;
      }

      i.next(".validate").html(ierror ? (i.attr("data-msg") || "Wrong Input") : "").show("blind");
    });

    if (ferror) return false;

    this_form.find(".sent-message").slideUp();
    this_form.find(".error-message").slideUp();
    this_form.find(".loading").slideDown();

    fetch("https://api.web3forms.com/submit", {
      method: "POST",
      headers: { Accept: "application/json" },
      body: new FormData(this)
    })
      .then(function (response) {
        return response.json();
      })
      .then(function (json) {
        this_form.find(".loading").slideUp();
        if (json.success) {
          this_form.find(".sent-message").slideDown();
          this_form.find("input:not([type=hidden]):not([type=checkbox]), textarea").val("");
        } else {
          this_form.find(".error-message").slideDown().html(json.message || "Something went wrong. Please try again.");
        }
      })
      .catch(function () {
        this_form.find(".loading").slideUp();
        this_form.find(".error-message").slideDown().html("Something went wrong. Please check your connection and try again.");
      });

    return false;
  });
})(jQuery);
