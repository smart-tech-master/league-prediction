(function ($) {
    "use strict";
    var wWidth = $(window).width();

    jQuery(document).ready(function ($) {


        $('.form-group input').focusout(function () {
            var text_val = $(this).val();
            if (text_val === "") {
                $(this).removeClass('has-value');
            } else {
                $(this).addClass('has-value');
            }
        });

//----------menu--------
        $(document).on('click', '.admin-wrapper .admin-sidebar .hamburger', function (e) {
            e.stopPropagation();
            $(this).parents('.admin-sidebar').toggleClass('show-menu');
        });

        $(document).on('click', 'body', function () {
            $('.admin-sidebar').removeClass('show-menu');
        });

        $(document).on('click', '.admin-sidebar', function (e) {
            e.stopPropagation();
        });

        //-------
        $('#countries, #leagues').select2({
            multiple: true
        });

        //-------dashboard country select--------
        $('#country').select2({});


        //-----------show/hide password---------
        $('#show-password').click(function () {
            if ($(this).is(':checked')) {
                $(this).parent().find('input[name="password"]').attr('type', 'text');
                $(this).parent().find('.fa-eye').hide();
                $(this).parent().find('.fa-eye-slash').show();
            } else {
                $(this).parent().find('input[name="password"]').attr('type', 'password');
                $(this).parent().find('.fa-eye').show();
                $(this).parent().find('.fa-eye-slash').hide();
            }

        });

        //-----------dashboard account password hide show---------
        $('.show-password-checkbox').click(function () {
            if ($(this).is(':checked')) {
                $(this).parent().find('input.pass-field').attr('type', 'text');
                $(this).parent().find('.fa-eye').show();
                $(this).parent().find('.fa-eye-slash').hide();
            } else {
                $(this).parent().find('input.pass-field').attr('type', 'password');
                $(this).parent().find('.fa-eye').hide();
                $(this).parent().find('.fa-eye-slash').show();
            }

        });


        //--------product quantity increase or decrease-----
        $(".number-counter-wrapper .increse").on('click', function () {
            var old = $(this).parent().find('input[name="quantity"]').val();
            var newn = parseInt(old);
            $(this).parent().find('input[name="quantity"]').val(newn + 1);

        });

        $(".number-counter-wrapper .decrese").on('click', function (e) {
            e.preventDefault();
            var old = $(this).parent().find('input[name="quantity"]').val();
            var newn = parseInt(old);

            if (newn > 0) {
                $(this).parent().find('input[name="quantity"]').val(newn - 1);
            }
        });


        /*$(".doughnut-chart .chart-progress").each(function () {

            var $bar = $(this).find(".bar");
            var $val = $(this).find(".bar-percentage");
            var perc = parseInt($val.text(), 10);

            $({ p: 0 }).animate({
                p: perc
            }, {
                duration: 1000,
                easing: "swing",
                step: function (p) {
                    $bar.css({
                        transform: "rotate(" + (45 + (p * 1.8)) + "deg)", // 100%=180° so: ° = % * 1.8
                    });
                    $val.text(p | 0);
                }
            });
        });*/


        $('.selectbox').on('click', function () {
            $(this).find('select').focus();
        });

    }); //----end document ready function-----

}(jQuery));
