<?php 


class ConnectionGroupClass
{
    public const MINE = 1;
    public const HUSBAND = 2;
    public const WIFE = 3;
    public const FIRST_SON = 4;
    public const SECOND_SON = 5;
    public const THIRD_SON = 6;
    public const FORTH_SON = 7;
    public const FIRST_DAUGHTER = 8;
    public const SECOND_DAUGHTER = 9;
    public const THIRD_DAUGHTER = 10;
    public const FORTH_DAUGHTER = 11;
    public const MOTHER = 12;
    public const FATHER = 13;
    public const GROND_MOTHER = 14;
    public const GROND_FATHER = 15;
    public const RELATIVES = 16;
    public const PARTNER = 17;
    public const OTHER = 18;

    /****************************************************
	**  続き柄取得
	******************************************************/
    public function getRelationship($relationship){
        $ret = "未設定";
        if($relationship == ConnectionGroupClass::MINE)$ret = "本人";
        else if($relationship == ConnectionGroupClass::HUSBAND) $ret = "夫";
        else if($relationship == ConnectionGroupClass::WIFE) $ret = "妻";
        else if($relationship == ConnectionGroupClass::FIRST_SON) $ret = "長男";
        else if($relationship == ConnectionGroupClass::SECOND_SON) $ret = "次男";
        else if($relationship == ConnectionGroupClass::THIRD_SON) $ret = "三男";
        else if($relationship == ConnectionGroupClass::FORTH_SON) $ret = "四男";
        else if($relationship == ConnectionGroupClass::FIRST_DAUGHTER) $ret = "長女";
        else if($relationship == ConnectionGroupClass::SECOND_DAUGHTER) $ret = "次女";
        else if($relationship == ConnectionGroupClass::THIRD_DAUGHTER) $ret = "三女";
        else if($relationship == ConnectionGroupClass::FORTH_DAUGHTER) $ret = "四女";
        else if($relationship == ConnectionGroupClass::MOTHER) $ret = "母";
        else if($relationship == ConnectionGroupClass::FATHER) $ret = "父";
        else if($relationship == ConnectionGroupClass::GROND_MOTHER) $ret = "祖父";
        else if($relationship == ConnectionGroupClass::GROND_FATHER) $ret = "祖母";
        else if($relationship == ConnectionGroupClass::RELATIVES) $ret = "親族";
        else if($relationship == ConnectionGroupClass::PARTNER) $ret = "パートナー";
        else if($relationship == ConnectionGroupClass::OTHER) $ret = "その他";

        return $ret;
    }

    /****************************************************
	**  関連設定重複チェック
	******************************************************/
    public function checkConnectionGroup($target_title){
        
        $list = $this->GetConnectionGroup( );
        foreach ($list as $post) {
            if ($post->post_title == $target_title) {
                return false;   // 重複あればfalse
            }
        }

        return true;
    }

    /****************************************************
	**  関連設定新規作成
	******************************************************/
	public function newConnectionGroup( $post)
	{
        $user = wp_get_current_user();

        $my_post = array(
            'post_title' => $post["connection_name"],
            'post_type' => 'cpt_connection_group', //カスタム投稿タイプの名称を入れる
            'post_status' => 'publish',
            'post_author' => $user->ID,
        );

        $program_id = wp_insert_post($my_post);

        if ($program_id) {
            if(isset($post["chose_user_id"])){

                update_field("acf_connection_top", $post["chose_user_id"], $program_id);//代表者ID
                update_field("acf_connection_top_name", $post["connection_name"], $program_id);//代表者指名

                // 選んだ人がいた場合に関連として追加
                $combined_data[$post["chose_user_id"]] = [
                    
                    "relationship_data" => 1,
                    "add_memo" => ""
                ];
                $json_list = json_encode($combined_data, JSON_UNESCAPED_UNICODE);

                update_field("acf_connection_count", 1, $program_id);//人数
                update_field('acf_connection_list', $json_list,$program_id);       //追加したユーザーに関連データ追加

                // グループユーザー作成
                $user_connect_data['connect_group'][0] = $program_id;
                $user_connect_data['connect_group'] = json_encode($user_connect_data['connect_group']); // json化
                
                update_user_meta($post["chose_user_id"],'connect_group',$user_connect_data['connect_group']);        // ユーザーに関連番号付与

                // ユーザー情報に関連追加
                $user_connect_data = $this->GetUserConnectGroupData($post["chose_user_id"],$program_id); //ユーザーの関連データ取得
                $this->addConnectGroup($user_connect_data,$program_id,$post["chose_user_id"]);
                

            }else{
                
                update_field("acf_connection_count", 0, $program_id);//人数
            }


        }

        
        return $program_id;

	}


