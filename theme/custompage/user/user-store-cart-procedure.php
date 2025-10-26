<?php 
    require_once (dirname(__FILE__)."/../../custompage/admin/a-gate-functions.php");
    require_once (dirname(__FILE__)."/../../class/spiritTypeClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritSalesClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritUserClass.php");
    
    // カート関数をインクルード（上記で作成した関数ファイル）
    require_once (dirname(__FILE__)."/../../inc/shopping-cart-functions.php");

    $spiritType = new SpiritTypeClass(); //管理データ
    $spiritSales = new SpiritSalesClass(); //管理データ
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
?>


<div class="user-official-store-page">
    <div class="user-official-store-title-row">
        <div class="user-official-store-title">A-GATE OFFICIAL STORE</div>
        <div class="user-official-store-cart">
			<a href="<?php echo getURLSetSlag("users/user-store-cart"); echo $get_url["add"]; ?>">
				<div class="user-official-store-cart-flex">
					<div class="material-icons">shopping_cart</div>
					<div class="user-official-store-cart-badge">
						<?php echo $cart_count; ?>
					</div>
				</div>
			</a>
        </div>
    </div>


    <?php if($cart_count == 0){?>

        <div class="user-official-store-cart-empty">
            カートに商品がありません
        </div>

    <?php }else{?>


        <form action="<?php echo getURLSetSlag("users/user-store-cart-thanks"); echo $get_url["add"]; ?>" method="post" id="purchase-form">
            <input type="hidden" name="cart_total" value="<?php echo $cart_total; ?>">
            <input type="hidden" name="cart_count" value="<?php echo $cart_count; ?>">
            <input type="hidden" name="user_id" value="<?php echo $target_user_id; ?>">
            <div class="user-official-store-cart-contents">

                <div class="user-official-store-cart-title">購入する商品</div>

                <div class="user-official-store-cart-item-area">

                    <?php foreach($cart_contents as $key => $value){?>

                        <?php 
                        
                            //商品自体のデータ
                            $type_data = $spiritTypeArray[ $key ];

                            //販売ページデータ
                            $saledata = $spiritSales->getSalesPage($spiritTypeArray[$key ] , $type_data["sales_page"]);

                            
                        
                            //var_dump($type_data);
                            
                        ?>

                        <div class="user-official-store-cart-item">
                            
                            <div class="user-official-store-cart-item-details">
                                <div class="user-official-store-cart-item-image">
                                    <?php if($saledata["サムネイル"] != ""){?>
                                        <img src="<?php echo esc_url($saledata["サムネイル"]); ?>" alt="<?php echo esc_attr($type_data["title"]); ?>">
                                    <?php }else{?>
                                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/noimage.jpg" alt="<?php echo esc_attr($type_data["title"]); ?>">
                                    <?php }?>
                                </div>

                                <div class="user-official-store-cart-item-name">
                                    <a href="<?php echo getURLSetSlag("users/user-sales-page") . $get_url["add"]; if($get_url["add"] == ""){echo "?sales_id=" . $key;}else{echo "&sales_id=" . $key;}?>" class="user-official-store-item-link">
                                        <?php echo esc_html($type_data["title"]); ?>
                                    </a>
                                </div>
                            </div>
                            
                            <div class="user-official-store-cart-item-controls">
                                <div class="user-official-store-cart-item-unit-price">
                                    ￥<?php echo number_format($type_data["price"]); ?>
                                </div>

                                <div class="user-official-store-cart-item-quantity">
                                <?php echo $value; ?>点
                                </div>

                                
                                <div class="user-official-store-cart-item-total-price">
                                    ￥<?php echo number_format($type_data["price"] * $value); ?>
                                </div>

                            
                            </div>
                        </div>

                    <?php }?>

                </div>

                

                <div class="user-official-store-cart-summary">
                    <div class="cart-summary-total" style="color: #888888;">
                        <span>合計金額:</span>
                        <span id="cart-grand-total" style="color: #888888;">￥<?php echo number_format($cart_total); ?></span>
                    </div>
                </div>

                <div class="user-official-store-cart-title">郵送先</div>

                <hr style="color: #888888;">

                <?php  //一覧
                    $post_address_list = $userClass->getUserPostAddressList($user_id);

                    if(count($post_address_list) > 0){

                ?>
                        <div class="post-address-selection">
                            <?php foreach($post_address_list as $post_key => $post_address_data){?>
                                <div class="post-address-option">
                                    <label class="post-address-radio-label">
                                        <input type="radio" name="post_address" value="<?php echo $post_key; ?>" <?php if($post_address_data["メイン"] == "1"){echo "checked";} ?>>
                                        <div class="post-address-radio-content">
                                            <div class="post-address-radio-info">
                                                <div class="post-address-radio-name">
                                                    <?php echo $post_address_data["郵送先名前"]; ?>
                                                </div>
                                                <div class="post-address-radio-address">
                                                    〒<?php echo $post_address_data["郵送先郵便番号"]; ?><br>
                                                    <?php echo $post_address_data["郵送先住所1"]; ?><?php echo $post_address_data["郵送先住所2"] ? '　' . $post_address_data["郵送先住所2"] : ''; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            <?php } ?>
                        </div>

                        <div class="user-account-edit-form-btn-wrap" style="margin-top: 30px;">
                            <a href="<?php echo getURLSetSlag("users/user-post-address"); echo $get_url["add"]; ?>" class="user-account-edit-return-btn"> 郵送先の編集  &gt;</a>
                        </div>

                    <?php }else{ ?>

                        <div class="user-account-edit-form-btn-wrap" style="margin-top: 30px;">
                            <a href="<?php echo getURLSetSlag("users/user-post-address"); echo $get_url["add"]; ?>" class="user-account-edit-return-btn"> 郵送先の登録  &gt;</a>
                        </div>

                    <?php } ?>

                    <div class="user-official-store-cart-title" style="margin-top: 100px;">お支払い方法</div>

                    <hr style="color: #888888;">

                    <div style="text-align: center;margin-top: 30px;color: #888888;">クレジットカード支払いのみ</div>



                    <div class="user-official-store-cart-title" style="margin-top: 50px;">お会計合計</div>

                    <hr style="color: #888888;">

                    <div class="user-jorei-payment-price-flex" style="margin-top: 40px;">
                        <div class="user-jorei-payment-price-title">ご購入金額</div>
                        <div class="user-jorei-payment-price-text">
                            ￥<?php echo number_format($cart_total); ?>（税込）
                        </div>
                    </div>
                    <div class="user-jorei-payment-price-flex">
                        <div class="user-jorei-payment-price-title">送料</div>
                        <div class="user-jorei-payment-price-text" style="margin-right: 10px;">
                            ￥600
                        </div>
                    </div><div class="user-jorei-payment-price-flex">
                        <div class="user-jorei-payment-price-title">お支払い合計</div>
                        <div class="user-jorei-payment-price-text">
                            ￥<?php echo number_format($cart_total + 600); ?>（税込）
                        </div>
                    </div>
                    
                    <div class="user-account-edit-form-btn-wrap" style="margin-top: 30px; display: flex; justify-content: center;">
                        <button type="button" class="purchase-btn" style="height: 60px;width: 260px;font-size: 28px;margin-top: 50px;" onclick="confirmPurchase()">購入する</button>
                    </div>


            </div>

        </form>
    <?php }?>

    <div class="user-account-edit-form-btn-wrap" style="margin-top: 100px;">
        <a href="<?php echo getURLSetSlag("users/user-store-cart"); echo $get_url["add"]; ?>" class="user-account-edit-return-btn">カートへ戻る  &gt;</a>
    </div>


    <div class="user-account-edit-form-btn-wrap" style="margin-top: 80px;margin-bottom: 100px;">
        <a href="<?php echo getURLSetSlag("users/user_top"); echo $get_url["add"]; ?>" class="user-account-edit-return-btn">TOPへ戻る  &gt;</a>
    </div>
