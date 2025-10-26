<?php 

    require_once ("a-gate-functions.php");
    require_once (dirname(__FILE__)."/../../class/spiritTypeClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritScheduleClass.php");


  
    $spiritTypeData = new SpiritTypeClass(); //浄霊タイプデータ
    $spiritScheduleData = new SpiritScheduleClass(); //スケジュールデータ


    if(isset($_POST["save"])){

        foreach($_POST as $key => $value){
            if(strpos($key, 'acf_pure_spirit_color_') === 0){
                $id = str_replace('acf_pure_spirit_color_', '', $key);
               
                $spiritTypeData->saveSpiritTypeColor($id, $_POST["acf_pure_spirit_color_" . $id]);
            }
        }

//$spiritTypeData->saveSpiritTypeColor($_POST["type_id"], $_POST["color"]);
    }

    $spiritType = $spiritTypeData->getSpiritType();
    //var_dump($spiritType);
    foreach($spiritType as $key => $value){
            
        if($key != SpiritTypeClass::SPIRIT_TYPE_NAME_SODAN && $key != SpiritTypeClass::SPIRIT_TYPE_NAME_DAY){
            unset($spiritType[$key]);
        }
    }

    

  ?>

<div class="admin-user-table-area">

    <div class="admin-title">
        <?php echo  "スケジュール　カラー設定"; ?>
    </div>

    <div class="admin-spirit-sheets-button-flex" style="max-width: 800px;margin-left: auto;margin-right: auto;">

       

        <div class="admin-preview-button-flex">

            

            <div class="admin-preview-button-flex-box">
                <button class="admin-preview-button" type="button" onclick="location.href='<?php echo getURLSetSlag('admin-profile-menu'); ?>'">設定に戻る</button>
            </div>

           
       
        </div>

     </div>


    <form method="post" action="<?php echo getURLSetSlag('admin-schedule-color-setting'); ?>" onsubmit="return saveColors(event);">

    <input type="hidden" name="save" value="">
        <div style="display: flex; justify-content: center; margin: 20px 0;">
            <table class="admin-table" style="border: 2px solid #ccc; border-collapse: separate; border-spacing: 4px;">
                <thead>
                    <tr>
                        <th style="border: 2px solid #ccc; padding: 8px; background-color: #f0f0f0;">施術名</th>
                        <th style="border: 2px solid #ccc; padding: 8px; background-color: #f0f0f0;">表示色</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($spiritType as $key => $value): ?>
                        <?php foreach($value as $key2 => $value2): ?>
                            <tr data-type="<?php echo $key; ?>" data-num="<?php echo $key2; ?>">
                                <td style="border: 2px solid #ccc; padding: 8px; font-weight: 600;background-color: <?php echo $value2["color"]; ?>;"><?php echo $value2["title"]; ?></td>
                                <td style="border: 2px solid #ccc; padding: 8px; background-color: white;">
                                    <input type="color" name="acf_pure_spirit_color_<?php echo $value2["ID"];?>" value="<?php echo $value2["color"]; ?>" 
                                        onchange="updateColor('<?php echo $key; ?>', '<?php echo $key2; ?>', this.value)">
                                    <span><?php echo $value2["color"]; ?></span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div style="text-align: center; margin: 20px 0;">
            <button type="submit" style="font-size: 24px;height: 48px;width: 500px;padding: 8px 16px; background-color: #0073aa; color: white; border: none; border-radius: 4px; cursor: pointer;">
                保存する
            </button>
        </div>
    </form>
    
    <script>
    function updateColor(type, num, color) {
        // タイトルセルの背景色を更新
        const titleCell = document.querySelector(`tr[data-type="${type}"][data-num="${num}"] td:first-child`);
        if (titleCell) {
            titleCell.style.backgroundColor = color;
        }
        // 色コードの表示も更新
        const colorSpan = document.querySelector(`tr[data-type="${type}"][data-num="${num}"] td:last-child span`);
        if (colorSpan) {
            colorSpan.textContent = color;
        }
    }

    function saveColors(event) {
        if (!confirm('色の設定を保存してもよろしいですか？')) {
            event.preventDefault();
            return false;
        }
        return true;
    }
    </script>

    <?php
    // POSTデータの処理
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        foreach ($_POST as $key => $value) {
            if (strpos($key, 'acf_pure_spirit_color_') === 0) {
                $id = str_replace('acf_pure_spirit_color_', '', $key);
                // ここで色の更新処理を実装
                // update_field('acf_pure_spirit_color', $value, $id);
            }
        }
        echo '<div style="text-align: center; color: green; margin: 10px 0;">色の設定を保存しました。</div>';
    }
    ?>

</div>


