<?php 


require_once (dirname(__FILE__)."/spiritSheetClass.php");
require_once (dirname(__FILE__)."/spiritTypeClass.php");
require_once (dirname(__FILE__)."/spiritScheduleClass.php");

class SpiritUserClass
{

	//グローバル変数
	public $payment_type_field;
	public $spirit_type_array;
	public $spirit_status_array;
	public $spirit_uSerStatus_array;


	//初期化
	public function __construct()
	{
		$payment_type_field = array();
		$this->group_id = '33';//対象者
        $this->fields = acf_get_fields($this->group_id);

		foreach($this->fields as $field)
		{
			if($field["name"] == "acf_previous_payment_type")
			{
				$this->payment_type_field = $field["choices"];
			}
		}


		$spiritSheet = new SpiritSheetClass(); //管理データ
		$spiritType = new SpiritTypeClass(); //管理データ

		$this->spirit_type_array = $spiritType->getSpiritTypeKeyTypeNum();//浄霊タイプ取得
		
		// 自分が申込者
		$this->spirit_status_array = $spiritSheet->getSpiritAdminStatusID('cpt_spirit_status');//管理ステータス
		$this->spirit_uSerStatus_array = $spiritSheet->getSpiritAdminStatusID('cpt_spirit_usestatus');//会員ステータス

		//var_dump($this->payment_type_field);
	}

	//TOPの履歴数
	const SPIRIT_TOP_HISTORY_COMPLETE = 5;

	//入金締め切り
	const SPIRIT_PAYMENT_DEADLINE = " +1 week";

	//会員ステータス
	public const MEMBER_STATUS_CREATE = 7830;//作成中
    public const MEMBER_STATUS_WATING_PAYMENT = 7820;//入金待ち
	public const MEMBER_STATUS_NOT_INFOMATION = 7821;//情報未入力
	public const MEMBER_STATUS_CONFIRMATION_INFOMATION = 7822;//必要事項入力確認待ち
	public const MEMBER_STATUS_RE_INFOMATION = 7823;//必要事項再入力
	public const MEMBER_STATUS_CURRNTLY_REQUESTING = 7824;//浄霊・依頼中（先生確認）
	public const MEMBER_STATUS_CONFIRMED = 7825;//日程確定
	public const MEMBER_STATUS_COMPLETE = 7826;//完了
	public const MEMBER_STATUS_CANCEL = 7827;//ｷｬﾝｾﾙ
	public const MEMBER_STATUS_SHIPPING_PREPARATION = 8045;//発送準備
	public const MEMBER_STATUS_SHIPMENT_COMPLETE = 8046;//発送完了
	public const MEMBER_STATUS_SHIPMENT_CONFIRMATION = 8049;//発送確認中
	public const MEMBER_STATUS_LAST_CONFIRMATION = 9657;//最終確認

	//管理者ステータス
	public const ADMIN_STATUS_COMPLETE = 43;//完了
	public const ADMIN_STATUS_NOT_PHOTO = 45;//写真未送付
	public const ADMIN_STATUS_CANCEL = 44;//ｷｬﾝｾﾙ
	public const ADMIN_STATUS_NOT_DATA = 46;//情報不備
	public const ADMIN_STATUS_CURRNTLY_REQUESTING = 48;//依頼前確認
	public const ADMIN_STATUS_CURRNTLY_TEACH = 47;//先生確認
	public const ADMIN_STATUS_CURRNTLY_KOBATASHI = 48;//小林確認
	public const ADMIN_STATUS_CURRNTLY_NAKURA = 6407;//杏子確認
	public const ADMIN_STATUS_EARLY = 6697;//急ぎ
    public const ADMIN_STATUS_WATING_PAYMENT = 7814;//入金待ち
	public const ADMIN_STATUS_CONFIRMATION_INFOMATION = 7815;//確認待ち
	public const ADMIN_STATUS_HAND_DELIVERY = 8052;//手渡し
	public const ADMIN_STATUS_NOT_SEND= 8053;//未送付
	public const ADMIN_STATUS_SHIPPING_PREPARATION = 8054;//送付済

	
	//支払いタイプ
	public const PAYMENT_TYPE_NOT_SET = 0;//未設定
	public const PAYMENT_TYPE_CREDIT = 1;//クレジット
	public const PAYMENT_TYPE_TRANSFER = 2;//振込
	public const PAYMENT_TYPE_E_MONEY = 3;//電子マネー決済
	public const PAYMENT_TYPE_CONVENIENCE = 4;//コンビニ決済
	public const PAYMENT_TYPE_CASH = 5;//現金
	public const PAYMENT_TYPE_TRANSIT = 6;//交通系決済
	public const PAYMENT_TYPE_FREE = 7;//無料
	public const PAYMENT_TYPE_OTHER = 99;//その他



	//メールタイプ(支払いタイプとは別)
	public const MAIL_TYPE_COMPLETE_REPORT = 101;//施術完了報告
	public const MAIL_TYPE_SHEET_CONFIRM_REPORT = 102;//シート確認完了報告
	public const MAIL_TYPE_SHEET_RETURN_REPORT = 103;//シート再提出報告
	public const MAIL_TYPE_PAYMENT_CONFIRM_REPORT = 104;//入金確認完了報告
	public const MAIL_TYPE_SCHEDULE_DECISION_REPORT = 105;//施術日決定報告
	public const MAIL_TYPE_SALES_SEND_REPORT = 106;//物販発送完了報告


	//霊種類
	public const SPIRIT_KIND_SENZO = 0;//先祖霊
	public const SPIRIT_KIND_JIBAKU = 1;//地縛霊
	public const SPIRIT_KIND_KOUJIGOKU = 2;//高地獄霊
	public const SPIRIT_KIND_DOUBUTSU = 3;//動物霊
	public const SPIRIT_KIND_MAKAI = 4;//魔界霊

	public const SPIRIT_KIND_MAX = 5;//最大値

	
	/****************************************************
	**  支払いタイプの番号からラベル文字列を返す
	******************************************************/
    public function getPaymentTypeLabel($type) {
        if(isset($this->payment_type_field[$type])) {
            return $this->payment_type_field[$type];
        }
        return '';
    }



	/****************************************************
	**  霊種類の文字列を返す
	******************************************************/
    public function getSpiritTypeLabel($type) {
        
		switch($type) {
			case self::SPIRIT_KIND_SENZO:
				return '先祖霊';
			case self::SPIRIT_KIND_JIBAKU:
				return '地縛霊';
			case self::SPIRIT_KIND_KOUJIGOKU:
				return '高地獄霊';
			case self::SPIRIT_KIND_DOUBUTSU:
				return '動物霊';
			case self::SPIRIT_KIND_MAKAI:
				return '魔界霊';
			default:
				return '未設定';
		}

    }



