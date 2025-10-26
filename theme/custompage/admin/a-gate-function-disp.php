<?php 

/****************************************************
 **   表示絞り込みチェック
 ******************************************************/
function checkSqueeze($check, $disp_squeeze)
{
    if (isset($_GET['introduction_member']))
        return true;   //紹介者選択時はすべて表示
    if ($disp_squeeze == null)
        return true;  //設定なければ全表示
    else if ($disp_squeeze == "-")
        return false;

    // 検索用
    $search_check = false;
    foreach ($disp_squeeze as $key => $value) {
        if ($check == $value)
            $search_check = true;
    }

    return $search_check;

}

/****************************************************
 **   検索絞り込み保存
 ******************************************************/
function SearchSqueeze(){
    if(isset($_POST['search-squeeze'])){
        
        if(isset($_POST['search-squeeze-content'])){

            $sq_data = json_encode($_POST['search-squeeze-content']);
        }else{
            $sq_data = "-";
        }
        return update_user_meta(get_current_user_id(),'search_squeeze',$sq_data);   // ユーザーに関連名付与
    }
}


/****************************************************
 **   データテーブルで検索しない奴出力
$current_user_disp_squeeze  :表示設定
$current_user_search_squeeze:検索設定
$sqeeze_name                :
$data_name                  :
 ******************************************************/
function JSDataTableNoSearch($current_user_disp_squeeze,$current_user_search_squeeze,$sqeeze_name,$data_name){
    if(checkSqueeze($sqeeze_name,$current_user_disp_squeeze)){
        echo "{ data: \"$data_name\"";
                // 検索対象外の処理
                if($current_user_disp_squeeze != null && !property_exists($current_user_search_squeeze, $data_name)){
                    echo ",  searchable: false";
                }
        echo "},";
    }
}
function JSDataTableNoSearchSameName($current_user_disp_squeeze,$current_user_search_squeeze,$A,$B){
    if(checkSqueeze($A,$current_user_disp_squeeze)){
        echo "{ data: \"$B\"";
                // 検索対象外の処理
                if($current_user_disp_squeeze != null && !property_exists($current_user_search_squeeze, $A)){
                    echo ",  searchable: false";
                }
        echo "},";
    }
}

/****************************************************
 **   表示絞り込み保存
 ******************************************************/
function DispSqueeze(){

    if(isset($_POST['disp-squeeze'])){
        $sq_data = json_encode($_POST['disp-squeeze-content']);
        return update_user_meta(get_current_user_id(),'disp_squeeze',$sq_data);   // ユーザーに関連名付与
    }
}

?>