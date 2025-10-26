<?php 

   require_once (dirname(__FILE__)."/../../custompage/admin/a-gate-functions.php");
   require_once (dirname(__FILE__)."/../../class/spiritUserClass.php");


    //ポストユーザー
    $user_id = get_current_user_id();

    //もし管理者等でチェックするユーザーがいるならここで変更する
    $get_url = CheckUserPageAdmin($user_id);

    $userClass = new SpiritUserClass(); //ユーザー管理

    $userData = $userClass->getUserAcountData($user_id);



    $target_user_id = $user_id;
    //var_dump($_POST);
?>

<style>
/* 追加: デザイン調整用CSS */
.family-tree-upload-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-top: 40px;
}
.family-tree-description {
    text-align: center;
    margin-bottom: 32px;
    color: #555;
    font-size: 15px;
    line-height: 1.7;
    margin-top: 50px;
}
.upload-icon {
    font-size: 48px;
    color: #b388dd;
    margin-bottom: 8px;
    margin-top: 50px;
    cursor: pointer;
}
.file-select-link {
    color: #b388dd;
    text-decoration: underline;
    cursor: pointer;
    font-size: 16px;
    margin-bottom: 32px;
    display: inline-block;
}
.upload-btn-purple {
    background: #7c3aed;
    color: #fff;
    border: none;
    border-radius: 20px;
    padding: 10px 48px;
    font-size: 16px;
    margin: 24px 0 32px 0;
    cursor: pointer;
    display: block;
    margin-left: auto;
    margin-right: auto;
}
.top-link-btn {
    border: 1px solid #b388dd;
    color: #b388dd;
    background: #fff;
    border-radius: 20px;
    padding: 8px 40px;
    font-size: 15px;
    text-align: center;
    margin: 0 auto;
    display: block;
    cursor: pointer;
    margin-bottom: 40px;
}
#uploaded-image-wrapper {
    margin-top: 24px;
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
}
#uploaded-image {
    display: none;
    width: 300px;
    height: auto;
    margin-top: 16px;
    margin-bottom: 8px;
}
#delete-image-button {
    display: none;
    background: #e57373;
    color: #fff;
    border: none;
    border-radius: 16px;
    padding: 6px 18px;
    font-size: 15px;
    cursor: pointer;
    margin-top: 4px;
    transition: background 0.2s;
}
#delete-image-button:hover {
    background: #c62828;
}
.family-tree-description,
.file-select-link,
.upload-btn-purple,
.top-link-btn {
    font-family: 'Noto Sans JP', 'ヒラギノ角ゴ ProN', 'Hiragino Kaku Gothic ProN', 'メイリオ', Meiryo, sans-serif;
    font-weight: 500;
}
</style>

<div class="user-top-area">

    <div class="user-jorei-section-title-wrap">
        <div class="user-jorei-section-title">家系図</div>
    </div>

    <?php include(dirname(__FILE__)."/user-acount-menu.php"); ?>



</div>