</div>

<style>
/* 郵送先選択のスタイル */
.post-address-selection {
    max-width: 800px;
    margin: 20px auto 0;
    padding: 0 20px;
}

.post-address-option {
    margin-bottom: 15px;
}

.post-address-option.main-address {
    border: 2px solid #007cba;
    border-radius: 8px;
    background: #f8f9ff;
}

.post-address-radio-label {
    display: block;
    cursor: pointer;
    padding: 15px;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    background: #fff;
    transition: all 0.2s ease;
    margin: 0;
    position: relative;
}

.post-address-radio-label:hover {
    border-color: #007cba;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.post-address-radio-label input[type="radio"] {
    display: none;
}

.post-address-radio-label input[type="radio"]:checked + .post-address-radio-content {
    background: #f0f8ff;
}

.post-address-radio-content {
    display: flex;
    align-items: flex-start;
    gap: 15px;
    position: relative;
}

.post-address-radio-info {
    flex: 1;
}

.post-address-radio-name {
    font-size: 16px;
    font-weight: 600;
    color: #333;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.main-badge {
    background: #007cba;
    color: white;
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: normal;
}

.post-address-radio-address {
    font-size: 14px;
    color: #666;
    line-height: 1.5;
    margin-left: 30px; /* ラジオボタンの幅分だけ左マージンを追加 */
}