	/****************************************************
	**  ユーザーのアカウント情報を取得
	******************************************************/
	public function getUserAcountData($user_id)
	{
		
		//ユーザーメタが存在しているかどうか(
		$users = get_userdata($user_id);

		$acount_array = array();


		$acount_array["必須"] = 0;
		$acount_array["必須項目"] = array();


		$acount_array["ID"] = $user_id;//ID
		$acount_array["ユニークID"] = get_user_meta($user_id, 'user_unique_id', true);//ユニークID
		$acount_array["メール"] = $users->user_email;
		//認証
		$acount_array["認証"] = get_user_meta($user_id, 'user_date_complete', true);//認証


		$acount_array["非表示"] = get_user_meta($user_id, 'is_delete', true);//非表示

		if($acount_array["メール"] == "")
		{
			$acount_array["必須"]++;
			array_push($acount_array["必須項目"] , "メールアドレス" );
		}


		$acount_array["苗字"] = get_user_meta($user_id, 'last_name', true);//苗字
		$acount_array["名前"] = get_user_meta($user_id, 'first_name', true);//名前
		$acount_array["フル名前"] = $acount_array["苗字"] . " " .$acount_array["名前"];//フル

		if($acount_array["苗字"] == "" || $acount_array["名前"] == "")
		{
			$acount_array["必須"]++;
			array_push($acount_array["必須項目"] , "名前" );
		}

		$acount_array["ミョウジ"] = get_user_meta($user_id, 'last_name_kana', true);//ミョウジ
		$acount_array["ナマエ"] = get_user_meta($user_id, 'first_name_kana', true);//ナマエ
		$acount_array["フルナマエ"] = $acount_array["ミョウジ"] . " " .$acount_array["ナマエ"];//フル

		if($acount_array["ミョウジ"] == "" || $acount_array["ナマエ"] == "")
		{
			$acount_array["必須"]++;
			array_push($acount_array["必須項目"] , "ヨミカタ" );
		}

		$acount_array["性別値"] = get_user_meta($user_id, 'sex', true);//性別
		if($acount_array["性別値"]  =="")
		{
			$acount_array["性別値"] = "U"; //未設定でまとめる
		}

		$acount_array["性別"] = "未設定";
		$acount_array["性別C"] = "未設定";

		if($acount_array["性別値"] == "M")
		{
			$acount_array["性別"] = "男性";
			$acount_array["性別C"] = "<font color='blue'>男性</font>";
		}
		else if($acount_array["性別値"] == "W")
		{
			$acount_array["性別"] = "女性";
			$acount_array["性別C"] = "<font color='red'>男性</font>";
		}
		else if($acount_array["性別値"] == "U")
		{
			$acount_array["性別"] = "未設定";
		}
		 

		$acount_array["電話番号1"] = get_user_meta($user_id, 'billing_phone', true);//電話番号
		$acount_array["電話番号2"] = get_user_meta($user_id, 'billing_phone2', true);//電話番号
		$acount_array["電話番号3"] = get_user_meta($user_id, 'billing_phone3', true);//電話番号

		$acount_array["電話番号"] = "";

		if($acount_array["電話番号1"] != "")//最初の番号があると仮定して
		{
			$acount_array["電話番号"] = $acount_array["電話番号1"] . "-" . $acount_array["電話番号2"] ."-" .$acount_array["電話番号3"];
		}

		if($acount_array["電話番号1"] == "" || $acount_array["電話番号2"] == "" || $acount_array["電話番号3"] == "")
		{
			$acount_array["必須"]++;
			array_push($acount_array["必須項目"] , "電話番号" );
		}


		$acount_array["誕生日年"] = get_user_meta($user_id, 'born_year', true);//誕生日年
		$acount_array["誕生日月"] = get_user_meta($user_id, 'born_month', true);//誕生日月
		$acount_array["誕生日日"] = get_user_meta($user_id, 'born_day', true);//誕生日日
		$acount_array["誕生日"] = "";
		$acount_array["誕生日年月日"] = "";
		$acount_array["年齢"] = "";

		if($acount_array["誕生日年"] != "")//最初の年があると仮定して
		{
			$acount_array["誕生日"] = $acount_array["誕生日年"] ."-" .$acount_array["誕生日月"] . "-" . $acount_array["誕生日日"];

			$date_time = new DateTime($acount_array["誕生日"]);

			$acount_array["誕生日年月日"] = $date_time->format('Y年n月j日');

			//年齢
			$birth = new DateTime($acount_array["誕生日"]);
			$today = new DateTime('now');
			$acount_array["年齢"]  = $birth->diff($today)->y;
		}


		if($acount_array["誕生日年"] == "" || $acount_array["誕生日月"] == "" || $acount_array["誕生日日"] == "")
		{
			$acount_array["必須"]++;
			array_push($acount_array["必須項目"] , "誕生日" );
		}




		$acount_array["届け出日年"] = get_user_meta($user_id, 'report_born_year', true);//届け出日年
		$acount_array["届け出日月"] = get_user_meta($user_id, 'report_born_month', true);//届け出日月
		$acount_array["届け出日日"] = get_user_meta($user_id, 'report_born_day', true);//届け出日日
		$acount_array["届け出日"] = "";
		$acount_array["届け出日年月日"] = "";

		
		if($acount_array["届け出日年"] != "" && $acount_array["届け出日月"] <= 12 && $acount_array["届け出日日"] <= 31)//最初の年があると仮定して
		{
			$acount_array["届け出日"] = $acount_array["届け出日年"] ."-" .$acount_array["届け出日月"] . "-" . $acount_array["届け出日日"];

			if($acount_array["届け出日"] != "")
			{
				$date_time = new DateTime($acount_array["届け出日"]);

				$acount_array["届け出日年月日"] = $date_time->format('Y年n月j日');
			}
		}


		$acount_array["郵便番号"] = get_user_meta($user_id, 'billing_postcode', true);//郵便番号
		$acount_array["郵便番号ハイフン"] = "";
		

		if($acount_array["郵便番号"] != "")
		{
			 // 郵便番号の数字以外を取り除く（誤入力などの対策）
			$postalCode = preg_replace('/[^0-9]/', '', $acount_array["郵便番号"]);
    
			// 7桁の場合だけハイフンを入れる
			if (strlen($postalCode) === 7) {
				$acount_array["郵便番号ハイフン"] =  substr($postalCode, 0, 3) . '-' . substr($postalCode, 3, 4);

			} else {
				$acount_array["郵便番号ハイフン"] = $acount_array["郵便番号"];
			}
		}

		if($acount_array["郵便番号"] == "")
		{
			$acount_array["必須"]++;
			array_push($acount_array["必須項目"] , "郵便番号" );
		}

		$acount_array["住所1"] = get_user_meta($user_id, 'billing_city', true);//住所1
		$acount_array["住所2"] = get_user_meta($user_id, 'billing_address_1', true);//住所2
		$acount_array["住所"] = $acount_array["住所1"];

		if($acount_array["住所2"] != "")
		{
			$acount_array["住所"] .= "" .$acount_array["住所2"];
		}

		if($acount_array["住所"] == "")
		{
			$acount_array["必須"]++;
			array_push($acount_array["必須項目"] , "住所" );
		}


		$acount_array["郵送先郵便番号"] = get_user_meta($user_id, 'post_billing_postcode', true);//郵便番号
		$acount_array["郵送先郵便番号ハイフン"] = "";
		

		if($acount_array["郵送先郵便番号"] != "")
		{
			 // 郵便番号の数字以外を取り除く（誤入力などの対策）
			$postalCode = preg_replace('/[^0-9]/', '', $acount_array["郵送先郵便番号"]);
    
			// 7桁の場合だけハイフンを入れる
			if (strlen($postalCode) === 7) {
				$acount_array["郵送先郵便番号ハイフン"] =  substr($postalCode, 0, 3) . '-' . substr($postalCode, 3, 4);

			} else {
				$acount_array["郵送先郵便番号ハイフン"] = $acount_array["郵送先郵便番号"];
			}
		}



		$acount_array["郵送先住所1"] = get_user_meta($user_id, 'post_billing_city', true);//住所1
		$acount_array["郵送先住所2"] = get_user_meta($user_id, 'post_billing_address_1', true);//住所2
		$acount_array["郵送先住所"] = $acount_array["郵送先住所1"];

		if($acount_array["郵送先住所2"] != "")
		{
			$acount_array["郵送先住所"] .= "" .$acount_array["郵送先住所2"];
		}
	

		$acount_array["実家郵便番号"] = get_user_meta($user_id, 'billing_parents_billing_postcode', true);//郵便番号
		$acount_array["実家郵便番号ハイフン"] = "";
		

		if($acount_array["実家郵便番号"] != "")
		{
			 // 郵便番号の数字以外を取り除く（誤入力などの対策）
			$postalCode = preg_replace('/[^0-9]/', '', $acount_array["実家郵便番号"]);
    
			// 7桁の場合だけハイフンを入れる
			if (strlen($postalCode) === 7) {
				$acount_array["実家郵便番号ハイフン"] =  substr($postalCode, 0, 3) . '-' . substr($postalCode, 3, 4);

			} else {
				$acount_array["実家郵便番号ハイフン"] = $acount_array["実家郵便番号"];
			}
		}



		$acount_array["実家住所1"] = get_user_meta($user_id, 'billing_parents_address_1', true);//住所1
		$acount_array["実家住所2"] = get_user_meta($user_id, 'billing_parents_address_2', true);//住所2
		$acount_array["実家住所"] = $acount_array["実家住所1"];

		if($acount_array["実家住所2"] != "")
		{
			$acount_array["実家住所"] .= "" .$acount_array["実家住所2"];
		}


		//郵送先情報
		$acount_array["郵送先名前"] = get_user_meta($user_id, 'post_billing_first_name', true);//名前

		$acount_array["LINEID"]= get_user_meta($user_id, 'line_id', true);//LINE ID

		if($acount_array["LINEID"] != "")
		{
			$acount_array["LINEID"]  = "@" .$acount_array["LINEID"];
		}


		$acount_array["関係"]= "本人";


		$acount_array["施術ID"]= json_decode(get_user_meta($user_id,'spirit_data',true));

		
		if($acount_array["施術ID"] == "")$acount_array["施術ID"] = array();

		return $acount_array;

		
	}


	/****************************************************
	**  ユーザーページからの保存
	******************************************************/
	public function saveUserAcountData($user_id,$postData)
	{
		/*if ( current_user_can( 'edit_user', $user_id ) ) {
		} else {
			echo 'ユーザーにはメタ情報の編集権限がありません。';
		}
		*/
        //苗字
		if(isset( $postData["input_last_name"]))
		{
            update_user_meta($user_id, 'last_name', $postData["input_last_name"]);
		}
		 
        //名前
		if(isset( $postData["input_first_name"]))
		{
            update_user_meta($user_id, 'first_name', $postData["input_first_name"]);
		}

        //ミョウ
		if(isset( $postData["input_last_name_kana"]))
		{
            update_user_meta($user_id, 'last_name_kana', $postData["input_last_name_kana"]);
		}
		 
        //ナマエ
		if(isset( $postData["input_first_name_kana"]))
		{
            update_user_meta($user_id, 'first_name_kana', $postData["input_first_name_kana"]);
		}
        
		//性別
		if(isset( $postData["input_user_sex"]))
		{
            update_user_meta($user_id, 'sex', $postData["input_user_sex"]);
		}
       

		//電話番号１
		if(isset( $postData["input_tel_1"]))
		{
            update_user_meta($user_id, 'billing_phone', $postData["input_tel_1"]);
		}
		//電話番号２
		if(isset( $postData["input_tel_2"]))
		{
            update_user_meta($user_id, 'billing_phone2', $postData["input_tel_2"]);
		}
		//電話番号３
		if(isset( $postData["input_tel_3"]))
		{
            update_user_meta($user_id, 'billing_phone3', $postData["input_tel_3"]);
		}

        //郵便番号
		if(isset( $postData["input_post_no"]))
		{
            update_user_meta($user_id, 'billing_postcode', $postData["input_post_no"]);
		}
        
		//住所１
		if(isset( $postData["input_address1"]))
		{
            update_user_meta($user_id, 'billing_city', $postData["input_address1"]);
		}
        //住所２
		if(isset( $postData["input_address2"]))
		{
            update_user_meta($user_id, 'billing_address_1', $postData["input_address2"]);
		}
        
		//LINE ID
		if(isset( $postData["input_lind_id"]))
		{
            update_user_meta($user_id, 'line_id', $postData["input_lind_id"]);
		}

		//生年月日　年
		if(isset( $postData["input_user_born_year"]))
		{
            update_user_meta($user_id, 'born_year', $postData["input_user_born_year"]);
		}
		//生年月日　月
		if(isset( $postData["input_user_born_month"]))
		{
            update_user_meta($user_id, 'born_month', $postData["input_user_born_month"]);
		}
		//生年月日　日
		if(isset( $postData["input_user_born_day"]))
		{
            update_user_meta($user_id, 'born_day', $postData["input_user_born_day"]);
		}
        
       //届け出日　年
		if(isset( $postData["input_user_report_year"]))
		{
            update_user_meta($user_id, 'report_born_year', $postData["input_user_report_year"]);
		}
		//届け出日　月
		if(isset( $postData["input_user_report_month"]))
		{
            update_user_meta($user_id, 'report_born_month', $postData["input_user_report_month"]);
		}
		//届け出日　日
		if(isset( $postData["input_user_report_day"]))
		{
            update_user_meta($user_id, 'report_born_day', $postData["input_user_report_day"]);
		}

       
       //郵送先郵便番号
		if(isset( $postData["input_post_no2"]))
		{
            update_user_meta($user_id, 'post_billing_postcode', $postData["input_post_no2"]);
		}
        
		//郵送先住所１
		if(isset( $postData["input_post_address1"]))
		{
            update_user_meta($user_id, 'post_billing_city', $postData["input_post_address1"]);
		}
        //郵送先住所２
		if(isset( $postData["input_post_address2"]))
		{
            update_user_meta($user_id, 'post_billing_address_1', $postData["input_post_address2"]);
		}


		//実家郵便番号
		if(isset( $postData["input_parents_post_no"]))
		{
            update_user_meta($user_id, 'billing_parents_billing_postcode', $postData["input_parents_post_no"]);
		}
        
		//実家住所１
		if(isset( $postData["input_parents_address1"]))
		{
            update_user_meta($user_id, 'billing_parents_address_1', $postData["input_parents_address1"]);
		}
        //実家住所２
		if(isset( $postData["input_parents_address2"]))
		{
            update_user_meta($user_id, 'billing_parents_address_2', $postData["input_parents_address2"]);
		}

	}
	
