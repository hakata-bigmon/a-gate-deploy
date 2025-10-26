
<?php 

    require_once ("a-gate-functions.php");
    require_once (dirname(__FILE__)."/../../class/spiritTypeClass.php");


    $spiritType = new SpiritTypeClass(); //管理データ

    $spiritTypeArray = $spiritType->getSpiritType();

 
   // echo "aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa";
  //  var_dump($_POST);

?>


<div class="">


    <div class="admin-exorcism-button-area">

        <div class="admin-exorcism-menu-title-box"><div class="admin-exorcism-menu-title">サンクスページURL一覧</div></div>

        <table class="admin-url-list-table">

    <?php 
    
         foreach ($spiritTypeArray as $key => $value) {
    ?>

            <?php 
                foreach ($value as $key_num => $value_num) {

                    $url_data =  $spiritType->encrypt($value_num["ID"]);

            ?>

                <tr>
                    <th><?php echo $value_num["title"]; ?></th>
                   
                    <td><a href="<?php echo  getURLSetSlag( "thanks" );?>?type_id=<?php echo $value_num["ID"];?>"  target="_blank"><?php echo  getURLSetSlag( "thanks" );?>?type=<?php echo $url_data;?></a></td>
                </tr>

            <?php
                }
            ?>
        
    <?php
         }
    ?>

        </table>


    </div>


</div>