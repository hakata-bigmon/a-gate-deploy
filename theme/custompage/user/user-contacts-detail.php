<?php 

   require_once (dirname(__FILE__)."/../../custompage/admin/a-gate-functions.php");
   require_once (dirname(__FILE__)."/../../class/spiritUserClass.php");
   require_once (dirname(__FILE__)."/../../class/spiritContensQuestionClass.php");

    //ポストユーザー
    $user_id = get_current_user_id();

    //もし管理者等でチェックするユーザーがいるならここで変更する
    $get_url = CheckUserPageAdmin($user_id);

    $userClass = new SpiritUserClass(); //ユーザー管理

    $spiritContensQuestion = new SpiritContensQuestionClass();


    $contact_id = "";

    if(isset($_POST["contact_id"])){
        $contact_id = $_POST["contact_id"];
    }

    //保存
    if(isset($_POST["save"])){
      $save_id = $spiritContensQuestion->saveContacts($user_id,$_POST);
      //新規作成なので管理者確認を入れる
      if($save_id != ""){
        update_field("acf_contacts_admin_check", "1", $contact_id);
      }
    }

    

    //編集
    if(isset($_POST["edit"])){
      $spiritContensQuestion->editContacts($user_id,$_POST["edit"],$_POST);
    }

    $contact_data = $spiritContensQuestion->getContactsDetail($contact_id);
    $user_data = $userClass->getUserAcountData($contact_data["質問者"]);


    //newを外す
    if($contact_data["質問者確認"] == "1"){
      update_field("acf_contacts_user_check", "", $contact_id);
    }

    //var_dump($_POST);
?>

<style>
.user-contacts-detail-card {
  max-width: 700px;
  margin: 36px auto 0 auto;
  background: #fff;
  border-radius: 18px;
  box-shadow: 0 2px 16px rgba(193, 138, 209, 0.10);
  padding: 28px 24px 24px 24px;
  border: 1px solid #e9d6f2;
  font-family: 'Noto Sans JP', sans-serif;
  margin-bottom: 32px;
}
.user-contacts-detail-label {
  font-weight: 600;
  color: #C18AD1;
  margin-bottom: 4px;
  font-size: 15px;
  margin-right: 10px;
}
.user-contacts-detail-content {
  background: #f8f3fa;
  border-radius: 8px;
  padding: 14px 16px;
  font-size: 14px;
  color: #555;
  margin-bottom: 18px;
  word-break: break-all;
  min-height: 40px;
}
.user-contacts-detail-form input[type="text"],
.user-contacts-detail-form textarea {
  width: 100%;
  border: 1px solid #b6c7e3;
  border-radius: 8px;
  padding: 10px 12px;
  font-size: 15px;
  font-family: 'Noto Sans JP', sans-serif;
  margin-bottom: 16px;
  background: #f8fafc;
  transition: border 0.2s;
}
.user-contacts-detail-form input[type="text"]:focus,
.user-contacts-detail-form textarea:focus {
  border: 1.5px solid #C18AD1;
  outline: none;
  background: #fff;
}
.user-contacts-detail-form textarea {
  min-height: 180px;
  resize: vertical;
}
.user-contacts-detail-form button[type="submit"] {
  display: block;
  width: 220px;
  margin: 16px auto 0 auto;
  background: #C18AD1;
  color: #fff;
  font-weight: 700;
  font-size: 16px;
  border-radius: 22px;
  padding: 12px 0;
  border: none;
  cursor: pointer;
  transition: background 0.2s;
  box-shadow: 0 2px 8px rgba(193, 138, 209, 0.08);
}
.user-contacts-detail-form button[type="submit"]:hover {
  background: #a46bb3;
}
</style>

