<?php 

    require_once ("a-gate-functions.php");
    require_once (dirname(__FILE__)."/../../class/spiritTypeClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritSalesClass.php");
    require_once (dirname(__FILE__)."/../../class/spiritScheduleClass.php");

    //タイプデータ
    $spiritType = new SpiritTypeClass(); //管理データ

    //販売ページデータ
    $spiritSales = new SpiritSalesClass(); //管理データ


    //指定データ
    $spiritTypeArray = $spiritType->getSpiritTypeKeyTypeNum();


    //var_dump($spiritTypeArray);
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

    //担当者取得
    $spiritSchedule = new SpiritScheduleClass();
    
    $schedule_manager = $spiritSchedule->getScheduleManager($type , $spiritTypeArray);


    //var_dump($saledata["画像並び"]);

    //var_dump($spiritTypeArray[ $type ]);
?>

<div class="admin-sales-edit-container">
    <div class="admin-sales-header">
        <div class="admin-sales-title">
            <h1><?php echo $spiritTypeArray[ $type ]["title"]?>　ページ編集</h1>
        </div>
        
        <div class="admin-sales-actions">
            <button class="admin-action-btn edit-btn" type="button" onclick="window.open('<?php echo home_url(); ?>/wp-admin/post.php?post=<?php echo $page_id;?>&action=edit', '_blank')">
                <span class="btn-icon">📝</span>
                ページ編集
            </button>
            
           
            
            <button class="admin-action-btn back-btn" type="button" onclick="window.location.href='<?php echo getURLSetSlag("admin-schedule-list"); ?>'">
                <span class="btn-icon">←</span>
                スケジュール一覧に戻る
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
                    

                    <?php if($type_data["time"] != ""){?>
                        <div class="product-price">
                            <span class="price-label">時間:</span>
                            <span class="price-value"><?php echo $type_data["time"];?>分</span>
                        </div>
                    <?php } ?>

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

        <?php if($saledata["URL"] != ""){?>
            <div class="product-description-section">
                <h3>別サイトURL</h3>
                <div class="product-description">
                    <a href="<?php echo $saledata["URL"]; ?>" target="_blank"><?php echo $saledata["URL"]; ?></a>
                </div>
            </div>
        <?php } ?>

                


        <div class="product-description-section">
            <?php $post_contens = get_post_field('post_content', $page_id); ?>
            <?php if($post_contens !=  ""){ ?>
                <h3>施術説明</h3>
                <div class="product-description">
                    <?php 
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

        <?php $post_schedule_reservation_target = get_field("acf_sales_schedule_reservation_target", $page_id);?>
        <?php if($post_schedule_reservation_target != ""){?>
            <div class="product-description-section">
                <h3>予約対象</h3>
                <div class="product-description">
                <?php 
                    $allowed_tags = wp_kses_allowed_html('post');
                    $allowed_tags['img'] = array(
                        'src' => true,
                        'alt' => true,
                        'title' => true,
                        'width' => true,
                        'height' => true,
                    );
                ?>
                    <?php echo wp_kses($post_schedule_reservation_target, $allowed_tags);?>
                </div>
            </div>
        <?php } ?>

        <?php $post_schedule_precautions = get_field("acf_sales_schedule_precautions", $page_id);?>
        <?php if($post_schedule_precautions != ""){?>
            <div class="product-description-section">
                <h3>予約に関しての注意事項</h3>
                <div class="product-description">
                    <?php 
                        $allowed_tags = wp_kses_allowed_html('post');
                        $allowed_tags['img'] = array(
                            'src' => true,
                            'alt' => true,
                            'title' => true,
                            'width' => true,
                            'height' => true,
                        );
                    ?>
                    <?php echo wp_kses($post_schedule_precautions, $allowed_tags);?>
                </div>
            </div>
        <?php } ?>

        <?php $post_schedule_precautions = get_field("acf_sales_schedule_remarks", $page_id);?>
        <?php if($post_schedule_precautions != ""){?>
            <div class="product-description-section">
                <h3>備考</h3>
                <div class="product-description">
                    <?php 
                        $allowed_tags = wp_kses_allowed_html('post');
                        $allowed_tags['img'] = array(
                            'src' => true,
                            'alt' => true,
                            'title' => true,
                            'width' => true,
                            'height' => true,
                        );
                    ?>
                    <?php echo wp_kses($post_schedule_precautions, $allowed_tags);?>
                </div>
            </div>
        <?php } ?>

        <?php $post_add_title_1 = get_field("acf_sales_schedule_add_title_1", $page_id);?>
        <?php $post_add_txt_1 = get_field("acf_sales_schedule_add_txt_1", $page_id);?>
        <?php if($post_add_title_1 != "" && $post_add_txt_1 != ""){?>
            <div class="product-description-section">
                <h3><?php echo $post_add_title_1;?></h3>
                <div class="product-description">
                    <?php 
                        $allowed_tags = wp_kses_allowed_html('post');
                        $allowed_tags['img'] = array(
                            'src' => true,
                            'alt' => true,
                            'title' => true,
                            'width' => true,
                            'height' => true,
                        );
                    ?>
                    <?php echo wp_kses($post_add_txt_1, $allowed_tags);?>
                </div>
            </div>
        <?php } ?>


        <?php if(count($schedule_manager) > 0){?>
            <div class="product-description-section">
                <h3>スタッフ</h3>
                <div class="product-description">
                <?php foreach($schedule_manager as $key => $value){?>
                        <?php $user_data = get_userdata($value);?>
                        <div class="product-description-item">
                            <div class="product-description-item-name"><?php echo $user_data->display_name;?></div>
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