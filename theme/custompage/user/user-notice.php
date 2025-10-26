<?php 

    require_once (dirname(__FILE__)."/../../custompage/admin/a-gate-functions.php");
    require_once (dirname(__FILE__)."/../../class/spiritNewsClass.php");


    //ポストユーザー
    $user_id = get_current_user_id();

    //もし管理者等でチェックするユーザーがいるならここで変更する
    $get_url = CheckUserPageAdmin($user_id);


    $newsClass = new SpiritNewsClass(); //ニュースデータ


    $newData = $newsClass->getNewsPostDate($user_id);

   

    //絞り込み保存
    if(isset($_POST["search_save"]))
    {
        $newsClass->saveUserNewsSearch(get_current_user_id(),$user_id,$_POST["notice_search_unread"]);
    }

    //search用の値を取得
    $search_array = $newsClass->getUserNewsSearch(get_current_user_id(),$user_id);


    $search_unread = $search_array["news_unread"];

     //var_dump($newData);
?>
<div class="user-top-area">

    <div class="user-jorei-section-title-wrap">
        <div class="user-jorei-section-title">ニュース一覧</div>
    </div>

    <div class="filter-buttons">
        <form action="<?php echo getURLSetSlag("users/user-notice"); echo $get_url["add"]; ?>" method="post">
            <div class="button-grid">
                <button type="submit" name="notice_search_unread" value="0" class="filter-btn <?php if($search_unread == 0){ echo "active";}?>">
                    全て表示
                </button>
                <button type="submit" name="notice_search_unread" value="3" class="filter-btn <?php if($search_unread == 3){ echo "active";}?>">
                    未読のみ &gt;
                </button>
                <button type="submit" name="notice_search_unread" value="1" class="filter-btn <?php if($search_unread == 1){ echo "active";}?>">
                    通常のお知らせ &gt;
                </button>
                <button type="submit" name="notice_search_unread" value="2" class="filter-btn <?php if($search_unread == 2){ echo "active";}?>">
                    個別のみ &gt;
                </button>
            </div>
            <input type="hidden" name="search_save" value="">
        </form>
    </div>


    <?php 
    
      $page_nation = 20;
      $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
      $items_per_page = $page_nation;
      $total_items = count($newData["all"]);
      $total_pages = ceil($total_items / $items_per_page);
      $offset = ($current_page - 1) * $items_per_page;
    
    ?>

    <div style="margin-top: 100px;border-top: 1px solid #C18AD1;margin-bottom: 50px;">

        <?php  
            $item_count = 0;
            $displayed_items = 0;

            foreach ($newData["all"] as $key => $value) {

                foreach ($value as $news_key => $news_value) {
                    
                    // ページネーション処理
                    $item_count++;
                    if ($item_count <= $offset) {
                        continue;
                    }
                    if ($displayed_items >= $items_per_page) {
                        break 2;
                    }
                    $displayed_items++;


                    //未読既読
                    if($search_unread >= 1)
                    {
                        //告知があるのみ
                        if(get_field('acf_news_post', $news_value) != "")
                        {
                            if($search_unread == 1){
                                continue;
                            }

                            if($newsClass->checkUnreadPost($user_id , $news_value))
                            {
                                //既読済
                                if($search_unread == 3){
                                    continue;
                                }
                            }
                            else{
                                //未読
                               
                            }
                        }
                        else{
                            if($search_unread >= 2)
                            {
                                continue;
                            }
                        }
                        
                    }

                    //個別判断

                    $is_individual = false;

                    if(isset($newData["individual"][$key]))
                    {
                        if(in_array($news_value, $newData["individual"][$key]))
                        {
                            $is_individual = true;
                        }
                    }
                            
                    
                    if($search_unread == 2 && !$is_individual){
                        continue;
                    }


        ?>

            <div class="notice-item">
                <div class="notice-date">
                    <?php echo $newsClass->getPostDate($news_value); ?>
                    <?php if($is_individual){ ?>
                        <span class="individual-tag">個別</span>
                    <?php } ?>
                </div>
                <div class="notice-content">
                    <?php
                        //告知があるのみ
                        if(get_field('acf_news_post', $news_value) != "")
                        {
                            if(!$newsClass->checkUnreadPost($user_id , $news_value))
                            {
                                echo '<span class="no-reading-tag">未読</span>';
                            }
                        }
                    ?>
                    <a href="<?php echo getURLSetSlag("users/user-notice-detail"); echo $get_url["add"]; ?><?php if($get_url["add"]){ echo "&id=";}else{ echo "?id=";}?><?php echo $news_value; ?>" class="notice-title">
                        <?php echo get_the_title($news_value); ?>
                    </a>
                </div>
            </div>

        <?php 
                } 
            }
        ?>

        <?php if ($total_pages > 1): ?>
            <div class="pagination">
                <?php if ($current_page > 1): ?>
                    <a href="?page=<?php echo $current_page - 1; ?>" class="page-link">&lt;</a>
                <?php endif; ?>
                
                <?php
                // 表示するページ番号を計算
                $start_page = max(1, $current_page - 2);
                $end_page = min($total_pages, $current_page + 2);
                
                // 最初のページを表示
                if ($start_page > 1): ?>
                    <a href="?page=1" class="page-link">1</a>
                    <?php if ($start_page > 2): ?>
                        <span class="page-ellipsis">...</span>
                    <?php endif; ?>
                <?php endif; ?>
                
                <?php for ($i = $start_page; $i <= $end_page; $i++): ?>
                    <?php if ($i == $current_page): ?>
                        <span class="page-link current"><?php echo $i; ?></span>
                    <?php else: ?>
                        <a href="?page=<?php echo $i; ?>" class="page-link"><?php echo $i; ?></a>
                    <?php endif; ?>
                <?php endfor; ?>
                
                <?php if ($end_page < $total_pages): ?>
                    <?php if ($end_page < $total_pages - 1): ?>
                        <span class="page-ellipsis">...</span>
                    <?php endif; ?>
                    <a href="?page=<?php echo $total_pages; ?>" class="page-link"><?php echo $total_pages; ?></a>
                <?php endif; ?>
                
                <?php if ($current_page < $total_pages): ?>
                    <a href="?page=<?php echo $current_page + 1; ?>" class="page-link">&gt;</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    </div>
