<?php
/**
 * The main template file for display portfolio page.
 *
 * Template Name: Projetos
 * @package WordPress
 */

/**
*	Get Current page object
**//*
$page = get_page($post->ID);
$current_page_id = '';

if(isset($page->ID)){
    $current_page_id = $post->ID;
}*/

//Check if password protected
$portfolio_password = get_post_meta($current_page_id, 'portfolio_password', true);
if(!empty($portfolio_password)){
	session_start();
	if(!isset($_SESSION['gallery_page_'.$current_page_id]) OR empty($_SESSION['gallery_page_'.$current_page_id])){
		get_template_part("/templates/template-password");
		exit;
	}
}

//Get global gallery sorting
$pp_orderby = 'menu_order';
$pp_order = 'ASC';
$pp_gallery_sort = get_option('pp_gallery_sort');


if(!empty($pp_gallery_sort)){
	switch($pp_gallery_sort)	{
		case 'post_date':
			$pp_orderby = 'post_date';
			$pp_order = 'DESC';
		break;
		
		case 'post_date_old':
			$pp_orderby = 'post_date';
			$pp_order = 'ASC';
		break;
		
		case 'rand':
			$pp_orderby = 'rand';
			$pp_order = 'ASC';
		break;
		
		case 'title':
			$pp_orderby = 'title';
			$pp_order = 'ASC';
		break;
	}
}?>

<?php get_header();?>
    <!-- Begin content -->
<link rel="stylesheet" type="text/css" href="http://sorgalla.com/jcarousel/examples/_shared/css/style.css">
<link rel="stylesheet" type="text/css" href="http://sorgalla.com/jcarousel/examples/responsive/jcarousel.responsive.css">
<script type="text/javascript" src="http://sorgalla.com/jcarousel/libs/jquery/jquery.js"></script>
<script type="text/javascript" src="http://sorgalla.com/jcarousel/dist/jquery.jcarousel.min.js"></script>
<script type="text/javascript" src="http://sorgalla.com/jcarousel/examples/responsive/jcarousel.responsive.js"></script>


<br class="clear"/>
</div>
<div id="page_content_wrapper" class="fade-in two">
    
    <div class="inner">
    	<div class="inner_wrapper">
    	<div id="page_caption">
    		<h1 class="cufon"><?php echo $post->post_title; ?></h1>
            <div class="bg-page-caption-title"></div>
    	</div>
        <div id="page_main_content" class="sidebar_content full_width transparentbg scroll-pane">
    		
    		<?php
			    //Get social media sharing option
			    $pp_social_sharing = get_option('pp_social_sharing');
			    
			    if(!empty($pp_social_sharing)){
			?>
			<!-- AddThis Button BEGIN -->
			<div class="addthis_toolbox addthis_default_style addthis_32x32_style">
                <a class="addthis_button_preferred_1"></a>
                <a class="addthis_button_preferred_2"></a>
                <a class="addthis_button_preferred_3"></a>
                <a class="addthis_button_preferred_4"></a>
                <a class="addthis_button_compact"></a>
                <a class="addthis_counter addthis_bubble_style"></a>
			</div>
			<script type="text/javascript">var addthis_config = {"data_track_addressbar":true};</script>
			<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ppulpipatnan"></script>
			<!-- AddThis Button END -->
			<br class="clear"/>
            
			<?php
			    }
			?>   
