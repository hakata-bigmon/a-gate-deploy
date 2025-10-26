<?php 
    require_once (dirname(__FILE__)."/../../custompage/admin/a-gate-functions.php");
    require_once (dirname(__FILE__)."/../../class/spiritTypeClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritSalesClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritSheetClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritUserClass.php");
    
    // カート関数をインクルード（上記で作成した関数ファイル）
    require_once (dirname(__FILE__)."/../../inc/shopping-cart-functions.php");

    $spiritType = new SpiritTypeClass(); //管理データ
    $spiritSales = new SpiritSalesClass(); //管理データ
    $spiritSheet = new SpiritSheetClass(); //管理データ
    $userClass = new SpiritUserClass(); //管理データ

    //ポストユーザー
    $user_id = get_current_user_id();

    //もし管理者等でチェックするユーザーがいるならここで変更する
    $get_url = CheckUserPageAdmin($user_id);
    
    // $user_idは既に指定されたユーザーIDに変更されているので、そのまま使用
    $target_user_id = $user_id;

    //指定データ
    $spiritTypeArray = $spiritType->getSpiritTypeKeyTypeNum();

    //物販のみのデータを取得
    $sales_array = $spiritType->getSpiritSalesData(0);

    //var_dump($sales_array);
    
    // 現在のカート内アイテム数を取得
    $cart_count = get_cart_item_count($target_user_id);
    
	//カート内容を取得
	$cart_contents = get_cart_contents($target_user_id);

	//var_dump($cart_contents);

    // カート全体の合計金額を計算
    $cart_total = 0;
    if (!empty($cart_contents)) {
        foreach($cart_contents as $id => $qty) {
            $item_info = $spiritTypeArray[$id] ?? null;
            if($item_info && isset($item_info['price'])) {
                $cart_total += $item_info['price'] * $qty;
            }
        }
    }


    //物販を登録
    if($cart_count > 0)
    {

        //var_dump($_POST);


        //編集用
        $post_address_data = $userClass->getUserPostAddressArray($_POST["post_address"]);
    

        //タイムゾーンを東京に
        date_default_timezone_set('Asia/Tokyo');

        $count = 0;
        foreach($cart_contents as $id => $qty)
        {
            $post_array = array();

            $post_array["user_id"] = $target_user_id;
            $post_array["category_type"] = $id;
            $post_array["target_slots"] = $qty;
            $post_array["add_sheet"] = "";
            $post_array["schedule_id"] = "";
            $post_array["add_sheet_unix"] = time() + $count;
            $post_array["payment_type"] = "1";//基本はクレジットカード

            $post_array["acf_purespirit_payment_date"] = date("Y-m-d");
            $post_array["acf_purespirit_user_status"] = "7821";

            $post_array["acf_previous_post_billing_postcode"] = $post_address_data["郵送先郵便番号"];
            $post_array["acf_previous_billing_city"] = $post_address_data["郵送先住所1"];
            $post_array["acf_previous_billing_address_1"] = $post_address_data["郵送先住所2"];
            $post_array["acf_previous_billing_first_name"] = $post_address_data["郵送先名前"];

            $post_array["acf_purespirit_request_confirmation_date"] = date("Y-m-d");

            $add_id = "";
            $add_id = $spiritSheet->newSpiritSheet($post_array);
            if($add_id != "")
            {
                $spiritSheet->newSpiritSheetUserAdd( $target_user_id ,$add_id);
            }
           
            //支払い日と
            //var_dump($post_array);
            $count++;
        }

        $spiritSales->sendSalesMail($target_user_id,$cart_contents,$spiritTypeArray,$post_address_data);
        
        //カートを空にする
        clear_cart($target_user_id);
       
    }


?>


<div class="user-official-store-page">
    <div class="user-official-store-title-row">
        <div class="user-official-store-title">A-GATE OFFICIAL STORE</div>
        <div class="user-official-store-cart">
			<a href="<?php echo getURLSetSlag("users/user-store-cart"); echo $get_url["add"]; ?>">
				<div class="user-official-store-cart-flex">
					<div class="material-icons">shopping_cart</div>
					<div class="user-official-store-cart-badge">
						0
					</div>
				</div>
			</a>
        </div>
    </div>

    <!-- サンキューページデザインここから -->
    <div class="thanks-container">
        <div class="thanks-message">ご購入ありがとうございました</div>
        <div class="thanks-desc">
            この後、ご購入内容のメールと<br>
            本マイページにてお届け予定日などをお知らせします。<br>
            お手元に届くまで、もうしばらくお待ちください。
        </div>
    </div>
    <!-- サンキューページデザインここまで -->
    <div class="user-account-edit-form-btn-wrap" style="margin-top: 80px;margin-bottom: 100px;">
        <a href="<?php echo getURLSetSlag("users/user_top"); echo $get_url["add"]; ?>" class="user-account-edit-return-btn">TOPへ戻る  &gt;</a>
    </div>
</div>

<style>
.thanks-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  min-height: 240px;
  text-align: center;
  padding: 40px 20px;
  background: #fff;
}
.thanks-message {
    font-size: 20px;
    font-weight: 600;
    color: #888888;
    margin-bottom: 100px;
    line-height: 1.4;
}
.thanks-desc {
    font-size: 16px;
    color: #888888;
    line-height: 1.6;
    max-width: 600px;
}
@media (max-width: 768px) {
  .thanks-message {
    font-size: 20px;
    margin-bottom: 24px;
  }
  .thanks-desc {
    font-size: 14px;
  }
}
</style>
