<?php 

    require_once ("a-gate-functions.php");
    require_once (dirname(__FILE__)."/../../class/ConnectionGroupClass.php");
    $connection_group_data = new ConnectionGroupClass(); //管理データ

?>


<div class="admin-exorcism-area">
    <div class="admin-exorcism-button-area">
        <div class="admin-exorcism-menu-title-box">
            <div class="admin-exorcism-menu-title">（親族）関連新規作成</div>
        </div>

        <form id="" action="<?php echo  getURLSetSlag( "admin-profile-connection" );?>" method="post" >
            <input type="hidden" name="add_connection">

            <div class="user-input-area">

                <div class="user-table-flex">
                    <div class="user-table-item">代表者</div>
                    <div class="user-table-data">
                        
                        <?php /* 代表者選択時 */?>
                        <?php if(isset($_POST['chose_id'])){?>
                            <input type="hidden" name="chose_user_id" value="<?php echo $_POST['chose_id']; ?>">
                            <?php 
                                $chose_user_data =  get_userdata($_POST['chose_id']);
                                echo $group_name = $chose_user_data->last_name.$chose_user_data->first_name;
                            ?>
                            <input type="hidden" name="top_name" value="<?php echo $group_name;?>">

                        <?php }else{?>

                            <input type="hidden" name="chose_connect_user">
                            <a href="<?php echo getURLSetSlag("admin-connection-top-chose"); ?>">
                                <button type="button" id="" class="admin-spirit-return-button" onclick="changeFormActionAndSubmit()">登録者から選択</button>
                            </a>
                        <?php } ?>
                        
                    </div>
                </div>

                <?php 
                    /* 

                    <div class="user-table-flex">
                        <div class="user-table-item">関連名</div>
                                <?php if(!isset($_POST['chose_id'])){?>
                                    <input class="" type="text" name="connection_name" value="" readonly>
                                <?php }else{ ?>
                                    <input class="" type="text" name="connection_name" value="<?php echo $group_name."グループ";?>">
                                <?php } ?>
                        </div>
                    </div>
                     */
                ?>
                <?php if(isset($_POST['chose_id'])){?>
                    <div class="user-table-flex">
                        <div class="user-table-item">関連名</div>
                            <input class="" type="text" name="connection_name" value="<?php echo $group_name."グループ";?>">
                        </div>
                    </div>
                        
                <?php } ?>
            <?php /* 新規作成ノミ */?>
            <?php if(!isset($_POST['group_user_id'])){?>

            <div class="">＊関連者を追加する場合は、新規作成後、関連一覧の個別の詳細から追加してください</div>

                <?php if(isset($_POST['chose_id'])){?>
                    <button class="form-btn red">作成する</button>
                <?php } ?>

            <?php } ?>
        </form>

        <div class="admin-spirit-return-button-area">
            <button type=“button” class="admin-spirit-return-button" onclick="location.href='<?php echo getURLSetSlag('admin-profile-connection'); ?>'">戻る</button>
        </div>

    </div>
</div>