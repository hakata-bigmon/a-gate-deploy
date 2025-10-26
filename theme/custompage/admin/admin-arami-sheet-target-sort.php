<?php

	require_once ("a-gate-functions.php");

	require_once (dirname(__FILE__)."/../../class/spiritTypeClass.php");
	require_once (dirname(__FILE__)."/../../class/spiritSheetClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritUserClass.php");

	$spiritType = new SpiritTypeClass(); //管理データ
	$spiritTypeArray = $spiritType->getSpiritType();

	$spiritSheet = new SpiritSheetClass(); //管理データ
	    //ユーザークラス
    $userClass = new SpiritUserClass(); //ユーザー管理
  // var_dump($_POST);


  $sheet_id = "";

  //シートID
  if(isset($_GET["sheet_id"])){
    $sheet_id = $_GET["sheet_id"];
  }

  //保存
  if(isset($_POST["sort_data"])){
    $sort_data = $_POST["sort_data"];
    $sort_data = stripslashes($sort_data); // バックスラッシュ除去
    $sort_data = json_decode($sort_data, true);
    // 並び替え・保存処理はここに
    $sort_data_array = array();

    foreach($sort_data as $key => $value){
        $sort_data_array[$value["no"]] = $value["value"];
    }

    //保存
    $spiritSheet->getAramiSheetTargetSort( $sheet_id , $sort_data_array );
  }

  if($sheet_id != ""){
    $arami_data = $spiritSheet->setAramiSheet($sheet_id);
  }

  //var_dump($_POST);
?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<script>
jQuery(function($) {
    $("#sortableTable").sortable({
        axis: "y",
        cursor: "move"
    });
    $("form").on("submit", function(e) {
        var order = [];
        $("#sortableTable tr").each(function(idx, el) {
            order.push({
                no: idx + 1,
                value: $(el).data("value")
            });
        });
        console.log('order:', order); // 配列の中身を確認
        console.log('JSON:', JSON.stringify(order)); // JSON文字列を確認
        $("input[name='sort_data']").val(JSON.stringify(order));
    });
});
</script>

<div class="admin-user-table-area">

    <div class="admin-title" style="margin-bottom: 15px;">
        粗見シート対象者並べ替え　(<?php echo $arami_data["タイトル"];?>)
    </div>

    <div class="admin-preview-button-flex"  style="max-width: 1200px;">
    
        <div class="admin-preview-button-flex-box">
            <button class="admin-preview-button" type="button" onclick="location.href='<?php echo getURLSetSlag("admin-arami-sheet-detail"); ?>?sheet_id=<?php echo $sheet_id; ?>'" style="color: black;background-color: lemonchiffon;width: 200px;height: 30px;">シート詳細へ</button>
        </div>
        <div class="admin-preview-button-flex-box">
            <button class="admin-preview-button" type="button" onclick="location.href='<?php echo getURLSetSlag("admin-arami-sheet-edit"); ?>?arami_sheet_id=<?php echo $sheet_id; ?>'" style="color: black;background-color: aliceblue;width: 200px;height: 30px;">シート編集へ</button>
        </div>
       
    
    </div>

    <div>

        <form action="<?php echo getURLSetSlag("admin-arami-sheet-target-sort"); ?>?sheet_id=<?php echo $sheet_id; ?>" method="post">

            <input type="hidden" name="sort_data" value="">
            <input type="submit" value="並べ替え" style="width: 400px;height: 40px;border-radius: 15px;font-size: 18px;font-weight: bold;background-color: red;color: white;margin-top: 50px;margin-bottom: 30px;">

            <table class="arami-sortable-table">
                <tr>
                    <th>並び順</th>
                    <th>対象者名</th>
                    <th>申込者</th>
                    <th>誕生日</th>
                    <th>管理者ステータス</th>
                    <th>会員ステータス</th>
                </tr>
                <tbody id="sortableTable">
                <?php foreach($arami_data["登録者"] as $key => $value){ ?>
                    <tr data-value="<?php echo $value; ?>">
                        <?php 
                            $user_id = get_field('acf_purespirit_id',$value);
                        
                            $user_data = $userClass->getUserSpritApplicantSheet($user_id,$value);

                           
                        
                            if($user_data == "")continue;

                            $app_user_data = $userClass->getUserAcountData($user_id);

                           // var_dump($user_data);
                        ?>

                        <td>
                            <?php echo $key; ?>
                        </td>
                        <td>
                            <?php if($user_data[$value]["対象者"]["対象者情報"] == false){ ?>
                                <?php echo $user_data[$value]["フル名前"];?>
                            <?php }else{ ?>
                                <?php echo $user_data[$value]["対象者"]["フル名前"];?>
                            <?php }?>
                        </td>
                        <td>
                            <?php echo $user_data[$value]["フル名前"];?>
                        </td>
                        <td>
                            <?php if($user_data[$value]["対象者"]["対象者情報"] == false){ ?>

                                <?php echo $app_user_data["誕生日年月日"];?>
                            <?php }else{ ?>
                                <?php echo $user_data[$value]["対象者"]["誕生日年月日"];?>
                            <?php }?>
                        </td>
                       
                        <td>
                            <?php echo $user_data[$value]["管理者ステータス表示"];?>
                        </td>
                        <td>
                            <?php echo $user_data[$value]["会員ステータス表示"];?>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>

        </form>

    </div>

</div>
