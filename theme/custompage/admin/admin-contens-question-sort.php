<?php 

    require_once ("a-gate-functions.php");
    require_once (dirname(__FILE__)."/../../class/spiritContensQuestionClass.php");

    $spiritContensQuestion = new SpiritContensQuestionClass();

   
    if(isset($_POST["sorted_ids"])){
        $sorted_ids = $_POST["sorted_ids"];
        $sorted_ids = explode(",", $sorted_ids);

        //var_dump($sorted_ids);
        foreach($sorted_ids as $key => $sorted_id){
            update_field("acf_contens_question_sort", $key + 1, $sorted_id);
        }
    }
  

    $question_list = $spiritContensQuestion->getQuestionList();

   // var_dump($question_list);

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
.sort-controls {
  display: flex;
  flex-direction: column;
  gap: 2px;
}

.sort-btn {
  width: 30px;
  height: 20px;
  border: none;
  background: #1976d2;
  color: white;
  cursor: pointer;
  font-size: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: background 0.2s;
}

.sort-btn:hover:not(:disabled) {
  background: #1565c0;
}

.sort-btn:disabled {
  background: #ccc;
  cursor: not-allowed;
}

.sort-btn.up {
  border-radius: 4px 4px 0 0;
}

.sort-btn.down {
  border-radius: 0 0 4px 4px;
}

#sortable-tbody tr {
  transition: background 0.2s ease;
}

#sortable-tbody tr:hover {
  background: #f8fbff;
}

#sortable-tbody tr td:first-child {
  cursor: default;
}

#sortable-tbody tr td:first-child:hover {
  background: transparent;
}

.original-number.changed {
  background-color: #fff3cd;
  border: 1px solid #ffeaa7 !important;
  border-left: 1px solid #1976d2 !important;
  border-right: 1px solid #1976d2 !important;
  border-top: 1px solid #1976d2 !important;
  border-bottom: 1px solid #1976d2 !important;
}

#sortable-tbody tr.recently-moved {
  border: 2px solid #dc3545 !important;
  box-shadow: 0 0 8px rgba(220, 53, 69, 0.3);
}
</style>


<?php $count = 1;?>

<div class="admin-user-table-area">
    <div class="admin-title">
      よくある質問並び替え
    </div>
    <div class="admin-btn-row">
      <form action="<?php echo getURLSetSlag('admin-contens-question-list'); ?>">
        <button class="admin-form-btn" type="submit">
          <span class="btn-icon">
            <svg width="20" height="20" fill="none" viewBox="0 0 20 20"><path d="M3 3h4v4H3V3zm5 0h4v4H8V3zm5 0h4v4h-4V3zM3 8h4v4H3V8zm5 0h4v4H8V8zm5 0h4v4h-4V8zM3 13h4v4H3v-4zm5 0h4v4H8v-4zm5 0h4v4h-4v-4z" fill="#1976d2"/></svg>
          </span>
          一覧に戻る
        </button>
      </form>
      
    </div>

    <form id="sort-form" method="post" action="">
      <input type="hidden" name="sorted_ids" id="sorted-ids" value="">
      <table class="admin-table" id="question-table">
        <thead>
          <tr>
            <th style="width: 80px;">操作</th>
            <th data-sort="number" style="width: 60px;">並び順</th>
            <th data-sort="number" style="width: 60px;">元の番号</th>
            <th data-sort="string">質問</th>
          </tr>
        </thead>
        <tbody id="sortable-tbody">
        <?php foreach($question_list as $key => $question_id){ ?>
          <tr data-id="<?php echo $question_id; ?>" data-index="<?php echo $key; ?>">
            <td class="sort-controls">
              <button type="button" class="sort-btn up" onclick="moveUp(<?php echo $key; ?>)" <?php echo ($key == 0) ? 'disabled' : ''; ?>>▲</button>
              <button type="button" class="sort-btn down" onclick="moveDown(<?php echo $key; ?>)" <?php echo ($key == count($question_list) - 1) ? 'disabled' : ''; ?>>▼</button>
            </td>
            <td class="order-number" style="text-align: center;font-weight: bold;"><?php echo $count; ?></td>
            <td class="original-number" style="text-align: center;"><?php echo get_field("acf_contens_question_sort", $question_id) ?: ($count); ?></td>
            <td><?php echo get_field("acf_contens_question_title", $question_id); ?></td>
          </tr>
          <?php $count++; ?>
        <?php } ?>
        </tbody>
      </table>
      <button type="submit" class="admin-form-btn" style="margin-top:24px;">保存</button>
    </form>
