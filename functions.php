<?php

add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );
add_action( 'admin_enqueue_scripts', 'my_theme_enqueue_styles' );
function my_theme_enqueue_styles() {
    $parenthandle = 'parent-style'; 
    $theme = wp_get_theme();
    wp_enqueue_style( $parenthandle, get_template_directory_uri() . '/style.css', array(), $theme->parent()->get('Version') );
    wp_enqueue_style( 'child-style', get_stylesheet_uri(), array( $parenthandle ), $theme->get('Version') );

    if( is_page(118) )
    {
        wp_enqueue_style( 'custom-css', get_template_directory_uri() . '-child/assets/custom.css', rand() );
    }

    wp_enqueue_script('jquery');
    wp_localize_script( 'jquery', 'my_ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );

    if( is_page(118) )
    {
        wp_enqueue_script( 'oops-custom-js', get_template_directory_uri() . '-child/assets/oops-custom.js', array(), rand() );

    }else{

        wp_enqueue_script( $parenthandle, get_template_directory_uri() . '-child/assets/custom.js', array(), rand() );
    }
    wp_enqueue_script( 'jquery-ui', 'https://code.jquery.com/ui/1.13.2/jquery-ui.js', array(), rand() );
}

function wpdocs_codex_book_init() {
    $labels = array(
        'name'                  => _x( 'Books', 'Post type general name', 'textdomain' ),
        'singular_name'         => _x( 'Book', 'Post type singular name', 'textdomain' ),
        'menu_name'             => _x( 'Books', 'Admin Menu text', 'textdomain' ),
        'name_admin_bar'        => _x( 'Book', 'Add New on Toolbar', 'textdomain' ),
        'add_new'               => __( 'Add New', 'textdomain' ),
        'add_new_item'          => __( 'Add New Book', 'textdomain' ),
        'new_item'              => __( 'New Book', 'textdomain' ),
        'edit_item'             => __( 'Edit Book', 'textdomain' ),
        'view_item'             => __( 'View Book', 'textdomain' ),
        'all_items'             => __( 'All Books', 'textdomain' ),
        'search_items'          => __( 'Search Books', 'textdomain' ),
        'parent_item_colon'     => __( 'Parent Books:', 'textdomain' ),
        'not_found'             => __( 'No books found.', 'textdomain' ),
        'not_found_in_trash'    => __( 'No books found in Trash.', 'textdomain' ),
        'featured_image'        => _x( 'Book Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'textdomain' ),
        'set_featured_image'    => _x( 'Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'textdomain' ),
        'remove_featured_image' => _x( 'Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'textdomain' ),
        'use_featured_image'    => _x( 'Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'textdomain' ),
        'archives'              => _x( 'Book archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'textdomain' ),
        'insert_into_item'      => _x( 'Insert into book', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'textdomain' ),
        'uploaded_to_this_item' => _x( 'Uploaded to this book', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'textdomain' ),
        'filter_items_list'     => _x( 'Filter books list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'textdomain' ),
        'items_list_navigation' => _x( 'Books list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'textdomain' ),
        'items_list'            => _x( 'Books list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'textdomain' ),
    );
 
    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'book' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ),
    );
 
    register_post_type( 'book', $args );
}
 
add_action( 'init', 'wpdocs_codex_book_init' );


function wpdocs_create_book_taxonomies() {
    // Add new taxonomy, make it hierarchical (like categories)
    $labels = array(
        'name'              => _x( 'Genres', 'taxonomy general name', 'textdomain' ),
        'singular_name'     => _x( 'Genre', 'taxonomy singular name', 'textdomain' ),
        'search_items'      => __( 'Search Genres', 'textdomain' ),
        'all_items'         => __( 'All Genres', 'textdomain' ),
        'parent_item'       => __( 'Parent Genre', 'textdomain' ),
        'parent_item_colon' => __( 'Parent Genre:', 'textdomain' ),
        'edit_item'         => __( 'Edit Genre', 'textdomain' ),
        'update_item'       => __( 'Update Genre', 'textdomain' ),
        'add_new_item'      => __( 'Add New Genre', 'textdomain' ),
        'new_item_name'     => __( 'New Genre Name', 'textdomain' ),
        'menu_name'         => __( 'Genre', 'textdomain' ),
    );
 
    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'genre' ),
    );
 
    register_taxonomy( 'genre', array( 'book' ), $args );
 
    unset( $args );
    unset( $labels );
 
    // Add new taxonomy, NOT hierarchical (like tags)
    $labels = array(
        'name'                       => _x( 'Writers', 'taxonomy general name', 'textdomain' ),
        'singular_name'              => _x( 'Writer', 'taxonomy singular name', 'textdomain' ),
        'search_items'               => __( 'Search Writers', 'textdomain' ),
        'popular_items'              => __( 'Popular Writers', 'textdomain' ),
        'all_items'                  => __( 'All Writers', 'textdomain' ),
        'parent_item'                => null,
        'parent_item_colon'          => null,
        'edit_item'                  => __( 'Edit Writer', 'textdomain' ),
        'update_item'                => __( 'Update Writer', 'textdomain' ),
        'add_new_item'               => __( 'Add New Writer', 'textdomain' ),
        'new_item_name'              => __( 'New Writer Name', 'textdomain' ),
        'separate_items_with_commas' => __( 'Separate writers with commas', 'textdomain' ),
        'add_or_remove_items'        => __( 'Add or remove writers', 'textdomain' ),
        'choose_from_most_used'      => __( 'Choose from the most used writers', 'textdomain' ),
        'not_found'                  => __( 'No writers found.', 'textdomain' ),
        'menu_name'                  => __( 'Writers', 'textdomain' ),
    );
 
    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'               => array( 'slug' => 'writer' ),
    );
 
    register_taxonomy( 'writer', 'book', $args );
}
// hook into the init action and call create_book_taxonomies when it fires
add_action( 'init', 'wpdocs_create_book_taxonomies', 0 );

include get_stylesheet_directory().'/shortcode_html.php';

/*
add_shortcode( 'filter_post', 'filter_post_func' );
function filter_post_func( $atts ) {
    shortcode_html($atts);
	// echo '<pre>'; print_r($atts); echo '</pre>';
    
}
*/

add_action( 'wp_ajax_nopriv_get_books', 'get_books' );
add_action( 'wp_ajax_get_books', 'get_books' );

function get_books() {
    
    $current_page = (isset($_POST['current_page']) && !empty($_POST['current_page'])) ? $_POST['current_page'] : 1 ;
    $cat_slug = (isset($_POST['cat_slug']) && !empty($_POST['cat_slug']) ) ? $_POST['cat_slug'] : '' ;
    $writer = (isset($_POST['writer']) && !empty($_POST['writer']) ) ? $_POST['writer'] : '' ;
    $book_evnt = (isset($_POST['book_evnt']) && !empty($_POST['book_evnt']) ) ? $_POST['book_evnt'] : '' ;
    $search_book = (isset($_POST['search_book']) && !empty($_POST['search_book']) ) ? $_POST['search_book'] : '' ;

    $html = filter_books_fun($current_page, $cat_slug, $writer, $book_evnt, $search_book);

    echo $html;

    die();
}

function filter_books_fun($page_num, $cat_slug, $writer,$book_evnt,$search_book) {
    
       
    $html = array();
    $tax_args = $meta_args = array();

    if(isset($book_evnt) && !empty($book_evnt) ) {
        $newDate = date("Ymd", strtotime($book_evnt));

        $meta_args = array(
            'relation' => 'AND',
            array(
                'key' => 'start_date',
                'value' => $newDate,
                'compare' => '<=',
                'type' => 'DATE',
            ),
            array(
                'key' => 'end_date',
                'value' => $newDate,
                'compare' => '>=',
                'type' => 'DATE',
            ),
    
        );
    }
    

    if(!empty($cat_slug) && $cat_slug !== 'all') {
        $tax_args = array(
            array(
                'taxonomy'  => 'genre',
                'field'     => 'slug',
                'terms'     => $cat_slug
            ),
        );
    }

    if(!empty($writer) && $writer !== 'all') {
        $tax_args = array(
            array(
                'taxonomy'  => 'writer',
                'field'     => 'slug',
                'terms'     => $writer
            ),
        );
    }

    if(!empty($cat_slug) && $cat_slug !== 'all' && !empty($writer) && $writer !== 'all') {
        $tax_args = array(
            'relation' => 'AND',
                array(
                    'taxonomy'  => 'genre',
                    'field'     => 'slug',
                    'terms'     => $cat_slug
                ),
                array(
                    'taxonomy' => 'writer',
                    'field' => 'slug',
                    'terms' => $writer
                )
            );
    }
            
    $args = array(
        'post_type' => 'book',
        'post_status' => 'publishs',
        'posts_per_page' => 2,
        'paged' => $page_num,
        'tax_query' => $tax_args,
        'meta_query' => $meta_args,
        's' => $search_book
    );
    
    $query = new wp_Query($args);

    $total_pages = $query->max_num_pages;   
    
    if ( $query->have_posts() ) :
        while ( $query->have_posts() ) :
            $query->the_post();

            $html['books_html'] .= '<div class="column">
                        <div class="card">
                             '.get_the_post_thumbnail().'
                            <h5>'. get_the_title().'</h5>';

            $html['books_html']  .= '<p>'.(!empty(get_post_meta(get_the_ID(), 'start_date', true))) ? date("d/m/Y", strtotime(get_post_meta(get_the_ID(), 'start_date', true)))  : '';

            $html['books_html']  .= (!empty(get_post_meta(get_the_ID(), 'start_date', true)) && !empty(get_post_meta(get_the_ID(), 'end_date', true)) ) ? '-' : '';

            $html['books_html']  .= (!empty(get_post_meta(get_the_ID(), 'end_date', true))) ? date("d/m/Y", strtotime(get_post_meta(get_the_ID(), 'end_date', true))) : '';

            $html['books_html']  .= '</p>';
            
            $html['books_html'] .=    '</div> </div>';
        endwhile;
    endif;

    
    if ( $total_pages > 1 ) :
        $html['pagination'] .= ($page_num == 1) ? '' : '<a href="javascript:void(0);" class="pagination_prev_book" id="pagination_prev_book" data-current_page="prev_book">&laquo;</a>';
            for( $i=1; $i<=$total_pages; $i++ ) : 
                if($i == $page_num) {
                    $html['pagination'] .=    '<a href="javascript:void(0);" class="active pagination_book" id="pagination_book" data-current_page="'.$i.'" data-total_pages="'. $total_pages .'" >'. $i .'</a>';
                }else {
                    $html['pagination'] .= '<a href="javascript:void(0);" class="pagination_book" id="pagination_book" data-current_page="'. $i .'" data-total_pages="'. $total_pages .'" >'. $i .'</a>';
                }
            endfor;

        $html['pagination'] .= ($page_num == $total_pages) ? '' : '<a href="javascript:void(0);" class="pagination_next_book" id="pagination_next_book" data-current_page="next_book">&raquo;</a>';
    endif;
    
    return json_encode($html);
}


add_action( 'wp_ajax_nopriv_get_filter_posts', 'get_filter_posts' );
add_action( 'wp_ajax_get_filter_posts', 'get_filter_posts' );
function get_filter_posts()
{
    $post_type = (isset($_POST['post_type']) && !empty($_POST['post_type'])) ? $_POST['post_type'] : 'post' ;
    $current_page = (isset($_POST['current_page']) && !empty($_POST['current_page'])) ? $_POST['current_page'] : 1 ;

    filter_posts_fun($current_page, $post_type);
}

function filter_posts_fun($current_page,$post_type)
{
    $posts_obj = new Filter_post();
    $posts = $posts_obj->get_posts( $post_type , $current_page );
    
    $posts_obj->get_post_html($posts);
    $posts_obj->get_post_pagination($posts,$current_page);
        
    die;
}

add_action('init', function() {
    // global $wpdb;
    // $tblname = 'simple_plugin';
    // $wp_track_table = $wpdb->prefix . "$tblname";
    
    // $sql = "CREATE TABLE IF NOT EXISTS $wp_track_table ( ";
    // $sql .= "  `id`  int(11)   NOT NULL auto_increment, ";
    // $sql .= "  `name`  varchar(128)   NOT NULL, ";
    // $sql .= "  `post_type`  varchar(128)   NOT NULL, ";
    // $sql .= "  `date`  varchar(128)   NOT NULL, ";
    // $sql .= "  PRIMARY KEY `order_id` (`id`) "; 
    // $sql .= ") ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ; ";
    // require_once( ABSPATH . '/wp-admin/includes/upgrade.php' );
    
    // dbDelta($sql);

    // $sql = "DELETE a,b,c
    // FROM wp_posts a
    // LEFT JOIN wp_term_relationships b ON (a.ID = b.object_id)
    // LEFT JOIN wp_postmeta c ON (a.ID = c.post_id)
    // WHERE a.post_status = 'draft'";
    // dbDelta($sql);

    global $wpdb;
    $removefromdb = $wpdb->query("DELETE a,b,c FROM wp_posts a  LEFT JOIN wp_term_relationships b ON (a.ID = b.object_id) LEFT JOIN wp_postmeta c ON (a.ID = c.post_id)
    WHERE a.post_status = 'draft'");
});


// add_action( 'save_post', 'save_cd_meta_box_data' );

// function save_cd_meta_box_data( $post_id ) {

//     if(count($_POST['acf']) == '2' ) {
//         echo '<pre>'; print_r($_POST['acf']); echo '</pre>';
//     }else{
//         global $error;
//         $error->add('regerror','Email already in use. Did you forget your Password? If yes click here to reset.');
//         $error = new WP_Error();
//         echo '<pre>'; print_r($error); echo '</pre>';
//         // echo count($_POST['acf']);
//     }
//     die;
// }

// add_action( 'save_post', 'save_cd_meta_box_data' );
// function save_cd_meta_box_data( $post_id ) 
// {
//     // echo '<pre>'; print_r($_POST['acf']); echo '</pre>';
//     // die;
//     if(count($_POST['acf']) == '2' ) {
//         update_post_meta( $post_id, 'title_1', $_POST['acf']['field_63a192491a09f'] );
//         update_post_meta( $post_id, 'title_1', $_POST['acf']['field_63a192611a0a0'] );
//         update_post_meta( $post_id, 'title_1', $_POST['acf']['field_63a1926b1a0a1'] );
//     }else{
//         add_filter('redirect_post_location', 'my_redirect_post_location_filter', 99);
//     }
// }

// add_filter('wp_insert_post_data', 'ccl', 99);
// function ccl($data) {
//     echo '<pre>'; print_r($_POST); echo '</pre>';
//     die;
//     if(count($_POST['acf']) == '2' ) {
//     $data['post_status'] = 'draft';
//     add_filter('redirect_post_location', 'my_redirect_post_location_filter', 99);
//   }
//   return $data;
// }

// function my_redirect_post_location_filter($location) {
//     remove_filter('redirect_post_location', __FUNCTION__, 99);
//     $location = add_query_arg('message', 99, $location);
//     return $location;
// }

// add_filter('post_updated_messages', 'my_post_updated_messages_filter');
// function my_post_updated_messages_filter($messages) {
//   $messages['post'][99] = 'Publish not allowed';
//   return $messages;
// }

// function custom_post_site_save($post_id, $post_data) {
//     echo '<pre>'; print_r($post_data); echo '</pre>';
//     die;
// 	# If this is just a revision, don't do anything.
// 	if (wp_is_post_revision($post_id))
// 		return;

// 	if ($post_data['post_type'] == 'site') {
// 		# In this example, we will deny post titles with less than 5 characters
// 		if (strlen($post_data['post_title'] < 5)) {
// 			# Add a notification
// 			update_option('my_notifications', json_encode(array('error', 'Post title can\'t be less than 5 characters.')));
// 			# And redirect
// 			header('Location: '.get_edit_post_link($post_id, 'redirect'));
// 			exit;
// 		}
// 	}
// }
// add_action( 'pre_post_update', 'custom_post_site_save', 10, 2);

/**
 *   Shows custom notifications on wordpress admin panel
 */
// function my_notification() {
// 	$notifications = get_option('my_notifications');

// 	if (!empty($notifications)) {
// 		$notifications = json_decode($notifications);
// 		#notifications[0] = (string) Type of notification: error, updated or update-nag
// 		#notifications[1] = (string) Message
// 		#notifications[2] = (boolean) is_dismissible?
// 		switch ($notifications[0]) {
// 			case 'error': # red
// 			case 'updated': # green
// 			case 'update-nag': # ?
// 				$class = $notifications[0];
// 				break;
// 			default:
// 				# Defaults to error just in case
// 				$class = 'error';
// 				break;
// 		}

// 		$is_dismissable = '';
// 		if (isset($notifications[2]) && $notifications[2] == true)
// 			$is_dismissable = 'is_dismissable';

// 		echo '<div class="'.$class.' notice '.$is_dismissable.'">';
// 		echo '<p>'.$notifications[1].'</p>';
// 		echo '</div>';

// 		# Let's reset the notification
// 		update_option('my_notifications', false);
// 	}
// }
// add_action( 'admin_notices', 'my_notification' );



// add_action( 'admin_notices', 'my_error_message' );

function my_textarea_validate_value( $valid, $value, $field, $input ) {
    // echo '<pre>'; print_r($_POST); echo '</pre>';
    // echo '<pre>'; print_r($field); echo '</pre>';
    // Bail early if the field is already invalid.
    // if ( ! $valid ) {
    //     return $valid;
    // }

    if(count($_POST['acf']) == '2' ) {
        return $valid;
    }else{
        $valid = sprintf( __( 'Value must not include "%s"', 'my-textdomain' ) );
    }

    // Bail early if the "Exclude Words" setting is not set.
    // if ( !empty( $field['title_1'] ) ) {
    //     return $valid;
    // }else{
    //     $valid = sprintf( __( 'Value must not include "%s"', 'my-textdomain' ) );
    //         // break;
    // }

    // // Explode the words into an array we can loop over.
    // $words = explode( ',', $field['title_1'] );
    // $words = array_map( 'trim', $words ); // Trim white spaces.
    // $words = array_values( $words ); // Remove empty values.

    // foreach( $words as $word ) {
    //     // Check if the word exists.
    //     if( stripos( $value, $word ) !== false ) {
    //         $valid = sprintf( __( 'Value must not include "%s"', 'my-textdomain' ), $word );
    //         break;
    //     }
    // }

    return $valid;
}
add_filter( 'acf/validate_value/name=title_1', 'my_textarea_validate_value', 10, 4 );

add_filter( 'wpcf7_validate_email*', 'custom_email_confirmation_validation_filter', 20, 2 );
  
function custom_email_confirmation_validation_filter( $result, $tag ) {
//   if ( 'your-email-confirm' == $tag->name ) {
//     $your_email = isset( $_POST['your-email'] ) ? trim( $_POST['your-email'] ) : '';
//     $your_email_confirm = isset( $_POST['your-email-confirm'] ) ? trim( $_POST['your-email-confirm'] ) : '';
  
    // if ( $your_email != $your_email_confirm ) {
      $result->invalidate( $tag, "Are you sure this is the correct address?" );
//     }
//   }
  
  return $result;
}

add_action('admin_head', function() {
?>
<script>

    jQuery(document).ready(function(){ 
        jQuery('.acf-checkbox-list input[type="checkbox"]').on('click', function(){
            if (jQuery(this).is(':checked')) {
                var currentRow = jQuery(this).closest('li').addClass('test852');
            }
        });
    })
    /*
    jQuery(document).ready(function(){       
        jQuery('.acf-checkbox-list input[type="checkbox"]').on('click', function(){
            if (jQuery(this).is(':checked')) {
                // jQuery(this).parents('li').find('.children input[type="checkbox"]').attr('checked', true);
                // jQuery(this).parents('li').find('ul input[type="checkbox"]').attr('checked', true);
                // var id = jQuery(this).parents('li').find('ul input[type="checkbox"]').val();
                
                var id = jQuery(this).parents('li:first').addClass('test123');
                jQuery(this).parents('li:first').find('.children input[type="checkbox"]').attr('checked', true);
            } else {    
                jQuery(this).parents('li:first').find('.children input[type="checkbox"]').attr('checked', false);
                // jQuery(this).parents('li').find('.children input[type="checkbox"]').attr('checked', false);
            }
        });
    })
    */

    /*
    $(document).ready(function() {
        $('.child').on('change', ':checkbox', function() {
            if ($(this).is(':checked')) {
                var currentRow = $(this).closest('tr');
                var targetedRow = currentRow.prevAll('.parent').first();
                var targetedCheckbox = targetedRow.find(':checkbox');
                targetedCheckbox.prop('checked', true).trigger('change');
            }
        });
    });
    */
</script>
<?php
});