<?php
    $args = array(
        'orderby'       => 'name',
        'order'         => 'ASC',
        'hide_empty'    => 1,
        'taxonomy'      => 'category', //change this to any taxonomy
    );
   
    foreach (get_categories($args) as $tax){
        $args = array(
            'post_type'         => 'gallery', //change to your post_type
            'posts_per_page'    => -1,
            'orderby'           => 'title',
            'orderby'           => 'ASC',
			'tag'               => 'projeto',
            'tax_query' => array(
                array(
                    'taxonomy'  => 'category', //change this to any taxonomy
                    'field'     => 'term_id',
					'terms'     => $tax->term_id
                )
            )
        );
      	$project_id = get_posts( $args);
		foreach($project_id as $project_only){
		//Get content gallery
		//$current_page_id = $project_only->ID;
		$gallery_id = $project_only->ID; //get_post_meta($p->ID, 'page_gallery_id', true);//1150;//		
		$args = array( 
			'post_type' => 'attachment', 
			'numberposts' => -1, 
			'post_status' => null,
			'post_parent' => $gallery_id,
			'order' => $pp_order,
		); 
		//Get gallery images
		$all_photo_arr = get_posts( $args);
?>
<?php
//Get Page background style
$bg_style = get_post_meta($current_page_id, 'page_bg_style', true);

if($bg_style == 'Static Image'){
    if(has_post_thumbnail($current_page_id, 'full'))    {
        $image_id = get_post_thumbnail_id($current_page_id); 
        $image_thumb = wp_get_attachment_image_src($image_id, 'full', true);
        $pp_page_bg = $image_thumb[0];
    	}    else    {
    	$pp_page_bg = get_template_directory_uri().'/example/bg.jpg';
    	}
		wp_enqueue_script("script-static-bg", get_template_directory_uri()."/templates/script-static-bg.php?bg_url=".$pp_page_bg, false, THEMEVERSION, true);
	} // end if static image
	else	{
    $page_bg_gallery_id = get_post_meta($current_page_id, 'page_bg_gallery_id', true);
    wp_enqueue_script("script-supersized-gallery", get_template_directory_uri()."/templates/script-supersized-projetos.php?gallery_id=".$page_bg_gallery_id, false, THEMEVERSION, true);
	}
?>

<?php
	//Display main gallery contents
    if(!empty($all_photo_arr)){
		if($bg_style == 'Static Image'){
?>
    <div class="page_control_static">
        <a id="page_minimize" href="#">
            <img src="<?php echo get_template_directory_uri(); ?>/images/icon_zoom.png" alt=""/>
        </a>
        <a id="page_maximize" href="#">
            <img src="<?php echo get_template_directory_uri(); ?>/images/icon_plus.png" alt=""/>
        </a>
    </div>
    <?php
	}else{
?>
    <div class="page_control">
        <a id="page_minimize" href="#">
            <img src="<?php echo get_template_directory_uri(); ?>/images/icon_minus.png" alt=""/>
        </a>
        <a id="page_maximize" href="#">
            <img src="<?php echo get_template_directory_uri(); ?>/images/icon_plus.png" alt=""/>
        </a>
    </div>
    <div class="jcarousel-wrapper">
            <?php echo $project_only->post_title ?>
			<div class="jcarousel"> 
            <ul>  
<?php
	}
	$page_audio = get_post_meta($current_page_id, 'page_audio', true);
	if(!empty($page_audio)){
?>
    <div class="page_audio">
        <?php echo do_shortcode('[audio width="30" height="30" src="'.$page_audio.'"]'); ?>
    </div>
<?php
	}
	if(!empty($post->post_content)){
		echo pp_apply_content($post->post_content);
	}
	$pp_portfolio_enable_slideshow_title = get_option('pp_portfolio_enable_slideshow_title');
	
	foreach($all_photo_arr as $key => $photo){ // <!--/start foreach all_photo_arr -->
		$small_image_url = get_template_directory_uri().'/images/000_70.png';
		$hyperlink_url = get_permalink($photo->ID);
		
		if(!empty($photo->guid)){
			$image_url[0] = $photo->guid;
			$small_image_url = wp_get_attachment_image_src($photo->ID, 'gallery_4', true);
		}
		
		$last_class = '';
		if(($key+1)%4==0){
			$last_class = 'active';
		}
		?>
            <li><!--class="item <?php //echo $last_class; ?>"-->
				<?php if(!empty($small_image_url)){?>
                        <a <?php if(!empty($pp_portfolio_enable_slideshow_title)) { ?>data-title="<?php echo $photo->post_title; ?> <?php if(!empty($photo->post_content)) { ?>- <?php echo $photo->post_content; ?><?php } ?>"<?php } ?> class="fancy-gallery lightbox_youtube" data-fancybox-group="fancybox-thumb" href="<?php echo $image_url[0]; ?>">
                        <img src="<?php echo $small_image_url[0]; ?>" alt="Image" style="max-width:100%;"/>
							<div class="mask">
								<?php /* REMOVE TITLE
								if(!empty($pp_portfolio_enable_slideshow_title)) { ?>
									<h6><?php echo $photo->post_title; ?></h6>
									<span class="caption"><?php echo $photo->post_excerpt; ?></span>
								<?php }*/ ?>
							</div>
						</a>
				<?php } ?>
                </li><!--/end item -->
                <?php
				} 	// <!--/end foreach all_photo_arr -->
				?>
                </ul>
                </div><!--/end carousel_container -->
                <a href="#" class="jcarousel-control-prev">&lsaquo;</a>
                <a href="#" class="jcarousel-control-next">&rsaquo;</a>

                <p class="jcarousel-pagination"></p>
				</div><!--/end well -->
			<?php
			}
    	}
    	?>
        </div><!--/end scroll -->
    	</div><!--/end wapprer -->
    </div><!--/end inner -->
</div>

<?php get_footer(); ?>

<?php
    }
?>