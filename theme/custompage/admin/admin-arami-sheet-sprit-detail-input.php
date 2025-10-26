<?php
    require_once ("a-gate-functions.php");
    require_once (dirname(__FILE__)."/../../class/spiritTypeClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritSheetClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritUserClass.php");

    $spiritType = new SpiritTypeClass(); //管理データ
    $spiritTypeArray = $spiritType->getSpiritTypeKeyTypeNum();

    $spiritSheet = new SpiritSheetClass(); //管理データ
    //ユーザークラス
    $userClass = new SpiritUserClass(); //ユーザー管理
    // var_dump($_POST);

    $sheet_id = "";


    

    //シートID
    if(isset($_GET["sheet_id"])){
        $sheet_id = $_GET["sheet_id"];
    }

    if($sheet_id != ""){
        $arami_data = $spiritSheet->setAramiSheet($sheet_id);
    }


    if(isset($_POST["save"]) && $sheet_id != ""){

        $spirt_array = array();

        //var_dump($_POST);

        foreach($arami_data["登録者"] as $key => $value){

            if(isset($_POST["arami_sprit_detail_input_array_".$value])){
                $spirt_array[$value]["spirt_array"] = $_POST["arami_sprit_detail_input_array_".$value];
                $spirt_array[$value]["spirt_text"] = $_POST["arami_sprit_detail_input_text_".$value]; // nl2br()を削除
            }else{
                $spirt_array[$value]["spirt_array"] = array();
                $spirt_array[$value]["spirt_text"] = $_POST["arami_sprit_detail_input_text_".$value]; // nl2br()を削除
            }
        }

       

        //var_dump($spirt_array_json);
        if(isset($_POST["arami_sprit_detail_input_array_".$value])){
            $spirt_array_json = json_encode($spirt_array,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            update_field('arami_sprit_detail_input_array',$spirt_array_json,$sheet_id);
        }else{
            $spirt_array_json = $spirt_array;
            update_field('acf_arami_sheet_sprit_text_area',$spirt_array_json,$sheet_id);
        }
    }


    $spiritStatusArray = $spiritSheet->getSpiritAdminStatusID('cpt_spirit_status');
    $spiritMemberStatusArray = $spiritSheet->getSpiritAdminStatusID('cpt_spirit_usestatus');

    //var_dump($arami_data);
?>

<style>
table.dataTable {
    border-collapse: collapse;
    width: 100%;
}
table.dataTable th, table.dataTable td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: center;
}
table.dataTable thead th {
    background-color: #f5f5f5;
    border: 1px solid #ddd;
    position: sticky;
    top: 0;
    z-index: 1;
}
table.dataTable tbody tr:nth-child(even) {
    background-color: #f9f9f9;
}
table.dataTable tbody tr:hover {
    background-color: #f5f5f5;
}

/* 列表示切替エリアのスタイル */
.column-toggle-area {
    margin: 20px 0;
    padding: 10px;
    background: #f5f5f5;
    border: 1px solid #ddd;
    border-radius: 4px;
}
.column-toggle-area label {
    margin-right: 15px;
    cursor: pointer;
}
.column-toggle-area input[type="checkbox"] {
    margin-right: 5px;
}

/* ヘッダー行の高さを強制 */
table.dataTable thead tr {
    height: auto !important;
    min-height: 30px !important;
}
table.dataTable thead th {
    height: auto !important;
    min-height: 30px !important;
}

/* 保存ボタンのスタイル */
input[type="submit"][value="保存"] {
    margin-top: 10px;
    padding: 12px 30px;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    transition: all 0.3s ease;
}

input[type="submit"][value="保存"]:hover {
    background-color: #45a049;
    box-shadow: 0 4px 8px rgba(0,0,0,0.3);
    transform: translateY(-1px);
}

input[type="submit"][value="保存"]:active {
    transform: translateY(0);
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
}

/* ボタンコンテナのスタイル */
.admin-button-container {
    display: flex;
    gap: 15px;
    margin-bottom: 20px;
    flex-wrap: wrap;
    align-items: center;
}

