<?php 
/**
 * single.php
 * 
 * Modified version of the V1.03 Good Space WordPress Theme
 * Modified by Ian Renshaw for www.ianandsam.net
 *
 * Change History
 * ---------------
 * 
 * 2012-10-17 - Comment out - 'About Author' and 'Include Social Shares'
 *
 */
get_header(); ?>
	<?php
		// Check and get Sidebar Class
		$sidebar = get_post_meta($post->ID,'post-option-sidebar-template',true);
		global $default_post_sidebar;
		if( empty( $sidebar ) ){ $sidebar = $default_post_sidebar; }
		if( $sidebar == "left-sidebar" || $sidebar == "right-sidebar"){
			$sidebar_class = "sidebar-included " . $sidebar;
		}else if( $sidebar == "both-sidebar" ){
			$sidebar_class = "both-sidebar-included";
		}else{
			$sidebar_class = '';
		}

		// Translator words
		if( $gdl_admin_translator == 'enable' ){
			$translator_date = get_option(THEME_SHORT_NAME.'_translator_blog_date', 'Date: ');
			$translator_by = get_option(THEME_SHORT_NAME.'_translator_blog_by', 'By: ');
			$translator_tag = get_option(THEME_SHORT_NAME.'_translator_blog_tag', 'Tags: ');
			$translator_comment = get_option(THEME_SHORT_NAME.'_translator_blog_comment', 'Comments: ');		
			$translator_about_author = get_option(THEME_SHORT_NAME.'_translator_about_author', 'About the Author');
			$translator_social_share = get_option(THEME_SHORT_NAME.'_translator_social_shares', 'Social Share');
		}else{
			$translator_date = __('Date: ','gdl_front_end');
			$translator_by = __('By: ','gdl_front_end');
			$translator_tag = __('Tags: ','gdl_front_end');
			$translator_comment = __('Comments: ','gdl_front_end');		
			$translator_about_author = __('About the Author','gdl_front_end');
			$translator_social_share = __('Social Share','gdl_front_end');
		}
	
	?>
	<div class="content-wrapper <?php echo $sidebar_class; ?>">  
		<div class="clear"></div>
		<?php
			$left_sidebar = get_post_meta( $post->ID , "post-option-choose-left-sidebar", true);
			$right_sidebar = get_post_meta( $post->ID , "post-option-choose-right-sidebar", true);
			global $default_post_left_sidebar, $default_post_right_sidebar;
			if( empty( $left_sidebar )){ $left_sidebar = $default_post_left_sidebar; } 
			if( empty( $right_sidebar )){ $right_sidebar = $default_post_right_sidebar; } 

			echo '<div class="sixteen columns mb0">';
			echo '<div class="gdl-page-title-wrapper">';
			echo '<h1 class="gdl-page-title gdl-title title-color">';
			the_title();
			echo '</h1>';
			echo '<div class="gdl-page-caption">';
			echo get_post_meta($post->ID, 'post-option-caption', true);
			echo '</div>';
			echo '<div class="gdl-page-title-left-bar"></div>';
			echo '<div class="clear"></div>';
			echo '</div>'; // gdl-page-title-wrapper
			echo '</div>'; // sixteen columns
			
			echo "<div class='gdl-page-float-left'>";
				
		?>
		
		<div class='gdl-page-item'>
		
		<?php 
			if ( have_posts() ){
				while (have_posts()){
					the_post();

					echo '<div class="sixteen columns mt0">';	
					
					// Inside Thumbnail
					if( $sidebar == "left-sidebar" || $sidebar == "right-sidebar" ){
						$item_size = "640x240";
					}else if( $sidebar == "both-sidebar" ){
						$item_size = "460x170";
					}else{
						$item_size = "940x350";
					} 
					
					$inside_thumbnail_type = get_post_meta($post->ID, 'post-option-inside-thumbnail-types', true);
					
					switch($inside_thumbnail_type){
					
						case "Image" : 
						
							$thumbnail_id = get_post_meta($post->ID,'post-option-inside-thumbnial-image', true);
							$thumbnail = wp_get_attachment_image_src( $thumbnail_id , $item_size );
							$thumbnail_full = wp_get_attachment_image_src( $thumbnail_id , 'full' );
							$alt_text = get_post_meta($thumbnail_id , '_wp_attachment_image_alt', true);
							
							if( !empty($thumbnail) ){
								echo '<div class="blog-thumbnail-image">';
								echo '<a href="' . $thumbnail_full[0] . '" data-rel="prettyPhoto" title="' . get_the_title() . '" ><img src="' . $thumbnail[0] .'" alt="'. $alt_text .'"/></a>'; 
								echo '</div>';
							}
							break;
							
						case "Video" : 
						
							$video_link = get_post_meta($post->ID,'post-option-inside-thumbnail-video', true);
							echo '<div class="blog-thumbnail-video">';
							echo get_video($video_link, gdl_get_width($item_size), gdl_get_height($item_size));
							echo '</div>';							
							break;
							
						case "Slider" : 
						
							$slider_xml = get_post_meta( $post->ID, 'post-option-inside-thumbnail-xml', true); 
							$slider_xml_dom = new DOMDocument();
							$slider_xml_dom->loadXML($slider_xml);
							
							echo '<div class="blog-thumbnail-slider">';
							echo print_flex_slider($slider_xml_dom->documentElement, $item_size);
							echo '</div>';					
							break;
							
					}
					
					echo "<div class='clear'></div>";
					
					// Single info
					echo '<div class="single-thumbnail-info post-info-color">';

					echo '<div class="single-info-inner-wrapper">';
					echo '<span class="single-info-header">' . $translator_date . '</span>';
					echo '<span class="single-info-content">' . get_the_time('d M Y') . '</span>';
					echo '</div>';					

					echo '<div class="single-info-inner-wrapper">';
					echo '<span class="single-info-header">' . $translator_by . '</span>';
					echo '<span class="single-info-content">' . get_the_author_link() . '</span>';	
					echo '</div>';
					
					$tag_string = '<div class="single-info-inner-wrapper">';
					$tag_string = $tag_string . '<span class="single-info-header">' . $translator_tag . '</span>';
					the_tags( $tag_string . '<span class="single-info-content">', ', ', '</span></div>');
					
					echo '<div class="single-info-inner-wrapper">';
					echo '<span class="single-info-header">' . $translator_comment . '</span>';
					echo '<span class="single-info-content">';
					comments_popup_link( __('0','gdl_front_end'), __('1','gdl_front_end'), __('%','gdl_front_end'), '', __('Comments are off','gdl_front_end') );
					echo '</span>'; // single-thumbnial-comment
					echo '</div>';
					
					echo '<div class="clear"></div>';			
					echo '</div>';	// single-thumbnail-info			
					
					// Single Content
					echo "<div class='single-context'>";
					echo "<div class='single-content'>";
					echo the_content();
					echo "</div>";
					echo "</div>"; // single-context
					
					// About Author - Commented out to remove from blog post comments
					// if(get_post_meta($post->ID, 'post-option-author-info-enabled', true) == "Yes"){
// 						echo "<div class='about-author-wrapper'>";
// 						echo "<div class='about-author-avartar'>" . get_avatar( get_the_author_meta('ID'), 90 ) . "</div>";
// 						echo "<div class='about-author-info'>";
// 						echo "<div class='about-author-title gdl-link-title gdl-title'>" . $translator_about_author . "</div>";
// 						echo get_the_author_meta('description');
// 						echo "</div>";
// 						echo "<div class='clear'></div>";
// 						echo "</div>";
// 					}
					
					// Include Social Shares - Commented out to remove from blog post comments
					// if(get_post_meta($post->ID, 'post-option-social-enabled', true) == "Yes"){
// 						echo "<div class='social-share-title gdl-link-title gdl-title'>";
// 						echo $translator_social_share;
// 						echo "</div>";
// 						include_social_shares();
// 						echo "<div class='clear'></div>";
// 					}
				
					echo '<div class="comment-wrapper">';
					comments_template(); 
					echo '</div>';
					
					echo "</div>"; // sixteen-columns
				}
			}
		?>
			
		</div> <!-- gdl-page-item -->
		
		<?php 	
			get_sidebar('left');	
			
			echo "</div>";
			get_sidebar('right');
		?>
		
		<div class="clear"></div>
		
	</div> <!-- content-wrapper -->

<?php get_footer(); ?>