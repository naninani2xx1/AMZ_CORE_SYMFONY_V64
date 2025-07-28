$(document).ready(function () {

    if ( $('form').length > 0 ) {

        const mainform = document.querySelector('#contactForm');
        if (mainform != null) {
            mainform.addEventListener("submit", function(e) {
                $('.overlay').removeClass('hide');

                fetch(action, {
                    method: 'POST',
                    body: data,
                })
                    .then(() => {
                        //alert("Success!");

                        $('.overlay').addClass('hide');
                        //show modal
                        $('#success').modal('show');

                        $(this).find(".form-control").val('');
                        $(this).find(".form-select").val('').change();
                        e.preventDefault();

                        $('.success .btn').on('click', function(e){
                            $('.success').modal('hide');
                        });
                    })
            });
        }
    }
});