	/****************************************************
	**  浄霊履歴を取得
	******************************************************/
	public function getUserSpritApplicant($user_id)
	{

		//東京にタイムゾーンを設定
		date_default_timezone_set('Asia/Tokyo');

		$spiritSheet = new SpiritSheetClass(); //管理データ
		$spiritSchedule = new SpiritScheduleClass(); //管理データ

		// 自分が申込者
        $applicant_split_data = $spiritSheet->getSpritApplicant( $user_id );

		
		//var_dump($spiritTypeArray);

		$applicant_split_array = array();

		$applicant_split_array["all"] = array();
		$applicant_split_array["complete"] = array();//完了
		$applicant_split_array["incomplete"] = array();//未完了

		foreach ($applicant_split_data as $key => $value) {


			//削除の場合は入れない
			if(get_field('is_delete',$key) != "")
			{
				continue;
			}

			//日程


			$split_all_array =  $this->getUserSpritApplicantSheet($user_id,$key);//シート単独の情報を取得する


			//日程と相談はスケジュールが入っていないと省く()
			if($split_all_array[$key]["依頼タイプ"] == SpiritTypeClass::SPIRIT_TYPE_NAME_DAY || $split_all_array[$key]["依頼タイプ"] == SpiritTypeClass::SPIRIT_TYPE_NAME_SODAN)
			{
				if($split_all_array[$key]["スケジュール"] == "")
				{
					continue;
				}
			}

			$applicant_split_array["all"] =  $split_all_array;

			

			//完了と未完了に分ける

			$member_status = $applicant_split_array["all"][$key]["会員ステータス"];

			//依頼タイプ
			$spirit_type = $applicant_split_array["all"][$key]["依頼タイプ"];

			
			//入金待ちの際に入金期限が超えていたらキャンセルにする
			if($member_status == SpiritUserClass::MEMBER_STATUS_WATING_PAYMENT && 
				$spirit_type != SpiritTypeClass::SPIRIT_TYPE_NAME_SPIRIT && 
				$spirit_type != SpiritTypeClass::SPIRIT_TYPE_NAME_APPRAISAL &&
				$spirit_type != SpiritTypeClass::SPIRIT_TYPE_NAME_SALES){

				//var_dump($applicant_split_array["all"][$key]);

				$application_date = $applicant_split_array["all"][$key]["依頼日"];

				if($spirit_type == SpiritTypeClass::SPIRIT_TYPE_NAME_DAY || $spirit_type == SpiritTypeClass::SPIRIT_TYPE_NAME_SODAN)
				{
					//銀行振込(銀行振込は依頼日から営業日３日以内)
					if($applicant_split_array["all"][$key]["支払いタイプ"] == 2)
					{

					}
					else{
						//銀行振込以外は1日以内
						
					}

				}

				//スケジュールの時のクレジット関連は１日以内、銀行振込は営業日３日以内

				$application_date_one_week_after = date('Y-m-d', strtotime($application_date . SpiritUserClass::SPIRIT_PAYMENT_DEADLINE));

				if($application_date_one_week_after < date('Y-m-d')){
					$applicant_split_array["all"][$key]["会員ステータス"] = SpiritUserClass::MEMBER_STATUS_CANCEL;
					$applicant_split_array["all"][$key]["会員ステータス表示"] = "キャンセル";
					$applicant_split_array["all"][$key]["キャンセル日年月日"] = date('Y年n月d日', strtotime($application_date_one_week_after));
					$applicant_split_array["complete"][$key] = $applicant_split_array["all"][$key];
					continue;
				}
			}

			//日程確定のみ、日程が１日過ぎていたら自動敵に完了にする
			if($spirit_type == 3 && $member_status != SpiritUserClass::MEMBER_STATUS_COMPLETE && $member_status != SpiritUserClass::MEMBER_STATUS_CANCEL){

				$set_schedule_sheet = $spiritSchedule->getSpritScheduledetail($applicant_split_array["all"][$key]["スケジュール"]);

				$date_time = new DateTime($set_schedule_sheet["実行日"]);

				//1日過ぎているかどうか
				$date_time->modify('+1 day');

				if($date_time < new DateTime()){

					//入金待ちの場合はキャンセル、それ以外の時は完了に変更しておく
					if($member_status == SpiritUserClass::MEMBER_STATUS_WATING_PAYMENT){
						$applicant_split_array["all"][$key]["会員ステータス"] = SpiritUserClass::MEMBER_STATUS_CANCEL;
						$applicant_split_array["all"][$key]["会員ステータス表示"] = "キャンセル";
						$applicant_split_array["all"][$key]["キャンセル日年月日"] = $date_time->format('Y年n月d日');
					}
					else{
						$applicant_split_array["all"][$key]["会員ステータス"] = SpiritUserClass::MEMBER_STATUS_COMPLETE;
						$applicant_split_array["all"][$key]["会員ステータス表示"] = "完了";
					}


					$applicant_split_array["complete"][$key] = $applicant_split_array["all"][$key];
					continue;
				}

				
				//$applicant_split_array["all"][$key]["会員ステータス"] = SpiritUserClass::MEMBER_STATUS_COMPLETE;
			}
				


			


			if($member_status== SpiritUserClass::MEMBER_STATUS_COMPLETE || $member_status == SpiritUserClass::MEMBER_STATUS_SHIPMENT_COMPLETE || $member_status == SpiritUserClass::MEMBER_STATUS_CANCEL) //完了&送付済み
			{
				$applicant_split_array["complete"][$key] = $applicant_split_array["all"][$key];
			}
			else{
				$applicant_split_array["incomplete"][$key] = $applicant_split_array["all"][$key];
			}

			$applicant_split_array["all_data"][$key] = $applicant_split_array["all"][$key];
			//var_dump($applicant_split_array["all"][$key]["質問回答"] );
		}
		

		return $applicant_split_array;

	}

