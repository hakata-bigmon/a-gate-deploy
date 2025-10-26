<?php

require_once ("a-gate-functions.php");
require_once (dirname(__FILE__)."/../../class/spiritSheetClass.php");

// 流入データ
$spirit_sheet_data = new spiritSheetClass(); //管理データ
$spiritStatusArray = $spirit_sheet_data->getGeneralPurposeData("cpt_inflow");
$regist_error = false;



$sei = "";
$mei = "";
$sei_kana = "";
$mei_kana = "";
$sex = "";
$tel1 = "";
$tel2 = "";
$tel3 = "";
$post_code = "";
$adress1 = "";
$adress2 = "";
$mail = "";
$line = "";
$born_year = "";
$born_month = "";
$born_day = "";
$report_born_year = "";
$report_born_month = "";
$report_born_day = "";
$input_inflow = "";
$introduction_id = "";
$input_remarks = "";
$inflow_remarks = "";
$check_phone_none = "";


// 確認項目
if(isset($_POST['input_first_name'])){
    $sei = $_POST['input_last_name'];
    $mei = $_POST['input_first_name'];
    $sei_kana = $_POST['input_first_name_kana'];
    $mei_kana = $_POST['input_last_name_kana'];
    $sex = $_POST['input_user_sex'];
    $tel1 = $_POST['input_tel_1'];
    $tel2 = $_POST['input_tel_2'];
    $tel3 = $_POST['input_tel_3'];
    $post_code = $_POST['input_post_no'];
    $adress1 = $_POST['input_address1'];
    $adress2 = $_POST['input_address2'];
    $mail = $_POST['input_user_email'];
    $line = $_POST['input_user_line_id'];
    $born_year = $_POST['input_user_born_year'];
    $born_month = $_POST['input_user_born_month'];
    $born_day = $_POST['input_user_born_day'];
    $report_born_year = $_POST['input_user_report_year'];
    $report_born_month =  $_POST['input_user_report_month'];
    $report_born_day = $_POST['input_user_report_day'];
    $input_inflow = $_POST['input_inflow'];
    $inflow_remarks = $_POST['input_inflow_remarks'];

    // if(isset($_POST['input_first_name'])){
    //     $introduction_id = $_POST['input_first_name'];
    // }
    $input_remarks = $_POST['input_remarks'];

}

if(isset($_POST['check_phone_none'])){
    $check_phone_none = $_POST['check_phone_none'];
}

// 新規作成
if(isset($_POST["create_member"])) {

    $result_msg = "メンバー作成処理";

    // if(!$res['regist_error'] && $res["registed_id"] == ""){

    
    $res = checkUserRegist($_POST);

    if($res['res_msg'] != "既存ユーザーです"){

        $res = setNewUserData($_POST);
    
    }
    
        
    
}

