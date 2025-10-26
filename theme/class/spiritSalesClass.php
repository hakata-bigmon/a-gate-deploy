<?php 


class SpiritSalesClass
{

   /****************************************************
	**  販売ページの作成
	******************************************************/
	public function newSalesPage($user_id , $spirit_type_id , $title)
	{
		$wp_query = new WP_Query();
     
        $my_post = array(
            'post_title' => $title,
            'post_type' => 'cpt_sales_page', //カスタム投稿タイプの名称を入れる
            'post_status' => 'publish',
            'post_author' => $user_id,
        );

        $program_id = wp_insert_post($my_post);

        //作成できたので番号をタイプに紐づける
        update_field("acf_pure_spirit_sales_num",$program_id, $spirit_type_id);

        
        return $program_id;

	}


    /****************************************************
	**  販売ページの取得
	******************************************************/
	public function getSalesPage($spirit_type_array , $sales_id)
	{
		$sales_data = array();



        $sales_data["名前"] = $spirit_type_array["title"];
        $sales_data["表示名"] = $spirit_type_array["disp"];

        if($sales_data["表示名"] =="")
        {
            $sales_data["表示名"] = $sales_data["名前"];
        }

        $sales_data["価格"] = $spirit_type_array["price"];


        $img_thumbnail = get_field('acf_sales_page_thumbnail' , $sales_id);

        if($img_thumbnail == "")
        {
            $sales_data["サムネイル"] =  "";
        }
        else{
            $sales_data["サムネイル"] = $img_thumbnail["url"];
        }



        for($i=1;$i<=15;$i++)
        {
            $img = get_field('acf_sales_page_img_' . $i  , $sales_id);

            if($img == "")
            {
                $sales_data["画像" . $i] = "";
            }
            else{
                $sales_data["画像" . $i] = $img["url"];
            }
           
        }

        //画像順番
        $sales_data["画像並び"] = $this->getSalesPageImgSort( $sales_id);


        //URL
        $sales_data["URL"] =  get_field('acf_sales_schedule_another_url' , $sales_id);

        return $sales_data;

	}


    /****************************************************
    **  販売ページの画像順番を取得
    ******************************************************/
    public function getSalesPageImgSort( $sales_id)
    {

        $json_data = get_field('acf_sales_page_img_sort',$sales_id);    //jsonデータ取得

        if($json_data == "")
        {
            $decoded_data = array();
        }
        else{
            $decoded_data = json_decode($json_data, true);  //jsonデータ戻し
        }

        $sort_array = array();

        //現在、配列に入っている
        $not_array = array();

        //まず入っているソート番号の画像の有無を調べていれる
        $sort_count = 1;

        for($i=1;$i<=15;$i++)
        {
            if(isset($decoded_data[$i]))
            {
                $img = get_field('acf_sales_page_img_' . $decoded_data[$i]  , $sales_id);

                if($img != "")
                {
                    $sort_array[ $sort_count ] = $decoded_data[$i];

                    $sort_count++;

                    $not_array[ $decoded_data[$i] ] = $decoded_data[$i];//キーに入れる
                }
            }
        }



        //入っていないものを
        for($i=1;$i<=15;$i++)
        {
            if( !isset( $not_array[ $i ] ) )
            {
                 $img = get_field('acf_sales_page_img_' . $i  , $sales_id);

                 if($img != "")
                 {
                      $sort_array[ $sort_count ] = $i;

                      $sort_count++;
                 }

            }
        }

        return $sort_array;
        
    }


     /****************************************************
    **  販売ページの画像順番を保存
    ******************************************************/
    public function saveSalesPageImgSort( $sales_id , $sort_array)
    {
        $json_data = json_encode($sort_array, JSON_UNESCAPED_UNICODE);

        update_field("acf_sales_page_img_sort",$json_data, $sales_id);
    }


    /****************************************************
    **  キャンセルの際に数を戻す
    ******************************************************/
    public function cancelSalesPage( $sheet_id , $post_array)
    {
         //浄霊タイプ
         $acf_pure_spirit_type = get_field('acf_acf_purespirit_type',$sheet_id);

         //物販のみ
         if(get_field('acf_pure_spirit_type_min',$acf_pure_spirit_type) != SpiritTypeClass::SPIRIT_TYPE_NAME_SALES)
         {
            return;
         }
        
        $admin_status =  get_field('acf_purespirit_status',$sheet_id);//管理者ステータス
        $member_status =  get_field('acf_purespirit_user_status',$sheet_id);//会員ステータス

        //すでにキャンセルの場合は対応しない
        if($admin_status == SpiritUserClass::ADMIN_STATUS_CANCEL)
        {
            return;
        }

        if($member_status == SpiritUserClass::MEMBER_STATUS_CANCEL)
        {
            return;
        }

        //今回キャンセルじゃない場合は対応しない
        if($post_array["acf_purespirit_status"] != SpiritUserClass::ADMIN_STATUS_CANCEL)
        {
            return;
        }

        if($post_array["acf_purespirit_user_status"] != SpiritUserClass::MEMBER_STATUS_CANCEL)
        {
            return;
        }



        //数を戻す
        $acf_previous_quantity = get_field('acf_previous_quantity',$sheet_id);

        //現在の在庫数
        $acf_pure_spirit_stock = get_field('acf_pure_spirit_stock',$acf_pure_spirit_type);
       
        update_field("acf_pure_spirit_stock",$acf_previous_quantity + $acf_pure_spirit_stock, $acf_pure_spirit_type);

    }