	/****************************************************
	**  浄霊履歴を取得(シート指定)
	******************************************************/
	public function getUserSpritApplicantSheet($user_id,$key, $get_array = array())
	{

		if($key == "")
		{
			return "";
		}

		$get_all_data = false;

		if(count($get_array) == 0)
		{
			$get_all_data = true;
		}

		//このユーザーが存在するかどうか
		$user_data = get_user_by('id',$user_id);

		if($user_data == false)
		{
			return "";
		}

		$spiritSheet = new SpiritSheetClass(); //管理データ
		$spiritType = new SpiritTypeClass(); //管理データ


		//$spiritTypeArray = $spiritType->getSpiritTypeKeyTypeNum();//浄霊タイプ取得
		
		// 自分が申込者
		//$spiritStatusArray = $spiritSheet->getSpiritAdminStatusID('cpt_spirit_status');//管理ステータス
		//$spiritUSerStatusArray = $spiritSheet->getSpiritAdminStatusID('cpt_spirit_usestatus');//会員ステータス

		
		//var_dump($spiritTypeArray);

		$applicant_split_array[$key] = array();

		//削除の場合は入れない
		if(get_field('is_delete',$key) != "")
		{
			return "";
		}

		$applicant_split_array[$key] = array();

		//この間は何があっても取得
		$applicant_split_array[$key]["ID"] = $key;

		$applicant_split_array[$key]["依頼者ID"] = get_field('acf_purespirit_id',$key);

		$applicant_split_array[$key]["苗字"] = get_user_meta($applicant_split_array[$key]["依頼者ID"], 'last_name', true);//苗字
		$applicant_split_array[$key]["名前"] = get_user_meta($applicant_split_array[$key]["依頼者ID"], 'first_name', true);//名前
		$applicant_split_array[$key]["フル名前"] = $applicant_split_array[$key]["苗字"] . " " .$applicant_split_array[$key]["名前"];//フル

		$applicant_split_array[$key]["ミョウジ"] = get_user_meta($applicant_split_array[$key]["依頼者ID"], 'last_name_kana', true);//ミョウジ
		$applicant_split_array[$key]["ナマエ"] = get_user_meta($applicant_split_array[$key]["依頼者ID"], 'first_name_kana', true);//ナマエ
		$applicant_split_array[$key]["フルナマエ"] = $applicant_split_array[$key]["ミョウジ"] . " " .$applicant_split_array[$key]["ナマエ"];//フル

		//最終変更日
		$applicant_split_array[$key]["最終変更日"] = get_field('acf_previous_change_last_day',$key);

		if($applicant_split_array[$key]["最終変更日"] != "")
		{
			$date_time = new DateTime($applicant_split_array[$key]["最終変更日"]);
			$applicant_split_array[$key]["最終変更日年月日"] = $date_time->format('Y年n月j日 H:i:s');
		}
		else{

			//最終変更日がない場合はUBIXUNIXTIMEを入れる
			$applicant_split_array[$key]["最終変更日"] = date('Y-m-d H:i:s',get_field('acf_purespirit_unixtime',$key));
			$applicant_split_array[$key]["最終変更日年月日"] = date('Y年n月j日 H:i:s',get_field('acf_purespirit_unixtime',$key));
		}


		//最終変更者
		$applicant_split_array[$key]["最終変更者ID"] = get_field('acf_previous_change_last_id',$key);

		if($applicant_split_array[$key]["最終変更者ID"] != "")
		{
			$applicant_split_array[$key]["最終変更者"] = get_user_by('id',$applicant_split_array[$key]["最終変更者ID"]);
			$applicant_split_array[$key]["最終変更者名"] = $applicant_split_array[$key]["最終変更者"]->last_name . " " . $applicant_split_array[$key]["最終変更者"]->first_name;
		}
		else{
			$applicant_split_array[$key]["最終変更者名"] = "";
		}


		//依頼内容
		$spirit_name = $this->spirit_type_array[get_field('acf_acf_purespirit_type',$key)]["disp"];

		if($spirit_name =="")
		{
			//表示名がない(タイトルをそのまま入れる)
			$spirit_name = $this->spirit_type_array[get_field('acf_acf_purespirit_type',$key)]["title"];
		}

		$applicant_split_array[$key]["依頼タイプ"] =  $this->spirit_type_array[get_field('acf_acf_purespirit_type',$key)]["group"];
		$applicant_split_array[$key]["依頼タイプ名"] = $spiritType->getSpiritTypeName($applicant_split_array[$key]["依頼タイプ"]);
		$applicant_split_array[$key]["依頼ID"] = get_field('acf_acf_purespirit_type',$key);
		$applicant_split_array[$key]["依頼名前"] = $spirit_name;
		$applicant_split_array[$key]["依頼管理名前"] = $this->spirit_type_array[get_field('acf_acf_purespirit_type',$key)]["title"];


		//管理者ステータス
		$applicant_split_array[$key]["管理者ステータス"] = get_field('acf_purespirit_status',$key);

		if($applicant_split_array[$key]["管理者ステータス"] == "")
		{
			$applicant_split_array[$key]["管理者ステータス"] = 7815;//未完了
		}

		if($applicant_split_array[$key]["管理者ステータス"] != "")
		{
			$applicant_split_array[$key]["管理者ステータス表示"] = $this->spirit_status_array[ $applicant_split_array[$key]["管理者ステータス"] ]["title"];
		}

		//会員ステータス
		$applicant_split_array[$key]["会員ステータス"] = get_field('acf_purespirit_user_status',$key);

		if($applicant_split_array[$key]["会員ステータス"] == "")
		{
			$applicant_split_array[$key]["会員ステータス"] = 7830;//未完了
		}

		if($applicant_split_array[$key]["会員ステータス"] != "")
		{
			$applicant_split_array[$key]["会員ステータス表示"] = $this->spirit_uSerStatus_array[ $applicant_split_array[$key]["会員ステータス"] ]["disp_title"];
			
		}

		//ここまでは何があっても取得する

		//var_dump($spiritTypeArray);

		

		//依頼日
		if($get_all_data || in_array('依頼日',$get_array))
		{
			$applicant_split_array[$key]["依頼日"] = get_field('acf_purespirit_requested_date',$key);

			if($applicant_split_array[$key]["依頼日"] != "")
			{
				$date_time = new DateTime($applicant_split_array[$key]["依頼日"]);

				$applicant_split_array[$key]["依頼日年月日"] = $date_time->format('Y年n月j日');
			}
			else{
				$applicant_split_array[$key]["依頼日年月日"] = "";
			}
		}


		//依頼確定日
		if($get_all_data || in_array('依頼確定日',$get_array))
		{
			$applicant_split_array[$key]["依頼確定日"] = get_field('acf_purespirit_request_confirmation_date',$key);

			if($applicant_split_array[$key]["依頼確定日"] != "")
			{
				$date_time = new DateTime($applicant_split_array[$key]["依頼確定日"]);

				$applicant_split_array[$key]["依頼確定日年月日"] = $date_time->format('Y年n月j日');
			}
			else{
				$applicant_split_array[$key]["依頼確定日年月日"] = "";
			}
		}

		//実行日
		if($get_all_data || in_array('実行日',$get_array))
		{
			$applicant_split_array[$key]["実行日"] = get_field('acf_purespirit_execution_date',$key);

			if($applicant_split_array[$key]["実行日"] != "")
			{
				$date_time = new DateTime($applicant_split_array[$key]["実行日"]);

				$applicant_split_array[$key]["実行日年月日"] = $date_time->format('Y年n月j日');
			}
			else{
				$applicant_split_array[$key]["実行日年月日"] = "";
			}
		}

		//実行予定日
		if($get_all_data || in_array('実行予定日',$get_array))
		{
			$applicant_split_array[$key]["実行予定日"] = get_field('acf_purespirit_execution_confirmation_date',$key);

			if($applicant_split_array[$key]["実行予定日"] != "")
			{
				$date_time = new DateTime($applicant_split_array[$key]["実行予定日"]);

				$applicant_split_array[$key]["実行予定日年月日"] = $date_time->format('Y年n月j日');
			}
			else{
				$applicant_split_array[$key]["実行予定日年月日"] = "";
			}
		}


		//決済日
		if($get_all_data || in_array('決済日',$get_array))
		{
			$applicant_split_array[$key]["決済日"] = get_field('acf_purespirit_payment_date',$key);

			if($applicant_split_array[$key]["決済日"] != "")
			{
				$date_time = new DateTime($applicant_split_array[$key]["決済日"]);

				$applicant_split_array[$key]["決済日年月日"] = $date_time->format('Y年n月j日');
			}
			else{
				$applicant_split_array[$key]["決済日年月日"] = "";
			}
		}

		//価格
		//if($get_all_data || in_array('価格',$get_array))
		{
			$applicant_split_array[$key]["価格"] = get_field('acf_purespirit_price',$key);
			$applicant_split_array[$key]["価格元"] = get_field('acf_purespirit_price',$key);

			if($applicant_split_array[$key]["価格"] != "")
			{
				$applicant_split_array[$key]["価格"] = number_format($applicant_split_array[$key]["価格"]);
			}
			else{
				$applicant_split_array[$key]["価格"] = 0;
				$applicant_split_array[$key]["価格元"] = 0;
			}
		}
		
		//販売個数
		if($get_all_data || in_array('販売個数',$get_array))
		{
			$applicant_split_array[$key]["販売個数"] = get_field('acf_previous_quantity',$key);

			if($applicant_split_array[$key]["販売個数"] != "")
			{
				$applicant_split_array[$key]["販売個数"] = number_format($applicant_split_array[$key]["販売個数"]);
			}
			else{
				$applicant_split_array[$key]["販売個数"] = 0;
			}
		}

		//キャンセル日
		if($get_all_data || in_array('キャンセル日',$get_array))
		{
			$applicant_split_array[$key]["キャンセル日"] = get_field('acf_previous_cancel_day',$key);

			//キャンセル日の年月日
			if($applicant_split_array[$key]["キャンセル日"] != "")
			{
				$date_time = new DateTime($applicant_split_array[$key]["キャンセル日"]);

				$applicant_split_array[$key]["キャンセル日年月日"] = $date_time->format('Y年n月j日');
			}
			else{
				$applicant_split_array[$key]["キャンセル日年月日"] = "";
			}
		}


		//質問
		if($get_all_data || in_array('質問',$get_array))
		{
			$applicant_split_array[$key]["質問"] = get_field('acf_acf_purespirit_type',$key);

			//質問タイプ
			$applicant_split_array[$key]["質問タイトル"] = get_field('acf_pure_spirit_disp_title',$applicant_split_array[$key]["質問"]);

			//質問回答
			$applicant_split_array[$key]["質問回答"] = $spiritSheet->getSpiritSheetAnswer( $key );
			//場所の指定
			$applicant_split_array[$key]["質問場所"] = get_field('acf_pure_spirit_place_on',$applicant_split_array[$key]["質問"]);

			//未入力数
			$applicant_split_array[$key]["質問未入力数"] =  $this->checkUserSpiritQuestion($user_id,$applicant_split_array[$key]["質問回答"],$spiritSheet );

		}


		//対象者有無
		if($get_all_data || in_array('対象者',$get_array))
		{

			if(!isset($applicant_split_array[$key]["質問"]))
			{
				$applicant_split_array[$key]["質問"] = get_field('acf_acf_purespirit_type',$key);
			}
			

			$applicant_split_array[$key]["対象者有無"] = get_field('acf_pure_spirit_by_subject',$applicant_split_array[$key]["質問"]);
			//２つあったので、こちらでも判定
			if($applicant_split_array[$key]["対象者有無"] == "")
			{
				$applicant_split_array[$key]["対象者有無"] = get_field('acf_target_onoff',$key);
			}

			//対象者情報
			$applicant_split_array[$key]["対象者"] = $this->getUserTargetData($user_id, $key);
		}


			
		

		//リモート浄霊
		if($get_all_data || in_array('リモート浄霊',$get_array))
		{
			//リモート浄霊があるかどうか
			$applicant_split_array[$key]["リモート浄霊"] = get_field('acf_purespirit_remote_sprit_num',$key);

			//リモート浄霊の場合は別で調べる
			if($applicant_split_array[$key]["リモート浄霊"] != "")
			{
				$applicant_split_array[$key]["質問未入力数"] =  $this->checkUserRemoteSpiritQuestion($user_id,$applicant_split_array[$key]["リモート浄霊"],$spiritSheet );

				//リモート浄霊の枠数を取得
				$applicant_split_array[$key]["リモート浄霊枠"] = get_field('acf_remote_sprit_sheet_target_slots',$applicant_split_array[$key]["リモート浄霊"]);

				//リモート浄霊の情報を入れる
				$applicant_split_array[$key]["リモート浄霊情報"] = $this->getRemoteSpiritData( $applicant_split_array[$key]["リモート浄霊"] );
			}
		}

		if($get_all_data || in_array('再提出依頼',$get_array))
		{
		//再提出依頼表示
			$applicant_split_array[$key]["再提出依頼"] = get_field('acf_purespirit_return_input_text',$key);
		}


		//スケジュール
		if($get_all_data || in_array('スケジュール',$get_array))
		{
			$applicant_split_array[$key]["スケジュール有無"] = $this->spirit_type_array[get_field('acf_acf_purespirit_type',$key)]["placeOn"];

			$applicant_split_array[$key]["スケジュール"] = get_field('acf_previous_schedule_number',$key);
		}

		if($get_all_data || in_array('依頼内容追記',$get_array))
		{
			$applicant_split_array[$key]["依頼内容追記"] = get_field('acf_purespirit_add_text',$key);
		}

		//支払いタイプ
		if($get_all_data || in_array('支払いタイプ',$get_array))
		{
			$applicant_split_array[$key]["支払いタイプ"] = get_field('acf_previous_payment_type',$key);

			if($applicant_split_array[$key]["支払いタイプ"] == "")
			{
				$applicant_split_array[$key]["支払いタイプ"] = self::PAYMENT_TYPE_NOT_SET;
			}

			$applicant_split_array[$key]["支払い設定"] = $this->spirit_type_array[get_field('acf_acf_purespirit_type',$key)]["payment_setting"];
			$applicant_split_array[$key]["支払いタイプ表示"] = self::getPaymentTypeLabel($applicant_split_array[$key]["支払いタイプ"]);
		}
		//echo $applicant_split_array[$key]["リモート浄霊"] ."<br>";

		if($get_all_data || in_array('粗見シート',$get_array))
		{
			$applicant_split_array[$key]["粗見シート"] = get_field('acf_previous_target_arami',$key);
		}

	
		if($get_all_data || in_array('請求書',$get_array))
		{
			//請求書発行月
			$applicant_split_array[$key]["請求書発行月"] = get_field('acf_previous_execution_invoice_date',$key);

			//請求書発行年
			$applicant_split_array[$key]["請求書発行年"] = get_field('acf_previous_execution_invoice_date_year',$key);

			//発行稼働が有効かどうか
			$applicant_split_array[$key]["請求書発行"] = get_field('acf_previous_execution_invoice_enable',$key);

			if($applicant_split_array[$key]["請求書発行"] == ""){
				$applicant_split_array[$key]["請求書発行"] = 0;
			}
		}



		//送付先画像
		if($get_all_data || in_array('郵送先',$get_array))
		{
			$applicant_split_array[$key]["追跡者番号画像"] = get_field('acf_previous_sales_post_img',$key);
			
			//sheet上の郵送先
			$applicant_split_array[$key]["郵送先郵便番号"] = get_field('acf_previous_post_billing_postcode',$key);
			$applicant_split_array[$key]["郵送先住所1"] = get_field('acf_previous_billing_city',$key);
			$applicant_split_array[$key]["郵送先住所2"] = get_field('acf_previous_billing_address_1',$key);
			$applicant_split_array[$key]["郵送先名前"] = get_field('acf_previous_billing_first_name',$key);
		}

		//振込予定日
		if($get_all_data || in_array('振込予定日',$get_array))
		{
			$applicant_split_array[$key]["振込予定日"] = get_field('acf_previous_payment_schedule',$key);

			if($applicant_split_array[$key]["振込予定日"] != "")
			{
				$date_time = new DateTime($applicant_split_array[$key]["振込予定日"]);
				$applicant_split_array[$key]["振込予定日年月日"] = $date_time->format('Y年n月j日');
			}
			else{
				$applicant_split_array[$key]["振込予定日年月日"] = "";
			}
		}

		

		//再提出依頼
		//$applicant_split_array[$key]["再提出依頼"] = get_field('acf_purespirit_return_input_text',$key);

		return $applicant_split_array;

	}

	

	/****************************************************
	**  浄霊履歴から完了のものだけを取得
	******************************************************/
	public function getUserSpritApplicantComplete($user_history_array)
	{
		$complete_array = array();

		if(isset($user_history_array["complete"]))
		{
		

			foreach($user_history_array["complete"] as $key => $value)
			{

				//echo $value["依頼ID"] . " " . $value["会員ステータス"] . "<br>";
				
				if($value["会員ステータス"] == self::MEMBER_STATUS_COMPLETE)
				{
					if(!isset($complete_array[$value["依頼ID"]]))
					{
						$complete_array[$value["依頼ID"]] = $value;
					}
				}
			}
		}


		return $complete_array;
	}



