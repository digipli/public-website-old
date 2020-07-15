
function validateForm(formId, modalId, submitButtonId) {

  var formValidation = $('#' + formId).validate({
    onkeyup: false,
    onclick: false,
    onfocusout: false,
    normalizer: function (value) {
      return $.trim(value);
    },
    rules: {
      name: {
        required: true,
      },
      email: {
        required: true,
        email: true,
      },
      company: {
        required: true,
      },
      country: {
        required: true,
      }
    },
    messages: {
      name: {
        required: "Name is required!",
      },
      email: {
        required: "Email is required!",
        email: "Please enter valid email!",
      },
      company: {
        required: "Company is required!",
      },
      country: {
        required: "Country is required!",
      }
    },
    highlight: function (element) {
      $(element).addClass('is-invalid');
    },
    unhighlight: function (element) {
      $(element).removeClass('is-invalid');
    },
    errorElement: 'div',
    errorClass: 'invalid-feedback ml-1',
    submitHandler: function () {
      sendEmail(formId);
    }
  });

  function sendEmail(formId) {
    var data = $('#' + formId).serialize();

    $.ajax({
      url: './send-mail/send-mail.php',
      method: 'POST',
      data: data,
      beforeSend: function () {
        $("#" + submitButtonId).button("loading");
      },
      success: function (data) {

        if (data.status === "error") {
          errorToastrMsg(data.message);
          return true;
        }
        $('#' + modalId).modal('hide');
        successToastrMsg(data.message);

        // window.open('./images/AML Managed Utility White Paper - SF 9Sep19.pdf', '_blank');

        $('<a />', {
          'href': './images/AML-Managed-Utility-White-Paper-SF-Feb-2020.pdf',
          'download': 'DigiPli.pdf',
          'text': "click"
        }).hide().appendTo("body")[0].click();
      },
      error: function (xhr) {
        $("#" + submitButtonId).button("reset");
        displayError(xhr);
      },
      complete: function () {
        $("#" + submitButtonId).button("reset");
      }
    });
  }

  $('#' + modalId).on('hidden.bs.modal', function () {
    $('#' + formId).trigger("reset");
    formValidation.resetForm();
    $('#' + formId).find('.is-invalid').removeClass('is-invalid');
  });

}

// Send Mail Contact Us Form
var orderRequestFormValidation = $("#contactUsForm").validate({
  onkeyup: false,
  onclick: false,
  onfocusout: false,
  normalizer: function(value) {
    return $.trim(value);
  },
  rules: {
    name: {
      required: true
    },
    email: {
      required: true,
      email: true
    },
    business: {
      required: true,
    },
    message: {
      required: true
    }
  },
  messages: {
    name: {
      required: "Name is required!"
    },
    email: {
      required: "Email is required!",
      email: "Please enter valid email!"
    },
    business: {
      required: "Business is required!"
    },
    message: {
      required: "Message is required!"
    }
  },
  highlight: function(element) {
    $(element).addClass("is-invalid");
  },
  unhighlight: function(element, errorClass) {
    $(element).removeClass("is-invalid");
  },
  errorElement: "div",
  errorClass: "invalid-feedback",
  submitHandler: function() {
    sendContactUsEmail();
  }
});

function sendContactUsEmail() {
  var data = $("#contactUsForm").serialize();
  var siteName = "Digipli";
  data += "&siteName=" + siteName;

  $.ajax({
    url: "./send-mail/send-contant-us-mail.php",
    method: "POST",
    data: data,
    beforeSend: function() {
      $("#contactUsFormSubmit span").removeClass("d-none");
      $("#contactUsFormSubmit").attr("disabled", true);
    },
    success: function(data, status, xhr) {
      $("#contactUsForm").trigger("reset");
      $("#contactUsFormSubmit span").addClass("d-none");
      $("#contactUsFormSubmit").attr("disabled", false);
      if (data.status === "error") {
        errorToastrMsg(data.message);
        return true;
      }
      successToastrMsg(data.message);
    },
    error: function(xhr, status, error) {
      $("#contactUsFormSubmit span").addClass("d-none");
      displayError(xhr);
    }
  });
}

var toastrOptions = {
  // "closeButton": true,
  "preventDuplicates": true,
  "preventOpenDuplicates": true,
  "showMethod": 'slideDown',
  "progressBar": true,
  "showMethod": 'slideDown',
  "hideMethod": 'slideUp',
  "closeMethod": 'slideUp',
  "timeOut": 3000,
  "maxOpened": 1
}

function successToastrMsg($msg) {
  toastr.options = toastrOptions;

  toastr.success($msg);
}

function warningToastrMsg($msg) {
  toastr.options = toastrOptions;

  toastr.warning($msg);
}

function infoToastrMsg($msg) {
  toastr.options = toastrOptions;

  toastr.info($msg);
}

function errorToastrMsg($msg) {
  toastr.options = toastrOptions;

  toastr.error($msg);
}

function displayError(xhr) {
  var err = JSON.parse(xhr.responseText);
  var message = [err.message];
  if (err.errors !== undefined) {
    $.each(err.errors, function(key, value) {
      message.push("<li>" + value + "</li>");
    });
  }
  errorToastrMsg(message);
}
