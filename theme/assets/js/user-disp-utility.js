document.addEventListener("DOMContentLoaded", function () {
    let selectedFiles = {};  // 🔹 新規追加した画像
    let initialFiles = {};  // 🔹 初期画像（サーバーから取得）

    function updateFileInput(input) {
        let dataTransfer = new DataTransfer();
        const inputName = input.name;

        // **初期画像URL情報（削除されていないもの）を保持するためのhidden inputを更新**
        const existingImagesContainer = document.getElementById(`existingImages${input.id.replace('imageInput', '')}`);
        if (existingImagesContainer) {
            existingImagesContainer.innerHTML = ''; // コンテナをクリア
            (initialFiles[inputName] || []).forEach(url => {
                if (url) { // urlがnullでない（削除されていない）場合
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    // サーバー側で既存の画像と新しいアップロードを区別しやすいように名前を変更
                    hiddenInput.name = `existing_images_${input.id.replace('imageInput', '')}[]`;
                    hiddenInput.value = url;
                    existingImagesContainer.appendChild(hiddenInput);
                }
            });
        }

        // **ユーザーが新規追加した画像のみをDataTransferにセット**
        (selectedFiles[inputName] || []).forEach(file => {
            if (file instanceof File) {
                dataTransfer.items.add(file);
            }
        });

        // input.filesを更新
        input.files = dataTransfer.files;
    }

    function createPreviewImage(src, index, input, isInitial) {
        const inputName = input.name;
        const previewContainer = document.getElementById(`imagePreview${input.id.replace('imageInput', '')}`);
        if (!previewContainer) return;

        let imgDiv = document.createElement("div");
        imgDiv.classList.add("preview-container");

        let img = document.createElement("img");
        img.src = src;
        img.classList.add("preview-img");

        let removeBtn = document.createElement("button");
        removeBtn.textContent = "×";
        removeBtn.classList.add("remove-btn");
        removeBtn.type = "button"; // submit防止

        removeBtn.onclick = function () {
            if (isInitial) {
                // 既存の画像をnullにして削除マーク
                initialFiles[inputName][index] = null;
            } else {
                // 新規追加した画像を配列から削除
                selectedFiles[inputName].splice(index, 1);
            }
            // プレビューとinputの状態を更新
            updatePreview(input);
            updateFileInput(input);
        };

        imgDiv.appendChild(img);
        imgDiv.appendChild(removeBtn);
        previewContainer.appendChild(imgDiv);
    }

    function updatePreview(input) {
        const inputName = input.name;
        const previewContainer = document.getElementById(`imagePreview${input.id.replace('imageInput', '')}`);
        if (!previewContainer) return;

        previewContainer.innerHTML = ""; // プレビューをクリア

        // サーバーからの初期画像を表示
        (initialFiles[inputName] || []).forEach((url, index) => {
            if (url) { // nullでない（削除されていない）画像のみ表示
                createPreviewImage(url, index, input, true);
            }
        });

        // ユーザーが選択した新規画像を表示
        (selectedFiles[inputName] || []).forEach((file, index) => {
            let reader = new FileReader();
            reader.onload = function (e) {
                createPreviewImage(e.target.result, index, input, false);
            };
            reader.readAsDataURL(file);
        });
    }

    window.initImageUpload = function (savedImages, rquestionId) {
        const inputId = `imageInput${rquestionId}`;
        const input = document.getElementById(inputId);
        if (!input) {
            console.error(`Input element with id '${inputId}' not found.`);
            return;
        }

        const inputName = input.name;

        // 既存画像のURLを保持するためのコンテナ（なければ作成）
        let existingImagesContainer = document.getElementById(`existingImages${rquestionId}`);
        if (!existingImagesContainer) {
            existingImagesContainer = document.createElement('div');
            existingImagesContainer.id = `existingImages${rquestionId}`;
            // inputの直後にコンテナを挿入
            input.parentNode.insertBefore(existingImagesContainer, input.nextSibling);
        }

        // 状態を初期化
        initialFiles[inputName] = [...savedImages];
        selectedFiles[inputName] = [];

        // 初期プレビューとhidden inputを生成
        updatePreview(input);
        updateFileInput(input);

        // ファイルが選択されたときのイベントリスナー
        input.addEventListener('change', function () {
            // 新しく選択されたファイルをselectedFilesに追加
            // 古いブラウザも考慮し、concatの代わりにループを使用
            const newFiles = [];
            for (let i = 0; i < this.files.length; i++) {
                newFiles.push(this.files[i]);
            }
            selectedFiles[inputName] = (selectedFiles[inputName] || []).concat(newFiles);

            // プレビューとinputの状態を更新
            updatePreview(this);
            updateFileInput(this);
        });
    };
});
