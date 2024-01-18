<a href="#" id="scroll_top" title="Наверх"></a>
<footer class="footer">
    <div class="footer__container">
        <div class="footer__descr">
            <div class="footer__descr_logo logo">
                <a href="<?=get_site_url();?>"><img src="<?$custom_logo__url = wp_get_attachment_image_src(get_theme_mod('custom_logo')); echo $custom_logo__url[0];?>" alt="logo-steel-center"></a>
            </div>

            <?contacts('footer','footer__descr');?>

            <button data-btn="call" class="open-popup-js footer__descr_btn btn">Связаться с нами</button>
            <div class="footer__descr_company akkardion"> <span>Компания:</span>
                <?wp_nav_menu(array(
                    'theme_location'  => 'header-menu',
                    'menu_id'      => false,
                    'container'       => false,
                    'container_class' => false,
                    'menu_class'      => false,
                    'items_wrap'      => '<ul>%3$s</ul>',
                    'order' => 'ASC',
                    'before' => 'menu-footer',
                    'walker' => new header_menu_another()   
                ));?>
            </div>
            <div class="footer__descr_services akkardion"> <span>Услуги:</span>
                <?wp_nav_menu(array(
                    'theme_location'  => 'services',
                    'menu_id'      => false,
                    'container'       => false,
                    'container_class' => false,
                    'menu_class'      => false,
                    'items_wrap'      => '<ul>%3$s</ul>',
                    'order' => 'ASC',
                    'before' => '',
                    'walker' => new header_menu_another()   
                ));?>
            </div>
            <?messeng_social('footer__descr_social');?>
        </div>
    </div>
    <div class="footer__bottom">
        <div class="footer__container">
            <div class="footer__bottom_container"> <a class="politics" href="/privacy" target="_blank">Политика конфиденциальности</a>
                <p class="offer"> Материалы, размещенные на сайте, носят информационный характер и не являются публичной офертой </p>
            </div>
            <div class="footer__bottom_weblitex"> <a class="weblitex" target="_blank" href="https://weblitex.ru/?utm_source=clients&utm_medium=referal&utm_campaign=stalcentr.ru">
                Разработка сайтов «Лайтекс»
            </a> </div>
        </div>
    </div>
