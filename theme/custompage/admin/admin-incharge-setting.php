<?php 

    require_once ("a-gate-functions.php");
    require_once (dirname(__FILE__)."/../../class/spiritTypeClass.php");
   

// POSTデータの処理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['save_settings'])) {
        // 全ユーザーのチェックボックスをリセット
        $all_users = get_users(array(
            'role__in' => array('administrator', 'editor', 'author'),
            'exclude' => array(1),
            'fields' => array('ID')
        ));
        
        foreach ($all_users as $user) {
            update_user_meta($user->ID, 'explanation_types', array());
        }

        // チェックされたものを保存
        if (isset($_POST['user_settings'])) {
            foreach ($_POST['user_settings'] as $user_id => $types) {
                update_user_meta($user_id, 'explanation_types', $types);
            }
        }
        $success_message = '設定を保存しました。';
    }
}

// 全ユーザーの取得（管理者、編集者、投稿者）
$users = get_users(array(
    'role__in' => array('administrator', 'editor', 'author'),
    'exclude' => array(1), // ID:1のユーザーを除外
    'orderby' => 'ID',
    'order' => 'ASC'
));

$spiritTypeData = new SpiritTypeClass(); //浄霊タイプデータ
    

//浄霊タイプの相談のものだけ取得する
$explanation_type_array = array();

//浄霊タイプ
$spiritType = $spiritTypeData->getSpiritType();

foreach($spiritType as $spiritType_key => $spiritType_value){
    if( $spiritType_key == SpiritTypeClass::SPIRIT_TYPE_NAME_SODAN){ 

        foreach($spiritType_value as $key => $value){
            $explanation_type_array[ $value["ID"]] = $value;
        }
    }
}


?>
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/custompage/admin/css/admin.css">

<div class="admin-user-table-area">
    <div class="admin-title">
        担当設定
    </div>

    <?php if (isset($success_message)) : ?>
        <div class="success-message" style="color: green; margin: 10px 0; text-align: center;">
            <?php echo $success_message; ?>
        </div>
    <?php endif; ?>

    <form method="post">
        <table class="wp-list-table widefat fixed striped" style="border-collapse: collapse;">
            <thead>
                <tr>
                    <th style="border: 1px solid #ddd; width: 50px;">ID</th>
                    <th style="border: 1px solid #ddd; width: 150px;">名前</th>
                    <th style="border: 1px solid #ddd; width: 100px;">権限</th>
                    <?php foreach ($explanation_type_array as $type) : ?>
                        <th style="border: 1px solid #ddd; font-size: 0.85em; line-height: 1.2; width: 100px; white-space: normal; word-wrap: break-word; padding: 8px;"><?php echo esc_html($type['title']); ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user) : 
                    $user_types = get_user_meta($user->ID, 'explanation_types', true);
                    if (!is_array($user_types)) {
                        $user_types = array();
                    }
                    
                    // ユーザーの権限を日本語に変換
                    $role = '';
                    if (in_array('administrator', $user->roles)) {
                        $role = '<span style="color: red;font-weight: bold;">管理者</span>';
                    } elseif (in_array('editor', $user->roles)) {
                        $role = '<span style="color: blue;font-weight: bold;">先生</span>';
                    } elseif (in_array('author', $user->roles)) {
                        $role = '<span style="color: green;font-weight: bold;">相談スタッフ</span>';
                    }
                ?>
                    <tr>
                        <td style="border: 1px solid #ddd;text-align: center;"><?php echo $user->ID; ?></td>
                        <td style="border: 1px solid #ddd;"><?php echo $user->display_name; ?></td>
                        <td style="border: 1px solid #ddd;text-align: center;"><?php echo $role; ?></td>
                        <?php foreach ($explanation_type_array as $key => $type) : ?>
                            <td style="border: 1px solid #ddd; text-align: center;">
                                <input type="checkbox" 
                                       name="user_settings[<?php echo $user->ID; ?>][]" 
                                       value="<?php echo $key; ?>"
                                       <?php checked(in_array($key, $user_types)); ?>>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div style="margin-top: 20px; text-align: center;">
            <button type="submit" name="save_settings" class="admin-preview-button" style="background-color: #D6E9F5;">
                保存
            </button>
        </div>
    </form>
</div>