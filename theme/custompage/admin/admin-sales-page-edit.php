


<?php 

    require_once ("a-gate-functions.php");
    require_once (dirname(__FILE__)."/../../class/spiritTypeClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritSalesClass.php");


    //タイプデータ
    $spiritType = new SpiritTypeClass(); //管理データ

    //販売ページデータ
    $spiritSales = new SpiritSalesClass(); //管理データ


    //指定データ
    $spiritTypeArray = $spiritType->getSpiritTypeKeyTypeNum();

    //物販のみのデータを取得
    $sales_array = $spiritType->getSpiritSalesData(6);

    $type = "";

    if(isset($_GET["type_id"]))
    {
        $type = $_GET["type_id"];
    }

    //商品自体のデータ
    $type_data = $spiritTypeArray[ $type ];

    $page_id = "";

     //販売ページの指定がない場合は販売ページを作成
    if($type != "" && $type_data["sales_page"] == "")
    {
        $page_id = $spiritSales->newSalesPage(wp_get_current_user() , $type  , $type_data["title"]);
    }
    else if($type != ""){
        $page_id = $type_data["sales_page"];
    }
   
    

    //販売ページデータ
    $saledata = $spiritSales->getSalesPage($spiritTypeArray[ $type ] , $page_id);

    //その他のアイテム

    //var_dump($type_data);

    //var_dump($saledata["画像並び"]);

    //var_dump($saledata);
?>

