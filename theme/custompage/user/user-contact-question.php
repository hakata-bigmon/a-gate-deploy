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

    $question_list = $spiritContensQuestion->getQuestionList();

    $userData = $userClass->getUserAcountData($user_id);


    $category_list = $spiritContensQuestion->getCategoryList();
    $category_list_sort = $spiritContensQuestion->getCategoryList(true);
  //  var_dump($category_list);
?>

<style>
.faq-title-main {
  text-align: center;
  font-size: 22px;
  font-weight: 700;
  margin: 32px 0 36px 0;
  color: #555;
  font-family: 'Noto Sans JP', sans-serif;
}
.faq-list {
  max-width: 700px;
  margin: 0 auto;
}
.faq-item {
  margin-bottom: 24px;
}
.faq-question-box {
  background: #fff;
    border-radius: 18px;
    margin-bottom: 8px;
    margin-top: 50px;
    border: 1px solid #DCB4DC;
 
}
.faq-answer-box {
    padding-top: 10px;
    border-top: 1px solid #CCCCCC;
    margin-top: 30px;
}
.faq-row {
  display: flex;
  align-items: stretch;
  gap: 18px;
  min-height: 50px;
}
.faq-label {
  display: flex;
  flex-direction: column;
  justify-content: center;
  min-width: 100px;
  height: auto; /* 100%をautoに変更 */
  align-self: stretch;
  box-sizing: border-box;
  min-height: 100%; /* 48pxを100%に変更 */
  font-size: 22px;
  font-weight: 700;
  background: #b388dd;
  color: #fff;
  border-radius: 14px 0 0 14px;
  text-align: center;
  font-family: 'Noto Sans JP', sans-serif;
  flex-shrink: 0;
  padding: 0 22px;
  margin-bottom: 0;
  line-height: 1;
}
.faq-label.multiline {
  justify-content: flex-start;
}
.faq-title {
    font-size: 14px;
    font-weight: 400;
    color: #888888;
    font-family: 'Noto Sans JP', sans-serif;
    line-height: 1.6;
    flex: 1;
    padding-right: 10px;
    align-items: center;
    display: flex
;
}
.faq-answer {
  font-size: 13px;
  color: #888;
  font-family: 'Noto Sans JP', sans-serif;
  line-height: 1.8;
  
}
@media (max-width: 800px) {
  .faq-question-box,
  .faq-answer-box { 
    
  }

  .faq-question-box { 
    
  }
  .faq-answer-box {
    margin-left: 10px;
  }
  .faq-row { 
    flex-direction: column; 
    align-items: flex-start; 
    gap: 12px;
  }
  .faq-label { 
    border-radius: 14px 14px 0px 0px;
    padding-bottom: 5px;
  }
  .faq-answer { 
    margin-left: 0; 
  }

  .faq-title{
    padding-left: 10px;
    padding-bottom: 10px;
  }
}
.faq-link-btn-wrap {
  display: flex;
  justify-content: flex-end;
  margin-bottom: 16px;
}
.faq-link-btn {
  display: inline-block;
  background: #1976d2;
  color: #fff;
  font-weight: 600;
  font-size: 15px;
  border-radius: 22px;
  padding: 8px 28px;
  text-decoration: none;
  transition: background 0.2s;
  box-shadow: 0 2px 8px rgba(128,10,144,0.08);
  border: none;
  cursor: pointer;
}
.faq-link-btn:hover {
  background: #87b9eb;
  color: #fff;
}
.faq-category-list {
  max-width: 700px;
  margin: 0 auto 40px auto;
}
.faq-filter-form {
  margin: 0;
}
.faq-filter-wrap {
  display: flex;
    gap: 16px;
    align-items: center;
    justify-content: center;
    background: #fff;
    border-radius: 18px;
}
.faq-category-select {
  flex: 1;
  max-width: 300px;
  padding: 12px 16px;
  border: 1px solid #DCB4DC;
  border-radius: 14px;
  font-size: 15px;
  font-family: 'Noto Sans JP', sans-serif;
  background: #fff;
  color: #555;
  cursor: pointer;
  transition: border-color 0.2s;
}
.faq-category-select:focus {
  outline: none;
  border-color: #800A90;
  box-shadow: 0 0 0 3px rgba(128,10,144,0.1);
}
.faq-filter-btn {
  background: #800A90;
  color: #fff;
  border: none;
  border-radius: 14px;
  padding: 12px 24px;
  font-size: 15px;
  font-weight: 600;
  font-family: 'Noto Sans JP', sans-serif;
  cursor: pointer;
  transition: background 0.2s;
  box-shadow: 0 2px 8px rgba(128,10,144,0.2);
}
.faq-filter-btn:hover {
  background: #6a0a7a;
}

