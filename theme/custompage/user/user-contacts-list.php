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


    if(isset($_POST["save_data"])){
      $save_id = $spiritContensQuestion->saveContacts($user_id,$_POST);
    }
  
    //お問い合わせ一覧
    $contacts_list = $spiritContensQuestion->getContactsList($user_id);


    //var_dump($_POST);
?>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<style>
/* 全体のコンテナ */
.user-top-area {
  width: 100%;
  max-width: 100%;
  overflow-x: hidden; /* 横スクロールを完全に防ぐ */
  box-sizing: border-box;
}

/* DataTablesのラッパーも制限 */
.dataTables_wrapper {
  width: 100%;
  max-width: 100%;
  overflow-x: hidden;
  box-sizing: border-box;
}

/* 既存のスタイルを以下に置き換え */
.contacts-table {
  width: 100%;
  table-layout: fixed;
  border-collapse: collapse;
  margin: 30px 0;
  font-family: 'Noto Sans JP', sans-serif;
  background: #fff;
  min-width: 0; /* 最小幅を0に設定してコンテナ内に収める */
}

/* テーブルのコンテナ */
.contacts-table-container {
  width: 100%;
  margin: 30px 0;
  box-sizing: border-box; /* パディングとボーダーを幅に含める */
}

.contacts-table th {
  background: #C18AD1;
  color: #fff;
  font-weight: 700;
  font-size: 15px;
  padding: 10px 4px; /* 左右のパディングを減らす */
  border: none;
  white-space: nowrap;
  box-sizing: border-box;
}

/* 各列の幅を画面サイズに対する割合で指定 */
.contacts-table th.col-id,
.contacts-table td.col-id {
  width: 10%; /* ID列 */
  min-width: 0;
}

.contacts-table th.col-date,
.contacts-table td.col-date {
  width: 15%; /* 日付列 */
  min-width: 0;
}

.contacts-table th.col-count,
.contacts-table td.col-count {
  width: 10%; /* 返信件数列 */
  min-width: 0;
}

.contacts-table th.col-detail,
.contacts-table td.col-detail {
  width: 15%; /* 詳細ボタン列 */
  min-width: 0;
}

.contacts-table th.contacts-title-col,
.contacts-table td.contacts-title-col {
  width: 50%; /* 件名列 */
  min-width: 0;
}

.contacts-table td {
  border-bottom: 1px solid #e9d6f2;
  font-size: 14px;
  color: #555;
  padding: 10px 4px; /* 左右のパディングを減らす */
  overflow: hidden;
  text-overflow: ellipsis; /* 長いテキストを省略記号で表示 */
  box-sizing: border-box;
}

/* ID列と返信件数列は中央揃え */
.contacts-table .col-id,
.contacts-table .col-count {
  text-align: center;
}

/* 日付列と詳細列は中央揃え */
.contacts-table .col-date,
.contacts-table .col-detail {
  text-align: center;
}

/* 日付列の文字サイズを調整 */
.contacts-table .col-date {
  font-size: 12px; /* さらに小さく */
}

.contacts-table .col-id {
  font-size: 13px;
}

.contacts-table .col-count {
  font-size: 13px;
}

.contacts-table tr:nth-child(even) td {
  background: #f8f3fa;
}

.contacts-table tr:hover td {
  background: #f3e6f8;
}

.contacts-table .contacts-detail-btn {
  background: #C18AD1;
  color: #fff;
  border: none;
  border-radius: 18px;
  padding: 4px 8px; /* パディングを小さく */
  font-size: 12px; /* フォントサイズを小さく */
  cursor: pointer;
  transition: background 0.18s;
  white-space: nowrap;
  width: 100%;
  max-width: 60px; /* ボタンの最大幅を制限 */
}

.contacts-table .contacts-detail-btn:hover {
  background: #a46bb3;
}

.dataTables_wrapper .dataTables_paginate .paginate_button {
  color: #C18AD1 !important;
}

.dataTables_wrapper .dataTables_filter input {
  border: 1px solid #C18AD1;
  border-radius: 8px;
  padding: 4px 8px;
}

.contacts-title-col {
  word-break: break-all;
  white-space: normal;
  overflow-wrap: break-word;
  font-weight: 600;
  /* max-widthを削除して、利用可能な幅を全て使用 */
}

.contacts-reply-count-sp {
  display: none;
  font-size: 12px;
  color: #a46bb3;
  font-weight: 400;
}

/* レスポンシブ対応 */
@media (max-width: 1000px) {
  .contacts-table-container, .contacts-table { display: none !important; }
  .contacts-list-cards {
    display: flex;
    flex-direction: column;
    gap: 18px;
    margin: 24px 0;
  }
  .contact-card {
    background: #fff;
    border: 1.5px solid #C18AD1;
    border-radius: 18px;
    padding: 18px 16px 16px 16px;
    box-shadow: 0 2px 8px rgba(193,138,209,0.08);
    font-family: 'Noto Sans JP', sans-serif;
  }
  .contact-card-title {
    font-size: 16px;
    font-weight: 700;
    color: #800A90;
    margin-bottom: 8px;
    word-break: break-all;
  }
  .contact-card-meta {
    font-size: 13px;
    color: #888;
    margin-bottom: 12px;
    display: flex;
    gap: 18px;
  }
  .contact-card-btn {
    background: #C18AD1;
    color: #fff;
    border: none;
    border-radius: 22px;
    padding: 10px 0;
    width: 100%;
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.18s;
  }
  .contact-card-btn:hover {
    background: #a46bb3;
  }
}
@media (min-width: 1001px) {
  .contacts-list-cards { display: none; }
}

