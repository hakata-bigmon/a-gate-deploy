<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_One
 * @since Twenty Twenty-One 1.0
 */

get_header();



/* Start the Loop */
while ( have_posts() ) :
	the_post();

	 $current_cat = get_queried_object();


	

	 if($current_cat->post_type == "cpt_confirmationmail")//確認メール
	 {
			//var_dump($current_cat);

?>

			<div class="confirmationmail_preview_area">

				<div class="confirmationmail_preview_title">
					<?php echo get_field('acf_ConfirmationMail_title',get_the_ID()); ?>
				</div>



				<?php 
				
					$post_contens = wpautop($current_cat->post_content);

	                 // 許可するHTML要素と属性を指定
	                $allowed_tags = wp_kses_allowed_html('post');
	                $allowed_tags['img'] = array(
		                'src' => true,
		                'alt' => true,
		                'title' => true,
		                'width' => true,
		                'height' => true,
	                );
				
				
				?>

				<div class="confirmationmail_preview_contens">

					<?php echo  wp_kses($post_contens, $allowed_tags);?>
				</div>

			</div>
<?php

	 }
	 else if($current_cat->post_type == "cpt_news")//ニュース
	 {
		require_once (dirname(__FILE__)."/class/spiritNewsClass.php");

		$newsClass = new SpiritNewsClass(); //管理データ

		// var_dump($current_cat);
		//自分が見れるかどうかの判定をする
		$post_id = $current_cat->ID;

		$no_post = false;//記事が見れるかどうか

		$traget_text = ""; //対象者
		
		$post_user = get_field('acf_news_all_post', $post_id);

		if($post_user == "all_users")
		{
			//全員なので見れる
			$traget_text = "全員";
			$no_post = true;

		}
		else if($post_user == "no_users" || $post_user == "")
		{
			$no_post = false;//指定されてないので見れない

			$traget_text = "対象者なし";
		}
		else if($post_user == "specific_users")
		{

			$member_array = $newsClass->getEnablePostUserID($post_id);//ユーザーのデータ取得

			if(!isset($member_array[get_current_user_id()]))
			{
				$no_post = false;//指定されてなかったら見れない
			}

			//対象者を表示用
			foreach ($member_array as $key => $value) {

				$traget_text .= $value . ",";

			}
		}

		//表示しない場合は見れない
		if(get_field('acf_news_disp', $post_id) == "")
		{
			$no_post = false;
		}

		//告知日を取得
		$disp_day = $newsClass->getPostDate($post_id);

		//告知日で見れるかどうの確認
		if(!$newsClass->isPostDate($post_id))
		{
			$no_post = false;
		}



		if(current_user_can('administrator') || current_user_can('Editor'))
		{
			//管理者は見れる
			$no_post = true;
		}
		
		if($no_post)
		{


?>

			<div class="confirmationmail_preview_title">
				<?php echo get_the_title($post_id); ?>
			</div>

			<?php 
			
			
				if(current_user_can('administrator') || current_user_can('Editor'))
				{
			?>
					<div>
						対象者:<?php echo $traget_text;?>
					</div>
			<?php
				}
			
				//確認モードではない時は既読チェック
				if(!isset($_GET["preview"])){
					$newsClass->setUnreadPost(get_current_user_id(),$post_id);
				}


				//全記事情報を取得
				$all_news = $newsClass->getNewsPostDateNoSort(get_current_user_id());
			
			?>

			<div class="confirmationmail_preview_area">

				<?php 
				
					$post_contens = wpautop($current_cat->post_content);

	                 // 許可するHTML要素と属性を指定
	                $allowed_tags = wp_kses_allowed_html('post');
	                $allowed_tags['img'] = array(
		                'src' => true,
		                'alt' => true,
		                'title' => true,
		                'width' => true,
		                'height' => true,
	                );
				
				
				?>

				<div class="confirmationmail_preview_contens">

					<?php echo  wp_kses($post_contens, $allowed_tags);?>
				</div>

				<div>
					日付:<?php echo $disp_day;?>
				</div>

			</div>

			<?php if(!isset($_GET["preview"])){ //確認モードではない?>

				<div>
					 <a href="<?php echo home_url(); ?>/users/user_top">会員ページに戻る</a>
				</div>
				<div>
					<a href="<?php echo home_url(); ?>/users/user-notice">お知らせ一覧に戻る</a>
				</div>


				<?php 
					if(isset($all_news[$post_id])){
					
						$keys = array_keys($all_news);

						$currentPosition = array_search($post_id, $keys);

						 // 前のキー（存在する場合）
						$prevKey = $currentPosition > 0 ? $keys[$currentPosition - 1] : null;
						// 次のキー（存在する場合）
						$nextKey = $currentPosition < count($keys) - 1 ? $keys[$currentPosition + 1] : null;

						if($prevKey !== null){

				?>
						<div>
							<a href="<?php echo home_url(); ?>/?post_type=cpt_news&p=<?php echo $prevKey;?>">次の記事へ</a>
						</div>
				<?php 
						}

						if($nextKey !== null){
				?>
						<div>
							<a href="<?php echo home_url(); ?>/?post_type=cpt_news&p=<?php echo $nextKey;?>">前の記事へ</a>
						</div>
				<?php 
						}

					}
				?>
			<?php } ?>

<?php
		}
		else{
?>

			この記事は見る事ができません

			<div>
				 <a href="<?php echo home_url(); ?>/users/user_top">会員ページに戻る</a>
			</div>

			<div>
				<a href="<?php echo home_url(); ?>/users/user-notice">お知らせ一覧に戻る</a>
			</div>
<?php
		}
?>


<?php
	 }else{
	
			/*get_template_part( 'template-parts/content/content-single' );

			if ( is_attachment() ) {
				// Parent post navigation.
				the_post_navigation(
					array(
					
						'prev_text' => sprintf( __( '<span class="meta-nav">Published in</span><span class="post-title">%s</span>', 'twentytwentyone' ), '%title' ),
					)
				);
			}

			// If comments are open or there is at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) {
				comments_template();
			}

			// Previous/next post navigation.
			$twentytwentyone_next = is_rtl() ? twenty_twenty_one_get_icon_svg( 'ui', 'arrow_left' ) : twenty_twenty_one_get_icon_svg( 'ui', 'arrow_right' );
			$twentytwentyone_prev = is_rtl() ? twenty_twenty_one_get_icon_svg( 'ui', 'arrow_right' ) : twenty_twenty_one_get_icon_svg( 'ui', 'arrow_left' );

			$twentytwentyone_next_label     = esc_html__( 'Next post', 'twentytwentyone' );
			$twentytwentyone_previous_label = esc_html__( 'Previous post', 'twentytwentyone' );

			the_post_navigation(
				array(
					'next_text' => '<p class="meta-nav">' . $twentytwentyone_next_label . $twentytwentyone_next . '</p><p class="post-title">%title</p>',
					'prev_text' => '<p class="meta-nav">' . $twentytwentyone_prev . $twentytwentyone_previous_label . '</p><p class="post-title">%title</p>',
				)
			);
			*/
	 }
endwhile; // End of the loop.

get_footer();
