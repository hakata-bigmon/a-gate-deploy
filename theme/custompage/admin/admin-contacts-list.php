<?php 

    require_once ("a-gate-functions.php");
    require_once (dirname(__FILE__)."/../../class/spiritContensQuestionClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritUserClass.php");


    $spiritContensQuestion = new SpiritContensQuestionClass();
    $spiritUser = new SpiritUserClass();

    //削除
    if(isset($_POST["delete"])){
        $question_id = $_POST["contact_id"];
        wp_delete_post($question_id);
    }


//お問い合わせ一覧
    $contacts_list = $spiritContensQuestion->getContactsList("");


    $contacts_unanswered_question = $spiritContensQuestion->getContactsUnansweredQuestion();
		$contacts_unanswered_question_count = count($contacts_unanswered_question);
?>

<head>
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
</head>

<style>
.admin-user-table-area {
  max-width: 1000px;
    background: #fff;
    border-radius: 16px;
    font-family: 'Noto Sans JP', sans-serif;
    margin-left: 10px;
    margin-right: 10px;
}
.admin-title {

}
.admin-form-btn {
  background: #1976d2;
  color: #fff;
  border: none;
  border-radius: 20px;
  padding: 8px 36px;
  font-size: 15px;
  margin-bottom: 24px;
  cursor: pointer;
  display: block;
  font-family: 'Noto Sans JP', sans-serif;
  font-weight: 500;
  transition: background 0.18s;
  box-shadow: 0 2px 8px #1976d222;
}
.admin-form-btn:hover {
  background: #1565c0;
}
.admin-table-search {
  width: 300px;
  padding: 8px 12px;
  border: 1.5px solid #1976d2;
  border-radius: 10px;
  font-size: 15px;
  margin-bottom: 18px;
  font-family: 'Noto Sans JP', sans-serif;
  background: #e3f2fd;
  color: #1565c0;
}
.admin-table {
  width: 100%;
  border-collapse: collapse;
  background: #fff;
  margin-top: 10px;
}
.admin-table th, .admin-table td {
  border: 1px solid #1976d2;
  padding: 10px 12px;
  text-align: left;
  font-size: 15px;
}
.admin-table th {
  background: #e3f2fd;
  color: #1976d2;
  cursor: pointer;
  user-select: none;
  position: relative;
}
.admin-table th.sort-asc::after {
  content: '▲';
  position: absolute;
  right: 8px;
  font-size: 12px;
}
.admin-table th.sort-desc::after {
  content: '▼';
  position: absolute;
  right: 8px;
  font-size: 12px;
}
.admin-table tr:nth-child(even) {
  background: #f5fafd;
}
.admin-table tr:hover {
  background: #e3f2fd;
}
.admin-table-edit-btn {
  background: #1976d2;
  color: #fff;
  border: none;
  border-radius: 14px;
  padding: 6px 22px;
  font-size: 15px;
  font-weight: 500;
  cursor: pointer;
  transition: background 0.18s;
}
.admin-table-edit-btn:hover {
  background: #1565c0;
}
.admin-table-delete-btn {
  background: #e57373;
  color: #fff;
  border: none;
  border-radius: 14px;
  padding: 6px 22px;
  font-size: 15px;
  font-weight: 500;
  cursor: pointer;
  transition: background 0.18s;
  margin-left: 0;
}
.admin-table-delete-btn:hover {
  background: #c62828;
}
.admin-btn-row {
  display: flex;
  gap: 24px;
  margin-bottom: 24px;
}
.admin-btn-row .admin-form-btn {
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 24px;
  font-size: 16px;
  font-weight: 500;
  padding: 12px 40px;
  min-width: 180px;
  box-shadow: 0 2px 8px #e3f2fd;
  border: 2px solid #b0b8c1;
  background: #fff;
  color: #1976d2;
  transition: background 0.18s, color 0.18s, border 0.18s;
  gap: 10px;
}
.admin-btn-row .admin-form-btn:hover {
  background: #e3f2fd;
  color: #1565c0;
  border-color: #1976d2;
}
.admin-btn-row .admin-form-btn.blue {
  background: #e3f2fd;
  color: #1976d2;
  border: none;
}
.admin-btn-row .admin-form-btn.blue:hover {
  background: #bbdefb;
  color: #1565c0;
}
.admin-btn-row .admin-form-btn .btn-icon {
  font-size: 20px;
  margin-right: 8px;
  display: flex;
  align-items: center;
}
.admin-confirm-label {
  display: inline-block;
  background: #e57373;
  color: #fff;
  font-weight: 700;
  font-size: 12px;
  border-radius: 14px;
  padding: 2px 12px 2px 12px;
  margin-right: 8px;
  box-shadow: 0 2px 6px rgba(229,115,115,0.13);
  letter-spacing: 1px;
  vertical-align: middle;
  border: none;
  transition: background 0.18s;
}
.admin-confirm-label:hover {
  background: #c62828;
}
</style>

