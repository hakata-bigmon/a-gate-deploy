<?php 

  require_once ("a-gate-functions.php");
  require_once (dirname(__FILE__)."/../../class/spiritSheetClass.php");
  require_once (dirname(__FILE__)."/../../class/spiritTypeClass.php");
  require_once (dirname(__FILE__)."/../../class/spiritUserClass.php");

  $users = get_users();
  $user_data = array();

  $sprit_type = $_GET["type"];

  $spiritSheet = new SpiritSheetClass(); //管理データ
  $spiritType = new SpiritTypeClass(); //管理データ
  $userClass = new SpiritUserClass(); //管理データ


  //保存
  if(isset($_POST["save"])){

    $spiritTypeArray = $spiritType->getSpiritType();

    foreach($spiritTypeArray[$sprit_type] as $key => $value){

      if(isset($_POST["schedule_payment_type_".$value["ID"]])){
        $payment_type_array = $_POST["schedule_payment_type_".$value["ID"]];
        //var_dump($payment_type_array);
       // echo "<br>";
      }
      else{
        $payment_type_array = "";
      }

      update_field("acf_pure_payment_setting", $payment_type_array, $value["ID"]);
    }

    //var_dump($_POST);
  }

  $spiritTypeArray = $spiritType->getSpiritType();


?>


<style>
    @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@500&display=swap');
    
    * {
        font-family: 'Noto Sans JP', sans-serif;
    }
    
    .payment-setting-container {
        max-width: 1200px;
        padding: 20px;
    }
    
    .payment-setting-header {
        text-align: center;
        margin-bottom: 40px;
    }
    
    .payment-setting-header h1 {
        font-size: 24px;
        font-weight: 600;
        color: #333;
        margin: 0;
    }
    
    .payment-setting-form {
        background: white;
        border-radius: 8px;
        padding: 30px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .payment-setting-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }
    
    .payment-setting-table th {
        background: #f8f9fa;
        padding: 15px;
        text-align: left;
        font-weight: 600;
        color: #333;
        border: 1px solid #dee2e6;
        vertical-align: top;
        width: 400px;
        min-width: 200px;
        max-width: 400px;
        word-wrap: break-word;
        word-break: break-word;
        white-space: normal;
    }
    
    .payment-setting-table td {
        padding: 15px;
        border: 1px solid #dee2e6;
        vertical-align: top;
    }
    
    .payment-type-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 10px;
        max-width: 100%;
    }
    
    .payment-type-item {
        display: flex;
        align-items: center;
        padding: 8px 12px;
        background: #f8f9fa;
        border-radius: 4px;
        border: 1px solid #dee2e6;
        transition: all 0.2s ease;
    }
    
    .payment-type-item:hover {
        background: #e9ecef;
        border-color: #A078D0;
    }
    
    .payment-type-item input[type="checkbox"] {
        margin-right: 8px;
        transform: scale(1.1);
    }
    
    .payment-type-item label {
        font-size: 14px;
        color: #333;
        cursor: pointer;
        margin: 0;
        flex: 1;
    }
    
    .save-button {
        background: #A078D0;
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 6px;
        font-size: 16px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-top: 20px;
    }
    
    .save-button:hover {
        background: #8B5BB3;
        transform: translateY(-1px);
    }
    
    @media (max-width: 768px) {
        .payment-setting-container {
            padding: 15px;
        }
        
        .payment-setting-table {
            font-size: 14px;
        }
        
        .payment-setting-table th {
            width: 120px;
            min-width: 120px;
        }
        
        .payment-type-grid {
            grid-template-columns: 1fr;
        }
        
        .payment-type-item {
            padding: 6px 10px;
        }
    }
    
    @media (max-width: 480px) {
        .payment-setting-table {
            display: block;
            overflow-x: auto;
        }
        
        .payment-setting-table th,
        .payment-setting-table td {
            min-width: 150px;
        }
    }
</style>

<div class="payment-setting-container">
    <div class="payment-setting-header">
        <h1><?php echo $spiritType->getSpiritTypeName($sprit_type); ?>　支払い設定</h1>
    </div>

    <div class="payment-setting-form">
        <form action="<?php echo getURLSetSlag("admin-payment-setting"); ?>?type=<?php echo $sprit_type; ?>" method="post">
            <input type="hidden" name="save" value="1">
            
            <table class="payment-setting-table">
                <?php foreach($spiritTypeArray[$sprit_type] as $key => $value){?>
                    <tr>
                        <th>
                            <div>
                                <?php echo $value["title"];?>
                            </div>
                        </th>
                        <td>
                            <div class="payment-type-grid">
                                <?php foreach($userClass->payment_type_field as $type_key => $type_value){ ?>
                                    <?php if($type_key == SpiritUserClass::PAYMENT_TYPE_NOT_SET){ continue; } ?>
                                    <div class="payment-type-item">
                                        <input type="checkbox" name="schedule_payment_type_<?php echo $value["ID"];?>[]" id="payment_<?php echo $value["ID"];?>_<?php echo $type_key;?>" value="<?php echo $type_key; ?>" <?php 
                                        $payment_setting = is_string($value["payment_setting"]) ? json_decode($value["payment_setting"], true) : $value["payment_setting"];
                                        if(is_array($payment_setting) && in_array($type_key, $payment_setting)){ echo "checked"; } 
                                        ?>>
                                        <label for="payment_<?php echo $value["ID"];?>_<?php echo $type_key;?>"><?php echo $type_value; ?></label>
                                    </div>
                                <?php } ?>
                            </div>
                        </td>
                    </tr>
                <?php }?>
            </table>
            
            <button type="submit" class="save-button">保存</button>
        </form>
    </div>
</div>