    /****************************************************
    **  購入メール
    ******************************************************/
    public function sendSalesMail( $user_id , $post_array , $spiritTypeArray,$post_address_data)
    {

        $users = get_userdata($user_id);

       

        $mail_to = $users->user_email;
        $mail_subject = "A-GATE OFFICIAL STORE ご購入ありがとうございました";
       

        $body = '

            <div style="max-width: 900px;margin-right: auto;margin-left: auto;">

            <h2 style="text-align: center;">ご購入ありがとうございます！</h2>
            <div style="text-align: center;">
                このたびはA-GATE OFFICIAL STOREでお買い物いただき、ありがとうございました。<br>
                商品がお手元に届くまで、こちらのメールは大切に保管してください。<br>
                <br>
                詳細は会員ページよりご確認できます。<br>
                <br>
                お手数ですが、ご確認の上、入力シートのご入力もお願い致します。
            </div>

            <hr>

            <div style="text-align: center;font-size: 24px;font-weight: bold;margin-bottom: 20px;">ご注文内容</div>

             <table style="width:100%; border-collapse: collapse;max-width: 800px;margin-right: auto;margin-left: auto;">
        ';
        
        $total_price = 0;

        foreach($post_array as $key => $value)
        {
            $body .= '<tr style="border-bottom: 1px solid black;border-top: 1px solid black;">';

            //商品自体のデータ
            $type_data = $spiritTypeArray[ $key ];

            //販売ページデータ
            $saledata = $this->getSalesPage($spiritTypeArray[$key ] , $type_data["sales_page"]);

            if($saledata["サムネイル"] != ""){
                $body .= '<td style="text-align: right;padding-top: 10px;"><img src="'.esc_url($saledata["サムネイル"]).'" alt="'.esc_attr($type_data["title"]).'" style="width: 100px;max-width: 100px"></td>';
            }else{
                $body .= '<td style="text-align: right;padding-top: 10px;"><img src="'.get_template_directory_uri().'/assets/images/noimage.jpg" alt="'.esc_attr($type_data["title"]).'" style="width: 100px;max-width: 100px"></td>';
            }
           

            $body .= '<td style="padding-left: 20px;">' .  esc_html($type_data["title"]) . '<br>';
            $body .=  $value . '点<br>';
            $body .=  '￥' . number_format($type_data["price"] * $value) . '円</td>';
            $body .= '</tr>';

            $total_price += $type_data["price"] * $value;
        }

        $body .= '</table>';

        $body .= '<div style="text-align:right;margin-top: 30px;margin-bottom: 30px;max-width: 850px;font-size: 24px;color: black;">';
        $body .= '<div style="">合計　' . number_format($total_price) . '円</div>';


        $body .= '<hr>';

        $body .= '<div style="text-align: center;font-size: 20px;margin-bottom: 20px;margin-top: 30px;">郵送先</div>';

        $body .= '<div style="margin-left: 50px;text-align: left;margin-top: 20px;margin-bottom: 20px;max-width: 850px;font-size: 16px;color: black;">';
        $body .= "【" . $post_address_data["郵送先名前"] . "】 様" . '<br><br>';
        $body .= "〒" .$post_address_data["郵送先郵便番号"] . '<br>';
        $body .= $post_address_data["郵送先住所1"];
        $body .= $post_address_data["郵送先住所2"] . '<br>';
        
        $body .= '</div>';


        $body .= '</div>';

        $body .= '<hr>';

        $body .= '<div style="text-align: center;font-size: 24px;font-weight: bold;margin-bottom: 20px;margin-top: 30px;">A-GATE OFFICIAL STORE</div>';

        $body .= '</div>';
       //  echo $body;


        // 送信者名とアドレスをセット（ここが重要）
        $mail_headers  = "From: A-GATE OFFICIAL STORE <info@a-gate-kanri.com>\r\n";
        $mail_headers .= "Content-Type: text/html; charset=UTF-8\r\n";

        wp_mail($mail_to, $mail_subject, $body, $mail_headers);




    }
}









?>