<?
/*
Template Name: Наши работы
Template Post Type: page
*/
get_header();
$title_page = wp_title('',false);
$id_current = get_the_ID();
?>
<main>
	<section class="our-works">
	<section class="bread-crumb __container">
            <?breadcrumb_home_url();?>
            <span class="bread-crumb__item"><?=$title_page;?></span>
        </section>
		<h1 class="our-works__container"><?=$title_page;?></h1>
		<div class="our-works__body">
			<div class="our-works__container">
				<div class="our-works__wrapper">
				<?
				if($cats_filter = get_terms(array('taxonomy' => 'category', 'orderby' => 'name' ))) {
					echo '<div class="our-works__filter" id="filter">';
					echo '<div class="our-works__filter_item search-js active" data-cat="all">Все работы</div>';
					foreach($cats_filter as $filter){
						echo '<div class="our-works__filter_item search-js" data-cat="'.$filter->term_id.'">'.$filter->name.'</div>';
					}
					echo '<div class="our-works__filter_item search-js" data-cat="test">Тест</div>'; //НЕ ЗАБЫВАТЬ УДАЛИТЬ ЭТУ СТРОКУ КАК МАРИНА ПОПРАВИТ СТИЛИ
					echo '</div>';
				}
				?>
					<?
					$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
					$query = new WP_Query( [
						'post_type'      => 'post',
						'posts_per_page' => 8,
						'paged' => $paged,
					]);
					if ($query->have_posts()) {
						echo '<div class="filter-wrapper"><div class="our-works__cards">';
							while ( $query->have_posts() ) {
								$query->the_post();
								?>
								<div class="our-works__card">
									<div class="card-swiper swiper our-works__card_img">
										<div class="swiper-wrapper">
											<?$works_imgs = get_field('fotografii');
											foreach ($works_imgs as $works_img) {
												$count = 1;
												echo '<div class="swiper-slide card__img"> <img data-src="'.$works_img.'" src="'.url_lazy_pixel().'" alt="work-img-'.$count.'" class="swiper-lazy"><div class="swiper-lazy-preloader"></div></div>';
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
										}
										?>
									</div>
									<div class="our-works__card_text type">
										<h3>Вид металла:</h3>
										<?$tags = get_the_tags();
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
							$current = max(1, get_query_var('paged'));
							$base    = str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) );
							$format  = '?paged=%#%';
							$paginate_links = paginate_links(
								apply_filters('the_posts_pagination_args',
									array(
										'base'         => $base,
										'format'       => $format,
										'add_args'     => false,            
										'current'      => max(1, $current),
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
								echo '<nav class="pagination" data-term="all">';
									if ($current == 1 ) {
										echo '<span class="prev disabled"></span>';
									}
									foreach ($paginate_links as $paginate_link) {
										$paginate_link = str_replace( 'page-numbers', 'page-numbers', $paginate_link );
										echo wp_kses_post($paginate_link);
									}
									if ($total == $current) { 
										echo '<span class="next disabled"></span>';
									}
								echo '</nav>';
							}
							wp_reset_postdata();
					}
						?>
						
					</div>
				</div>
			</div>
		</div>
	</section>
	<?contacts_form($id_current, 'our-works');?>
</main>
<?get_footer();?>