    /****************************************************
	**  関連人数変更
	******************************************************/
    public function ChangeConnectionGroupCount($group_data,$group_id){

        $data_count = $this->GetConnectionGroupCount($group_data,$group_id);
        
        update_field("acf_connection_count", $data_count, $group_id);
    }

    /****************************************************
	**  関連人数取得
	******************************************************/
    public function GetConnectionGroupCount($group_data,$group_id){
        $data_count = 0;

        if(is_null($group_data)) return 0;

        // 関連ユーザ人数チェック　→　複数対応時に取得できていない
        foreach ($group_data as $user_id => $data) {
            
            $user_group_id = json_decode(get_user_meta($user_id, 'connect_group', true));

            // もし$user_group_idが配列でなければ、配列に変換
            if (!is_array($user_group_id)) {
                $user_group_id = array($user_group_id);
            }

            // in_arrayで配列の中にグループIDが含まれているか確認
            if (!in_array($group_id, $user_group_id)) {
                continue;
            }

            $data_count++;
        }

        return $data_count;
    }

    /****************************************************
	**  関連設定一覧取得
	******************************************************/
	public function GetConnectionGroup( $detail_disp = false )
	{
        // 引数の設定
        $args = array(
            'post_type'      => "cpt_connection_group", // 取得するカスタム投稿タイプ
            'posts_per_page' => -1,         // すべての投稿を取得
        );

        // クエリを作成して実行
        $query = new WP_Query($args);

        $cgroup_data = $query->posts;
        $ret_data = array();
        
        // 削除グループを除外
        foreach ($cgroup_data as $key => $value) {
            if(get_field("acf_connection_is_delete",$value->ID)) continue;
    
            if($detail_disp){
                $ret_data[$value->ID] = $value->post_title;
            }else{

                $ret_data[] = $value;
            }
        }


        return $ret_data;

	}

    /****************************************************
	**  関連設グループ名取得
	******************************************************/
    public function GetConnectGroupName(){
        $cgroup_data =  $this->GetConnectionGroup();
        $cgroup = array(); 

        foreach ($cgroup_data as $key => $value) {
            // if(get_field("acf_connection_is_delete",$value->ID)) continue;
    
            $group_top_name = get_field("acf_connection_top_name",$value->ID); //代表者名;
            if(is_null($group_top_name)) $group_top_name = "";
            
            $cgroup[] = array(
                'ID' => $value->ID,
                'group_name' => $value->post_title,                
            );
        }

        return $cgroup;
    }

    /****************************************************
	**  関連設定詳細取得
	******************************************************/
	public function GetConnectionGroupDetail( $id )
	{
        // 引数の設定
        $args = array(
            'post_type'      => "cpt_connection_group", // 取得するカスタム投稿タイプ
            'posts_per_page' => -1,         // すべての投稿を取得
            'p' => $id, // 特定のIDに一致する投稿を取得
        );

        // クエリを作成して実行
        $query = new WP_Query($args);

        // 結果を返す
        return $query->posts;

	}

