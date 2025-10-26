<?php 
    require_once (dirname(__FILE__)."/../../custompage/admin/a-gate-functions.php");
    require_once (dirname(__FILE__)."/../../class/spiritTypeClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritSalesClass.php");
    
    // カート関数をインクルード（上記で作成した関数ファイル）
    require_once (dirname(__FILE__)."/../../inc/shopping-cart-functions.php");

    $spiritType = new SpiritTypeClass(); //管理データ
    $spiritSales = new SpiritSalesClass(); //管理データ

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



        <div class="user-official-store-cart-contents">

            <div class="user-official-store-cart-title">ショッピングカート</div>

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
                                <select name="item_quantity[<?php echo esc_attr($key); ?>]" class="cart-item-quantity-select" data-item-id="<?php echo esc_attr($key); ?>">
                                    <?php for($i = 0; $i <= $type_data["max"]; $i++){?>
                                        <option value="<?php echo $i; ?>" <?php selected($i, $value); ?>><?php echo $i; ?></option>
                                    <?php }?>
                                </select>
                                <div class="cart-quantity-spinner"></div>
                            </div>

                            
                            <div class="user-official-store-cart-item-total-price">
                                ￥<?php echo number_format($type_data["price"] * $value); ?>
                            </div>

                            <div class="user-official-store-cart-item-delete">
                                <div class="user-official-store-cart-item-delete-button" data-item-id="<?php echo esc_attr($key); ?>">
                                    <span class="material-icons">delete</span>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php }?>

            </div>

            <div class="user-official-store-cart-summary">
                <div class="cart-summary-total">
                    <span>合計金額:</span>
                    <span id="cart-grand-total">￥<?php echo number_format($cart_total); ?></span>
                </div>
                <div class="cart-summary-actions">
                    <a href="<?php echo getURLSetSlag("users/user-store-cart-procedure"); echo $get_url["add"]; ?>" class="button-primary">ご購入手続きへ</a>
                </div>
            </div>

        </div>


    <?php }?>


</div>

<script>
jQuery(document).ready(function($) {
    // AJAX処理用の設定
    var ajax_object = {
        ajax_url: '<?php echo admin_url('admin-ajax.php'); ?>',
        nonce: '<?php echo wp_create_nonce('cart_nonce'); ?>',
        target_user_id: <?php echo $target_user_id; ?>
    };

    // 削除ボタンのイベントリスナー
    $('.user-official-store-cart-item-delete-button').on('click', function() {
        var deleteButton = $(this);
        var itemId = deleteButton.data('item-id');
        var itemRow = deleteButton.closest('.user-official-store-cart-item');
        var quantitySelect = itemRow.find('.cart-item-quantity-select');
        
        // 確認ダイアログ
        if (confirm('この商品をカートから削除しますか？')) {
            // 数量を0に設定
            quantitySelect.val(0).trigger('change');
        }
    });

    // 数量変更のイベントリスナー
    $('.cart-item-quantity-select').on('change', function() {
        var select = $(this);
        var itemId = select.data('item-id');
        var quantity = select.val();
        var itemRow = select.closest('.user-official-store-cart-item');
        var spinner = itemRow.find('.cart-quantity-spinner');

        select.prop('disabled', true);
        spinner.show();

        $.ajax({
            url: ajax_object.ajax_url,
            type: 'POST',
            data: {
                action: 'update_cart_quantity',
                item_id: itemId,
                quantity: quantity,
                target_user_id: ajax_object.target_user_id,
                nonce: ajax_object.nonce
            },
            success: function(response) {
                if (response.success) {
                    // 商品の合計金額を更新
                    itemRow.find('.user-official-store-cart-item-total-price').text(response.data.formatted_item_total);

                    // カートバッジの数を更新
                    $('.user-official-store-cart-badge').text(response.data.cart_count);

                    // カート全体の合計金額を更新
                    $('#cart-grand-total').text(response.data.formatted_cart_total);
                    
                    // 数量が0になった場合は行を削除
                    if (quantity == 0) {
                        itemRow.fadeOut(300, function() { 
                            $(this).remove(); 
                            // カートが空になったかチェック
                            if ($('.user-official-store-cart-item').length === 0) {
                                location.reload(); // ページをリロードして「カートは空です」を表示
                            }
                        });
                    }
                } else {
                    alert('エラー: ' + (response.data.message || response.data));
                    select.val(select.data('previous-quantity'));
                }
            },
            error: function() {
                alert('通信エラーが発生しました。');
                select.val(select.data('previous-quantity'));
            },
            complete: function() {
                spinner.hide();
                select.prop('disabled', false);
            }
        });
    });
    
    // 変更前の値を保持
    $('.cart-item-quantity-select').on('focus', function() {
        $(this).data('previous-quantity', $(this).val());
    });
});
</script>