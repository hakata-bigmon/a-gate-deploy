
<?php 

    require_once ("a-gate-functions.php");
    require_once (dirname(__FILE__)."/../../class/spiritInputCustomizeClass.php");


    $spirit_sheet_data = new SpiritInputCustomizeClass(); //管理データ



    $question_num = 0;
    $post_id = 0;

    if(isset($_GET["type_id"]))
    {
        $question_num = $_GET["type_id"];
    }

    if(isset($_GET["post_id"]))
    {
        $post_id = $_GET["post_id"];
    }


    

   

    //項目データ
    $customize_data = $spirit_sheet_data->getPersonalDataInput( $question_num );


     //保存
    if(isset($_POST["save_id"])){


        $personal_array = array();

        foreach ($customize_data as $key => $value) {

            if( !isset($personal_array[ $value["ID"] ]) )
            {
                $personal_array[ $value["ID"] ] = array();
            }
            
            //表示
            if( isset( $_POST[ "personal_" .$value["ID"] ] ) )
            {
                 $personal_array[ $value["ID"] ][0] = 1;
            }
            else{
                $personal_array[ $value["ID"] ][0] = "";
            }


            //対象者と申込者が別の場合は対象者ありにする(保存先はタイプの方)
            if(isset($_POST["personal_490"]))
            {
                update_field(  "acf_pure_spirit_by_subject" , 1 , $question_num);
            }
            else{
               update_field(  "acf_pure_spirit_by_subject" , 0 , $question_num);
            }



            //必須
            if( isset( $_POST[ "personal_" .$value["ID"] ."_required" ] ) )
            {
                 $personal_array[ $value["ID"] ][1] = 1;
            }
            else{
                $personal_array[ $value["ID"] ][1] = "";
            }

            //識別コードでの表示
            if( isset( $_POST[ "personal_" .$value["ID"] ."_identification" ] ) )
            {
                 $personal_array[ $value["ID"] ][2] = 1;
            }
            else{
                $personal_array[ $value["ID"] ][2] = "";
            }
        

            /*
             //識別コードでの表示
            if( isset( $_POST[ "personal_" .$value["ID"] ."_member_only" ] ) )
            {
                 $personal_array[ $value["ID"] ][3] = 1;
            }
            else{
                $personal_array[ $value["ID"] ][3] = "";
            }
            */
          
        }

         // var_dump($personal_array);
        $spirit_sheet_data->saveInputCustomizPpersonalData( $_POST["save_id"] ,$personal_array);

    }

    //個人情報データ
    $personal_array = $spirit_sheet_data->getInputCustomizPpersonalData( $post_id );


?>





<div class="admin-exorcism-area">


    <div class="admin-exorcism-button-area">

        <div class="admin-exorcism-menu-title-box"><div class="admin-exorcism-menu-title" style="text-align: center;"><?php echo get_field('acf_pure_spirit_title',$question_num); ?>　個人情報入力設定</div></div>

        <div class="admin-inputcustomize-area">


             <form action="<?php echo  getURLSetSlag( "admin-input-personaldata" );?>?type_id=<?php echo $question_num;?>&post_id=<?php echo $post_id; ?>" name="customize_input" method="post" id="customize_input">


                

                <?php if(isset($_POST["save_id"])){ ?>


                    <div class="admin-inputcustomize-save-area">保存しました</div>

                <?php } ?>


                <table class="admin-input-personaldata-table">

                    <tr>
                        <th>項目</th>
                        <th>表示</th>
                        <th>必須</th>
                        <th>識別コード<br>での表示</th>
                    </tr>

                    <?php 
    
                        foreach ($customize_data as $key => $value) {
                    ?>
                        <?php if($key != "ID"){ ?>

                            <?php 
                                $style="";

                                if($value["acf_input_target"])
                                {
                                   continue;
                                }
                            
                            ?>


                            <tr style="<?php echo $style;?>">
                                <td style="font-weight: bold;"><?php echo $value["acf_input_personal_data_title"];?></td>
                                <td>
                                    <input type="checkbox"  name="personal_<?php echo $value["ID"]; ?>" <?php if( isset( $personal_array[ $value["ID"] ][0] ) ){ if( $personal_array[ $value["ID"] ][0] != "" ){echo "checked";}}?>/>
                                </td>
                                <td>
                                    <input type="checkbox"  name="personal_<?php echo $value["ID"]; ?>_required" <?php if( isset( $personal_array[ $value["ID"] ][1] ) ){ if( $personal_array[ $value["ID"] ][1] != "" ){echo "checked";}}?>/>
                                </td>
                                <td>
                                    <input type="checkbox"  name="personal_<?php echo $value["ID"]; ?>_identification" <?php if( isset( $personal_array[ $value["ID"] ][2] ) ){ if( $personal_array[ $value["ID"] ][2] != "" ){echo "checked";}}?>/>
                                </td>
                               
                            </tr>

                        <?php } ?>


                    <?php } ?>
                    
                </table>



                <table class="admin-input-personaldata-table">
                    
                     <tr>
                        <th>対象者　項目</th>
                        <th>表示</th>
                        <th>必須</th>
                        <th>識別コード<br>での表示</th>
                    </tr>

                    <?php 
    
                        foreach ($customize_data as $key => $value) {
                    ?>
                        <?php if($key != "ID"){ ?>

                            <?php 
                                $style="";

                                if($value["acf_input_target"] != "")
                                {
                                    
                                }
                                else{
                                     continue;
                                }
                            
                            ?>


                             <tr style="<?php echo $style;?>">
                                <td style="font-weight: bold;"><?php echo $value["acf_input_personal_data_title"];?></td>
                                <td>
                                    <input type="checkbox"  name="personal_<?php echo $value["ID"]; ?>" <?php if( isset( $personal_array[ $value["ID"] ][0] ) ){ if( $personal_array[ $value["ID"] ][0] != "" ){echo "checked";}}?>/>
                                </td>
                                <td>
                                    <input type="checkbox"  name="personal_<?php echo $value["ID"]; ?>_required" <?php if( isset( $personal_array[ $value["ID"] ][1] ) ){ if( $personal_array[ $value["ID"] ][1] != "" ){echo "checked";}}?>/>
                                </td>
                                <td>
                                    <input type="checkbox"  name="personal_<?php echo $value["ID"]; ?>_identification" <?php if( isset( $personal_array[ $value["ID"] ][2] ) ){ if( $personal_array[ $value["ID"] ][2] != "" ){echo "checked";}}?>/>
                                </td>
                            </tr>

                        <?php } ?>


                    <?php } ?>
                    
                </table>


                 <input type="hidden" name="type_id" id="type_id" value="<?php echo $question_num;?>" />
                 <input type="hidden" name="save_id" id="save_id" value="<?php echo $post_id;?>" />

                 <div class="admin-inputcustomize-submit-area">
                    <input class="admin-spirit-submit-button" type="submit" value="保　存" onclick=""/>
                </div>

             </form>



             



             <div class="admin-spirit-return-button-area">
                <button type=“button” class="admin-spirit-return-button" onclick="location.href='<?php echo  getURLSetSlag( "admin-input-text" );?>?type_id=<?php echo $question_num;?>'">戻る</button>
             </div>

         </div>

    </div>


 </div>