<div class="user-top-area">

    <div class="user-jorei-section-title-wrap">
        <div class="user-jorei-section-title">お問い合わせ詳細</div>
    </div>

    <?php 

      //配列の最後が自分のものならば編集、違うならば返信
      $return_flag = false;

      $end_value = end($contact_data["返信"]);

      if($end_value == "")//親
      {
        $end_value = $contact_id;
      }

      $contact_check_end_data = $spiritContensQuestion->getContactsDetail($end_value);

      //自分の者ではない
      if($contact_check_end_data["質問者"] != $contact_data["質問者"]){
        $return_flag = true;
      }
    ?>
    <div class="user-contacts-detail-card">
      <div style="display: flex;">
        <div>
          <span class="user-contacts-detail-label">日付</span>
          <span><?php echo $contact_data["質問日付年月日"]; ?></span>
        </div>
        <div style="margin-left: 30px;">
          <span class="user-contacts-detail-label">お問い合わせ番号</span>
          <span><?php echo $contact_data["問い合わせ番号"]; ?></span>
        </div>
      </div>
      <div class="user-contacts-detail-label" style="margin-top: 30px;">件名</div>
      <div class="user-contacts-detail-content"><?php echo $contact_data["件名"]; ?></div>
      <div class="user-contacts-detail-label" style="margin-top: 30px;">質問内容</div>
      <div class="user-contacts-detail-content"><?php echo nl2br(htmlspecialchars($contact_data["質問内容"])); ?></div>
    </div>


    <?php if(count($contact_data["返信"]) > 0){   ?>
      <?php foreach($contact_data["返信"] as $key => $value){ ?>
        <?php 
          $contact_return_data = $spiritContensQuestion->getContactsDetail($value); 
          $user_return_data = $userClass->getUserAcountData($contact_return_data["質問者"]);
        ?>


        <?php if($end_value != $value  || $contact_data["書き込み終了"] == "1" || ($end_value == $value && $contact_return_data["質問者"] !=  $contact_data["質問者"]) ){ ?>
          <div class="user-contacts-detail-card" <?php if($contact_return_data["質問者"] != $contact_data["質問者"]){ ?>style="border: 2px solid #e3f2fd;"<?php } ?>>

            <div style="display: flex;">
              <div>
                <span class="user-contacts-detail-label" <?php if($contact_return_data["質問者"] != $contact_data["質問者"]){ ?>style="color: #1976d2;"<?php } ?>>日付</span>
                <span><?php echo $contact_return_data["質問日付年月日"]; ?></span>
              </div>
              <?php if($contact_return_data["質問者"] != $contact_data["質問者"]){ ?>
                <div style="margin-left: 30px;">
                  <span class="user-contacts-detail-label" style="color: #1976d2;">返信者</span>
                  <span>運営</span>
                </div>
              <?php } ?>
            </div>

            
            <div class="user-contacts-detail-label" style="margin-top: 30px; <?php if($contact_return_data["質問者"] != $contact_data["質問者"]){ ?>color: #1976d2;<?php } ?>">返信内容</div>
            <div class="user-contacts-detail-content" <?php if($contact_return_data["質問者"] != $contact_data["質問者"]){ ?>style="background: #e3f2fd;"<?php } ?>><?php echo nl2br(htmlspecialchars($contact_return_data["質問内容"])); ?></div>
          </div>

          

        <?php }else{ ?>
          <div class="user-contacts-detail-card">
            <div style="display: flex;">
              <div>
                <span class="user-contacts-detail-label">日付</span>
                <span><?php echo $contact_return_data["質問日付年月日"]; ?></span>
              </div>
            </div>
            <form action="<?php echo getURLSetSlag('users/user-contacts-detail'); echo $get_url["add"]; ?>" method="post" class="user-contacts-detail-form">
              <input type="hidden" name="edit" value="<?php echo $value; ?>">
              <input type="hidden" name="contact_id" value="<?php echo $contact_id; ?>">
              <input type="hidden" name="acf_contacts_title" value="<?php echo $contact_return_data["件名"]; ?>">
              <div class="user-contacts-detail-label" style="margin-top: 30px;">返信内容</div>
              <textarea name="acf_contacts_txt"><?php echo htmlspecialchars($contact_return_data["質問内容"]); ?></textarea>
              <div style="font-size: 12px;color: red;text-align: center;">運営からの返信があるか、質問が閉じられるまでは編集が可能です</div>
              <button type="submit" name="return_submit">編集する</button>
            </form>
          </div>
        <?php } ?>
      <?php } ?>
    <?php } ?>

    <?php if($contact_data["書き込み終了"] == "1"){ ?>
        <div style="text-align: center;font-size: 20px;color: red;font-weight: 600;">こちらの質問は終了しました</div>
      <?php } ?>


    <?php if($return_flag && $contact_data["書き込み終了"] == ""){ ?>
      <?php $end_data = $spiritContensQuestion->getContactsDetail($end_value); ?>
      <div class="user-contacts-detail-card">
        <form action="<?php echo getURLSetSlag("users/user-contacts-detail"); echo $get_url["add"]; ?>" method="post" class="user-contacts-detail-form">
          <input type="hidden" name="save" value="1">
          <input type="hidden" name="contact_id" value="<?php echo $contact_id; ?>">
          <input type="hidden" name="acf_contacts_parent_number" value="<?php echo $contact_id; ?>">
          <input type="hidden" name="acf_contacts_unixtime" value="<?php echo time(); ?>">
          <input type="hidden" name="acf_contacts_return_number" value="<?php echo $end_value; ?>">
          <input type="hidden" name="acf_contacts_title" value="RE:<?php echo $end_data["件名"]; ?>">
          <div class="user-contacts-detail-label">返信</div>
          <textarea name="acf_contacts_txt" required></textarea>
          <button type="submit" name="return_submit">返信する</button>
        </form>
      </div>


    <?php } ?>




    <div class="user-account-edit-form-btn-wrap" style="margin-top: 80px;margin-bottom: 100px;">
        <a href="<?php echo getURLSetSlag("users/user-contacts-list"); echo $get_url["add"]; ?>" class="user-account-edit-return-btn">一覧に戻る  &gt;</a>
    </div>

    <div class="user-account-edit-form-btn-wrap" style="margin-top: 80px;margin-bottom: 100px;">
        <a href="<?php echo getURLSetSlag("users/user_top"); echo $get_url["add"]; ?>" class="user-account-edit-return-btn">TOPへ戻る  &gt;</a>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  document.querySelectorAll('.user-contacts-detail-form').forEach(function(form) {
    form.addEventListener('submit', function(e) {
      if (!confirm('本当に送信しますか？')) {
        e.preventDefault();
      }
    });
  });
});
</script>