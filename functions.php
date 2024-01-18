<?
//Скрытие меню навигации админ панели при просмотре сайта
// add_filter('show_admin_bar', '__return_false');
// function remove_admin_bar() {
// 	if (!current_user_can('administrator') && !is_admin()) {
// 		show_admin_bar(false);
// 	}
// }

//удаляем версию движка WP в конце файлов css/js
add_filter('style_loader_src', 'remove_cssjs_ver', 10,2);
//add_filter('script_loader_src', 'remove_cssjs_ver', 10,2);
function remove_cssjs_ver($src) {
	if(strpos($src,'?ver='))
		$src = remove_query_arg('ver', $src);
	return $src;
}

// Подключаем стили css в header
add_action( 'wp_enqueue_scripts', 'style_theme');
function style_theme() {
	$main = get_stylesheet_directory() . '/assets/css/style.min.css';
	wp_enqueue_style( 'main', get_stylesheet_directory_uri().'/assets/css/style.min.css?leave=1', null, filemtime($main));
}

// Подключаем скрипты js footer
add_action( 'wp_enqueue_scripts', 'scripts_theme' );
function scripts_theme() {	
	wp_deregister_script('jquery');
	wp_register_script( 'jquery', get_template_directory_uri().'/assets/js/jquery-2.2.4.min.js', false, null, true );
	wp_enqueue_script('jquery');

	wp_enqueue_script( 'maskedinput', get_template_directory_uri() . '/assets/js/maskedinput.js', array('jquery'), null, true);

    $swiper = get_stylesheet_directory() . '/assets/js/swiper.min.js';
	wp_enqueue_script( 'swiper', get_template_directory_uri().'/assets/js/swiper.min.js?leave=1', array('jquery'), filemtime($swiper), true);

	$main = get_stylesheet_directory() . '/assets/js/scripts.js';
	wp_enqueue_script( 'main', get_template_directory_uri().'/assets/js/scripts.js?leave=1', array('jquery'), filemtime($main), true);

    if (!is_front_page()) {
		$map = get_stylesheet_directory() . '/assets/js/ymaps.js';
		wp_enqueue_script( 'map', get_template_directory_uri().'/assets/js/ymaps.js?leave=1', array('jquery'), filemtime($map), true);
        wp_enqueue_script('map-api', get_template_directory_uri() . '/assets/js/ymaps-api.js', array('jquery'), null, true);
    }

	wp_enqueue_script( 'jquery-form' );
	// Подключаем файл скрипта формы обратной связи 
	$feedback = get_stylesheet_directory() . '/assets/js/feedback.js';
	wp_enqueue_script( 'feedback', get_template_directory_uri().'/assets/js/feedback.js?leave=1', array('jquery'), filemtime($feedback), true);

	// Задаем данные обьекта ajax
	wp_localize_script(
		'feedback',
		'feedback_object',
		array(
			'url'   => admin_url( 'admin-ajax.php' ),
			'nonce' => wp_create_nonce( 'feedback-nonce' ),
		)
	);

	if (is_page(65)) {
		$filter = get_stylesheet_directory() . '/assets/js/filter.js';
		wp_enqueue_script( 'filter', get_template_directory_uri().'/assets/js/filter.js?leave=1', array('jquery'), filemtime($filter), true);
		wp_localize_script(
			'filter',
			'filter_object',
			array(
				'url'   => admin_url( 'admin-ajax.php' ),
				'nonce' => wp_create_nonce( 'filter-nonce' ),
			)
		);
	}

}

add_action( 'wp_ajax_filter_action', 'filter_post' );
add_action( 'wp_ajax_nopriv_filter_action', 'filter_post' );

function filter_post(){

	if (!wp_verify_nonce( $_POST['nonce'], 'filter-nonce' ) ) {
		wp_die();
	}

	$term = sanitize_text_field($_POST['terms']);
	if ((isset($term)) && (!empty($term))) {
		global $post;
		$paged = $_POST['paged'];
		if (!$paged) {
			$paged = 1;
		}else {
			if ($term == 'all') {
				$url = ''.get_permalink(65).'page/';
				$paged_replace = str_replace($url, "", $paged);
				$paged_replace = preg_replace('/[^0-9]/', '', $paged_replace);
				$paged = (int)$paged_replace;
			}else {
				$paged_replace = str_replace ("/wp-admin/admin-ajax.php?page=" , "", $paged);
				$paged = (int)$paged_replace;
			}
		}
		$args = array(
			'post_type' => 'post',
			'paged' => $paged,
			'posts_per_page' => 8,
		);
		if ($term !== 'all') {
			$args['tax_query'][] = array(
				'taxonomy' => 'category',
				'field' => 'id',
				'terms' => $term,
				'posts_per_page' => 8,
			);
		}
		$query = new WP_Query($args);
		if ($query->have_posts()) {
			echo '<div class="our-works__cards">';
				while ($query->have_posts()) {
					$query->the_post();?>
					<div class="our-works__card">
						<div class="card-swiper swiper our-works__card_img">
							<div class="swiper-wrapper">
								<?$works_imgs = get_field('fotografii');
								$count = 1;
								foreach ($works_imgs as $works_img) {
									echo '<div class="swiper-slide card__img"><img data-src="'.$works_img.'" src="'.url_lazy_pixel().'" alt="work-img-'.$count.'" class="swiper-lazy"><div class="swiper-lazy-preloader"></div></div>';
									$count++;
								}?>
								<div class="swiper-pagination"></div>
							</div>
							<div class="card-prev"></div>
							<div class="card-next"></div>
						</div>
						<div class="our-works__card_text technology">
							<h3>Технология:</h3>
							<?$cats = get_the_category();
							if($cats){
								echo '<p>';
								foreach( $cats as $cat ){
									if (!next($cats)) {
										echo $cat->name;
									}else {
										echo $cat->name.', ';
									}
								}
								echo '</p>';
							}?>
						</div>
						<div class="our-works__card_text type">
							<h3>Вид металла:</h3>
							<?
							$tags = get_the_tags();
							foreach ($tags as $tag) {
								$html = "<p>{$tag->name}</p>";
							}if ($tags) {echo $html;}
							?>
						</div>
						<div class="our-works__card_text thickness">
							<h3>Толщина:</h3>
							<p><?=get_field('tolshhina');?> мм</p>
						</div>
					</div>

				<?
				}
				echo '</div>';
				$total   = $query->max_num_pages;
				$paginate_links = paginate_links(
					apply_filters('the_posts_pagination_args',
						array(
							'base'         => @add_query_arg('page','%#%'),
							'format'       => '?page=%#%',
							'add_args'     => false,            
							'current'      => $paged,
							'total'        => $total,
							'prev_text'    => '',
							'next_text'    => '',
							'type'         => 'array',
							'end_size'     => 1,
							'mid_size'     => 2,
							'show_all'     => false,
						)
					)
				);
				if (is_array($paginate_links)) {
					echo '<nav class="pagination" data-term="'.$term.'">';
						if ($paged == 1 ) {
							echo '<span class="prev disabled"></span>';
						}
						foreach ($paginate_links as $paginate_link) {
							$paginate_link = str_replace( 'page-numbers', 'page-numbers', $paginate_link );
							echo wp_kses_post($paginate_link);
						}
						if ($total == $paged) { 
							echo '<span class="next disabled"></span>';
						}
					echo '</nav>';
				}
				wp_reset_postdata();
		} else {
			echo '<p class="our-works-message">Постов по данному запросу не обнаружено</p>';
		}
		wp_reset_postdata();
	}
	else {
		echo '<p class="our-works-message">Произошла ошибка. Попробуйте еще раз</p>';
	}
	wp_die();
}

// Регистрируем меню, виджеты, свои размеры для img, настройки темы
add_action( 'after_setup_theme', 'theme_register_nav_menu' );
function theme_register_nav_menu() {
    register_nav_menu( 'header-menu', 'Меню в шапке');
	// register_nav_menu( 'nav-services', 'Услуги в шапке');
	register_nav_menu( 'services', 'Услуги');
    add_theme_support(
		'custom-logo',
		array(
			'width'       => 170,
			'height'      => 45,
			'flex-width'  => true,
			'flex-height' => true,
		));
        add_theme_support( 'title-tag' );
		// add_theme_support( 'post_tag', 'page' );
}

// ------------------------- НАЧАЛО СВОЕГО ШАБЛОНА МЕНЮ НАВИГАЦИИ -------------------------
class header_menu extends Walker_Nav_Menu {
	function start_el(&$output, $item, $depth=0, $args=[], $id=0) {
		// $menu_attr_title = $item->attr_title;
		$menu_class = $item->classes[0];
		if ($item->url && $item->url != '') {
			if ($depth === 0) {
				if ($menu_class === 'services') {
					$output .= '<div class="nav__wrapper"><div><img src="'.get_template_directory_uri().'/assets/img/icons/icon-header/'.$menu_class.'.svg" alt="'.$menu_class.'"><span>'.$item->title.'</span></div>';
				}
				else {
					$output .= '<div class="nav__wrapper"><a href="'.$item->url.'"><img src="'.get_template_directory_uri().'/assets/img/icons/icon-header/'.$menu_class.'.svg" alt="'.$menu_class.'"><span>'.$item->title.'</span></a>';
				}
			}
			else if ($depth === 1) { 
				$output .= '<li> <a href="'.$item->url.'"><span>'.$item->title.'</span></a>';
			}
			else {
				$output .= '<li><a href="'.$item->url.'"><span>'.$item->title.'</span></a><li>';
			}
		}
	}