/* 統一されたボタンスタイル */
.admin-button {
    padding: 10px 20px;
    border: 2px solid;
    border-radius: 8px;
    font-size: 14px;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-block;
    text-align: center;
    min-width: 120px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.admin-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

.admin-button:active {
    transform: translateY(0);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

/* フォーム内のボタン用のスタイル調整 */
.admin-button-container form {
    margin: 0;
}
</style>

<!-- カスタムJSファイルの読み込みをフッターに移動し、wp_enqueue_scriptを使用したほうがよいです -->

<div class="admin-user-table-area">

    <div class="admin-title">
        <?php echo $arami_data["タイトル"]; ?>　粗見シート詳細
    </div>

    <div class="admin-preview-button-flex" style="justify-content: left;">
        


        <div class="admin-preview-button-flex-box">
            <form method="post" action="<?php echo getURLSetSlag('admin-arami-sheet-edit'); ?>" style="margin: 0;">
                <input type="hidden" name="arami_sheet_id" value="<?php echo $sheet_id; ?>">
                <button class="admin-preview-button" type="submit" style="color: black;background-color: lemonchiffon;width: 220px;height: 30px;">粗見シート詳細(一括編集)</button>
            </form>
        </div>

        <div class="admin-preview-button-flex-box">
            <div class="admin-preview-button-flex-box">
                <button class="admin-preview-button" type="button" onclick="location.href='<?php echo getURLSetSlag('admin-arami-sheet-detail'); ?>?sheet_id=<?php echo $sheet_id; ?>'" style="color: black;background-color: lemonchiffon;width: 220px;height: 30px;">粗見シート詳細（個別編集）</button>
            </div>
        </div>
        
        <div class="admin-preview-button-flex-box">
            <button class="admin-preview-button" type="button" onclick="location.href='<?php echo getURLSetSlag('admin-arami-sheet-detail'); ?>?sheet_id=<?php echo $sheet_id; ?>&detail=true'" style="color: black;background-color: lemonchiffon;width: 200px;height: 30px;">シート確認へ</button>
        </div>

     
        <div class="admin-preview-button-flex-box">
            <button class="admin-preview-button" type="button" onclick="location.href='<?php echo getURLSetSlag('admin-arami-sheet-list'); ?>'" style="color: black;background-color: aliceblue;width: 260px;height: 30px;">粗見用シート一覧に戻る</button>
        </div>
       
    </div>


   
   

    
    
                
    <?php if($arami_data["登録者"] != ""){ ?>


        <div class="admin-button-container" style="margin-top: 20px;">
            <form method="post" action="<?php echo getURLSetSlag('admin-arami-sheet-sprit-print'); ?>?sheet_id=<?php echo $sheet_id; ?>" target="_blank">
                <input type="hidden" name="arami_sheet_id" value="<?php echo $sheet_id; ?>">
                <button class="admin-button" type="submit" style="background-color: #2196f3; color: white; border-color: #1976d2;">
                    結果　印刷ページ
                </button>
            </form>

            <?php if($arami_data["施術"] == 7644){ //リモート完全浄霊 ?>
                <form method="post" action="<?php echo getURLSetSlag('admin-arami-sheet-sprit-print'); ?>?sheet_id=<?php echo $sheet_id; ?>&print=overall_photo_list" target="_blank">
                    <input type="hidden" name="arami_sheet_id" value="<?php echo $sheet_id; ?>">
                    <button class="admin-button" type="submit" style="background-color: #ff9800; color: white; border-color: #f57c00;">
                        一覧　印刷ページ
                    </button>
                </form>
            <?php } ?>
        </div>


        <?php 


            $spirt_kind_json = "";

            if($arami_data["施術"] != 7644){ //リモート完全浄霊 
                $spirt_kind_json = get_field('arami_sprit_detail_input_array',$sheet_id); 
            }else{
                $spirt_kind_json = get_field('acf_arami_sheet_sprit_text_area',$sheet_id);
            }

            //デコード
            if($spirt_kind_json != ""){
                //echo $spirt_kind_json;
                if($arami_data["施術"] != 7644){
                    $spirt_kind_array = json_decode($spirt_kind_json,true);
                }else{
                    $spirt_kind_array = $spirt_kind_json;
                }
            }else{
                $spirt_kind_array = array();
            }

           
        ?>

        <form method="post" action="<?php echo getURLSetSlag('admin-arami-sheet-sprit-detail-input'); ?>?sheet_id=<?php echo $sheet_id; ?>">


            <input type="hidden" name="arami_sheet_id" value="<?php echo $sheet_id; ?>">
            <input type="hidden" name="save" value="1">

            <input type="submit" value="保存" style="margin-top: 40px; width: 180px;padding: 12px 30px; background-color: #4CAF50; color: white; border: none; border-radius: 5px; font-size: 16px; font-weight: bold; cursor: pointer; box-shadow: 0 2px 4px rgba(0,0,0,0.2); transition: all 0.3s ease;">

            <?php foreach($arami_data["登録者"] as $key => $value){ ?>

                <?php 

                    
                    $user_id = get_field('acf_purespirit_id',$value);
                
                    $sheet_data = $userClass->getUserSpritApplicantSheet($user_id,$value);
                
                    //申込者のユーザーデータが必要
                    $user_data = $userClass->getUserAcountData($user_id);

                    if($sheet_data == "")continue;

                    //var_dump($sheet_data);

                    //本人かどうかでデータが違う

                    $user_name = "";
                    $user_kana = "";
                    $user_birthday = "";
                    $user_birthday_year = "";
                    $user_birthday_month = "";
                    $user_birthday_day = "";
                    $relationship = "";
                    
                    if($sheet_data[$value]["対象者"]["対象者情報"] == false){

                        $user_name = $sheet_data[$value]["フル名前"];
                        $user_kana = $sheet_data[$value]["フルナマエ"];
                        $user_birthday = $user_data["誕生日年月日"];
                        $user_birthday_year = $user_data["誕生日年"];
                        $user_birthday_month = $user_data["誕生日月"];
                        $user_birthday_day = $user_data["誕生日日"];
                        $relationship = "本人";
                    }else{

                        $user_name = $sheet_data[$value]["対象者"]["フル名前"];
                        $user_kana = $sheet_data[$value]["対象者"]["フルナマエ"];
                        $user_birthday = $sheet_data[$value]["対象者"]["誕生日年月日"];
                        $user_birthday_year = $sheet_data[$value]["対象者"]["誕生日年"];
                        $user_birthday_month = $sheet_data[$value]["対象者"]["誕生日月"];
                        $user_birthday_day = $sheet_data[$value]["対象者"]["誕生日日"];
                        $relationship = $sheet_data[$value]["対象者"]["関係"];
                    }

                    //誕生日から年齢を計算
                    $user_age = "";
                    if($user_birthday != ""){
                        $birth = new DateTime($user_birthday_year ."-".$user_birthday_month ."-".$user_birthday_day);
                        $today = new DateTime();
                        $age = $birth->diff($today);
                        $user_age = "(" .$age->y . "歳)";
                    }

                    //質問配列から確定画像を選択
                    $sheet_question_id =  $spiritSheet->getSpiritSheetAnswer( $value );

                    //var_dump($sheet_question_id);
                    $img_url_array = array();

                    if(count($sheet_question_id) > 0)
                    {
                        foreach($sheet_question_id as $img_key => $img_value)
                        {
                            //echo get_field('acf_question_type',$key);
                            //質問番号
                            if( get_field('acf_question_type',$img_key) == 13) //リモート一斉浄霊用
                            {
                                $img_url_array[] = get_field('acf_questionqnser_img_choice_url',$img_value);
                            }
                            
                        }
                    }

                    //var_dump($img_url_array);

                    

                ?>

                <div class="admin-arami-sheet-detail-area"  <?php if($arami_data["施術"] != 7644){ //リモート完全浄霊 ?>style="max-width: 520px;"<?php }else{ ?>style="max-width: 800px;"<?php } ?>>

                    <div class="admin-arami-sheet-detail-flex">
                        <div class="admin-arami-sheet-detail-left" style="width: 150px;">


                            <div class="admin-arami-sheet-detail-name" style="border-bottom: 5px solid goldenrod;">
                                No.　<font color="blue" style="font-weight: 600;font-size: 24px;"><?php echo $key;?></font>
                            </div>
                            <div class="admin-arami-sheet-detail-name" style="font-size: 12px;margin-top: 10px;text-align: center;">
                                <?php echo $user_birthday .$user_age;?>
                            </div>
                            
                            <div class="admin-arami-sheet-detail-name" style="text-align: center;">
                            <?php echo $user_kana;?>
                            </div>
                            
                        
                            <div class="admin-arami-sheet-detail-name" style="margin-top: 30px;text-align: center;font-weight: 600;font-size: 18px;">
                            <?php echo $user_name;?>
                            </div>
                        </div>


                        <?php if($arami_data["施術"] == 7644){ //リモート完全浄霊 ?>
                            <div class="admin-arami-sheet-detail-right" style="height: 150px;">
                                <div class="admin-arami-sheet-detail-right-img" style="">
                                    <?php if(count($img_url_array) > 0){ ?>
                                        <?php foreach($img_url_array as $img_key => $img_value){ ?>
                                            <img src="<?php echo $img_value;?>" alt="" style="width: 100%;height: auto;max-height: 150px;">
                                            <?php break;//前面だけ?>
                                        <?php } ?>
                                    <?php } ?>
                                </div>
                            </div>

                        <?php }else{ //リモート一斉浄霊 ?>

                            <div class="admin-arami-sheet-detail-right" style="height: 150px;">
                                <div class="admin-arami-sheet-detail-right-img" style="">
                                    <?php if(count($img_url_array) > 0){ ?>
                                        <?php foreach($img_url_array as $img_key => $img_value){ ?>

                                            <img src="<?php echo $img_value;?>" alt="" style="width: 100%;height: auto;max-height: 150px;">
                                        <?php } ?>
                                    <?php } ?>
                                </div>
                            </div>

                        <?php } ?>


                        <div class="admin-arami-sheet-detail-input-area" style="margin-left: 20px;">
                            

                        <?php if($arami_data["施術"] == 7644){ //リモート完全浄霊 ?>

                            <textarea name="arami_sprit_detail_input_text_<?php echo $value;?>" cols="60" rows="8" placeholder="コメントを入力"><?php if(isset($spirt_kind_array[$value]["spirt_text"])){echo htmlspecialchars($spirt_kind_array[$value]["spirt_text"]);}else{echo "";}?></textarea>

                                                    
                        <?php }else{ //リモート一斉浄霊 ?>

                            <?php for($i = 0; $i < SpiritUserClass::SPIRIT_KIND_MAX; $i++){ ?>
                                <div style="display: flex;color: black;font-weight: 600;">
                                    <div style="width: 70px;"><?php echo $userClass->getSpiritTypeLabel($i);?></div>
                                    <div><input type="number" name="arami_sprit_detail_input_array_<?php echo $value;?>[]" value="<?php if(isset($spirt_kind_array[$value]["spirt_array"][$i])){echo $spirt_kind_array[$value]["spirt_array"][$i];}else{echo 0;}?>" style="width: 30px;">体</div>
                                </div>
                            <?php } ?>

                            <div style="margin-top: 10px;">
                                <input type="text" name="arami_sprit_detail_input_text_<?php echo $value;?>" value="<?php if(isset($spirt_kind_array[$value]["spirt_text"])){echo strip_tags($spirt_kind_array[$value]["spirt_text"]);}else{echo "";}?>" placeholder="コメントを入力">
                            </div>
                        <?php } ?>

                        </div>

                    </div>
                </div>


            <?php } ?>

        </form>

    <?php } ?>

</div>
