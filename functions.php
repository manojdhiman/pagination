if ( !function_exists( 'my_pagination' ) ) {
function my_pagination($total) {
$prev_arrow = is_rtl() ? 'next' : 'prev';
$next_arrow = is_rtl() ? 'prev' : 'next';
global $wp_query;
//$total = $wp_query->max_num_pages;
//$total="10";
$big = 999999999; // need an unlikely integer
if( $total > 1 ) {
if( !$current_page = get_query_var('paged') )
$current_page = 1;
if( get_option('permalink_structure') ) {
$format = '/%#%/';
} else {
$format = '&paged=%#%';
}
echo my_paginate_links(array(
'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
'format' => $format,
'current' => max( 1, get_query_var('paged') ),
'total' => $total,
'mid_size' => 3,
'type' => 'list',
'prev_text' => $prev_arrow,
'next_text' => $next_arrow,
) );
}
}
}
function my_paginate_links( $args = '' ) {
$defaults = array(
'base' => '%_%', // http://example.com/all_posts.php%_% : %_% is replaced by format (below)
'format' => '?page=%#%', // ?page=%#% : %#% is replaced by the page number
'total' => 1,
'current' => 0,
'show_all' => false,
'prev_next' => true,
'prev_text' => __('&laquo; Previous'),
'next_text' => __('Next &raquo;'),
'end_size' => 1,
'mid_size' => 2,
'type' => 'plain',
'add_args' => false, // array of query args to add
'add_fragment' => '',
'before_page_number' => '',
'after_page_number' => ''
);
$args = wp_parse_args( $args, $defaults );
extract($args, EXTR_SKIP);
// Who knows what else people pass in $args
$total = (int) $total;
if ( $total < 2 )
return;
$current = (int) $current;
$end_size = 0 < (int) $end_size ? (int) $end_size : 1; // Out of bounds? Make it the default.
$mid_size = 0 <= (int) $mid_size ? (int) $mid_size : 2;
$add_args = is_array($add_args) ? $add_args : false;
$r = '';
$page_links = array();
$n = 0;
$dots = false;
$prev_link = '<p class="prev-div" ><span class="prev-click">prev</span></p>';
if ( $prev_next && $current && 1 < $current ) :
$link = str_replace('%_%', 2 == $current ? '' : $format, $base);
$link = str_replace('%#%', $current - 1, $link);
if ( $add_args )
$link = add_query_arg( $add_args, $link );
$link .= $add_fragment;
/**
* Filter the paginated links for the given archive pages.
*
* @since 3.0.0
*
* @param string $link The paginated link URL.
*/
$prev_link = '<a class="prev-div" href="' . esc_url( apply_filters( 'paginate_links', $link ) ) . '"><span class="prev-click">prev</span></a>';
endif;
for ( $n = 1; $n <= $total; $n++ ) :
if ( $n == $current ) :
$page_links[] = "<h3 class='page-numbers'>" . $before_page_number . number_format_i18n( $n ) . $after_page_number . "</h3>";
$dots = true;
else :
if ( $show_all || ( $n <= $end_size || ( $current && $n >= $current - $mid_size && $n <= $current + $mid_size ) || $n > $total - $end_size ) ) :
$link = str_replace('%_%', 1 == $n ? '' : $format, $base);
$link = str_replace('%#%', $n, $link);
if ( $add_args )
$link = add_query_arg( $add_args, $link );
$link .= $add_fragment;
/** This filter is documented in wp-includes/general-template.php */
$page_links[] = "<a class='page-numbers' href='" . esc_url( apply_filters( 'paginate_links', $link ) ) . "'>" . $before_page_number . number_format_i18n( $n ) . $after_page_number . "</a>";
$dots = true;
elseif ( $dots && !$show_all ) :
$page_links[] = '<span class="page-numbers dots">' . __( '&hellip;' ) . '</span>';
$dots = false;
endif;
endif;
endfor;
$next_link = '<p class="next-div" ><span class="next-click">next</span></p>';
if ( $prev_next && $current && ( $current < $total || -1 == $total ) ) :
$link = str_replace('%_%', $format, $base);
$link = str_replace('%#%', $current + 1, $link);
if ( $add_args )
$link = add_query_arg( $add_args, $link );
$link .= $add_fragment;
/** This filter is documented in wp-includes/general-template.php */
//$page_links[] = '<a class="next page-numbers" href="' . esc_url( apply_filters( 'paginate_links', $link ) ) . '">' . $next_text . '</a>';
$next_link = '<a class="next-div" href="' . esc_url( apply_filters( 'paginate_links', $link ) ) . '"><span class="next-click">next</span></a>';
endif;
switch ( $type ) :
case 'array' :
return $page_links;
break;
case 'list' :
$r.='<div class="my_pagination">';
$r.=$prev_link;
$r .= "<ul class='page-numbers'>\n\t<li>";
$r .= join("</li>\n\t<li>", $page_links);
$r .= "</li>\n</ul>\n";
$r.=$next_link;
$r.='<div class="clear"></div></div>';
break;
default :
$r = join("\n", $page_links);
break;
endswitch;
return $r;
}