	function start_lvl(&$output, $depth=0, $args=null) {
		if ($depth === 1) {
			$output .= '<ul class="nav__wrapper_items2">';	
		}
		else {
			$output .= '<ul class="nav__wrapper_items1">';	
		}
	}

	function end_lvl(&$output, $depth=0, $args=null) {
		if ($depth === 1) {
			$output .= '</ul>';	
		}
		else {
			$output .= '</ul>';	
		}
	}

	function end_el(&$output, $item, $depth=0, $args=null) { 
		if ($depth === 0) {
			$output .= '</div>';
		}
		else if ($depth === 1) { 
			$output .= '</li>';
		}
	}
}

class header_menu_another extends Walker_Nav_Menu {
	function start_el(&$output, $item, $depth=0, $args=[], $id=0) {
		$menu_before = $args->before;
		$menu_class = $item->classes[0];
		if ($item->url && $item->url != '') {

			if ($menu_before === 'menu-footer') {
				if ($depth === 0 && $menu_class !== 'services') {
					$output .= '<li><a href="'.$item->url.'">'.$item->title.'</a></li>';
				}
			}
			else {
				$output .= '<li class="'.$menu_before.'"><a href="'.$item->url.'">'.$item->title.'</a></li>';
			}
		}
	}
	function start_lvl(&$output, $depth=0, $args=null) {
		$output .= '';
	}
	function end_lvl(&$output, $depth=0, $args=null) {
		$output .= '';
	}
	function end_el(&$output, $item, $depth=0, $args=null) { 
		$output .= '';
	}
}

class services extends Walker_Nav_Menu {
	function start_el(&$output, $item, $depth=0, $args=[], $id=0) {
		$id_current = (int)$args->before;
		$id = (int)get_post_meta($item->ID, '_menu_item_object_id', true);
		$img = get_field('services-img-min',$id);
		$price = get_field('main-price',$id);
		$unit = get_field('unit',$id);
		$title = $item->title;

		if ($item->url && $item->url != '') {
			$output_block = '<a href="'.$item->url.'" class="full-services__card" id="'.$id.'" id-cur="'.$id_current.'"><div class="full-services__card_img"><img src="'.$img.'" alt="'.$title.'"></div><div class="full-services__card_body"><div class="title"><span>'.$title.'</span></div><div class="price"> от <span>'.$price.'</span> '.$unit.' </div><div class="button"><div class="btn">Подробнее</div></div></div></a>';
			if ($id_current === -1) {
				$output .= $output_block;
			}
			else {
				if ($id !== $id_current) {
					$output .= $output_block;
				}
				$output .= '';
			}
		}

	}
	function start_lvl(&$output, $depth=0, $args=null) {
		$output .= '';
	}
	function end_lvl(&$output, $depth=0, $args=null) {
		$output .= '';
	}
	function end_el(&$output, $item, $depth=0, $args=null) { 
		$output .= '';
	}
}

// ------------------------- КОНЕЦ СВОЕГО ШАБЛОНА МЕНЮ НАВИГАЦИИ -------------------------

// ------------------------- НАЧАЛО ДИНАМИЧЕСКОЕ УПР.ВЫВОДА ИНФ. -------------------------
function breadcrumb_home_url() {
	echo '<a class="bread-crumb__item" href="'.get_site_url().'"><svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M11.7 5.21942L11.6991 5.21851L6.80403 0.323639C6.59538 0.114899 6.31798 0 6.0229 0C5.72783 0 5.45042 0.114899 5.24168 0.323639L0.349194 5.21603L0.344251 5.22107C-0.0842162 5.65201 -0.0834838 6.3512 0.346356 6.78104C0.542737 6.97751 0.802014 7.09122 1.07933 7.10321C1.09068 7.10431 1.10203 7.10486 1.11348 7.10486H1.30848V10.7071C1.30848 11.42 1.88856 12 2.60148 12H4.51658C4.71077 12 4.86815 11.8425 4.86815 11.6484V8.82422C4.86815 8.49893 5.13282 8.23434 5.45811 8.23434H6.58769C6.91298 8.23434 7.17756 8.49893 7.17756 8.82422V11.6484C7.17756 11.8425 7.33494 12 7.52913 12H9.44423C10.1572 12 10.7372 11.42 10.7372 10.7071V7.10486H10.9181C11.2131 7.10486 11.4905 6.98996 11.6994 6.78113C12.1297 6.35065 12.1298 5.65018 11.7 5.21942Z" fill="#E2E5EB" /></svg></a>';
}

function dinner_time($id) {
	$dinner_time = get_field('contacts-time-dinner', $id);if ($dinner_time) {echo '<span>обед: '.$dinner_time.'</span>';}
}

function contacts($args,$class) {
	$id = 19;
	$tel_main = get_field('contacts-tel-main', $id);
	$email_company = get_field('contacts-email', $id);
	$country = get_field('contacts-country', $id);
	$city = get_field('contacts-city', $id);
	$address = get_field('contacts-address', $id);
	$ymaps_geo = get_field('contacts-geo', $id);
	$url_2gis = get_field('contacts-2gis', $id);
	$work_time = get_field('contacts-time-work', $id);
	//$dinner_time = get_field('contacts-time-dinner', $id);
	$tel_main1 = mb_substr($tel_main, 0, 3, 'UTF8');
	$tel_main2 = mb_substr($tel_main, 3, 3, 'UTF8');
	$tel_main3 = mb_substr($tel_main, 6, 2, 'UTF8');
	$tel_main4 = mb_substr($tel_main, 8, 2, 'UTF8');
	$tel_main_all = '+7 ('.$tel_main1.') '.$tel_main2.'-'.$tel_main3.'-'.$tel_main4;
	if ($args === 'header') {
		// class=header__contacts
		echo '<div class="'.$class.'">';
			echo '<a class="header__contacts_tel" href="tel:+7'.$tel_main.'">'.$tel_main_all.'</a>';
			echo '<a class="header__contacts_mail" href="mailto:'.$email_company.'">'.$email_company.'</a>';
		echo '</div>';
	}
	else if ($args === 'page-contacts') {
		// class=contacts__descr_items
		echo '<div class="'.$class.'">';
			$address_full = $country.', '.$city.', '.$address.'';
			echo '<a href="'.$url_2gis.'" target="_blank" class="contacts__descr_address">'.$address_full.'</a>';
			echo '<div class="contacts__descr_tel"><a href="tel:+7'.$tel_main.'"><span>Контактный тел.:</span><nobr>'.$tel_main_all.'</nobr></a>';
				$tel_other = get_field('contacts-tel-ad', $id);
				if ($tel_other) {
					$office_on = $tel_other['contacts-tel-office-enabled'];
					if ($office_on) {
						$type = $tel_other['contacts-tel-office-type'];
						$tel = $tel_other['contacts-tel-office-input'];
						if ($type === 'mobile') {
							$tel1 = mb_substr($tel, 0, 3, 'UTF8');
							$tel2 = mb_substr($tel, 3, 3, 'UTF8');
						}
						else if ($type === 'home') {
							$tel1 = mb_substr($tel, 0, 4, 'UTF8');
							$tel2 = mb_substr($tel, 4, 2, 'UTF8');
						}
						$tel3 = mb_substr($tel, 6, 2, 'UTF8');
						$tel4 = mb_substr($tel, 8, 2, 'UTF8');
						$tel_other_all = '+7 ('.$tel1.') '.$tel2.'-'.$tel3.'-'.$tel4;
						echo '<a href="tel:+7'.$tel.'"><span>Бухгалтерия:</span><nobr>'.$tel_other_all.'</nobr></a>';
					}
				}
			echo '</div>';
			echo '<a class="contacts__descr_mail" href="mailto:'.$email_company.'">'.$email_company.'</a>';
			echo '<div class="contacts__descr_duty">Пн – Пт: '.$work_time.'';
				dinner_time($id);
			echo '</div>';
		echo '</div>';
	}
	else if ($args === 'footer') {
		// class=footer__descr
		$address_full = $country.', '.$city.',<br> '.$address.'';
		echo '<div class="'.$class.'_address address">'.$address_full.'</div>';
		echo '<div class="'.$class.'_duty duty">Пн – Пт: '.$work_time.'';
			dinner_time($id);
		echo '</div>';
		echo '<div class="'.$class.'_connection connection">';
			echo '<a class="connection__tel" href="tel:+7'.$tel_main.'">'.$tel_main_all.'</a>';
			echo '<a class="connection__mail" href="mailto:'.$email_company.'">'.$email_company.'</a>';
		echo '</div>';
	}
	else if ($args === 'burger-menu') {
		// class=mob-section
		$address_full = $country.', '.$city.',<br> '.$address.'';
		echo '<div class="'.$class.'">';
			echo '<div class="address">'.$address_full.'</div>';
			echo '<div class="duty">Пн – Пт: '.$work_time.'';
				dinner_time($id);
			echo '</div>';
			echo '<div class="connection">';
				echo '<a class="connection__tel" href="tel:+7'.$tel_main.'">'.$tel_main_all.'</a>';
				echo '<a class="connection__mail" href="mailto:'.$email_company.'">'.$email_company.'</a>';
			echo '</div>';
			messeng_social('');
		echo '</div>';
	}
	else if ($args === 'example-order') {
		echo '<a href="tel:+7'.$tel_main.'">'.$tel_main_all.'</a>';
	}
	else if ($args === 'map') {
		$address_full = $city.', '.$address.'';
		echo '<div class="contacts__map-address '.$class.'"><span data-type-geo="Производство" data-addr="'.$address_full.'" data-geo="'.$ymaps_geo.'" data-2gis="'.$url_2gis.'"></span></div>';
	}

}

