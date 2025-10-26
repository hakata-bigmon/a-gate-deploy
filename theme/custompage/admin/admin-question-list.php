


<?php 

    require_once ("a-gate-functions.php");
    require_once (dirname(__FILE__)."/../../class/spiritTypeClass.php");


    $spiritType = new SpiritTypeClass(); //管理データ


    $spiritTypeArray = $spiritType->getSpiritType();


    //var_dump($spiritTypeArray);
?>

<div class="">


    <div class="admin-exorcism-button-area">

        <div class="admin-exorcism-menu-title-box"><div class="admin-exorcism-menu-title">質問リスト</div></div>

        
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
                    <button type=“button” class="admin-exorcism-menu-button" onclick="location.href='<?php echo getURLSetSlag("admin-exorcism-questions"); ?>?type_id=<?php echo $value_num["ID"]; ?>'"><?php echo $value_num["title"]; ?></button>
                </div>
            <?php
                }
            ?>
         </div>
    <?php
         }
    ?>

   


    </div>


</div>