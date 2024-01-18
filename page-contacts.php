<?
/*
Template Name: Контакты
Template Post Type: page
*/
get_header();
$title_page = single_post_title('',false);
?>
<main>
    <section class="contacts">
        <section class="bread-crumb __container">
            <?breadcrumb_home_url();?>
            <span class="bread-crumb__item"><?echo $title_page;?></span>
        </section>
        <div class="contacts__container">
            <div class="contacts__descr">
                <h1><?echo $title_page;?></h1>
                <?contacts('page-contacts','contacts__descr_items');?>
                <?messeng_social('contacts__descr_social');?>
                <button data-btn="call" class="open-popup-js btn">Связаться с нами</button>
            </div>
            <div class="contacts__map">
                <?contacts('map','d-hide');?>
                <div class="map" id="map" data-dir="<?=get_stylesheet_directory_uri();?>/assets"></div>
            </div>
        </div>
    </section>
    <section class="company-details">
        <div class="company-details__container">
            <div class="company-details__wrapper">
                <h2>Реквизиты компании <span><?echo get_field('contacts-company-name');?></span>:</h2>
                <div class="company-details__items">
                    <div class="company-details__items_wrapper">
                        <div class="company-details__item"> <span>Юр. адрес:</span>
                            <p><?echo get_field('contacts-yur_address');?></p>
                        </div>
                        <div class="company-details__item"> <span>ИНН:</span>
                            <p><?echo get_field('contacts-inn');?></p>
                        </div>
                        <div class="company-details__item"> <span>КПП:</span>
                            <p><?echo get_field('contacts-kpp');?></p>
                        </div>
                        <div class="company-details__item"> <span>ОГРН:</span>
                            <p><?echo get_field('contacts-ogrn');?></p>
                        </div>
                        <div class="company-details__item"> <span>Директор:</span>
                            <p><?echo get_field('contacts-director');?></p>
                        </div>
                    </div>
                    <div class="company-details__items_wrapper">
                        <div class="company-details__item"> <span>Банк:</span>
                            <p><?echo get_field('contacts-bank');?></p>
                        </div>
                        <div class="company-details__item"> <span>БИК:</span>
                            <p><?echo get_field('contacts-bik');?></p>
                        </div>
                        <div class="company-details__item"> <span>Расч. счет:</span>
                            <p><?echo get_field('contacts-rasch_schyot');?></p>
                        </div>
                        <div class="company-details__item"> <span>Кор. счет:</span>
                            <p><?echo get_field('contacts-korresp_schyot');?></p>
                        </div> <a download="Реквизиты <?bloginfo('name');?>" href="<?echo get_field('contacts-down_requisites');?>" class="download-details">Скачать реквизиты</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
<?get_footer();?>