function messeng_social($class) {
	$id = 19;
	$socials = get_field('enable_display_social', $id);
	if ($socials) {
		$whatsapp = get_field('whatsapp', $id);
		$viber = get_field('viber', $id);
		$telegram = get_field('telegram', $id);
		$instagram = get_field('instagram', $id);
		$vk = get_field('vk', $id);
		$facebook = get_field('facebook', $id);
		$ok = get_field('ok', $id);
		$youtube = get_field('youtube', $id);
		if (!empty($class)) {
			echo '<div class="'.$class.'">';
		}
			echo '<div class="social">';
				if($socials && in_array('whatsapp', $socials) && $whatsapp) {
					$whatsapp_cut = mb_substr($whatsapp, 1, 10, 'UTF8');
					echo '<a href="https://api.whatsapp.com/send?phone=+7'.$whatsapp_cut.'" class="whatsapp" target="_blank"><svg width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12.9966 3.66797C11.2773 3.66862 9.59168 4.14426 8.1256 5.04241C6.65952 5.94055 5.47002 7.22629 4.68833 8.75776C3.90664 10.2892 3.56316 12.0069 3.69578 13.7212C3.82839 15.4356 4.43196 17.08 5.43987 18.473L4.27335 21.9403L7.8639 20.7923C9.07858 21.5936 10.4638 22.0998 11.9089 22.2703C13.354 22.4409 14.819 22.2711 16.1868 21.7745C17.5546 21.2779 18.7874 20.4683 19.7866 19.4103C20.7858 18.3523 21.5238 17.0754 21.9416 15.6813C22.3595 14.2873 22.4456 12.8148 22.193 11.3816C21.9405 9.94835 21.3564 8.59403 20.4873 7.4268C19.6181 6.25957 18.4882 5.31178 17.1875 4.65913C15.8869 4.00648 14.4517 3.66706 12.9966 3.66797ZM17.9566 16.8676L16.9628 17.8616C15.9176 18.907 13.1459 17.7566 10.6892 15.295C8.23252 12.8333 7.13133 10.0683 8.1252 9.03464L9.11908 8.04064C9.31154 7.86371 9.56341 7.76552 9.82482 7.76552C10.0862 7.76552 10.3381 7.86371 10.5306 8.04064L11.9957 9.5013C12.1152 9.61746 12.2028 9.76236 12.2502 9.92212C12.2977 10.0819 12.3033 10.2511 12.2665 10.4137C12.2297 10.5762 12.1518 10.7266 12.0403 10.8504C11.9287 10.9742 11.7873 11.0672 11.6294 11.1206C11.3966 11.195 11.2014 11.3564 11.0845 11.5711C10.9676 11.7858 10.938 12.0373 11.0018 12.2733C11.2631 13.37 12.6746 14.7303 13.7222 14.9916C13.9569 15.0409 14.2015 15.0038 14.411 14.8872C14.6206 14.7707 14.7811 14.5824 14.863 14.357C14.9158 14.1968 15.0093 14.0531 15.1342 13.9399C15.2591 13.8266 15.4112 13.7477 15.5758 13.7108C15.7403 13.6738 15.9115 13.6802 16.0729 13.7293C16.2342 13.7783 16.38 13.8684 16.4962 13.9906L17.959 15.456C18.1356 15.6487 18.2333 15.9008 18.2329 16.1623C18.2325 16.4237 18.1339 16.6754 17.9566 16.8676Z" fill="#F1F1F1"></path></svg></a>';
				}
				if($socials && in_array('viber', $socials) && $viber) {
					$viber_cut = mb_substr($viber, 1, 10, 'UTF8');
					echo '<a href="viber://chat?number=+7'.$viber_cut.'" class="viber" target="_blank"><svg width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M15.0754 3.66797H10.9272C9.00195 3.66797 7.15553 4.4219 5.79416 5.7639C4.43278 7.1059 3.66797 8.92605 3.66797 10.8239V13.8908C3.66739 15.2454 4.0569 16.5725 4.79122 17.7175C5.52553 18.8626 6.57448 19.7787 7.81612 20.3592V23.7762C7.81735 23.8429 7.8383 23.9077 7.87639 23.9628C7.91449 24.0179 7.96806 24.0608 8.03055 24.0864C8.09304 24.1119 8.16172 24.1188 8.22817 24.1064C8.29463 24.094 8.35595 24.0627 8.40464 24.0164L11.4172 21.0467H15.0754C17.0007 21.0467 18.8471 20.2928 20.2084 18.9508C21.5698 17.6088 22.3346 15.7886 22.3346 13.8908V10.8239C22.3346 8.92605 21.5698 7.1059 20.2084 5.7639C18.8471 4.4219 17.0007 3.66797 15.0754 3.66797ZM17.7924 17.0803L16.7554 18.1026C15.6509 19.1683 12.768 17.9518 10.2272 15.3935C7.68649 12.8353 6.57167 9.96521 7.63464 8.89949L8.67167 7.87721C8.87492 7.69736 9.13918 7.59916 9.41229 7.602C9.6854 7.60484 9.9475 7.70851 10.1469 7.89254L11.6609 9.42596C11.7812 9.54828 11.8679 9.69874 11.9129 9.86319C11.9579 10.0276 11.9598 10.2007 11.9183 10.366C11.8768 10.5314 11.7934 10.6836 11.6758 10.8084C11.5582 10.9332 11.4103 11.0265 11.2461 11.0795C11.0027 11.1531 10.7974 11.3163 10.6728 11.5353C10.5483 11.7542 10.514 12.012 10.5772 12.2551C10.8365 13.3899 12.2987 14.8108 13.3876 15.0894C13.6331 15.1471 13.8915 15.1145 14.1144 14.9978C14.3372 14.8811 14.5093 14.6884 14.5983 14.4556C14.6559 14.2932 14.7548 14.1482 14.8856 14.0345C15.0165 13.9207 15.1748 13.8421 15.3454 13.8062C15.516 13.7702 15.6931 13.7782 15.8597 13.8292C16.0262 13.8803 16.1767 13.9728 16.2965 14.0978L17.808 15.6312C17.9895 15.831 18.0886 16.0905 18.0857 16.3587C18.0828 16.6269 17.9782 16.8843 17.7924 17.0803ZM13.945 8.5238C13.8419 8.52331 13.7388 8.52929 13.6365 8.54169C13.5914 8.54655 13.5457 8.54261 13.5022 8.53009C13.4586 8.51757 13.418 8.49671 13.3826 8.46871C13.3472 8.44071 13.3177 8.4061 13.2959 8.36688C13.2741 8.32766 13.2603 8.28458 13.2554 8.24012C13.2504 8.19565 13.2544 8.15065 13.2671 8.10771C13.2798 8.06476 13.301 8.0247 13.3294 7.98981C13.3578 7.95493 13.3929 7.9259 13.4327 7.90439C13.4725 7.88287 13.5162 7.86929 13.5613 7.86443C13.6887 7.84997 13.8168 7.84314 13.945 7.84398C14.8625 7.8433 15.7427 8.20177 16.3922 8.84058C17.0417 9.4794 17.4073 10.3463 17.4087 11.2507C17.4096 11.3771 17.4026 11.5034 17.388 11.629C17.383 11.6734 17.3693 11.7165 17.3474 11.7557C17.3256 11.795 17.2962 11.8296 17.2608 11.8576C17.2254 11.8856 17.1847 11.9064 17.1412 11.919C17.0976 11.9315 17.052 11.9354 17.0069 11.9305C16.9617 11.9257 16.918 11.9121 16.8783 11.8906C16.8385 11.8691 16.8034 11.84 16.775 11.8052C16.7466 11.7703 16.7254 11.7302 16.7127 11.6873C16.7 11.6443 16.696 11.5993 16.7009 11.5549C16.7122 11.4539 16.7183 11.3523 16.7191 11.2507C16.7177 10.5266 16.4247 9.83261 15.9046 9.32128C15.3844 8.80996 14.6796 8.52312 13.945 8.5238ZM16.0191 11.2507C16.0116 11.3361 15.9719 11.4156 15.9078 11.4736C15.8437 11.5315 15.7599 11.5636 15.673 11.5636C15.586 11.5636 15.5022 11.5315 15.4382 11.4736C15.3741 11.4156 15.3344 11.3361 15.3269 11.2507C15.3269 10.8895 15.1813 10.543 14.9221 10.2875C14.663 10.0321 14.3115 9.88854 13.945 9.88854C13.897 9.89264 13.8487 9.88686 13.8031 9.87157C13.7576 9.85629 13.7157 9.83183 13.6802 9.79975C13.6447 9.76767 13.6164 9.72866 13.597 9.68522C13.5776 9.64177 13.5676 9.59482 13.5676 9.54736C13.5676 9.49989 13.5776 9.45295 13.597 9.4095C13.6164 9.36605 13.6447 9.32704 13.6802 9.29496C13.7157 9.26288 13.7576 9.23842 13.8031 9.22314C13.8487 9.20785 13.897 9.20207 13.945 9.20617C14.4951 9.20617 15.0226 9.42158 15.4116 9.80501C15.8006 10.1884 16.0191 10.7085 16.0191 11.2507ZM18.6557 12.3573C18.6481 12.4038 18.6308 12.4482 18.6049 12.4878C18.5791 12.5274 18.5452 12.5613 18.5055 12.5874C18.4657 12.6135 18.421 12.6312 18.374 12.6395C18.3269 12.6478 18.2787 12.6464 18.2323 12.6355C18.1858 12.6246 18.1421 12.6043 18.104 12.576C18.0658 12.5477 18.034 12.512 18.0105 12.471C17.987 12.43 17.9723 12.3847 17.9673 12.3379C17.9624 12.2911 17.9672 12.2438 17.9817 12.1989C18.0565 11.89 18.094 11.5734 18.0932 11.2558C18.0932 10.1713 17.6561 9.13126 16.8782 8.3644C16.1003 7.59754 15.0452 7.16672 13.945 7.16672C13.8387 7.16672 13.7298 7.16672 13.6235 7.16672C13.5782 7.17008 13.5327 7.16461 13.4896 7.15063C13.4465 7.13665 13.4065 7.11443 13.3721 7.08524C13.3026 7.02628 13.2597 6.94252 13.2528 6.85237C13.2459 6.76222 13.2756 6.67307 13.3354 6.60453C13.3952 6.536 13.4802 6.49368 13.5717 6.48691C13.6961 6.47668 13.8206 6.47157 13.945 6.47157C15.2295 6.4736 16.4606 6.97802 17.3681 7.87406C18.2757 8.77011 18.7854 9.98454 18.7854 11.2507C18.7855 11.6233 18.742 11.9946 18.6557 12.3573Z" fill="#F1F1F1"></path></svg></a>';
				}
				if($socials && in_array('telegram', $socials) && $telegram) {
					echo '<a href="https://t.me/'.$telegram.'" class="telegram" target="_blank"><svg width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M2.15308 12.569L21.4364 5.13397C22.3314 4.81063 23.1131 5.3523 22.8231 6.70563L22.8247 6.70397L19.5414 22.1723C19.2981 23.269 18.6464 23.5356 17.7347 23.019L12.7347 19.334L10.3231 21.6573C10.0564 21.924 9.83141 22.149 9.31475 22.149L9.66975 17.0606L18.9364 8.68897C19.3397 8.33397 18.8464 8.13397 18.3147 8.4873L6.86308 15.6973L1.92641 14.1573C0.854747 13.8173 0.831414 13.0856 2.15308 12.569V12.569Z" fill="#F1F1F1"></path></svg></a>';
				}
				if($socials && in_array('instagram', $socials) && $instagram) {
					echo '<a href="https://www.instagram.com/'.$instagram.'" class="instagram" target="_blank"><svg width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M21.6832 16.9042C21.6832 18.1717 21.1797 19.3872 20.2835 20.2835C19.3872 21.1797 18.1717 21.6832 16.9042 21.6832H9.09371C7.82625 21.6832 6.61071 21.1797 5.71448 20.2835C4.81826 19.3872 4.31476 18.1717 4.31476 16.9042V9.09371C4.31476 7.82625 4.81826 6.61071 5.71448 5.71448C6.61071 4.81826 7.82625 4.31476 9.09371 4.31476H16.9042C18.1717 4.31476 19.3872 4.81826 20.2835 5.71448C21.1797 6.61071 21.6832 7.82625 21.6832 9.09371V16.9042ZM16.8385 9.19904L16.7885 9.14904L16.7464 9.10694C15.7514 8.11538 14.4038 7.55891 12.999 7.55957C12.2897 7.56439 11.5882 7.70893 10.9347 7.98494C10.2812 8.26094 9.68854 8.663 9.19049 9.16814C8.69243 9.67328 8.29879 10.2716 8.03205 10.9289C7.7653 11.5862 7.63069 12.2897 7.63589 12.999C7.63481 14.4387 8.20228 15.8204 9.21483 16.8438C9.7108 17.3458 10.3018 17.744 10.9533 18.0151C11.6049 18.2861 12.3039 18.4247 13.0096 18.4227C14.0681 18.4005 15.0973 18.0708 15.9716 17.4737C16.846 16.8766 17.5277 16.0381 17.9336 15.0602C18.3395 14.0824 18.4521 13.0075 18.2577 11.9668C18.0632 10.926 17.5701 9.96436 16.8385 9.19904ZM12.999 16.5675C12.2912 16.5774 11.5965 16.3766 11.0031 15.9905C10.4097 15.6045 9.94459 15.0507 9.66682 14.3996C9.38905 13.7485 9.31121 13.0295 9.4432 12.334C9.57519 11.6386 9.91105 10.9981 10.4081 10.494C10.9051 9.98994 11.5407 9.64508 12.2343 9.50329C12.9278 9.3615 13.6479 9.42919 14.3028 9.69774C14.9578 9.96629 15.5181 10.4236 15.9124 11.0114C16.3068 11.5993 16.5174 12.2912 16.5175 12.999C16.5209 13.4644 16.4327 13.9259 16.2577 14.3572C16.0827 14.7884 15.8245 15.181 15.4977 15.5124C15.171 15.8437 14.7821 16.1075 14.3534 16.2885C13.9246 16.4696 13.4644 16.5644 12.999 16.5675ZM19.861 7.80235C19.9244 7.64584 19.9563 7.47836 19.955 7.30949C19.9561 7.0106 19.8538 6.72051 19.6655 6.48844L19.6365 6.45686C19.602 6.41442 19.5632 6.37562 19.5207 6.34107L19.4944 6.31476C19.2663 6.12428 18.9785 6.01996 18.6813 6.02002C18.4273 6.0242 18.1802 6.10283 17.9705 6.24615C17.7609 6.38947 17.5979 6.59118 17.5018 6.82626C17.4056 7.06135 17.3807 7.31948 17.4299 7.56864C17.4791 7.8178 17.6004 8.04703 17.7786 8.22791C17.8963 8.34705 18.0364 8.44166 18.1909 8.50625C18.3454 8.57085 18.5112 8.60415 18.6786 8.60423C18.8475 8.6032 19.0145 8.56887 19.1701 8.50321C19.3257 8.43755 19.4668 8.34185 19.5853 8.22159C19.7039 8.10133 19.7976 7.95886 19.861 7.80235Z" fill="#F1F1F1"></path></svg></a>';
				}
				if($socials && in_array('vk', $socials) && $vk) {
					echo '<a href="'.$vk.'" class="vk" target="_blank"><svg width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M24.7374 20.306L21.2874 20.3533C21.2874 20.3533 20.5453 20.5007 19.5716 19.827C18.2795 18.9401 17.0637 16.6375 16.1164 16.9322C15.169 17.2269 15.1848 19.3007 15.1848 19.3007C15.1879 19.5439 15.1132 19.7818 14.9717 19.9797C14.7726 20.1503 14.5269 20.2575 14.2665 20.2876H12.7375C12.7375 20.2876 9.31646 20.4902 6.32959 17.3691C3.04537 13.9797 0.153257 7.20857 0.153257 7.20857C0.153257 7.20857 -0.0170544 6.81583 0.169039 6.55068C0.329899 6.32146 0.726616 6.29685 0.926928 6.28755C1.79044 6.24716 4.38482 6.26388 4.38482 6.26388C4.60185 6.25849 4.74771 6.26989 5.01638 6.38755C5.24732 6.50169 5.42247 6.67169 5.53482 6.94544C5.93396 7.93935 6.39724 8.9063 6.9217 9.84021C8.4638 12.506 9.18224 13.0876 9.70591 12.8033C10.4665 12.3876 10.2322 9.03755 10.2322 9.03755C10.2322 9.03755 10.2322 7.81911 9.84802 7.27966C9.5588 6.94177 9.15092 6.72794 8.70857 6.68232C8.50068 6.65333 8.84279 6.17177 9.2849 5.95599C9.94802 5.62966 11.1218 5.62966 12.5059 5.62966C13.1151 5.60669 13.7244 5.66872 14.3165 5.81388C15.5901 6.12177 15.1586 7.30599 15.1586 10.1533C15.1586 11.0639 14.9928 12.3481 15.6507 12.7849C15.9349 12.9665 16.6243 12.8112 18.3559 9.87435C18.9083 8.90247 19.3877 7.89099 19.7901 6.84802C19.8676 6.68224 19.9852 6.53849 20.1322 6.42958C20.2877 6.35216 20.4625 6.322 20.6349 6.34271L24.5191 6.31638C24.5191 6.31638 25.6849 6.17693 25.8743 6.70583C26.0638 7.23482 25.4375 8.54794 23.8507 10.6532C21.2454 14.1268 20.9559 13.8111 23.1191 15.8084C25.1849 17.7268 25.6139 18.6611 25.6849 18.7768C26.5426 20.1929 24.7374 20.306 24.7374 20.306Z" fill="#F1F1F1"></path></svg></a>';
				}
				if($socials && in_array('facebook', $socials) && $facebook) {
					echo '<a href="'.$facebook.'" class="facebook" target="_blank"><svg width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M10.4842 14.3596H7.86844C7.45792 14.3596 7.31055 14.2096 7.31055 13.7965C7.31055 12.7316 7.31055 11.6675 7.31055 10.6044C7.31055 10.1939 7.46581 10.0386 7.87107 10.0386H10.4842V7.73596C10.4532 6.70165 10.7014 5.67807 11.2027 4.77281C11.7237 3.85888 12.5587 3.16507 13.5527 2.82018C14.1977 2.58548 14.8794 2.46784 15.5658 2.47281H18.1527C18.5237 2.47281 18.679 2.63596 18.679 2.99912V6.00175C18.679 6.37807 18.5211 6.52807 18.1527 6.52807C17.4448 6.52807 16.7369 6.52807 16.0316 6.55702C15.3263 6.58597 14.9421 6.90702 14.9421 7.64386C14.9263 8.43333 14.9421 9.20702 14.9421 10.0123H17.9816C18.4132 10.0123 18.5605 10.1596 18.5605 10.5939C18.5605 11.6465 18.5605 12.7044 18.5605 13.7675C18.5605 14.1965 18.4237 14.3307 17.9895 14.3333H14.9263V22.8965C14.9263 23.3544 14.7842 23.4991 14.3316 23.4991H11.0369C10.6395 23.4991 10.4842 23.3439 10.4842 22.9465V14.3596Z" fill="#F1F1F1"></path></svg></a>';
				}
				if($socials && in_array('ok', $socials) && $ok) {
					echo '<a href="'.$ok.'" class="ok" target="_blank"><svg width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M13 2.47266C11.9497 2.47266 10.9229 2.78411 10.0496 3.36764C9.1763 3.95117 8.49564 4.78056 8.0937 5.75093C7.69175 6.7213 7.58659 7.78907 7.7915 8.81922C7.9964 9.84936 8.50218 10.7956 9.24487 11.5383C9.98756 12.281 10.9338 12.7868 11.964 12.9917C12.9941 13.1966 14.0619 13.0914 15.0322 12.6895C16.0026 12.2875 16.832 11.6069 17.4155 10.7336C17.9991 9.86024 18.3105 8.83351 18.3105 7.78318C18.3109 7.0857 18.1737 6.39498 17.907 5.75052C17.6402 5.10607 17.2491 4.5205 16.7559 4.0273C16.2627 3.53411 15.6771 3.14295 15.0326 2.87619C14.3882 2.60944 13.6975 2.47231 13 2.47266V2.47266ZM13 10.4306C12.4764 10.4306 11.9645 10.2753 11.5292 9.98439C11.0938 9.69349 10.7545 9.28003 10.5541 8.79629C10.3538 8.31254 10.3013 7.78025 10.4035 7.26671C10.5056 6.75317 10.7578 6.28145 11.128 5.91121C11.4983 5.54097 11.97 5.28883 12.4835 5.18668C12.997 5.08453 13.5293 5.13696 14.0131 5.33733C14.4968 5.53771 14.9103 5.87703 15.2012 6.31238C15.4921 6.74774 15.6474 7.25958 15.6474 7.78318C15.6477 8.13094 15.5795 8.47535 15.4465 8.7967C15.3136 9.11805 15.1186 9.41003 14.8727 9.65593C14.6268 9.90183 14.3348 10.0968 14.0135 10.2297C13.6921 10.3627 13.3477 10.4309 13 10.4306Z" fill="#F1F1F1"></path><path d="M20.8275 13.9087C20.7118 13.7023 20.5566 13.5206 20.3707 13.3742C20.1849 13.2278 19.9719 13.1195 19.7441 13.0554C19.5163 12.9914 19.2781 12.9729 19.0432 13.001C18.8082 13.0291 18.5811 13.1033 18.3748 13.2193C14.759 15.2456 11.2433 15.2456 7.62747 13.2193C7.42011 13.1035 7.19199 13.0297 6.95612 13.0021C6.72025 12.9745 6.48125 12.9936 6.25277 13.0584C6.02429 13.1231 5.8108 13.2322 5.62449 13.3795C5.43819 13.5268 5.28271 13.7093 5.16694 13.9166C5.05117 14.124 4.97737 14.3521 4.94976 14.588C4.92215 14.8238 4.94127 15.0628 5.00603 15.2913C5.13681 15.7528 5.44554 16.1434 5.86431 16.3772C7.05051 17.0504 8.31776 17.5695 9.63536 17.9219L7.07746 20.4798C6.74144 20.8159 6.55267 21.2717 6.55267 21.7469C6.55267 22.2221 6.74144 22.6779 7.07746 23.014C7.41354 23.35 7.86933 23.5388 8.34457 23.5388C8.81981 23.5388 9.2756 23.35 9.61168 23.014L13.0011 19.6114L16.3906 23.0008C16.7267 23.3369 17.1825 23.5256 17.6577 23.5256C18.133 23.5256 18.5888 23.3369 18.9248 23.0008C19.2609 22.6648 19.4496 22.209 19.4496 21.7337C19.4496 21.2585 19.2609 20.8027 18.9248 20.4666L16.3669 17.9087C17.6845 17.5563 18.9518 17.0372 20.138 16.364C20.3448 16.2484 20.5267 16.0931 20.6734 15.907C20.82 15.7209 20.9285 15.5076 20.9925 15.2795C21.0566 15.0514 21.075 14.8129 21.0467 14.5777C21.0183 14.3424 20.9438 14.1151 20.8275 13.9087V13.9087Z" fill="#F1F1F1"></path></svg></a>';
				}
				if($socials && in_array('youtube', $socials) && $youtube) {
					echo '<a class="youtube" target="_blank" href="'.$youtube.'"><svg width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M19.5437 5.47656H6.45424C5.93115 5.47656 5.41318 5.57964 4.92994 5.77989C4.4467 5.98015 4.00766 6.27367 3.6379 6.64367C3.26815 7.01367 2.97492 7.45291 2.77498 7.93628C2.57504 8.41965 2.47231 8.93768 2.47266 9.46077V16.5397C2.47231 17.0628 2.57504 17.5808 2.77498 18.0642C2.97492 18.5476 3.26815 18.9868 3.6379 19.3568C4.00766 19.7268 4.4467 20.0203 4.92994 20.2206C5.41318 20.4209 5.93115 20.5239 6.45424 20.5239H19.5437C20.0668 20.5239 20.5848 20.4209 21.068 20.2206C21.5512 20.0203 21.9903 19.7268 22.36 19.3568C22.7298 18.9868 23.023 18.5476 23.223 18.0642C23.4229 17.5808 23.5256 17.0628 23.5253 16.5397V9.46077C23.5256 8.93768 23.4229 8.41965 23.223 7.93628C23.023 7.45291 22.7298 7.01367 22.36 6.64367C21.9903 6.27367 21.5512 5.98015 21.068 5.77989C20.5848 5.57964 20.0668 5.47656 19.5437 5.47656V5.47656ZM10.2253 16.2266V9.77393L15.7727 13.0002L10.2253 16.2266Z" fill="#F1F1F1"></path></svg></a>';
				}
			echo '</div>';
		if (!empty($class)) {
			echo '</div>';
		}
	}
}

