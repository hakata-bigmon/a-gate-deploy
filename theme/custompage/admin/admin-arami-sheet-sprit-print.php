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


   
    $spiritStatusArray = $spiritSheet->getSpiritAdminStatusID('cpt_spirit_status');
    $spiritMemberStatusArray = $spiritSheet->getSpiritAdminStatusID('cpt_spirit_usestatus');

   // var_dump($arami_data);
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
</style>

<!-- カスタムJSファイルの読み込みをフッターに移動し、wp_enqueue_scriptを使用したほうがよいです -->

<div class="admin-user-table-area" style="max-width: 1000px;margin-left: auto;margin-right: auto;">

    <div class="admin-title" style="margin-bottom: 10px;text-align: center;">
        <div>
            <?php echo $arami_data["タイトル"]; ?>
            <?php if($arami_data["会員ステータス"] == SpiritUserClass::MEMBER_STATUS_COMPLETE){?>
                　結果
            <?php }?>
        </div>
        <div style="font-size: 21px;">
            <?php if($arami_data["実行日"] != ""){?>
                処理実行日　<?php echo $arami_data["実行日年月日"]; ?>
            <?php }?>
        </div>
    </div>

    
   
   

    
    
                
    <?php if($arami_data["登録者"] != ""){ ?>



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

        <div style="display: flex; flex-wrap: wrap; gap: 2px; margin-top: 20px;">
            
            <?php foreach($arami_data["登録者"] as $key => $value){ ?>

            <?php //for($j = 0; $j < 10; $j++){ ?>


                <?php 

                    /*if($j % 2 == 0)
                    {
                        $value = $arami_data["登録者"][1];
                    }else{
                        $value = $arami_data["登録者"][2];
                    }

                    $key = $j + 1;
                    */
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


                <?php if(!isset($_GET["print"])){ //リモート完全浄霊 ?>
                    <div class="admin-arami-sheet-detail-area"  <?php if($arami_data["施術"] != 7644){ //リモート完全浄霊 ?>style="max-width: 520px;"<?php }else{ ?>style="width: 650px;"<?php } ?>>

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

                            <div class="admin-arami-sheet-detail-right" style="height: 150px;padding-left: 5px;">
                                <div class="admin-arami-sheet-detail-right-img" style="min-width: 150px;">
                                    <?php if(count($img_url_array) > 0){ ?>
                                        <?php foreach($img_url_array as $img_key => $img_value){ ?>

                                            <img src="<?php echo $img_value;?>" alt="" style="height: auto;max-height: 150px;">
                                            <?php if($arami_data["施術"] == 7644){ //リモート完全浄霊 ?>

                                                <?php break;//前面だけ?>
                                            <?php } ?>
                                        <?php } ?>
                                    <?php } ?>
                                </div>
                            </div>

                            <div class="admin-arami-sheet-detail-input-area" style="margin-left: 8px;">
                                


                                <?php if($arami_data["施術"] == 7644){ //リモート完全浄霊 ?>
                                    <?php if(isset($spirt_kind_array[$value]["spirt_text"])){ ?>
                                    <div><?php echo nl2br($spirt_kind_array[$value]["spirt_text"]);?></div>
                                    <?php }else{ ?>
                                        <?php echo "";?>
                                    <?php } ?>
                                <?php }else{ ?>

                                    <?php for($i = 0; $i < SpiritUserClass::SPIRIT_KIND_MAX; $i++){ ?>
                                        <?php if(isset($spirt_kind_array[$value]["spirt_array"][$i]) && $spirt_kind_array[$value]["spirt_array"][$i] != 0){ ?>
                                            <div style="display: flex;color: black;font-size: 22px">
                                                <div style="width: 110px;"><?php echo $userClass->getSpiritTypeLabel($i);?></div>
                                                <div><?php if(isset($spirt_kind_array[$value]["spirt_array"][$i])){echo $spirt_kind_array[$value]["spirt_array"][$i];}else{echo 0;}?>体</div>
                                            </div>
                                        <?php } ?>
                                    <?php } ?>

                                    <div style="margin-top: 10px;">
                                        <?php if(isset($spirt_kind_array[$value]["spirt_text"])){ ?>
                                            <div style="border: 2px solid red;text-align: center;color: red;font-weight: 600;"><?php echo $spirt_kind_array[$value]["spirt_text"];?></div>
                                        <?php }else{ ?>
                                            <?php echo "";?>
                                        <?php } ?>
                                    </div>

                                <?php } ?>

                            </div>

                        </div>
                    </div>
                <?php }else{ ?>


                    <div>
                        <div class="admin-arami-sheet-detail-area"  style="border: 0;width: 300px;">

                            <div class="">
                                <div class="admin-arami-sheet-detail-left" style="">


                                    <div class="admin-arami-sheet-detail-name" style="border-bottom: 5px solid goldenrod;">
                                        No.　<font color="blue" style="font-weight: 600;font-size: 24px;"><?php echo $key;?></font>
                                    </div>
                                
                                    <div class="admin-arami-sheet-detail-name" style="margin-top: 30px;text-align: center;font-weight: 600;font-size: 18px;">
                                    <?php echo $user_name;?>
                                    </div>
                                </div>

                                <div class="" style="padding-left: 5px;text-align: center;">
                                    <div class="admin-arami-sheet-detail-right-img" style="max-width: 200px;margin: 0 auto;">
                                        <?php if(count($img_url_array) > 0){ ?>
                                            <?php foreach($img_url_array as $img_key => $img_value){ ?>
                                                <div style="text-align: center;margin-top: 10px;min-height: 320px;">
                                                    <img src="<?php echo $img_value;?>" alt="" style="width: 100%;height: auto;">
                                                </div>
                                            <?php } ?>
                                        <?php } ?>
                                    </div>
                                </div>

                            
                              

                            </div>
                        </div>
                    </div>




                <?php } ?>

            <?php } ?>
        </div>

       

    <?php } ?>

</div>
