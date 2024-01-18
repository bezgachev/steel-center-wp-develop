<?php
/*
Template Name: Политика конфиденциальности
Template Post Type: page
*/
get_header();
$title_page = single_post_title('',false);
?>
<main>
	<section class="bread-crumb __container">
			<?breadcrumb_home_url();?>
			<span class="bread-crumb__item"><?=$title_page;?></span>
	</section>
	<div class="privacy-policy__body">
		<div class="privacy-policy__container">
			<h1><?=$title_page;?></h1>
			<p><?the_content();?></p>
		</div>
	</div>
</main>
<?get_footer();?>