	/****************************************************
	**  浄霊の質問の必須が全部入力があるかのチェック
	******************************************************/
	public function checkUserSpiritQuestion($user_id,$question_array,$spiritSheet )
	{

		$count = 0;

		//var_dump($question_array);

		foreach ($question_array as $key => $value) {

			$question_num =  get_field('acf_questionqnser_number',$key); //質問の番号

			if(get_field('acf_question_form_disp',$key) != 1)
			{
				continue;//会員に表示している
			}

			if(get_field('acf_question_required',$key) != 1)
			{
				continue;//必須じゃない
			}

			//未入力
			if(get_field('acf_question_type',$key) != 11) //画像以外
			{
				if(get_field('acf_questionqnser_text',$value) != "")
				{
					continue;//未入力じゃない
				}
			}
			else if(get_field('acf_question_type',$key) == 11) //画像
			{
				if(get_field('acf_questionqnser_img_url',$value) != "") //画像フォルダ
				{
					$folder_path = get_field('acf_questionqnser_img_url',$value) ;

					if (is_dir($folder_path)) {

						$files = array_diff(scandir($folder_path), array('.', '..'));

						if (!empty($files)) {
							continue;//フォルダ内に何かある
						} 
					} 
					
				}
			}


			$count++;

		}
		

		return $count;

	}
	/****************************************************
	**  リモート浄霊の質問の必須が全部入力があるかのチェック
	******************************************************/
	public function checkUserRemoteSpiritQuestion($user_id,$remote_sheet,$spiritSheet )
	{

		//枠数から対象者がいるかどうかを調べる
		$slots = get_field('acf_remote_sprit_sheet_target_slots',$remote_sheet);

		

		$count = 0;

		for($i=1;$i<=$slots;$i++)
		{
			//対象者シートを取得
			$sheet_id = get_field('acf_remote_sprit_sheet_id_' .$i ,$remote_sheet) ;

			if($sheet_id == "")
			{
				$count++;//シートがない場合はまだ
				continue;
			}



			//対象者シートが変わったので変更
			if(get_field('acf_target_last_name' ,$sheet_id) == "")//苗字
			{
				$count++;
			}

			if(get_field('acf_target_first_name' ,$sheet_id) == "")//名前
			{
				$count++;
			}
			if(get_field('acf_target_last_name_kana' ,$sheet_id) == "")//ミョウジ
			{
				$count++;
			}
			if(get_field('acf_target_first_name_kana' ,$sheet_id) == "")//ナマエ
			{
				$count++;
			}

			if(get_field('acf_target_relationship' ,$sheet_id) == "")//関係
			{
				$count++;
			}
			if(get_field('acf_target_born_year' ,$sheet_id) == "")//誕生日
			{
				$count++;
			}
			if(get_field('acf_target_born_day' ,$sheet_id) == "")//誕生日
			{
				$count++;
			}
			if(get_field('acf_target_born_month' ,$sheet_id) == "")//誕生日
			{
				$count++;
			}

			if(get_field('acf_remote_sprit_sheet_target_img_1',$sheet_id) != "") //画像フォルダ
			{
				$folder_path = get_field('acf_remote_sprit_sheet_target_img_1',$sheet_id) ;

				if (is_dir($folder_path)) {

					$files = array_diff(scandir($folder_path), array('.', '..'));

					if (empty($files)) {


						$count++;//フォルダ内になにもない
					} 
				} 
					
			}
			else {
				$count++;//フォルダ内になにもない
			}
		}
		
		return $count;//対象者を入力している時点で、質問は入っている

	}


	




	/****************************************************
	**  ユーザーの対象者情報を取得
	******************************************************/
	public function getUserTargetData($user_id, $target_id)
	{

		$target_array = array();


		$target_array["ID"] = $target_id;//ID
		$target_array["メール"] = "";


		if($target_id != "")
		{
			$target_array["メール"] = get_field('acf_target_user_email',$target_id) ;
		}

		$target_array["申込者ID"] = $user_id;//ID



		$target_array["苗字"] = "";
		$target_array["名前"] = "";
		$target_array["フル名前"] = "";

		if($target_id != "")
		{
			$target_array["苗字"] = get_field('acf_target_last_name',$target_id) ;//苗字
			$target_array["名前"] = get_field('acf_target_first_name',$target_id) ;//名前

			if($target_array["苗字"] == NULL)$target_array["苗字"] = "";
			if($target_array["名前"] == NULL)$target_array["名前"] = "";

			$target_array["フル名前"] = $target_array["苗字"] . " " .$target_array["名前"];//フル
		}

		$target_array["ミョウジ"] = "";
		$target_array["ナマエ"] = "";
		$target_array["フルナマエ"] = "";

		if($target_id != "")
		{
			$target_array["ミョウジ"] = get_field('acf_target_last_name_kana',$target_id) ;//苗字
			$target_array["ナマエ"] = get_field('acf_target_first_name_kana',$target_id) ;//名前

			if($target_array["ミョウジ"] == NULL)$target_array["ミョウジ"] = "";
			if($target_array["ナマエ"] == NULL)$target_array["ナマエ"] = "";

			$target_array["フルナマエ"] = $target_array["ミョウジ"] . " " .$target_array["ナマエ"];//フル
		}

		$target_array["性別値"] = "";
		$target_array["性別"] = "未設定";
		
		if($target_id != "")
		{
			$target_array["性別値"] = get_field('acf_target_sex',$target_id) ;

			if($target_array["性別値"] == NULL)$target_array["性別値"] = "";
		}

		if($target_array["性別値"]  =="")
		{
			$target_array["性別値"] = "U"; //未設定でまとめる
		}

		if($target_array["性別値"] == "M")
		{
			$target_array["性別"] = "男性";
		}
		else if($target_array["性別値"] == "W")
		{
			$target_array["性別"] = "女性";
		}
		else if($target_array["性別値"] == "U")
		{
			$target_array["性別"] = "未設定";
		}
		 
		$target_array["電話番号1"] = "";
		$target_array["電話番号2"] = "";
		$target_array["電話番号3"] = "";

		if($target_id != "")
		{
			$target_array["電話番号1"] = get_field('acf_target_billing_phone',$target_id) ;
			$target_array["電話番号2"] = get_field('acf_target_billing_phone2',$target_id) ;
			$target_array["電話番号3"] = get_field('acf_target_billing_phone3',$target_id) ;

			if($target_array["電話番号1"] == NULL)$target_array["電話番号1"] = "";
			if($target_array["電話番号2"] == NULL)$target_array["電話番号2"] = "";
			if($target_array["電話番号3"] == NULL)$target_array["電話番号3"] = "";
		}

	
		$target_array["電話番号"] = "";

		if($target_array["電話番号1"] != "")//最初の番号があると仮定して
		{
			$target_array["電話番号"] = $target_array["電話番号1"] . "-" . $target_array["電話番号2"] ."-" .$target_array["電話番号3"];
		}

		
		$target_array["誕生日年"] = "";
		$target_array["誕生日月"] = "";
		$target_array["誕生日日"] = "";
		$target_array["誕生日"] = "";
		$target_array["誕生日年月日"] = "";


		if($target_id != "")
		{
			$target_array["誕生日年"] = get_field('acf_target_born_year',$target_id) ;
			$target_array["誕生日月"] = get_field('acf_target_born_month',$target_id) ;
			$target_array["誕生日日"] = get_field('acf_target_born_day',$target_id) ;

			if($target_array["誕生日年"] == NULL)$target_array["誕生日年"] = "";
			if($target_array["誕生日月"] == NULL)$target_array["誕生日月"] = "";
			if($target_array["誕生日日"] == NULL)$target_array["誕生日日"] = "";
		}

		
		if($target_array["誕生日年"] != "" && $target_array["誕生日月"] != "")//最初の年があると仮定して
		{
			$target_array["誕生日"] = $target_array["誕生日年"] ."-" .$target_array["誕生日月"] . "-" . $target_array["誕生日日"];

			$date_time = new DateTime($target_array["誕生日"]);

			$target_array["誕生日年月日"] = $date_time->format('Y年n月j日');
		}



		$target_array["届け出日年"] = "";
		$target_array["届け出日月"] = "";
		$target_array["届け出日日"] = "";
		$target_array["届け出日"] = "";
		$target_array["届け出日年月日"] = "";


		if($target_id != "")
		{
			$target_array["届け出日年"] = get_field('acf_target_report_born_year',$target_id) ;
			$target_array["届け出日月"] = get_field('acf_target_report_born_month',$target_id) ;
			$target_array["届け出日日"] = get_field('acf_target_report_born_day',$target_id) ;

			if($target_array["届け出日年"] == NULL)$target_array["届け出日年"] = "";
			if($target_array["届け出日月"] == NULL)$target_array["届け出日月"] = "";
			if($target_array["届け出日日"] == NULL)$target_array["届け出日日"] = "";
		}


		if($target_array["届け出日年"] != "")//最初の年があると仮定して
		{
			$target_array["届け出日"] = $target_array["届け出日年"] ."-" .$target_array["届け出日月"] . "-" . $target_array["届け出日日"];

			$date_time = new DateTime($target_array["届け出日"]);

			$target_array["届け出日年月日"] = $date_time->format('Y年n月j日');
		}


		$target_array["郵便番号"] = "";
		$target_array["郵便番号ハイフン"] = "";

		if($target_id != "")
		{
			$target_array["郵便番号"] = get_field('acf_target_billing_postcode',$target_id) ;

			if($target_array["郵便番号"] == NULL)$target_array["郵便番号"] = "";
		}


		if($target_array["郵便番号"] != "")
		{
			 // 郵便番号の数字以外を取り除く（誤入力などの対策）
			$postalCode = preg_replace('/[^0-9]/', '', $target_array["郵便番号"]);
    
			// 7桁の場合だけハイフンを入れる
			if (strlen($postalCode) === 7) {
				$target_array["郵便番号ハイフン"] =  substr($postalCode, 0, 3) . '-' . substr($postalCode, 3, 4);

			} else {
				$target_array["郵便番号ハイフン"] = $target_array["郵便番号"];
			}
		}

		
		$target_array["住所1"] = "";
		$target_array["住所2"] = "";
		$target_array["住所"] = "";

		if($target_id != "")
		{
			$target_array["住所1"] = get_field('acf_target_billing_city',$target_id) ;
			$target_array["住所2"] = get_field('acf_target_billing_address_1',$target_id) ;

			if($target_array["住所1"] == NULL)$target_array["住所1"] = "";
			if($target_array["住所2"] == NULL)$target_array["住所2"] = "";

			$target_array["住所"] = $target_array["住所1"];

		}


		if($target_array["住所2"] != "")
		{
			$target_array["住所"] .= "" .$target_array["住所2"];
		}


		$target_array["関係"] = "";

		if($target_id != "")
		{
			$target_array["関係"] = get_field('acf_target_relationship',$target_id) ;

			if($target_array["関係"] == NULL)$target_array["関係"] = "";
		}

		$target_array["LINEID"] = "";

		if($target_id != "")
		{
			$target_array["LINEID"] = get_field('acf_target_line_id',$target_id) ;

			if($target_array["LINEID"] == NULL)$target_array["LINEID"] = "";
		}
		

		$target_array["実家郵便番号"] = "";
		$target_array["実家郵便番号ハイフン"] = "";

		if($target_id != "")
		{
			$target_array["実家郵便番号"] = get_field('acf_target_billing_parents_home_postcode',$target_id) ;

			if($target_array["実家郵便番号"] == NULL)$target_array["実家郵便番号"] = "";
		}


		if($target_array["実家郵便番号"] != "")
		{
			 // 郵便番号の数字以外を取り除く（誤入力などの対策）
			$postalCode = preg_replace('/[^0-9]/', '', $target_array["実家郵便番号"]);
    
			// 7桁の場合だけハイフンを入れる
			if (strlen($postalCode) === 7) {
				$target_array["実家郵便番号ハイフン"] =  substr($postalCode, 0, 3) . '-' . substr($postalCode, 3, 4);

			} else {
				$target_array["実家郵便番号ハイフン"] = $target_array["実家郵便番号"];
			}
		}

		
		$target_array["実家住所1"] = "";
		$target_array["実家住所2"] = "";
		$target_array["実家住所"] = "";

		if($target_id != "")
		{
			$target_array["実家住所1"] = get_field('acf_target_parents_home_billing_city',$target_id) ;
			$target_array["実家住所2"] = get_field('acf_target_parents_home_billing_address_1',$target_id) ;

			if($target_array["実家住所1"] == NULL)$target_array["実家住所1"] = "";
			if($target_array["実家住所2"] == NULL)$target_array["実家住所2"] = "";

			$target_array["実家住所"] = $target_array["実家住所1"];

		}

		//対象者情報の有無
		$target_array["対象者情報"] = get_field('acf_target_onoff',$target_id) ;

		if($target_array["対象者情報"] == "")$target_array["対象者情報"] = 0;

		return $target_array;
	}


