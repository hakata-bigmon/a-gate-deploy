<?php 

    require_once ("a-gate-functions.php");


?>

<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<style>
.admin-menu-list.staff-vertical {
  flex-direction: column;
  align-items: center;
  gap: 28px;
}
.admin-menu-link.staff-big {
  width: 100%;
  max-width: 480px;
  font-size: 1.35rem;
  padding: 28px 0;
  justify-content: center;
  margin-bottom: 0;
}
</style>

<div class="admin-menu-wrapper">
  <h1 class="admin-menu-title">スタッフ管理メニュー</h1>
  <div class="admin-menu-section">
   
    <div class="admin-menu-list staff-vertical">
      <a class="admin-menu-link staff-big" href="<?php echo getURLSetSlag('admin-make-admin-user-list'); ?>">
        <span class="material-icons">admin_panel_settings</span> 管理者一覧
      </a>
      <a class="admin-menu-link staff-big" href="<?php echo getURLSetSlag('admin-make-teaching-user-list'); ?>">
        <span class="material-icons">school</span> 先生一覧
      </a>
      <a class="admin-menu-link staff-big" href="<?php echo getURLSetSlag('admin-make-consultation-user-list'); ?>">
        <span class="material-icons">person_search</span> 電話相談スタッフ一覧
      </a>
      <a class="admin-menu-link staff-big" href="<?php echo getURLSetSlag('admin-incharge-setting'); ?>">
        <span class="material-icons">assignment_ind</span> 担当設定
      </a>
    </div>
  </div>
</div>