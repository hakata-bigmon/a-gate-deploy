<?php 
  //ポストユーザー
    $user_id = get_current_user_id();

    //もし管理者等でチェックするユーザーがいるならここで変更する
    $get_url = CheckUserPageAdmin($user_id);

?>



    <div class="user-account-menu-area">
        
        <div class="user-account-menu-wrap">
        <a href="<?php echo getURLSetSlag("users/user-acount-edit"); echo $get_url["add"]; ?>" class="user-account-menu-btn">会員情報編集 &gt;</a>
        <a href="<?php echo getURLSetSlag("users/user-post-address"); echo $get_url["add"]; ?>" class="user-account-menu-btn">送付先登録/編集 &gt;</a>
        <a href="<?php echo getURLSetSlag("users/user-password-edit"); echo $get_url["add"]; ?>" class="user-account-menu-btn">パスワードの変更 &gt;</a>
        <a href="<?php echo getURLSetSlag("users/user-family-tree"); echo $get_url["add"]; ?>" class="user-account-menu-btn">家系図 &gt;</a>
        <a href="<?php echo getURLSetSlag("users/user-target-list"); echo $get_url["add"]; ?>" class="user-account-menu-btn">対象者設定 &gt;</a>
        <a href="<?php echo getURLSetSlag("users/user-security"); echo $get_url["add"]; ?>" class="user-account-menu-btn">セキュリティ &gt;</a>
        </div>

    </div>