	/****************************************************
	**  ユーザーページからの対象者取得
	******************************************************/
	public function getTargetAcountData($user_id)
	{
		$wp_query = new WP_Query();

        $param = array(
            'posts_per_page' => '-1', //表示件数。-1なら全件表示
            'post_type' => 'cpt_target', //カスタム投稿タイプの名称を入れる
            'post_status' => 'publish', //取得するステータス。publishなら一般公開のもののみ
            'orderby' => 'ID', //ID順に並び替え
            'order' => 'DESC'
        );

        $wp_query->query($param);

        $target_list = array();

        //一番大きいソート番号を探す
        if ($wp_query->have_posts()):

            while ($wp_query->have_posts()):
                $wp_query->the_post();


                if( get_field('acf_target_make_user') == $user_id)
				{
					$target_list[get_the_ID()] = get_the_ID();
				}

            endwhile;
        endif;


        return $target_list;

	}


	/****************************************************
	**  ユーザーページからの対象者作成
	******************************************************/
	public function newTargetAcountData($user_id)
	{
		$wp_query = new WP_Query();

        $title =  get_user_meta($user_id,'last_name',true) . " " .get_user_meta($user_id,'first_name',true);

        $my_post = array(
            'post_title' => $title,
            'post_type' => 'cpt_target', //カスタム投稿タイプの名称を入れる
            'post_status' => 'publish',
            'post_author' => $user_id,
        );

        $program_id = wp_insert_post($my_post);
        
        return $program_id;

	}

	/****************************************************
	**  ユーザーページからの対象者保存
	******************************************************/
	public function saveTargetAcountData($user_id,$target_id,$postData)
	{

		//作成ユーザー
		update_field("acf_target_make_user", $user_id, $target_id);

        //苗字
		if(isset( $postData["input_last_name"]))
		{
			update_field("acf_target_last_name", $postData["input_last_name"], $target_id);
		}
		 
        //名前
		if(isset( $postData["input_first_name"]))
		{
            update_field("acf_target_first_name", $postData["input_first_name"], $target_id);
		}

        //ミョウ
		if(isset( $postData["input_last_name_kana"]))
		{
			update_field("acf_target_last_name_kana", $postData["input_last_name_kana"], $target_id);
		}
		 
        //ナマエ
		if(isset( $postData["input_first_name_kana"]))
		{
           update_field("acf_target_first_name_kana", $postData["input_first_name_kana"], $target_id);
		}
        
		//メールアドレス
		if(isset( $postData["input_mail"]))
		{
            update_field("acf_target_user_email", $postData["input_mail"], $target_id);
		}

		//性別
		if(isset( $postData["input_user_sex"]))
		{
            update_field("acf_target_sex", $postData["input_user_sex"], $target_id);
		}
       

		//電話番号１
		if(isset( $postData["input_tel_1"]))
		{
           update_field("acf_target_billing_phone", $postData["input_tel_1"], $target_id);
		}
		//電話番号２
		if(isset( $postData["input_tel_2"]))
		{
            update_field("acf_target_billing_phone2", $postData["input_tel_2"], $target_id);
		}
		//電話番号３
		if(isset( $postData["input_tel_3"]))
		{
           update_field("acf_target_billing_phone3", $postData["input_tel_3"], $target_id);
		}

        //郵便番号
		if(isset( $postData["input_post_no"]))
		{
            update_field("acf_target_billing_postcode", $postData["input_post_no"], $target_id);
		}
        
		//住所１
		if(isset( $postData["input_address1"]))
		{
            update_field("acf_target_billing_city", $postData["input_address1"], $target_id);
		}
        //住所２
		if(isset( $postData["input_address2"]))
		{
            update_field("acf_target_billing_address_1", $postData["input_address2"], $target_id);
		}
        

		//生年月日　年
		if(isset( $postData["input_user_born_year"]))
		{
           update_field("acf_target_born_year", $postData["input_user_born_year"], $target_id);
		}
		//生年月日　月
		if(isset( $postData["input_user_born_month"]))
		{
           update_field("acf_target_born_month", $postData["input_user_born_month"], $target_id);
		}
		//生年月日　日
		if(isset( $postData["input_user_born_day"]))
		{
            update_field("acf_target_born_day", $postData["input_user_born_day"], $target_id);
		}
        
       //届け出日　年
		if(isset( $postData["input_user_report_year"]))
		{
            update_field("acf_target_report_born_year", $postData["input_user_report_year"], $target_id);
		}
		//届け出日　月
		if(isset( $postData["input_user_report_month"]))
		{
            update_field("acf_target_report_born_month", $postData["input_user_report_month"], $target_id);
		}
		//届け出日　日
		if(isset( $postData["input_user_report_day"]))
		{
            update_field("acf_target_report_born_day", $postData["input_user_report_day"], $target_id);
		}

		//関係
		if(isset( $postData["input_user_target_relationship"]))
		{
			update_field("acf_target_relationship", $postData["input_user_target_relationship"], $target_id);
		}
		//LINE ID
		if(isset( $postData["input_user_target_line_id"]))
		{
			update_field("acf_target_line_id", $postData["input_user_target_line_id"], $target_id);
		}

		//実家郵便番号
		if(isset( $postData["input_parents_home_post_no"]))
		{
            update_field("acf_target_billing_parents_home_postcode", $postData["input_parents_home_post_no"], $target_id);
		}
        
		//実家住所１
		if(isset( $postData["input_parents_home_address1"]))
		{
            update_field("acf_target_parents_home_billing_city", $postData["input_parents_home_address1"], $target_id);
		}
        //実家住所２
		if(isset( $postData["input_parents_home_address2"]))
		{
            update_field("acf_target_parents_home_billing_address_1", $postData["input_parents_home_address2"], $target_id);
		}
	}


	/****************************************************
	**  質問ページからの対象者保存
	******************************************************/
	public function saveTargetQuestiontData($user_id,$postData)
	{
		$group_id = '7898';//対象者
        $fields = acf_get_fields($group_id);

		$wp_query = new WP_Query();

        $param = array(
            'posts_per_page' => '-1', //表示件数。-1なら全件表示
            'post_type' => 'cpt_target', //カスタム投稿タイプの名称を入れる
            'post_status' => 'publish', //取得するステータス。publishなら一般公開のもののみ
            'orderby' => 'ID', //ID順に並び替え
            'order' => 'DESC'
        );

        $wp_query->query($param);

        //一番大きいソート番号を探す
        if ($wp_query->have_posts()):

            while ($wp_query->have_posts()):
                $wp_query->the_post();


                if( get_field('acf_target_make_user') == $user_id && get_field('acf_target_last_name') == $postData["acf_target_last_name"] && get_field('acf_target_first_name') == $postData["acf_target_first_name"] && get_field('acf_target_user_email') == $postData["acf_target_user_email"] )
				{
					//上書き
					foreach ($fields as $field => $data) {

						if(isset($postData[ $data["name"] ]))
						{
							 update_field(  $data["name"] , $postData[ $data["name"] ] , get_the_ID());
						}
					}   

					return get_the_ID();//上書き

				}

            endwhile;
        endif;

		//新規登録
		$target_id = $this->newTargetAcountData($user_id);


		//作成ユーザー
		update_field("acf_target_make_user", $user_id, $target_id);

		//データ保存
		foreach ($fields as $field => $data) {

			if(isset($postData[ $data["name"] ]))
			{
				update_field(  $data["name"] , $postData[ $data["name"] ] , $target_id);
			}
		}

		return $target_id;

	}



	/****************************************************
	**  質問の送付先配列を取得する
	******************************************************/
	public function getQuestionPostAddressArray()
	{
		 $post_address_array = array();

        $post_address_array["苗字"] =  "";
        $post_address_array["名前"] =  "";
        $post_address_array["電話番号1"] =   "";
        $post_address_array["電話番号2"] =    "";
        $post_address_array["電話番号3"] =    "";
        $post_address_array["郵便番号"] =   "";
        $post_address_array["住所1"] =    "";
        $post_address_array["住所2"] =    "";

		return $post_address_array;

	}


