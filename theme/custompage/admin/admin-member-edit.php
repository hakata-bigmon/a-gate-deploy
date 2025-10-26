<?php

    // require_once (dirname(__FILE__)."/../../class/ConnectionGroupClass.php");
    // $connection_group_data = new ConnectionGroupClass(); //管理データ
    // $cgroup_data = $connection_group_data->GetConnectionGroup();
    
    require_once (dirname(__FILE__)."/../../class/ConnectionGroupClass.php");
    require_once (dirname(__FILE__)."/../../class/GroupSettingClass.php");

    if(isset($_GET['user_id'])) $user_id = $_GET['user_id'];
    $group_setting_data = new GroupSettingClass();      //グループクラス
    $group_list = $group_setting_data->getGroupData();  //グループリスト
    $group_name = $group_setting_data->getGroupName();  //グループリスト
    
    $connection_group_data = new ConnectionGroupClass();            //管理データ
    $cgroup_data = $connection_group_data->GetConnectionGroup(true);    //関連データ取得
    
   
    $connection_id = json_decode(get_user_meta( $user_id, 'connect_group', true));//所属関連取得

?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/custompage/admin/a-gate-script.js?<?php echo date('Ymd H:i:s'); ?>"></script>

<?php 

    if(!isset($_POST["change_user"]) && !isset($_POST["do_change_user"])){
        if(isset($_GET['test'])){

            require_once ("_admin-member-edit_read.php");
        }else{
            require_once ("admin-member-edit_read.php");

        }
    }else{
        
        require_once ("admin-member-edit_change.php");
    }
    
    
?>

