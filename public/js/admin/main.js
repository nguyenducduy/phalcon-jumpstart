// Delete alert confirm box
function deleteConfirm(theURL, id) {
    // if (confirm('Are you want to DELETE?')) {
    //     window.location.href=theURL;
    // }
    toastr.options = {
      "closeButton": true,
      "debug": false,
      "newestOnTop": false,
      "progressBar": false,
      "positionClass": "toast-bottom-full-width",
      "preventDuplicates": true,
      "onclick": null,
      "showDuration": "300",
      "hideDuration": "1000",
      "timeOut": 0,
      "extendedTimeOut": 0,
      "showEasing": "swing",
      "hideEasing": "linear",
      "showMethod": "fadeIn",
      "hideMethod": "fadeOut",
      "tapToDismiss": false
    }
    toastr.error('Are you want to DEL item <strong>'+ id +'</strong>? <a href="'+ theURL +'" class="pull-right btn btn-default btn-xs" style="color: red;margin-right:10px">Yes</a>');
}

$(document).ready(function() {
    // seleted sidebar
    var activemenu = $('.contentpanel').attr('rel');
    var activemenuselector = $('#' + activemenu);
    if (activemenuselector.length) {
        activemenuselector.addClass('active');
        activemenuselector.parent().parent().addClass('active');
    }
    // Check all checkboxes when the one in a table head is checked:
    $('.contentpanel .check-all').click(
        function(){
            // $(this).parent().parent().parent().parent().find("input[type='checkbox']").attr('checked', $(this).is(':checked'));
            $(this).parent().parent().parent().parent().find("input[type='checkbox']").prop('checked', $(this).is(':checked'));
        }
    );

    toastr.options = {
        "closeButton": true,
        "debug": false,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "onclick": null,
        "showDuration": "100",
        "hideDuration": "500",
        "timeOut": "1000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };
});