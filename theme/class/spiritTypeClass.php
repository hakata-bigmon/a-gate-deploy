<?php 


class SpiritTypeClass
{

    public const ENCRYPT_KEY = "d";


    //大カテゴリー
    public const SPIRIT_TYPE_NAME_SPIRIT = 1;//リモート依頼
    public const SPIRIT_TYPE_NAME_APPRAISAL = 2;//鑑定
	public const SPIRIT_TYPE_NAME_ELSE = 99;//その他
    public const SPIRIT_TYPE_NAME_SALES = 4;//物販
    public const SPIRIT_TYPE_NAME_SODAN = 5;//遠隔・相談
    public const SPIRIT_TYPE_NAME_HEALING = 6;//ヒーリング
    public const SPIRIT_TYPE_NAME_DAY = 3;//日程確定


   

    /****************************************************
	 **  銀行情報を取得
	 ******************************************************/
	public function getSpiritBankInfo()
	{
		return array(
			"bank_name" => "住信SBIネット銀行",
			"bank_branch" => "法人第一支店",
			"bank_type" => "普通",
			"bank_number" => "1399898",
			"bank_acount_name" => "カ）エーゲート"
		);
	}

    /****************************************************
	 **  カテゴリーネームを取得
	 ******************************************************/
	public function getSpiritTypeName($category_num)
	{

        if($category_num == SpiritTypeClass::SPIRIT_TYPE_NAME_SPIRIT)
        {
            return "浄霊・施術管理";
        }
        else if($category_num == SpiritTypeClass::SPIRIT_TYPE_NAME_APPRAISAL)
        {
            return "鑑定";
        }
        else if($category_num == SpiritTypeClass::SPIRIT_TYPE_NAME_DAY)
        {
            return "日程確定依頼";
        }
        else if($category_num == SpiritTypeClass::SPIRIT_TYPE_NAME_SALES)
        {
            return "物販";
        }
         else if($category_num == SpiritTypeClass::SPIRIT_TYPE_NAME_SODAN)
        {
            return "遠隔・相談";
        }
        else if($category_num == SpiritTypeClass::SPIRIT_TYPE_NAME_HEALING)
        {
            return "ヒーリング";
        }
        else if($category_num  == SpiritTypeClass::SPIRIT_TYPE_NAME_ELSE)
        {
            return "その他";
        }


        return "未設定";
    }


    /****************************************************
	 **  カテゴリーネームを取得
	 ******************************************************/
	public function getSpiritTypeNameArary()
	{

        
        $group_id = '68';
        $fields = acf_get_fields($group_id);

        $type_array = array();

        foreach ($fields as $field => $data) {

            if($data["name"] == "acf_pure_spirit_type_min" )
            {
                foreach ($data["choices"] as $choices_field => $choices_data) {
                    $type_array[ $choices_field ] = $choices_data;
                }
            }

        }

        return $type_array;
        
    }

