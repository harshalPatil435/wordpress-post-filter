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
