<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_One
 * @since Twenty Twenty-One 1.0
 */

?>
			</main><!-- #main -->
		</div><!-- #primary -->
	</div><!-- #content -->

	<style>

.footer-area {

	

}

.footer-links {

	display: flex;

	justify-content: center;

	align-items: center;

	gap: 18px;

	margin-bottom: 12px;

	flex-wrap: wrap;

}

.footer-link {

	color: #fff;

	text-decoration: underline;

	font-weight: bold;

	font-size: 1.05em;

	transition: color 0.18s;

}

.footer-link:hover {

	color: #ffd180;

}

.footer-sep {

	color: #fff;

	font-size: 1.1em;

	margin: 0 8px;

}

.footer-text {

	font-size: 1em;

	margin-top: 8px;

	opacity: 0.95;

}

@media (max-width: 600px) {

	.footer-links { flex-direction: column; gap: 6px; }

	.footer-sep { display: none; }

}

</style>

		
	<footer id="colophon" class="">

		<?php if(!is_user_logged_in()){ ?>



			<div class="footer-area">

				<div class="footer-links">


					<a href="<?php echo home_url(); ?>/login" class="footer-link">ログイン</a>

					<span class="footer-sep">|</span>

					<a href="<?php echo home_url(); ?>/company-profile" class="footer-link">会社概要</a>

					<span class="footer-sep">|</span>

					<a href="<?php echo home_url(); ?>/privacy-policy" class="footer-link">個人情報保護方針</a>

					<span class="footer-sep">|</span>

					<a href="<?php echo home_url(); ?>/terms-of-service" class="footer-link">特定商取引法に関する表記</a>

				</div>

				<div class="footer-text">A-GATE 会員ページ　／　produced by. A-GATE</div>

			</div>

			
		<?php }else{  ?>

						
			<?php 

			wp_reset_postdata();//POSTをリセット

			if($post_type  != null){
				$footer_parent_id = $post->post_parent;  //属性を取得
			}
			else{
				$footer_parent_id = 0;
			}


			?>

			<?php if(is_page( "admin-arami-sheet-sprit-print" )){ ?>

			<?php }else if($footer_parent_id == 7525  || current_user_can('Subscriber') || is_user_logged_in()){ //ユーザーページはここで対応 ?>

				<div class="footer-area" style="margin-top: 0;">

					<div class="footer-links">


						<a href="<?php echo home_url(); ?>/users/user_top/" class="footer-link">TOP</a>

						<span class="footer-sep">|</span>

						<a href="<?php echo home_url(); ?>/company-profile" class="footer-link">会社概要</a>

						<span class="footer-sep">|</span>

						<a href="<?php echo home_url(); ?>/privacy-policy" class="footer-link">個人情報保護方針</a>

						<span class="footer-sep">|</span>

						<a href="<?php echo home_url(); ?>/terms-of-service" class="footer-link">特定商取引法に関する表記</a>

					</div>

					<div class="footer-text">A-GATE 会員ページ　／　produced by. A-GATE</div>

				</div>
			<?php }else{  ?>

			

			<?php }  ?>

			<?php }  ?>


		
	</footer><!-- #colophon -->

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
