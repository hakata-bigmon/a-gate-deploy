/**
 * ショッピングカート機能 - JavaScript
 */

console.log('Cart.js is loading...');
console.log('Cart.js file path:', document.currentScript ? document.currentScript.src : 'unknown');

// 読み込み完了フラグを設定
window.cartScriptLoaded = true;
console.log('Cart.js loaded successfully');

// グローバル関数として定義
window.updateCartBadge = function(count) {
    console.log('updateCartBadge called with count:', count);
    jQuery(function($) {
        var badge = $('.user-official-store-cart-badge, #cart-badge');
        console.log('Found badge elements:', badge.length);
        badge.text(count);
        
        // カウントが0の場合でも表示（0を表示）
        badge.show();
        
        // アニメーション効果
        badge.addClass('updated');
        setTimeout(function() {
            badge.removeClass('updated');
        }, 300);
        
        console.log('Cart badge updated to:', count);
    });
};

// 初期化関数
window.initializeCart = function() {
    if (typeof ajax_object !== 'undefined' && ajax_object.current_cart_count !== undefined) {
        updateCartBadge(ajax_object.current_cart_count);
        console.log('Cart initialized with count:', ajax_object.current_cart_count);
    }
};

// DOMContentLoadedイベントで初期化
document.addEventListener('DOMContentLoaded', function() {
    console.log('Cart.js DOMContentLoaded event fired');
    // 少し遅延させて他のスクリプトが読み込まれるのを待つ
    setTimeout(function() {
        initializeCart();
    }, 100);
});

jQuery(document).ready(function($) {
    console.log('Cart.js jQuery ready event fired');
    
    // 初期化実行
    initializeCart();
    
    /**
     * カートに追加ボタンのクリックイベント
     */
    $(document).on('click', '.user-official-store-button', function(e) {
        e.preventDefault();
        
        // イベントがキャッチされたことを確認
       // alert('ボタンがクリックされました！');
        
        var button = $(this);
        var itemId = button.data('item-id');
        var quantity = button.data('quantity') || 1;
        
        console.log('Add to cart clicked:', itemId, quantity);
        
        // アイテムIDが設定されていない場合はエラー
        if (!itemId) {
            alert('商品IDが設定されていません');
            console.error('No item ID found');
            return;
        }
        
        // ajax_objectが定義されているかチェック
        if (typeof ajax_object === 'undefined') {
            alert('AJAX設定が読み込まれていません');
            console.error('ajax_object is undefined');
            return;
        }
        
        // ボタンを無効化してローディング表示
        button.prop('disabled', true);
        button.addClass('loading');
        
        // 送信データをログに出力
        var requestData = {
            action: 'add_to_cart',
            item_id: itemId,
            quantity: quantity,
            target_user_id: ajax_object.target_user_id,
            nonce: ajax_object.nonce
        };
        console.log('Sending AJAX request with data:', requestData);
        
        // 保存前にアラートで確認
        //alert('保存予定の情報:\nユーザーID: ' + ajax_object.target_user_id + '\n商品ID: ' + itemId);
        
        // AJAX処理
        $.ajax({
            url: ajax_object.ajax_url,
            type: 'POST',
            data: requestData,
            success: function(response) {
                console.log('AJAX Response:', response);
                
                if (response.success) {
                    // 成功時の処理
                    showMessage('カートに追加しました', 'success');
                    
                    // カートバッジの更新
                    updateCartBadge(response.data.cart_count);
                    
                    // ボタンアニメーション
                    button.removeClass('loading');
                    button.find('.button-text').text('追加完了！');
                    
                    setTimeout(function() {
                        button.find('.button-text').text('カートに入れる');
                        button.prop('disabled', false);
                    }, 1500);
                    
                } else {
                    // エラー時の処理
                    console.error('Error:', response.data);
                    showMessage(response.data || 'エラーが発生しました', 'error');
                    resetButton(button);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', xhr.responseText);
                showMessage('通信エラーが発生しました', 'error');
                resetButton(button);
            }
        });
    });
    
    /**
     * ボタンを元の状態に戻す
     */
    function resetButton(button) {
        button.removeClass('loading');
        button.prop('disabled', false);
    }
    
    /**
     * メッセージ表示
     */
    function showMessage(message, type) {
        // 既存のメッセージを削除
        $('.cart-message').remove();
        
        var messageClass = type === 'success' ? 'cart-message-success' : 'cart-message-error';
        var messageHtml = '<div class="cart-message ' + messageClass + '">' + message + '</div>';
        
        // メッセージを表示
        $('body').append(messageHtml);
        
        // 3秒後に自動で非表示
        setTimeout(function() {
            $('.cart-message').fadeOut(300, function() {
                $(this).remove();
            });
        }, 3000);
    }
    
    // デバッグ用：現在のカート状態をコンソールに出力
    console.log('Cart script loaded. Current ajax_object:', typeof ajax_object !== 'undefined' ? ajax_object : 'undefined');
});