	/****************************************************
	 **  カテゴリータイプを取得
	 ******************************************************/
	public function getSpiritType($stock_only = false)
	{
		$wp_query = new WP_Query();

        $param = array(
            'posts_per_page' => '-1', //表示件数。-1なら全件表示
            'post_type' => 'cpt_pure_spirit_type', //カスタム投稿タイプの名称を入れる
            'post_status' => 'publish', //取得するステータス。publishなら一般公開のもののみ
            'orderby' => 'ID', //ID順に並び替え
            'order' => 'DESC'
        );

        $wp_query->query($param);


		//ソート用
        $sort_array = "";

        $no_data_num = 100000;

		//データを入れる
        if ($wp_query->have_posts()):

            while ($wp_query->have_posts()):
                $wp_query->the_post();


                if ($sort_array == "") {
                    $sort_array = array();
                }


                //ストックがないものは表示しない
                if($stock_only)
                {

                    if(get_field('acf_pure_spirit_stock') == 0 || get_field('acf_pure_spirit_stock') == "")
                    {
                        continue;
                    }
                }

                $type = get_field('acf_pure_spirit_type_min');

              // echo  implode(', ', $type);

                $fields = get_field_object('acf_pure_spirit_type_min');

              

                if (!isset($sort_array[$type])) {
                    $sort_array[$type] = array();
                }

                $num = get_field('acf_pure_spirit_define_num');

                if($num == "")
                {
                    $num = $no_data_num;

                    $no_data_num++;
                }

                $sort_array[$type][$num]["ID"] = get_the_ID();
                $sort_array[$type][$num]["title"] = get_field('acf_pure_spirit_title');
                $sort_array[$type][$num]["disp"] = get_field('acf_pure_spirit_disp_title');

                if($sort_array[$type][$num]["disp"] == "")
                {
                    $sort_array[$type][$num]["disp"] =  $sort_array[$type][$num]["title"];
                }

                $sort_array[$type][$num]["placeOn"] = get_field('acf_pure_spirit_place_on');
                $sort_array[$type][$num]["group"] = get_field('acf_pure_spirit_type_min');
                $sort_array[$type][$num]["price"] = get_field('acf_pure_spirit_price');
                $sort_array[$type][$num]["max"] = get_field('acf_pure_spirit_max');
                $sort_array[$type][$num]["min"] = get_field('acf_pure_spirit_min');
                $sort_array[$type][$num]["stock"] = get_field('acf_pure_spirit_stock');
                $sort_array[$type][$num]["sales_category"] = get_field('acf_pure_spirit_sales_category');
                $sort_array[$type][$num]["sales_page"] = get_field('acf_pure_spirit_sales_num');
                $sort_array[$type][$num]["time"] = get_field('acf_pure_spirit_time');
                $sort_array[$type][$num]["color"] = get_field('acf_pure_spirit_color');
                $sort_array[$type][$num]["arami"] = get_field('acf_pure_arami_sheet');
                $sort_array[$type][$num]["payment_setting"] = get_field('acf_pure_payment_setting');
                $sort_array[$type][$num]["last_check"] = get_field('acf_pure_last_check');

                if($sort_array[$type][$num]["payment_setting"] == "")
                {
                    $sort_array[$type][$num]["payment_setting"] = array();
                }

                if($sort_array[$type][$num]["price"] == "")
                {
                    $sort_array[$type][$num]["price"] = 0;
                }

                if($sort_array[$type][$num]["max"] == "")
                {
                    $sort_array[$type][$num]["max"] = 1;
                }

                if($sort_array[$type][$num]["min"] == "")
                {
                    $sort_array[$type][$num]["min"] = 1;
                }

                if($sort_array[$type][$num]["stock"] == "")
                {
                    $sort_array[$type][$num]["stock"] =0;
                }

                if($sort_array[$type][$num]["time"] == "")
                {
                    $sort_array[$type][$num]["time"] =0;
                }

                if($sort_array[$type][$num]["color"] == "")
                {
                    $sort_array[$type][$num]["color"] = "#EAB2D2";
                }
                
                
            endwhile;
        endif;

        ksort($sort_array);

        foreach ($sort_array as $key => $value) {
            ksort($sort_array[$key]);
        }




        

        return $sort_array;

	}

    /****************************************************
	 **  カテゴリータイプを取得(keyがタイプ番号)
	 ******************************************************/
	public function getSpiritTypeKeyTypeNum()
	{
		$wp_query = new WP_Query();

        $param = array(
            'posts_per_page' => '-1', //表示件数。-1なら全件表示
            'post_type' => 'cpt_pure_spirit_type', //カスタム投稿タイプの名称を入れる
            'post_status' => 'publish', //取得するステータス。publishなら一般公開のもののみ
            'orderby' => 'ID', //ID順に並び替え
            'order' => 'DESC'
        );

        $wp_query->query($param);


		//ソート用
        $sort_array = array();

		//データを入れる
        if ($wp_query->have_posts()):

            while ($wp_query->have_posts()):
                $wp_query->the_post();

                $num = get_the_ID();

                if($num == "")
                {
                    $sort_array[$num] = array();
                }

                $sort_array[$num]["ID"] = get_the_ID();
                $sort_array[$num]["title"] = get_field('acf_pure_spirit_title');
                $sort_array[$num]["disp"] = get_field('acf_pure_spirit_disp_title');
                $sort_array[$num]["group"] = get_field('acf_pure_spirit_type_min');

                $sort_array[$num]["placeOn"] = get_field('acf_pure_spirit_place_on');

                $sort_array[$num]["price"] = get_field('acf_pure_spirit_price');
                $sort_array[$num]["max"] = get_field('acf_pure_spirit_max');
                $sort_array[$num]["min"] = get_field('acf_pure_spirit_min');
                $sort_array[$num]["stock"] = get_field('acf_pure_spirit_stock');
                $sort_array[$num]["sales_category"] = get_field('acf_pure_spirit_sales_category');
                $sort_array[$num]["sales_page"] = get_field('acf_pure_spirit_sales_num');
                $sort_array[$num]["time"] = get_field('acf_pure_spirit_time');
                $sort_array[$num]["color"] = get_field('acf_pure_spirit_color');
                $sort_array[$num]["arami"] = get_field('acf_pure_arami_sheet');
                $sort_array[$num]["payment_setting"] = get_field('acf_pure_payment_setting');
                $sort_array[$num]["last_check"] = get_field('acf_pure_last_check');

                if($sort_array[$num]["payment_setting"] == "")
                {
                    $sort_array[$num]["payment_setting"] = array();
                }

                if($sort_array[$num]["price"] == "")
                {
                    $sort_array[$num]["price"] = 0;
                }

                if($sort_array[$num]["max"] == "")
                {
                    $sort_array[$num]["max"] = 1;
                }

                if($sort_array[$num]["min"] == "")
                {
                    $sort_array[$num]["min"] = 1;
                }

                if($sort_array[$num]["stock"] == "")
                {
                    $sort_array[$num]["stock"] = 0;
                }
                if($sort_array[$num]["time"] == "")
                {
                    $sort_array[$num]["time"] = 0;
                }

                if($sort_array[$num]["color"] == "")
                {
                    $sort_array[$num]["color"] = "#EAB2D2";
                }
            endwhile;
        endif;
       
        return $sort_array;

	}


