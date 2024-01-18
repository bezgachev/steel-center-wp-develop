<?
/*
Template Name: Услуги
Template Post Type: page
*/
get_header();
//id страницы Услуги, откуда берутся настройки для иерархии
$id_services = 21;
$website_addr = get_site_url();
// echo '<pre>';
// print_r($file_array);
// echo '</pre>';
$id_current = get_the_ID();
$title_page = single_post_title('',false);
$services_pages = get_field('services-pages',$id_services);
if (!$services_pages) {
	echo '<main><section class="services"><section class="bread-crumb __container"></section><div class="services__container"><h1>Сообщение администратору сайта*</h1>'.$title_page.'<p class="services__descr">* Произошла ошибка! Пожалуйста, заполните таблицу "Карта страниц Услуг" на странице '.get_the_title($id_services).' (page_id='.$id_services.') в административной панели WordPress.</p></div></section></main>';
	get_footer(); 
	return false;
}
$pages_array = array();
foreach($services_pages as $index_page => $pages) {
	$page = $pages['services-page'];
	$sub_pages = $pages['services-sub-page'];
	$sub_page = null;
	if ($sub_pages == null) {
		$sub_page .= $page;
	}else {
		foreach($sub_pages as $sub) {
			$sub_page[] .= $sub;
		}
	}
	$pages_array += array($page => $sub_page);
}

// echo '<pre>';
// print_r($pages_array);
// echo '</pre>';

if (array_key_exists($id_current, $pages_array)) {
	$id_need = $id_current;
}
else {
	foreach ($pages_array as $index_arrays => $arrays) {
		if (is_array($arrays)) {
			foreach ($arrays as $array) {
				if ($array == $id_current) {
					$id_need = $index_arrays;
				}
			}
		}
	}
}