	/****************************************************
	**  リモート浄霊の情報を取得する
	******************************************************/
	public function getRemoteSpiritData($sheet_id)
	{
		//枠数取得
		$slots = get_field('acf_remote_sprit_sheet_target_slots', $sheet_id);


		$target_remote_array = array();

		for($i=1;$i<=$slots;$i++)
		{
			/*
			$target_array = array();

			$target_array["ID"] = "";
			$target_array["苗字"] = "";
			$target_array["名前"] = "";
			$target_array["フル名前"] = "";
			$target_array["ミョウジ"] = "";
			$target_array["ナマエ"] = "";
			$target_array["フルナマエ"] = "";
			$target_array["誕生日年"] = "";
			$target_array["誕生日月"] = "";
			$target_array["誕生日日"] = "";
			$target_array["誕生日"] = "";
			$target_array["誕生日年月日"] = "";
			$target_array["関係"] = "";
			$target_array["画像1"] = "";
			$target_array["画像2"] = "";
			$target_array["画像URL1"] = "";
			$target_array["画像URL2"] = "";
			$target_array["実行日"] = "";
			$target_array["実行予定日"] = "";
			$target_array["入力完了日"] = "";
			$target_array["ステータス"] = "";
			$target_array["粗見シート"] = "";

			$target_array["実行日年月日"] = "";
			$target_array["実行予定日年月日"] = "";
			$target_array["入力完了日年月日"] = "";

			if(get_field('acf_remote_sprit_sheet_id_' .$i , $sheet_id) != "")//対象者がある
			{
				$target_num = get_field('acf_remote_sprit_sheet_id_' .$i , $sheet_id);

				$target_array["ID"] = $target_num;

				$target_array["苗字"] = get_field('acf_target_last_name',$target_num) ;//苗字
				$target_array["名前"] = get_field('acf_target_first_name',$target_num) ;//名前

				if($target_array["苗字"] == NULL)$target_array["苗字"] = "";
				if($target_array["名前"] == NULL)$target_array["名前"] = "";

				$target_array["フル名前"] = $target_array["苗字"] . " " .$target_array["名前"];//フル



				$target_array["ミョウジ"] = get_field('acf_target_last_name_kana',$target_num) ;//苗字
				$target_array["ナマエ"] = get_field('acf_target_first_name_kana',$target_num) ;//名前

				if($target_array["ミョウジ"] == NULL)$target_array["ミョウジ"] = "";
				if($target_array["ナマエ"] == NULL)$target_array["ナマエ"] = "";

				$target_array["フルナマエ"] = $target_array["ミョウジ"] . " " .$target_array["ナマエ"];//フル


				$target_array["誕生日年"] = get_field('acf_target_born_year',$target_num) ;
				$target_array["誕生日月"] = get_field('acf_target_born_month',$target_num) ;
				$target_array["誕生日日"] = get_field('acf_target_born_day',$target_num) ;

				if($target_array["誕生日年"] == NULL)$target_array["誕生日年"] = "";
				if($target_array["誕生日月"] == NULL)$target_array["誕生日月"] = "";
				if($target_array["誕生日日"] == NULL)$target_array["誕生日日"] = "";

				if($target_array["誕生日年"] != "")//最初の年があると仮定して
				{
					$target_array["誕生日"] = $target_array["誕生日年"] ."-" .$target_array["誕生日月"] . "-" . $target_array["誕生日日"];

					$date_time = new DateTime($target_array["誕生日"]);

					$target_array["誕生日年月日"] = $date_time->format('Y年n月j日');
				}

				$target_array["関係"] = get_field('acf_target_relationship',$target_num) ;

				if($target_array["関係"] == NULL)$target_array["関係"] = "";

				$target_array["画像1"] = get_field('acf_remote_sprit_sheet_target_img_1',$target_num) ;
				$target_array["画像2"] = get_field('acf_remote_sprit_sheet_target_img_2',$target_num) ;


				for($j=1;$j<=2;$j++)
				{

					if($target_array["画像" .$j] != "")
					{
						$saved_images = array();

						$file = glob($target_array["画像" .$j] ."/*.*");

						foreach ($file as $path) {
                
							$result = get_stylesheet_directory_uri()  ."/" . strstr($path, "user-img-folder"); // "is a test string."


							array_push($saved_images,$result);
						}

						$target_array["画像URL"  .$j] = $saved_images;
					}
				}


				$target_array["粗見シート"] = get_field('acf_remote_sprit_sheet_target_arami',$target_num) ;



				$target_array["実行日"] = get_field('acf_remote_sprit_sheet_target_execution_date',$target_num) ;


				if($target_array["実行日"] != "")
				{
					$date_time = new DateTime($target_array["実行日"]);
					$target_array["実行日年月日"] = $date_time->format('Y年n月j日');
				}

				$target_array["実行予定日"] = get_field('acf_remote_sprit_sheet_target_execution_schedule_date',$target_num) ;

				if($target_array["実行予定日"] != "")
				{
					$date_time = new DateTime($target_array["実行予定日"]);
					$target_array["実行予定日年月日"] = $date_time->format('Y年n月j日');
				}

				$target_array["入力完了日"] = get_field('acf_remote_sprit_sheet_target_input_date',$target_num) ;


				if($target_array["入力完了日"] != "")
				{
					$date_time = new DateTime($target_array["入力完了日"]);
					$target_array["入力完了日年月日"] = $date_time->format('Y年n月j日');
				}

				$target_array["ステータス"] = get_field('acf_remote_sprit_sheet_target_status',$target_num) ;


			}
			*/

			$target_remote_array[ $i ] = $this->getRemoteSpiritDataOne($sheet_id , $i);

		}
			


		return $target_remote_array;
			
	}

	/****************************************************
	**  リモート浄霊の情報を取得する
	******************************************************/
	public function getRemoteSpiritDataOne($sheet_id , $in_number)
	{
		//枠数取得
		$slots = get_field('acf_remote_sprit_sheet_target_slots', $sheet_id);


		$target_remote_array = array();

		$target_array = array();

		$target_array["ID"] = "";
		$target_array["シート内番号"] = "";

		$target_array["苗字"] = "";
		$target_array["名前"] = "";
		$target_array["フル名前"] = "";
		$target_array["ミョウジ"] = "";
		$target_array["ナマエ"] = "";
		$target_array["フルナマエ"] = "";
		$target_array["誕生日年"] = "";
		$target_array["誕生日月"] = "";
		$target_array["誕生日日"] = "";
		$target_array["誕生日"] = "";
		$target_array["誕生日年月日"] = "";
		$target_array["関係"] = "";
		$target_array["画像1"] = "";
		$target_array["画像2"] = "";
		$target_array["画像URL1"] = "";
		$target_array["画像URL2"] = "";
		$target_array["実行日"] = "";
		$target_array["実行予定日"] = "";
		$target_array["入力完了日"] = "";
		$target_array["ステータス"] = "";
		$target_array["粗見シート"] = "";

		$target_array["実行日年月日"] = "";
		$target_array["実行予定日年月日"] = "";
		$target_array["入力完了日年月日"] = "";
		$target_array["粗見表示画像"] = "";


		if(get_field('acf_remote_sprit_sheet_id_' .$in_number , $sheet_id) != "")//対象者がある
		{
			$target_num = get_field('acf_remote_sprit_sheet_id_' .$in_number , $sheet_id);

			$target_array["ID"] = $target_num;
			$target_array["シート内番号"] = $in_number;
			$target_array["苗字"] = get_field('acf_target_last_name',$target_num) ;//苗字
			$target_array["名前"] = get_field('acf_target_first_name',$target_num) ;//名前

			if($target_array["苗字"] == NULL)$target_array["苗字"] = "";
			if($target_array["名前"] == NULL)$target_array["名前"] = "";

			$target_array["フル名前"] = $target_array["苗字"] . " " .$target_array["名前"];//フル



			$target_array["ミョウジ"] = get_field('acf_target_last_name_kana',$target_num) ;//苗字
			$target_array["ナマエ"] = get_field('acf_target_first_name_kana',$target_num) ;//名前

			if($target_array["ミョウジ"] == NULL)$target_array["ミョウジ"] = "";
			if($target_array["ナマエ"] == NULL)$target_array["ナマエ"] = "";

			$target_array["フルナマエ"] = $target_array["ミョウジ"] . " " .$target_array["ナマエ"];//フル


			$target_array["誕生日年"] = get_field('acf_target_born_year',$target_num) ;
			$target_array["誕生日月"] = get_field('acf_target_born_month',$target_num) ;
			$target_array["誕生日日"] = get_field('acf_target_born_day',$target_num) ;

			if($target_array["誕生日年"] == NULL)$target_array["誕生日年"] = "";
			if($target_array["誕生日月"] == NULL)$target_array["誕生日月"] = "";
			if($target_array["誕生日日"] == NULL)$target_array["誕生日日"] = "";

			if($target_array["誕生日年"] != "")//最初の年があると仮定して
			{
				$target_array["誕生日"] = $target_array["誕生日年"] ."-" .$target_array["誕生日月"] . "-" . $target_array["誕生日日"];

				$date_time = new DateTime($target_array["誕生日"]);

				$target_array["誕生日年月日"] = $date_time->format('Y年n月j日');
			}

			$target_array["関係"] = get_field('acf_target_relationship',$target_num) ;

			if($target_array["関係"] == NULL)$target_array["関係"] = "";

			$target_array["画像1"] = get_field('acf_remote_sprit_sheet_target_img_1',$target_num) ;
			$target_array["画像2"] = get_field('acf_remote_sprit_sheet_target_img_2',$target_num) ;


			for($j=1;$j<=2;$j++)
			{

				if($target_array["画像" .$j] != "")
				{
					$saved_images = array();

					$file = glob($target_array["画像" .$j] ."/*.*");

					foreach ($file as $path) {
                
						$result = get_stylesheet_directory_uri()  ."/" . strstr($path, "user-img-folder"); // "is a test string."


						array_push($saved_images,$result);
					}

					$target_array["画像URL"  .$j] = $saved_images;
				}
			}


			$target_array["粗見シート"] = get_field('acf_remote_sprit_sheet_target_arami',$target_num) ;
			$target_array["粗見表示画像"] = get_field('acf_remote_sprit_sheet_movie_choice',$target_num) ;


			$target_array["実行日"] = get_field('acf_remote_sprit_sheet_target_execution_date',$target_num) ;


			if($target_array["実行日"] != "")
			{
				$date_time = new DateTime($target_array["実行日"]);
				$target_array["実行日年月日"] = $date_time->format('Y年n月j日');
			}

			$target_array["実行予定日"] = get_field('acf_remote_sprit_sheet_target_execution_schedule_date',$target_num) ;

			if($target_array["実行予定日"] != "")
			{
				$date_time = new DateTime($target_array["実行予定日"]);
				$target_array["実行予定日年月日"] = $date_time->format('Y年n月j日');
			}

			$target_array["入力完了日"] = get_field('acf_remote_sprit_sheet_target_input_date',$target_num) ;


			if($target_array["入力完了日"] != "")
			{
				$date_time = new DateTime($target_array["入力完了日"]);
				$target_array["入力完了日年月日"] = $date_time->format('Y年n月j日');
			}

			$target_array["ステータス"] = get_field('acf_remote_sprit_sheet_target_status',$target_num) ;


		}


		return $target_array;
			
	}

	/****************************************************
	**  リモート浄霊の情報を保存する(会員ページ)
	******************************************************/
	public function saveRemoteSpiritDataMemberPage($sheet_id , $post_data , $post_label)
	{

		if(isset($post_data[ $post_label . "_sei" ]))
		{
			update_field( "acf_target_last_name" , $post_data[ $post_label . "_sei" ] , $sheet_id);//苗字
		}


		if(isset($post_data[ $post_label . "_mei" ]))
		{
			update_field( "acf_target_first_name" , $post_data[ $post_label . "_mei" ] , $sheet_id);//名前
		}


		if(isset($post_data[ $post_label . "_sei_kana" ]))
		{
			update_field( "acf_target_last_name_kana" , $post_data[ $post_label . "_sei_kana" ] , $sheet_id);//ミョウジ
		}

		if(isset($post_data[ $post_label . "_mei_kana" ]))
		{
			update_field( "acf_target_first_name_kana" , $post_data[ $post_label . "_mei_kana" ] , $sheet_id);//ナマエ
		}

		if(isset($post_data[ $post_label . "_parents" ]))
		{
			update_field( "acf_target_relationship" , $post_data[ $post_label . "_parents" ] , $sheet_id);//関係
		}

		if(isset($post_data[ $post_label . "_born_year" ]))
		{
			update_field( "acf_target_born_year" , $post_data[ $post_label . "_born_year" ] , $sheet_id);//誕生日年
		}

		if(isset($post_data[ $post_label . "_born_month" ]))
		{
			update_field( "acf_target_born_month" , $post_data[ $post_label . "_born_month" ] , $sheet_id);//誕生日月
		}

		if(isset($post_data[ $post_label . "_born_day" ]))
		{
			update_field( "acf_target_born_day" , $post_data[ $post_label . "_born_day" ] , $sheet_id);//誕生日日
		}
	}