?>
<script src="<?php echo get_template_directory_uri()."/custompage/admin/a-gate-script.js"?>"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // ユーザー情報チェック
	function check_user(){
		
        const regex = /^[a-zA-Z0-9_.+-]+@([a-zA-Z0-9][a-zA-Z0-9-]*[a-zA-Z0-9]*\.)+[a-zA-Z]{2,}$/;   // メアドチェック
        const liregex = /^[a-zA-Z0-9,._-]{1,20}$/;  //LIENチェック
        var check_ok = true;
        
        // 入力チェック
		var last_name = document.getElementsByName('input_last_name')[0].value;
		var first_name = document.getElementsByName('input_first_name')[0].value;
		var post_code = document.getElementsByName('input_post_no')[0].value;
		var address1 = document.getElementsByName('input_address1')[0].value;


		var u_mal = document.getElementsByName('input_user_email')[0].value;
		var tel1 = document.getElementsByName('input_tel_1')[0].value;
		var tel2 = document.getElementsByName('input_tel_2')[0].value;
		var tel3 = document.getElementsByName('input_tel_3')[0].value;
		var tel_none = document.getElementById('no-tel').checked;

        // LINE IDの入力フィールド
        var emailInput = document.getElementById("input_must_mail");
        var lineInput = document.getElementById("input_must_line");
        var born1 = document.getElementById("born1").value;
        var born2 = document.getElementById("born2").value;
        var born3 = document.getElementById("born3").value;

        if(!last_name){
            alert("姓を入れてください");
            check_ok = false;
        }else if(!first_name){
            alert("名を入れてください");
            check_ok = false;
        }else if(!post_code){
            alert("郵便番号を入れてください");
            check_ok = false;
        }else if(!address1){
            alert("住所を入れてください");
            check_ok = false;
        }else if(born1 == "" || born2 == "" || born3 == ""){
            alert("生年月日を入れてください");
            check_ok = false;

        // LINE mail入力チェック
        // 入力フィールドが空の場合にコンソールにメッセージを表示する
        }else if (!emailInput.value && !lineInput.value) {
            alert("メールアドレスかLINE IDを入力してください");
            check_ok = false;
        }else if(!regex.test(u_mal) && !lineInput.value ){
            // LINE ID未設定　かつ　メアド入力アリ
            alert("メールアドレスが正しく入力されていません");
            check_ok = false;
        }else if(!liregex.test(lineInput.value) && u_mal.value == "" ){
            // LINE ID設定　かつ　メアド入力なし
            alert("LINE IDが正しく入力されていません");
            check_ok = false;
        }else if ((tel1 == "" || tel2 == "" || tel3 == "") && !tel_none) {
            alert("電話番号を正しく入力してください");
            check_ok = false;
        }

        if(!check_ok) return false;

        document.post_new_user_input.submit();



	}

    // 紹介者ボタン押下時URL変更
    function change_form_action(){
        
        document.getElementById("post_new_user_input").action = '<?php echo getURLSetSlag("admin-member-list").'?introduction_member=on'; ?>';
        document.getElementById("post_new_user_input").submit();
    }
    // 紹介者選び直し
    function reset_form_action(){
        
        // document.getElementById("post_new_user_input").action = '<?php echo getURLSetSlag("admin-member-list").'?introduction_member=on'; ?>';
        document.getElementById("input_check_user_id").remove() ;
        document.getElementById("input_introduction_id").value = "" ;
        document.getElementById("input_introduction_name").value = "" ;
        document.getElementById("post_new_user_input").submit();
    }
</script>

