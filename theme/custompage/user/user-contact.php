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
  box-shadow: 0 2px 16px rgba(25, 118, 210, 0.08);
  padding: 36px 32px 32px 32px;
  border: 1px solid #dbeafe;
}
.user-contact-form-card label {
  display: block;
  font-weight: 600;
  color: #C18AD1;
  margin-bottom: 8px;
  font-size: 15px;
  font-family: 'Noto Sans JP', sans-serif;
}
.user-contact-form-card input[type="text"],
.user-contact-form-card textarea {
  width: 100%;
  border: 1px solid #b6c7e3;
  border-radius: 8px;
  padding: 10px 12px;
  font-size: 15px;
  font-family: 'Noto Sans JP', sans-serif;
  margin-bottom: 20px;
  background: #f8fafc;
  transition: border 0.2s;
}
.user-contact-form-card input[type="text"]:focus,
.user-contact-form-card textarea:focus {
  border: 1.5px solid #C18AD1;
  outline: none;
  background: #fff;
}
.user-contact-form-card textarea {
  min-height: 400px;
  resize: vertical;
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
      <form action="<?php echo getURLSetSlag('users/user-contacts-check') . $get_url['add']; ?>" method="post">
        <div>
          <label for="acf_contacts_title">件名</label>
          <input type="text" name="acf_contacts_title" id="acf_contacts_title" value="<?php if(isset($_POST["acf_contacts_title"])){echo $_POST["acf_contacts_title"];}?>" required>
        </div>
        <div>
          <label for="acf_contacts_txt">問い合わせ内容</label>
          <textarea name="acf_contacts_txt" id="acf_contacts_txt" required><?php if(isset($_POST["acf_contacts_txt"])){echo $_POST["acf_contacts_txt"];}?></textarea>
        </div>
        <button type="submit">確認画面へ</button>
      </form>
    </div>

    <div class="user-account-edit-form-btn-wrap" style="margin-top: 80px;margin-bottom: 100px;">
        <a href="<?php echo getURLSetSlag("users/user-contact-question"); echo $get_url["add"]; ?>" class="user-account-edit-return-btn">よくある質問に戻る  &gt;</a>
    </div>

    <div class="user-account-edit-form-btn-wrap" style="margin-top: 80px;margin-bottom: 100px;">
        <a href="<?php echo getURLSetSlag("users/user_top"); echo $get_url["add"]; ?>" class="user-account-edit-return-btn">TOPへ戻る  &gt;</a>
    </div>


</div>