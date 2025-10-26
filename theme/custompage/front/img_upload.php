
<?php 

    require_once (dirname(__FILE__)."/../../custompage/admin/a-gate-functions.php");
    require_once (dirname(__FILE__)."/../../class/spiritInputCustomizeClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritMailPostClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritSheetClass.php");

    $spirit_customize_data = new SpiritInputCustomizeClass(); //管理データ
    $spirit_sheet_data = new spiritSheetClass(); //質問データ
    $mail_post_data = new SpiritMailPostClass(); //管理データ
    

 ?>
 

 <style>
    .image-container {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 20px;
    }

    .image-box {
        position: relative;
        display: inline-block;
    }

    .image-box img {
        max-width: 150px;
        max-height: 150px;
        object-fit: cover;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    .image-box button {
        position: absolute;
        top: 5px;
        right: 5px;
        background-color: red;
        color: white;
        border: none;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        font-size: 12px;
        cursor: pointer;
    }

    .file-list {
        margin-top: 10px;
    }

    .file-item {
        display: flex;
        align-items: center;
        margin-bottom: 5px;
    }

    .file-item .delete-button {
        margin-left: 10px;
        background-color: red;
        color: white;
        border: none;
        padding: 3px 6px;
        cursor: pointer;
    }

    #fileCountDisplay {
        margin-left: 10px;
        font-weight: bold;
        color: #333;
    }
</style>

<div class="input-form-area">
    <div class="input-form-contens">
        <div class="input-form-header-img">
            <div class="input-img-upload-box">
                <div class="input-img-upload-text">画像アップロード </div>
            </div>
        </div>

        <div class="input-form-main">
            <div class="input-img-upload-message">
                認証コードと画像をアップロードしてください。<br><br>
                認証コードを間違うと違うユーザーとしてアップロードされてしまうのでご注意ください。<br>
                認証コードがわからない場合は、saito-masako@earth-a-gate.com までご連絡ください。<br>
            </div>

            <div class="orderform-remote-question-area">
                <div class="input-img-upload-form-area">
                    <form id="uploadForm" method="POST" enctype="multipart/form-data" action="<?php echo admin_url('admin-post.php'); ?>">
                        <div class="input-img-upload-form-text">
                            <div class="input-img-upload-form-box">
                                <div class="input-img-upload-form--label">認証コード</div>
                                <div class="orderform-remote-question-target-execution-date">
                                    <input type="number" name="img_register_code" value="" style="">
                                </div>
                            </div>
                        </div>

                        <div class="input-img-upload-form-text">
                            <div class="input-img-upload-form-box">
                                <div class="input-img-upload-form--label">お名前</div>
                                <div class="orderform-remote-question-target-execution-date">
                                    <input type="text" name="img_register_name" value="" style="" required>
                                </div>
                            </div>
                        </div>

                        <div class="input-img-upload-form-text">
                            <div class="input-img-upload-form-box">
                                <div class="input-img-upload-form--label">画像</div>
                                <div class="orderform-remote-question-target-execution-date">
                                    <input type="file" id="fileInput" name="uploaded_files[]" multiple accept="image/*">
                                </div>
                            </div>
                        </div>

                        <div class="input-img-upload-img-area">
                            <div style="margin-top: 30px;"><span id="fileCountDisplay">選択されたファイル数: 0</span></div>
                            <div id="fileListContainer" class="file-list"></div>
                        </div>

                        <input type="hidden" name="action" value="upload_files">
                        <button type="submit" class="input-img-upload-button">アップロード</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>



<script>
let selectedFiles = []; // 選択されたファイルを保持

document.getElementById('fileInput').addEventListener('change', function (event) {
    const files = Array.from(event.target.files);

    // 新しく選択されたファイルを`selectedFiles`に追加
    files.forEach(file => {
        selectedFiles.push(file);
    });

    renderFileList(); // ファイルリストを再描画
});

function renderFileList() {
    const fileListContainer = document.getElementById('fileListContainer');
    const fileCountDisplay = document.getElementById('fileCountDisplay'); // 合計ファイル数の要素
    fileListContainer.innerHTML = ''; // リストをクリア

    selectedFiles.forEach((file, index) => {
        const listItem = document.createElement('div');
        listItem.className = 'image-box';

        // プレビュー画像表示
        const reader = new FileReader();
        reader.onload = function (e) {
            const img = document.createElement('img');
            img.src = e.target.result;
            listItem.appendChild(img);
        };
        reader.readAsDataURL(file);

        // 削除ボタン
        const deleteButton = document.createElement('button');
        deleteButton.textContent = '×';
        deleteButton.className = 'delete-button';
        deleteButton.addEventListener('click', function () {
            selectedFiles.splice(index, 1); // 選択されたファイルを削除
            renderFileList(); // リストを再描画
        });

        listItem.appendChild(deleteButton);
        fileListContainer.appendChild(listItem);
    });

    // 合計ファイル数を更新
    fileCountDisplay.textContent = `選択されたファイル数: ${selectedFiles.length}`;
}

// フォーム送信時に現在の`selectedFiles`だけを送信


</script>