function section_example_order($class) {
    echo '<section class="example-order '.$class.'">';?>
        <div class="example-order__container">
            <h2 class="example-order__title">
            <span>Оформление заказа в&nbsp;компании “СтальЦентр”</span>
        </h2>
            <div class="example-order__col">
                <div class="example-order__item item-one">
                    <h3>Входящая заявка</h3>
                    <p>Расскажите менеджерам вашу задачу связавшись любым удобным способом
                        <button data-btn="calculation" class="open-popup-js example-order__item_btn">Оставьте заявку</button> или звоните по&nbsp;тел.&nbsp;<?contacts('example-order','');?></p>
                </div>
                <div class="example-order__item">
                    <h3>Техническое задание</h3>
                    <p>Обсуждение чертежа или эскиза для точного расчёта стоимости изделия</p>
                </div>
                <div class="example-order__item">
                    <h3>Согласование</h3>
                    <p>Озвучиваем коммерческие условия, сроки изготовления, подпишем спецификацию и договор</p>
                </div>
                <div class="example-order__item">
                    <h3>Выполнение работ</h3>
                    <p>Запуск в производство, межоперационный и конечный контроль качества</p>
                </div>
            </div>
            <button data-btn="offer" class="open-popup-js btn">Заказать услугу</button>
        </div>
    </section>
<?}
function url_lazy_pixel() {
	return get_stylesheet_directory_uri().'/assets/img/pixel.png';
}

