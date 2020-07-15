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

// display error
function displayError(xhr) {
  var err = JSON.parse(xhr.responseText);
  var message = [err.message];
  if (err.errors !== undefined) {
    $.each(err.errors, function (key, value) {
      message.push("<li>" + value + "</li>");
    });
  }
  errorToastrMsg(message);
}

(function ($) {
  $.fn.button = function (action) {
    if (action === 'loading') {
      var loadingButtonPreTxt = '';
      var btnLoadingText = "<i class='fa fa-spinner fa-spin'></i>";
      if (this.data('loading-text')) {
        btnLoadingText = this.data('loading-text');
      }
      this.data('original-text', this.html()).html(loadingButtonPreTxt + btnLoadingText).prop('disabled', true);
    }
    if (action === 'reset' && this.data('original-text')) {
      this.html(this.data('original-text')).prop('disabled', false);
    }
  };
}(jQuery));