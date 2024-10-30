<?php
/*
Plugin Name: Cool Portfolio
Description: Cool Portfolio to display a nice portfolio.
Tags: Cool Portfolio, Portfolio, image, fancybox, lightbox, popup box overlay,grid,gallery
Author URI: https://hoverboard.se
Author: Kjeld Hansen
Text Domain: cool-portfolio
Requires at least: 4.0
Tested up to: 4.4.2
Version: 1.0
*/

add_action( 'init', 'codex_coolportfolio_init' );
/**
 * Register a coolportfolio post type.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_post_type
 */
function codex_coolportfolio_init() {
	$labels = array(
		'name'               => _x( 'Portfolios', 'post type general name', 'cool-portfolio' ),
		'singular_name'      => _x( 'Portfolio', 'post type singular name', 'cool-portfolio' ),
		'menu_name'          => _x( 'Portfolios', 'admin menu', 'cool-portfolio' ),
		'name_admin_bar'     => _x( 'Portfolio', 'add new on admin bar', 'cool-portfolio' ),
		'add_new'            => _x( 'Add New', 'coolportfolio', 'cool-portfolio' ),
		'add_new_item'       => __( 'Add New Portfolio', 'cool-portfolio' ),
		'new_item'           => __( 'New Portfolio', 'cool-portfolio' ),
		'edit_item'          => __( 'Edit Portfolio', 'cool-portfolio' ),
		'view_item'          => __( 'View Portfolio', 'cool-portfolio' ),
		'all_items'          => __( 'All Portfolios', 'cool-portfolio' ),
		'search_items'       => __( 'Search Portfolios', 'cool-portfolio' ),
		'parent_item_colon'  => __( 'Parent Portfolios:', 'cool-portfolio' ),
		'not_found'          => __( 'No portfolios found.', 'cool-portfolio' ),
		'not_found_in_trash' => __( 'No portfolios found in Trash.', 'cool-portfolio' )
	);

	$args = array(
		'labels'             => $labels,
        'description'        => __( 'Description.', 'cool-portfolio' ),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'coolportfolio' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt' )
	);

	register_post_type( 'coolportfolio', $args );
}

// hook into the init action and call create_coolportfolio_taxonomies when it fires
add_action( 'init', 'create_coolportfolio_taxonomies', 0 );

// create two taxonomies, genres and writers for the post type "coolportfolio"
function create_coolportfolio_taxonomies() {
	// Add new taxonomy, make it hierarchical (like categories)
	$labels = array(
		'name'              => _x( 'Genres', 'taxonomy general name', 'cat-portfolio' ),
		'singular_name'     => _x( 'Genre', 'taxonomy singular name', 'cat-portfolio' ),
		'search_items'      => __( 'Search Genres', 'cat-portfolio' ),
		'all_items'         => __( 'All Genres', 'cat-portfolio' ),
		'parent_item'       => __( 'Parent Genre', 'cat-portfolio' ),
		'parent_item_colon' => __( 'Parent Genre:', 'cat-portfolio' ),
		'edit_item'         => __( 'Edit Genre', 'cat-portfolio' ),
		'update_item'       => __( 'Update Genre', 'cat-portfolio' ),
		'add_new_item'      => __( 'Add New Genre', 'cat-portfolio' ),
		'new_item_name'     => __( 'New Genre Name', 'cat-portfolio' ),
		'menu_name'         => __( 'Genre', 'cat-portfolio' ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'genre' ),
	);

	register_taxonomy( 'genre', array( 'coolportfolio' ), $args );

}


function cool_portfolio_scripts_js() {
    wp_enqueue_style( 'cool-portfolio', plugins_url( '/assets/cool-portfolio.css', __FILE__) );
    wp_enqueue_script( 'cool-portfolio', plugins_url( '/assets/cool-portfolio.js', __FILE__), array(), '1.0.0', true );
}
//add_action( 'wp_enqueue_scripts', 'cool_portfolio_scripts_js' );

function cool_portfolio_cpcall_func( $atts ) {
	cool_portfolio_scripts_js();

$args = array(
	'post_type' => 'coolportfolio',
	'orderby' => 'title menu_order',
	'order'   => 'DESC',
	'posts_per_page' => 40,
	/*'tax_query' => array(
		array(
			'taxonomy' => 'genre',
			'field'    => 'slug',
			'terms'    => 'bob',
		),
	),*/
);
// the query
$the_query = new WP_Query( $args ); ?>

<?php if ( $the_query->have_posts() ) : ?>
	<ul id="cool-port-1" class="cool-portfolio">
		<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
        <li>
        	<?php if(has_post_thumbnail()){ ?>
        		<a href="<?php the_post_thumbnail_url( 'large' ); ?>" class="cool-portfolio"><?php the_post_thumbnail('thumbnail'); ?>
                <h2><?php the_title(); ?></h2></a>
            <?php }else{ ?>
            <h2><?php the_title(); ?></h2> <?php } ?>
        </li>
        <?php endwhile; ?>
    <ul>
	<?php wp_reset_postdata(); ?>

<?php else : ?>
	<p><?php _e( 'Sorry, no posts matched your criteria.' ); ?></p>
<?php endif; 
	
}
add_shortcode( 'coolPortfolio', 'cool_portfolio_cpcall_func' );



