<?php
/*
Template Name: Page full-width 100%
*/

//Use custom field:
//ipin_page_categories
//ipin_page_tags
?>
<?php get_header(); ?>

<div class="container-fluid">
	<div class="row">
		<?php while (have_posts()) : the_post(); ?>
		<div id="post-<?php the_ID(); ?>" <?php post_class('post-wrapper'); ?>>
			<div class="h1-wrapper">
				<h1><?php the_title(); ?></h1>
			</div>		

			<div class="post-content">
				<div class="thecontent">
				<?php the_content(); ?>
				</div>
				<?php
				wp_link_pages( array( 'before' => '<p><strong>' . __('Pages:', 'ipin') . '</strong>', 'after' => '</p>' ) );
				edit_post_link(__('Edit Page', 'ipin'),'<p>[ ',' ]</p>');
				?>
			</div>
			
			<div class="post-comments">
				<div class="post-comments-wrapper">
					<?php comments_template(); ?>
				</div>
			</div>
			
		</div>
		<?php endwhile; ?>
		
		<div id="post-masonry" class="container-fluid">
			<div class="row">
			<?php
			$ipin_page_categories = explode(',', get_post_meta($post->ID, 'ipin_page_categories', true));
			$ipin_page_tags = explode(',', get_post_meta($post->ID, 'ipin_page_tags', true));
			$ipin_page_posts = explode(',', get_post_meta($post->ID, 'ipin_page_posts', true));
			
			$pnum = isset($_GET['pnum']) ? intval($_GET['pnum']) : 1;
			
			if (get_post_meta($post->ID, 'ipin_page_categories', true) != '') {
				$args = array(
					'tax_query' => array(
						array(
							'taxonomy' => 'category',
							'field' => 'term_id',
							'terms' => $ipin_page_categories,
						)
					),
					'meta_query' => array(
						'relation' => 'OR',
						array(
							'key' => '_Original Post ID',
							'compare' => 'NOT EXISTS'
						),
						array(
							'key' => '_Original Post ID',
							'value' => 'deleted'
						)
					),
					'paged' => $pnum
				);
			}


			if (get_post_meta($post->ID, 'ipin_page_tags', true) != '') {
				$args = array(
					'tax_query' => array(
						array(
							'taxonomy' => 'post_tag',
							'field' => 'term_id',
							'terms' => $ipin_page_tags,
						)
					),
					'meta_query' => array(
						'relation' => 'OR',
						array(
							'key' => '_Original Post ID',
							'compare' => 'NOT EXISTS'
						),
						array(
							'key' => '_Original Post ID',
							'value' => 'deleted'
						)
					),
					'paged' => $pnum
				);
			}
			
			if (get_post_meta($post->ID, 'ipin_page_posts', true) != '') {
				$args = array(
					'post__in' => $ipin_page_posts,
					'paged' => $pnum
				);
			}
				
			query_posts($args);
			$maxpage = $wp_query->max_num_pages;
			?>
		
			<?php if (have_posts()) { ?>
				<div id="ajax-loader-masonry" class="ajax-loader"></div>
			
				<h3 class="text-center"><?php _e('Related Pins', 'ipin'); ?></h3>
			<?php } ?>
		
			<div id="masonry" class="row">
				<?php $count_ad = 1; if (have_posts()) : while (have_posts()) : the_post(); ?>
				
				<?php if (of_get_option('frontpage1_ad') == $count_ad && of_get_option('frontpage1_ad_code') != '' && ($pnum == 1  || of_get_option('infinitescroll') == 'disable')) { ?>
				<div class="thumb thumb-ad-wrapper">
					<div class="thumb-ad">				
						<?php eval('?>' . of_get_option('frontpage1_ad_code')); ?>
					</div>	 
				</div>
				<?php } ?>
				
				<?php if (of_get_option('frontpage2_ad') == $count_ad && of_get_option('frontpage2_ad_code') != '' && ($pnum == 1  || of_get_option('infinitescroll') == 'disable')) { ?>
				<div class="thumb thumb-ad-wrapper">
					<div class="thumb-ad">				
						<?php eval('?>' . of_get_option('frontpage2_ad_code')); ?>
					</div>	 
				</div>
				<?php } ?>
				
				<?php if (of_get_option('frontpage3_ad') == $count_ad && of_get_option('frontpage3_ad_code') != '' && ($pnum == 1  || of_get_option('infinitescroll') == 'disable')) { ?>
				<div class="thumb thumb-ad-wrapper">
					<div class="thumb-ad">				
						<?php eval('?>' . of_get_option('frontpage3_ad_code')); ?>
					</div>	 
				</div>
				<?php } ?>
				
				<?php if (of_get_option('frontpage4_ad') == $count_ad && of_get_option('frontpage4_ad_code') != '' && ($pnum == 1 || of_get_option('infinitescroll') == 'disable')) { ?>
				<div class="thumb thumb-ad-wrapper">
					<div class="thumb-ad">				
						<?php eval('?>' . of_get_option('frontpage4_ad_code')); ?>
					</div>	 
				</div>
				<?php } ?>
				
				<?php if (of_get_option('frontpage5_ad') == $count_ad && of_get_option('frontpage5_ad_code') != '' && ($pnum == 1 || of_get_option('infinitescroll') == 'disable')) { ?>
				<div class="thumb thumb-ad-wrapper">
					<div class="thumb-ad">				
						<?php eval('?>' . of_get_option('frontpage5_ad_code')); ?>
					</div>	 
				</div>
				<?php } ?>
				
				<?php
				get_template_part('index-masonry-inc');
				$count_ad++;
				endwhile;
				?>
		
				<?php 
				endif;
				wp_reset_query(); 
				?>
			</div>
			
			<?php if ($maxpage != 0) { ?>
			<div id="navigation">
				<ul class="pager">			
					<?php if ($pnum != 1 && $maxpage >= $pnum) { ?>
					<li id="navigation-previous">
						<a href="<?php echo get_permalink() . '?pnum=' . ($pnum-1); ?>"><?php _e('&laquo; Previous', 'ipin') ?></a>
					</li>
					<?php } ?>
					
					<?php if ($maxpage != 1 && $maxpage != $pnum) { ?>
					<li id="navigation-next">
						<a href="<?php echo get_permalink() . '?pnum=' . ($pnum+1); ?>"><?php _e('Next &raquo;', 'ipin') ?></a>
					</li>
					<?php } ?>
				</ul>
			</div>
			<?php } ?>
			</div>
		</div>


	</div>
</div>

<?php get_footer(); ?>