.faq-category-label{
  font-size: 12px;
    margin-bottom: 5px;
    color: #b388dd;
    font-weight: 600;
}
.faq-category-label a {
  color: #b388dd;
  text-decoration: none;
  transition: color 0.2s;
}
.faq-category-label a:hover {
  color: #800A90;
  text-decoration: underline;
}
@media (max-width: 800px) {
  .faq-filter-wrap {
    flex-direction: column;
    gap: 12px;
  }
  .faq-category-select {
    max-width: 100%;
    width: 100%;
  }
  .faq-filter-btn {
    width: 100%;
  }
}
</style>

<div class="user-top-area">

    <div class="user-jorei-section-title-wrap">
        <div class="user-jorei-section-title">お問い合わせ</div>
    </div>

    <div class="faq-link-btn-wrap">
      <a href="<?php echo getURLSetSlag('users/user-contacts-list') . $get_url['add']; ?>" class="faq-link-btn">
        お問い合わせ一覧 &gt;
      </a>
    </div>

    <div class="faq-title-main">よくある質問</div>

    <div class="faq-category-list">
        <form action="<?php echo getURLSetSlag('users/user-contact-question') . $get_url['add']; ?>" method="post" class="faq-filter-form">
          <div class="faq-filter-wrap">
            <select name="category_id" id="category_id" class="faq-category-select">
              <option value="">全て表示</option>
              <?php foreach($category_list_sort as $category_num => $category_id){ ?>
                  <option value="<?php echo $category_id; ?>" <?php if(isset($_POST["category_id"]) && $_POST["category_id"] == $category_id){ echo "selected"; } ?>><?php echo get_field("acf_question_category_name", $category_id); ?></option>
            <?php } ?>
            </select>
            <button type="submit" class="faq-filter-btn">質問カテゴリーを絞り込む</button>
          </div>
        </form>
    </div>


    <div class="faq-list">
        <?php $question_count = 1; ?>
        <?php foreach($question_list as $question_id){
            $question_title = get_field("acf_contens_question_title", $question_id);
            $question_result = get_field("acf_contens_question_result", $question_id);
            $question_category = get_field("acf_contens_question_category", $question_id);
            if(isset($_POST["category_id"]) && $_POST["category_id"] != ""){
                if($question_category != $_POST["category_id"]){
                    continue;
                }
            }
        ?>
        <div class="faq-item">
            <!-- 質問部分 -->
            <div class="faq-question-box">
                <div class="faq-row">
                    <div class="faq-label">Q.<?php echo $question_count; ?></div>
                    <div class="faq-title"><?php echo $question_title; ?></div>
                </div>
            </div>
            
            <!-- 回答部分 -->
            <div class="faq-answer-box">
                <div>
                    <?php if(isset($category_list[$question_category])){ ?>
                      <div class="faq-category-label">
                        <a href="javascript:void(0);" onclick="filterByCategory('<?php echo $question_category; ?>')">
                          カテゴリー::<?php echo $category_list[$question_category]; ?>
                        </a>
                      </div>
                    <?php }else{ ?>
                      <div class="faq-category-label"> カテゴリー::未設定</div>
                    <?php } ?>
                </div>
                <div class="faq-answer"><?php echo $question_result; ?></div>
            </div>
        </div>
        <?php $question_count++; ?>
        <?php } ?>
    </div>


    <div class="user-account-edit-form-btn-wrap" style="margin-top: 80px;margin-bottom: 100px;">
        <a href="<?php echo getURLSetSlag("users/user-contact"); echo $get_url["add"]; ?>" class="user-account-edit-return-btn" style="background-color: #800A90;color: #fff;">お問い合わせ  &gt;</a>
    </div>

    <div class="user-account-edit-form-btn-wrap" style="margin-top: 80px;margin-bottom: 100px;">
        <a href="<?php echo getURLSetSlag("users/user_top"); echo $get_url["add"]; ?>" class="user-account-edit-return-btn">TOPへ戻る  &gt;</a>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  document.querySelectorAll('.faq-label').forEach(function(label) {
    // 1行の高さより大きければ2行以上とみなす
    if (label.scrollHeight > label.clientHeight + 2) {
      label.classList.add('multiline');
    }
  });
});

function filterByCategory(categoryId) {
  // セレクトボックスの値を設定
  document.getElementById('category_id').value = categoryId;
  
  // フォームを送信
  document.querySelector('.faq-filter-form').submit();
}
</script>