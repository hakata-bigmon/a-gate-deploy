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

// /* Start the Loop */
// while ( have_posts() ) :
// 	the_post();
// 	get_template_part( 'template-parts/content/content-page' );

// 	// If comments are open or there is at least one comment, load up the comment template.
// 	if ( comments_open() || get_comments_number() ) {
// 		comments_template();
// 	}
// endwhile; // End of the loop.


get_header();

$page_hit = false;
$admin_page_hit = false;

$custom_page_array = array(

	"401" => "order-form",			//入力フォーム
	"402" => "thanks",			//サンクスページ
	"403" => "order-check",					//個人の情報入力確認画面
	"404" => "img_upload",					//画像アップロード
	"405" => "img_thanks",					//画像アップロードサンクス
	"406" => "company-profile",					//会社概要
	"407" => "privacy-policy",					//個人情報保護方針
	"408" => "terms-of-service",					//特定商取引法に関する表記
);

$custom_page_admin_array = array();

if ( (current_user_can('administrator') || current_user_can('editor')) && !current_user_can('Subscriber') &&  is_user_logged_in()) {

	$custom_page_admin_array = array(

		"0" => "admin-menu",					//管理者メニュー
		"1" => "admin-member-regist",			//新規顧客登録
		"2" => "admin-member-list",				//顧客一覧
		"3" => "admin-exorcism-list",			//浄霊項目一覧
		"4" => "admin-input-custom",			//入力フォーム設定
		"5" => "admin-member-profile-custom",	//プロフィール設定
		"6" => "admin-exorcism-questions",		//浄霊質問集
		"7" => "admin_spirit_status",			//浄霊ステータス
		"8" => "admin-profile-menu",			//プロフィール設定メニュー
		"9" => "admin-profile-connection",		//関連設定
		"10" => "admin-profile-grope",			//グループ設定
		"11" => "admin-profile-inflow",			//流入元設定
		"12" => "admin-input-text",				//浄霊・鑑定　入力フォーム記述
		"13" => "admin-member-edit",			//顧客編集
		"14" => "admin-preview",				//入力プレビュー
		"15" => "admin-thanks-custom",			//サンクス設定
		"16" => "admin-thanks-text",			//サンクス編集
		"17" => "admin-thanks-preview",			//サンクスプレビュー
		"18" => "admin-order-form-list",		//入力フォームURLリスト
		"19" => "admin-thanks-list",			//サンクスページURLリスト
		"20" => "admin-spirit-detail",			//各浄霊シート詳細
		"21" => "admin-input-personaldata",		//個人の情報入力設定
		"22" => "admin-exorcism-personaldata",  //個人の情報入力管理設定
		"23" => "admin-sprit-add",				//各顧客浄霊情報追加
		"24" => "admin-spirit-sheets-list",		//浄霊シート一覧
		"25" => "admin-connection-member-list",	//関連グループメンバー一覧
		"26" => "admin-connection-make",		//関連グループ作成
		"27" => "admin-connection-top-chose",	//関連グループ代表者選択
		"28" => "admin-connection-group-disp",	//関連グループ詳細
		"29" => "admin-temporary-registration-list",//仮登録リスト
		"30" => "admin-temporary-registration-new",//仮登録新規
		"31" => "admin-remote-menu",			//リモート浄霊メニュー
		"32" => "admin-sprit-make",				//浄霊作成
		"33" => "admin-arami-sheet-detail",		//粗見シート詳細
		"34" => "admin-remote-sprit-registration-list",		//リモート浄霊本登録
		"35" => "admin-remote-sprit-registration-detail",		//リモート浄霊仮登録詳細
		"36" => "admin-arami-sheet-list",		//粗見シート一覧
		"37" => "admin-arami-sheet-edit",		//粗見シート編集
		"38" => "admin-arami-sheet-target-sort",	//粗見シート対象者番号変更
		
		"39" => "admin-news-postmember",			//管理_お知らせ送信者選択
		"40" => "admin-new-edit",					//管理_お知らせ編集
		"41" => "admin-news-list",					//管理_お知らせ一覧
		"42" => "admin_news_menu",					//管理_お知らせメニュー

		"43" => "admin-question-menu",				//管理_お知らせメニュー
		"44" => "admin-exorcism-sort",				//管理_浄霊タイプ順番
		"45" => "admin_spirit_user_status",			//管理_浄霊ユーザーステータス
		"46" => "admin-spirit-place-edit",			//管理_浄霊場所作成
		"47" => "admin-spirit-place-list",			//管理_浄霊場所一覧
		"48" => "admin-spirit-schedule-list",		//管理_浄霊スケジュール一覧
		"49" => "admin-spirit-schedule-edit",		//管理_浄霊スケジュール作成
		"50" => "admin-spirit-schedule-calendar",		//管理_浄霊スケジュールカレンダー
		"51" => "admin-exorcism-price-setting",		//管理_浄霊価格設定
		"52" => "admin-question-list",				//管理_質問編集リスト
		"53" => "admin-sales-list",					//管理_販売ページリスト
		"54" => "admin-sales-edit",					//管理_販売ページ編集
		"55" => "admin-sales-category",				//管理_販売カテゴリー作成
		"56" => "admin-sales-page-edit",			//管理_販売ページ編集
		"57" => "admin-sales-page-img-sort",		//管理_販売ページ画像ソート
		"58" => "admin-make-admin-user-list",        //管理_管理者ユーザー一覧
		"59" => "admin-make-admin-user",             //管理_管理者ユーザー作成
		"60" => "admin-make-consultation-user-list", //管理_相談ユーザー一覧
		"61" => "admin-make-consultation-user",      //管理_相談ユーザー作成
		"62" => "admin-make-teaching-user-list",     //管理_指導ユーザー一覧
		"63" => "admin-make-teaching-user",          //管理_指導ユーザー作成
		"64" => "admin-staff-menu",                  //管理_スタッフメニュー
		"65" => "admin-schedule-menu",                //管理_スケジュールメニュー
		"66" => "admin-spirit-explanation-schedule-list",//管理_相談スケジュール一覧
		"67" => "admin-spirit-explanation-schedule-edit",//管理_相談スケジュール作成
		"68" => "admin-spirit-explanation-schedule-calendar",//管理_相談スケジュールカレンダー
		"69" => "admin-incharge-setting",				//管理_担当設定
		"70" => "admin-sprit-make-menu",				//管理_浄霊施術作成メニュー
		"71" => "admin-schedule-color-setting",		//管理_スケジュールカラー設定
		"72" => "admin-schedule-execution-list",		//管理_スケジュ―ル実行リスト
		"73" => "admin-explanation-invoice",		//管理_相談請求書
		"74" => "admin-explanation-receipt",		//管理_相談領収書
		"75" => "admin-explanation-invoice-list",		//管理_相談請求書一覧
		"76" => "admin-explanation-receipt-list",		//管理_相談領収書一覧
		"77" => "admin-receipt-setting",			//管理_領収書設定
		"78" => "admin-contens-question-list",		//管理_よくある質問
		"79" => "admin-contens-question-sort",		//管理者_よくある質問並び替え
		"80" => "admin-contens-question-edit",		//管理者_よくある質問編集
		"81" => "admin-contacts-list",				//管理者_お問い合わせ一覧
		"82" => "admin-contacts-detail",			//管理者_お問い合わせ詳細
		"83" => "admin-schedule-list",				//管理者_スケジュール一覧
		"84" => "admin-schedule-page-edit",			//管理者_スケジュール作成
		"85" => "admin-payment-setting",			//管理者_支払い設定
		"86" => "admin-contens-question-category",		//管理_よくある質問カテゴリー
		"87" => "admin-contens-question-category-sort",		//管理者_よくある質問カテゴリーソート
		"88" => "admin-mail-setting-menu",				//管理者_メール設定メニュー
		"89" => "admin-mail-setting",				//管理者_メール設定
		"90" => "admin-arami-sheet-sprit-detail-input",		//管理者　粗見シート　浄霊内容詳細入力
		"91" => "admin-arami-sheet-sprit-print",		//管理者　粗見シート　印刷ページ
		"92" => "admin-membership-information",		//管理者　会員情報申請
		"93" => "admin-personal-news-edit",				//管理者_個別お知らせ作成
	);
	
	$custom_page_array = $custom_page_array + $custom_page_admin_array;

}


