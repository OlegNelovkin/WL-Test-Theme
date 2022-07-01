<?php
get_header();
?>

<main id="primary" class="site-main">
	<?php if ( have_posts() ) : ?>
		<?php while ( have_posts() ) : the_post(); ?>
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<h1><?php the_title(); ?></h1>
			<?php
			$car_color = get_post_meta(get_the_ID(), 'car-color', true);
			$car_fuel = get_post_meta(get_the_ID(), 'car-fuel', true);
			$car_power = get_post_meta(get_the_ID(), 'car-power', true);
			$car_price = get_post_meta(get_the_ID(), 'car-price', true);
			?>
			<?php if ( has_post_thumbnail()) : ?>
				<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" >
					<?php the_post_thumbnail(); ?>
				</a>
			<?php endif; ?>
			<?php the_content(); ?>
			<div class="car-characteristics">
				<?php 
				$car_brand = get_the_terms( $post->ID, 'car_brand' );
				$car_country = get_the_terms( $post->ID, 'car_country' );
				?>
				<?php
				foreach($car_brand as $car_brands) {?>
					<p>Марка: <?php echo $car_brands->name; ?></p>
				<?php } ?>
				<?php
				foreach($car_country as $car_countrys) {?>
					<p>Страна: <?php echo $car_countrys->name; ?></p>
				<?php } ?>

				<?php if($car_price) {?>
					<p>Цена: <?php echo $car_price; ?>$</p>
				<?php } ?>

				<?php if($car_fuel) {?>
					<p>Топливо: <?php echo $car_fuel; ?></p>
				<?php } ?>

				<?php if($car_power) {?>
					<p>Мощность <?php echo $car_power; ?> л.с.</p>
				<?php } ?>

				<?php if($car_color) {?>
					<p>Цвет: <?php echo $car_color; ?></p>	
					<div class="color-example" style="background-color:<?php echo $car_color; ?> ;"></div>
				<?php } ?>
			</div>
		</article>
		<?php endwhile; ?>
	<?php endif; ?>
</main>
<?php

get_footer();