/* ラジオボタンのカスタムスタイル - 削除（重複を防ぐため） */

/* 未チェック状態のラジオボタン */
.post-address-radio-name::before {
    content: '';
    display: inline-block;
    width: 16px;
    height: 16px;
    border: 2px solid #ddd;
    border-radius: 50%;
    background: white;
    margin-right: 10px;
    flex-shrink: 0;
    transition: all 0.2s ease;
    vertical-align: middle;
}

/* チェック状態の時のラジオボタン（名前の左横） */
.post-address-radio-label input[type="radio"]:checked + .post-address-radio-content .post-address-radio-name::before {
    border-color: #007cba;
    background: #007cba;
    box-shadow: inset 0 0 0 3px white;
}

/* レスポンシブ対応 */
@media (max-width: 768px) {
    .post-address-selection {
        padding: 0 15px;
    }
    
    .post-address-radio-label {
        padding: 12px;
    }
    
    .post-address-radio-content {
        flex-direction: column;
        gap: 10px;
    }
    
    .post-address-radio-name {
        font-size: 15px;
    }
    
    .post-address-radio-address {
        font-size: 13px;
        margin-left: 26px; /* モバイルでも左マージンを維持 */
    }
}

/* 購入ボタンのスタイル */
.purchase-btn {
    font-size: 14px;
    height: 24px;
    margin-left: auto;
    margin-right: auto;
    width: 160px;
    color: #C18AD1;
    margin-top: 30px;
    border: 2px solid #B085C7;
    border-radius: 24px;
    background: #fff;
    cursor: pointer;
    display: flex;
    justify-content: center;
    align-items: center;
    font-family: 'Noto Sans JP', sans-serif;
    padding: 0;
    font-weight: normal;
    transition: all 0.2s ease;
}

.purchase-btn:hover {
    background: #f8f4ff;
    color: #C18AD1;
    text-decoration: none;
}

    .purchase-btn:active {
        transform: translateY(1px);
    }

    .user-jorei-payment-wrap{
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
    }

    .user-jorei-payment-price-flex{
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
        max-width: 400px;
        margin-left: auto;
        margin-right: auto;
        padding-top: 10px;
        border-bottom: 1px solid #888888;
        padding-bottom: 12px;
    }

    .user-jorei-payment-price-title{
        font-size: 16px;
        font-weight: 600;
        color: #888888;
        text-align: center;

    }

    .user-jorei-payment-title{
        padding-bottom: 10px;
        border-bottom: 1px solid #888888;
        text-align: center;
    }
    
    .user-jorei-payment-title-text{
        font-size: 20px;
        font-weight: 600;
        color: #888888;
    }
</style>

<script>
function confirmPurchase() {
    const totalAmount = <?php echo $cart_total + 600; ?>;
    const itemCount = <?php echo $cart_count; ?>;
    
    const message = `購入を確定しますか？\n\n` +
                   `商品数: ${itemCount}点\n` +
                   `合計金額: ￥${totalAmount.toLocaleString()}\n\n` +
                   `この操作は取り消せません。`;
    
    if (confirm(message)) {
        // フォームを送信
        document.getElementById('purchase-form').submit();
    }
}
</script>

   






