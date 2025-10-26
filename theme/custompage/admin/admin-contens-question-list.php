<?php 

    require_once ("a-gate-functions.php");
    require_once (dirname(__FILE__)."/../../class/spiritContensQuestionClass.php");

    $spiritContensQuestion = new SpiritContensQuestionClass();


    //削除
    if(isset($_POST["delete"])){
        $question_id = $_POST["question_id"];
        wp_delete_post($question_id);
    }

    $question_list = $spiritContensQuestion->getQuestionList();

    $category_list = $spiritContensQuestion->getCategoryList();

?>

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
</style>

<div class="admin-user-table-area">
    <div class="admin-title">
      よくある質問一覧
    </div>
    <div class="admin-btn-row">
      <form action="<?php echo getURLSetSlag('admin-contens-question-edit'); ?>">
        <button class="admin-form-btn" type="submit">
          <span class="btn-icon">
            <svg width="20" height="20" fill="none" viewBox="0 0 20 20"><path d="M10 4v12M4 10h12" stroke="#1976d2" stroke-width="2" stroke-linecap="round"/></svg>
          </span>
          新規質問作成
        </button>
      </form>
      <form action="<?php echo getURLSetSlag('admin-contens-question-category'); ?>">
        <button class="admin-form-btn blue" type="submit">
          <span class="btn-icon">
            <svg width="20" height="20" fill="none" viewBox="0 0 20 20"><path d="M3 3h4v4H3V3zm5 0h4v4H8V3zm5 0h4v4h-4V3zM3 8h4v4H3V8zm5 0h4v4H8V8zm5 0h4v4h-4V8zM3 13h4v4H3v-4zm5 0h4v4H8v-4zm5 0h4v4h-4v-4z" fill="#1976d2"/></svg>
          </span>
          カテゴリー作成
        </button>
      </form>
      <form action="<?php echo getURLSetSlag('admin-contens-question-sort'); ?>">
        <button class="admin-form-btn blue" type="submit">
          <span class="btn-icon">
            <svg width="20" height="20" fill="none" viewBox="0 0 20 20"><circle cx="10" cy="10" r="8" stroke="#1976d2" stroke-width="2"/><path d="M10 6v4l2 2" stroke="#1976d2" stroke-width="2" stroke-linecap="round"/></svg>
          </span>
          質問並び替え
        </button>
      </form>
    </div>

    <input type="text" id="admin-table-search" class="admin-table-search" placeholder="検索...">
    <table class="admin-table" id="question-table">
      <thead>
        <tr>
          <th data-sort="number">並び順</th>
          <th>カテゴリー</th>
          <th data-sort="string">質問</th>
          <th>操作</th>
          <th>削除</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach($question_list as $key => $question_id){ ?>
        <tr>
          <td><?php echo $key; ?></td>
          
          <td>
            <?php if(isset($category_list[get_field("acf_contens_question_category", $question_id)])){ ?>
              <?php echo $category_list[get_field("acf_contens_question_category", $question_id)]; ?>
            <?php }else{ ?>
              <span>未設定</span>
            <?php } ?>
          </td>
          <td><?php echo get_field("acf_contens_question_title", $question_id); ?></td>
          <td>
            <form action="<?php echo getURLSetSlag('admin-contens-question-edit'); ?>" method="post" style="display:inline;">
              <input type="hidden" name="question_id" value="<?php echo $question_id; ?>">
              <button class="admin-table-edit-btn" type="submit">編集</button>
            </form>
          </td>
          <td>
            <form action="<?php echo getURLSetSlag('admin-contens-question-list'); ?>" method="post" style="display:inline;">
              <input type="hidden" name="question_id" value="<?php echo $question_id; ?>">
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
</script>
