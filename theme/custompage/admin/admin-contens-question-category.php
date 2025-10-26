<?php 

    require_once ("a-gate-functions.php");
    require_once (dirname(__FILE__)."/../../class/spiritContensQuestionClass.php");

    $spiritContensQuestion = new SpiritContensQuestionClass();


    $question_id = "";


    //var_dump($_POST);
    //新規
    if(isset($_POST["new_save"])){

        $spiritContensQuestion->createCategory($_POST);

    }

    //編集
    if(isset($_POST["edit_save"])){

        $spiritContensQuestion->editCategory($_POST["edit_id"],$_POST);

    }


    //削除
    if(isset($_POST["delete_save"])){

        $spiritContensQuestion->deleteCategory($_POST["delete_id"]);

    }


    $category_list = $spiritContensQuestion->getCategoryList();
    $category_list_sort = $spiritContensQuestion->getCategoryList(true);

  //  var_dump($_POST);

?>



<div class="admin-user-table-area">
    <div class="admin-title">
      よくある質問カテゴリー
    </div>
    <div class="admin-btn-row">
      <a href="<?php echo getURLSetSlag('admin-contens-question-list'); ?>" class="admin-form-btn blue">
        <span class="btn-icon">
          <svg width="20" height="20" fill="none" viewBox="0 0 20 20"><path d="M3 3h4v4H3V3zm5 0h4v4H8V3zm5 0h4v4h-4V3zM3 8h4v4H3V8zm5 0h4v4H8V8zm5 0h4v4h-4V8zM3 13h4v4H3v-4zm5 0h4v4H8v-4zm5 0h4v4h-4v-4z" fill="#1976d2"/></svg>
        </span>
        よくある質問一覧へ
      </a>
      <a href="<?php echo getURLSetSlag('admin-profile-menu'); ?>" class="admin-form-btn blue">
        <span class="btn-icon">
          <svg width="20" height="20" fill="none" viewBox="0 0 20 20"><path d="M10 2C5.58 2 2 5.58 2 10s3.58 8 8 8 8-3.58 8-8-3.58-8-8-8zm0 14c-3.31 0-6-2.69-6-6s2.69-6 6-6 6 2.69 6 6-2.69 6-6 6z" fill="#1976d2"/><path d="M10 6c-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4z" fill="#1976d2"/></svg>
        </span>
        設定メニューへ
      </a>
      <a href="<?php echo getURLSetSlag('admin-contens-question-category-sort'); ?>" class="admin-form-btn blue">
        <span class="btn-icon">
          <svg width="20" height="20" fill="none" viewBox="0 0 20 20"><path d="M3 18h6v-2H3v2zM3 6v2h18V6H3zm0 7h12v-2H3v2z" fill="#1976d2"/></svg>
        </span>
        カテゴリー並び順
      </a>
      
    </div>

    <div class="admin-form-container">
        <?php if(isset($_POST["category_id"])){ ?>
            <form action="<?php echo getURLSetSlag('admin-contens-question-category'); ?>" method="post" class="admin-form">
                <div class="form-row">
                    <div class="form-group">
                        <input type="text" id="category_name" name="acf_question_category_name" placeholder="カテゴリー名" value="<?php echo $category_list[$_POST["category_id"]]; ?>" class="form-input" required>
                    </div>
                    <div class="form-actions">
                        <input type="hidden" name="edit_save" value="1">
                        <input type="hidden" name="edit_id" value="<?php echo $_POST["category_id"]; ?>">
                        <button type="submit" class="admin-form-btn">
                            <span class="material-icons">save</span> 変更
                        </button>
                    </div>
                </div>
            </form>
        <?php }else{ ?>
            <form action="<?php echo getURLSetSlag('admin-contens-question-category'); ?>" method="post" class="admin-form">
                <div class="form-row">
                    <div class="form-group">
                        <input type="text" id="category_name" name="acf_question_category_name" placeholder="カテゴリー名" class="form-input" required>
                    </div>
                    <div class="form-actions">
                        <input type="hidden" name="new_save" value="1">
                        <input type="hidden" name="acf_question_category_save_unix_time" value="<?php echo time(); ?>">
                        <button type="submit" class="admin-form-btn">
                            <span class="material-icons">add</span> 新規作成
                        </button>
                    </div>
                </div>
            </form>
        <?php } ?>
    </div>

    <?php if(count($category_list) > 0){ ?>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>カテゴリー名</th>
                    <th>操作</th>
                    <th>削除</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($category_list_sort as $key => $category_id){ ?>
                    <tr>
                        <td class="category-name"><?php echo get_field("acf_question_category_name", $category_id); ?></td>
                        <td class="action-cell">
                            <form action="<?php echo getURLSetSlag('admin-contens-question-category'); ?>" method="post" class="inline-form">
                                <input type="hidden" name="category_id" value="<?php echo $category_id; ?>">
                                <button type="submit" class="admin-table-edit-btn">
                                    編集
                                </button>
                            </form>
                        </td>
                        <td class="action-cell">
                            <form action="<?php echo getURLSetSlag('admin-contens-question-category'); ?>" method="post" class="inline-form delete-form">
                                <input type="hidden" name="delete_id" value="<?php echo $category_id; ?>">
                                <input type="hidden" name="delete_save" value="1">
                                <button type="submit" class="admin-table-delete-btn">
                                    削除
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } ?>
</div>

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
.admin-form-container {
  background: #f9f9f9;
  padding: 25px;
  border-radius: 8px;
  margin-bottom: 30px;
  border: 1px solid #e1e1e1;
}
.admin-form {
  margin: 0;
}
.form-row {
  display: flex;
  align-items: center;
  gap: 20px;
}
.form-group {
  
}
.form-group label {
  display: block;
  margin-bottom: 8px;
  font-weight: 500;
  color: #333;
  font-size: 14px;
}
.form-input {
  width: 500px;
  padding: 12px 15px;
  border: 1px solid #ddd;
  border-radius: 6px;
  font-size: 14px;
  transition: border-color 0.2s;
}
.form-input:focus {
  outline: none;
  border-color: #1976d2;
  box-shadow: 0 0 0 3px rgba(25, 118, 210, 0.1);
}
.form-actions {
  display: flex;
  align-items: center;
}
.btn {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 12px 24px;
  border: none;
  border-radius: 6px;
  font-size: 14px;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s;
  text-decoration: none;
}
.btn-primary {
  background: #1976d2;
  color: white;
}
.btn-primary:hover {
  background: #1565c0;
}
.btn-edit {
  background: #1976d2;
  color: white;
  padding: 8px 16px;
  font-size: 13px;
}
.btn-edit:hover {
  background: #1565c0;
}
.btn-danger {
  background: #e57373;
  color: white;
  padding: 8px 16px;
  font-size: 13px;
}
.btn-danger:hover {
  background: #c62828;
}
.admin-table-container {
  background: white;
  border-radius: 8px;
  overflow: hidden;
  border: 1px solid #e1e1e1;
}
.category-name {
  font-weight: 500;
  color: #333;
}
.action-cell {
  width: 120px;
}
.inline-form {
  margin: 0;
}
.material-icons {
  font-size: 18px;
}
@media (max-width: 768px) {
  .form-row {
    flex-direction: column;
    align-items: stretch;
  }
  .admin-actions {
    flex-direction: column;
  }
  .admin-table {
    font-size: 13px;
  }
  .admin-table th,
  .admin-table td {
    padding: 10px 15px;
      }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.delete-form').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            if (!window.confirm('本当に削除しますか？')) {
                e.preventDefault();
                return false;
            }
        });
    });
});
</script>






