<?php
/**
 * The template for displaying the footer
 *
 * Contains footer content and the closing of the #main and #page div elements.
 *
 * @package WordPress
 * @subpackage Twenty_Thirteen
 * @since Twenty Thirteen 1.0
 */
?>

		</div><!-- #main -->
		<footer id="colophon" class="site-footer" role="contentinfo">
			<?php  //get_sidebar( 'main' ); ?>
		
<div class="final-thoughts">
	<p class="description">Post Playhouse is Northwestern Nebraska's premiere venue for live theatre.  Located on <a href="http://www.ngpc.state.ne.us/parks/guides/parksearch/showpark.asp?Area_No=77" title="Nebraska Parks and Rec: Fort Robinson">Historic Fort Robinson State Park</a>, Post Playhouse produces several musicals running in a repertory schedule every summer by utilizing the talent of creative professionals from across the country and nearby.</p>

  <div class="sponsors">
    <div class="sponsor">
    	<a href="http://www.nebraskaartscouncil.org"><img src="<?php echo get_stylesheet_directory_uri();?>/images/na_logo.png" alt="NE Arts Council Logo" /></a>
        <p>The Nebraska Arts Council, a state agency, has supported the programs of this organization through its matching grants program funded by the Nebraska Legislature, the National Endowment for the Arts and the Nebraska Cultural Endowment.  Visit <a href="http://www.nebraskaartscouncil.org" target="_blank">www.nebraskaartscouncil.org</a> for informationon how the Nebraska Arts Council can assist your organization, or how you can support the Nebraska Cultural Endowment, at <a href="http://www.nebraskaculturalendowment.org" target="_blank">www.nebraskaculturalendowment.org</a>.</p>
     </div>
     
    <div class="sponsor">
        <a href="http://www.csc.edu" title="Chadron State College"><img src="<?php echo get_stylesheet_directory_uri();?>/images/csc_logo.png"></a>
        <p><a href="http://www.csc.edu" title="Chadron State College">Chadron State College</a>, located in Chadron, Nebraska, is a proud sponsor of the Post Playhouse. CSC routinely donates lighting equipment, sound equipment, and costumes to the theatre to help Post Playhouse bring you the very best looking and sounding shows you'll see in Western Nebraska.</p>
    </div>
        
    <div class="sponsor">
        <a href="http://www.ngpc.state.ne.us/parks/guides/parksearch/showpark.asp?Area_No=77" title="Nebraska Parks and Rec: Fort Robinson"><img src="<?php echo get_stylesheet_directory_uri();?>/images/NGPC.png"></a>
        <p><a href="http://www.ngpc.state.ne.us/parks/guides/parksearch/showpark.asp?Area_No=77" title="Nebraska Parks and Rec: Fort Robinson">Historic Fort Robinson State Park</a> is not only the home of the Post Playhouse, but it is also a beautiful and exciting getaway spot for countless individuals, couples, and families every summer.  The park offers many activities and an abundence of history to discover.</p>
    </div>     
	</div>	
</div><!-- final thoughts -->
			<div class="site-info">
				<?php // do_action( 'twentythirteen_credits' ); ?>
				<p>All Content &copy; <?php echo date('Y');?> Post Playhouse. This site is the result of a <a href="http://happycollision.com">Happy Collision</a>.</p>
			</div><!-- .site-info -->
		</footer><!-- #colophon -->
	</div><!-- #page -->

	<?php wp_footer(); ?>
</body>
</html>