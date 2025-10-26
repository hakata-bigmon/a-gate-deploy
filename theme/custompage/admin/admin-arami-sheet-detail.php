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

    
    //var_dump($arami_data);
?>

<!-- DataTables用のCSSとJavaScriptを追加 -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
<script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>

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
</style>

<!-- カスタムJSファイルの読み込みをフッターに移動し、wp_enqueue_scriptを使用したほうがよいです -->

<div class="admin-user-table-area">

    <div class="admin-title">
        <?php echo $arami_data["タイトル"]; ?>　粗見シート詳細
    </div>

    <div class="admin-preview-button-flex" style="justify-content: left;">
        

        <?php if(!isset($_GET["detail"])){ ?>
            <div class="admin-preview-button-flex-box">
                <button class="admin-preview-button" type="button" onclick="location.href='<?php echo getURLSetSlag('admin-arami-sheet-detail'); ?>?sheet_id=<?php echo $sheet_id; ?>&detail=true'" style="color: black;background-color: lemonchiffon;width: 200px;height: 30px;">粗見シート確認</button>
            </div>
        <?php }else{ ?>
            <div class="admin-preview-button-flex-box">
                <button class="admin-preview-button" type="button" onclick="location.href='<?php echo getURLSetSlag('admin-arami-sheet-detail'); ?>?sheet_id=<?php echo $sheet_id; ?>'" style="color: black;background-color: lemonchiffon;width: 220px;height: 30px;">粗見シート詳細（個別編集）</button>
            </div>
        <?php } ?>

        
        <div class="admin-preview-button-flex-box">
            <form method="post" action="<?php echo getURLSetSlag('admin-arami-sheet-edit'); ?>" style="margin: 0;">
                <input type="hidden" name="arami_sheet_id" value="<?php echo $sheet_id; ?>">
                <button class="admin-preview-button" type="submit" style="color: black;background-color: lemonchiffon;width: 220px;height: 30px;">粗見シート詳細(一括編集)</button>
            </form>
        </div>
        
        <?php if(isset($_GET["detail"])){ ?>
            
            <div class="admin-preview-button-flex-box">
                <button class="admin-preview-button" type="button" onclick="location.href='<?php echo getURLSetSlag('admin-arami-sheet-sprit-detail-input'); ?>?sheet_id=<?php echo $sheet_id; ?>'" style="color: black;background-color: lemonchiffon;width: 200px;height: 30px;">結果入力・印刷</button>
            </div>
        <?php }else{ ?>
            
        <?php } ?>

        <?php if($arami_data["登録者"] != ""){ ?>
            <div class="admin-preview-button-flex-box">
                <button class="admin-preview-button" type="button" onclick="location.href='<?php echo getURLSetSlag('admin-arami-sheet-target-sort'); ?>?sheet_id=<?php echo $sheet_id; ?>'" style="color: black;background-color: lemonchiffon;width: 200px;height: 30px;">対象者並べ替え</button>
            </div>
        <?php } ?>
        <div class="admin-preview-button-flex-box">
            <button class="admin-preview-button" type="button" onclick="location.href='<?php echo getURLSetSlag('admin-arami-sheet-list'); ?>'" style="color: black;background-color: aliceblue;width: 260px;height: 30px;">粗見用シート一覧に戻る</button>
        </div>
       
    </div>


   
    <?php if(!isset($_GET["detail"])){ ?>
        <?php if($arami_data["登録者"] != ""){ ?>


            <div class="admin-arami-sheet-detail-disp-table-area">
                <div class="column-toggle-area">
                    <label><input type="checkbox" checked data-column="0">ID</label>
                    <label><input type="checkbox" checked data-column="1">対象者</label>
                    <label><input type="checkbox" checked data-column="2">誕生日</label>
                    <label><input type="checkbox" checked data-column="3">依頼日</label>
                    <label><input type="checkbox" checked data-column="4">実行日</label>
                    <label><input type="checkbox" checked data-column="5">実行予定日</label>
                    <label><input type="checkbox" checked data-column="6">申込者</label>
                    <label><input type="checkbox" checked data-column="7">連絡先</label>
                    <label><input type="checkbox" checked data-column="8">メールアドレス</label>
                    <label><input type="checkbox" checked data-column="9">ステータス</label>
                    <label><input type="checkbox" checked data-column="10">会員ステータス</label>
                    <label><input type="checkbox" checked data-column="11">依頼内容追記</label>
                    
                </div>
            </div>


            <table id="arami-sheet-table" class="display">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>対象者</th>
                        <th>誕生日</th>
                        <th>依頼日</th>
                        <th>実行日</th>
                        <th>実行予定日</th>
                        <th>申込者</th>
                        <th>連絡先</th>
                        <th>メールアドレス</th>
                        <th>ステータス</th>
                        <th>会員ステータス</th>
                        <th>依頼内容追記</th>
                        
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($arami_data["登録者"] as $key => $value){ ?>
                        <?php 
                            $user_id = get_field('acf_purespirit_id',$value);
                        
                            $sheet_data = $userClass->getUserSpritApplicantSheet($user_id,$value);
                        
                            //申込者のユーザーデータが必要
                            $user_data = $userClass->getUserAcountData($user_id);
                            
                            //var_dump($sheet_data);
                        ?>

                        <?php if($sheet_data == "")continue; ?>

                        <tr>
                            <td><a href="<?php echo getURLSetSlag('admin-spirit-detail'); ?>?sheet_name=<?php echo $sheet_data[$value]["ID"]; ?>&user_id=<?php echo $user_id; ?>" target="_blank"><?php echo $sheet_data[$value]["ID"];?></a></td>
                            <td>
                                <?php if($sheet_data[$value]["対象者"]["対象者情報"] == false){ ?>
                                    <?php echo $sheet_data[$value]["フル名前"];?>
                                <?php }else{ ?>
                                    <?php echo $sheet_data[$value]["対象者"]["フル名前"];?>
                                <?php }?>
                            </td>
                            <td>
                                <?php if($sheet_data[$value]["対象者"]["対象者情報"] == false){ ?>
                                    <?php echo $user_data["誕生日年月日"];?>
                                <?php }else{ ?>
                                    <?php echo $sheet_data[$value]["対象者"]["誕生日年月日"];?>
                                <?php }?>
                            </td>
                            <td><?php echo $sheet_data[$value]["依頼日年月日"];?></td>
                            <td>
                                <input type="date" name="acf_purespirit_execution_date" value="<?php echo $sheet_data[$value]["実行日"];?>">
                            </td>
                            <td>
                                <input type="date" name="acf_purespirit_execution_confirmation_date" value="<?php echo $sheet_data[$value]["実行予定日"];?>">
                            </td>
                            <td><a href="<?php echo getURLSetSlag('admin-member-edit'); ?>?user_id=<?php echo $user_id; ?>" target="_blank"><?php echo $sheet_data[$value]["フル名前"];?></a></td>
                            <td>
                                <?php echo $user_data["電話番号"];?>
                            </td>
                            
                            <td>
                                <?php echo $user_data["メール"];?>
                            </td>
                        
                            <td>
                                <select name="acf_purespirit_status" id="">
                                    <option value="">未設定</option>

                                    <?php
                                        foreach ($spiritStatusArray as $array_key => $array_value) {
                                    ?>
                                            <option value="<?php echo $array_value["ID"];?>" <?php if($sheet_data[$value]["管理者ステータス"] == $array_value["ID"]){ echo "selected"; }?>><?php echo $array_value["title"]?></option>
                                    <?php } ?>

                                </select>
                            </td>
                            <td>

                                <select name="acf_purespirit_user_status" id="">
                                    <option value="">未設定</option>

                                    <?php
                                        foreach ($spiritMemberStatusArray as $array_key => $array_value) {
                                    ?>
                                            <option value="<?php echo $array_value["ID"];?>" <?php if($sheet_data[$value]["会員ステータス"] == $array_value["ID"]){ echo "selected"; }?>><?php echo $array_value["title"]?></option>
                                    <?php } ?>
                                </select>

                            </td>
                            
                            <td style="text-align: left;">
                                <textarea style="width: 800px; height: 100px;" name="acf_purespirit_add_text"><?php echo $sheet_data[$value]["依頼内容追記"];?></textarea>
                            </td>
                        
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } ?>
    <?php }else{ ?>

    
        <div class="admin-arami-sheet-edit-area" style="margin-top: 30px;">
                
            <div class="user-table-flex">
                <div class="user-table-item" style="font-size: 24px;">シート名</div>
                <?php echo $arami_data["タイトル"];?>
            </div>

            <div class="user-table-flex">
                <div class="user-table-item" style="font-size: 24px;">施術</div>
                <?php echo $spiritTypeArray[$arami_data["施術"]]["title"];?>
            </div>

            <div class="user-table-flex">
                <div class="user-table-item" style="font-size: 24px;">実行日</div>
                <?php echo $arami_data["実行日年月日"];?>
            </div>

            <div class="user-table-flex">
                <div class="user-table-item" style="font-size: 24px;">実行予定日</div>
                <?php echo $arami_data["実行予定日年月日"];?>
            </div>

            <div class="user-table-flex">
                <div class="user-table-item" style="font-size: 24px;">管理ステータス</div>
                <?php if($arami_data["ステータス"] != "" && isset($spiritStatusArray[$arami_data["ステータス"]])){ ?>
                    <?php echo $spiritStatusArray[$arami_data["ステータス"]]["title"];?>
                <?php }else{ ?>
                    未設定
                <?php } ?>
            </div>

            <div class="user-table-flex">
                <div class="user-table-item" style="font-size: 24px;">会員ステータス</div>
                <?php if($arami_data["会員ステータス"] != "" && isset($spiritMemberStatusArray[$arami_data["会員ステータス"]])){ ?>
                    <?php echo $spiritMemberStatusArray[$arami_data["会員ステータス"]]["title"];?>
                <?php }else{ ?>
                    未設定
                <?php } ?>
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

                <div class="admin-arami-sheet-detail-area" <?php if($arami_data["施術"] == 7644){ //リモート完全浄霊 ?>style="width: 700px;"<?php }else{ ?>style="width: 540px;"<?php } ?>>

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
                            <div class="admin-arami-sheet-detail-name">
                            
                                <form method="post" action="<?php echo getURLSetSlag('users/user-spirit-question-edit'); ?>?check_user=<?php echo $user_id; ?>" target="_blank">
                                    <input type="hidden" name="sheet_id" value="<?php echo $value; ?>">
                                <button class="admin-preview-button" type="submit" style="color: black;background-color: lemonchiffon;width: 140px;height: 30px;">質問シート編集</button>
                                </form>
                            </div>
                        </div>

                       
                        

                        <?php if($arami_data["施術"] == 7644){ //リモート完全浄霊 ?>
                            <div class="admin-arami-sheet-detail-right">
                                <div class="admin-arami-sheet-detail-right-img" style="display: flex;max-width: 300px;">
                                    <?php if(count($img_url_array) > 0){ ?>
                                        <?php foreach($img_url_array as $img_key => $img_value){ ?>
                                            <img src="<?php echo $img_value;?>" alt="" style="width: 100%;height: auto;max-height: 150px;margin-left: 5px;">
        
                                        <?php } ?>
                                    <?php } ?>
                                </div>
                            </div>
                        <?php }else{ //リモート一斉浄霊 ?>
                            <div class="admin-arami-sheet-detail-right">
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


            <?php } ?>

        <?php } ?>



    <?php } ?>
</div>

<script>
$(document).ready(function() {
    // テーブルが存在するか確認
    if ($('#arami-sheet-table').length === 0) {
        return; // テーブルがなければ何もしない
    }

    // LocalStorageのキーを定義（シートIDを含めると各シート固有の設定になります）
    var storageKey = 'aramiSheet_columnVisibility_' + '<?php echo $sheet_id; ?>';
    
    // 保存された設定を取得
    var storedSettings;
    try {
        var storedData = localStorage.getItem(storageKey);
        storedSettings = storedData ? JSON.parse(storedData) : {};
    } catch (e) {
        console.error('列設定の読み込みエラー:', e);
        storedSettings = {};
    }

    // DataTablesの初期化
    var table = $('#arami-sheet-table').DataTable({
        language: {
            url: "https://cdn.datatables.net/plug-ins/1.11.5/i18n/ja.json"
        },
        order: [[0, 'desc']],
        pageLength: 50,
        lengthMenu: [[10, 30, 50, 100, 500, -1], [10, 30, 50, 100, 500, "全件"]],
        searching: false,
        lengthChange: false,
        paging: false,
        autoWidth: false,
        scrollX: true,
        columnDefs: [
            {
                targets: '_all',
                className: 'dt-body-center dt-head-center'
            }
        ],
        // DataTables初期化完了時のコールバック
        initComplete: function() {
            // 保存されている設定を適用
            applyColumnSettings();
            
            // ヘッダー行の高さ修正
            fixHeaderHeight();
        }
    });

    // ヘッダー行の高さが0になる問題を修正するための処理
    function fixHeaderHeight() {
        $('#arami-sheet-table thead tr').css('height', 'auto');
        $('#arami-sheet-table thead tr').css('min-height', '30px');
    }
    
    // 保存された設定を列に適用
    function applyColumnSettings() {
        // 各チェックボックスに対して処理
        $('.column-toggle-area input[type="checkbox"]').each(function() {
            var columnIndex = parseInt($(this).attr('data-column'));
            
            // デフォルトではチェック済み、保存データでfalseの場合のみ非表示に
            var isVisible = storedSettings[columnIndex] !== false;
            
            // チェックボックスの状態を設定
            $(this).prop('checked', isVisible);
            
            try {
                // 列の表示/非表示を設定
                var column = table.column(columnIndex);
                if (column && column.visible) {
                    column.visible(isVisible);
                }
            } catch (e) {
                console.error('列の表示設定エラー:', e);
            }
        });
    }
    
    // 列表示設定を保存する
    function saveColumnSettings() {
        var settings = {};
        
        // 各チェックボックスの状態を取得
        $('.column-toggle-area input[type="checkbox"]').each(function() {
            var columnIndex = $(this).attr('data-column');
            settings[columnIndex] = $(this).prop('checked');
        });
        
        // LocalStorageに保存
        try {
            localStorage.setItem(storageKey, JSON.stringify(settings));
        } catch (e) {
            console.error('列設定の保存エラー:', e);
        }
    }

    // 列の表示/非表示を切り替える
    $('.column-toggle-area input[type="checkbox"]').on('change', function() {
        // チェックが1つだけなら外せない
        if ($('.column-toggle-area input[type="checkbox"]:checked').length === 0) {
            this.checked = true;
            return;
        }
        
        try {
            var columnIndex = parseInt($(this).attr('data-column'));
            var column = table.column(columnIndex);
            if (column && column.visible) {
                column.visible($(this).prop('checked'));
            }
            
            // 設定を保存
            saveColumnSettings();
            
            // 列の表示/非表示切替後にヘッダー行の高さを修正
            setTimeout(fixHeaderHeight, 10);
        } catch (e) {
            console.error('列の表示切替エラー:', e);
        }
    });
});
</script>