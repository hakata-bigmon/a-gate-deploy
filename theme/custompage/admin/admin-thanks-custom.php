
<?php 

    require_once ("a-gate-functions.php");
    require_once (dirname(__FILE__)."/../../class/spiritTypeClass.php");


    $spiritType = new SpiritTypeClass(); //管理データ


    $spiritTypeArray = $spiritType->getSpiritType();


    //var_dump($spiritTypeArray);
?>

<div class="">


    <div class="admin-exorcism-button-area">

        <div class="admin-exorcism-menu-title-box"><div class="admin-exorcism-menu-title">サンクス設定</div></div>


        <div class="admin-exorcism-menu-status-box">
             <button type=“button” class="admin-exorcism-menu-status-button" onclick="location.href='<?php echo getURLSetSlag("admin-thanks-list"); ?>'">サンクスページURL一覧</button>
        </div>

    <?php 
    
         foreach ($spiritTypeArray as $key => $value) {
    ?>

         <div class="admin-exorcism-button-type-title">
            <?php echo $spiritType->getSpiritTypeName($key);?>
        </div>

         <div class="admin-exorcism-button-type-area">

            <?php 
                foreach ($value as $key_num => $value_num) {
            ?>

                <div class="admin-exorcism-menu-button-box">
                    <button type=“button” class="admin-exorcism-menu-button" onclick="location.href='<?php echo getURLSetSlag("admin-thanks-text"); ?>?type_id=<?php echo $value_num["ID"]; ?>'"><?php echo $value_num["title"]; ?></button>
                </div>
            <?php
                }
            ?>
         </div>
    <?php
         }
    ?>

   
        <div class="admin-exorcism-menu-status-box">
             <button type=“button” class="admin-exorcism-menu-status-button" onClick="window.open('https://a-gate-kanri.com/wp-admin/post.php?post=669&action=edit', '_blank')">サンクスメール　共通フッター作成</button>
        </div>

        <div class="admin-exorcism-menu-status-box">
             <button type=“button” class="admin-exorcism-menu-status-button" onClick="window.open('https://a-gate-kanri.com/wp-admin/post.php?post=670&action=edit', '_blank')">入力確認メールフッター作成</button>
        </div>

    </div>


</div>