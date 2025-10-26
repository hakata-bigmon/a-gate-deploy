<?php 

   require_once (dirname(__FILE__)."/../../custompage/admin/a-gate-functions.php");
   require_once (dirname(__FILE__)."/../../class/spiritUserClass.php");


    //ポストユーザー
    $user_id = get_current_user_id();

    //もし管理者等でチェックするユーザーがいるならここで変更する
    $get_url = CheckUserPageAdmin($user_id);

    $userClass = new SpiritUserClass(); //ユーザー管理


   

    $userData = $userClass->getUserAcountData($user_id);


    //var_dump($_POST);
?>

<style>
.user-contact-form-card {
  max-width: 520px;
  margin: 40px auto 0 auto;
  background: #fff;
  border-radius: 18px;
  box-shadow: 0 2px 16px rgba(193, 138, 209, 0.08);
  padding: 36px 32px 32px 32px;
  border: 1px solid #e9d6f2;
}
.user-contact-form-card label {
  display: block;
  font-weight: 600;
  color: #C18AD1;
  margin-bottom: 8px;
  font-size: 15px;
  font-family: 'Noto Sans JP', sans-serif;
}
.user-contact-form-card .confirm-value {
  background: #f8f3fa;
  border-radius: 8px;
  padding: 12px 14px;
  font-size: 15px;
  color: #555;
  margin-bottom: 22px;
  font-family: 'Noto Sans JP', sans-serif;
  word-break: break-all;
  min-height: 40px;
}
.user-contact-form-card button[type="submit"] {
  display: block;
  width: 100%;
  background: #C18AD1;
  color: #fff;
  font-weight: 700;
  font-size: 16px;
  border-radius: 22px;
  padding: 12px 0;
  border: none;
  cursor: pointer;
  margin-top: 10px;
  transition: background 0.2s;
  box-shadow: 0 2px 8px rgba(193, 138, 209, 0.08);
}
.user-contact-form-card button[type="submit"]:hover {
  background: #a46bb3;
}
</style>

<div class="user-top-area">

    <div class="user-jorei-section-title-wrap">
        <div class="user-jorei-section-title">お問い合わせ</div>
    </div>

    <div class="user-contact-form-card">
      <form action="<?php echo getURLSetSlag('users/user-contacts-list') . $get_url['add']; ?>" method="post">
        <input type="hidden" name="save_data" value="1">
        <input type="hidden" name="acf_contacts_unixtime" value="<?php echo time(); ?>">
        <input type="hidden" name="acf_contacts_title" value="<?php echo $_POST["acf_contacts_title"]; ?>">
        <input type="hidden" name="acf_contacts_txt" value="<?php echo $_POST["acf_contacts_txt"]; ?>">
        
        <div>
          <label for="acf_contacts_title">件名</label>
          <div class="confirm-value"><?php echo htmlspecialchars($_POST["acf_contacts_title"]); ?></div>
        </div>
        <div>
          <label for="acf_contacts_txt">問い合わせ内容</label>
          <div class="confirm-value"><?php echo nl2br(htmlspecialchars($_POST["acf_contacts_txt"])); ?></div>
        </div>
        <button type="submit">送信</button>
      </form>
    </div>

    <div class="user-account-edit-form-btn-wrap" style="margin-top: 80px;margin-bottom: 100px;">
      <form action="<?php echo getURLSetSlag('users/user-contact') . $get_url['add']; ?>" method="post" style="display:inline;">
        <input type="hidden" name="acf_contacts_title" value="<?php echo htmlspecialchars($_POST['acf_contacts_title']); ?>">
        <input type="hidden" name="acf_contacts_txt" value="<?php echo htmlspecialchars($_POST['acf_contacts_txt']); ?>">
        <button type="submit" class="user-account-edit-return-btn">入力に戻る  &gt;</button>
      </form>
    </div>

    <div class="user-account-edit-form-btn-wrap" style="margin-top: 80px;margin-bottom: 100px;">
        <a href="<?php echo getURLSetSlag("users/user_top"); echo $get_url["add"]; ?>" class="user-account-edit-return-btn">TOPへ戻る  &gt;</a>
    </div>


</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  var form = document.querySelector('.user-contact-form-card form');
  if (form) {
    form.addEventListener('submit', function(e) {
      if (!confirm('本当に送信しますか？')) {
        e.preventDefault();
      }
    });
  }
});
</script>