$(document).ready(function () {
    let form = $('form');
    if (form.length) {
        let popup_file_valid_2 = 'Данные форматы файлов запрещены для загрузки',
            popup_file_size_1 = 'Размер файла большой',
            popup_file_size_2 = 'Загрузка файлов приостановлена. Максимальный размер файлов 20&nbsp;МБ',
            popup_send_ok_1 = 'Заявка успешно отправлена',
            popup_send_ok_2 = 'Совсем скоро мы свяжемся с Вами',
            popup_send_error_1 = 'Что-то пошло не так',
            popup_send_error_2 = 'Не удалось отправить заявку. Пожалуйста, попробуйте снова',
            popup_title = $('.popup-info__descr_title'),
            popup_subtitle = $('.popup-info__descr_message'),
            popup = $('.popup'),
            popup_info = $('.popup-info'),
            overlay = $('#overlay'),
            dt = new DataTransfer(),
            th_form,
            th_form_received,
            filelist = [],
            loader_svg = '<svg fill=none height=22 viewBox="0 0 22 22"width=22 xmlns=http://www.w3.org/2000/svg><g clip-path=url(#clip0_826_10097)><path d="M4.33826 18.99C5.63397 18.99 6.68434 17.9396 6.68434 16.6439C6.68434 15.3482 5.63397 14.2979 4.33826 14.2979C3.04256 14.2979 1.99219 15.3482 1.99219 16.6439C1.99219 17.9396 3.04256 18.99 4.33826 18.99Z"fill=white fill-opacity=0.85></path><path d="M19.4327 16.9093C20.5573 16.9093 21.4689 15.9977 21.4689 14.8731C21.4689 13.7486 20.5573 12.8369 19.4327 12.8369C18.3081 12.8369 17.3965 13.7486 17.3965 14.8731C17.3965 15.9977 18.3081 16.9093 19.4327 16.9093Z"fill=white fill-opacity=0.7></path><path d="M17.5737 6.64006C18.5027 6.64006 19.2558 5.88697 19.2558 4.95797C19.2558 4.02898 18.5027 3.27588 17.5737 3.27588C16.6447 3.27588 15.8916 4.02898 15.8916 4.95797C15.8916 5.88697 16.6447 6.64006 17.5737 6.64006Z"fill=white fill-opacity=0.6></path><path d="M2.48972 13.2633C3.86475 13.2633 4.97944 12.1758 4.97944 10.8343C4.97944 9.49279 3.86475 8.40527 2.48972 8.40527C1.11468 8.40527 0 9.49279 0 10.8343C0 12.1758 1.11468 13.2633 2.48972 13.2633Z"fill=white fill-opacity=0.9></path><path d="M9.64229 21.9999C10.8923 21.9999 11.9057 21.0125 11.9057 19.7944C11.9057 18.5763 10.8923 17.5889 9.64229 17.5889C8.39226 17.5889 7.37891 18.5763 7.37891 19.7944C7.37891 21.0125 8.39226 21.9999 9.64229 21.9999Z"fill=white fill-opacity=0.8></path><path d="M15.4588 21.0335C16.6463 21.0335 17.609 20.0961 17.609 18.9398C17.609 17.7835 16.6463 16.8462 15.4588 16.8462C14.2713 16.8462 13.3086 17.7835 13.3086 18.9398C13.3086 20.0961 14.2713 21.0335 15.4588 21.0335Z"fill=white fill-opacity=0.75></path><path d="M5.18294 7.62195C6.62046 7.62195 7.7858 6.48444 7.7858 5.08124C7.7858 3.67804 6.62046 2.54053 5.18294 2.54053C3.74542 2.54053 2.58008 3.67804 2.58008 5.08124C2.58008 6.48444 3.74542 7.62195 5.18294 7.62195Z"fill=white fill-opacity=0.95></path><path d="M20.0762 11.4707C21.1387 11.4707 22.0001 10.6253 22.0001 9.58253C22.0001 8.53971 21.1387 7.69434 20.0762 7.69434C19.0137 7.69434 18.1523 8.53971 18.1523 9.58253C18.1523 10.6253 19.0137 11.4707 20.0762 11.4707Z"fill=white fill-opacity=0.65></path><path d="M11.6419 5.48893C13.1577 5.48893 14.3864 4.26019 14.3864 2.74447C14.3864 1.22874 13.1577 0 11.6419 0C10.1262 0 8.89746 1.22874 8.89746 2.74447C8.89746 4.26019 10.1262 5.48893 11.6419 5.48893Z"fill=white></path></g><defs><clipPath id=clip0_826_10097><rect fill=white height=22 width=22></rect></clipPath></defs></svg>';

        function removeFilesItem(target) {
            let loader = $(target).parents('form').find('.loader-file');
            loader.html(loader_svg);
            loader.addClass('loading');
            // loader.removeClass('d-hide');
            let file = $('input[name=file_send_server]');
            let name = $(target).prev().text();
            let input = $(target).closest('.input-file-row').find('input[name=file_send_server]');

            let file_remove = $(target).attr('data-name-file-remove');
            // console.log(file_remove);
            $.ajax({
                url: '/wp-admin/admin-ajax.php',
                type: 'POST',
                data: {
                    action: 'feedback_action',
                    nonce: feedback_object.nonce,
                    file_remove: 1,
                    name_file_remove: file_remove
                },
                dataType: 'json',
                success: function (respond, status, jqXHR) {
                    if (respond.remove_ok === 'REMOVE-OK') {
                        //console.log('Успешно удалено');
                        for (let i = 0; i < dt.items.length; i++) {
                            if (name === dt.items[i].getAsFile().name) {
                                dt.items.remove(i);
                                filelist.splice($.inArray(name, filelist), 1);
                                file.trigger('change');
                            }
                        }
                        remove_loader(loader);
                    }
                },
                error: function (jqXHR, status, errorThrown) {
                    remove_loader(loader);
                }
            });
            $(target).closest('.input-file-list-item').remove();
            input[0].files = dt.files;

        }

        function popup_info_message(title, subtitle, status) {
            popup_title.html(title);
            popup_subtitle.html(subtitle);
            overlay.fadeIn();
            // console.log(th_form_type);
            function popup_info_show() {
                popup_info.css("display", "flex")
                    .hide()
                    .fadeIn();
            }
            if (status == 'ok') {
                popup_info.find('.popup-info__icon-err').addClass('d-hide');
                setTimeout(() => {
                    popup_info.find('.popup-info__icon-ok').removeClass('d-hide');
                }, 200);
                if (th_form_received == 'page') {
                    popup_info_show();
                }
                else {
                    popup.fadeOut();
                    popup_info_show();
                }
                setTimeout(() => {
                    popup_info.fadeOut();
                    overlay.fadeOut();
                    $('body').removeAttr('style');
                    th_form.find('input[name=file_send_email]').val('');
                    th_form.find('input[type=file]').val('');
                    th_form.find('.input-file-list').html('');
                    th_form[0].reset();
                }, 3000);
                // yaCounter92533461.reachGoal('call');
                ym(92533461, 'reachGoal', 'call');
            } else if (status == 'error') {
                popup_info.find('.popup-info__icon-err').removeClass('d-hide');
                popup_info.find('.popup-info__icon-ok').addClass('d-hide');
                if (th_form_received == 'page') {
                    popup_info_show();
                }
                else {
                    popup.fadeOut();
                    popup_info_show();
                    setTimeout(() => {
                        popup_info.fadeOut();
                        popup.fadeIn();
                    }, 4000);

                }

            }

        }


        $(document).on('click', '.input-file-list-remove', function () {
            removeFilesItem(this);
            return false;
        });

        $(document).on('click', '.popup__radio input[type=radio]', function () {
            $(this).parents('form').find('input').parent().removeClass('required-invalid');
        });


        $(document).on('click', '.checkbox-privacy-policy', function () {
            if (!$(this).is(':checked')) {
                $(this).parents('.popup__submit').find('[type="submit"]').addClass('disabled');
            } else {
                $(this).parents('.popup__submit').find('[type="submit"]').removeClass('disabled');
            }
        });
        // let filelist = [];
        let valid_types = ['jpg', 'jpeg', 'png', 'webp', 'doc', 'docx', 'rar', 'zip', '7z'];
        function remove_loader(loader) {
            setTimeout(() => {
                loader.html('Прикрепить файл');
                loader.removeClass('loading');
                loader.parents('form').find('[type="submit"]').removeClass('disabled-loading-file');
            }, 500);
        }
        function remove_loader_submit(th_form) {
            setTimeout(() => {
                th_form.find('.btn-text').removeClass('d-hide');
                th_form.find('.loader-submit').addClass('d-hide');
            }, 500);
        }
        let file_size_valid = false;
        let file_limit = 20;
        let file_limit_result = (file_limit * 1024 * 1024);
        $('input[name=file_send_server]').on('change', function (element) {
            let valid_types_error = [];
            let file_wrapper = $(this).parents('form').find('.input-file_wrapper');
            let file_input = file_wrapper.find('input');

            let files_list = $(this).closest('.input-file').next();
            for (let i = 0; i < this.files.length; i++) {
                let name_file = this.files.item(i).name;
                let ext = name_file.split('.').pop();
                let search_ext = $.inArray(ext, valid_types);
                let search_name = $.inArray(name_file, filelist);
                let file_size = this.files[i].size;
                if ((file_size > file_limit_result)) {
                    file_size_valid = true;
                }
                if (search_name === -1 && search_ext !== -1 && filelist.length < 5 && (file_size < file_limit_result)) {
                    let new_file_input = '<div class="input-file-list-item">' +
                        '<span class="input-file-list-name">' + name_file + '</span>' +
                        '<a href="#" class="input-file-list-remove" data-name-file-remove="' + name_file + '"></a>' +
                        '</div>';
                    // onclick="removeFilesItem(this); return false;" 
                    files_list.append(new_file_input);
                    dt.items.add(this.files.item(i));
                    filelist.push(name_file);
                }
                if (search_ext == -1) {
                    valid_types_error.push(ext);
                }
            }
            let types_error = Array.from(new Set(valid_types_error));
            let types_error_mess = '';
            $.each(types_error, function (key, val) {
                var is_last_item = key == types_error.length - 1;
                if (is_last_item) {
                    types_error_mess += val;
                } else {
                    types_error_mess += val + ', ';
                }
            });
            if (types_error_mess) {
                let popup_file_valid_1 = '<div style="text-transform:uppercase;">' + types_error_mess + '</div> ';
                popup_info_message(popup_file_valid_1, popup_file_valid_2, 'error');
            }
            this.files = dt.files;
            // console.log(dt);
            let then = this;
            files = this.files;
            if (file_size_valid == true) {
                popup_info_message(popup_file_size_1, popup_file_size_2, 'error');
            }
            if (typeof files == 'undefined' || files.length == 0) {
                return;
            }
            if (files.length == 5) {
                file_wrapper.parent().addClass('disabled');
                file_input.prop('disabled', true);
                // file_wrapper.addClass('disabled');
                // file_input.prop('disabled', true);
            }
            else {
                file_wrapper.parent().removeClass('disabled');
                file_input.removeAttr('disabled');
            }
            let loader = $(this).parents('form').find('.loader-file');

            loader.html(loader_svg);
            loader.parents('form').find('[type="submit"]').addClass('disabled-loading-file');
            loader.addClass('loading');
            // создадим объект данных формы
            let data = new FormData();
            // заполняем объект данных файлами в подходящем для отправки формате
            $.each(files, function (key, value) {
                data.append(key, value);
            });
            // добавим переменную для идентификации запроса
            data.append('file_loading', 1);
            data.append('action', 'feedback_action');
            data.append('nonce', feedback_object.nonce);
            let html = '';
            // AJAX запрос
            $.ajax({
                url: '/wp-admin/admin-ajax.php',
                type: 'POST', // важно!
                data: data,
                cache: false,
                dataType: 'json',
                // отключаем обработку передаваемых данных, пусть передаются как есть
                processData: false,
                // отключаем установку заголовка типа запроса. Так jQuery скажет серверу что это строковой запрос
                contentType: false,
                // функция успешного ответа сервера
                success: function (respond, status, jqXHR) {

                    if (typeof respond.error === 'undefined') {
                        let files_path = respond.files;
                        // let html = '';
                        $.each(files_path, function (key, val) {
                            let isLastElement = key == files_path.length - 1;
                            if (isLastElement) {
                                html += val;
                            } else {
                                html += val + "||";
                            }
                        });
                        $(then).parents('form').find('input[name=file_send_email]').val(html);
                    }
                    else {
                        // console.log('ОШИБКА: ' + respond.error);

                    }
                    remove_loader(loader);

                    //console.log('ОШИБКА: ' + respond.message);
                },
                // функция ошибки ответа сервера
                error: function (jqXHR, status, errorThrown) {
                    remove_loader(loader);
                    // console.log('ОШИБКА AJAX запроса: ' + status, jqXHR);
                }
            });
            maps();
        });

        $('input[type="tel"]').mask("+7 (999) 999-99-99", {
            autoclear: false
        });
        form.on('click', '[type="submit"]', function () {
            th_form = $(this).parents('form');
            th_form.find('.btn-text').addClass('d-hide');
            th_form.find('.loader-submit').removeClass('d-hide');
            // th_form.find('[type=submit]').addClass('loader-submit');
            th_form_received = th_form.attr('data-form-type');
            let data = {
                action: 'feedback_action',
                nonce: feedback_object.nonce,
                received_from: th_form_received,
                send_form: 1,
            };

            let options = {
                url: feedback_object.url,
                type: 'POST',
                data: data,
                dataType: 'json',
                beforeSubmit: function (xhr) {
                    th_form.find('input').removeClass('err');
                    th_form.find('input').parent().removeClass('required-invalid');
                },
                success: function (request, xhr, status, error) {
                    remove_loader_submit(th_form);
                    if (request.success !== true) {
                        $.each(request.data, function (key) {
                            // console.log('key ' + key);
                            if (th_form_received == 'page') {
                                th_form.find(`[name="${key}"]`).addClass('err');
                            } else {
                                th_form.find(`[name="${key}"]`).parent().addClass('required-invalid');
                            }

                        });
                    }
                    if (request.message == 'OK') {
                        // console.log('хорошо от сервера');
                        popup_info_message(popup_send_ok_1, popup_send_ok_2, 'ok');

                    }
                    if (request.message == 'ERROR') {
                        popup_info_message(popup_send_error_1, popup_send_error_2, 'error');
                        // console.log('ошибка от сервера');
                    }
                },
                error: function () {
                    popup_info_message(popup_send_error_1, popup_send_error_2, 'error');
                    // console.log('ошибка загрузки');
                    remove_loader_submit(th_form);
                }
            }
            // Отправка формы
            form.ajaxForm(options);
        });

        function translationInput() {
            //Скрипт по мгновенному переводу с англ. на русс. язык
            var keyboard_layout = {
                "q": "й", "w": "ц", "e": "у", "r": "к", "t": "е", "y": "н", "u": "г", "i": "ш", "o": "щ", "p": "з", "[": "х", "]": "ъ", "a": "ф", "s": "ы", "d": "в", "f": "а", "g": "п", "h": "р", "j": "о", "k": "л", "l": "д", ";": "ж", "\'": "э", "z": "я", "x": "ч", "c": "с", "v": "м", "b": "и", "n": "т", "m": "ь", "Q": "Й", "W": "Ц", "E": "У", "R": "К", "T": "Е", "Y": "Н", "U": "Г", "I": "Ш", "O": "Щ", "P": "З", "{": "Х", "}": "Ъ", "A": "Ф", "S": "Ы", "D": "В", "F": "А", "G": "П", "H": "Р", "J": "О", "K": "Л", "L": "Д", ":": "Ж", "\"": "Э", "Z": "Я", "X": "Ч", "C": "С", "V": "М", "B": "И", "N": "Т", "M": "Ь", "<": "Б", ">": "Ю",
            };
            var search_input = $('form input[name=name],form textarea');
            search_input.on('input', function () {
                var val = '';
                var ss = this.selectionStart;
                for (var i = 0; i < this.value.length; i++) {
                    if (keyboard_layout[this.value[i]]) {
                        val += keyboard_layout[this.value[i]];
                    } else {
                        val += this.value[i];
                    }
                }
                this.value = val;
                this.selectionStart = ss;
                this.selectionEnd = ss;
            });
        } translationInput();
    }
});