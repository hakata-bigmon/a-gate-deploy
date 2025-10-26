<?php 

    require_once ("a-gate-functions.php");
    require_once (dirname(__FILE__)."/../../class/spiritContensQuestionClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritUserClass.php");

    $spiritContensQuestion = new SpiritContensQuestionClass();
    $spiritUser = new SpiritUserClass();


    $contact_id = "";

    if(isset($_POST["contact_id"])){
        $contact_id = $_POST["contact_id"];
    }
    else if(isset($_GET["contact_id"])){
        $contact_id = $_GET["contact_id"];
    }


    //保存
    if(isset($_POST["save"])){
        $save_id = $spiritContensQuestion->saveContacts(get_current_user_id(),$_POST);

        //新規作成なのでユーザー確認を入れる
        if($save_id != ""){
          update_field("acf_contacts_user_check", "1", $contact_id);
        }

        //ここでリロードを入れる（POSTも入れる）
        echo "<script>window.location.href='" . getURLSetSlag("admin-contacts-detail") . "?contact_id=" . $contact_id . "';</script>";
    }

    //編集
    if(isset($_POST["edit"])){
        $spiritContensQuestion->editContacts(get_current_user_id(),$_POST["edit"],$_POST);

        
        echo "<script>window.location.href='" . getURLSetSlag("admin-contacts-detail") . "?contact_id=" . $contact_id . "';</script>";
    }

    //質問を閉じる
    if(isset($_POST["end_save"])){
        if(isset($_POST["acf_contacts_end"])){
            $acf_contacts_end = "1";
        }else{
            $acf_contacts_end = "";
        }
        update_field("acf_contacts_end", $acf_contacts_end, $contact_id);

        echo "<script>window.location.href='" . getURLSetSlag("admin-contacts-detail") . "?contact_id=" . $contact_id . "';</script>";
    }


    $contact_data = $spiritContensQuestion->getContactsDetail($contact_id);

    //var_dump($_POST);

    $user_data = $spiritUser->getUserAcountData($contact_data["質問者"]);
    
    //newを外す
    if($contact_data["管理者確認"] == "1"){
      update_field("acf_contacts_admin_check", "", $contact_id);
    }

?>

<style>
.admin-contacts-detail-card {
  max-width: 1000px;
  margin: 32px auto 0 auto;
  background: #fff;
  border-radius: 18px;
  box-shadow: 0 2px 16px rgba(193, 138, 209, 0.10);
  padding: 28px 24px 24px 24px;
  border: 5px solid #e9d6f2;
  font-family: 'Noto Sans JP', sans-serif;
  margin-bottom: 28px;
}

.admin-contacts-detail-back-btn {
  background: #e3f2fd;
    color: #1976d2;
    border: 1.5px solid #1976d2;
    border-radius: 20px;
    padding: 8px 36px;
    font-size: 15px;
    margin-bottom: 24px;
    cursor: pointer;
    display: block;
    margin-left: auto;
    margin-right: auto;
    font-family: 'Noto Sans JP', sans-serif;
    font-weight: 500;
    transition: background 0.18s, color 0.18s;
}
.admin-contacts-detail-back-btn:hover {
  background: #1976d2;
  color: #fff;
}