</div>
<script>
function moveUp(index) {
  const tbody = document.getElementById('sortable-tbody');
  const rows = Array.from(tbody.querySelectorAll('tr'));
  const currentRow = rows[index];
  const prevRow = rows[index - 1];
  
  if (prevRow) {
    // 前の赤枠をすべて削除
    tbody.querySelectorAll('tr').forEach(row => {
      row.classList.remove('recently-moved');
    });
    
    tbody.insertBefore(currentRow, prevRow);
    updateOrderNumbers();
    updateButtonStates();
    
    // 移動した行に赤枠を追加
    currentRow.classList.add('recently-moved');
  }
}

function moveDown(index) {
  const tbody = document.getElementById('sortable-tbody');
  const rows = Array.from(tbody.querySelectorAll('tr'));
  const currentRow = rows[index];
  const nextRow = rows[index + 1];
  
  if (nextRow) {
    // 前の赤枠をすべて削除
    tbody.querySelectorAll('tr').forEach(row => {
      row.classList.remove('recently-moved');
    });
    
    tbody.insertBefore(currentRow, nextRow.nextSibling);
    updateOrderNumbers();
    updateButtonStates();
    
    // 移動した行に赤枠を追加
    currentRow.classList.add('recently-moved');
  }
}

function updateOrderNumbers() {
  const tbody = document.getElementById('sortable-tbody');
  const rows = Array.from(tbody.querySelectorAll('tr'));
  
  rows.forEach((row, idx) => {
    const orderCell = row.querySelector('.order-number');
    const originalCell = row.querySelector('.original-number');
    
    if (orderCell) orderCell.textContent = idx + 1;
    
    // 並び順と元の番号が異なる場合、背景色を変更
    if (originalCell) {
      const orderNumber = idx + 1;
      const originalNumber = parseInt(originalCell.textContent);
      
      if (orderNumber !== originalNumber) {
        originalCell.classList.add('changed');
      } else {
        originalCell.classList.remove('changed');
      }
    }
    
    row.setAttribute('data-index', idx);
  });
}

function updateButtonStates() {
  const tbody = document.getElementById('sortable-tbody');
  const rows = Array.from(tbody.querySelectorAll('tr'));
  
  rows.forEach((row, idx) => {
    const upBtn = row.querySelector('.sort-btn.up');
    const downBtn = row.querySelector('.sort-btn.down');
    
    if (upBtn) {
      upBtn.disabled = idx === 0;
      upBtn.onclick = () => moveUp(idx);
    }
    
    if (downBtn) {
      downBtn.disabled = idx === rows.length - 1;
      downBtn.onclick = () => moveDown(idx);
    }
  });
}

document.addEventListener('DOMContentLoaded', function() {
  const tbody = document.getElementById('sortable-tbody');
  
  // 初期化時にボタン状態を更新
  updateButtonStates();
  
  const form = document.getElementById('sort-form');
  form.addEventListener('submit', function(e) {
    if (!window.confirm('本当にこの順番で保存しますか？')) {
      e.preventDefault();
      return false;
    }
    const ids = Array.from(tbody.querySelectorAll('tr')).map(tr => tr.getAttribute('data-id'));
    document.getElementById('sorted-ids').value = ids.join(',');
  });
});
</script>