</div>

<style>
.notice-item {
    padding: 20px 0;
    border-bottom: 1px solid #e0e0e0;
}

.notice-item:last-child {
    border-bottom: none;
}

.notice-date {
    color: #888;
    font-size: 14px;
    margin-bottom: 8px;
    font-family: 'Noto Sans JP', sans-serif;
}

.notice-content {
    display: flex;
    align-items: flex-start;
    gap: 12px;
}

.new-tag {
    background: #ff4444;
    color: white;
    font-size: 11px;
    font-weight: bold;
    padding: 2px 8px;
    border-radius: 4px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    flex-shrink: 0;
    margin-top: 2px;
}

.no-reading-tag {
    background: blue;
    color: white;
    font-size: 11px;
    font-weight: bold;
    padding: 2px 8px;
    border-radius: 4px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    flex-shrink: 0;
    margin-top: 2px;
}

.individual-tag {
    background: #4CAF50;
    color: white;
    font-size: 11px;
    font-weight: bold;
    padding: 2px 8px;
    border-radius: 4px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    flex-shrink: 0;
    margin-top: 2px;
    margin-left: 8px;
}

.notice-title {
    color: #555;
    text-decoration: none;
    font-size: 15px;
    line-height: 1.5;
    font-family: 'Noto Sans JP', sans-serif;
    flex: 1;
}

.notice-title:hover {
    color: #333;
    text-decoration: underline;
}

@media (max-width: 768px) {
    .notice-content {
        flex-direction: column;
        gap: 8px;
    }
    
    .new-tag {
        align-self: flex-start;
    }

    .no-reading-tag{
        align-self: flex-start;
    }
}

.pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 15px;
    margin-top: 30px;
    padding: 20px 0;
}

.page-link {
    display: inline-block;
    text-decoration: none;
    color: #C18AD1;
    font-size: 16px;
    font-family: 'Noto Sans JP', sans-serif;
    transition: color 0.2s;
    padding: 5px 8px;
}

.page-link:hover {
    color: #8B5A8B;
}

.page-link.current {
    color: #C18AD1;
    border-bottom: 2px solid #C18AD1;
    font-weight: 500;
}

.page-ellipsis {
    color: #C18AD1;
    font-size: 16px;
    padding: 5px 8px;
}

.filter-buttons {
    margin: 30px 0;
}

.button-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
    max-width: 600px;
    margin: 0 auto;
}

.filter-btn {
    background: white;
    border: 1px solid #C18AD1;
    border-radius: 25px;
    padding: 12px 20px;
    font-size: 14px;
    font-family: 'Noto Sans JP', sans-serif;
    color: #C18AD1;
    cursor: pointer;
    transition: all 0.2s;
    text-align: center;
    min-height: 45px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.filter-btn:hover {
    background: #f8f0ff;
    border-color: #A06AB8;
    color: #A06AB8;
}

.filter-btn.active {
    background: #C18AD1;
    color: white;
    border-color: #C18AD1;
}

.filter-btn.active:hover {
    background: #A06AB8;
    border-color: #A06AB8;
}
</style>