function section_works($class) {
	$query = new WP_Query( [
		'post_type'      => 'post',
		'posts_per_page' => 10,
	]);
	if ($query->have_posts()) {
		echo '<section class="works '.$class.'">';?>
			<div class="works__container">
			<?
			$h2_text = 'Примеры последних работ';
			if ($class) {
				echo '<h2 class="works__title"><span>'.$h2_text.'</span></h2>';
			}else {
				echo'<h2>'.$h2_text.'</h2>';}?>
				<div class="works-slider swiper">
					<div class="works-slider__nav">
						<div class="works-slider-prev"></div>
						<div class="works-slider-pagination"></div>
						<div class="works-slider-next"></div>
					</div>
					<div class="works-slider__wrapper swiper-wrapper">
						<?while ($query->have_posts()) { $query->the_post();?>
								<div class="works-slider__slide swiper-slide">
									<div class="works-slider__image work-img swiper">
										<div class="work-img__wrapper swiper-wrapper">
											<?$works_imgs = get_field('fotografii');
											$count = 1;
											foreach ($works_imgs as $works_img) {
												echo '<div class="work-img__slide swiper-slide"><img data-src="'.$works_img.'" src="'.url_lazy_pixel().'" alt="work-img-'.$count.'" class="swiper-lazy"><div class="swiper-lazy-preloader"></div></div>';
												$count++;
											}?>
										</div>
										<div class="work-img-pagination"></div>
									</div>
									<div class="works-slider__descr">
										<div class="works-slider__descr_item">
											<p>Технология:</p>
											<?$cats = get_the_category();
											if($cats){
												foreach($cats as $cat){
													echo '<h3>';
													echo $cat->name;
													echo '</h3>';
												}
											}?>
										</div>
										<div class="works-slider__descr_item">
											<p>Вид металла:</p>
											<?$tags = get_the_tags();
											foreach ($tags as $tag) {
												$html = "<h3>{$tag->name}</h3>";
											}if ($tags) {echo $html;}
											?>
										</div>
										<div class="works-slider__descr_item">
											<p>Толщина:</p>
											<h3><?=get_field('tolshhina');?> мм</h3>
										</div>
										<a href="<?=get_permalink(65);?>" class="btn">Смотреть все работы</a>
									</div>
								</div>
						<?} wp_reset_postdata();?>
					</div>
				</div>
			</div>
		</section>
	<?}
}

