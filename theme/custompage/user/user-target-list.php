<?php 

    require_once (dirname(__FILE__)."/../../custompage/admin/a-gate-functions.php");
    require_once (dirname(__FILE__)."/../../class/spiritUserClass.php");


    //ポストユーザー
    $user_id = get_current_user_id();

    //もし管理者等でチェックするユーザーがいるならここで変更する
    $get_url = CheckUserPageAdmin($user_id);

    $userClass = new SpiritUserClass(); //ユーザー管理


   

    $userData = $userClass->getUserAcountData($user_id);


    $TargetList = $userClass->getTargetAcountData($user_id);

     //対象者の削除
    $delete_text = "";

     if(isset($_POST["delete_target_id"]))
    {
        if(isset( $TargetList[ $_POST["delete_target_id"] ] ))
        {
            wp_delete_post($_POST["delete_target_id"], true);
            $delete_text = "<div style='color:red;text-align:center;'>対象者を削除しました</div>";

            $TargetList = $userClass->getTargetAcountData($user_id);
        }
    }
    
   // var_dump($TargetList);
?>


<div class="user-top-area">

    <div class="user-jorei-section-title-wrap">
        <div class="user-jorei-section-title">対象者設定</div>
    </div>

    <?php include(dirname(__FILE__)."/user-acount-menu.php"); ?>



</div>


<div>
    <?php echo $delete_text;?>
</div>

<style>
.target-list-container {
    max-width: 700px;
    margin: 40px auto 0 auto;
    font-family: 'Noto Sans JP', 'ヒラギノ角ゴ ProN', 'Hiragino Kaku Gothic ProN', 'メイリオ', Meiryo, sans-serif;
}
.target-list-row {
    display: flex;
    align-items: center;
    background: #f3e9fa;
    border-radius: 18px;
    margin-bottom: 18px;
    padding: 12px 18px;
    box-shadow: none;
}
.target-label {
    background: #b388dd;
    color: #fff;
    border-radius: 12px;
    padding: 4px 16px;
    font-size: 15px;
    font-weight: 500;
    margin-right: 18px;
    min-width: 60px;
    text-align: center;
}
.target-title {
    flex: 1;
    font-size: 16px;
    color: #5a3e8a;
    font-weight: 600;
    margin-right: 18px;
    min-width: 120px;
}
.target-btn {
    background: #fff;
    color: #b388dd;
    border: 1.5px solid #b388dd;
    border-radius: 16px;
    padding: 6px 22px;
    font-size: 15px;
    font-weight: 500;
    margin-left: 10px;
    cursor: pointer;
    transition: background 0.2s, color 0.2s;
}
.target-btn:hover {
    background: #b388dd;
    color: #fff;
}
.add-target-btn {
    background: #b388dd;
    color: #fff;
    border: none;
    border-radius: 20px;
    padding: 12px 40px;
    font-size: 16px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(179, 136, 221, 0.3);
    font-family: 'Noto Sans JP', 'ヒラギノ角ゴ ProN', 'Hiragino Kaku Gothic ProN', 'メイリオ', Meiryo, sans-serif;
}
.add-target-btn:hover {
    background: #9c6bc4;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(179, 136, 221, 0.4);
}
@media (max-width: 600px) {
    .target-list-row { flex-direction: column; align-items: stretch; }
    .target-label, .target-title, .target-btn { margin: 6px 0;}

    .target-btn {width: 100%;}
    .target-title{
        font-size: 24px;
        text-align: center;
    }
}
</style>

<div class="target-list-container" style="min-height: 440px;">


<div style="text-align: center; margin-bottom: 30px;">
    <form action="<?php echo getURLSetSlag("users/user-target"); echo $get_url["add"];?>" method="post" >
        <button type="submit" class="add-target-btn">対象者追加</button>
    </form>
</div>

<?php if(count($TargetList) > 0){ ?>
    <?php foreach ($TargetList as $key => $value) { ?>
        <?php $target_data = $userClass->getUserTargetData($user_id , $value);//対象者情報 ?>
        <div class="target-list-row">
            <span class="target-label"><?php echo $target_data["関係"];?></span>
            <span class="target-title"><?php echo $target_data["フル名前"];?></span>
            <form action="<?php echo getURLSetSlag("users/user-target"); echo $get_url["add"];?>" method="post" style="display:inline;">
                <input type="hidden"  name="target_id" value="<?php echo $value; ?>">
                <button type="submit" class="target-btn">編集</button>
            </form>
            <form action="<?php echo getURLSetSlag("users/user-target-list"); echo $get_url["add"];?>" method="post" style="display:inline;" onSubmit="return delete_check()">
                <input type="hidden"  name="target_id" value="<?php echo $value; ?>">
                <input type="hidden"  name="delete_target_id" value="<?php echo $value; ?>">
                <button type="submit" class="target-btn">削除</button>
            </form>
        </div>
    <?php } ?>
<?php }else{ ?>
    <div style="text-align:center; color:#b388dd; font-size:16px; margin-top:40px;">対象者は登録されていません</div>
<?php } ?>

<div class="user-account-edit-form-btn-wrap" style="margin-top: 80px;margin-bottom: 100px;">
    <a href="<?php echo getURLSetSlag("users/user_top"); echo $get_url["add"]; ?>" class="user-account-edit-return-btn">TOPへ戻る  &gt;</a>
</div>
</div>

<script>
function delete_check(){
    if(window.confirm('削除してもよろしいですか？')){
        return true;
    } else {
        window.alert('キャンセルされました');
        return false;
    }
}
</script>