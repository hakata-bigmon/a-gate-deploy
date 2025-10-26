// 保存確認用
function save_check() {

	if (window.confirm('保存しますか？')) { // 確認ダイアログを表示

		return true; // 「OK」時は送信を実行

	}
	else { // 「キャンセル」時の処理

		window.alert('キャンセルされました'); // 警告ダイアログを表示
		return false; // 送信を中止

	}

}


//テーブル↑移動
function moveUp(btn) {
    const row = btn.closest('tr');
    const prevRow = row.previousElementSibling;
    if (prevRow && prevRow.tagName === 'TR') {
        row.parentNode.insertBefore(row, prevRow);
        updateRowColors();
    }
}
//テーブル↓移動
function moveDown(btn) {
    const row = btn.closest('tr');
    const nextRow = row.nextElementSibling;
    if (nextRow && nextRow.tagName === 'TR') {
        row.parentNode.insertBefore(nextRow, row);
        updateRowColors();
    }
}
//テーブルが移動しても色を入れなおす
function updateRowColors() {
    const rows = document.querySelectorAll('#adminSpiritEditTableID tbody tr');
    rows.forEach((row, index) => {
        if (index % 2 === 0) {
            row.classList.add('admin-spirit-edit-row');
        } else {
            row.classList.remove('admin-spirit-edit-row');
        }
    });
}
//並び順ソート
function saveOrder() {

    if (confirm("並び順をソートしても宜しいですか?")) {
        const rows = document.querySelectorAll('#adminSpiritEditTableID tbody tr');
        const order = Array.from(rows).map(row => row.getAttribute('data-id'));
        document.getElementById('orderInput').value = order.join(',');
        document.getElementById('orderForm').submit();
    }
}

//編集保存
function saveItemData() {

    if (confirm("このデータで保存しても宜しいですか?")) {
        
        document.getElementById('edit_item').submit();
    }
}


//新規登録
function saveItemAddData() {

    if (confirm("このデータで登録しても宜しいですか?")) {

        document.getElementById('add_item').submit();
    }
}

//削除
function saveItemDeleteData( id,formtitle ) {

    if (confirm("「 " + formtitle + "」\n\nのデータを削除しても宜しいですか?")) {

        document.getElementById('delete_item_' + id).submit();
    }
}




// 初期ロード時の色設定
document.addEventListener('DOMContentLoaded', (event) => {
    updateRowColors();
});

//削除確認用

function delete_check_modal(form_name) {

	var js_moji = "選択した項目を削除しますか？\r削除する場合は『はい』を\r削除しない場合は『戻る』を \r 押してください。";;

	
	click_modal(js_moji, form_name);
}




//ユーザー入力
function userInputFormData() {

    document.getElementById('orderForm').submit();
}