function services_item($id_current, $id, $class) {
	$item_1 = get_field('services-item-1', $id_current);
	$item_2 = get_field('services-item-2', $id_current);
	$item_3 = get_field('services-item-3', $id_current);
	if(!$item_1) {$item_1 = get_field('services-item-1', $id);}
	if(!$item_2) {$item_2 = get_field('services-item-2', $id);}
	if(!$item_3) {$item_3 = get_field('services-item-3', $id);}
	if(!empty($item_1 || $item_2 || $item_3)) {
		echo '<ul class="'.$class.'__items">';
			if($item_1){echo '<li class="'.$class.'__item"><span>'.$item_1.'</span></li>';}
			if($item_2){echo '<li class="'.$class.'__item"><span>'.$item_2.'</span></li>';}
			if($item_3){echo '<li class="'.$class.'__item"><span>'.$item_3.'</span></li>';}
		echo '</ul>';
	}
}

function contacts_form($id, $class_section) {
	$form_h2 = get_field('form-h2', $id);
	$form_p = get_field('form-p', $id);
	$form_btn = get_field('form-btn', $id);
	$form_file_on = get_field('form-file-on', $id);
	$title_page = single_post_title('',false);
	if ($class_section) {
		echo '<section class="form-contacts '.$class_section.'">';
	}
	else { echo '<section class="form-contacts">';}
	?>
	
		<div class="contacts__container">
			<div class="contacts__descr">
				<h2><?=$form_h2;?></h2>
				<p><?=$form_p;?></p>
				<form method="POST" class="popup__form black" enctype="multipart/form-data" data-form-type="page">
					<div class="popup__fields">
						
						<input type="text" name="name" class="ym-record-keys" placeholder="Имя" autocomplete="on">
						<input type="tel" name="phone" inputmode="decimal" class="ym-record-keys" placeholder="Телефон *" autocomplete="on">
						<input type="email" name="email" class="ym-record-keys" placeholder="E-mail *" autocomplete="on">
					</div>
					<textarea name="message" placeholder="Комментарий"></textarea>
					<?if($form_file_on) {?>
					<div class="input-file-row">
						
						<label class="input-file">
							<div class="input-file_wrapper">
								<input type="file" accept=".jpg,.jpeg,.png,.webp,.doc,.docx,.rar,.zip,.7z" name="file_send_server" multiple="multiple"> <span class="loader-file">Прикрепить файл</span> </div>
						</label>
						
						<div class="input-file-list"></div>
					</div>
					<input name="file_send_email" type="hidden">
					<?}?>
					<!-- <div class="loader-file d-hide"></div> -->
					<input name="form_check" value="VweRyb" type="hidden">
					
					<input name="form_title" value="<?=$form_h2;?>" type="hidden">
					<input name="page_title" value="<?=$title_page;?>" type="hidden">
					<input name="page_url" value="<?=get_page_uri();?>" type="hidden">
					<div class="popup__submit">
						<button class="btn" type="submit"><span class="btn-text"><?=$form_btn;?></span><span class="loader-submit d-hide"><svg fill=none height=22 viewBox="0 0 22 22"width=22 xmlns=http://www.w3.org/2000/svg><g clip-path=url(#clip0_826_10097)><path d="M4.33826 18.99C5.63397 18.99 6.68434 17.9396 6.68434 16.6439C6.68434 15.3482 5.63397 14.2979 4.33826 14.2979C3.04256 14.2979 1.99219 15.3482 1.99219 16.6439C1.99219 17.9396 3.04256 18.99 4.33826 18.99Z"fill=white fill-opacity=0.85></path><path d="M19.4327 16.9093C20.5573 16.9093 21.4689 15.9977 21.4689 14.8731C21.4689 13.7486 20.5573 12.8369 19.4327 12.8369C18.3081 12.8369 17.3965 13.7486 17.3965 14.8731C17.3965 15.9977 18.3081 16.9093 19.4327 16.9093Z"fill=white fill-opacity=0.7></path><path d="M17.5737 6.64006C18.5027 6.64006 19.2558 5.88697 19.2558 4.95797C19.2558 4.02898 18.5027 3.27588 17.5737 3.27588C16.6447 3.27588 15.8916 4.02898 15.8916 4.95797C15.8916 5.88697 16.6447 6.64006 17.5737 6.64006Z"fill=white fill-opacity=0.6></path><path d="M2.48972 13.2633C3.86475 13.2633 4.97944 12.1758 4.97944 10.8343C4.97944 9.49279 3.86475 8.40527 2.48972 8.40527C1.11468 8.40527 0 9.49279 0 10.8343C0 12.1758 1.11468 13.2633 2.48972 13.2633Z"fill=white fill-opacity=0.9></path><path d="M9.64229 21.9999C10.8923 21.9999 11.9057 21.0125 11.9057 19.7944C11.9057 18.5763 10.8923 17.5889 9.64229 17.5889C8.39226 17.5889 7.37891 18.5763 7.37891 19.7944C7.37891 21.0125 8.39226 21.9999 9.64229 21.9999Z"fill=white fill-opacity=0.8></path><path d="M15.4588 21.0335C16.6463 21.0335 17.609 20.0961 17.609 18.9398C17.609 17.7835 16.6463 16.8462 15.4588 16.8462C14.2713 16.8462 13.3086 17.7835 13.3086 18.9398C13.3086 20.0961 14.2713 21.0335 15.4588 21.0335Z"fill=white fill-opacity=0.75></path><path d="M5.18294 7.62195C6.62046 7.62195 7.7858 6.48444 7.7858 5.08124C7.7858 3.67804 6.62046 2.54053 5.18294 2.54053C3.74542 2.54053 2.58008 3.67804 2.58008 5.08124C2.58008 6.48444 3.74542 7.62195 5.18294 7.62195Z"fill=white fill-opacity=0.95></path><path d="M20.0762 11.4707C21.1387 11.4707 22.0001 10.6253 22.0001 9.58253C22.0001 8.53971 21.1387 7.69434 20.0762 7.69434C19.0137 7.69434 18.1523 8.53971 18.1523 9.58253C18.1523 10.6253 19.0137 11.4707 20.0762 11.4707Z"fill=white fill-opacity=0.65></path><path d="M11.6419 5.48893C13.1577 5.48893 14.3864 4.26019 14.3864 2.74447C14.3864 1.22874 13.1577 0 11.6419 0C10.1262 0 8.89746 1.22874 8.89746 2.74447C8.89746 4.26019 10.1262 5.48893 11.6419 5.48893Z"fill=white></path></g><defs><clipPath id=clip0_826_10097><rect fill=white height=22 width=22></rect></clipPath></defs></svg></span></button>
						<label class="custom-checkbox">
							<input class="checkbox-privacy-policy" type="checkbox" name="checkbox" checked><span> Я согласен(-а) на обработку <a href="/privacy" target="_blank">персональных данных</a></span>
						</label>
					</div>
				</form>
			</div>
			<div class="contacts__map">
				<?contacts('map','d-hide');?>
				<div class="map" id="map" data-dir="<?=get_stylesheet_directory_uri();?>/assets"></div>
			</div>
		</div>
	</section>

<?
}