<div class="admin-user-table-area">
    <div class="admin-title">
      お問い合わせ一覧
    </div>
    

    <input type="text" id="admin-table-search" class="admin-table-search" placeholder="検索...">
    <table class="admin-table" id="question-table">
      <thead>
        <tr>
          <th >確認</th>
          <th >継続</th>
          <th >返信日付</th>
          <th >質問者</th>
          <th >件名</th>
          <th>操作</th>
          <th>削除</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach($contacts_list as $key => $question_id){ ?>
        <?php 
        
        $contact_data = $spiritContensQuestion->getContactsDetail($question_id);
        $user_data = $spiritUser->getUserAcountData($contact_data["質問者"]);
        ?>
        <tr>
          <td><?php if($contact_data["管理者確認"] == "1"){ ?>
            <span class="admin-confirm-label">NEW</span>
          <?php } ?></td>
          <td>

            <?php if(isset($contacts_unanswered_question[$question_id])){ ?>
              <span class="admin-confirm-label" style="background: #4caf50;">継続中</span>
            <?php }else if(get_field("acf_contacts_end",$question_id) != ""){ ?>
              <span class="admin-confirm-label">終了</span>
            <?php } ?>
          </td>


          <td><?php echo $contact_data["返信日付年月日"]; ?></td>
          <td><?php echo $user_data["フル名前"]; ?></td>
          <td><?php echo $contact_data["件名"]; ?></td>
          <td>
            <form action="<?php echo getURLSetSlag('admin-contacts-detail'); ?>" method="post" style="display:inline;">
              <input type="hidden" name="contact_id" value="<?php echo $question_id; ?>">
              <button class="admin-table-edit-btn" type="submit">編集</button>
            </form>
          </td>
          <td>
            <form action="<?php echo getURLSetSlag('admin-contens-question-list'); ?>" method="post" style="display:inline;">
              <input type="hidden" name="contact_id" value="<?php echo $question_id; ?>">
              <input type="hidden" name="delete" value="delete">
              <button class="admin-table-delete-btn" type="submit">削除</button>
            </form>
          </td>
        </tr>
      <?php } ?>
      </tbody>
    </table>
</div>
<script>
// 検索フィルタ
const searchInput = document.getElementById('admin-table-search');
const table = document.getElementById('question-table');
searchInput.addEventListener('input', function() {
  const filter = this.value.toLowerCase();
  const rows = table.querySelectorAll('tbody tr');
  rows.forEach(row => {
    const text = row.textContent.toLowerCase();
    row.style.display = text.includes(filter) ? '' : 'none';
  });
});
// ソート
let sortCol = null;
let sortAsc = true;
table.querySelectorAll('th[data-sort]').forEach((th, idx) => {
  th.addEventListener('click', function() {
    const type = th.getAttribute('data-sort');
    const rows = Array.from(table.querySelectorAll('tbody tr'));
    if (sortCol === idx) sortAsc = !sortAsc; else { sortCol = idx; sortAsc = true; }
    table.querySelectorAll('th').forEach(t => t.classList.remove('sort-asc', 'sort-desc'));
    th.classList.add(sortAsc ? 'sort-asc' : 'sort-desc');
    rows.sort((a, b) => {
      let ta = a.children[idx].textContent.trim();
      let tb = b.children[idx].textContent.trim();
      if (type === 'number') { ta = parseFloat(ta); tb = parseFloat(tb); }
      return (ta < tb ? -1 : ta > tb ? 1 : 0) * (sortAsc ? 1 : -1);
    });
    rows.forEach(row => table.querySelector('tbody').appendChild(row));
  });
});

document.addEventListener('DOMContentLoaded', function() {
  document.querySelectorAll('form[action*="admin-contens-question-list"][method="post"]').forEach(function(form) {
    form.addEventListener('submit', function(e) {
      if (!window.confirm('本当に削除しますか？')) {
        e.preventDefault();
        return false;
      }
    });
  });
});

jQuery(function($) {
  $('#question-table').DataTable({
    order: [[1, 'desc']],
    language: {
      url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/ja.json"
    },
    dom: 't<"bottom"ip>'
  });
});
</script>
