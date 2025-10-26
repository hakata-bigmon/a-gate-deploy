<?php 

    require_once ("a-gate-functions.php");
    require_once (dirname(__FILE__)."/../../class/spiritTypeClass.php");

?>

<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<div class="admin-menu-wrapper">
  <h1 class="admin-menu-title">設定メニュー</h1>

  <div class="admin-menu-section">
    <div class="admin-menu-section-title"><span class="material-icons">healing</span> 施術</div>
    <div class="admin-menu-list">
      <a class="admin-menu-link" href="<?php echo getURLSetSlag('admin-exorcism-price-setting'); ?>">
        <span class="material-icons">settings</span> 施術詳細設定
      </a>
      <a class="admin-menu-link" href="<?php echo getURLSetSlag('admin-spirit-place-list'); ?>">
        <span class="material-icons">place</span> 浄霊場所設定
      </a>
      <a class="admin-menu-link" href="<?php echo getURLSetSlag('admin_spirit_status'); ?>">
        <span class="material-icons">admin_panel_settings</span> 管理者 施術ステータス設定
      </a>
      <a class="admin-menu-link" href="<?php echo getURLSetSlag('admin_spirit_user_status'); ?>">
        <span class="material-icons">person</span> 会員 施術ステータス設定
      </a>
      <a class="admin-menu-link" href="<?php echo getURLSetSlag('admin-exorcism-sort'); ?>">
        <span class="material-icons">sort</span> 施術表示順
      </a>
    </div>
  </div>

  <div class="admin-menu-section">
    <div class="admin-menu-section-title"><span class="material-icons">wifi</span> リモート依頼</div>
    <div class="admin-menu-list">
      <a class="admin-menu-link" href="<?php echo getURLSetSlag('admin-sales-list'); ?>?type=<?php echo SpiritTypeClass::SPIRIT_TYPE_NAME_SPIRIT;?>">
        <span class="material-icons">storefront</span> リモート依頼説明ページ
      </a>
      <a class="admin-menu-link" href="<?php echo getURLSetSlag('admin-payment-setting'); ?>?type=1">
        <span class="material-icons">payment</span> 支払い設定
      </a>
      <a class="admin-menu-link" href="<?php echo getURLSetSlag('admin-exorcism-price-setting'); ?>">
        <span class="material-icons">settings</span> 価格・最大数設定
      </a>
      
  </div>

  <div class="admin-menu-section" style="margin-top: 20px;">
    <div class="admin-menu-section-title"><span class="material-icons">psychology</span> 鑑定</div>
    <div class="admin-menu-list">
      <a class="admin-menu-link" href="<?php echo getURLSetSlag('admin-sales-list'); ?>?type=<?php echo SpiritTypeClass::SPIRIT_TYPE_NAME_APPRAISAL;?>">
        <span class="material-icons">storefront</span> 鑑定説明ページ
      </a>
      <a class="admin-menu-link" href="<?php echo getURLSetSlag('admin-payment-setting'); ?>?type=2">
        <span class="material-icons">payment</span> 支払い設定
      </a>
      <a class="admin-menu-link" href="<?php echo getURLSetSlag('admin-exorcism-price-setting'); ?>">
        <span class="material-icons">settings</span> 価格・最大数設定
      </a>
    </div>
  </div>



  <div class="admin-menu-section">
    <div class="admin-menu-section-title"><span class="material-icons">event</span> スケジュール</div>
    <div class="admin-menu-list">
      <a class="admin-menu-link" href="<?php echo getURLSetSlag('admin-schedule-list'); ?>">
         <span class="material-icons">storefront</span> スケジュール説明ページ
      </a>
      <a class="admin-menu-link" href="<?php echo getURLSetSlag('admin-payment-setting'); ?>?type=3">
        <span class="material-icons">payment</span> 支払い設定
      </a>
      <a class="admin-menu-link" href="<?php echo getURLSetSlag('admin-exorcism-price-setting'); ?>">
        <span class="material-icons">settings</span> 価格・最大数設定
      </a>
      <a class="admin-menu-link" href="<?php echo getURLSetSlag('admin-schedule-color-setting'); ?>">
        <span class="material-icons">palette</span> スケジュールカラー
      </a>
    </div>
  </div>

  
  <div class="admin-menu-section">
    <div class="admin-menu-section-title"><span class="material-icons">shopping_cart</span> 物販</div>
    <div class="admin-menu-list">
      <a class="admin-menu-link" href="<?php echo getURLSetSlag('admin-sales-list'); ?>">
        <span class="material-icons">storefront</span> 物販販売ページ
      </a>
      <a class="admin-menu-link" href="<?php echo getURLSetSlag('admin-sales-category'); ?>">
        <span class="material-icons">category</span> 物販販売カテゴリー設定
      </a>
      <a class="admin-menu-link" href="<?php echo getURLSetSlag('admin-exorcism-price-setting'); ?>">
        <span class="material-icons">settings</span> 価格・最大数設定
      </a>
    </div>
  </div>

  <div class="admin-menu-section">
    <div class="admin-menu-section-title"><span class="material-icons">assignment</span> 会員入力・表示</div>
    <div class="admin-menu-list">
      <a class="admin-menu-link" href="<?php echo getURLSetSlag('admin-exorcism-personaldata'); ?>">
        <span class="material-icons">person</span> 個人情報入力管理設定
      </a>
      <a class="admin-menu-link" href="<?php echo getURLSetSlag('admin-input-custom'); ?>">
        <span class="material-icons">edit_note</span> 入力フォーム設定
      </a>
      <a class="admin-menu-link" href="<?php echo getURLSetSlag('admin-question-list'); ?>">
        <span class="material-icons">quiz</span> 入力フォーム質問設定
      </a>
      <a class="admin-menu-link" href="<?php echo getURLSetSlag('admin-thanks-custom'); ?>">
        <span class="material-icons">thumb_up</span> サンクス設定
      </a>
    </div>
  </div>

  <div class="admin-menu-section">
    <div class="admin-menu-section-title"><span class="material-icons">group</span> 会員管理</div>
    <div class="admin-menu-list">
      <a class="admin-menu-link" href="<?php echo getURLSetSlag('admin_spirit_status'); ?>">
        <span class="material-icons">admin_panel_settings</span> 管理者 施術ステータス設定
      </a>
      <a class="admin-menu-link" href="<?php echo getURLSetSlag('admin_spirit_user_status'); ?>">
        <span class="material-icons">person</span> 会員 施術ステータス設定
      </a>
      <a class="admin-menu-link" href="<?php echo getURLSetSlag('admin-exorcism-sort'); ?>">
        <span class="material-icons">sort</span> 施術表示順
      </a>
      <a class="admin-menu-link" href="<?php echo getURLSetSlag('admin-profile-connection'); ?>">
        <span class="material-icons">link</span> 関連設定
      </a>
      <a class="admin-menu-link" href="<?php echo getURLSetSlag('admin-profile-grope'); ?>">
        <span class="material-icons">groups</span> グループ設定
      </a>
      <a class="admin-menu-link" href="<?php echo getURLSetSlag('admin-profile-inflow'); ?>">
        <span class="material-icons">trending_up</span> 流入元設定
      </a>
    </div>
  </div>


  <div class="admin-menu-section"  style="margin-top: 20px;">
    <div class="admin-menu-section-title"><span class="material-icons">quiz</span> よくある質問</div>
    <div class="admin-menu-list">
      <a class="admin-menu-link" href="<?php echo getURLSetSlag('admin-contens-question-list'); ?>">
        <span class="material-icons">edit_note</span> 質問作成・編集
      </a>
      <a class="admin-menu-link" href="<?php echo getURLSetSlag('admin-contens-question-category'); ?>">
        <span class="material-icons">category</span> カテゴリー作成・編集
      </a>
    </div>
  </div>

  <div class="admin-menu-section">
    <div class="admin-menu-section-title"><span class="material-icons">email</span> メール</div>
    <div class="admin-menu-list">
      <a class="admin-menu-link" href="<?php echo getURLSetSlag('admin-mail-setting-menu'); ?>">
        <span class="material-icons">settings</span> メール設定
      </a>
    </div>
  </div>

</div>