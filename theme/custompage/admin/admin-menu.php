<?php 
    require_once ("a-gate-functions.php");
    $current_user = wp_get_current_user();
?>

<!-- Google Material Icons -->
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<div class="admin-menu-wrapper">
  <h1 class="admin-menu-title">管理メニュー</h1>

  <?php if(in_array( 'editor', (array) $current_user->roles )){?>
    <div class="admin-menu-section">
      <div class="admin-menu-section-title"><span class="material-icons">menu</span> メニュー</div>
      <div class="admin-menu-list">
        <a class="admin-menu-link" href="<?php echo getURLSetSlag('admin-member-list'); ?>">
          <span class="material-icons">people</span> 顧客一覧
        </a>
        <a class="admin-menu-link" href="<?php echo getURLSetSlag('admin-spirit-sheets-list'); ?>">
          <span class="material-icons">list_alt</span> 浄霊・施術一覧
        </a>
        <a class="admin-menu-link" href="<?php echo getURLSetSlag('admin-sprit-make-menu'); ?>">
          <span class="material-icons">event</span>スケジュール作成
        </a>
        <a class="admin-menu-link" href="<?php echo getURLSetSlag('admin-explanation-invoice-list'); ?>">
          <span class="material-icons">receipt_long</span>請求書・領収書確認
        </a>
      </div>
    </div>
  <?php } ?>

  <?php if(in_array( 'administrator', (array) $current_user->roles )){?>
    <div class="admin-menu-section">
      <div class="admin-menu-section-title"><span class="material-icons">healing</span> 施術</div>
      <div class="admin-menu-list">
        <a class="admin-menu-link" href="<?php echo getURLSetSlag('admin-member-list'); ?>">
          <span class="material-icons">people</span> 顧客一覧
        </a>
        <a class="admin-menu-link" href="<?php echo getURLSetSlag('admin-spirit-sheets-list'); ?>">
          <span class="material-icons">list_alt</span> 浄霊・施術一覧
        </a>
        <a class="admin-menu-link" href="<?php echo getURLSetSlag('admin-sprit-make-menu'); ?>">
          <span class="material-icons">add_circle</span> 浄霊・施術作成
        </a>
      </div>
    </div>
    <div class="admin-menu-section">
      <div class="admin-menu-section-title"><span class="material-icons">event</span> 施術作成</div>
      <div class="admin-menu-list">
        <a class="admin-menu-link" href="<?php echo getURLSetSlag('admin-sprit-make-menu'); ?>">
          <span class="material-icons">add_circle</span> 浄霊・施術作成
        </a>
        <a class="admin-menu-link" href="<?php echo getURLSetSlag('admin-schedule-menu'); ?>">
          <span class="material-icons">calendar_today</span> 日程・スケジュール
        </a>
        <a class="admin-menu-link" href="<?php echo getURLSetSlag('admin-spirit-schedule-list'); ?>?group=3">
          <span class="material-icons">assignment</span> 施術スケジュール一覧
        </a>
        <a class="admin-menu-link" href="<?php echo getURLSetSlag('admin-spirit-schedule-list'); ?>?group=5">
          <span class="material-icons">psychology</span> 相談・ヒーリングスケジュール一覧
        </a>
       
      </div>
    </div>
    <div class="admin-menu-section">
      <div class="admin-menu-section-title"><span class="material-icons">description</span> 粗見シート</div>
      <div class="admin-menu-list">
        <a class="admin-menu-link" href="<?php echo getURLSetSlag('admin-arami-sheet-list'); ?>">
          <span class="material-icons">note_add</span> 粗見シート一覧・新規作成
        </a>
      </div>
    </div>
    <div class="admin-menu-section">
      <div class="admin-menu-section-title"><span class="material-icons">groups</span> スタッフ管理</div>
      <div class="admin-menu-list">
        <a class="admin-menu-link" href="<?php echo getURLSetSlag('admin-make-admin-user-list'); ?>">
          <span class="material-icons">admin_panel_settings</span> 管理者一覧
        </a>
        <a class="admin-menu-link" href="<?php echo getURLSetSlag('admin-make-teaching-user-list'); ?>">
          <span class="material-icons">school</span> 先生一覧
        </a>
        <a class="admin-menu-link" href="<?php echo getURLSetSlag('admin-make-consultation-user-list'); ?>">
          <span class="material-icons">person_search</span> 相談者一覧
        </a>
        <a class="admin-menu-link" href="<?php echo getURLSetSlag('admin-incharge-setting'); ?>">
          <span class="material-icons">assignment_ind</span> 担当設定
        </a>
      </div>
    </div>
    <div class="admin-menu-section">
      <div class="admin-menu-section-title"><span class="material-icons">campaign</span> お知らせ</div>
      <div class="admin-menu-list">
        <a class="admin-menu-link" href="<?php echo getURLSetSlag('admin-news-list'); ?>">
          <span class="material-icons">announcement</span> お知らせ一覧
        </a>
        <a class="admin-menu-link" href="<?php echo home_url(); ?>/wp-admin/post-new.php?post_type=cpt_news" target="_blank">
          <span class="material-icons">add_alert</span> お知らせ新規作成
        </a>
      </div>
    </div>
    <div class="admin-menu-section">
      <div class="admin-menu-section-title"><span class="material-icons">settings</span> 管理設定</div>
      <div class="admin-menu-list">
        <a class="admin-menu-link" href="<?php echo getURLSetSlag('admin-profile-menu'); ?>">
          <span class="material-icons">settings</span> 設定メニュー
        </a>
      </div>
    </div>
  <?php } ?>
</div>
