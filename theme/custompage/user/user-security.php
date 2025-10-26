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

<div class="user-top-area">

    <div class="user-jorei-section-title-wrap">
        <div class="user-jorei-section-title">セキュリティ</div>
    </div>

    <?php include(dirname(__FILE__)."/user-acount-menu.php"); ?>

    <!-- 二段階認証の流れデザインここから -->
    <div class="security-flow-container">
      <div class="security-flow-title">二段階認証の流れ</div>
      <div class="security-flow-desc">
        携帯電話を使って認証コードが記載されたSMSを受信し、ログインするときに入力します。<br>
        <span class="security-flow-note">※「連絡先」で登録している電話番号が固定電話の場合、二段階認証のセキュリティをご利用する事は出来ません。</span>
      </div>
      <div class="security-flow-steps">
        <div class="security-flow-step">
          <div class="security-flow-icon">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/2sv_01.jpg" alt="二段階認証の流れ">
         </div>
          <div class="security-flow-step-title">二段認証を承認する</div>
          <div class="security-flow-step-desc">下記ボタンから「二段認証を承認する」を実行してください。</div>
        </div>
        <div class="security-flow-step">
          <div class="security-flow-icon">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/2sv_02.jpg" alt="二段階認証の流れ">
          </div>
          <div class="security-flow-step-title">お持ちの電話と連動</div>
          <div class="security-flow-step-desc">「連絡先」で登録している携帯電話の電話番号と連動します。登録後、ログイン時毎に認証コードを発行されます。</div>
        </div>
        <div class="security-flow-step">
          <div class="security-flow-icon">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/2sv_03.jpg" alt="二段階認証の流れ">
          </div>
          <div class="security-flow-step-title">ログイン時にコードを発行</div>
          <div class="security-flow-step-desc">連動した電話にショートメールで認証コードが届き、認証コードを入力する事でログインのロックが解除されます。</div>
        </div>
      </div>
      <div class="security-flow-btn-wrap">
        <button class="security-flow-btn">二段階認証 切</button>
      </div>
    </div>
    <!-- 二段階認証の流れデザインここまで -->
    <div class="user-account-edit-form-btn-wrap" style="margin-top: 80px;margin-bottom: 100px;">
        <a href="<?php echo getURLSetSlag("users/user_top"); echo $get_url["add"]; ?>" class="user-account-edit-return-btn">TOPへ戻る  &gt;</a>
    </div>
</div>

<style>
.security-flow-container {
  background: #fff;
  border-radius: 12px;
  box-shadow: 0 2px 12px #C18AD122;
  padding: 32px 24px 48px 24px;
  margin: 32px auto 0 auto;
  max-width: 800px;
}
.security-flow-title {
  background: #7B3FA0;
  color: #fff;
  font-size: 22px;
  font-weight: 700;
  border-radius: 6px 6px 0 0;
  padding: 12px 0;
  text-align: center;
  margin-bottom: 18px;
}
.security-flow-desc {
  color: #444;
  font-size: 15px;
  text-align: left;
  margin-bottom: 10px;
  padding: 0 8px;
}
.security-flow-note {
  color: #7B3FA0;
  font-size: 13px;
}
.security-flow-steps {
  display: flex;
  justify-content: space-between;
  gap: 18px;
  margin: 32px 0 0 0;
  flex-wrap: wrap;
}
.security-flow-step {
  flex: 1 1 0;
  min-width: 200px;
  max-width: 260px;
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
  margin: 0 8px;
}
.security-flow-icon {
  margin-bottom: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 2px;
}
.security-flow-step-title {
  font-size: 15px;
  font-weight: 700;
  color: #444;
  margin-bottom: 6px;
  margin-top: 2px;
}
.security-flow-step-desc {
  font-size: 13px;
  color: #888;
  margin-bottom: 0;
}
.security-flow-btn-wrap {
  display: flex;
  justify-content: center;
  margin-top: 48px;
}
.security-flow-btn {
  background: #7B3FA0;
  color: #fff;
  font-size: 18px;
  font-weight: 700;
  border: none;
  border-radius: 18px;
  padding: 10px 48px;
  cursor: pointer;
  box-shadow: 0 2px 8px #C18AD122;
  transition: background 0.18s;
}
.security-flow-btn:hover {
  background: #C18AD1;
}
@media (max-width: 900px) {
  .security-flow-steps {
    flex-direction: column;
    align-items: center;
    gap: 32px;
  }
  .security-flow-step {
    max-width: 100%;
    min-width: 0;
    width: 100%;
  }
}
</style>