<div class="admin-main-area">
    
    <?php //確認画面でない　新規作成でない　or 登録者IDを使用 or 紹介者選択から戻る
        if(!isset($_POST["input_check_user_id"]) && !isset($_POST["create_member"]) || isset($_POST["use_old_regist_id"]) || isset($_POST["back_introduction"]) ) { ?>
        
    <div class="admin-title">新規登録</div>

        <form class="user-input-area" action="<?php echo getURLSetSlag("admin-member-regist"); ?>" method="post" name="post_new_user_input" id="post_new_user_input">

            <input type="hidden" name="input_check_user_id" id="input_check_user_id">

            <div class="user-table-flex">
                <div class="user-table-item">*名前</div>
                <div class="user-table-data">
                    <input class="user-table-item" type="text" name="input_last_name" placeholder="姓" value="<?php echo $sei?>" required>
                    <input class="user-table-item" type="text" name="input_first_name" placeholder="名" value="<?php echo $mei?>" required>
                </div>
            </div>

            <div class="user-table-flex">
                <div class="user-table-item">ナマエ</div>
                <div class="user-table-data">
                    <input class="user-table-item" type="text" name="input_last_name_kana" placeholder="セイ" value="<?php echo $sei_kana?>">
                    <input class="user-table-item" type="text" name="input_first_name_kana" placeholder="メイ" value="<?php echo $mei_kana?>">
                </div>
            </div>
            
            <div class="user-table-flex">
                <div class="user-table-item">*性別</div>
                <select name="input_user_sex" required>
                    <option value="M" <?php if($sex == "M") echo "selected"; ?>>男</option>
                    <option value="W" <?php if($sex == "W") echo "selected";?>>女</option>
                    <option value="U" <?php if($sex == "U") echo "selected";?>>不明</option>
                </select>
            </div>
            
            <div class="user-table-flex">
                <div class="user-table-item">*連絡先</div>
                <input type="text" name="input_tel_1" class="user-tel-input" id="" value="<?php echo $tel1?>" oninput="this.value = this.value.replace(/[^0-9]/g, '')"> -
                <input type="text" name="input_tel_2" class="user-tel-input" id="" value="<?php echo $tel2?>" oninput="this.value = this.value.replace(/[^0-9]/g, '')"> -
                <input type="text" name="input_tel_3" class="user-tel-input" id="" value="<?php echo $tel3?>" oninput="this.value = this.value.replace(/[^0-9]/g, '')">

                <input type="checkbox" name="check_phone_none" id="no-tel" <?php if($check_phone_none == "on") echo "checked";?>>電話番号なし
            </div>

            <div class="user-table-flex">
                <div class="user-table-item">*郵便番号</div>
                <input type="text" name="input_post_no" id="zipcode" value="<?php echo $post_code?>" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required placeholder="ハイフン無し">

                <button type="button" onclick="getAddress()">住所を取得</button>
            </div>

            <div class="user-table-flex">
                <div class="user-table-item">*住所</div>
                <input type="text"  class="input_address" name="input_address1" id="address" value="<?php echo $adress1?>" required>

            </div>

            <div class="user-table-flex">
                <div class="user-table-item">マンション名など</div>
                <input type="text" class="input_address" name="input_address2" id="" value="<?php echo $adress2?>">
            </div>

            <div class="user-table-flex" >
                <div class="user-table-item">*連絡先情報</div>
                <div class="">メールアドレスを入力するか、LINE IDを入力してください</div>
            </div>
            <div class="user-table-flex" >
                <div class="user-table-item"></div>
                <div class="user-table-item">メールアドレス</div>
                <input class="user-input-mail" type="email" name="input_user_email" id="input_must_mail" placeholder="メールアドレス" value="<?php echo $mail?>">
            </div>
            

            <div class="user-table-flex">
                <div class="user-table-item"></div>
                <div class="user-table-item">LINE ID</div>
                <input type="text" name="input_user_line_id" id="input_must_line" value="<?php echo $line?>">
            </div>

            <div class="user-table-flex">
                <div class="user-table-item">*生年月日</div>
                <input type="text" class="user-born-input" name="input_user_born_year" id="born1" value="<?php echo $born_year?>" required>年
                <input type="number" max="12" min="1" class="user-born-input" name="input_user_born_month" id="born2" value="<?php echo $born_month?>" required>月
                <input type="number" max="31" min="1" class="user-born-input" name="input_user_born_day" id="born3" value="<?php echo $born_day ?>" required>日
            </div>

            <div class="user-table-flex">
                <div class="user-table-item">届け出日</div>
                <input type="text" class="user-born-input" name="input_user_report_year" id="" value="<?php echo $report_born_year?>" >年
                <input type="number" max="12" min="1" class="user-born-input" name="input_user_report_month" id="" value="<?php echo $report_born_month?>" >月
                <input type="number" max="31" min="1" class="user-born-input" name="input_user_report_day" id="" value="<?php echo $report_born_day?>" >日
            </div>

            <?php 
                // var_dump($spiritStatusArray);  //削除okd
            ?>
            <div class="user-table-flex">
                <div class="user-table-item">流入元</div>
                
                <select name="input_inflow" id="">
                <?php
                foreach ($spiritStatusArray as $key => $value) {
                    /*
                    <option value="<?php echo $key;?>" <?php if(isset($_POST['input_inflow']) && ($_POST['input_inflow'] == $value["title"] || $_POST['input_inflow'] == $key)) echo "selected";?>><?php echo $value["title"];?></option>
                    <option value="<?php echo $value['ID']; ?>" <?php if ($user_inflow == $value['ID'] || $user_inflow == $value["title"]) echo "selected"; ?>><?php echo $value["title"]; ?></option>
                    <option value="<?php echo $value['ID'];?>" <?php if(isset($_POST['input_inflow']) && ($_POST['input_inflow'] == $value["title"] || $_POST['input_inflow'] == $key)) echo "selected";?>><?php echo $value["title"];?></option>
                    */
                ?>
                    <option value="<?php echo $value['ID'];?>" <?php if(isset($_POST['input_inflow']) && ($_POST['input_inflow'] == $value["ID"])) echo "selected";?>><?php echo $value["title"];?></option>
                    
                
                <?php } ?>
                </select>
            </div>

            <div class="user-table-flex">
                <div class="user-table-item">流入元追記</div>
                <input type="text" name="input_inflow_remarks" id="" value="<?php echo $inflow_remarks?>">
            </div>

            <?php /*
            <div class="user-table-flex">
                <div class="user-table-item">紹介者(ID)</div>
                <button type="button" class="squeeze-btn" onclick="change_form_action()">登録者から選択</button>

                <?php 
                    //登録済みならIDを表示
                    if(isset($_POST['use_old_regist_id']) || isset($_POST['input_introduction_id']) && $_POST['input_introduction_id'] != null){
                ?>
                    <button type="button" class="squeeze-btn gray" onclick="reset_form_action()">紹介者をクリア</button>
                <?php } ?>
            </div>
            <div class="user-table-flex">
                <div class="user-table-item"></div>
                <?php 
                    //登録済みならIDを表示
                    if(isset($_POST['use_old_regist_id']) || isset($_POST['input_introduction_id']) && $_POST['input_introduction_id'] != null){?>

                    <input type="hidden" name="input_introduction_id" id="input_introduction_id" value="<?php echo $_POST['input_introduction_id']?>" readonly>
                    <input type="text" name="" id="" value="<?php echo get_user_meta($_POST['input_introduction_id'],'user_unique_id',true) ?>" readonly>
                    <input type="text" name="input_introduction_name" id="input_introduction_name" value="<?php echo get_user_meta($_POST['input_introduction_id'],'last_name',true) ?> <?php echo get_user_meta($_POST['input_introduction_id'],'first_name',true) ?> " readonly>

                    
                <?php }else{ ?>
                    <?php 
                        $intoroduct = "";
                        if($_POST['input_introduction_id'] != ""){
                            $intoroduct = $_POST['input_introduction_id'];
                        }else{
                            $intoroduct = $_POST['input_introduction_name'];
                        }
                        ?>
                    <!-- <input type="text" name="input_introduction_name" id="" value="<?php echo $introduction_id?>"> -->
                    <input type="text" name="input_introduction_name" id="" value="<?php echo $intoroduct?>">
                    <div class=""> 登録者でない場合はこちらに入力してください</div>
                <?php } ?>

                
            </div>
            <?php */ ?>

            <div class="user-table-flex">
                <div class="user-table-item">特記事項</div>
                <textarea class="user-input-remarks" name="input_remarks" id="" value=""><?php echo $input_remarks?></textarea>
            </div>

            <div class="btn-flex" style="">
                <button type="button" id="form-submit" class="form-btn" onclick="check_user();">確認画面へ</button>
                <button class="form-btn gray"><a href="<?php echo getURLSetSlag("admin-member-list"); ?>">顧客一覧へ戻る</a></button>

            </div>
        </form>


    <?php }else if(isset($_POST["input_check_user_id"]) || $regist_error ) { ?>
        
        <div class="admin-title">登録確認</div>

        <?php if( $regist_error ) {?>
        <div class="result-font"><?php echo $result_msg; ?></div>
        <?php } ?>
        
        <form class="user-input-area" action="<?php echo getURLSetSlag("admin-member-regist"); ?>" method="post" name="post_new_user_input" id="post_new_user_input" onsubmit="return input_check()">

            <input type="hidden" name="" value="" id="hidden_input">
            <div class="user-table-flex">
                <div class="user-table-item">*名前</div>
                <div class="user-table-data">
                    <input class="data-check" type="text" name="input_last_name" placeholder="姓" value="<?php echo $_POST['input_last_name']?>" readonly>
                    <input class="data-check" type="text" name="input_first_name" placeholder="名" value="<?php echo $_POST['input_first_name']?>" readonly>
                </div>
            </div>

            <div class="user-table-flex">
                <div class="user-table-item">ナマエ</div>
                <div class="user-table-data">
                    <input class="data-check" type="text" name="input_last_name_kana" value="<?php echo $_POST['input_last_name_kana']?>" readonly>
                    <input class="data-check" type="text" name="input_first_name_kana" value="<?php echo $_POST['input_first_name_kana']?>" readonly>
                </div>
            </div>
            
            <div class="user-table-flex">
                <div class="user-table-item">*性別</div>
                <input class="data-check" type="hidden" name="input_user_sex" id="" value="<?php echo $_POST['input_user_sex']?>" readonly>
                <?php 
                    $disp_user_sex = "不明";
                    if($_POST['input_user_sex'] == "M"){
                        $disp_user_sex = "男性";
                    }else if($_POST['input_user_sex'] == "W"){
                        $disp_user_sex = "女性";
                    }
                    echo $disp_user_sex;
                ?>
            </div>
            
            <div class="user-table-flex">
                <div class="user-table-item">*連絡先</div>
                <input class="data-check user-tel-input" type="text" name="input_tel_1" id="" value="<?php echo $_POST['input_tel_1']?>" readonly> -
                <input class="data-check user-tel-input" type="text" name="input_tel_2" id="" value="<?php echo $_POST['input_tel_2']?>" readonly> -
                <input class="data-check user-tel-input" type="text" name="input_tel_3" id="" value="<?php echo $_POST['input_tel_3']?>" readonly>
            </div>
            
            <div class="user-table-flex">
                <div class="user-table-item">電話番号なし</div>
                <input type="checkbox" name="check_phone_none" id="" <?php if($check_phone_none) echo "checked"?> onclick='return false;' readonly>
            </div>

            <div class="user-table-flex">
                <div class="user-table-item">*郵便番号</div>
                <input class="data-check" type="text" name="input_post_no" id="" value="<?php echo $_POST['input_post_no']?>" readonly placeholder="ハイフン無し">
            </div>

            <div class="user-table-flex">
                <div class="user-table-item">*住所</div>
                <input class="data-check" type="text" name="input_address1" id="" value="<?php echo $_POST['input_address1']?>" readonly>
            </div>

            <div class="user-table-flex">
                <div class="user-table-item">マンション名など</div>
                <input class="data-check" type="text" name="input_address2" id="" value="<?php echo $_POST['input_address2']?>" readonly>
            </div>

            <div class="user-table-flex" >
                <div class="user-table-item">*メールアドレス</div>
                <div class="user-table-data">
                    <input class="data-check" type="email" name="input_user_email" value="<?php echo $_POST['input_user_email']?>" readonly>
                </div>
            </div>

            <div class="user-table-flex">
                <div class="user-table-item">LINE ID</div>
                <input class="data-check" type="text" name="input_user_line_id" id="" value="<?php echo $_POST['input_user_line_id']?>" readonly>
            </div>

            <div class="user-table-flex">
                <div class="user-table-item">生年月日</div>
                <input class="data-check user-born-input" type="text" name="input_user_born_year" id="" value="<?php echo $_POST['input_user_born_year']?>" readonly>年
                <input class="data-check user-born-input" type="text" name="input_user_born_month" id="" value="<?php echo $_POST['input_user_born_month']?>" readonly>月
                <input class="data-check user-born-input" type="text" name="input_user_born_day" id="" value="<?php echo $_POST['input_user_born_day']?>" readonly>日
            </div>

            <div class="user-table-flex">
                <div class="user-table-item">届け出日</div>
                <input class="data-check user-born-input" type="text" name="input_user_report_year" id="" value="<?php echo $_POST['input_user_report_year']?>" readonly>年
                <input class="data-check user-born-input" type="text" name="input_user_report_month" id="" value="<?php echo $_POST['input_user_report_month']?>" readonly>月
                <input class="data-check user-born-input" type="text" name="input_user_report_day" id="" value="<?php echo $_POST['input_user_report_day']?>" readonly>日
            </div>

            <?php 
                $t_inflow_title = "";
                // var_dump($_POST['input_inflow']);  //削除okd
                foreach ($spiritStatusArray as $key => $value) {
                    if($value['ID'] == $_POST['input_inflow']){
                        $t_inflow_title = $value["title"];
                        break;
                    }
                }
            ?>
            <div class="user-table-flex">
                <div class="user-table-item" class="user-table-item">流入元</div>
                
                <input class="data-check" type="hidden" name="input_inflow" id="" value="<?php echo $_POST['input_inflow']?>" readonly>
                <?php echo $t_inflow_title?>

            </div>

            <div class="user-table-flex">
                <div class="user-table-item">流入元追記</div>
                <input class="data-check" type="text" name="input_inflow_remarks" id="" value="<?php echo $_POST['input_inflow_remarks']?>" readonly>
            </div>

            <div class="user-table-flex">
                <div class="user-table-item">紹介者</div>
                <input class="data-check-double" type="hidden" name="input_introduction_id" id="" value="<?php if(isset($_POST['input_introduction_id'])){echo $_POST['input_introduction_id'];}?>" readonly>
                <input class="data-check-double" type="text" name="" id="" value="<?php if(isset($_POST['input_introduction_id'])){echo get_user_meta($_POST['input_introduction_id'],'user_unique_id',true);}?>" readonly>
                <input class="data-check-double" type="text" name="input_introduction_name" id="" value="<?php if(isset($_POST['input_introduction_name'])){echo $_POST['input_introduction_name'];}?>" readonly>
            </div>

            <div class="user-table-flex">
                <div class="user-table-item">特記事項</div>
                <textarea class="data-check user-input-remarks" name="input_remarks" id="" readonly><?php echo $_POST['input_remarks']?></textarea>
            </div>

            
            <div class="btn-flex" style="">
                <button type="submit" id="form-submit" class="form-btn orange"  >顧客登録</button>

                <button type="submit" id="form-back " class="form-btn gray" onclick="changeInputAndSubmit()">戻　る</button>
            </div>
        </form>

        <script>
            document.getElementById("form-submit").addEventListener("click", function() {
                var hiddenInput = document.getElementById('hidden_input');
                hiddenInput.name = 'create_member';
                hiddenInput.value = 'create_member';
                document.getElementById("post_new_user_input").value = "submit";
            });

            // 戻るボタン
            function changeInputAndSubmit() {
                // フォーム内のhidden inputのnameとvalueを変更
                var hiddenInput = document.getElementById('hidden_input');
                hiddenInput.name = 'create_back';
                hiddenInput.value = 'create_back';

                // フォームを送信
                document.getElementById('post_new_user_input').submit();
            }
        </script>
    <?php } else { ?>
        <div class="result-font">
            <?php 
                $regist_error = false;

                if(is_numeric($res)){

                    echo "登録しました ID:".get_user_meta($res, 'user_unique_id', true);
                }else if($res == "そのアドレスは既に使用されています。"){
                    echo $res;
                }else if($res["registed_id"] != ""){

                    // if(strpos($res["res_msg"] , "同一住所の為、家族として登録します") !== false){

                    //     echo "ID:".get_user_meta($res["registed_id"], 'user_unique_id', true)."の同一住所の為、家族として登録します";
                    //     echo "ID:".get_user_meta($res["res_data"], 'user_unique_id', true);
                    // }else{

                        echo "既に登録されています ID:".get_user_meta($res["registed_id"], 'user_unique_id', true);
                        $regist_error = true;
                    // }
                    
                }else{
                    
                    echo "登録に失敗しました";
                    $regist_error = true;
                }
            ?>
        </div>

        <?php if($regist_error || $res == "そのアドレスは既に使用されています。"){?>
            <form action="<?php echo getURLSetSlag("admin-member-regist"); ?>" method="post">
                <?php 
                    foreach ($_POST as $key => $value) {
                        if($key == "create_member") continue;
                        echo '<input type="hidden" name="' . htmlspecialchars($key, ENT_QUOTES, 'UTF-8') . '" value="' . htmlspecialchars($value, ENT_QUOTES, 'UTF-8') . '">' . "\n";
                    }
                
                ?>
                <button class="form-btn gray">入力内容に戻る</button>


            </form>
        <?php }else{?>

            <button class="form-btn gray"><a href="<?php echo getURLSetSlag("admin-member-list"); ?>">顧客一覧へ</a></button>
            
        <?php } ?>
    <?php } ?>
</div>