switch ($id_current) {
	case 33:
		echo '<main class="laser-cutting">'; 
		break;
	case 35:
	case 39:
	case 46:
	case 48:
	case 73:
	case 75:
	case 77:
		echo '<main class="inside-page">'; 
		break;
	case 54:
		echo '<main class="services-metal-bending">'; 
		break;
	case 50:
		echo '<main class="services-metal-punching">'; 
		break;
	case 89:
		echo '<main class="cutting">'; 
		break;
	case 52:
		echo '<main class="powder-painting">'; 
		break;
	default:
		echo '<main>'; 
		break;
}
?>
	<section class="services" style="background-image: url(<?=get_field('backg-img',$id_need);?>);">
		<section class="bread-crumb __container">
			<?breadcrumb_home_url();?>
			<a class="bread-crumb__item" href="<?=get_permalink(21);?>">Услуги</a>
			<span class="bread-crumb__item"><?=$title_page;?></span>
		</section>
		<div class="services__container">
			<h1><?=$title_page;?></h1>
			<p class="services__descr"><?=get_field('descr');?></p>
			<?
			$class_item = 'services';
			services_item($id_current, $id_need, $class_item);
			?>
		</div>
	</section>
	<section class="price-list">
		<h2 class="__container"><?=get_field('price-h2');?></h2>
		<div class="price-list__container">
			<div class="price-list__body">
				<p class="price-list__body_descr"><?=get_field('price-descr');?></p>
				<?
				$table_price = get_field('table_price', $id_current);
				$table_price_have = $table_price['body'];
				if (!empty($table_price) && !empty($table_price_have)) {
					if (empty($table_price['caption'])) {
						if ($id_current == 52) {
							echo '<div class="price-list__body_table linear-header-left">';
						}else {
							echo '<div class="price-list__body_table linear-header">';
						}
					}else {
						echo '<div class="price-list__body_table">';
					}
					echo '<div class="table-scrollbar"><div class="swiper-scrollbar-drag"></div></div><div class="table-grid">';
					foreach($table_price['header'] as $index => $th) {
						if ($index === 0) {
							echo '<div class="table-rowspan">'.$th['c'].'</div>';
						}else if ($index !== 0) {
							$head_text[] = $th['c'];
						}
					}
					if (!empty($table_price['caption'])) {
						echo '<div class="table-row">'.$table_price['caption'].'</div>';
					}
					echo '<div class="table-col">';
						foreach($table_price['body'] as $index_tr => $tr) {
							//$row = $index_tr;
							echo '<div>';
							foreach($tr as $index_td => $td) {
								$col = $index_td;
								if ($index_td === 0) {
									$content = $td['c'];
									if (empty($content)) {
										echo '-';
									} else {
										echo $content;
									}
								}
							}
							echo '</div>';
						}
					echo '</div>';
					echo '<div class="table-body"><div class="swiper swiper-table" data-mobile="false"><div class="swiper-wrapper">';
					$count_row = 0;
					$count_col = 1;
					$count = 0;
					$first = true;
					for ($i=0; $i < $col; $i++) {
						echo '<div class="swiper-slide">';
							foreach($table_price['body'] as $index_tr => $tr) {
								
								if ($index_tr == $count_row) {
									foreach($tr as $index_td => $td) {
										if (($index_td == $count_col) && ($index_td !== 0)) {
											if ($first) {
												echo '<div>'.$head_text[$count].'</div>';
												$count++;
												$first = false;
											}
											echo '<div>';
												$content = $td['c'];
												if (empty($content)) {
													echo '-';
												} else {
													echo $content;
												}
											echo '</div>';
										}
									}
									$count_row++;
								}
							}
							$count_col++;
							$count_row = 0;
							$first = true;
						echo '</div>';
					}
					echo '</div></div></div>';
				echo '</div></div>';
					$note_on = get_field('note-on');
					$note_text = get_field('note-text');
					if ($note_on && $note_text) {
						echo '<p class="price-list__body_alert">* '.$note_text.'</p>';
					}
				}
				if ($id_current === 89) {
					echo '<div class="technical-specifications"><h2 class="technical-specifications__title">Технические характеристики</h2><div class="technical-specifications__img"></div></div>';
				}
				?>

			</div>
			<div class="price-list__info sticky">
				<div class="price-list__info_descr">

					<h3>Для оптовых и постоянных заказчиков у нас предусмотрены <span>скидки до 10%</span></h3>
					<p> Цены обновлены <b></b> и могут менятся в зависимости от объёма и сроков исполнения
						заказа </p>
					
				</div>
				<div class="price-list__info_btn">
					<?$price_file = get_field('price-file',$id_need);
						if ($price_file) {
							echo '<a download="Прайс-лист '.get_the_title($id_need).'" href="'.$price_file.'" class="btn-reverse" title="Скачать прайс '.get_the_title($id_need).'">Скачать прайс-лист</a>';
						}
					?>
					<button data-btn="price" class="open-popup-js btn">Запросить расчет</button>
				</div>
			</div>
		</div>
	</section>
	<?
	$type_on = get_field('types-on');
	$type_block = get_field('types-block');
	if ($type_on && $type_block) {
		$type_redirect = get_field('types-redirect-on');
		$type_h2 = get_field('types-h2');
		$type_block = get_field('types-block');
		$count_type = 1;
		if ($type_redirect) {?>
			<section class="types-cutting">
				<h2><?=$type_h2;?></h2>
				<?echo '<div class="types-cutting__container">';
			
					foreach($type_block as $block) {
						$h3 = $block['types-h3'];
						$url = $block['types-link'];
						$link_svg = $block['types-svg'];
						echo '<a class="types-cutting__item" href="'.$url.'"> <img src="'.$link_svg.'" alt="types-cutting-'.$count_type.'"><div><span>'.$h3.'</span></div></a>';
						$count_type++;
					}
				echo '</div>';?>
			</section>
		<?}else {?>
			<section class="types-work">
				<div class="types-work__container">
					<h2><?=$type_h2;?></h2>
					<?echo '<div class="types-work__items">';
						foreach($type_block as $block) {
							$h3 = $block['types-h3'];
							$url = $block['types-link'];
							$link_svg = $block['types-svg'];
							echo '<div class="types-work__item"><img src="'.$link_svg.'" alt="types-work-'.$count_type.'"><span>'.$h3.'</span></div>';
							$count_type++;
						}
					echo '</div>';?>
				</div>
			</section>
		<?}?>
	<?}?>
	<?
	$table_tech_on = get_field('equipment-on');
	$table_tech = get_field('table_equipment');
	$table_tech_have = $table_tech['body'];
	unset($head_text);
	if (!empty($table_tech) && $table_tech_on && !empty($table_tech_have)) {
	echo '<section class="equipment"><h2>'.get_field('equipment-h2').'</h2><div class="equipment__table"><div class="equipment__container"><div class="table-scrollbar"> <div class="swiper-scrollbar-drag"></div></div><div class="table-grid">';
		foreach($table_tech['header'] as $index => $th) {
			if ($index === 0) {
				echo '<div class="table-rowspan">'.$th['c'].'</div>';
			}else if ($index !== 0) {
				$head_text[] = $th['c'];
			}
		}
		echo '<div class="table-col">';
			foreach($table_tech['body'] as $index_tr => $tr) {
				//$row = $index_tr;
				echo '<div>';
				foreach($tr as $index_td => $td) {
					$col = $index_td;
					if ($index_td === 0) {
						$content = $td['c'];
						if (empty($content)) {
							echo '-';
						} else {
							echo $content;
						}
					}
				}
				echo '</div>';
			}
		echo '</div>';
		echo '<div class="table-body"><div class="swiper swiper-table" data-mobile="false"><div class="swiper-wrapper">';
		$count_row = 0;
		$count_col = 1;
		$count = 0;
		$first = true;
		for ($i=0; $i<$col; $i++) {
			echo '<div class="swiper-slide">';
				foreach($table_tech['body'] as $index_tr => $tr) {
					if ($index_tr == $count_row) {
						foreach($tr as $index_td => $td) {
							if (($index_td == $count_col) && ($index_td !== 0)) {
								if ($first) {
									echo '<div>'.$head_text[$count].'</div>';
									$count++;
									$first = false;
								}
								echo '<div>';
									$content = $td['c'];
									if (empty($content)) {
										echo '-';
									} else {
										echo $content;
									}
								echo '</div>';
							}
						}
						$count_row++;
					}
				}
				$count_col++;
				$count_row = 0;
				$first = true;
			echo '</div>';
		}
		echo '</div></div></div>';

	echo '</div></div></div></section>';
	}
	?>
	<section class="example-order">
		<div class="example-order__container">
			<h2>Оформление заказа в&nbsp;компании “СтальЦентр”</h2>
			<div class="example-order__col">
				<div class="example-order__item item-one">
					<h3>Входящая заявка</h3>
					<p>Расскажите менеджерам вашу задачу связавшись любым удобным способом
						<button data-btn="calculation" class="open-popup-js example-order__item_btn">Оставьте
							заявку</button> или звоните по&nbsp;тел.&nbsp;<?contacts('example-order','');?>
					</p>
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
	<div class="bg-service">
	<?section_works('');?>
	<section class="full-services">
		<div class="full-services__container">
			<h2>Оказываем комплексные услуги металлообработки</h2>
			<?wp_nav_menu(array(
				'theme_location'  => 'services',
				'menu_id'      => false,
				'container'       => 'div',
				'container_class' => 'full-services__col',
				'menu_class'      => false,
				'items_wrap'      => '%3$s',
				'order' => 'ASC',
				'before' => $id_need,
				'walker' => new services()   
			));?>
		</div>
	</section>
	<?contacts_form($id_services, null);?>
	</div>
</main>
<?get_footer();?>