// -------------------------- КОНЕЦ ДИНАМИЧЕСКОЕ УПР.ВЫВОДА ИНФ. --------------------------

// ------------------------- НАЧАЛО ДОП.НЕОБХОДИОСТИ ДЛЯ WP -------------------------
// размеры, которые нужно удалить
add_action('init', 'true_remove_wp_image_sizes', 999);
function true_remove_wp_image_sizes() {
	remove_image_size( '1536x1536' );
	remove_image_size( '2048x2048' );
	remove_image_size( 'large' );
	remove_image_size( 'thumbnail' );
	remove_image_size( 'medium' );
	remove_image_size( 'medium_large' );
}
add_filter('intermediate_image_sizes', 'delete_intermediate_image_sizes');
function delete_intermediate_image_sizes($sizes){
	return array_diff($sizes, [
		'thumbnail',
		'medium',
		'medium_large',
		'large',
		'1536x1536',
		'2048x2048',
	]);
}
//Отключаем Gutenberg
add_filter('use_block_editor_for_post_type', '__return_false', 100);
add_action('admin_init', function() {
    remove_action('admin_notices', ['WP_Privacy_Policy_Content', 'notice']);
    add_action('edit_form_after_title', ['WP_Privacy_Policy_Content', 'notice']); 
});
// ------------------------- КОНЕЦ ДОП.НЕОБХОДИОСТИ ДЛЯ WP -------------------------

add_filter( 'register_post_type_args', 'filter_function_name_8795', 10, 2 );
function filter_function_name_8795( $args, $post_type ){
	if ( 'post' == $post_type ) {
		$args['menu_icon'] = 'dashicons-grid-view';
		$args['labels'] = [
			'name'                  => 'Наши работы',
			'singular_name'         => 'Наши работы',
			'add_new'               => 'Добавить пост',
			'add_new_item'          => 'Добавить пост',
			'edit_item'             => 'Редактировать пост',
			'new_item'              => 'Новый пост',
			'view_item'             => 'Просмотреть пост',
			'search_items'          => 'Поиск постов',
			'not_found'             => 'Постов не найдено.',
			'not_found_in_trash'    => 'Постов в корзине не найдено.',
			'parent_item_colon'     => '',
			'all_items'             => 'Все посты',
			'archives'              => 'Архивы постов',
			'insert_into_item'      => 'Вставить в пост',
			'uploaded_to_this_item' => 'Загруженные для этого поста',
			'featured_image'        => 'Миниатюра поста',
			'filter_items_list'     => 'Фильтровать список постов',
			'items_list_navigation' => 'Навигация по списку постов',
			'items_list'            => 'Список постов',
			'menu_name'             => 'Наши работы',
			'name_admin_bar'        => 'Пост', // пункте "добавить"
		];
	}

	return $args;
}

$enabled_mail_smtp = get_option('enabled_mail_smtp');
if ($enabled_mail_smtp) {
	add_action( 'phpmailer_init', 'my_phpmailer_example' );
	function my_phpmailer_example( $phpmailer ) {
		$phpmailer->isSMTP();     
		$phpmailer->Host = get_option('mail_custom_SMTP_HOST');
		$phpmailer->SMTPAuth = true; // Force it to use Username and Password to authenticate
		$phpmailer->Port = get_option('mail_custom_SMTP_PORT');
		$phpmailer->Username = get_option('admin_email');
		$phpmailer->Password = get_option('mail_custom_SMTP_PASS');
		$phpmailer->SMTPSecure = get_option('mail_custom_SMTP_SECURE');
	}

}

add_filter( 'wp_mail_content_type', 'true_content_type' );
function true_content_type( $content_type ) {
	return 'text/html';
}

//ОТПРАВКА ПОЧТЫ
add_action( 'wp_ajax_feedback_action', 'ajax_action_callback' );
add_action( 'wp_ajax_nopriv_feedback_action', 'ajax_action_callback' );

