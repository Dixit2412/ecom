/* Delete Time Model Open Code */
(function () {
    var laravel = {
        initialize: function () {
            this.methodLinks = jQuery('body');
            this.registerEvents();
        },
        registerEvents: function () {
            //this.methodLinks.on('click', this.handleMethod);
            this.methodLinks.on('click', 'a[data-method]', this.handleMethod);
        },
        handleMethod: function (e) {
            e.preventDefault();
            var link = jQuery(this);
            var csrf_token = jQuery('meta[name="csrf-token"]').attr('content');
            var httpMethod = link.data('method').toUpperCase();
            var allowedMethods = ['PUT', 'DELETE', 'GET'];
            var extraMsg = link.data('modal-text');
            var reject = link.data('reject');
            var prefix_text = link.data('prefix-text') || '';

            // if (reject) {
            //     var msg = '<i class="fa fa-exclamation-triangle fa-2x" style="vertical-align: middle; color:#f39c12;"></i>' + (prefix_text.length) ? prefix_text : rejectStatus_msg + extraMsg;

            var msg = '';
            if (prefix_text.length > 0) {
                msg += prefix_text
            } else if (reject) {
                msg += 'Are You sure you want to reject';
            } else {
                msg += 'Are you sure you want to delete';
            }
            msg += extraMsg;

            // If the data-method attribute is not PUT or DELETE,
            // then we don't know what to do. Just ignore.
            if ($.inArray(httpMethod, allowedMethods) === -1) {
                return;
            }

            // Set default properties
            let toast = Swal.mixin({
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'btn btn-success m-1',
                    cancelButton: 'btn btn-danger m-1',
                    input: 'form-control'
                }
            });
            toast.fire({
                title: 'Please Confirm',
                icon: 'warning',
                showCancelButton: true,
                customClass: {
                    confirmButton: 'btn btn-danger m-1',
                    cancelButton: 'btn btn-secondary m-1'
                },
                confirmButtonText: 'Yes, delete it!',
                html: msg,
                preConfirm: e => {
                    return new Promise(resolve => {
                        setTimeout(() => {
                            resolve();
                        }, 50);
                    });
                }
            }).then(result => {
                if (result.value) {
                    var form = $('<form>', {
                        'method': 'POST',
                        'action': link.attr('href')
                    });
                    var hiddenInput = $('<input>', {
                        'name': '_method',
                        'type': 'hidden',
                        'value': link.data('method')
                    });
                    var tokenInput = $('<input>', {
                        'name': '_token',
                        'type': 'hidden',
                        'value': csrf_token
                    });
                    form.append(tokenInput);
                    form.append(hiddenInput).appendTo('body').submit();

                    // toast.fire('Deleted!', 'Your imaginary file has been deleted.', 'success');
                    // result.dismiss can be 'overlay', 'cancel', 'close', 'esc', 'timer'
                } else if (result.dismiss === 'cancel') {
                    toast.fire('Cancelled', 'Your imaginary file is safe :)', 'error');
                }
            });
        }
    };
    laravel.initialize();
})();

// allow only positive numeric
jQuery(document).on("keypress blur", ".allownumeric", function (event) {
    var theEvent = event || window.event;
    var key = theEvent.keyCode || theEvent.which;
    if (key == 37 || key == 46 || key == 38 || key == 39 || key == 9 || key == 40 || key == 8 || key == 46 || key == 123 || key == 16) {
        return;
    } else {
        key = String.fromCharCode(key);
        var regex = /[0-9. ]|\./;
        if (!regex.test(key)) {
            theEvent.returnValue = false;
            if (theEvent.preventDefault) theEvent.preventDefault();
        }
    }
});

jQuery.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
    }
});