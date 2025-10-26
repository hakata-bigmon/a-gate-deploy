<?php 
    require_once (dirname(__FILE__)."/../../custompage/admin/a-gate-functions.php");
    require_once (dirname(__FILE__)."/../../class/spiritTypeClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritSalesClass.php");
    
    // カート関数をインクルード（上記で作成した関数ファイル）
    require_once (dirname(__FILE__)."/../../inc/shopping-cart-functions.php");
    require_once (dirname(__FILE__)."/../../class/spiritUserClass.php");

    $userClass = new SpiritUserClass(); //ユーザー管理
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
    
    // 現在のカート内アイテム数を取得
    $cart_count = get_cart_item_count($target_user_id);
    
	//カート内容を取得
	$cart_contents = get_cart_contents($target_user_id);

    $userData = $userClass->getUserAcountData($user_id);//ユーザー情報

	//var_dump($cart_contents);
?>

<script>
// AJAX処理用の設定をJavaScriptに渡す
var ajax_object = {
    ajax_url: '<?php echo admin_url('admin-ajax.php'); ?>',
    nonce: '<?php echo wp_create_nonce('add_to_cart_nonce'); ?>',
    current_cart_count: <?php echo $cart_count; ?>,
    target_user_id: <?php echo $target_user_id; ?>
};

// デバッグ用
//console.log('PHP Cart Count:', <?php echo $cart_count; ?>);
//console.log('ajax_object:', ajax_object);
//console.log('Cart.js loading check - window.cartScriptLoaded:', typeof window.cartScriptLoaded !== 'undefined' ? window.cartScriptLoaded : 'undefined');

// cart.jsが読み込まれていない場合のフォールバック
if (typeof window.updateCartBadge === 'undefined') {
    console.log('Cart.js not loaded, checking if script tag exists...');
    var cartScript = document.querySelector('script[src*="cart.js"]');
    if (!cartScript) {
        console.log('Cart.js script tag not found, adding manual script tag...');
        var script = document.createElement('script');
        script.src = '<?php echo get_template_directory_uri(); ?>/assets/js/cart.js';
        script.onload = function() {
            console.log('Cart.js loaded manually');
            window.cartScriptLoaded = true;
        };
        document.head.appendChild(script);
    }
}

// ページ読み込み時にカートバッジを初期化
jQuery(document).ready(function($) {
    console.log('jQuery ready - initializing cart badge');
    console.log('Current cart count from PHP:', <?php echo $cart_count; ?>);
    
    // カートバッジの初期値を直接設定
    $('.user-official-store-cart-badge').text(<?php echo $cart_count; ?>);
    console.log('Direct badge text set to:', <?php echo $cart_count; ?>);
    
    // cart.jsが読み込まれるまで待機してからupdateCartBadgeを呼び出し
    function initializeCartBadge() {
        console.log('Checking if updateCartBadge function exists...');
        console.log('window.updateCartBadge:', typeof window.updateCartBadge);
        console.log('window.cartScriptLoaded:', typeof window.cartScriptLoaded !== 'undefined' ? window.cartScriptLoaded : 'undefined');
        console.log('window object keys:', Object.keys(window).filter(key => key.includes('Cart')));
        
        if (typeof window.updateCartBadge === 'function') {
            console.log('updateCartBadge function found, calling with count:', ajax_object.current_cart_count);
            updateCartBadge(ajax_object.current_cart_count);
            console.log('Cart badge initialized with count:', ajax_object.current_cart_count);
        } else {
            console.log('updateCartBadge function not found yet, retrying...');
            console.log('Attempt count:', (window.retryCount || 0) + 1);
            window.retryCount = (window.retryCount || 0) + 1;
            
            // 20回試行しても見つからない場合は停止
            if (window.retryCount >= 20) {
                console.error('updateCartBadge function not found after 20 attempts. Cart.js may not be loaded properly.');
                console.log('Using fallback: direct badge update');
                // フォールバック: 直接バッジを更新
                $('.user-official-store-cart-badge').text(ajax_object.current_cart_count);
                
                // 手動でcart.jsを読み込む試行
                if (!window.cartScriptLoaded) {
                    console.log('Attempting to load cart.js manually...');
                    var script = document.createElement('script');
                    script.src = '<?php echo get_template_directory_uri(); ?>/assets/js/cart.js';
                    script.onload = function() {
                        console.log('Cart.js loaded manually, retrying initialization...');
                        window.retryCount = 0; // リセット
                        setTimeout(initializeCartBadge, 500);
                    };
                    document.head.appendChild(script);
                }
                return;
            }
            
            // 関数がまだ読み込まれていない場合は少し待ってから再試行
            setTimeout(initializeCartBadge, 300);
        }
    }
    
    // 少し遅延させてから初期化を実行
    setTimeout(initializeCartBadge, 200);
    
    // テスト用ボタンのイベントハンドラー
    $('#test-add-to-cart').on('click', function() {
        console.log('Test add to cart - target_user_id:', ajax_object.target_user_id);
        $.ajax({
            url: ajax_object.ajax_url,
            type: 'POST',
            data: {
                action: 'add_to_cart',
                item_id: 999, // テスト用アイテムID
                quantity: 1,
                target_user_id: ajax_object.target_user_id,
                nonce: ajax_object.nonce
            },
            success: function(response) {
                console.log('Test add to cart response:', response);
                $('#test-result').html('<pre>' + JSON.stringify(response, null, 2) + '</pre>');
                if (response.success) {
                    updateCartBadge(response.data.cart_count);
                }
            },
            error: function(xhr, status, error) {
                console.error('Test add to cart error:', error);
                $('#test-result').html('<div style="color: red;">Error: ' + error + '</div>');
            }
        });
    });
    
    $('#test-clear-cart').on('click', function() {
        console.log('Test clear cart - target_user_id:', ajax_object.target_user_id);
        $.ajax({
            url: ajax_object.ajax_url,
            type: 'POST',
            data: {
                action: 'clear_cart',
                target_user_id: ajax_object.target_user_id,
                nonce: ajax_object.nonce
            },
            success: function(response) {
                console.log('Test clear cart response:', response);
                $('#test-result').html('<pre>' + JSON.stringify(response, null, 2) + '</pre>');
                if (response.success) {
                    updateCartBadge(0);
                }
            },
            error: function(xhr, status, error) {
                console.error('Test clear cart error:', error);
                $('#test-result').html('<div style="color: red;">Error: ' + error + '</div>');
            }
        });
    });
    
    $('#test-show-cart').on('click', function() {
        console.log('Test show cart - target_user_id:', ajax_object.target_user_id);
        $.ajax({
            url: ajax_object.ajax_url,
            type: 'POST',
            data: {
                action: 'get_cart_contents',
                target_user_id: ajax_object.target_user_id,
                nonce: ajax_object.nonce
            },
            success: function(response) {
                console.log('Test show cart response:', response);
                $('#test-result').html('<pre>' + JSON.stringify(response, null, 2) + '</pre>');
            },
            error: function(xhr, status, error) {
                console.error('Test show cart error:', error);
                $('#test-result').html('<div style="color: red;">Error: ' + error + '</div>');
            }
        });
    });
});
</script>

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
    
	<?php /*?
    <!-- テスト用ボタン（開発時のみ） -->
    <div style="margin: 20px 0; padding: 10px; background: #f0f0f0; border: 1px solid #ccc;">
        <h4>テスト用（開発時のみ）</h4>
        <button id="test-add-to-cart" style="padding: 10px; margin: 5px;">テストアイテムをカートに追加</button>
        <button id="test-clear-cart" style="padding: 10px; margin: 5px;">カートをクリア</button>
        <button id="test-show-cart" style="padding: 10px; margin: 5px;">カート内容を表示</button>
        <div id="test-result" style="margin-top: 10px; padding: 10px; background: white;"></div>
    </div>
	*/?>
