<?
/*
Template Name: Доставка и оплата
Template Post Type: page
*/
get_header();
$title_page = wp_title('',false);
$id_current = get_the_ID();
?>
<main>
	<section class="first-screen-delivery-payment">
		<section class="bread-crumb __container">
            <?breadcrumb_home_url();?>
            <span class="bread-crumb__item"><?echo $title_page;?></span>
        </section>
		<div class="first-screen-delivery-payment__container">
			<h1><?echo $title_page;?></h1>
			<div class="first-screen-delivery-payment__descr">
				<p><?echo get_field('descr');?></p>
			</div>
		</div>
	</section>
	<div class="delivery-payment">
		<section class="delivery">
			<div class="delivery__container">
				<h2>Транспортные компании, с которыми мы работаем:</h2>
				<div class="delivery__links">
					<?$deliverys = get_field('delivery');
					$count = 1;
					foreach($deliverys as $delivery) {
						$logo = $delivery['delivery-logo'];
						$url = $delivery['delivery-link'];
						echo '<a href="'.$url.'" target="_blank"><img src="'.$logo.'" alt="delivery-'.$count.'"></a>';
						$count++;
					}?>
				</div>
			</div>
		</section>
		<section class="payment">
			<div class="payment__container">
				<h2>Принимаем оплату любым удобным способом:</h2>
				<div class="payment__body">
					<div class="payment__items">
						<div class="payment__item">Наличный расчет </div>
						<div class="payment__item">Оплата банковской картой</div>
						<div class="payment__item">Банковский перевод
							<br> на расчетный счет компании
						</div>
					</div>
					<div class="payment__descr">
						<p><?echo get_field('payment-descr');?></p>
					</div>
				</div>
			</div>
		</section>
	</div>
	<?contacts_form($id_current, 'delivery-payment');?>
</main>
<?get_footer();?>