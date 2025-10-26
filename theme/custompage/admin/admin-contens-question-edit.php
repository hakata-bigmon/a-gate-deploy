<?php 

    require_once ("a-gate-functions.php");
    require_once (dirname(__FILE__)."/../../class/spiritContensQuestionClass.php");

    $spiritContensQuestion = new SpiritContensQuestionClass();


    $question_id = "";


    if(isset($_POST["question_id"])){
      $question_id = $_POST["question_id"];
    }

    $save_message = "";

    $category_list = $spiritContensQuestion->getCategoryList();
    $category_list_sort = $spiritContensQuestion->getCategoryList(true);

    if(isset($_POST["save"])){

      if($question_id != ""){
        $spiritContensQuestion->saveQuestion($question_id, $_POST);
      }
      else{
        $question_id = $spiritContensQuestion->createQuestion($_POST);
      }


      //保存しましたのmessageを表示
      $save_message = "保存しました";

    }

  //  var_dump($_POST);

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
.admin-form-label {
  display: block;
  font-size: 15px;
  color: #1565c0;
  margin-bottom: 6px;
  font-weight: 500;
}
.admin-form-input, .admin-form-textarea {
  width: 100%;
  border: 1.5px solid #1976d2;
  border-radius: 10px;
  padding: 10px 12px;
  font-size: 15px;
  margin-bottom: 18px;
  font-family: 'Noto Sans JP', sans-serif;
  background: #e3f2fd;
  box-sizing: border-box;
  color: #1565c0;
}
.admin-form-input:focus, .admin-form-textarea:focus {
  outline: none;
  border-color: #1565c0;
  background: #fff;
}
.admin-form-textarea {
  min-height: 150px;
  resize: vertical;
}
.admin-form-btn {
  background: #1976d2;
  color: #fff;
  border: none;
  border-radius: 20px;
  padding: 10px 48px;
  font-size: 16px;
  margin: 24px 0 0 0;
  cursor: pointer;
  display: block;
  margin-left: auto;
  margin-right: auto;
  font-family: 'Noto Sans JP', sans-serif;
  font-weight: 500;
  transition: background 0.18s;
  box-shadow: 0 2px 8px #1976d222;
}
.admin-form-btn:hover {
  background: #1565c0;
}
.admin-form-back {
  background: #e3f2fd;
  color: #1976d2;
  border: 1.5px solid #1976d2;
  border-radius: 20px;
  padding: 8px 36px;
  font-size: 15px;
  margin-bottom: 24px;
  cursor: pointer;
  display: block;
  margin-left: auto;
  margin-right: auto;
  font-family: 'Noto Sans JP', sans-serif;
  font-weight: 500;
  transition: background 0.18s, color 0.18s;
}
.admin-form-back:hover {
  background: #1976d2;
  color: #fff;
}
</style>

<div class="admin-user-table-area">
    <div class="admin-title">
      よくある質問編集
    </div>
    <form action="<?php echo getURLSetSlag('admin-contens-question-list'); ?>">
      <button class="admin-form-back" type="submit">一覧に戻る</button>
    </form>

    <?php if($save_message != ""){ ?>
        <div class="admin-form-message" style="margin-bottom: 15px;color: red;font-weight: 500;text-align: center;">
          <?php echo $save_message; ?>
        </div>
    <?php } ?>


    <form action="<?php echo getURLSetSlag('admin-contens-question-edit'); ?>" method="post">
      <input type="hidden" name="save" value="save">
      <input type="hidden" name="question_id" value="<?php echo $question_id; ?>">
      <label for="acf_contens_question_title" class="admin-form-label">質問内容</label>
      <input type="text" name="acf_contens_question_title" id="acf_contens_question_title" class="admin-form-input" value="<?php if($question_id != ""){ echo get_field("acf_contens_question_title", $question_id); } ?>" required>
      
      <label for="acf_contens_question_category" class="admin-form-label">カテゴリー</label>
      <select name="acf_contens_question_category" id="acf_contens_question_category" class="admin-form-select" style="margin-bottom: 15px;">
        <option value="">カテゴリーを選択してください</option>
        <?php foreach($category_list_sort as $key => $category_name){ ?>
          <option value="<?php echo $category_name; ?>" <?php if($question_id != ""){ echo selected(get_field("acf_contens_question_category", $question_id), $category_name); } ?>><?php echo get_field("acf_question_category_name", $category_name); ?></option>
        <?php } ?>
      </select>
      
      <label for="acf_contens_question_result" class="admin-form-label">回答内容</label>
      <textarea name="acf_contens_question_result" id="acf_contens_question_result" class="admin-form-textarea" required><?php if($question_id != ""){ echo get_field("acf_contens_question_result", $question_id); } ?></textarea>
      <button class="admin-form-btn" type="submit">保存</button>
    </form>
</div>
