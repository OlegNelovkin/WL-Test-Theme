<?php
/**
 * WL Test Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WL_Test_Theme
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function wl_test_theme_setup() {


	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
		add_theme_support( 'title-tag' );

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
		add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
		register_nav_menus(
			array(
				'menu-1' => esc_html__( 'Primary', 'wl-test-theme' ),
			)
		);

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			)
		);

	// Set up the WordPress core custom background feature.
		add_theme_support(
			'custom-background',
			apply_filters(
				'wl_test_theme_custom_background_args',
				array(
					'default-color' => 'ffffff',
					'default-image' => '',
				)
			)
		);

	// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}
add_action( 'after_setup_theme', 'wl_test_theme_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function wl_test_theme_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'wl_test_theme_content_width', 640 );
}
add_action( 'after_setup_theme', 'wl_test_theme_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function wl_test_theme_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'wl-test-theme' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'wl-test-theme' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'wl_test_theme_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function wl_test_theme_scripts() {
	wp_enqueue_style( 'wl-test-theme-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_style_add_data( 'wl-test-theme-style', 'rtl', 'replace' );


	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'wl_test_theme_scripts' );


/************************
 * Add post type Car
************************/

add_action('init', function() {
	register_post_type('Car', [
		'label' => __('Car', 'txtdomain'),
		'public' => true,
		'supports' => ['title', 'editor', 'thumbnail', 'author', 'revisions', 'comments'],
		'rewrite' => ['slug' => 'car'],
		'taxonomies' => ['car_country', 'car_brand', 'car_manufacturer'],		
	]);


	register_taxonomy_for_object_type('car_country', 'car');

	register_taxonomy('car_brand', ['car'], [
		'label' => __('Марка', 'txtdomain'),
		'hierarchical' => true,
		'rewrite' => ['slug' => 'car_brand'],
		'show_admin_column' => true,
		'show_in_rest'         => true,
		'labels' => [
			'singular_name' => __('Марка', 'txtdomain'),
		]
	]);
	register_taxonomy_for_object_type('car_brand', 'car');

	register_taxonomy('car_country', ['car'], [
		'label' => __('Страна производитель', 'txtdomain'),
		'hierarchical' => true,
		'show_in_rest'         => true,
		'rewrite' => ['slug' => 'car_brand'],
		'show_admin_column' => true,
		'labels' => [
			'singular_name' => __('Производитель', 'txtdomain'),
		]
	]);
	register_taxonomy_for_object_type('car_country', 'car');


});


/************************
 * Add phone to customizer
************************/

function customize_register_action( $wp_customize ) {
	$a = $wp_customize;
	$wp_customize->add_setting(
		'title_tagline_phone',
		array(
			'default' => '+7 (777) 777-77-77',
		)
	);
	$wp_customize->add_control(
		'title_tagline_phone',
		array(
			'label'   => 'Телефон',
			'section' => 'title_tagline',
			'type'    => 'text',
		)
	);
}
add_action( 'customize_register', 'customize_register_action' );



/************************
 * Add meta boxes to car posts
************************/

class ColorMetabox {

	private $screens = array('car');

	private $fields = array(
		array(
			'label' => 'Цвет',
			'id' => 'car-color',
			'type' => 'color',
			'default' => '#ffffff'
		),
		array(
			'label' => 'Топливо',
			'id' => 'car-fuel',
			'type' => 'select',
			'options' => array(
				'Дизель',
				'Бензин',
				'Газ',
			),
		),
		array(
			'label' => 'Мощность',
			'id' => 'car-power',
			'type' => 'number',
		),
		array(
			'label' => 'Цена',
			'id' => 'car-price',
			'type' => 'number',
		)  
	);

	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save_fields' ) );
	}

	public function add_meta_boxes() {
		foreach ( $this->screens as $s ) {
			add_meta_box(
				'Характеристики',
				__( 'Характеристики', 'textdomain' ),
				array( $this, 'meta_box_callback' ),
				$s,
				'normal',
				'default'
			);
		}
	}

	public function meta_box_callback( $post ) {
		wp_nonce_field( 'Color_data', 'Color_nonce' ); 
		$this->field_generator( $post );
	}

	public function field_generator( $post ) {
		$output = '';
		foreach ( $this->fields as $field ) {
			$label = '<label for="' . $field['id'] . '">' . $field['label'] . '</label>';
			$meta_value = get_post_meta( $post->ID, $field['id'], true );
			if ( empty( $meta_value ) ) {
				if ( isset( $field['default'] ) ) {
					$meta_value = $field['default'];
				}
			}
			switch ( $field['type'] ) {
				case 'select':
				$input = sprintf(
					'<select id="%s" name="%s">',
					$field['id'],
					$field['id']
				);
				foreach ( $field['options'] as $key => $value ) {
					$field_value = !is_numeric( $key ) ? $key : $value;
					$input .= sprintf(
						'<option %s value="%s">%s</option>',
						$meta_value === $field_value ? 'selected' : '',
						$field_value,
						$value
					);
				}
				$input .= '</select>';
				break;

				default:
				$input = sprintf(
					'<input %s id="%s" name="%s" type="%s" value="%s">',
					$field['type'] !== 'color' ? 'style="width: 100%"' : '',
					$field['id'],
					$field['id'],
					$field['type'],
					$meta_value
				);
			}
			$output .= $this->format_rows( $label, $input );
		}
		echo '<table class="form-table"><tbody>' . $output . '</tbody></table>';
	}

	public function format_rows( $label, $input ) {
		return '<div style="margin-top: 10px;"><strong>'.$label.'</strong></div><div>'.$input.'</div>';
	}



	public function save_fields( $post_id ) {
		if ( !isset( $_POST['Color_nonce'] ) ) {
			return $post_id;
		}
		$nonce = $_POST['Color_nonce'];
		if ( !wp_verify_nonce( $nonce, 'Color_data' ) ) {
			return $post_id;
		}
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}
		foreach ( $this->fields as $field ) {
			if ( isset( $_POST[ $field['id'] ] ) ) {
				switch ( $field['type'] ) {
					case 'email':
					$_POST[ $field['id'] ] = sanitize_email( $_POST[ $field['id'] ] );
					break;
					case 'text':
					$_POST[ $field['id'] ] = sanitize_text_field( $_POST[ $field['id'] ] );
					break;
				}
				update_post_meta( $post_id, $field['id'], $_POST[ $field['id'] ] );
			} else if ( $field['type'] === 'checkbox' ) {
				update_post_meta( $post_id, $field['id'], '0' );
			}
		}
	}

}

if (class_exists('ColorMetabox')) {
	new ColorMetabox;
};



/************************
 * Create Shortcode to Display Car Post Type
************************/


function create_shortcode_car_post_type(){

	$args = array(
		'post_type'      => 'car',
		'posts_per_page' => '10',
		'publish_status' => 'published',
	);

	$query = new WP_Query($args);

	if($query->have_posts()) :

		while($query->have_posts()) :

			$query->the_post() ;

			?>
			<div class="cars-item">
				<a href="<?php the_permalink(); ?>">

					<?php echo get_the_post_thumbnail(); ?>
					<?php the_title(); ?>
				</a>
			</div>
		<?php endwhile;

		wp_reset_postdata();

	endif;    

	return;            
}

add_shortcode( 'car-list', 'create_shortcode_car_post_type' ); 

