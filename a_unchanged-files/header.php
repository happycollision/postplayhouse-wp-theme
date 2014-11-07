<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="Description" content="Post Playhouse is Northwestern Nebraska's premiere venue for live theatre.  Located on Historic Fort Robinson State Park, Post Playhouse produces several musicals running in a repertory schedule every summer by utilizing the talent of creative professionals from across the country and nearby.">
<title><?php wp_title( '|', true, 'right' );?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<link rel="shortcut icon" href="<?php bloginfo('template_url');?>/favicon.ico" />

<?php wp_head();?>
</head>

<body>

<div id="mainContainer">


    

    <div id="contentContainer">

<!--[if lte IE 7]>
<style type="text/css">
#header{
	display:none;
}
#iesux{
	display:block;
    width:776px;
    margin:auto;
}
</style>
<![endif]-->
<!--[if lt IE 7]>
<style type="text/css">
    #top_decoration{
    	display:none;
    }
    #bottom_decoration{
    	display:none;
    }
</style>
<![endif]-->
<div id="iesux">
	<a href="<?php bloginfo('url');?>"><img src="<?php bloginfo('template_url');?>/images/nameplate_page.jpg" /></a>
</div><!--iesux-->
    <?php if(is_front_page()){?>
    <div id="header" style="margin:12px 0;">
        <a href="<?php bloginfo('url');?>">
        <img id="nameplate" src="<?php bloginfo('template_url');?>/images/nameplate.png" alt="Post Playhouse" style="float:left;"/></a>
        <?php include 'gallery.php';?>
        <div class="anchor"></div>
    <?php }else{ ?>
    <div id="header" style="margin-bottom:0;height:120px; overflow:hidden;">
        <a href="<?php bloginfo('url');?>">
        <img src="<?php bloginfo('template_url');?>/images/nameplate_page.png" alt="Post Playhouse" id="nameplate_page" /></a>
    <?php } ?>
    </div><!--header-->

    	<?php //wp_nav_menu(); ?>
        <?php get_sidebar(); ?>
		<?php //wp_nav_menu( array( 'container_class' => 'menu-header', 'theme_location' => 'primary' ) ); ?>
        <div id="content">
        	<div id="top_decoration">
            	<div class="left_wing"></div>
                <div class="right_wing"></div>
            </div>
        	<div id="bottom_decoration">
            	<div class="left_wing"></div>
                <div class="right_wing"></div>
            </div>
            <div id="decoration_fixer">
