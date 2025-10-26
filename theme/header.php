<?php
/**
 * The header.
 *
 * This is the template that displays all of the <head> section and everything up until main.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_One
 * @since Twenty Twenty-One 1.0
 */

 //require_once (dirname(__FILE__)."/custompage/a-gate-function-modals.php");
?>
<!doctype html>
<html <?php language_attributes(); ?> <?php twentytwentyone_the_html_classes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/assets/js/spirit-utility.js?<?php echo date('Ymd H:i:s'); ?>"></script>
	<link href="http://fonts.googleapis.com/earlyaccess/notosansjp.css">
	<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/custompage/admin/hakata_style.css?<?php echo date('Ymd H:i:s'); ?>">
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/custompage/admin/okada_style.css?<?php echo date('Ymd H:i:s'); ?>">
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/css/admin.css?<?php echo date('Ymd H:i:s'); ?>">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<?php wp_head(); ?>
	<?php $is_user_menu = (strpos($_SERVER['REQUEST_URI'], '/users/') !== false); ?>
	<?php if ($is_user_menu): ?>
	  <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/css/user-sidebar.css?<?php echo date('YmdHis'); ?>">
	  <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/css/user-page.css?<?php echo date('YmdHis'); ?>">
	  <script src="<?php echo get_template_directory_uri(); ?>/assets/js/user-sidebar.js?<?php echo date('YmdHis'); ?>" defer></script>
	<?php endif; ?>
</head>


