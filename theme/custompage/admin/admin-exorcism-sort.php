


<?php 

    require_once ("a-gate-functions.php");
    require_once (dirname(__FILE__)."/../../class/spiritTypeClass.php");


    $spiritType = new SpiritTypeClass(); //管理データ




    //保存
    if(isset($_POST["order"]))
    {
        $sort_array = array();

        $sort_array = explode(',', $_POST["order"]);

        $spiritType->saveSpiritTypeSort($sort_array, $_POST["sort"]);
    }




    $spiritTypeArray = $spiritType->getSpiritType();


    //var_dump($spiritTypeArray);

   // var_dump($_POST);
?>

<div class="">


    <div class="admin-exorcism-button-area" >

        <div class="admin-exorcism-menu-title-box"><div class="admin-exorcism-menu-title">施術順番並べ替え</div></div>


        <?php 
            foreach ($spiritTypeArray as $key => $value) {
        ?>
       
            <div class="admin-spirit-edit-list-area" style="border: 1px solid black;margin-top: 50px;border-radius: 12px;padding: 10px;padding-bottom: 30px;">


                <form id="orderForm" action="<?php echo  getURLSetSlag( "admin-exorcism-sort" );?>" method="post" >
                    <input type="hidden" name="order" id="orderInput">
                    <input type="hidden" name="sort" id="sortNumber" value="<?php echo $key;?>">


                    <table id="adminSpiritEditTableID<?php echo $key;?>" class="adminSpiritEditTable" style="margin-top: 0;" >

                        <tbody>

                    
                            <?php 

                                $count = 0;

                                foreach ($value as $key_num => $value_num) {
                            ?>

                                 <tr data-id-<?php echo $key;?>="<?php echo $value_num["ID"];?>" <?php if($count % 2 == 0){ ?>class="admin-spirit-edit-row" <?php }?>>


                                    <td style="width: 60px;">
                                        <div class="admin-spirit-edit-list-id"><?php echo $key_num;?></div>
                                    </td>
                                    <td style="width: 30px;">
                                        <span class="admin-spirit-edit-updown" onclick="moveTypeUp(this,<?php echo $key; ?>)">⬆️</span>
                                    </td>
                                    <td style="width: 30px;">
                                        <span class="admin-spirit-edit-updown" onclick="moveTypeDown(this,<?php echo $key; ?>)">⬇️</span>
                                    </td>

                                    <td>

                                         <?php 
                                    
                                                $text_color = "#000000";
                                                $back_color = "#ffffff";

                                                if($back_color =="")
                                                {
                                                    $back_color = "#ffffff";
                                                }

                                                if($text_color =="")
                                                {
                                                    $text_color = "#000000";
                                                }
                                    
                                            ?>

                                        <div class="admin-spirit-edit-list-title" style="color:<?php echo $text_color;?>;"><div style="padding-left: 10px;padding-top: 5px;"><?php echo $value_num["title"];?></div></div>
                                    </td>

                                </tr>
                            <?php

                                    $count++;

                                }
                            ?>

                        </tbody>

                    </table>

                        
                        <?php 
                        
                            $type_name = $spiritType->getSpiritTypeName($key);

                        ?>
                        <input class="admin-spirit-edit-button" type="button" value="<?php echo $type_name;?>の表示並び順を保存する" onclick="saveOrderType(<?php echo $key;?>)"/>

                </form>

             </div>
    

        <?php } ?>


    </div>


</div>


<script>


//並び順ソート
function saveOrderType(type) {

    if (confirm("並び順をソートしても宜しいですか?")) {
        const rows = document.querySelectorAll('#adminSpiritEditTableID' + type + ' tbody tr');
        const order = Array.from(rows).map(row => row.getAttribute('data-id-' + type));
        document.getElementById('orderInput').value = order.join(',');
        document.getElementById('sortNumber').value = type;
        document.getElementById('orderForm').submit();
    }
}




//テーブル↑移動
function moveTypeUp(btn,type) {
    const row = btn.closest('tr');
    const prevRow = row.previousElementSibling;
    if (prevRow && prevRow.tagName === 'TR') {
        row.parentNode.insertBefore(row, prevRow);
        updateTypeRowColors(type);
    }
}
//テーブル↓移動
function moveTypeDown(btn,type) {
    const row = btn.closest('tr');
    const nextRow = row.nextElementSibling;
    if (nextRow && nextRow.tagName === 'TR') {
        row.parentNode.insertBefore(nextRow, row);
        updateTypeRowColors(type);
    }
}


//テーブルが移動しても色を入れなおす
function updateTypeRowColors(type) {
    const rows = document.querySelectorAll('#adminSpiritEditTableID' + type + ' tbody tr');
    rows.forEach((row, index) => {
        if (index % 2 === 0) {
            row.classList.add('admin-spirit-edit-row');
        } else {
            row.classList.remove('admin-spirit-edit-row');
        }
    });
}
  

</script>