    /****************************************************
	**  関連設定詳細取得
	******************************************************/
    public function GetUserConnectGroupData($user_id,$group_id){
        $ret_data = array();
        $ret_data['registed_flg'] = false; // 登録チェック

        // 複数対応に変更 ユーザーフィールドの関連グループIDと代表ID取得
        $old_connect_group = get_user_meta($user_id,'connect_group',true);
        $old_connect_disp  = get_user_meta($user_id,'connect_group_disp',true);

        // 既存データがあればデコード
        if($old_connect_group != ""){
            $old_connect_group = json_decode($old_connect_group);
            $old_connect_disp  = json_decode($old_connect_disp);
        }

        // 配列かどうかチェック 関連データが空の時に必要
        if(!is_array($old_connect_group)){
            $old_connect_group = array($old_connect_group);
            $old_connect_disp = array($old_connect_disp);
        }
        
        $ret_data['old_connect_group'] = $old_connect_group;
        $ret_data['old_connect_disp'] = $old_connect_disp;

        // 追加チェック
        foreach ($ret_data['old_connect_group'] as $value) {

            if($value == $group_id) {
                $ret_data['registed_flg'] = true;
                break;
            }
        }

        return $ret_data;
    }

    /****************************************************
	 **  関連ユーザー調整（手動用
	 ******************************************************/
    public function adjustmentConnectGroup($cgroup_data,$user_data,$group_id){
        $after_cgroup_data = array();
        // 現在の登録データ分チェック
        foreach ($cgroup_data as $key => $value) {
            foreach ($user_data as $user_value) {
                if($user_value['ID'] != $key) continue;

                $after_cgroup_data[$key] = $value;
            }
        }
        
        $json_list = json_encode($after_cgroup_data);
        $res = update_field('acf_connection_list', $json_list,$group_id);   // 最新の状態に修正
        
        $this->ChangeConnectionGroupCount($cgroup_data,$group_id); //人数変更


    }

    /****************************************************
	 **  指定ユーザーの関連削除
	 ******************************************************/
    public function deleteTargetConnectGroup($group_id,$user_id){

        // グループデータ取得
        $cgroup_data = json_decode(get_field('acf_connection_list',$group_id),true);
        
        // 指定ユーザーからの関連削除し、新たな配列作成
        $res = $this->deleteConnectGroup($cgroup_data,$group_id,$user_id);  

        if($res && $cgroup_data != NULL){
            $this->ChangeConnectionGroupCount($cgroup_data,$group_id);
        }

        return $res;
    }

    /****************************************************
	 **  関連からの削除
     // $cgroup_data:    関連グループの情報
	 ******************************************************/
    public function deleteConnectGroup($cgroup_data,$group_id,$user_id = ""){
        $after_cgroup_data = array();

        if($user_id != ""){
            $delete_target = $user_id;
        }else{

            $delete_target = $_POST['delete_group_user'];
        }

        // 関連削除ユーザ
        foreach ($cgroup_data as $key => $value) {
            if($key == $delete_target) continue;
            $after_cgroup_data[$key] = $value;
        }
        
        // 指定ユーザーの関連削除
        $res = $this->DeleteTargetUserConnectGroup($delete_target);     //ユーザー情報更新
        $this->ChangeConnectionGroupCount($after_cgroup_data,$group_id);     // 関連人数変更

        return $res;
    }

    /****************************************************
	 **  ユーザーのグループ情報削除
	 ******************************************************/
    function DeleteTargetUserConnectGroup($delete_target) {

        // $res = update_user_meta($delete_target,'connect_group',"");        // 該当のグループ以外も削除されてしまうので除外
        $res = update_user_meta($delete_target,'connect_group_disp',"");   // 
        return $res;
    }

