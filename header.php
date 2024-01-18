<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<?php wp_head(); ?>
</head>

<body>
	<header class="header">
		<div class="header__container">
			<a href="<?=get_site_url();?>" class="header__logo logo"><img src="<?$custom_logo__url = wp_get_attachment_image_src(get_theme_mod('custom_logo')); echo $custom_logo__url[0];?>" alt="logo-steel-center"></a>

			<nav class="nav">
				<div class="nav__header">
					<div class="logo"><img src="<?$custom_logo__url = wp_get_attachment_image_src(get_theme_mod('custom_logo')); echo $custom_logo__url[0];?>" alt="logo-steel-center"></div>
					<button class="close"></button>
				</div>

				<? wp_nav_menu(array(
					'theme_location'  => 'header-menu',
					'menu_id'      => false,
					'container'       => false,
					'container_class' => false,
					'menu_class'      => false,
					'items_wrap'      => '%3$s',
					'order' => 'ASC',
					'show_carets' => true,
					'walker' => new header_menu()   
				)); ?>

				<?contacts('burger-menu','mob-section');?>

			</nav>
			<?contacts('header','header__contacts');?>
			<button data-btn="price" class="open-popup-js btn"><span>Запросить расчет</span></button>

			<div class="burger">
				<button class="burger-menu">
					<div>
						<span></span>
						<span></span>
						<span></span>
					</div>
				</button>
			</div>
		</div>
		<div class="overlay-menu"></div>
		<? if (!is_front_page()) {
			wp_nav_menu(array(
				'theme_location'  => 'services',
				'menu_id'      => false,
				'container'       => false,
				'container_class' => false,
				'menu_class'      => false,
				'items_wrap'      => '<ul class="another-header __container">%3$s</ul>',
				'order' => 'ASC',
				//'show_carets' => true,
				'before' => 'another-header__item',
				'walker' => new header_menu_another()   
			));
		} ?>
	</header>