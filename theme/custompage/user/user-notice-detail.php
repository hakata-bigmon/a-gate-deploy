<?php 

    require_once (dirname(__FILE__)."/../../custompage/admin/a-gate-functions.php");
    require_once (dirname(__FILE__)."/../../class/spiritNewsClass.php");


    //ポストユーザー
    $user_id = get_current_user_id();

    //もし管理者等でチェックするユーザーがいるならここで変更する
    $get_url = CheckUserPageAdmin($user_id);


    //newのID
    $post_id = $_GET["id"];


    $newsClass = new SpiritNewsClass(); //ニュースデータ


    $newData = $newsClass->getNewsPostDate($user_id);
    

    $no_post = false;//記事が見れるかどうか

    $traget_text = ""; //対象者
    
    $post_user = get_field('acf_news_all_post', $post_id);

    if($post_user == "all_users")
    {
        //全員なので見れる
        $traget_text = "全員";
        $no_post = true;

    }
    else if($post_user == "no_users" || $post_user == "")
    {
        $no_post = false;//指定されてないので見れない

        $traget_text = "対象者なし";
    }
    else if($post_user == "specific_users")
    {


        $member_array = $newsClass->getEnablePostUserID($post_id);//ユーザーのデータ取得
        

        if(!isset($member_array[$user_id]))
        {
            $no_post = false;//指定されてなかったら見れない
        }
        else{
            $no_post = true;
        }

        //対象者を表示用
        foreach ($member_array as $key => $value) {

            $traget_text .= $value . ",";

        }

    }

    //表示しない場合は見れない
    if(get_field('acf_news_disp', $post_id) == "")
    {
        $no_post = false;
    }

    //告知日を取得
    $disp_day = $newsClass->getPostDate($post_id);

    //告知日で見れるかどうの確認
    if(!$newsClass->isPostDate($post_id))
    {
        $no_post = false;
    }



    if(current_user_can('administrator') || current_user_can('Editor'))
    {
        //管理者は見れる
        $no_post = true;
    }
     //var_dump($_POST);
?>
<div class="user-top-area">

    <div class="user-jorei-section-title-wrap">
        <div class="user-jorei-section-title">ニュース</div>
    </div>

    

    <?php if($no_post){?>

        <?php 
            
        
            //全記事情報を取得
			$all_news = $newsClass->getNewsPostDateNoSort($user_id);

            //既読にする
            $newsClass->setUnreadPost($user_id,$post_id);

            //個別判断
            $is_individual = false;


            //告知日を取得
            $disp_day = $newsClass->getPostDateHyphen($post_id);

          

            $date_unix_base = new DateTime($disp_day);

            //ユニックスタイム変換
            $date_unix = $date_unix_base->format('U');

          

            if(isset($newData["individual"][$date_unix]))
            {
                if(in_array($post_id, $newData["individual"][$date_unix]))
                {
                    $is_individual = true;
                }
            }
        ?>

        <div class="notice-header">
            <div class="notice-date"><?php echo $newsClass->getPostDate($post_id); ?>
                <?php if($is_individual){ ?>
                    <span class="individual-tag">個別</span>
                <?php } ?>
            </div>
            <div class="notice-title-body">
                <?php
                    //告知があるのみ
                    if(get_field('acf_news_post', $post_id) != "")
                    {
                        if(!$newsClass->checkUnreadPost($user_id , $post_id))
                        {
                            echo '<span class="new-tag">NEW</span>';
                        }
                    }
                ?>
                <div class="notice-title"><?php echo get_the_title($post_id); ?></div>
            </div>
        </div>

        <div class="notice-content">
            <?php 
                $post_contens = get_post_field('post_content', $post_id);
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

            <div class="content-text">
                <?php echo wp_kses($post_contens, $allowed_tags); ?>
                </div>
</div>

<style>
.notice-header {
    margin: 30px 0;
    padding: 20px 0;
    border-bottom: 1px solid #e0e0e0;
}

.notice-date {
    color: #888;
    font-size: 14px;
    margin-bottom: 15px;
    font-family: 'Noto Sans JP', sans-serif;
}

.notice-title-body {
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

.notice-title {
    color: #C18AD1;
    font-size: 15px;
    line-height: 1.6;
    font-family: 'Noto Sans JP', sans-serif;
    flex: 1;
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

.notice-content {
    margin: 40px 0;
    padding: 20px;
    background: white;
    border-radius: 8px;
}

.content-text {
    
    font-family: 'Noto Sans JP', sans-serif;
   
}

.content-text p {
    margin-bottom: 15px;
}

.article-navigation {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 50px;
    padding: 20px 0;
    border-top: 1px solid #e0e0e0;
}

.nav-link {
    color: #C18AD1;
    text-decoration: none;
    font-size: 18px;
    font-family: 'Noto Sans JP', sans-serif;
    transition: color 0.2s;
    padding: 8px 16px;
}

.nav-link:hover {
    color: #A06AB8;
}

@media (max-width: 768px) {
    .article-navigation {
      
    }
    
    .prev-link,
    .next-link {
        margin: 0;
    }
}
</style>

        <?php 
            if(isset($all_news[$post_id])){
                $keys = array_keys($all_news);
                $currentPosition = array_search($post_id, $keys);
                // 前のキー（存在する場合）
                $prevKey = $currentPosition > 0 ? $keys[$currentPosition - 1] : null;
                // 次のキー（存在する場合）
                $nextKey = $currentPosition < count($keys) - 1 ? $keys[$currentPosition + 1] : null;
        ?>
            <div class="article-navigation">
                <?php if($prevKey !== null): ?>
                    <a href="<?php echo getURLSetSlag("users/user-notice-detail"); echo $get_url["add"]; ?>&id=<?php echo $prevKey;?>" class="nav-link prev-link">
                        &lt;前の記事へ
                    </a>
                <?php endif; ?>
                
                <?php if($nextKey !== null): ?>
                    <a href="<?php echo getURLSetSlag("users/user-notice-detail"); echo $get_url["add"]; ?>&id=<?php echo $nextKey;?>" class="nav-link next-link">
                        次の記事へ&gt;
                    </a>
                <?php endif; ?>
            </div>
        <?php } ?>

    <?php }else{?>
        
    <?php }?>



    <div class="user-account-edit-form-btn-wrap" style="margin-top: 80px;margin-bottom: 100px;">
        <a href="<?php echo getURLSetSlag("users/user-notice"); echo $get_url["add"]; ?>" class="user-account-edit-return-btn">一覧へ戻る  &gt;</a>
    </div>      

    <div class="user-account-edit-form-btn-wrap" style="margin-top: 80px;margin-bottom: 100px;">
        <a href="<?php echo getURLSetSlag("users/user_top"); echo $get_url["add"]; ?>" class="user-account-edit-return-btn">TOPへ戻る  &gt;</a>
    </div>    
</div>
