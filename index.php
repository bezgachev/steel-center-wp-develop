<?get_header();?>
	<main>
		<section class="main first-screen">
			<div class="first-screen__container">
				<h1 id="title"><?=get_field('title-h1');?></h1>
				<?wp_nav_menu(array(
					'theme_location'  => 'services',
					'menu_id'      => false,
					'container'       => false,
					'container_class' => false,
					'menu_class'      => false,
					'items_wrap'      => '<ul class="first-screen__links">%3$s</ul>',
					'order' => 'ASC',
					'before' => 'first-screen__link',
					'walker' => new header_menu_another()   
				));
				$class_item = 'first-screen';
				$id_current = get_the_ID();
				services_item(null, $id_current, $class_item); ?>
			</div>
		</section>
		<section class="main full-services">
			<div class="full-services__container">
				<h2 class="full-services__title"><span>Наши услуги</span></h2>
				<p class="full-services__subtitle"><?=get_field('services-subtitle');?>
				
				</p>
				<?wp_nav_menu(array(
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
		</section>
		<?
		$advantages = get_field('advantage');
			if ($advantages) {
				echo '<section class="main advantage"><div class="advantage__container"><h2 class="advantage__title"><span>Преимущества</span></h2><div class="advantage__body">';
				$count_row = sizeof($advantages);
				$cols = $count_row / 2;
				$col = round($cols);
				$count = 1;
				foreach($advantages as $advantage_index => $advantage) {
					$item_span = $advantage['advantage-span'];
					$item_h3 = $advantage['advantage-h3'];
					$item_p = $advantage['advantage-p'];
					if ($count == 1){
						echo '<div class="advantage__items">';
					}
					if ($count == ($col+1)) {
						echo '</div><div class="advantage__items">';
					}
					echo '<div class="advantage__item">';
					echo '<h3>';
						if ($item_span == '∞') {
							echo '<svg width="43" height="39" viewBox="0 0 43 39" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_859_11109)"><path d="M33.325 10.65C30.745 10.65 28.3084 11.6581 26.5615 13.413L21.5 17.913L21.491 17.904L18.7766 20.307L18.7945 20.3249L13.966 24.627C12.8194 25.77 11.2965 26.4 9.675 26.4C6.32461 26.4 3.60125 23.673 3.60125 20.325C3.60125 16.977 6.32461 14.25 9.675 14.25C11.2965 14.25 12.8194 14.8801 14.0466 16.104L16.0802 17.913L18.7855 15.51L16.5191 13.494C14.6916 11.6671 12.2638 10.6591 9.67492 10.6591C4.33586 10.65 0 14.9881 0 20.325C0 25.662 4.33586 30 9.675 30C12.255 30 14.6827 28.992 16.4385 27.246L21.5 22.737L21.509 22.7461L24.2144 20.3341L24.2054 20.325L29.0339 16.032C30.1806 14.8801 31.7035 14.25 33.325 14.25C36.6754 14.25 39.3988 16.977 39.3988 20.325C39.3988 23.673 36.6754 26.4 33.325 26.4C31.7035 26.4 30.1806 25.77 28.9534 24.555L26.9198 22.746L24.2144 25.158L26.4809 27.174C28.3084 29.0009 30.7361 29.9999 33.3161 29.9999C38.6641 30 43 25.662 43 20.325C43 14.9881 38.6641 10.65 33.325 10.65Z" fill="#F52929" /></g><defs><clipPath id="clip0_859_11109"><rect width="43" height="39" fill="white" /></clipPath></defs></svg>';
							
						}else {
							echo '<span>'.$item_span.'</span>';
						}
						if ($item_h3) {
							echo ' '.$item_h3;
						}
					echo '</h3>';
					echo '<p>'.$item_p.'</p>';
					if ($count !== $col) {
						echo '</div>';
					}
					if ($count == $count_row) {
						echo '</div>';
					}
					$count++;
				}
				echo '<div class="advantage__img"><img src="'.get_template_directory_uri().'/assets/img/bg/main_advantage.png" alt="main_advantage"></div>';
				echo '</div></div></section>';
		}
		?>
		<?section_works('main');?>
        <?section_example_order('main');?>
	</main>
<?get_footer();?>