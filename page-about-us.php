<?
/*
Template Name: О компании
Template Post Type: page
*/
get_header();
$title_page = wp_title('',false);
$id_current = get_the_ID();
?>
<main>
	<div class="bg-about">
		<section class="about ">
			<div class="about__container">
				<section class="bread-crumb __container">
					<?breadcrumb_home_url();?>
					<span class="bread-crumb__item"><?echo $title_page;?></span>
				</section>
				<h1><?echo $title_page;?></h1>
				<div class="about__img"><img src="<?echo get_field('about-img');?>" alt=""></div>
				<div class="about__descr">
					<p><?echo get_field('about-descr');?></p>
				</div>
				<div class="about__info">Работаем с <span>&nbsp;2007&nbsp;</span> года в Йошкар-Оле</div>
			</div>
		</section>
		<?$fleet_sliders = get_field('fleet');
		if($fleet_sliders) {?>
			<section class="gal">
				<h2 class="__container">Собственный автопарк станков</h2>
				<div class="__container">
					<div class="accordian accordian-swiper swiper" data-mobile="false">
						<div class="swiper-wrapper">
							<?
							foreach($fleet_sliders as $index => $fleet_slider) {
								$count = $fleet_slider['fleet-count'];
								$title = $fleet_slider['fleet-title'];
								$descr = $fleet_slider['fleet-descr'];
								$img_url = $fleet_slider['fleet-img'];
								echo '
								<div class="swiper-slide">
									<div class="swiper-slide_title">
										<h3><span>'.$count.'</span>'.$title.'</h3>
										<p>'.$descr.'</p>
									</div>
									<div class="swiper-slide_img"><img src="'.$img_url.'"/></div>
								</div>';
							}?>
						</div>
					</div>
					<div class="swiper accordian-swiper-thumbs" data-mobile="false">
						<div class="swiper-wrapper"></div>
					</div>
				</div>
			</section>
		<?}?>
	</div>
	<section class="trigger">
		<h2>Почему клиенты выбирают нас</h2>
		<div class="trigger__container">
			<div class="trigger__items">
				<div class="trigger__item">
					<h3>Качество реза</h3>
					<p><?echo get_field('trigger-item-1');?></p>
				</div>
				<div class="trigger__item">
					<h3>Своё оборудование</h3>
					<p><?echo get_field('trigger-item-2');?></p>
				</div>
				<div class="trigger__item">
					<h3>Быстрые сроки</h3>
					<p><?echo get_field('trigger-item-3');?></p>
				</div>
				<div class="trigger__item">
					<h3>Гибкие цены</h3>
					<p><?echo get_field('trigger-item-4');?></p>
				</div>
				<div class="trigger__item">
					<h3>3D проектирование</h3>
					<p><?echo get_field('trigger-item-5');?></p>
				</div>
				<div class="trigger__item">
					<h3>Доработка изделий</h3>
					<p><?echo get_field('trigger-item-6');?></p>
				</div>
			</div>
		</div>
	</section>
	<div class="bg-gallery">
		<section class="gallery">
			<h2>Знакомьтесь с нашим производством ближе</h2>
			<?$gallerys = get_field('gallery-items');
			echo '<div class="gallery__items">';
			$count = 1;
			foreach($gallerys as $gallery) {
				echo '<div class="gallery__item" id="'.$count.'"><img src="'.$gallery.'" alt="gallery__item-'.$count.'"></div>';
				$count++;
			}
			echo '</div>';
			?>
		</section>
		
		<?contacts_form($id_current, null);?>
			</div>
		</section>
	</div>
</main>
<div class="modal-img">
	<div class="modal-img__container">
		<div class="popup-prev">
			<div class="popup-pagination"></div>
			<div class="popup-prev__hover">
				<svg width="38" height="38" viewBox="0 0 38 38" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M10.6289 19.1241H28.0104C28.3568 19.1241 28.6367 18.8442 28.6367 18.4978C28.6367 18.1513 28.3568 17.8714 28.0104 17.8714H12.1419L15.9432 14.0702C16.1878 13.8255 16.1878 13.4282 15.9432 13.1835C15.6985 12.9388 15.3011 12.9388 15.0565 13.1835L10.1846 18.0554C10.0045 18.2355 9.95164 18.5036 10.0495 18.7385C10.1474 18.9715 10.3764 19.1241 10.6289 19.1241Z" fill="#E4E4E4" />
					<path d="M15.5069 24.0011C15.6674 24.0011 15.828 23.9404 15.9493 23.8171C16.194 23.5724 16.194 23.1751 15.9493 22.9304L11.0715 18.0526C10.8269 17.808 10.4295 17.808 10.1848 18.0526C9.94018 18.2973 9.94018 18.6947 10.1848 18.9393L15.0626 23.8171C15.1859 23.9404 15.3464 24.0011 15.5069 24.0011Z" fill="#E4E4E4" /> </svg>
			</div>
		</div>
		<div class="popup-img swiper">
			<?
			echo '<div class="popup-img-wrapper swiper-wrapper">';
				foreach($gallerys as $gallery) {
					echo '<div class="popup-img-slide swiper-slide"><img src="'.get_template_directory_uri().'/assets/img/pixel.png" data-src="'.$gallery.'" class="swiper-lazy"><div class="swiper-lazy-preloader"></div></div>';
				}
			echo '</div>';
			?>			
		</div>
		<div class="popup-next">
			<button class="close-button"></button>
			<div class="popup-next__hover">
				<svg width="38" height="38" viewBox="0 0 38 38" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M27.3711 19.1241H9.98964C9.64319 19.1241 9.36328 18.8442 9.36328 18.4978C9.36328 18.1513 9.64319 17.8714 9.98964 17.8714H25.8581L22.0568 14.0702C21.8122 13.8255 21.8122 13.4282 22.0568 13.1835C22.3015 12.9388 22.6989 12.9388 22.9435 13.1835L27.8154 18.0554C27.9955 18.2355 28.0484 18.5036 27.9505 18.7385C27.8526 18.9715 27.6236 19.1241 27.3711 19.1241Z" fill="#E4E4E4" />
					<path d="M22.4931 24.0011C22.3326 24.0011 22.172 23.9404 22.0507 23.8171C21.806 23.5724 21.806 23.1751 22.0507 22.9304L26.9285 18.0526C27.1731 17.808 27.5705 17.808 27.8152 18.0526C28.0598 18.2973 28.0598 18.6947 27.8152 18.9393L22.9374 23.8171C22.8141 23.9404 22.6536 24.0011 22.4931 24.0011Z" fill="#E4E4E4" /> </svg>
			</div>
		</div>
	</div>
</div>
<?get_footer();?>