<?
/*
Template Name: Каталог услуг
Template Post Type: page
*/
get_header();
$title_page = single_post_title('',false);
?>
<main>
    <section class="services bg-about">
        <section class="bread-crumb __container">
            <?breadcrumb_home_url();?>
            <span class="bread-crumb__item"><?=$title_page;?></span>
        </section>
        <div class="full-services__container">
            <h1><?=$title_page;?></h1>
            <div class="main full-services">
                <? wp_nav_menu(array(
                    'theme_location'  => 'services',
                    'menu_id'      => false,
                    'container'       => 'div',
                    'container_class' => 'full-services__col',
                    'menu_class'      => false,
                    'items_wrap'      => '%3$s',
                    'order' => 'ASC',
                    'before' => -1,
                    'walker' => new services()
                ));?>
            </div>
        </div>
    </section>
</main>
<?get_footer();?>