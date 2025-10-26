<?php 
    require_once (dirname(__FILE__)."/../../custompage/admin/a-gate-functions.php");
    require_once (dirname(__FILE__)."/../../class/spiritTypeClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritSalesClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritUserClass.php");
    
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


    $type_data = $spiritTypeArray[ $_GET["sales_id"] ];
    //販売ページデータ
    $saledata = $spiritSales->getSalesPage($type_data , $type_data["sales_page"]);

	//var_dump($cart_contents);
    $userClass = new SpiritUserClass(); //ユーザー管理
    $userData = $userClass->getUserAcountData($user_id);//ユーザー情報

    $img_count = 1;

    for($i = 1; $i <= 15; $i++)
    {
        if($saledata["画像".$i] != "")
        {
            $img_count++;
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


    <div class="product-detail-container">
        <div class="product-gallery">
            <div class="main-image-container">
                <img src="<?php echo $saledata["サムネイル"];?>" alt="商品画像" class="main-product-image" id="mainImage">
                <div class="image-counter">1 / <?php echo $img_count;?></div>
                <div class="nav-arrow nav-prev">‹</div>
                <div class="nav-arrow nav-next">›</div>
            </div>
            
            <div class="product-info">
                <div class="product-description">
                    <?php echo $saledata["表示名"];?>
                </div>
            </div>
            
            <hr style="margin-bottom: 20px;color: #CCCCCC;">

            <div class="thumbnail-gallery">
                <div class="thumbnail-item active" data-image="<?php echo $saledata["サムネイル"];?>" data-index="0">
                    <img src="<?php echo $saledata["サムネイル"];?>" alt="サムネイル">
                </div>
                <?php 
                    $index = 1;
                    for($i = 1; $i <= 15; $i++)
                    {
                        if($saledata["画像".$i] == "")
                        {
                            continue;
                        }
                ?>
                    <div class="thumbnail-item" data-image="<?php echo $saledata["画像".$i];?>" data-index="<?php echo $index;?>">
                        <img src="<?php echo $saledata["画像".$i];?>" alt="サムネイル">
                    </div>
                <?php 
                        $index++;
                    }
                ?>
            </div>
            
            <hr style="margin-bottom: 20px;color: #CCCCCC;">

            <?php if($userData["認証"] ==  "2"){?>
              <div class="product-purchase-section">
                  
                  <div class="price-info">
                      <div class="stock-badge">残り<?php echo $type_data["stock"];?>点</div>
                      <span class="price">¥<?php echo number_format($saledata["価格"]);?></span>
                      <span class="tax-info">税込</span>
                      <span class="shipping-info">/ (送料無料)</span>
                  </div>
                  <hr style="margin: 15px 0; color: #CCCCCC;">
                  
                  <div class="quantity-section">
                      <label for="quantity">数量:</label>
                      <select id="quantity" name="quantity" class="quantity-select">
                          <?php for($i = 1; $i <= $type_data["stock"]; $i++)
                          {
                          ?>
                          <option value="<?php echo $i;?>"><?php echo $i;?></option>
                          <?php
                          }
                          ?>
                      </select>
                  </div>

                  <div class="user-account-edit-form-btn-wrap" style="margin-top: 10px; display: flex; justify-content: center;">
                      <button type="button" class="add-to-cart-btn" data-sales-id="<?php echo $_GET["sales_id"]; ?>">　 カートに入れる   &gt;</button>
                  </div>

                  <div class="user-account-edit-form-btn-wrap" style="margin-top: 10px; display: flex; justify-content: center;">
                      <a href="<?php echo getURLSetSlag("users/user-store-cart"); echo $get_url["add"]; ?>" class="user-account-edit-return-btn">　 レジに進む   &gt;</a>
                  </div>    
                  
                  <hr style="margin: 15px 0; color: #CCCCCC;">
                  
                
              </div>
            <?php }else{?>
                <div class="user-spirit-list-menu-contens-wrap">
                    <div class="user-spirit-list-menu-contens-item">
                        <div class="user-spirit-list-menu-contens-item-title">会員情報認証が完了するまではご購入できません。</div>
                    </div>
                </div>
            <?php }?>
       
            <div style="color: #888888;font-size: 14px;">

                <?php $post_contens = get_post_field('post_content', $type_data["sales_page"]); ?>
                                        
                <?php if($post_contens !=  ""){ ?>


                    <?php 
                        
                            $post_contens = wpautop($post_contens);

                            // 許可するHTML要素と属性を指定
                        $allowed_tags = wp_kses_allowed_html('post');
                        $allowed_tags['img'] = array(
                            'src' => true,
                            'alt' => true,
                            'title' => true,
                            'width' => true,
                            'height' => true,
                        );
                        
                        
                    ?>

                    <div class="" style="width: 500px;">
                        <?php echo  wp_kses($post_contens, $allowed_tags);?>
                    </div>
                <?php } ?>

            </div>

        </div>
    </div>

    <div class="user-account-edit-form-btn-wrap" style="margin-top: 80px;margin-bottom: 100px;">
        <a href="<?php echo getURLSetSlag("users/user-official-store"); echo $get_url["add"]; ?>" class="user-account-edit-return-btn">ストアへ戻る  &gt;</a>
    </div>    
</div>

<script>
// Ajax URLを設定
var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
</script>

<style>
.product-detail-container {
  max-width: 800px;
  margin: 0 auto;
  padding: 20px;
}
.product-gallery {
    background: #fff;
    border-radius: 8px;
    overflow: hidden;
    max-width: 500px;
    margin-left: auto;
    margin-right: auto;
}
.main-image-container {
  position: relative;
  background: #fff;
  margin-bottom: 20px;
}
.main-product-image {
    width: 100%;
    height: auto;
    display: block;
    max-width: 500px;
    margin-left: auto;
    margin-right: auto;
}
.image-counter {
  position: absolute;
  bottom: 10px;
  left: 50%;
  transform: translateX(-50%);
  background: rgba(0,0,0,0.6);
  color: #fff;
  padding: 4px 12px;
  border-radius: 4px;
  font-size: 12px;
}
.zoom-icon {
  position: absolute;
  bottom: 10px;
  right: 10px;
  background: rgba(0,0,0,0.6);
  color: #fff;
  padding: 8px;
  border-radius: 4px;
  font-size: 14px;
  cursor: pointer;
}
.nav-arrow {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  background: rgba(0,0,0,0.6);
  color: #fff;
  padding: 12px 8px;
  border-radius: 4px;
  font-size: 18px;
  cursor: pointer;
  font-weight: bold;
}
.nav-prev {
  left: 10px;
}
.nav-next {
  right: 10px;
}
.product-info {
  padding: 0 20px 20px 20px;
}
.product-description {
  font-size: 16px;
  line-height: 1.6;
  color: #555;
  text-align: left;
}
.thumbnail-gallery {
  display: flex;
  gap: 10px;
  padding: 0 20px 20px 20px;
  justify-content: start;
}
.thumbnail-item {
  width: 60px;
  height: 60px;
  border: 2px solid transparent;
  border-radius: 4px;
  overflow: hidden;
  cursor: pointer;
  transition: border-color 0.2s;
}
.thumbnail-item.active {
  border-color: #007cba;
}
.thumbnail-item img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}
.color-options {
  text-align: center;
  padding: 0 20px 20px 20px;
  font-size: 14px;
  color: #666;
}

.product-purchase-section {
  padding: 0 20px 20px 20px;
}

.price-info {
  display: flex;
  align-items: center;
  gap: 10px;
  flex-wrap: wrap;
  justify-content: center;
}

.stock-badge {
  background: #007cba;
  color: white;
  padding: 4px 8px;
  border-radius: 4px;
  font-size: 12px;
  font-weight: bold;
  width: 50px;
  text-align: left;
}

.price {
  font-size: 24px;
  font-weight: bold;
  color: #333;
}

.tax-info {
  font-size: 14px;
  color: #666;
}

.shipping-info {
  font-size: 14px;
  color: #666;
}

.quantity-section {
  display: flex;
  align-items: center;
  gap: 10px;
  justify-content: center;
}

.quantity-section label {
  font-size: 16px;
  color: #333;
  font-weight: 500;
}

.quantity-select {
  padding: 8px 12px;
  border: 1px solid #ddd;
  border-radius: 6px;
  font-size: 16px;
  background: white;
  cursor: pointer;
}

.action-buttons {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.add-to-cart-btn,
.checkout-btn {
  padding: 12px 20px;
  border: none;
  border-radius: 6px;
  font-size: 16px;
  font-weight: 500;
  cursor: pointer;
  transition: background-color 0.2s;
}

.add-to-cart-btn {
  background: #f0f0f0;
  color: #333;
}

.add-to-cart-btn:hover {
  background: #e0e0e0;
}

.checkout-btn {
  background: #007cba;
  color: white;
}

.checkout-btn:hover {
  background: #005a8b;
}

.add-to-cart-btn {
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
  /* 以下の行を削除または変更 */
  padding: 0; /* padding: 12px 20px; を削除 */
  font-weight: normal; /* font-weight: 500; を削除 */
}

.add-to-cart-btn:hover {
  background: #f8f4ff;
  color: #C18AD1;
  text-decoration: none;
}

.add-to-cart-btn:disabled {
  background: #f5f5f5;
  color: #cccccc;
  border-color: #cccccc;
  cursor: not-allowed;
}
@media (max-width: 768px) {
  .product-detail-container {
    padding: 10px;
  }
  .thumbnail-gallery {
    flex-wrap: wrap;
    gap: 8px;
  }
  .thumbnail-item {
    width: 50px;
    height: 50px;
  }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const mainImage = document.getElementById('mainImage');
    const thumbnails = document.querySelectorAll('.thumbnail-item');
    const imageCounter = document.querySelector('.image-counter');
    const navPrev = document.querySelector('.nav-prev');
    const navNext = document.querySelector('.nav-next');
    let currentIndex = 0;

    function updateMainImage(index) {
        const thumbnail = thumbnails[index];
        const imageSrc = thumbnail.getAttribute('data-image');
        mainImage.src = imageSrc;
        
        // アクティブクラスの更新
        thumbnails.forEach(t => t.classList.remove('active'));
        thumbnail.classList.add('active');
        
        // カウンターの更新
        currentIndex = index;
        imageCounter.textContent = `${currentIndex + 1} / ${thumbnails.length}`;
    }

    thumbnails.forEach((thumbnail, index) => {
        thumbnail.addEventListener('click', function() {
            updateMainImage(index);
        });
    });

    // ナビゲーション矢印
    if (navPrev && navNext) {
        navPrev.addEventListener('click', function() {
            const newIndex = currentIndex > 0 ? currentIndex - 1 : thumbnails.length - 1;
            updateMainImage(newIndex);
        });

        navNext.addEventListener('click', function() {
            const newIndex = currentIndex < thumbnails.length - 1 ? currentIndex + 1 : 0;
            updateMainImage(newIndex);
        });
    }

    // カートに入れるボタンの処理
    const addToCartBtn = document.querySelector('.add-to-cart-btn');
    if (addToCartBtn) {
        addToCartBtn.addEventListener('click', function() {
            const salesId = this.getAttribute('data-sales-id');
            const quantity = document.getElementById('quantity').value;
            const cartBadge = document.querySelector('.user-official-store-cart-badge');
            
            // ボタンを無効化
            this.disabled = true;
            this.textContent = '処理中...';
            
            // Ajaxリクエスト
            jQuery.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'update_cart_quantity',
                    nonce: '<?php echo wp_create_nonce("cart_nonce"); ?>',
                    item_id: salesId,
                    quantity: quantity,
                    target_user_id: <?php echo $target_user_id; ?>
                },
                success: function(response) {
                    if (response.success) {
                        // カートバッジを更新
                        if (cartBadge) {
                            cartBadge.textContent = response.data.cart_count;
                        }
                        
                        // 成功メッセージを表示
                        const messageDiv = document.createElement('div');
                        messageDiv.className = 'cart-message success';
                        messageDiv.textContent = 'カートの中が変更されました';
                        messageDiv.style.cssText = 'background: #d4edda; color: #155724; padding: 10px; margin: 10px 0; border-radius: 4px; text-align: center;';
                        
                        const purchaseSection = document.querySelector('.product-purchase-section');
                        purchaseSection.insertBefore(messageDiv, purchaseSection.firstChild);
                        
                        // 3秒後にメッセージを削除
                        setTimeout(function() {
                            messageDiv.remove();
                        }, 3000);
                        
                    } else {
                        alert('エラー: ' + response.data);
                    }
                },
                error: function() {
                    alert('通信エラーが発生しました');
                },
                complete: function() {
                    // ボタンを元に戻す
                    addToCartBtn.disabled = false;
                    addToCartBtn.textContent = '　 カートに入れる   >';
                }
            });
        });
    }
});
</script>