<div class="family-tree-upload-container">
    <div class="family-tree-description">
        家系図のわかる、写真画像やPDFなどをアップロードしてください。<br>
        『ファイル選択』から該当データを選択し、<br>
        下記の『アップロード』をクリックもしくはタップをしてください。
    </div>
    <div class="upload-icon">
        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" viewBox="0 0 24 24"><path d="M12 16a1 1 0 0 1-1-1V7.83l-2.59 2.58a1 1 0 1 1-1.41-1.41l4.3-4.29a1 1 0 0 1 1.41 0l4.29 4.29a1 1 0 1 1-1.41 1.41L13 7.83V15a1 1 0 0 1-1 1zm-7 2a1 1 0 0 1 1-1h14a1 1 0 1 1 0 2H6a1 1 0 0 1-1-1z"/></svg>
    </div>
    <label class="file-select-link">①.ファイル選択</label>
    <form id="image-upload-form" enctype="multipart/form-data" style="text-align:center;">
        <input type="file" id="user-image-input" name="user_image" accept="image/*,application/pdf" style="display:none;" />
        <p id="selected-file-name" style="display:none; margin-bottom:0;">選択したファイル: <span></span></p>
        <div id="uploaded-image-wrapper">
            <img id="uploaded-image" src="" alt="選択された画像" style="display:none; width: 300px; height: auto; margin-top:16px;" />
            <button id="delete-image-button" style="display:none;">× 削除</button>
        </div>
        <button type="submit" class="upload-btn-purple">②.アップロード &gt;</button>
    </form>
    <div class="user-account-edit-form-btn-wrap" style="margin-top: 80px;margin-bottom: 100px;">
        <a href="<?php echo getURLSetSlag("users/user_top"); echo $get_url["add"]; ?>" class="user-account-edit-return-btn">TOPへ戻る  &gt;</a>
    </div>    
    <div id="message-popup" style="display:none; position:fixed; top:50%; left:50%; transform:translate(-50%, -50%); padding:20px; background-color:white; border:1px solid black;"></div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const uploadForm = document.getElementById('image-upload-form');
    const userImageInput = document.getElementById('user-image-input');
    const uploadedImage = document.getElementById('uploaded-image');
    const deleteImageButton = document.getElementById('delete-image-button');
    const messagePopup = document.getElementById('message-popup');
    const selectedFileName = document.getElementById('selected-file-name');
    const fileNameSpan = selectedFileName.querySelector('span');

    // 固定のユーザーIDをPHPから受け取る
    const targetUserId = <?php echo $target_user_id; ?>;

    function showPopupMessage(message) {
        messagePopup.innerText = message;
        messagePopup.style.display = 'block';
        setTimeout(() => { messagePopup.style.display = 'none'; }, 3000);
    }

    // 対象ユーザーの画像を取得して表示
    function loadUserImage() {
        fetch('<?php echo admin_url('admin-ajax.php'); ?>?action=get_user_image&target_user_id=' + targetUserId)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    uploadedImage.src = data.data.url;
                    uploadedImage.style.display = 'block';
                    deleteImageButton.style.display = 'block';
                } else {
                    uploadedImage.style.display = 'none';
                    deleteImageButton.style.display = 'none';
                }
            });
    }

    // 初期表示で保存された画像を表示
    loadUserImage();

    // 画像選択時にプレビュー表示とファイル名を表示
    userImageInput.addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                uploadedImage.src = e.target.result;
                uploadedImage.style.display = 'block';
                deleteImageButton.style.display = 'block';
                selectedFileName.style.display = 'block';
                fileNameSpan.textContent = file.name;
            };
            reader.readAsDataURL(file);
        }
    });

    // 画像アップロード処理
    uploadForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(uploadForm);
        formData.append('target_user_id', targetUserId);

        fetch('<?php echo admin_url('admin-ajax.php'); ?>?action=upload_user_image', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                uploadedImage.src = data.data.url;
                deleteImageButton.style.display = 'block';
                showPopupMessage(data.data.message);
            } else {
                showPopupMessage(data.data.message);
            }
        })
        .catch(() => {
            showPopupMessage('エラーが発生しました。');
        });
    });

    // 画像削除処理
    deleteImageButton.addEventListener('click', function() {
        if (confirm('本当に画像を削除しますか？')) {
            fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                method: 'POST',
                body: new URLSearchParams({
                    action: 'delete_user_image',
                    target_user_id: targetUserId
                })
            })
            .then(response => response.json())
            .then(data => {
                // 成功・失敗に関わらずUIをリセット
                uploadedImage.style.display = 'none';
                deleteImageButton.style.display = 'none';
                selectedFileName.style.display = 'none';
                showPopupMessage(data.data ? data.data.message : (data.message || '削除処理が完了しました。'));
            });
        }
    });

    // ファイル選択リンクとアイコンでinputを開く
    const fileSelectLink = document.querySelector('.file-select-link');
    const uploadIcon = document.querySelector('.upload-icon');
    if(fileSelectLink && userImageInput){
        fileSelectLink.addEventListener('click', function(e){
            e.preventDefault();
            e.stopPropagation();
            userImageInput.click();
        });
    }
    if(uploadIcon && userImageInput){
        uploadIcon.addEventListener('click', function(e){
            e.preventDefault();
            e.stopPropagation();
            userImageInput.click();
        });
    }
});
</script>