<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
	
	<?php if (is_user_logged_in() &&  !is_page( "admin-news-postmember" ) &&  !is_page( "admin-preview" ) &&  !is_page( "order-form" ) &&  !is_page( "thanks" )&&  !is_page( "img_upload" )) {?>

		<?php 
			wp_reset_postdata();//POSTをリセット
			$post_type =  get_post_type(); //属性を取得

			if($post_type  != null){
				$parent_id = $post->post_parent; 
			}
			else{
				$parent_id = 0;
			}

			//会員情報申請中のユーザーを取得
			$membership_information_users = get_users(array(
				'meta_query' => array(
					array(
						'key' => 'user_date_complete',
						'value' => '1',
					)
				)
			));

			$membership_information_users_count = count($membership_information_users);

			//お問い合わせで未回答の質問を取得
			require_once (dirname(__FILE__)."/class/spiritContensQuestionClass.php");
			$spiritContensQuestion = new SpiritContensQuestionClass();
			$contacts_unanswered_question = $spiritContensQuestion->getContactsUnansweredQuestion();
			$contacts_unanswered_question_count = count($contacts_unanswered_question);
		?>
		
		<?php if($parent_id == 7525 || current_user_can('Subscriber')){ //ユーザーページはここで対応(基本的には会員はこちら) ?>
			<script>
			// ユーザーページではメニューハイライトをクリア
			if (sessionStorage.getItem('activeMenuId')) {
				sessionStorage.removeItem('activeMenuId');
			}
			</script>

		<?php }else if(is_page( "admin-explanation-receipt" ) || is_page( "admin-explanation-invoice" )|| is_page( "company-profile" )|| is_page( "privacy-policy" )|| is_page( "terms-of-service" )|| is_page( "admin-arami-sheet-sprit-print" )){ //請求書・領収書 ?>
			<script>
			// メニューが表示されないページではハイライトをクリア
			if (sessionStorage.getItem('activeMenuId')) {
				sessionStorage.removeItem('activeMenuId');
			}
			</script>

		<?php }else{ ?>
			<?php if ( (is_page("admin-arami-sheet-edit") && !isset($_POST["arami_check_sheet_id"])) ||  (!is_page("admin-arami-sheet-edit") ) ) { //印刷の所は表示しない?>

				<div class=""></div>

				<!-- サイドバー型ナビゲーション -->
				<button class="admin-header-sidebar-toggle-btn" id="sidebarToggleBtn" aria-label="メニュー" <?php if(current_user_can('administrator')){?>style="margin-top: 20px;"<?php }?>>
				  <span class="material-icons">menu</span>
				</button>
				<nav class="admin-header-sidebar-nav" id="sidebarNav">
				  <div class="admin-header-sidebar-header"></div>
				  <ul>
				    <li><a href="<?php echo home_url();?>/admin-menu" data-menu-id="admin-menu"><span class="material-icons">menu</span>メニューTOP</a></li>
				    <li><a href="<?php echo home_url();?>/admin-member-list" data-menu-id="admin-member-list"><span class="material-icons">people</span>顧客管理</a></li>

				<?php if(current_user_can('administrator')){?>
					<li>
						<a href="<?php echo home_url();?>/admin-membership-information" data-menu-id="admin-membership-information" style="position: relative;">
							<span class="material-icons">how_to_reg</span>会員情報申請
							<?php if($membership_information_users_count > 0){?>
								<span class="user-badge" style="position: absolute; top: 7px; right: 28px; background: #ff4444; color: white; border-radius: 50%; width: 20px; height: 20px; display: inline-flex; align-items: center; justify-content: center; font-size: 12px; font-weight: bold;"><?php echo $membership_information_users_count; ?></span>
							<?php }?>
						</a>
					</li>
				<?php } ?>

				    <li><a href="<?php echo home_url();?>/admin-spirit-sheets-list?category_type=1" data-menu-id="admin-spirit-sheets-list-1"><span class="material-icons">list_alt</span>浄霊・施術管理</a></li>

					<li><a href="<?php echo home_url();?>/admin-spirit-sheets-list?category_type=2" data-menu-id="admin-spirit-sheets-list-2"><span class="material-icons">list_alt</span>鑑定管理</a></li>

					<li><a href="<?php echo home_url();?>/admin-spirit-sheets-list?category_type=4" data-menu-id="admin-spirit-sheets-list-4"><span class="material-icons">list_alt</span>物販管理</a></li>

					<li><a href="<?php echo home_url();?>/admin-spirit-sheets-list?category_type=3" data-menu-id="admin-spirit-sheets-list-3"><span class="material-icons">list_alt</span>日程施術管理</a></li>

					<li><a href="<?php echo home_url();?>/admin-spirit-sheets-list?category_type=6" data-menu-id="admin-spirit-sheets-list-6"><span class="material-icons">list_alt</span>ヒーリング管理</a></li>


					<li><a href="<?php echo home_url();?>/admin-spirit-sheets-list?teacher_check=true" data-menu-id="admin-spirit-sheets-list-teacher"><span class="material-icons">list_alt</span>先生依頼管理</a></li>


					<?php if(current_user_can('administrator')){?>
						<li><a href="<?php echo home_url();?>/admin-sprit-make-menu" data-menu-id="admin-sprit-make-menu"><span class="material-icons">event</span>施術管理者申込</a></li>
						<li><a href="<?php echo home_url();?>/admin-spirit-schedule-list/?group=3" data-menu-id="admin-spirit-schedule-list"><span class="material-icons">event</span>スケジュール作成</a></li>
					<?php }else{ ?>
						<li><a href="<?php echo home_url();?>/admin-spirit-schedule-list/?group=3" data-menu-id="admin-spirit-schedule-list"><span class="material-icons">event</span>スケジュール作成</a></li>
					<?php } ?>

					<?php if(current_user_can('administrator')){?>
						<li><a href="<?php echo home_url();?>/admin-schedule-menu" data-menu-id="admin-schedule-menu"><span class="material-icons">event</span>電話相談管理</a></li>
					<?php } ?>
						
					<?php if(!current_user_can('administrator')){?>
						<li><a href="<?php echo home_url();?>/admin-explanation-invoice-list" data-menu-id="admin-explanation-invoice-list"><span class="material-icons">receipt_long</span>請求書・領収書</a></li>
					<?php } ?>


					<?php if(current_user_can('administrator')){?>
						<li><a href="<?php echo home_url();?>/admin-arami-sheet-list" data-menu-id="admin-arami-sheet-list"><span class="material-icons">list_alt</span>粗見シート</a></li>
					<?php } ?>

					<?php if(current_user_can('administrator')){?>
						<li><a href="<?php echo home_url();?>/admin-staff-menu" data-menu-id="admin-staff-menu"><span class="material-icons">groups</span>スタッフ管理</a></li>
						<li><a href="<?php echo home_url();?>/admin_news_menu" data-menu-id="admin-news-menu"><span class="material-icons">campaign</span>お知らせ管理</a></li>
						<li>
						
							<a href="<?php echo home_url();?>/admin-contacts-list" data-menu-id="admin-contacts-list" style="position: relative;">
								<span class="material-icons">mail</span>お問い合わせ
								<?php if($contacts_unanswered_question_count > 0){?>
									<span class="user-badge" style="position: absolute; top: 7px; right: 18px; background: #ff4444; color: white; border-radius: 50%; width: 20px; height: 20px; display: inline-flex; align-items: center; justify-content: center; font-size: 12px; font-weight: bold;"><?php echo $contacts_unanswered_question_count; ?></span>
								<?php }?>
							</a>
						
						</li>
					
					
					</li>

					<?php } ?>
				    <?php if(current_user_can('administrator')){?>
				      <li><a href="<?php echo home_url();?>/admin-profile-menu" data-menu-id="admin-profile-menu"><span class="material-icons">settings</span>設定</a></li>
				    <?php } ?>


					
					<li><a href="<?php echo wp_logout_url( home_url() ); ?>" data-menu-id="logout"><span class="material-icons">logout</span>ログアウト</a></li>
				  </ul>
				  <div class="admin-header-sidebar-footer">© A-Gate. 2024 All Rights Reserved.</div>
				</nav>

				<!-- JavaScriptを外部ファイルに移動することを推奨 -->
				<script>
				// サイドバーメニューの制御（改善版）
				(function() {
					'use strict';
					
					// 既に初期化済みかチェック
					if (window.sidebarMenuInitialized) {
						return;
					}
					
					const sidebar = document.getElementById('sidebarNav');
					const toggleBtn = document.getElementById('sidebarToggleBtn');
					
					// 要素が存在しない場合は何もしない
					if (!sidebar || !toggleBtn) {
						return;
					}
					
					// オーバーレイ要素を作成
					let overlay = document.querySelector('.admin-header-sidebar-overlay');
					if (!overlay) {
						overlay = document.createElement('div');
						overlay.className = 'admin-header-sidebar-overlay';
						document.body.appendChild(overlay);
					}
					
					// サイドバー制御オブジェクト
					const SidebarMenu = {
						isOpen: true, // デフォルトは開いた状態
						
						init: function() {
						// 画面サイズをチェック
						const isMobile = window.innerWidth <= 900;
						
						if (isMobile) {
							// モバイルサイズの場合、常に閉じた状態で開始
							this.close();
						} else {
							// デスクトップサイズの場合、sessionStorageから前回の状態を取得
							const savedState = sessionStorage.getItem('sidebarMenuState');
							const shouldBeOpen = savedState !== null ? savedState === 'open' : true;
							
							if (shouldBeOpen) {
							this.open();
							} else {
							this.close();
							}
						}
						},
						
						open: function() {
						sidebar.classList.remove('closed');
						document.body.classList.add('admin-header-sidebar-open');
						this.isOpen = true;
						
						// デスクトップサイズの場合のみsessionStorageに保存
						if (window.innerWidth > 900) {
							sessionStorage.setItem('sidebarMenuState', 'open');
						}
						},
						
						close: function() {
						sidebar.classList.add('closed');
						document.body.classList.remove('admin-header-sidebar-open');
						this.isOpen = false;
						
						// デスクトップサイズの場合のみsessionStorageに保存
						if (window.innerWidth > 900) {
							sessionStorage.setItem('sidebarMenuState', 'closed');
						}
						},
						
						toggle: function() {
						if (this.isOpen) {
							this.close();
						} else {
							this.open();
						}
						}
					};
					
					// 初期化実行
					SidebarMenu.init();
					
					// トグルボタンのクリックイベント
					toggleBtn.addEventListener('click', function(e) {
						e.preventDefault();
						e.stopPropagation();
						SidebarMenu.toggle();
					});
					
					// オーバーレイクリックで閉じる（モバイルのみ）
					overlay.addEventListener('click', function() {
						if (window.innerWidth <= 900) {
						SidebarMenu.close();
						}
					});
					
					// リサイズ時の対応
					window.addEventListener('resize', function() {
						const isMobile = window.innerWidth <= 900;
						
						if (isMobile && SidebarMenu.isOpen) {
						// モバイルサイズになった時は閉じる
						SidebarMenu.close();
						} else if (!isMobile) {
						// デスクトップサイズに戻った時は保存された状態を復元
						const savedState = sessionStorage.getItem('sidebarMenuState');
						const shouldBeOpen = savedState !== null ? savedState === 'open' : true;
						
						if (shouldBeOpen && !SidebarMenu.isOpen) {
							SidebarMenu.open();
						} else if (!shouldBeOpen && SidebarMenu.isOpen) {
							SidebarMenu.close();
						}
						}
					});
					
					// 初期化完了フラグ
					window.sidebarMenuInitialized = true;
					
				})();

				// メニュー項目のハイライト機能
				(function() {
					'use strict';
					
					const sidebarNav = document.getElementById('sidebarNav');
					if (!sidebarNav) return;
					
					const menuLinks = sidebarNav.querySelectorAll('a[data-menu-id]');
					
					// ページ読み込み時：保存されたメニューIDを取得してハイライト
					function highlightActiveMenu() {
						const activeMenuId = sessionStorage.getItem('activeMenuId');
						
						if (activeMenuId) {
							menuLinks.forEach(link => {
								if (link.getAttribute('data-menu-id') === activeMenuId) {
									link.classList.add('active-menu');
								} else {
									link.classList.remove('active-menu');
								}
							});
						}
					}
					
					// 初期表示時にハイライトを適用
					highlightActiveMenu();
					
					// メニュー項目クリック時：クリックされたIDを保存
					menuLinks.forEach(link => {
						link.addEventListener('click', function() {
							const menuId = this.getAttribute('data-menu-id');
							
							// ログアウトの場合はsessionStorageをクリア
							if (menuId === 'logout') {
								sessionStorage.removeItem('activeMenuId');
							} else {
								sessionStorage.setItem('activeMenuId', menuId);
							}
							
							// すぐに見た目を更新
							menuLinks.forEach(l => l.classList.remove('active-menu'));
							this.classList.add('active-menu');
						});
					});
					
				})();
				</script>
				
				<style>
				/* アクティブメニューのスタイル */
				.admin-header-sidebar-nav a.active-menu {
					background-color: #3cb3db;
					border-left: 4px solid #007cba;
					padding-left: calc(20px - 4px);
					color: white;
				}
				
				.admin-header-sidebar-nav a.active-menu .material-icons {
					color: white;
				}
				</style>
			<?php } ?>

			<script>
				function toggleMenu() {
					const navMenu = document.getElementById('navMenu');
					const hamburger = document.querySelector('.hamburger-menu');

					if (navMenu) navMenu.classList.toggle('open');
					if (hamburger) hamburger.classList.toggle('open');
				}
			</script>

		<?php } ?>
	<?php } ?>

	<?php $is_user_menu = (strpos($_SERVER['REQUEST_URI'], '/users/') !== false); ?>
	<?php if ($is_user_menu): ?>
		<?php $admin_check_user = ""; ?>
		<?php $admin_check_user_id = get_current_user_id(); ?>
		<?php if(current_user_can('administrator') && isset($_GET["check_user"])){?>
			<?php $admin_check_user = "?check_user=" .$_GET["check_user"]; ?>
			<?php $admin_check_user_id = $_GET["check_user"]; ?>
		<?php }?>

	  <div class="user-layout">
	  	<aside id="userSidebar" class="user-sidebar open">  <!-- closedからopenに変更 -->
	      <div class="user-sidebar-inner">
	        <div class="user-sidebar-header">
	          	<div class="user-logo">A-GATE 会員ページ</div>
	          	<div class="user-lang"><span class="material-icons">language</span> 日本語 ▼</div>
			  	<div class="user-menu-line"></div>
	          	<div class="user-id">ID：<?php echo get_user_meta($admin_check_user_id, 'user_unique_id', true);; ?></div>
	          	<div class="user-name">
					<?php echo get_user_meta($admin_check_user_id,'last_name',true); ?><?php echo get_user_meta($admin_check_user_id,'first_name',true); ?>さん
			  	</div>
	          	<div class="user-point"><span class="material-icons">monetization_on</span> 10,000,000pt</div>
	          	<div class="user-notify">
					<?php 
						require_once (dirname(__FILE__)."/class/spiritNewsClass.php");
						$spiritNews = new SpiritNewsClass();
						$news_data = $spiritNews->getNewsPostDate($admin_check_user_id);
						$cart_count = get_cart_item_count($admin_check_user_id);
					?>
					<a href="<?php echo home_url();?>/users/user-notice<?php echo $admin_check_user; ?>" class="user-notify-btn">
						<span class="material-icons">notifications</span>お知らせ
						<?php if($news_data["unread"] > 0){?>
							<span class="user-badge"><?php echo $news_data["unread"]; ?></span>
						<?php }?>
					</a>

					<a href="<?php echo home_url();?>/users/user-store-cart<?php echo $admin_check_user; ?>" class="user-notify-btn" style="margin-top: 30px;gap: 7px;">
						<span class="material-icons">shopping_cart</span>カート　
						<?php if($cart_count > 0){?>
							<span class="user-badge"><?php echo $cart_count; ?></span>
						<?php }?>
					</a>
				</div>
				
	        <nav class="user-menu">
	          <ul>
				<?php if(current_user_can('administrator')){?>
					<li><a href="<?php echo home_url();?>/admin-menu"><span class="material-icons">menu</span>メニューTOP</a></li>
				<?php }?>
	            <li><a href="<?php echo home_url();?>/users/user_top/<?php echo $admin_check_user; ?>" class="">TOP</a></li>
	            <li><a href="<?php echo home_url();?>/users/user-in-progress-spirit-list/<?php echo $admin_check_user; ?>">浄霊履歴／完了一覧</a></li>
	            <li><a href="<?php echo home_url();?>/users/user-spirit-list-menu/<?php echo $admin_check_user; ?>">各種お申し込み</a></li>
	            <li><a href="<?php echo home_url();?>/users/user-official-store/<?php echo $admin_check_user; ?>">A-GATE OFFICIAL STORE</a></li>
	            <li><a href="<?php echo home_url();?>/users/user-acount/<?php echo $admin_check_user; ?>">会員情報</a></li>
	            <li><a href="<?php echo home_url();?>/users/user-contact-question/<?php echo $admin_check_user; ?>">お問い合わせ</a></li>
				<li><a href="<?php echo wp_logout_url( home_url() ); ?>">ログアウト</a></li>
	          </ul>
	        </nav>
	      </div>
	    </aside>
	    <div class="user-main-content" id="userMainContent">
	      <button class="sidebar-toggle" id="sidebarToggleBtn" aria-label="メニュー">
	        <span class="material-icons">menu</span>
	      </button>
	<?php endif; ?>

	<div id="content" class="site-content">
		<div id="primary" class="content-area">
			<main id="main" class="site-main">