$custom_page_user_array = array();

if ( is_user_logged_in()) {

	$custom_page_user_array = array(

		"201" => "user_top",						//TOPメニュー
		"202" => "user-application-possible-list",	//ユーザー_お申込み可能一覧
		"203" => "user-select-spirit-explanation",	//ユーザー_選択浄霊説明
		"204" => "user-thanks",						//ユーザー_サンクスページ
		"205" => "user-in-progress-spirit-list",	//ユーザー_進行中施術一覧
		"206" => "user-spirit-list",				//ユーザー_施術済一覧
		"207" => "user-acount",						//ユーザー_アカウント
		"208" => "user-acount-edit",				//ユーザー_アカウント編集
		"209" => "user-password-edit",				//ユーザー_パスワード変更
		"210" => "user-family-tree",				//ユーザー_家系図
		"211" => "user-security",					//ユーザー_セキュリティ
		"212" => "user-individual-notice",			//ユーザー_個人お知らせ
		"213" => "user-notice",						//ユーザー_お知らせ
		"214" => "user-contact",					//お知らせ
		"215" => "user-spirit-list-menu",			//ユーザー施術履歴メニュー
		"216" => "user-spirit-question-edit",		//ユーザー_入力シート
		"217" => "user-target",						//ユーザー_対象者設定
		"218" => "user-target-list",				//ユーザー_対象者設定
		"219" => "user-schedule-data",				//スケジュール確認
		"220" => "user-official-store",				//公式ストア
		"221" => "user-sales-page",					//販売ページ
		"222" => "user-store-cart",					//公式ストアカート
		"223" => "user-contact-question",			//よくある質問
		"224" => "user-contacts-list",				//お問い合わせ一覧
		"225" => "user-contacts-detail",			//お問い合わせ詳細
		"226" => "user-contacts-check",				//お問い合わせ確認
		"227" => "user-store-cart-procedure",		//公式ストアカート確認
		"228" => "user-store-cart-thanks",			//公式ストアカートサンクス
		"229" => "user-spirit-detail",				//ユーザー_施術詳細
		"230" => "user-sales-credit-page",			//ユーザー_クレジット購入ページ
		"231" => "user-post-address",				//ユーザー_送付先登録
		"232" => "user-schedule-page",				//ユーザー_スケジュール詳細
		"233" => "user-schedule-procedure",			//ユーザー_日程確定予約ページ
		"234" => "user-payment-method",				//ユーザー_支払い方法
		"235" => "user-schedule-thanks",				//ユーザー_スケジュールサンクス
		"236" => "user-schedule-credit-procedure",	//ユーザー_クレジット予約確定
		"237" => "user-spirit-credit-procedure",		//ユーザー_クレジット施術
		"238" => "user-notice-detail",				//ユーザー_お知らせ詳細
		"239" => "user-spirit-result",				//ユーザー_浄霊結果
		"240" => "user-spirit-question-check",		//ユーザー_最終確認ページ
	);
	
	$custom_page_array += $custom_page_user_array;

	//var_dump($custom_page_array);
}


