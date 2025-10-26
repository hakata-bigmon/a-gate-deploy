<?php
/**
 * ショッピングカート関数群
 * WordPressのカスタムフィールドを使用してユーザーのカート情報を管理
 */

/**
 * カートにアイテムを追加する関数
 * 
 * @param int $user_id ユーザーID
 * @param int $item_id 商品ID
 * @param int $quantity 追加する個数（デフォルト：1）
 * @return bool 成功時true、失敗時false
 */
function add_to_cart($user_id, $item_id, $quantity = 1) {
    // 入力値のバリデーション
    if (!$user_id || !$item_id || $quantity < 1) {
        error_log("add_to_cart: Invalid parameters - user_id: $user_id, item_id: $item_id, quantity: $quantity");
        return false;
    }
    
    // 現在のカート情報を取得
    $cart_data = get_user_meta($user_id, 'shopping_cart', true);
    
    // カートデータが存在しない場合は空の配列で初期化
    if (!is_array($cart_data)) {
        $cart_data = array();
    }
    
    // 既にカートに商品がある場合は個数を加算、ない場合は新規追加
    if (isset($cart_data[$item_id])) {
        $cart_data[$item_id] += $quantity;
    } else {
        $cart_data[$item_id] = $quantity;
    }
    
    // デバッグログ
    error_log("add_to_cart: Adding item $item_id (qty: $quantity) for user $user_id");
    error_log("add_to_cart: Updated cart data: " . print_r($cart_data, true));
    
    // カート情報を更新
    $result = update_user_meta($user_id, 'shopping_cart', $cart_data);
    
    if (!$result) {
        error_log("add_to_cart: Failed to update user meta for user $user_id");
    }
    
    return $result;
}

/**
 * カートの内容を取得する関数
 * 
 * @param int $user_id ユーザーID
 * @return array カートの内容
 */
function get_cart_contents($user_id) {
    $cart_data = get_user_meta($user_id, 'shopping_cart', true);
    
    if (!is_array($cart_data)) {
        return array();
    }
    
    return $cart_data;
}

/**
 * カート内のアイテム数を取得する関数
 * 
 * @param int $user_id ユーザーID
 * @return int カート内のアイテム総数
 */
function get_cart_item_count($user_id) {
    $cart_data = get_cart_contents($user_id);
    
    return array_sum($cart_data);
}

/**
 * カートから特定のアイテムを削除する関数
 * 
 * @param int $user_id ユーザーID
 * @param int $item_id 商品ID
 * @return bool 成功時true、失敗時false
 */
function remove_from_cart($user_id, $item_id) {
    $cart_data = get_cart_contents($user_id);
    
    if (isset($cart_data[$item_id])) {
        unset($cart_data[$item_id]);
        return update_user_meta($user_id, 'shopping_cart', $cart_data);
    }
    
    return false;
}

/**
 * カートを空にする関数
 * 
 * @param int $user_id ユーザーID
 * @return bool 成功時true、失敗時false
 */
function clear_cart($user_id) {
    return update_user_meta($user_id, 'shopping_cart', array());
}

/**
 * AJAX処理用の関数
 * カートにアイテムを追加するAJAX処理
 */
function ajax_add_to_cart() {
    // ログイン確認
    if (!is_user_logged_in()) {
        error_log("ajax_add_to_cart: User not logged in");
        wp_send_json_error('ログインが必要です');
        return;
    }
    
    // セキュリティチェック
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'add_to_cart_nonce')) {
        error_log("ajax_add_to_cart: Security check failed");
        wp_send_json_error('セキュリティエラー');
        return;
    }
    
    // 対象ユーザーIDを取得（指定されていない場合は現在のユーザーIDを使用）
    $target_user_id = isset($_POST['target_user_id']) ? intval($_POST['target_user_id']) : get_current_user_id();
    $item_id = intval($_POST['item_id']);
    $quantity = intval($_POST['quantity']) ?: 1;
    
    error_log("ajax_add_to_cart: Received request - target_user_id: $target_user_id, item_id: $item_id, quantity: $quantity");
    
    if ($item_id <= 0) {
        error_log("ajax_add_to_cart: Invalid item ID: $item_id");
        wp_send_json_error('無効な商品IDです');
        return;
    }
    
    // 権限チェック（管理者または自分自身のカートのみ操作可能）
    if (!current_user_can('manage_options') && $target_user_id !== get_current_user_id()) {
        error_log("ajax_add_to_cart: Permission denied - current_user: " . get_current_user_id() . ", target_user: $target_user_id");
        wp_send_json_error('権限がありません');
        return;
    }
    
    // カートに追加
    $result = add_to_cart($target_user_id, $item_id, $quantity);
    
    if ($result) {
        $cart_count = get_cart_item_count($target_user_id);
        error_log("ajax_add_to_cart: Success - new cart count: $cart_count for user: $target_user_id");
        
        wp_send_json_success(array(
            'message' => 'カートに追加しました',
            'cart_count' => $cart_count,
            'item_id' => $item_id,
            'quantity' => $quantity,
            'target_user_id' => $target_user_id
        ));
    } else {
        error_log("ajax_add_to_cart: Failed to add item to cart for user: $target_user_id");
        wp_send_json_error('カートへの追加に失敗しました');
    }
}