function ajax_action_callback() {
	if ( ! wp_verify_nonce( $_POST['nonce'], 'feedback-nonce' ) ) {
		wp_die();
	}

	if( isset( $_POST['file_loading'] ) ){  
		// ВАЖНО! тут должны быть все проверки безопасности передавемых файлов и вывести ошибки если нужно
	
		$max_size = 20 * 1024 * 1024;
		$valid_types 	=  array('jpg', 'jpeg', 'png', 'webp', 'pdf', 'doc', 'docx', 'rar', 'zip', '7z');
	
		//$uploaddir = './uploads'; // . - текущая папка где находится submit.php
		$uploaddir = ''.WP_CONTENT_DIR.'/uploads/storage-file-mail/';
		// cоздадим папку если её нет
		if( ! is_dir( $uploaddir ) ) mkdir( $uploaddir, 0777 );
	
		$files      = $_FILES; // полученные файлы
		$done_files = array();
	
		// переместим файлы из временной директории в указанную
		foreach( $files as $file ){
			$file_name = $file['name'];
			$ext = substr($file_name, 1 + strrpos($file_name, "."));
	
			if ($file['size'] > $max_size) {
				//echo json_encode(array('message'=>__('Error: File size > 20Mb')));
				//die(json_encode('Error: File size > 20Mb.'));
			} elseif (!in_array($ext, $valid_types)) {

				die(json_encode(array('message'=>__('Error: Invalid file type '.$file_name.''))));
				//die(json_encode('Error: Invalid file type.'));
			} elseif( move_uploaded_file( $file['tmp_name'], "$uploaddir/$file_name" ) ) {
				$done_files[] = $file_name;
		  		// $done_files[] = 'https://' . $_SERVER['HTTP_HOST'] . '/wp-content/uploads/loading-file-mail/' . $file_name;
			}	
		}
	
		$data = $done_files ? array('files' => $done_files ) : array('error' => 'Ошибка загрузки файлов.');
		//echo json_encode(array('message'=>__($data)));
		die(json_encode($data));
	}
	else if (isset($_POST['file_remove'])){  
		$uploaddir = ''.WP_CONTENT_DIR.'/uploads/storage-file-mail/';
		$name_file_remove = $_POST['name_file_remove'];
		if (isset($name_file_remove)){  
			$file = $uploaddir.''. $name_file_remove. '';
			$remove_ok = array('remove_ok' => 'REMOVE-OK');
			wp_delete_file($file);
			die(json_encode($remove_ok));
		}
		die();
	}
	else if (isset($_POST['send_form'])){  
		$check = $_POST['form_check'];
		if ($check !== 'VweRyb') {
			echo json_encode(array('message'=>__('ERROR')));
			die();
		}
		
		$type_form = sanitize_text_field($_POST['received_from']);
		//$type_form = (string)$type_form;

		//Массив ошибок
		$err_message = array();
		$tel = $_POST['phone'];
		$email = $_POST['email'];

		function check_phone($tel) {
			$clean_str_tel = mb_eregi_replace('[^0-9]', '', $tel);
			if (empty($tel) || !isset($tel)) {
			} elseif (mb_strlen($clean_str_tel) !== 11) {
			} else {
				return true;
			}
			return false;
		}

		function check_email($email) {
			if (empty($email) || ! isset($email)) {
			} elseif (!preg_match( '/^[[:alnum:]][a-z0-9_.-]*@[a-z0-9.-]+\.[a-z]{2,4}$/i', $email)) {
			} else {
				return true;
			}
			return false;
		}


		if ($type_form == 'page') {
			if (check_phone($tel)) {
				$tel = sanitize_text_field($tel);
			} else {
				$err_message['phone'] = '';
			}
	
			if (check_email($email)) {
				$email = sanitize_text_field($email);
			} else {
				$err_message['email'] = '';
			}
		}
		else if ($type_form == 'popup') {
			$radio = sanitize_text_field($_POST['contact-by']);
			if ($radio == 'phone') {
				if (check_phone($tel)) {
					$tel = sanitize_text_field($tel);
				} else {
					$err_message['phone'] = '';
				}
			}
			else if ($radio == 'email') {
				if (check_email($email)) {
					$email = sanitize_text_field($email);
				} else {
					$err_message['email'] = '';
				}
			}
		}

		if ( $err_message ) {
			//die(json_encode($err_message));
			wp_send_json_error( $err_message );
			die();
		} else {
			
			$email_to = get_option('admin_email');
			$art_subject = 'Сообщение с сайта Сталь Центр';
			$name = sanitize_text_field( $_POST['name'] );
			$comments = sanitize_textarea_field( $_POST['message'] );
			$form_title = sanitize_text_field($_POST['form_title']);
			$page_title = sanitize_text_field($_POST['page_title']);
			$page_url = sanitize_text_field($_POST['page_url']);
			$file_html = sanitize_text_field($_POST['file_send_email']);
			$website_addr = get_site_url();
			$company_name = get_bloginfo('name');
			foreach ($_POST as $key => $value) {
				if ($value != "" && $key != 'action' && $key != 'nonce' && $key != 'received_from' && $key != 'send_form' && $key != 'page_title' && $key != 'page_url' && $key != 'checkbox' && $key != 'file_send_email' && $key != 'contact-by') {
					if ($key == "name") $key = "Имя";
					if ($key == "email") $key = "E-mail";
					if ($key == "phone") $key = "Телефон";
					if ($key == "message") $key = "Сообщение";
					if ($key == "form_title") $key = "Заголовок";
					if ($key == "form_check") continue;
					$messages .= "
					<tr>
						<td style='padding: 10px; border: #e9e9e9 1px solid;'><b>$key</b></td>
						<td style='padding: 10px; border: #e9e9e9 1px solid;'>$value</td>
					</tr>
					";
				}
			}
			if ($messages) {
				$message .= 'Поступила новая заявка с сайта <a href="'.$website_addr.'">'.$company_name.'</a><br><br>';
				$message .= '<table>'.$messages.'';
				$message .= "<tr><td style='padding: 10px; border: #e9e9e9 1px solid;'><b>URL</b></td><td style='padding: 10px; border: #e9e9e9 1px solid;'><a href='".$website_addr."/".$page_url."'>".$page_title."</a></td></tr>";
				if (!empty($message_radio)) {
					$message .= "<tr><td style='padding: 10px; border: #e9e9e9 1px solid;'><b>Предпочтение связи</b></td><td style='padding: 10px; border: #e9e9e9 1px solid;'>".$message_radio."</td></tr>";
				}
				$message .= '</table>';
			}
			$home_url = wp_parse_url( home_url() );
			$body = $message;
			$headers = 'From: ' . $home_url['host'] . ' <' . $email_to . '>' . "\r\n" . 'Reply-To: ' . $email_to;

			if ($file_html) {
				$file_array = array();
				$file_array = (array) explode('||', $file_html );
				$url = WP_CONTENT_DIR.'/uploads/storage-file-mail/';
				$attachments = array();
				foreach($file_array as $index_file => $file) {
					$name_file = $file;
					$url_file = $url . $name_file;
					$attachments[] .= $url_file;
				}
				// Отправляем письмо
				$sent_message = wp_mail($email_to, $art_subject, $body, $headers, $attachments);
			}
			else {
				$sent_message = wp_mail($email_to, $art_subject, $body, $headers);
			}

			if ($sent_message ) {
				echo json_encode(array('message'=>__('OK')));
				if ($file_html) {
					foreach ((array)$attachments as $file) {
						if(file_exists($file)) {
						unlink($file);
						}
					}
				}
			} else {
				echo json_encode(array('message'=>__('ERROR')));
			}
		}


	}
	die();


}

add_action( 'customize_register', 'mt_customize' );
function mt_customize( $wp_customize ) {	

//Почта
	$wp_customize->add_section(
		// ID
		'mail_custom',
		// Arguments array
		array(
			'title' => 'Настройки Почты',
			'capability' => 'edit_theme_options',
			'priority'  => 4,
			'description' => "Здесь Вы можете настроить SMTP сервер-обработчик почты.<br><br>Внимание! Если нужно изменить Административный E-mail для WordPress (Настройки - Общие - Административный E-mail), то временно отключите SMTP-сервер.<br><br>При смене Административной почты, необходимо продублировать её также в настройках: WooCommerce - Настройки - Email'ы - Адрес отправителя.<br><br>Если вдруг почта не отправляется, сверьте свои настройки для исходящей почты: <a href='https://yandex.ru/support/mail/mail-clients/others.html' target='_blank'>Яндекс </a>, <a href='https://developers.google.com/gmail/imap/imap-smtp' target='_blank'>Google</a>, <a href='https://help.mail.ru/mail/mailer/popsmtp' target='_blank'>Mail.ru</a> или перезапустите SMTP-сервер, если настройки верны.",
		)
	);

	$wp_customize->add_setting('enabled_mail_smtp', array(
		'default'    => 'true',
		'capability' => 'edit_theme_options',
		'type' => 'option',
	));
	$wp_customize->add_control(
		'enabled_mail_smtp_control', array(
			'type'      => 'checkbox',
			'section' => 'mail_custom',
			'label'     => __('Запустить SMTP-сервер'),
			'settings'  => 'enabled_mail_smtp',		
		)
	);

	//email почты
	$wp_customize->add_setting(
		'mail_custom_SMTP_USER', array(
			'default' => '', 
			'type' => 'option',
		)
	);
	$wp_customize->add_control(
		'mail_custom_SMTP_USER_control', array(
			'type' => 'hidden',
			'section' => 'mail_custom',
			'label' => 'Сервер-обработчик: ' . get_option('admin_email'),
			'description' => "Для обработки писем используется Ваш Административный E-mail<br><br>",
			'settings' => 'mail_custom_SMTP_USER'
		)
	);

	$wp_customize->add_setting(
		'mail_custom_SMTP_PASS', array(
			'default' => '',
			'type' => 'option',
		)
	);
	$wp_customize->add_control(
		'mail_custom_SMTP_PASS_control', array(
			'type' => 'text',
			'section' => 'mail_custom',
			'label' => 'Пароль приложения',
			'description' => "Пароль приложения сервера почты. Пароль генерируется в аккаунте Вашей почты в настройках безопасности. Обычный пароль для входа не подойдёт, не рекомендуется в целях безопасности",
			'settings' => 'mail_custom_SMTP_PASS'
		)
	);
	
	$wp_customize->add_setting(
		'mail_custom_SMTP_HOST', array(
			'default' => '',
			'type' => 'option',
		)
	);
	$wp_customize->add_control(
		'mail_custom_SMTP_HOST_control', array(
			'type' => 'text',
			'section' => 'mail_custom',
			'label' => 'Хост почтового сервера',
			'description' => "Яндекс — smtp.yandex.ru, Google — smtp.gmail.com, Mail.ru — ssl://smtp.mail.ru. Скопируйте и вставьте значения, в соответствии с тем, какой сервис почты Вы используете.<br><br>Если письма не отправляются, добавьте приставку ssl://<br>Пример: ssl://smtp.yandex.ru",
			'settings' => 'mail_custom_SMTP_HOST'
		)
	);

	$wp_customize->add_setting(
		'mail_custom_SMTP_PORT', array(
			'default' => '',
			'type' => 'option',
		)
	);
	$wp_customize->add_control(
		'mail_custom_SMTP_PORT_control', array(
			'type' => 'text',
			'section' => 'mail_custom',
			'label' => 'Порт почтового сервера',
			'description' => "Яндекс — 465, Google — 465, либо 587, Mail.ru — 465",
			'settings' => 'mail_custom_SMTP_PORT'
		)
	);

	$wp_customize->add_setting(
		'mail_custom_SMTP_SECURE', array(
			'default' => '',
			'type' => 'option',
		)
	);
	$wp_customize->add_control(
		'mail_custom_SMTP_SECURE_control', array(
			'type' => 'select',
			'section' => 'mail_custom',
			'label' => 'Метод защиты соединения, передачи данных',
			'description' => "Яндекс — SSL, Google — TLS, Mail.ru — SSL или TLS",
			'settings' => 'mail_custom_SMTP_SECURE',
			'choices' => array(
				'SSL' => 'SSL',
				'TLS' => 'TLS',
			),
		)
	);
}