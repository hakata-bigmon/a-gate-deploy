
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

        <div class="admin-exorcism-menu-title-box"><div class="admin-exorcism-menu-title">入力フォームURL一覧</div></div>

        <table class="admin-url-list-table">

    <?php 
    
         foreach ($spiritTypeArray as $key => $value) {
    ?>

            <?php 
                foreach ($value as $key_num => $value_num) {

                    $url_data =  $spiritType->encrypt($value_num["ID"]);

            ?>

                
                

                    <tr>
                        <?php /*<th <td rowspan="2" style="background:#fbfbfb"><?php echo $value_num["title"]; ?></th> */ ?>
                        <th <td style="background:#fbfbfb"><?php echo $value_num["title"]; ?></th>
                        <td>新規</td>
                        <td><a href="<?php echo  getURLSetSlag( "order-form" );?>?type_id=<?php echo $value_num["ID"];?>" target="_blank"><?php echo  getURLSetSlag( "order-form" );?>?type_id=<?php echo $url_data;?></a></td>
                    </tr>
                    <?php /*
                    <tr style="background-color: lightcyan;">
                        <td>登録済み</td>
                        <td><a href="<?php echo  getURLSetSlag( "order-form" );?>?type_id=<?php echo $value_num["ID"];?>&registered=true"  target="_blank"><?php echo  getURLSetSlag( "order-form" );?>?type_id=<?php echo $url_data;?>&amp;registered=true</a></td>
                    </tr>
                    */ ?>
               
            <?php
                }
            ?>
        
    <?php
         }
    ?>

        </table>


    </div>


</div>