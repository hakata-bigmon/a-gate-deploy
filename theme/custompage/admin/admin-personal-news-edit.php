<?php

    require_once (dirname(__FILE__)."/../../class/spiritSheetClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritUserClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritQuestionDispClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritInputCustomizeClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritScheduleClass.php");
    require_once (dirname(__FILE__)."/../../class/mailTextClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritNewsClass.php");

    require_once ("a-gate-functions.php");

    // 流入データ
    $spirit_sheet_data = new spiritSheetClass(); //管理データ

    //ユーザークラス
    $userClass = new SpiritUserClass(); //ユーザー管理
    //表示データ
    $disp_questiont_class = new SpiritQuestionDispClass(); //表示データ
    //個人データ
    $spirit_customize_data = new SpiritInputCustomizeClass(); //文字データ

    $mailText = new MailTextClass();
    $newsClass = new SpiritNewsClass();

    

    $user_id = $_GET["user_id"];
    $sheet_name = $_GET["sheet_name"];

    $user_data = get_userdata($user_id);



?>



<style>
.personal-news-container {
    max-width: 1000px;
    margin-left: auto;
    margin-right: auto;
    margin-top: 50px;
    padding: 0 20px;
}

.personal-news-form {
    background: #ffffff;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    padding: 30px;
    margin-top: 20px;
}

.personal-news-field {
    margin-bottom: 25px;
}

.personal-news-label {
    font-weight: 600;
    font-size: 14px;
    color: #333;
    margin-bottom: 8px;
    display: block;
}

.personal-news-recipient {
    background-color: #f8f9fa;
    padding: 12px 15px;
    border-radius: 4px;
    border-left: 4px solid #17a2b8;
    font-size: 14px;
    color: #495057;
}

.personal-news-input {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
    transition: border-color 0.3s;
    box-sizing: border-box;
}

.personal-news-input:focus {
    outline: none;
    border-color: #17a2b8;
    box-shadow: 0 0 0 3px rgba(23, 162, 184, 0.1);
}

.personal-news-textarea {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
    font-family: inherit;
    resize: vertical;
    min-height: 300px;
    transition: border-color 0.3s;
    box-sizing: border-box;
}

.personal-news-textarea:focus {
    outline: none;
    border-color: #17a2b8;
    box-shadow: 0 0 0 3px rgba(23, 162, 184, 0.1);
}

.personal-news-submit-area {
    text-align: center;
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid #e9ecef;
}

.personal-news-submit-btn {
    background-color: #17a2b8;
    color: white;
    border: none;
    padding: 12px 40px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    font-weight: 600;
    transition: background-color 0.3s, transform 0.1s;
    min-width: 200px;
}

.personal-news-submit-btn:hover {
    background-color: #138496;
    transform: translateY(-1px);
}

.personal-news-submit-btn:active {
    transform: translateY(0);
}

.personal-news-info {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 15px 20px;
    border-radius: 8px;
    margin-top: 20px;
    margin-bottom: 20px;
    box-shadow: 0 4px 6px rgba(102, 126, 234, 0.3);
    display: flex;
    align-items: center;
    gap: 12px;
}

.personal-news-info::before {
    content: "ℹ️";
    font-size: 20px;
}

.personal-news-info-text {
    font-size: 14px;
    font-weight: 500;
    line-height: 1.5;
}

/* 確認モーダル */
#confirmModal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 10000;
    justify-content: center;
    align-items: center;
}

#confirmModalBox {
    background-color: white;
    border-radius: 10px;
    padding: 30px;
    max-width: 500px;
    width: 90%;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
    animation: modalSlideIn 0.3s ease-out;
}

@keyframes modalSlideIn {
    from {
        transform: translateY(-50px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

#confirmModalTitle {
    font-size: 20px;
    font-weight: bold;
    margin-bottom: 15px;
    color: #333;
    text-align: center;
}

#confirmModalMessage {
    font-size: 16px;
    margin-bottom: 25px;
    color: #555;
    text-align: center;
    line-height: 1.6;
}

#confirmModalButtons {
    display: flex;
    justify-content: center;
    gap: 15px;
}

.modal-btn {
    padding: 12px 30px;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
}

.modal-btn-confirm {
    background-color: #17a2b8;
    color: white;
}

.modal-btn-confirm:hover {
    background-color: #138496;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(23, 162, 184, 0.3);
}

.modal-btn-cancel {
    background-color: #6c757d;
    color: white;
}

.modal-btn-cancel:hover {
    background-color: #5a6268;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(108, 117, 125, 0.3);
}
</style>

<div class="personal-news-container">

    <div class="admin-spirit-title-box">
        <div class="admin-spirit-menu-title"><?php echo $user_data->last_name . " " . $user_data->first_name;?> お知らせ＆メール作成</div>
    </div>

    <div class="personal-news-info">
        <span class="personal-news-info-text">この入力した内容はメールとお知らせに会員に届きます</span>
    </div>

    <form action="<?php echo getURLSetSlag("admin-news-list"); ?>" method="post" class="personal-news-form" id="personalNewsForm">

        <input type="hidden" name="user_id" value="<?php echo $user_id;?>">
        <input type="hidden" name="sheet_name" value="<?php echo $sheet_name;?>">
        <input type="hidden" name="news_type" value="personal">
        <input type="hidden" name="update_unixtime" value="<?php echo time();?>">

        <div class="personal-news-field">
            <label class="personal-news-label">送信先</label>
            <div class="personal-news-recipient">
                <?php echo $user_data->last_name . " " . $user_data->first_name;?> (<?php echo $user_data->user_email;?>)
            </div>
        </div>

        <div class="personal-news-field">
            <label class="personal-news-label">タイトル <span style="color: #dc3545;">*</span></label>
            <input type="text" name="news_title" value="" class="personal-news-input" required placeholder="お知らせのタイトルを入力してください">
        </div>

        <div class="personal-news-field">
            <label class="personal-news-label">本文 <span style="color: #dc3545;">*</span></label>
            <textarea name="news_body" class="personal-news-textarea" required placeholder="お知らせの本文を入力してください"></textarea>
        </div>

        <hr>
        <div style="text-align: center;font-size: 24px;font-weight: bold;margin-bottom: 20px;margin-top: 30px;">A-GATE 運営 </div>

        <div class="personal-news-submit-area">
            <button type="button" onclick="showConfirmModal()" class="personal-news-submit-btn">送信する</button>
        </div>
    </form>

</div>

<!-- 確認モーダル -->
<div id="confirmModal">
    <div id="confirmModalBox">
        <div id="confirmModalTitle">送信確認</div>
        <div id="confirmModalMessage">
            この内容でメールとお知らせを送信してもよろしいですか？
        </div>
        <div id="confirmModalButtons">
            <button type="button" class="modal-btn modal-btn-cancel" onclick="closeConfirmModal()">キャンセル</button>
            <button type="button" class="modal-btn modal-btn-confirm" onclick="submitForm()">送信する</button>
        </div>
    </div>
</div>

<script>
function showConfirmModal() {
    const form = document.getElementById('personalNewsForm');
    
    // HTML5バリデーションチェック
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    
    // モーダルを表示
    const modal = document.getElementById('confirmModal');
    modal.style.display = 'flex';
}

function closeConfirmModal() {
    const modal = document.getElementById('confirmModal');
    modal.style.display = 'none';
}

function submitForm() {
    const form = document.getElementById('personalNewsForm');
    form.submit();
}

// モーダル外クリックで閉じる
document.getElementById('confirmModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeConfirmModal();
    }
});

// Escキーで閉じる
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeConfirmModal();
    }
});
</script>