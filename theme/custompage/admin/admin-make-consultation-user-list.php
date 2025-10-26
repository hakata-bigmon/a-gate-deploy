<?php
ob_start();
require_once ("a-gate-functions.php");
require_once(ABSPATH . 'wp-admin/includes/user.php');

// 管理者権限のチェック
if (!current_user_can('manage_options')) {
    wp_die('このページにアクセスする権限がありません。');
}

// 削除処理
if (isset($_GET['delete_user_id'])) {
    $delete_user_id = intval($_GET['delete_user_id']);
    if ($delete_user_id && $delete_user_id !== get_current_user_id()) {
        wp_delete_user($delete_user_id);
        echo '<script>window.location.href = "' . getURLSetSlag('admin-make-consultation-user-list') . '";</script>';
        exit;
    }
}

// 投稿者権限を持つユーザーを取得
$args = array(
    'role' => 'author',
    'orderby' => 'ID',
    'order' => 'ASC'
);
$users = get_users($args);
?>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">

<div class="admin-user-table-area">
    <div class="admin-title">
        <?php echo "電話相談スタッフ一覧"; ?>
    </div>

    <div style="font-size: 18px;font-weight: 600;color: red;">相談者を増やす際は顧客管理からプロフィール変更で相談者に変更してください</div>

    <div class="admin-temporary-registration-check-table">
        <table id="admin-user-table" class="display">
            <thead>
                <tr>
                    <th>ID</th>
                    <th style="width: 100px;"></th>
                    <th>ユーザーネーム</th>
                    <th>名前</th>
                    <th>メールアドレス</th>
                    <th>電話番号</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user) { ?>
                    <tr>
                        <td><?php echo $user->ID; ?></td>
                        <td>
                            <button class="admin-preview-button" type="button" onclick="location.href='<?php echo getURLSetSlag('admin-make-consultation-user'); ?>?user_id=<?php echo $user->ID; ?>'" style="width: 100px; background-color: #D6E9F5;">編集</button>
                        </td>
                        <td><?php echo $user->user_login; ?></td>
                        <td><?php echo $user->display_name; ?></td>
                        <td><?php echo $user->user_email; ?></td>
                        <td><?php echo get_user_meta($user->ID, 'phone', true); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#admin-user-table').DataTable({
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.13.4/i18n/ja.json"
            },
            "pageLength": 50,  // 1ページあたりの行数
            "lengthMenu": [50, 100, 200]  // 選択できる件数
        });
    });
</script>