    /****************************************************
	 **  カテゴリーの解放条件を調べて返す
	 ******************************************************/
	public function getSpiritTypeReleaseCondition($category_num,$user_complete_array)
	{

        //守護霊メッセージ
        if($category_num == 87)
        {
            //完全浄霊がなかったら表示しない
            if(!isset($user_complete_array[121]))
            {
                return false;
            }
        }

       //守指導霊（神様）メッセージプラス
       if($category_num == 7639)
       {
           //完全浄霊がなかったら表示しない
           if(!isset($user_complete_array[121]))
           {
               return false;
           }
       }

        //アカシックレコード
        if($category_num == 86)
        {
            //完全浄霊がなかったら表示しない
            if(!isset($user_complete_array[121]))
            {
                return false;
            }
        }

        //コスモヒーリング伝授 第一フェーズ
        if($category_num == 88)
        {
            //完全浄霊がなかったら表示しない
            if(!isset($user_complete_array[121]))
            {
                return false;
            }
        }

        //コスモヒーリング伝授 第二フェーズ
        if($category_num == 89)
        {
            //コスモヒーリング伝授 第一フェーズがなかったら表示しない
            if(!isset($user_complete_array[88]))
            {
                return false;
            }
        }


          //コスモヒーリング伝授 第三フェーズ
          if($category_num == 90)
          {
              //コスモヒーリング伝授 第二フェーズがなかったら表示しない
              if(!isset($user_complete_array[89]))
              {
                  return false;
              }
          }

          //ヒーリングマスター１
          if($category_num == 9464)
          {
              //コスモヒーリング伝授 第三フェーズがなかったら表示しない
              if(!isset($user_complete_array[90]))
              {
                  return false;
              }
          }

           //ヒーリングマスター２
           if($category_num == 9465)
           {
               //ヒーリングマスター１がなかったら表示しない
               if(!isset($user_complete_array[9464]))
               {
                   return false;
               }
           }
 
            //ヒーリングマスター３      
            if($category_num == 9466)
            {
                //ヒーリングマスター２がなかったら表示しない
                if(!isset($user_complete_array[9465]))
                {
                    return false;
                }
            }

            //ヒーリングマスター４
            if($category_num == 9467)
            {
                //ヒーリングマスター３がなかったら表示しない
                if(!isset($user_complete_array[9466]))
                {
                    return false;
                }
            }

            //ヒーリングマスター５
            if($category_num == 9468)
            {
                //ヒーリングマスター４がなかったら表示しない
                if(!isset($user_complete_array[9467]))
                {
                    return false;
                }
            }

             //グランドマスター
             if($category_num == 9469)
             {
                 //ヒーリングマスター５がなかったら表示しない
                 if(!isset($user_complete_array[9468]))
                 {
                     return false;
                 }
             }
          //霊視鑑定
          if($category_num == 78)
          {
              //完全浄霊があったら表示しない
              if(isset($user_complete_array[121]))
              {
                  return false;
              }
          }


           //宇宙繋ぎ
           if($category_num == 5487)
           {
               //神繋ぎライトがないと表示しない
               if(!isset($user_complete_array[97]))
               {
                   return false;
               }
           }
        return true;

    }


    /****************************************************
	 **  カテゴリータイプの価格を取得
	 ******************************************************/
	public function getSpiritTypePrice()
	{
		$wp_query = new WP_Query();

        $param = array(
            'posts_per_page' => '-1', //表示件数。-1なら全件表示
            'post_type' => 'cpt_pure_spirit_type', //カスタム投稿タイプの名称を入れる
            'post_status' => 'publish', //取得するステータス。publishなら一般公開のもののみ
            'orderby' => 'ID', //ID順に並び替え
            'order' => 'DESC'
        );

        $wp_query->query($param);


		//ソート用
        $sort_array = array();

		//データを入れる
        if ($wp_query->have_posts()):

            while ($wp_query->have_posts()):
                $wp_query->the_post();

                $num = get_the_ID();

                $sort_array[$num] = get_field('acf_pure_spirit_price');
                
            endwhile;
        endif;
       
        return $sort_array;

	}
    /****************************************************
	 **  暗号化関数の作成
	 ******************************************************/
    function encrypt($data) {
       
        return $data;
    }

