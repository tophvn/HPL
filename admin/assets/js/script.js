/*------------------------------------------------------------------
* Bootstrap Simple Admin Template
* Version: 3.0
* Author: Alexis Luna
* Website: https://github.com/alexis-luna/bootstrap-simple-admin-template
-------------------------------------------------------------------*/
(function() {
    'use strict';

    // Toggle sidebar on Menu button click
    $('#sidebarCollapse').on('click', function() {
        $('#sidebar').toggleClass('active');
        $('#body').toggleClass('active');
    });

    // Auto-hide sidebar on window resize if window size is small
    // $(window).on('resize', function () {
    //     if ($(window).width() <= 768) {
    //         $('#sidebar, #body').addClass('active');
    //     }
    // });
})();


/*Chỉnh sửa sản phẩm*/
(function ($) {
    "use strict";

    // Dropdown on mouse hover
    $(document).ready(function () {
        function toggleNavbarMethod() {
            if ($(window).width() > 992) {
                $('.navbar .dropdown').on('mouseover', function () {
                    $('.dropdown-toggle', this).trigger('click');
                }).on('mouseout', function () {
                    $('.dropdown-toggle', this).trigger('click').blur();
                });
            } else {
                $('.navbar .dropdown').off('mouseover').off('mouseout');
            }
        }
        toggleNavbarMethod();
        $(window).resize(toggleNavbarMethod);

        // Hiện dữ liệu sản phẩm vào biểu mẫu khi nhấn nút "Chỉnh sửa"
        $('.editBtn').on('click', function () {
            $('#formTitle').text('Chỉnh Sửa Sản Phẩm');
            $('#formAction').val('edit_product');
            $('#product_id').val($(this).data('id'));
            $('#product_name').val($(this).data('name'));
            $('#price').val($(this).data('price'));
            $('#description').val($(this).data('description'));
            $('#size').val($(this).data('size'));
            $('#discount').val($(this).data('discount'));
            $('#image').prop('required', false); // Không bắt buộc thay đổi ảnh khi chỉnh sửa
            $('#productModal').modal('show');
        });

        // Reset form khi nhấn nút "Thêm sản phẩm"
        $('#addProductBtn').on('click', function () {
            $('#formTitle').text('Thêm Sản Phẩm');
            $('#formAction').val('add_product');
            $('#product_id').val('');
            $('#product_name').val('');
            $('#price').val('');
            $('#description').val('');
            $('#size').val('');
            $('#discount').val('0');
            $('#image').prop('required', true); // Bắt buộc chọn ảnh khi thêm mới
            $('#image').val('');
            $('#image2').val('');
            $('#image3').val('');
            $('#category_id').val('');
            $('#subcategory_id').val('');
            $('#type_id').val('');
            $('#productModal').modal('show');
        });
    });

    // Back to top button
    $(window).scroll(function () {
        if ($(this).scrollTop() > 100) {
            $('.back-to-top').fadeIn('slow');
        } else {
            $('.back-to-top').fadeOut('slow');
        }
    });
    $('.back-to-top').click(function () {
        $('html, body').animate({ scrollTop: 0 }, 1500, 'easeInOutExpo');
        return false;
    });

    // Vendor carousel
    $('.vendor-carousel').owlCarousel({
        loop: true,
        margin: 29,
        nav: false,
        autoplay: true,
        smartSpeed: 1000,
        responsive: {
            0: { items: 2 },
            576: { items: 3 },
            768: { items: 4 },
            992: { items: 5 },
            1200: { items: 6 }
        }
    });

    // Related carousel
    $('.related-carousel').owlCarousel({
        loop: true,
        margin: 29,
        nav: false,
        autoplay: true,
        smartSpeed: 1000,
        responsive: {
            0: { items: 1 },
            576: { items: 2 },
            768: { items: 3 },
            992: { items: 4 }
        }
    });

    // Product Quantity
    $('.quantity button').on('click', function () {
        var button = $(this);
        var oldValue = button.parent().parent().find('input').val();
        var newVal = button.hasClass('btn-plus') 
            ? parseFloat(oldValue) + 1 
            : Math.max(0, parseFloat(oldValue) - 1);
        button.parent().parent().find('input').val(newVal);
    });
})(jQuery);