foreach ($custom_page_array as $page_key => $page_value) {
	

	if (is_page($page_value)) {

		
		if (!($page_key >= 0 && $page_key < count($custom_page_admin_array))) {
			$admin_page_hit = true;
		}

		// if ($page_key != 106 && $page_key != 104 && $admin_page_hit && $page_key != 109) {
		// 	Dispbread("page");
		// }
		// admin page
		if ($page_key < 200) {

			// メンテナンスページ
			// if(get_field("item_is_emergency_stop",6709) != "" && get_current_user_id() != 1 ){

			// 	include("maintenance.php");
			// }
			// if (!current_user_can('administrator') ){
			if (!is_user_logged_in()) {

				// アドミン以外はtopページへ飛ばす
				echo "<script>window.location.href = '" . esc_url(wp_login_url()) . "';</script>";

				
			} else {
				?>
				
				<?php
				include ("custompage/admin/" . $page_value . ".php");
			}

		} 
		else if ($page_key >= 200 && $page_key < 400){
			include ("custompage/user/" . $page_value . ".php");
		}
		else if ($page_key >= 400 ){
			include ("custompage/front/" . $page_value . ".php");
		}
	} else {
		// 指定ページ以外はTOPへ
		if(!is_user_logged_in()){

			if(is_page("privacy-policy") )
			{
				include ("custompage/front/privacy-policy.php");
			}
			else if(is_page("terms-of-service"))
			{
				include ("custompage/front/terms-of-service.php");
			}
			else if(is_page("company-profile"))
			{
				include ("custompage/front/company-profile.php");
			}
			else
			{
				echo "<script>window.location.href = '".esc_url( wp_login_url() )."';</script>";
			}
	
		}
	}
}

get_footer();
