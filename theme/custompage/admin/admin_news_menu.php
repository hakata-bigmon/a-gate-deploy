<?php 

    require_once ("a-gate-functions.php");


?>

<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<style>
.admin-menu-list.news-vertical {
  flex-direction: column;
  align-items: center;
  gap: 28px;
}
.admin-menu-link.news-big {
  width: 100%;
  max-width: 480px;
  font-size: 1.35rem;
  padding: 28px 0;
  justify-content: center;
  margin-bottom: 0;
}
</style>

<div class="admin-menu-wrapper">
  <h1 class="admin-menu-title">お知らせ設定</h1>
  <div class="admin-menu-section">
    
    <div class="admin-menu-list news-vertical">
      <a class="admin-menu-link news-big" href="<?php echo getURLSetSlag('admin-news-list'); ?>">
        <span class="material-icons">announcement</span> お知らせ一覧
      </a>
      <a class="admin-menu-link news-big" href="<?php echo home_url(); ?>/wp-admin/post-new.php?post_type=cpt_news" target="_blank">
        <span class="material-icons">add_alert</span> お知らせ作成
      </a>
    </div>
  </div>
</div>