<div class="admin-sales-edit-container">
    <div class="admin-sales-header">
        <div class="admin-sales-title">
            <h1><?php echo $spiritTypeArray[ $type ]["title"]?>　<?php echo $spiritType->getSpiritTypeName($type_data["group"]);?>ページ編集</h1>
        </div>
        
        <div class="admin-sales-actions">
            <button class="admin-action-btn edit-btn" type="button" onclick="window.open('<?php echo home_url(); ?>/wp-admin/post.php?post=<?php echo $page_id;?>&action=edit', '_blank')">
                <span class="btn-icon">📝</span>
                ページ編集
            </button>
            
            <?php if($type_data["group"] == SpiritTypeClass::SPIRIT_TYPE_NAME_SALES){?>
                <button class="admin-action-btn sort-btn" type="button" onclick="window.open('<?php echo getURLSetSlag("admin-sales-page-img-sort"); ?>?type_id=<?php echo $type; ?>', '_blank')">
                    <span class="btn-icon">🖼️</span>
                    商品画像並び替え
                </button>
            <?php }?>
            
            <button class="admin-action-btn back-btn" type="button" onclick="window.location.href='<?php echo getURLSetSlag("admin-sales-list"); ?>?type=<?php echo $type_data["group"];?>'">
                <span class="btn-icon">←</span>
                <?php echo $spiritType->getSpiritTypeName($type_data["group"]);?>ページに戻る
            </button>

            <button class="admin-action-btn back-btn" type="button" onclick="window.open('<?php echo getURLSetSlag("users/user-sales-page"); ?>?sales_id=<?php echo $type; ?>', '_blank')">
                <span class="btn-icon">←</span>
                お客様物販ページに移動
            </button>
        </div>
    </div>

    <div class="admin-sales-content">
        <div class="product-info-section">
            <div class="product-basic-info">
                <div class="product-name">
                    <h2><?php echo $saledata["表示名"];?></h2>
                </div>
                
                <div class="product-details">
                    <div class="product-stock">
                        <span class="stock-label">在庫:</span>
                        <span class="stock-value"><?php echo $type_data["stock"];?>点</span>
                    </div>
                    
                    <div class="product-price">
                        <span class="price-label">価格:</span>
                        <span class="price-value">￥<?php echo number_format($type_data["price"]);?></span>
                    </div>
                </div>
            </div>

            <?php if($saledata["サムネイル"] != ""){?>
                <div class="product-main-image">
                    <img src="<?php echo $saledata["サムネイル"]; ?>" alt="商品メイン画像">
                </div>
            <?php } ?>
        </div>

        <?php if(!empty($saledata["画像並び"])){ ?>
            <div class="product-gallery-section">
                <h3>商品画像ギャラリー</h3>
                <div class="product-gallery">
                    <?php foreach ($saledata["画像並び"] as $img_key => $img_value) { ?>
                        <div class="gallery-item">
                            <img src="<?php echo $saledata["画像" . $img_value]; ?>" alt="商品画像">
                        </div>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>

        <div class="product-description-section">
            <?php $post_contens = get_post_field('post_content', $page_id); ?>
            <?php if($post_contens !=  ""){ ?>
                <h3>商品説明</h3>
                <div class="product-description">
                    <?php 
                        $post_contens = wpautop($post_contens);
                        $allowed_tags = wp_kses_allowed_html('post');
                        $allowed_tags['img'] = array(
                            'src' => true,
                            'alt' => true,
                            'title' => true,
                            'width' => true,
                            'height' => true,
                        );
                    ?>
                    <?php echo wp_kses($post_contens, $allowed_tags);?>
                </div>
            <?php } ?>
        </div>

        <?php if(count($sales_array) > 0 && $type_data["group"] == SpiritTypeClass::SPIRIT_TYPE_NAME_SALES){ ?>
            <div class="other-products-section">
                <h3>その他の商品</h3>
                <div class="other-products-grid">
                    <?php foreach ($sales_array as $key => $value){ ?>
                        <div class="other-product-item">
                            <div class="other-product-info">
                                <div class="other-product-name"><?php echo $value["title"]; ?></div>
                                <div class="other-product-price">￥<?php echo number_format($value["price"]); ?></div>
                                <div class="other-product-stock">在庫: <?php echo $value["stock"]; ?>点</div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

<style>
/* 管理画面物販ページ編集のスタイル */
.admin-sales-edit-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.admin-sales-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 2px solid #f0f0f0;
}

.admin-sales-title h1 {
    margin: 0;
    color: #333;
    font-size: 28px;
    font-weight: 600;
}

.admin-sales-actions {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
}

.admin-action-btn {
    padding: 12px 20px;
    border: none;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
    color: #333;
}

.edit-btn {
    background: #007cba;
    color: white;
}

.edit-btn:hover {
    background: #005a8b;
}

.sort-btn {
    background: #28a745;
    color: white;
}

.sort-btn:hover {
    background: #218838;
}

.back-btn {
    background: #6c757d;
    color: white;
}

.back-btn:hover {
    background: #545b62;
}

.btn-icon {
    font-size: 16px;
}

.admin-sales-content {
    display: grid;
    gap: 30px;
}

.product-info-section {
    display: grid;
    grid-template-columns: 1fr auto;
    gap: 30px;
    align-items: start;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 8px;
}

.product-basic-info {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.product-name h2 {
    margin: 0;
    color: #333;
    font-size: 24px;
    font-weight: 600;
}

.product-details {
    display: flex;
    gap: 30px;
    flex-wrap: wrap;
}

.product-stock, .product-price {
    display: flex;
    align-items: center;
    gap: 8px;
}

.stock-label, .price-label {
    font-weight: 600;
    color: #666;
}

.stock-value, .price-value {
    font-size: 18px;
    font-weight: 600;
    color: #333;
}

.price-value {
    color: #dc3545;
}

.product-main-image {
    max-width: 300px;
}

.product-main-image img {
    width: 100%;
    height: auto;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.product-gallery-section h3,
.product-description-section h3,
.other-products-section h3 {
    margin: 0 0 20px 0;
    color: #333;
    font-size: 20px;
    font-weight: 600;
    padding-bottom: 10px;
    border-bottom: 2px solid #e9ecef;
}

.product-gallery {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 15px;
}

.gallery-item {
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    transition: transform 0.2s ease;
}

.gallery-item:hover {
    transform: translateY(-2px);
}

.gallery-item img {
    width: 100%;
    height: 150px;
    object-fit: cover;
}

.product-description {
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    border: 1px solid #e9ecef;
    line-height: 1.6;
    color: #333;
}

.other-products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 20px;
}

.other-product-item {
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    border: 1px solid #e9ecef;
    transition: box-shadow 0.2s ease;
}

.other-product-item:hover {
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.other-product-info {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.other-product-name {
    font-weight: 600;
    color: #333;
    font-size: 16px;
}

.other-product-price {
    color: #dc3545;
    font-weight: 600;
    font-size: 18px;
}

.other-product-stock {
    color: #666;
    font-size: 14px;
}

/* レスポンシブ対応 */
@media (max-width: 768px) {
    .admin-sales-header {
        flex-direction: column;
        gap: 20px;
        align-items: stretch;
    }
    
    .admin-sales-actions {
        justify-content: center;
    }
    
    .product-info-section {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    
    .product-details {
        flex-direction: column;
        gap: 15px;
    }
    
    .product-gallery {
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
    }
    
    .other-products-grid {
        grid-template-columns: 1fr;
    }
}
</style>