</div>

<div class="user-official-store-area">
    <div class="user-official-store-contens">
        <?php 
            foreach ($sales_array as $key => $value) {
                //商品自体のデータ
                $type_data = $spiritTypeArray[ $value["ID"] ];

                //販売ページデータ
                $saledata = $spiritSales->getSalesPage($spiritTypeArray[ $value["ID"] ] , $type_data["sales_page"]);

                if($type_data["stock"] == 0) {
                    continue;
                }
        ?>
                <div class="user-official-store-item">
                    <a href="<?php echo getURLSetSlag("users/user-sales-page") . $get_url["add"]; if($get_url["add"] == ""){echo "?sales_id=" . $value["ID"];}else{echo "&sales_id=" . $value["ID"];}?>" class="user-official-store-item-link">
                        <div class="user-official-store-item-image">
							<?php if($saledata["サムネイル"] != ""){?>
								<img src="<?php echo $saledata["サムネイル"]; ?>" alt="<?php echo htmlspecialchars($saledata["表示名"]); ?>">
							<?php }else{?>
								<img src="<?php echo get_template_directory_uri(); ?>/assets/images/noimage.jpg" alt="<?php echo htmlspecialchars($saledata["表示名"]); ?>">
							<?php }?>
                        </div>
                        <div class="user-official-store-item-name">
                            <?php echo $saledata["表示名"]; ?>
                        </div>
                        <div class="user-official-store-item-price">
                            ￥<?php echo number_format($saledata["価格"]); ?>
                        </div>
                    </a>

                    <div class="user-official-store-item-button-wrap">
						<?php 
						
							//すでにカートに入っている場合はボタンを非表示にする
							if(isset($cart_contents[$value["ID"]])){
						?>
							
							<span style="color: #888888; font-size: 16px;font-weight: 600;">カートに入っています</span>
							
						<?php
							}else{
						?>
                            <?php if($userData["認証"] ==  "2"){?>
                                <button class="user-official-store-button" 
                                        data-item-id="<?php echo $value["ID"]; ?>" 
                                        data-quantity="1">
                                    <span class="button-text">カートに入れる</span>
                                </button>
                            <?php } ?>
						<?php
							}
						?>
                    </div>
                </div>
        <?php
                }
        ?>
    </div>
</div>