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
    
    // 管理者フラグをJavaScriptで使用するため設定
    $is_admin = current_user_can('administrator');
?>

<div class="user-top-area">

    <div class="user-jorei-section-title-wrap">
        <div class="user-jorei-section-title">会員情報</div>
    </div>

    <?php include(dirname(__FILE__)."/user-acount-menu.php"); ?>

    <form id="password-change-form" method="POST" style="max-width: 500px;margin-left: auto;margin-right: auto;">
        <?php if(!current_user_can('administrator')){ ?>
        <div class="user-account-edit-wrap">
            <div class="user-account-edit-wrap-pc">
                <div class="user-account-edit-wrap-pc-flex">
                    <div class="user-account-edit-wrap-pc-title-flex">
                        <div class="user-account-edit-wrap-pc-title">既存のパスワード</div>
                        <div class="user-account-edit-wrap-sp-title">既存のパスワード</div>
                        <div class="user-account-edit-wrap-pc-required-area"></div>
                    </div>
                    <div class="user-account-edit-wrap-sp-flex">
                        <div class="user-account-edit-wrap-pc-content" style="width: 100%;">
                            <div class="admin-preview-button-flex-box">
                                <input type="password" class="user-account-edit-wrap-pc-text-one" name="current_password" id="current_password" required>
                                <span class="toggle-password" data-target="current_password" style=" top: 50%; transform: translateY(-50%); cursor: pointer;z-index: 1;">
                                    👁️
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>


        <div class="user-account-edit-wrap">
            <div class="user-account-edit-wrap-pc">
                <div class="user-account-edit-wrap-pc-flex">
                    <div class="user-account-edit-wrap-pc-title-flex">
                        <div class="user-account-edit-wrap-pc-title">新規パスワード</div>
                        <div class="user-account-edit-wrap-sp-title">新規パスワード</div>
                        <div class="user-account-edit-wrap-pc-required-area"></div>
                    </div>
                    <div class="user-account-edit-wrap-sp-flex">
                        <div class="user-account-edit-wrap-pc-content" style="width: 100%;">
                            <div class="admin-preview-button-flex-box">
                                <input type="password" class="user-account-edit-wrap-pc-text-one" name="new_password" id="new_password" required>
                                <span class="toggle-password" data-target="new_password" style=" top: 50%; transform: translateY(-50%); cursor: pointer;z-index: 1;">
                                    👁️
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="user-account-edit-wrap">
            <div class="user-account-edit-wrap-pc">
                <div class="user-account-edit-wrap-pc-flex">
                    <div class="user-account-edit-wrap-pc-title-flex">
                        <div class="user-account-edit-wrap-pc-title">新規パスワード確認</div>
                        <div class="user-account-edit-wrap-sp-title">新規パスワード確認</div>
                        <div class="user-account-edit-wrap-pc-required-area"></div>
                    </div>
                    <div class="user-account-edit-wrap-sp-flex">
                        <div class="user-account-edit-wrap-pc-content" style="width: 100%;">
                            <div class="admin-preview-button-flex-box">
                                <input type="password" class="user-account-edit-wrap-pc-text-one" name="confirm_password" id="confirm_password" required>
                                <span class="toggle-password" data-target="confirm_password" style=" top: 50%; transform: translateY(-50%); cursor: pointer;z-index: 1;">
                                    👁️
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div>   
            <p id="password_error_message" style="color: red; display: none;">新しいパスワードと確認用パスワードが一致しません</p>
        </div>

        <?php if(current_user_can('administrator') ){?>
            <input type="hidden" name="target_user_id" id="target_user_id" value="<?php echo $user_id;?>">
        <?php }else{ ?>
            <input type="hidden" name="target_user_id" id="target_user_id" value="">
        <?php } ?>
        <div class="user-account-edit-form-btn-wrap">
            <button type="button" id="submit_password_button" class="user-account-edit-form-btn">パスワードを変更する</button>
        </div>
    
    </form>

    <div class="user-account-edit-form-btn-wrap">
        <a href="<?php echo getURLSetSlag("users/user-acount"); echo $get_url["add"]; ?>" class="user-account-edit-return-btn">会員情報へ戻る  &gt;</a>
    </div>


    <div class="user-account-edit-form-btn-wrap" style="margin-top: 80px;margin-bottom: 100px;">
        <a href="<?php echo getURLSetSlag("users/user_top"); echo $get_url["add"]; ?>" class="user-account-edit-return-btn">TOPへ戻る  &gt;</a>
    </div>
</div>

<!-- ポップアップのためのモーダル -->
<div id="message_modal" style="display:none; position:fixed; top:50%; left:50%; transform:translate(-50%, -50%);
    padding: 20px; background: white; border: 1px solid #ccc; box-shadow: 0 0 10px rgba(0, 0, 0, 0.5); z-index: 1000;">
    <p id="modal_message"></p>
    <button onclick="document.getElementById('message_modal').style.display='none';">閉じる</button>
</div>


<script>
   document.getElementById('submit_password_button').addEventListener('click', function() {
    const targetUserId = document.getElementById('target_user_id').value;
    const currentPasswordElement = document.getElementById('current_password');
    const currentPassword = currentPasswordElement ? currentPasswordElement.value.trim() : '';
    const newPassword = document.getElementById('new_password').value.trim();
    const confirmPassword = document.getElementById('confirm_password').value.trim();
    const isAdmin = <?php echo $is_admin ? 'true' : 'false'; ?>;

    // 未入力チェック（フロント側）
    
    console.log('currentPasswordElement:', currentPasswordElement);
    console.log('currentPassword:', currentPassword);
    
    // 管理者でない場合のみ既存パスワードをチェック
    console.log('isAdmin:', isAdmin);
    if (!isAdmin && currentPassword === '') {
        alert('現在のパスワードを入力してください。');
        return;
    }
    if (newPassword === '' || confirmPassword === '') {
        alert('新しいパスワードを入力してください。');
        return;
    }

    // パスワード一致の確認
    if (newPassword !== confirmPassword) {
        document.getElementById('password_error_message').style.display = 'block';
        return;
    } else {
        document.getElementById('password_error_message').style.display = 'none';
    }

    // AJAX送信
    const formData = new FormData();
    formData.append('action', 'handle_password_change');
    formData.append('target_user_id', targetUserId);
    formData.append('current_password', currentPassword);
    formData.append('new_password', newPassword);
    formData.append('confirm_password', confirmPassword);

    fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        showModal(data.message);
    })
    .catch(error => {
        showModal('エラーが発生しました。もう一度お試しください。');
        console.error('エラー:', error);
    });
});
function showModal(message) {
    document.getElementById('modal_message').innerText = message;
    document.getElementById('message_modal').style.display = 'block';
}

document.getElementById('confirm_password').addEventListener('paste', function(e) {
    e.preventDefault();
    alert('確認用パスワードには手動で入力してください。');
});
    </script>


<script>
    document.querySelectorAll('.toggle-password').forEach(function(toggle) {
        toggle.addEventListener('mousedown', function() {
            const targetInput = document.getElementById(this.getAttribute('data-target'));
            targetInput.setAttribute('type', 'text'); // パスワードを表示
        });

        toggle.addEventListener('mouseup', function() {
            const targetInput = document.getElementById(this.getAttribute('data-target'));
            targetInput.setAttribute('type', 'password'); // パスワードを再び非表示
        });

        toggle.addEventListener('mouseleave', function() {
            const targetInput = document.getElementById(this.getAttribute('data-target'));
            targetInput.setAttribute('type', 'password'); // ホールドから外れたら非表示
        });
    });
</script>