</footer>
<div id="overlay"></div>
<div class="popup">
    <button class="btn-close">
        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M1.97711 0.916936C1.68421 0.624042 1.20934 0.624042 0.916447 0.916936C0.623554 1.20983 0.623554 1.6847 0.916447 1.9776L6.9391 8.00025L0.917085 14.0223C0.624192 14.3152 0.624192 14.79 0.917085 15.0829C1.20998 15.3758 1.68485 15.3758 1.97774 15.0829L7.99976 9.06091L14.0218 15.0829C14.3147 15.3758 14.7895 15.3758 15.0824 15.0829C15.3753 14.79 15.3753 14.3152 15.0824 14.0223L9.06042 8.00025L15.0831 1.9776C15.376 1.6847 15.376 1.20983 15.0831 0.916936C14.7902 0.624042 14.3153 0.624042 14.0224 0.916936L7.99976 6.93959L1.97711 0.916936Z" fill="#999999" /> </svg>
    </button>
    <div class="popup__title">Расчет стоимости</div>
    <form method="POST" class="popup__form" enctype="multipart/form-data" data-form-type="popup">
        <div class="popup__subtitle-container">
            <h2 class="popup__subtitle">Как с Вами лучше связаться?</h2>
            <div class="popup__radio">
                <label class="custom-radio">
                    <input type="radio" name="contact-by" value="phone" checked> <span>по телефону</span> </label>
                <label class="custom-radio">
                    <input type="radio" name="contact-by" value="email"> <span>по e-mail</span> </label>
            </div>
        </div>
        <div class="popup__fields">
            <div class="popup__fields_info">
                <input name="form_check" type="hidden" value="VweRyb">
                <div class="popup__field">
                    <input type="text" name="name" class="ym-record-keys" placeholder="Имя" autocomplete="on"> </div>
                <div class="popup__field">
                    <input type="tel" name="phone" inputmode="decimal" class="ym-record-keys" placeholder="Телефон*" autocomplete="on">
                    <span class="error-mess">
                    <span class="error-mess__tooltip">Обязательное поле</span> </span>
                </div>
                <div class="popup__field">
                    <input type="email" name="email" class="ym-record-keys" placeholder="E-mail" autocomplete="on">
                    <span class="error-mess">
                    <span class="error-mess__tooltip">Обязательное поле</span> </span>
                </div>
            </div>
            <textarea name="message" placeholder="Комментарий"></textarea>
        </div>
        <div class="input-file-row">
            <label class="input-file">
                <div class="input-file_wrapper">
                    <input type="file" accept=".jpg,.jpeg,.png,.webp,.pdf,.doc,.docx,.rar,.zip,.7z" name="file_send_server" multiple="multiple"> <span class="loader-file">Прикрепить файл</span> </div>
            </label>
            <div class="input-file-list"> </div>
        </div>
        <input name="file_send_email" type="hidden">
        
        <input name="form_title" value="Расчет стоимости" type="hidden">
        <input name="page_title" value="<?=single_post_title('',false);?>" type="hidden">
        <input name="page_url" value="<?=get_page_uri();?>" type="hidden">
        <div class="popup__submit">
        
        <!-- <input class="btn" type="submit" value="Запросить расчет"> -->
        <button class="btn" type="submit"><span class="btn-text">Запросить расчет</span><span class="loader-submit d-hide"><svg fill=none height=22 viewBox="0 0 22 22"width=22 xmlns=http://www.w3.org/2000/svg><g clip-path=url(#clip0_826_10097)><path d="M4.33826 18.99C5.63397 18.99 6.68434 17.9396 6.68434 16.6439C6.68434 15.3482 5.63397 14.2979 4.33826 14.2979C3.04256 14.2979 1.99219 15.3482 1.99219 16.6439C1.99219 17.9396 3.04256 18.99 4.33826 18.99Z"fill=white fill-opacity=0.85></path><path d="M19.4327 16.9093C20.5573 16.9093 21.4689 15.9977 21.4689 14.8731C21.4689 13.7486 20.5573 12.8369 19.4327 12.8369C18.3081 12.8369 17.3965 13.7486 17.3965 14.8731C17.3965 15.9977 18.3081 16.9093 19.4327 16.9093Z"fill=white fill-opacity=0.7></path><path d="M17.5737 6.64006C18.5027 6.64006 19.2558 5.88697 19.2558 4.95797C19.2558 4.02898 18.5027 3.27588 17.5737 3.27588C16.6447 3.27588 15.8916 4.02898 15.8916 4.95797C15.8916 5.88697 16.6447 6.64006 17.5737 6.64006Z"fill=white fill-opacity=0.6></path><path d="M2.48972 13.2633C3.86475 13.2633 4.97944 12.1758 4.97944 10.8343C4.97944 9.49279 3.86475 8.40527 2.48972 8.40527C1.11468 8.40527 0 9.49279 0 10.8343C0 12.1758 1.11468 13.2633 2.48972 13.2633Z"fill=white fill-opacity=0.9></path><path d="M9.64229 21.9999C10.8923 21.9999 11.9057 21.0125 11.9057 19.7944C11.9057 18.5763 10.8923 17.5889 9.64229 17.5889C8.39226 17.5889 7.37891 18.5763 7.37891 19.7944C7.37891 21.0125 8.39226 21.9999 9.64229 21.9999Z"fill=white fill-opacity=0.8></path><path d="M15.4588 21.0335C16.6463 21.0335 17.609 20.0961 17.609 18.9398C17.609 17.7835 16.6463 16.8462 15.4588 16.8462C14.2713 16.8462 13.3086 17.7835 13.3086 18.9398C13.3086 20.0961 14.2713 21.0335 15.4588 21.0335Z"fill=white fill-opacity=0.75></path><path d="M5.18294 7.62195C6.62046 7.62195 7.7858 6.48444 7.7858 5.08124C7.7858 3.67804 6.62046 2.54053 5.18294 2.54053C3.74542 2.54053 2.58008 3.67804 2.58008 5.08124C2.58008 6.48444 3.74542 7.62195 5.18294 7.62195Z"fill=white fill-opacity=0.95></path><path d="M20.0762 11.4707C21.1387 11.4707 22.0001 10.6253 22.0001 9.58253C22.0001 8.53971 21.1387 7.69434 20.0762 7.69434C19.0137 7.69434 18.1523 8.53971 18.1523 9.58253C18.1523 10.6253 19.0137 11.4707 20.0762 11.4707Z"fill=white fill-opacity=0.65></path><path d="M11.6419 5.48893C13.1577 5.48893 14.3864 4.26019 14.3864 2.74447C14.3864 1.22874 13.1577 0 11.6419 0C10.1262 0 8.89746 1.22874 8.89746 2.74447C8.89746 4.26019 10.1262 5.48893 11.6419 5.48893Z"fill=white></path></g><defs><clipPath id=clip0_826_10097><rect fill=white height=22 width=22></rect></clipPath></defs></svg></span></button>

            <label class="custom-checkbox">
                <input class="checkbox-privacy-policy" name="checkbox" type="checkbox" checked>
                    <span>Я согласен(-а) на обработку <a href="/privacy" target="_blank">персональных данных</a></span>
            </label>
        </div>
    </form>
