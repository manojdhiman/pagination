$total=wp_count_posts('press_release');
				$pager_id = (get_query_var('paged')) ? get_query_var('paged') : 1;
				$my_query = new WP_Query('post_type=press_release&post_status=publish&posts_per_page=9&meta_key=date_release&orderby=date_release&order=DESC&&paged='.$pager_id);
				if( $my_query->have_posts() ) {
				  while ($my_query->have_posts()) : $my_query->the_post(); 
					
					
					//code here
             
				  endwhile;
				}
				wp_reset_query();  // Restore global post data stomped by the_post().
			?>                          
				 
				 
				 // pagination div
				 
				 <?php my_pagination(ceil($total->publish/9)); ?>