    /****************************************************
	 **  復号化関数の作成
	 ******************************************************/
    function decrypt($data) {
        
        return $data;
    }


     /****************************************************
	 **  並び順を更新
	 ******************************************************/
	public function saveSpiritTypeSort( $sortArray , $type_num )
	{
        $ofset = ($type_num - 1) * 100;

         foreach ($sortArray as $key => $value) {
               update_field("acf_pure_spirit_define_num", $key + 1 + $ofset, $value);
         }

    }


    /****************************************************
	 **  価格・個数を更新
	 ******************************************************/
	public function saveSpiritTypePrice( $id , $post_data )
	{
        if(isset($post_data["admin-exorcism-price-" .$id ]))
        {
            update_field("acf_pure_spirit_price", $post_data["admin-exorcism-price-" .$id ], $id);
        }

        if(isset($post_data["admin-exorcism-max-" .$id ]))
        {
            update_field("acf_pure_spirit_max", $post_data["admin-exorcism-max-" .$id ], $id);
        }

        if(isset($post_data["admin-exorcism-min-" .$id ]))
        {
            update_field("acf_pure_spirit_min", $post_data["admin-exorcism-min-" .$id ], $id);
        }

        if(isset($post_data["admin-exorcism-stock-" .$id ]))
        {
            update_field("acf_pure_spirit_stock", $post_data["admin-exorcism-stock-" .$id ], $id);
        }

        if(isset($post_data["admin-exorcism-sales-category-" .$id ]))
        {
            update_field("acf_pure_spirit_sales_category", $post_data["admin-exorcism-sales-category-" .$id ], $id);
        }

        if(isset($post_data["admin-exorcism-sprit-category-" .$id ]))
        {
            update_field("acf_pure_spirit_type_min", $post_data["admin-exorcism-sprit-category-" .$id ], $id);
        }

        if(isset($post_data["admin-exorcism-time-" .$id ]))
        {
            update_field("acf_pure_spirit_time", $post_data["admin-exorcism-time-" .$id ], $id);
        }

        if(isset($post_data["acf_pure_last_check-" .$id ]))
        {
            update_field("acf_pure_last_check", "1", $id);
        }
        else
        {
            update_field("acf_pure_last_check", "", $id);
        }
    }


    /****************************************************
	 **  物販情報を全て取得
	 ******************************************************/
	public function getSpiritSalesData( $rand)
	{
        //全データを取得
        $spirit_array = $this->getSpiritType();


        //販売は配列
        $sales_array = array();


        if($rand != 0)
        {
            // キーをランダムに5つ取得
            $randomKeys = array_rand($spirit_array[ SpiritTypeClass::SPIRIT_TYPE_NAME_SALES ], $rand );


            foreach ($randomKeys as $key => $value) 
            {
                array_push( $sales_array , $spirit_array[ SpiritTypeClass::SPIRIT_TYPE_NAME_SALES ][ $value ]);
            }
        }else{
            foreach ($spirit_array[ SpiritTypeClass::SPIRIT_TYPE_NAME_SALES ] as $key => $value) 
            {
                array_push( $sales_array , $value);
            }
        }


        return $sales_array;
        

        
    }


    /****************************************************
	 **  物販情報の在庫数を減らす
	 ******************************************************/
	public function reduceSpiritSalesStock( $type_id , $sales)
	{
        $stock = get_field("acf_pure_spirit_stock", $type_id);

        $stock -= $sales;

        update_field("acf_pure_spirit_stock", $stock, $type_id);

        
    }

    /****************************************************
	 **  物販情報の在庫数があるかどうかの確認
	 ******************************************************/
	public function checkSpiritSalesStock( $type_id , $sales)
	{
        //物販のみ
        if(get_field('acf_pure_spirit_type_min',$type_id) != SpiritTypeClass::SPIRIT_TYPE_NAME_SALES)
        {
            return true;
        }
        $stock = get_field("acf_pure_spirit_stock", $type_id);

        $stock -= $sales;

        if($stock >= 0)
        {
            return true;
        }
        else
        {
            return false;
        }

        
    }

    /****************************************************
	 **  カラーの保存
	 ******************************************************/
	public function saveSpiritTypeColor( $type_id , $color)
	{
        
        update_field("acf_pure_spirit_color", $color, $type_id);
        
    }
}









?>