	/****************************************************
	** 実行日で会員の入力シートの締め切りを過ぎているかどうか
	******************************************************/
	public function checkInpuExecutionDateAfter( $execution_date )
	{

		if($execution_date == "")
		{
			return 1000;//入ってないので期日なし
		}


		date_default_timezone_set('Asia/Tokyo');

		$specifiedDate = new DateTime($execution_date); // 指定した日付
		$today = new DateTime(); // 今日の日付
		// 時刻をリセット（00:00:00 にする）
		$specifiedDate->setTime(0, 0, 0);
		$today->setTime(0, 0, 0);

		$interval = $today->diff($specifiedDate); // 差分を計算
		
		return $interval->days;

	}


	/****************************************************
	** 会員質問入力の入力可能かどうか
	******************************************************/
	public function checkInputQuestionSheet( $status , $execution_date  )
	{


		$limit_array = array();


		$limit_array["入力"] = 0;
		$limit_array["日数"] = "";


		//入力可能の時のみ(管理者はいじれる)
		if($status == SpiritUserClass::MEMBER_STATUS_NOT_INFOMATION || $status == SpiritUserClass::MEMBER_STATUS_RE_INFOMATION || current_user_can('administrator')){

			$after_day = $this->checkInpuExecutionDateAfter( $execution_date );

			if($after_day > 0) //実行日内
			{
				$limit_array["入力"] = 1;
				$limit_array["日数"] = $after_day;
			}
		}

		return $limit_array;
	}
	/****************************************************
	** 会員質問入力シートが見れるかどうか（編集できない）
	******************************************************/
	public function checkConfirmationQuestionSheet( $status , $execution_date )
	{
		//入金待ちは見れない
		if($status == SpiritUserClass::MEMBER_STATUS_WATING_PAYMENT){ //入金待ち
			return false;
		}


		//キャンセルは見れない
		if($status == SpiritUserClass::MEMBER_STATUS_CANCEL){ //キャンセル
			return false;
		}

		$input_sheet = $this->checkInputQuestionSheet( $status , $execution_date );

		//入力シートが入力状態だと見れない
		if($input_sheet["入力"] == 1)
		{
			return false;
		}

		return true;

	}



	/****************************************************
	** 会員結果が見れるかどうか
	******************************************************/
	public function checkResultSheet( $status  )
	{
		//完了
		if($status == SpiritUserClass::MEMBER_STATUS_COMPLETE){ //完了
			return true;
		}


		//発送完了
		if($status == SpiritUserClass::MEMBER_STATUS_SHIPMENT_COMPLETE){ 
			return true;
		}

		
		return false;

	}

	/****************************************************
	** 会員表示名()
	******************************************************/
	public function dispMemberStatus( $status  )
	{
		switch($status)
		{
			case SpiritUserClass::MEMBER_STATUS_WATING_PAYMENT:
					return "入金待ち";
			case SpiritUserClass::MEMBER_STATUS_NOT_INFOMATION:
					return "情報待機中";
			case SpiritUserClass::MEMBER_STATUS_CONFIRMATION_INFOMATION:
					return "情報確認中";
			case SpiritUserClass::MEMBER_STATUS_RE_INFOMATION:
					return "情報再入力";
			case SpiritUserClass::MEMBER_STATUS_CURRNTLY_REQUESTING:
					return "浄霊・施術依頼中";
			case SpiritUserClass::MEMBER_STATUS_CONFIRMED:
					return "日程確定";
			case SpiritUserClass::MEMBER_STATUS_COMPLETE:
					return "完了";
			case SpiritUserClass::MEMBER_STATUS_CANCEL:
					return "キャンセル";
			case SpiritUserClass::MEMBER_STATUS_SHIPPING_PREPARATION:
					return "発送準備中";
			case SpiritUserClass::MEMBER_STATUS_SHIPMENT_COMPLETE:
					return "発送完了";
			case SpiritUserClass::MEMBER_STATUS_SHIPMENT_CONFIRMATION:
					return "発送確認中";
		}
		
		
		return "";

	}

    /****************************************************
	**  郵送先住所の登録
	******************************************************/
	public function saveUserPostAddress($user_id,$postData)
	{

		//同じ保存ユニックタイムがあるなら保存しない
		$check_unixtime = get_posts(array(
			'post_type' => 'cpt_post_address',
			'meta_query' => array(
				array(
					'key' => 'acf_post_unixtime',
					'value' => $postData["acf_post_unixtime"],
				)
			)
		));

		if(count($check_unixtime) > 0){
			return "";
		}

		$wp_query = new WP_Query();
     
        $my_post = array(
            'post_title' => $postData["acf_post_billing_first_name"],
            'post_type' => 'cpt_post_address', //カスタム投稿タイプの名称を入れる
            'post_status' => 'publish',
            'post_author' => $user_id,
        );

        $program_id = wp_insert_post($my_post);

        //作成できたので番号をタイプに紐づける
		if($program_id){
			update_field("acf_post_billing_postcode",$postData["acf_post_billing_postcode"],$program_id);
			update_field("acf_post_billing_city",$postData["acf_post_billing_city"],$program_id);
			update_field("acf_post_billing_address_1",$postData["acf_post_billing_address_1"],$program_id);
			update_field("acf_post_billing_first_name",$postData["acf_post_billing_first_name"],$program_id);
			update_field("acf_post_unixtime",$postData["acf_post_unixtime"],$program_id);
			update_field("acf_post_user_id",$user_id,$program_id);
		}

		return $program_id;


	}

	/****************************************************
	**  郵送先住所の編集
	******************************************************/
	public function editUserPostAddress($post_id,$postData)
	{

		update_field("acf_post_billing_postcode",$postData["acf_post_billing_postcode"],$post_id);
		update_field("acf_post_billing_city",$postData["acf_post_billing_city"],$post_id);
		update_field("acf_post_billing_address_1",$postData["acf_post_billing_address_1"],$post_id);
		update_field("acf_post_billing_first_name",$postData["acf_post_billing_first_name"],$post_id);
	}

	/****************************************************
	**  郵送先住所の情報取得
	******************************************************/
	public function getUserPostAddressArray($post_id)
	{

		$post_data = array();

		if($post_id != ""){
			$post_data["郵送先郵便番号"] = get_field("acf_post_billing_postcode",$post_id);
			$post_data["郵送先住所1"] = get_field("acf_post_billing_city",$post_id);
			$post_data["郵送先住所2"] = get_field("acf_post_billing_address_1",$post_id);
			$post_data["郵送先名前"] = get_field("acf_post_billing_first_name",$post_id);
			$post_data["メイン"] = get_field("acf_post_main",$post_id);
		}else{
			$post_data["郵送先郵便番号"] = "";
			$post_data["郵送先住所1"] = "";
			$post_data["郵送先住所2"] = "";
			$post_data["郵送先名前"] = "";
			$post_data["メイン"] = "";
		}

		return $post_data;

	}

	/****************************************************
	**  郵送先住所の一覧取得
	******************************************************/
	public function getUserPostAddressList($user_id)
	{

		//cpt_post_addressのacf_post_user_idが$user_idのものだけ取得
		$post_list = get_posts(array(
			'post_type' => 'cpt_post_address',
			'meta_query' => array(
				array(
					'key' => 'acf_post_user_id',
					'value' => $user_id,
				)
			)
		));

		$post_array = array();

		foreach($post_list as $post){
			$post_array[$post->ID] = $this->getUserPostAddressArray($post->ID);
		}

		return $post_array;


	}

	/****************************************************
	**  郵送先メインを設定する
	******************************************************/
	public function setUserPostAddressMain($main_id,$user_id)
	{

		//cpt_post_addressのacf_post_user_idが$user_idのものだけ取得
		$post_list = get_posts(array(
			'post_type' => 'cpt_post_address',
			'meta_query' => array(
				array(
					'key' => 'acf_post_user_id',
					'value' => $user_id,
				)
			)
		));

		$post_array = array();

		foreach($post_list as $post){

			if($post->ID == $main_id){
				update_field("acf_post_main","1",$post->ID);
			}else{
				update_field("acf_post_main","",$post->ID);
			}
		}
	}

	/****************************************************
	**  荒見シートで会員ステータスがその値だと、基本的には選択できない
	******************************************************/
    public function getMemberAramiStatusCanSelect($status) {


		if($status == "") {
			return true;
		}

		//作成中
        if($status == self::MEMBER_STATUS_CREATE) {
            return true;
        }


		//入金待ち
        if($status == self::MEMBER_STATUS_WATING_PAYMENT) {
            return true;
        }

		//情報未入力
        if($status == self::MEMBER_STATUS_NOT_INFOMATION) {
            return true;
        }

		//必要事項再入力
        if($status == self::MEMBER_STATUS_RE_INFOMATION) {
            return true;
        }

		//完了
        if($status == self::MEMBER_STATUS_COMPLETE) {
            return true;
        }

		//キャンセル
        if($status == self::MEMBER_STATUS_CANCEL) {
            return true;
        }

		//発送準備
        if($status == self::MEMBER_STATUS_SHIPPING_PREPARATION) {
            return true;
        }

		//発送完了
        if($status == self::MEMBER_STATUS_SHIPMENT_COMPLETE) {
            return true;
        }

		//発送確認中
        if($status == self::MEMBER_STATUS_SHIPMENT_CONFIRMATION) {
            return true;
        }




        return false;
    }

	/****************************************************
	**  荒見シートで会員ステータスを変更する際に変更できるものを制限する
	******************************************************/
    public function getMemberAramiStatusCanChange($status) {

		
		//作成中
        if($status == self::MEMBER_STATUS_CREATE) {
            return true;
        }

		//入金待ち
        if($status == self::MEMBER_STATUS_WATING_PAYMENT) {
            return true;
        }

		//情報未入力
        if($status == self::MEMBER_STATUS_NOT_INFOMATION) {
            return true;
        }

		//必要事項再入力
        if($status == self::MEMBER_STATUS_RE_INFOMATION) {
            return true;
        }

		
		

		//発送準備
        if($status == self::MEMBER_STATUS_SHIPPING_PREPARATION) {
            return true;
        }

		//発送完了
        if($status == self::MEMBER_STATUS_SHIPMENT_COMPLETE) {
            return true;
        }

		//発送確認中
        if($status == self::MEMBER_STATUS_SHIPMENT_CONFIRMATION) {
            return true;
        }




        return false;

	}



	/****************************************************
	**  会員情報申請中のユーザーを取得する
	******************************************************/
    public function getMembershipInformationUser() {

		$users = get_users(array(
			'meta_query' => array(
				array(
					'key' => 'user_date_complete',
					'value' => '1',
				)
			)
		));

		return $users;



	}


}









?>


