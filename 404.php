<?
get_header();?>
<main>
    <section class="not-exist">
        <div class="not-exist__container">
            <div class="not-exist__img"><img src="<?php echo get_template_directory_uri();?>/assets/img/bg/404.png" alt="404"></div>
            <h1>Страница не найдена</h1>
            <p>Похоже, запрашиваемая вами страница не найдена.
                Воспользуйтесь меню нашего сайта или вернитесь на главную</p>
            <a href="<?=get_site_url();?>" class="btn">Вернуться на главную</a>
        </div>
    </section>
</main>
<?get_footer();