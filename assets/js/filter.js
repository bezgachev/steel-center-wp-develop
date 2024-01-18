$(function () {
    if (document.body.contains(document.querySelector('.card-swiper'))) {

        function swiperCard() {
            new Swiper('.card-swiper', {
                slidesPerView: 1,
                effect: 'fade',
                grabCursor: true,
                navigation: {
                    nextEl: '.card-next',
                    prevEl: '.card-prev'
                },
                // loop: true,
                // loopedSlides: 3,
                // Предзагрузка картинок
                preloadImages: true,
                lazy: {
                    loadOnTransitionStart: true, // Подгружать на старте переключения слайда
                    loadPrevNext: true, // Подгрузить предыдущую и следующую картинки
                },
                // Слежка за видимыми слайдами
                watchSlidesProgress: true,
                // Добавление класса видимым слайдам
                watchSlidesVisibility: true,
                pagination: {
                    el: '.swiper-pagination',
                    type: 'bullets',
                    clickable: true,
                },
            });
        }
        swiperCard();
    }

    function check_pagination() {
        if ($('.pagination').length) {
            $('.our-works__body').removeClass('pagination-false');
        } else {
            $('.our-works__body').addClass('pagination-false');
        }
    }
    check_pagination();

    $(document).on('click', '#filter .search-js, a.page-numbers', function (e) {
        e.preventDefault();
        var th = $(this);
        $('#filter div').removeClass('active');
        var ajax_url = "/wp-admin/admin-ajax.php";
        var term, data;
        if (th.hasClass('search-js')) {
            term = $(this).attr('data-cat');
            data = {
                action: 'filter_action',
                nonce: filter_object.nonce,
                terms: term
            };
        }
        else if (th.hasClass('page-numbers')) {
            term = th.parents('.pagination').attr('data-term');
            var page = th.attr('href');
            data = {
                action: 'filter_action',
                nonce: filter_object.nonce,
                terms: term,
                paged: page
            };
        }
        $('#filter').find('[data-cat=' + term + ']').addClass('active');
        $.ajax({
            type: 'POST',
            url: ajax_url,
            data: data,
            beforeSend: function (xhr) {
                $('.our-works__cards').addClass('preloader');
                $('.our-works__card').addClass('opacity');
            },
            success: function (data) {
                $('.filter-wrapper').html(data);
                swiperCard();
                check_pagination();
                return true;
            },
            error: function () {
                $('.filter-wrapper').html('<p>Произошла ошибка. Попробуйте еще раз</p>');
                return false;
            }
        });


    });

});