    /****************************************************
	 **  関連へ追加
     // $cgroup_data:    関連グループの情報
	 ******************************************************/
    public function addConnectGroup($cgroup_data,$group_id,$user_id){
        
        $cgroup_data[$user_id]["relationship_data"] = "";
        $cgroup_data[$user_id]["add_memo"] = "";
        // ユーザーに関連情報追加
        $group_d = $this->GetConnectionGroupDetail($group_id);
        $user_connect_data = $this->GetUserConnectGroupData($user_id,$group_id); //ユーザーの関連データ取得

        // 登録済みはスキップ
        if($user_connect_data['registed_flg']) return;

        // 新規追加グループ
        if($user_connect_data['old_connect_group'][0] == ""){
            $user_connect_data['old_connect_group'][0] = $group_id;
            $user_connect_data['old_connect_disp'][0] = $group_d[0]->post_title;
        }else{
            $user_connect_data['old_connect_group'][] = $group_id;
            $user_connect_data['old_connect_disp'][] = $group_d[0]->post_title;

        }
        
        // json化
        $user_connect_data['old_connect_group'] = json_encode($user_connect_data['old_connect_group']);
        $user_connect_data['old_connect_disp'] = json_encode($user_connect_data['old_connect_disp'], JSON_UNESCAPED_UNICODE);

        // 関連データアップデート
        $res = update_user_meta($user_id,'connect_group',$user_connect_data['old_connect_group']);        // ユーザーに関連番号付与
        $res = update_user_meta($user_id,'connect_group_disp',$user_connect_data['old_connect_disp']);   // ユーザーに関連名付与


        // アップデート
        $json_list = json_encode($cgroup_data, JSON_UNESCAPED_UNICODE);

        $res = update_field('acf_connection_list', $json_list,$group_id);
        $this->ChangeConnectionGroupCount($cgroup_data,$group_id); //人数変更
        

        return $res;
    }

    /****************************************************
	 **  関連グループテーブルデータ作成
	 ******************************************************/
    public function MakeCGroupData(){
        $cgroup_data = $this->GetConnectionGroup();

        $cgroup = array(); 
        foreach ($cgroup_data as $key => $value) {
    
            $group_top_name = get_field("acf_connection_top_name",$value->ID); //代表者名;
            if(is_null($group_top_name)) $group_top_name = "";

            if(isset($_GET['group_top'])){

                $top_id = get_field("acf_connection_top",$value->ID);


                // グループデータ取得
                $t_data = json_decode(get_field('acf_connection_list',$value->ID),true);
                // var_dump($t_data);  //削除okd

                // 本人以外でも表示は必要だが、代表設定されている人が所属していない場合は表示しないに変更　250326
                // 該当グループに関連TOPの人間が属しているかチェック
                $check_top = false;
                foreach ($t_data as $t_key => $t_value) {
                    if($t_key == $_GET['group_top']) $check_top = true;
                }
                if(!$check_top) continue;
                // if($_GET['group_top'] != $top_id) continue;      本人以外の状態でも表示　250326
            }
    
            
            $connect_group_name = get_field('acf_connection_top_name',$value->ID);
            $top_id = get_field("acf_connection_top",$value->ID);
            $user_info = get_userdata($top_id);
            $cgroup[] = array(
                'ID' => $value->ID,
                'group_name' => $connect_group_name,
                'group_number' => get_field("acf_connection_count",$value->ID), //関連数
                'group_top' => $user_info->last_name.$user_info->first_name,
                'group_id' => $top_id,  //代表者ID
                
            );
        }

        return $cgroup;
    }

    // /****************************************************
	//  **  関連設定を削除
	//  ******************************************************/
	public function deleteConnectionGroup( $id )
	{

        update_field("acf_connection_is_delete", true, $id); //削除フラグON
        $cgroup_data = json_decode(get_field('acf_connection_list', $id));

        // 関連データに設定されているユーザー分回す
        if($cgroup_data != null){

            foreach ($cgroup_data as $user_id => $value) {
    
                $res = $this->deleteConnectGroup($cgroup_data,$id,$user_id);
    
            }
        }
        

        // update_field("acf_connection_count", 0, $id);   //人数初期化
    }



}









?>