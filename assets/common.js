HTMLButtonElement.prototype.activeProgress = function(isActive = true)  {
    if(isActive){
        this.setAttribute('disabled', 'disabled');
        this.setAttribute('data-kt-indicator', 'on');
    }else{
        this.removeAttribute('disabled');
        this.removeAttribute('data-kt-indicator');
    }
}

const alertError = (error = "Sorry, looks like there are some errors detected, please try again.") => {
    Swal.fire({
        html: error,
        icon: "error",
        buttonsStyling: !1,
        confirmButtonText: "Ok, got it!",
        customClass: {confirmButton: "btn btn-light"}
    });
}

const alertSuccess = (options) => {
    Swal.fire({
        ...options,
        icon: "success",
        buttonsStyling: !1,
        confirmButtonText: "Ok",
        customClass: {
            confirmButton: "btn btn-primary btn-sm fs-6"
        }
    }).then(options.callback);
}

const addModalIntoBodyTag = (html) => {
    document.body.insertAdjacentHTML('afterbegin', html)
}

const alertProcessing = () => {
    Swal.fire({
        html: "Processing...",
        showConfirmButton: false,
        allowOutsideClick: false, // Không cho phép nhấp ngoài để đóng
        allowEscapeKey: false, // Không cho phép đóng bằng phím Escape
        customClass: {
            popup: 'swal2-processing-modal',
            title: 'swal2-processing-title',
        },
    });
};

export {alertError, alertProcessing, alertSuccess, addModalIntoBodyTag}