/* 中程度の画面サイズでの対応 */
@media (max-width: 800px) {
  .contacts-table th.contacts-title-col,
  .contacts-table td.contacts-title-col {
    width: 70%;
  }
  
  .contacts-table th.col-detail,
  .contacts-table td.col-detail {
    width: 30%;
  }
  
  .contacts-table .contacts-detail-btn {
    padding: 3px 5px;
    font-size: 10px;
    max-width: 50px;
  }
}

/* 非常に小さい画面での追加対応 */
@media (max-width: 600px) {
  .contacts-table {
    font-size: 11px;
  }
  
  .contacts-table th,
  .contacts-table td {
    padding: 6px 1px; /* さらにパディングを減らす */
  }
  
  /* 小さい画面では件名列をさらに広く */
  .contacts-table th.contacts-title-col,
  .contacts-table td.contacts-title-col {
    width: 65%;
  }
  
  .contacts-table th.col-detail,
  .contacts-table td.col-detail {
    width: 35%;
  }
  
  .contacts-table .contacts-detail-btn {
    padding: 2px 4px;
    font-size: 9px;
    max-width: 45px;
    border-radius: 12px;
  }
  
  .contacts-table .col-date {
    font-size: 10px;
  }
  
  .contacts-title-col {
    font-size: 10px !important;
  }
}

.contacts-new-label {
  display: inline-block;
  background: red;
  color: #fff;
  font-weight: 700;
  font-size: 12px;
  border-radius: 14px;
  padding: 2px 12px 2px 12px;
  margin-right: 8px;
  box-shadow: 0 2px 6px rgba(193,138,209,0.13);
  letter-spacing: 1px;
  vertical-align: middle;
  border: none;
  transition: background 0.18s;
}
.contacts-new-label:hover {
  background: #a46bb3;
}
</style>
<script>
$(document).ready(function() {
  $('#contacts-table').DataTable({
    pageLength: 25,
    order: [[1, 'desc']], // 1列目（0始まり）→日付
    language: {
      url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/ja.json"
    },
    responsive: true, // レスポンシブ機能を有効化
    autoWidth: false, // 自動幅調整を無効化
    scrollX: false, // 横スクロールを無効化
    columnDefs: [
      {
        targets: '_all',
        className: 'dt-body-nowrap' // テキストの折り返しを制御
      }
    ]
  });
});
</script>

<div class="user-top-area">

    <div class="user-jorei-section-title-wrap">
        <div class="user-jorei-section-title">お問い合わせ一覧</div>
    </div>


    <?php if(count($contacts_list) > 0){?>
  <div class="contacts-table-container">
    <table id="contacts-table" class="contacts-table">
      <thead>
        <tr>
          <th class="col-id">ID</th>
          <th class="col-date">最新日時</th>
          <th class="contacts-title-col">件名</th>
          <th class="col-count">返信件数</th>
          <th class="col-detail">詳細</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach($contacts_list as $key => $value){ $contact_data = $spiritContensQuestion->getContactsDetail($value); ?>
        <tr>
          <td class="col-id"><?php echo $contact_data["問い合わせ番号"]; ?></td>
          <td class="col-date"><?php echo $contact_data["返信日付年月日"]; ?></td>
          <td class="contacts-title-col">
            <?php if($contact_data["質問者確認"] == "1"){ ?>
              <span class="contacts-new-label">new</span>
            <?php } ?>
            <?php echo $contact_data["件名"]; ?>
            <span class="contacts-reply-count-sp">（<?php echo count($contact_data["返信"]); ?>件）</span>
          </td>
          <td class="col-count"><?php echo count($contact_data["返信"]); ?>件</td>
          <td class="col-detail">
            <form action="<?php echo getURLSetSlag('users/user-contacts-detail') . $get_url['add']; ?>" method="post" style="display:inline;">
              <input type="hidden" name="contact_id" value="<?php echo $value; ?>">
              <button type="submit" class="contacts-detail-btn">詳細</button>
            </form>
          </td>
        </tr>
      <?php } ?>
      </tbody>
    </table>
  </div>
  <div class="contacts-list-cards">
    <?php foreach($contacts_list as $key => $value){ $contact_data = $spiritContensQuestion->getContactsDetail($value); ?>
      <div class="contact-card">
        <div class="contact-card-title"><?php echo $contact_data["件名"]; ?></div>
        <div class="contact-card-meta">
          <span class="contact-card-date">最新日時: <?php echo $contact_data["返信日付年月日"]; ?></span>
          <span class="contact-card-count"><?php echo count($contact_data["返信"]); ?>件</span>
        </div>
        <form action="<?php echo getURLSetSlag('users/user-contacts-detail') . $get_url['add']; ?>" method="post">
          <input type="hidden" name="contact_id" value="<?php echo $value; ?>">
          <button type="submit" class="contact-card-btn">詳細</button>
        </form>
      </div>
    <?php } ?>
  </div>
<?php }else{?>
  <div>お問い合わせはありません</div>
<?php }?>
     
    <div class="user-account-edit-form-btn-wrap" style="margin-top: 80px;margin-bottom: 100px;">
        <a href="<?php echo getURLSetSlag("users/user-contact-question"); echo $get_url["add"]; ?>" class="user-account-edit-return-btn">よくある質問に戻る  &gt;</a>
    </div>

    <div class="user-account-edit-form-btn-wrap" style="margin-top: 80px;margin-bottom: 100px;">
        <a href="<?php echo getURLSetSlag("users/user_top"); echo $get_url["add"]; ?>" class="user-account-edit-return-btn">TOPへ戻る  &gt;</a>
    </div>
 
</div>