.admin-contacts-detail-form input[type="text"],
.admin-contacts-detail-form textarea {
  width: 100%;
  border: 1px solid #b6c7e3;
  border-radius: 8px;
  padding-top: 10px;
  padding-bottom: 10px;
  padding-left: 5px;
  font-size: 15px;
  font-family: 'Noto Sans JP', sans-serif;
  margin-bottom: 16px;
  background: #f8fafc;
  transition: border 0.2s;
}
.admin-contacts-detail-form input[type="text"]:focus,
.admin-contacts-detail-form textarea:focus {
  border: 1.5px solid #C18AD1;
  outline: none;
  background: #fff;
}
.admin-contacts-detail-form textarea {
  min-height: 300px;
  resize: vertical;
}
.admin-contacts-detail-form button[type="submit"] {
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
.admin-contacts-detail-form button[type="submit"]:hover {
  background: #1976d2;
}
.admin-contacts-detail-label {
  font-weight: 600;
  color: #234a6f;
  margin-bottom: 4px;
  font-size: 15px;
  margin-right: 10px;
}
.admin-contacts-detail-content {
  background: #e3f2fd;
  border-radius: 8px;
  padding: 14px 16px;
  font-size: 13px;
  color: #555;
  margin-bottom: 18px;
  word-break: break-all;
  min-height: 40px;
}
</style>

<div class="admin-user-table-area">
    <div class="admin-title">
      <?php echo $contact_data["件名"]; ?>
    </div>

    <div>

      <form action="<?php echo getURLSetSlag('admin-contacts-list'); ?>" method="post" style="margin-bottom: 24px;">
        <button type="submit" name="back_list" class="admin-contacts-detail-back-btn">一覧に戻る</button>
      </form>
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

      if($contact_check_end_data["質問者"] == $contact_data["質問者"]){
        $return_flag = true;
      }
    ?>

    <!-- 質問内容カード -->
    <div class="admin-contacts-detail-card">

      <div style="display: flex;">
        <div class="admin-contacts-detail-meta">
          <span class="admin-contacts-detail-label">日付</span>
          <span><?php echo $contact_data["質問日付年月日"]; ?></span>
        </div>
        <div class="admin-contacts-detail-meta" style="margin-left: 30px;">
          <span class="admin-contacts-detail-label">質問者</span>
          <span><?php echo $user_data["フル名前"]; ?></span>
        </div>
      </div>

      <div class="admin-contacts-detail-label" style="margin-top: 30px;">件名</div>
      <div class="admin-contacts-detail-content" style="background: #f8f3fa;"><?php echo $contact_data["件名"]; ?></div>
      <div class="admin-contacts-detail-label" style="margin-top: 30px;">質問内容</div>
      <div class="admin-contacts-detail-content" style="background: #f8f3fa;"><?php echo nl2br(htmlspecialchars($contact_data["質問内容"])); ?></div>
    </div>

    <?php if(count($contact_data["返信"]) > 0){   ?>
      <?php foreach($contact_data["返信"] as $key => $value){ ?>
        <?php if($value == $contact_id)continue;?>
        <?php 
          $contact_return_data = $spiritContensQuestion->getContactsDetail($value); 
          $user_return_data = $spiritUser->getUserAcountData($contact_return_data["質問者"]);

        ?>
        <div class="admin-contacts-detail-card" <?php if($contact_return_data["質問者"] != $contact_data["質問者"]){ ?>style="border: 5px solid #e3f2fd;"<?php } ?>>

          <?php if($contact_return_data["質問者"] != $contact_data["質問者"] && $end_value == $value){ //管理者が返信した場合 ?>

            <div style="display: flex;">
              <div class="admin-contacts-detail-meta">
                <span class="admin-contacts-detail-label">日付</span>
                <span><?php echo $contact_return_data["質問日付年月日"]; ?></span>
              </div>
              <div class="admin-contacts-detail-meta" style="margin-left: 30px;">
                <span class="admin-contacts-detail-label">返信者</span>
                <span><?php echo $user_return_data["フル名前"]; ?></span>
              </div>
            </div>


            <form action="<?php echo getURLSetSlag('admin-contacts-detail'); ?>" method="post" class="admin-contacts-detail-form">
              <input type="hidden" name="edit" value="<?php echo $value; ?>">
              <input type="hidden" name="contact_id" value="<?php echo $contact_id; ?>">
              <input type="hidden" name="acf_contacts_title" value="<?php echo $contact_return_data["件名"]; ?>">
              <div class="admin-contacts-detail-label" style="margin-top: 30px;">返信内容</div>
              <textarea name="acf_contacts_txt"><?php echo htmlspecialchars($contact_return_data["質問内容"]); ?></textarea>
              <button type="submit" name="return_submit" style="background: #1976d2;">編集する</button>
            </form>
          <?php }else{ ?>
            <div style="display: flex;">
              <div class="admin-contacts-detail-meta">
                <span class="admin-contacts-detail-label">日付</span>
                <span><?php echo $contact_return_data["質問日付年月日"]; ?></span>
              </div>

              <?php if($contact_return_data["質問者"] == $contact_data["質問者"]){ ?>
                <div class="admin-contacts-detail-meta" style="margin-left: 30px;">
                  <span class="admin-contacts-detail-label">返信者</span>
                  <span><?php echo $user_return_data["フル名前"]; ?></span>
                </div>
              <?php } ?>
            </div>
            <div class="admin-contacts-detail-label" style="margin-top: 30px;">返信内容</div>
            <div class="admin-contacts-detail-content" <?php if($contact_return_data["質問者"] == $contact_data["質問者"]){ ?>style="background: #f8f3fa;"<?php } ?>><?php echo nl2br(htmlspecialchars($contact_return_data["質問内容"])); ?></div>
          <?php } ?>

        </div>
      <?php } // foreachの閉じタグを明示的に ?>
    <?php } // if(count($contact_data["返信"]) > 0) ?>

    <?php if($return_flag){ //最後が自分以外 ?>
      <?php $end_data = $spiritContensQuestion->getContactsDetail($end_value); ?>
      <div class="admin-contacts-detail-card" style="border: 1px solid #e3f2fd;">
        <form action="<?php echo getURLSetSlag('admin-contacts-detail'); ?>" method="post" class="admin-contacts-detail-form">
          <input type="hidden" name="save" value="1">
          <input type="hidden" name="contact_id" value="<?php echo $contact_id; ?>">
          <input type="hidden" name="acf_contacts_parent_number" value="<?php echo $contact_id; ?>">
          <input type="hidden" name="acf_contacts_unixtime" value="<?php echo time(); ?>">
          <input type="hidden" name="acf_contacts_return_number" value="<?php echo $end_value; ?>">
          <input type="hidden" name="acf_contacts_title" value="RE:<?php echo $end_data["件名"]; ?>">
          <div class="admin-contacts-detail-label">返信内容</div>
          <textarea name="acf_contacts_txt" required></textarea>
          <button type="submit" name="return_submit" style="background: #1976d2;">返信する</button>
        </form>
      </div>
    <?php } ?>

    <div class="admin-contacts-detail-card" style="border: 1px solid #e3f2fd;">

      <form action="<?php echo getURLSetSlag('admin-contacts-detail'); ?>" method="post" class="admin-contacts-detail-form">
        <input type="hidden" name="end_save" value="1">
        <input type="hidden" name="contact_id" value="<?php echo $contact_id; ?>">
        <input type="checkbox" name="acf_contacts_end" value="1" <?php if($contact_data["書き込み終了"] == "1"){ ?>checked<?php } ?>>
        <label for="acf_contacts_end">質問を閉じる</label>
        <input type="submit" name="return_submit" style="" value="保存">
      </form>
    </div>


</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  document.querySelectorAll('.admin-contacts-detail-form').forEach(function(form) {
    form.addEventListener('submit', function(e) {
      if (!confirm('本当に送信しますか？')) {
        e.preventDefault();
      }
    });
  });
});
</script>