// AJAX処理をWordPressに登録
add_action('wp_ajax_add_to_cart', 'ajax_add_to_cart');
add_action('wp_ajax_nopriv_add_to_cart', 'ajax_add_to_cart');

// テスト用AJAX関数
function ajax_clear_cart() {
    if (!is_user_logged_in()) {
        wp_send_json_error('ログインが必要です');
        return;
    }
    
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'add_to_cart_nonce')) {
        wp_send_json_error('セキュリティエラー');
        return;
    }
    
    // 対象ユーザーIDを取得（指定されていない場合は現在のユーザーIDを使用）
    $target_user_id = isset($_POST['target_user_id']) ? intval($_POST['target_user_id']) : get_current_user_id();
    
    // 権限チェック（管理者または自分自身のカートのみ操作可能）
    if (!current_user_can('manage_options') && $target_user_id !== get_current_user_id()) {
        wp_send_json_error('権限がありません');
        return;
    }
    
    $result = clear_cart($target_user_id);
    
    if ($result) {
        wp_send_json_success(array(
            'message' => 'カートをクリアしました',
            'cart_count' => 0,
            'target_user_id' => $target_user_id
        ));
    } else {
        wp_send_json_error('カートのクリアに失敗しました');
    }
}

function ajax_get_cart_contents() {
    if (!is_user_logged_in()) {
        wp_send_json_error('ログインが必要です');
        return;
    }
    
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'add_to_cart_nonce')) {
        wp_send_json_error('セキュリティエラー');
        return;
    }
    
    // 対象ユーザーIDを取得（指定されていない場合は現在のユーザーIDを使用）
    $target_user_id = isset($_POST['target_user_id']) ? intval($_POST['target_user_id']) : get_current_user_id();
    
    // 権限チェック（管理者または自分自身のカートのみ操作可能）
    if (!current_user_can('manage_options') && $target_user_id !== get_current_user_id()) {
        wp_send_json_error('権限がありません');
        return;
    }
    
    $cart_contents = get_cart_contents($target_user_id);
    $cart_count = get_cart_item_count($target_user_id);
    
    wp_send_json_success(array(
        'cart_contents' => $cart_contents,
        'cart_count' => $cart_count,
        'target_user_id' => $target_user_id
    ));
}

add_action('wp_ajax_clear_cart', 'ajax_clear_cart');
add_action('wp_ajax_get_cart_contents', 'ajax_get_cart_contents');

// カートの数量を更新するAJAXハンドラ
add_action('wp_ajax_update_cart_quantity', 'handle_update_cart_quantity');
add_action('wp_ajax_nopriv_update_cart_quantity', 'handle_update_cart_quantity');

function handle_update_cart_quantity() {
    check_ajax_referer('cart_nonce', 'nonce');

    $item_id = isset($_POST['item_id']) ? intval($_POST['item_id']) : 0;
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 0;
    $target_user_id = isset($_POST['target_user_id']) ? intval($_POST['target_user_id']) : get_current_user_id();

    if (!$target_user_id) {
        wp_send_json_error('ユーザーが指定されていません。');
    }

    // カートを取得
    $cart = get_user_meta($target_user_id, 'shopping_cart', true);
    if (!is_array($cart)) {
        $cart = [];
    }

    if ($item_id > 0) {
        if ($quantity > 0) {
            // 数量を更新
            $cart[$item_id] = $quantity;
        } else {
            // 数量が0なら削除
            unset($cart[$item_id]);
        }

        // カートを更新
        update_user_meta($target_user_id, 'shopping_cart', $cart);

        // 更新後の情報を取得
        $cart_count = get_cart_item_count($target_user_id);
        
        // 商品情報を取得
        require_once (dirname(__FILE__)."/../class/spiritTypeClass.php");
        $spiritType = new SpiritTypeClass();
        $spiritTypeArray = $spiritType->getSpiritTypeKeyTypeNum();
        
        $item_price = 0;
        if (isset($spiritTypeArray[$item_id]['price'])) {
            $item_price = $spiritTypeArray[$item_id]['price'];
        }

        $item_total = $item_price * $quantity;
        
        // カート全体の合計金額を計算
        $cart_total = 0;
        foreach($cart as $id => $qty) {
            $item_info = $spiritTypeArray[$id] ?? null;
            if($item_info && isset($item_info['price'])) {
                $cart_total += $item_info['price'] * $qty;
            }
        }

        wp_send_json_success([
            'cart_count' => $cart_count,
            'item_total' => $item_total,
            'cart_total' => $cart_total,
            'formatted_item_total' => '￥' . number_format($item_total),
            'formatted_cart_total' => '￥' . number_format($cart_total),
            'message' => 'カートを更新しました。'
        ]);

    } else {
        wp_send_json_error('商品IDが無効です。');
    }
}
?>