</div>
<div class="popup-info">
    <div class="popup-info__icon-ok d-hide">
        <svg class="popup-info__svg" width="56" height="56" viewBox="0 0 56 56" fill="none" xmlns="http://www.w3.org/2000/svg">
            <g clip-path="url(#clip0_988_30212)">
                <path d="M53.1281 16.8008H2.87186C1.29227 16.8008 0 18.093 0 19.6725V51.2623C0 52.8417 1.29227 54.1342 2.87186 54.1342H53.1282C54.7076 54.1342 56.0001 52.8419 56.0001 51.2623V19.6725C56 18.093 54.7077 16.8008 53.1281 16.8008ZM52.0505 18.9545L29.652 35.7547C29.2493 36.0612 28.6311 36.2529 27.9999 36.2501C27.3688 36.2529 26.7507 36.0612 26.3479 35.7547L3.94942 18.9545H52.0505ZM40.0859 36.8632L52.2911 51.9402C52.3033 51.9553 52.3183 51.9668 52.3313 51.9805H3.66866C3.68156 51.9662 3.69666 51.9553 3.70891 51.9402L15.9141 36.8632C16.288 36.4009 16.2171 35.7231 15.7539 35.3483C15.2916 34.9743 14.6138 35.0452 14.2398 35.5076L2.15381 50.4374V20.3007L25.0563 37.4776C25.9172 38.1187 26.9645 38.4009 27.9998 38.4038C29.0336 38.4016 30.0818 38.1194 30.9433 37.4776L53.8457 20.3007V50.4372L41.7601 35.5076C41.3861 35.0453 40.7076 34.9742 40.246 35.3483C39.7829 35.7222 39.7118 36.4009 40.0859 36.8632Z" fill="#282E40" fill-opacity="0.35" />
                <path class="svg_circle" d="M28.9483 0C21.2614 0 15 6.28455 15 14C15 21.7155 21.2614 28 28.9483 28C36.6353 28 43 21.7155 43 14C43 6.28455 36.6353 0 28.9483 0Z" fill="#6EA36D" />
                <path class="path_line" d="M22.7383 14L27.378 18.6204" stroke="white" stroke-width="3" stroke-linecap="round" />
                <path class="path_line" d="M27.3984 18.6758L35.3008 10.8848" stroke="white" stroke-width="3" stroke-linecap="round" /> </g>
            <defs>
                <clipPath id="clip0_988_30212">
                    <rect width="56" height="56" fill="white" /> </clipPath>
            </defs>
        </svg>
    </div>
    <div class="popup-info__icon-err d-hide">
        <svg class="popup-info__svg" width="56" height="56" viewBox="0 0 56 56" fill="none" xmlns="http://www.w3.org/2000/svg">
            <g clip-path="url(#clip0_988_30226)">
                <path d="M53.1281 16.8008H2.87186C1.29227 16.8008 0 18.093 0 19.6725V51.2623C0 52.8417 1.29227 54.1342 2.87186 54.1342H53.1282C54.7076 54.1342 56.0001 52.8419 56.0001 51.2623V19.6725C56 18.093 54.7077 16.8008 53.1281 16.8008ZM52.0505 18.9545L29.652 35.7547C29.2493 36.0612 28.6311 36.2529 27.9999 36.2501C27.3688 36.2529 26.7507 36.0612 26.3479 35.7547L3.94942 18.9545H52.0505ZM40.0859 36.8632L52.2911 51.9402C52.3033 51.9553 52.3183 51.9668 52.3313 51.9805H3.66866C3.68156 51.9662 3.69666 51.9553 3.70891 51.9402L15.9141 36.8632C16.288 36.4009 16.2171 35.7231 15.7539 35.3483C15.2916 34.9743 14.6138 35.0452 14.2398 35.5076L2.15381 50.4374V20.3007L25.0563 37.4776C25.9172 38.1187 26.9645 38.4009 27.9998 38.4038C29.0336 38.4016 30.0818 38.1194 30.9433 37.4776L53.8457 20.3007V50.4372L41.7601 35.5076C41.3861 35.0453 40.7076 34.9742 40.246 35.3483C39.7829 35.7222 39.7118 36.4009 40.0859 36.8632Z" fill="#282E40" fill-opacity="0.35" />
                <path class="svg_circle" d="M28.9483 0C21.2614 0 15 6.26136 15 13.9483C15 21.6353 21.2614 27.8967 28.9483 27.8967C36.6353 27.8967 43 21.6353 43 13.9483C43 6.26136 36.6353 0 28.9483 0Z" fill="#EC5757" />
                <path class="path_line" d="M24.6035 9.55273L33.3711 18.3203" stroke="white" stroke-width="3" stroke-linecap="round" />
                <path class="path_line" d="M24.6172 18.332L33.3848 9.56445" stroke="white" stroke-width="3" stroke-linecap="round" /> </g>
            <defs>
                <clipPath id="clip0_988_30226">
                    <rect width="56" height="56" fill="white" /> </clipPath>
            </defs>
        </svg>
    </div>
    <div class="popup-info__descr">
        <div class="popup-info__descr_title">Заявка успешно отправлена</div>
        <!-- <div class="popup-info__descr_message">Совсем скоро мы свяжемся с&nbsp;Вами</div> -->
        <div class="popup-info__descr_message">Не удалось отправить заявку. Пожалуйста, попробуйте снова</div>
    </div>
</div>
<?wp_footer();?>

</body>
</html>