<?php
ob_start();
require_once ("a-gate-functions.php");
require_once(ABSPATH . 'wp-admin/includes/user.php');

// スクリプトの読み込みを先に行う
wp_enqueue_script('jquery');
wp_enqueue_media();

// 管理者権限のチェック
if (!current_user_can('manage_options')) {
    wp_die('このページにアクセスする権限がありません。');
}

// ユーザーIDが指定されている場合は編集モード
$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;
$user = $user_id ? get_user_by('ID', $user_id) : null;

// フォーム送信時の処理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize_user($_POST['username']);
    $email = sanitize_email($_POST['email']);
    $display_name = sanitize_text_field($_POST['display_name']);
    $phone = sanitize_text_field($_POST['phone']);
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];

    if ($user_id) {
        // 既存ユーザーの更新
        $userdata = array(
            'ID' => $user_id,
            'user_login' => $username,
            'user_email' => $email,
            'display_name' => $display_name
        );
        if (!empty($password)) {
            if ($password !== $password_confirm) {
                $error_message = 'パスワードが一致しません。';
            } else {
                $userdata['user_pass'] = $password;
            }
        }
        wp_update_user($userdata);
        update_user_meta($user_id, 'phone', $phone);
        update_field('acf_teacher_explanation', $_POST['acf_teacher_explanation'], 'user_' . $user_id);
        update_field('acf_teacher_profile_transfer_destination', $_POST['acf_teacher_profile_transfer_destination'], 'user_' . $user_id);
        if (!empty($_POST['acf_teacher_profile_img'])) {
            update_field('acf_teacher_profile_img', $_POST['acf_teacher_profile_img'], 'user_' . $user_id);
        }
    } else {
        // 新規ユーザーの作成
        if ($password !== $password_confirm) {
            $error_message = 'パスワードが一致しません。';
        } else {
            $userdata = array(
                'user_login' => $username,
                'user_email' => $email,
                'display_name' => $display_name,
                'user_pass' => $password,
                'role' => 'author'
            );
            $new_user_id = wp_insert_user($userdata);
            if (!is_wp_error($new_user_id)) {
                update_user_meta($new_user_id, 'phone', $phone);
                update_field('acf_teacher_explanation', $_POST['acf_teacher_explanation'], 'user_' . $new_user_id);
                update_field('acf_teacher_profile_transfer_destination', $_POST['acf_teacher_profile_transfer_destination'], 'user_' . $new_user_id);
                if (!empty($_POST['acf_teacher_profile_img'])) {
                    update_field('acf_teacher_profile_img', $_POST['acf_teacher_profile_img'], 'user_' . $new_user_id);
                }
            }
        }
    }

    if (!isset($error_message)) {
        // 処理後は一覧ページにリダイレクト
        echo '<script>window.location.href = "' . getURLSetSlag('admin-make-consultation-user-list') . '";</script>';
        exit;
    }
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
?>

<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/custompage/admin/css/admin.css">

<div class="admin-user-edit-area">
    <div class="admin-title">
        <?php echo $user_id ? "相談者編集" : "新規相談者作成"; ?>
    </div>

    <div class="admin-spirit-sheets-button-flex">
        <div class="admin-preview-button-flex">
            <div class="admin-preview-button-flex-box">
                <button class="admin-preview-button" type="button" onclick="location.href='<?php echo getURLSetSlag('admin-make-consultation-user-list'); ?>'" style="background-color: #D6E9F5;">戻る</button>
            </div>
        </div>
    </div>

    <?php if (isset($error_message)) : ?>
        <div class="error-message" style="color: red; margin: 10px 0; text-align: center;">
            <?php echo $error_message; ?>
        </div>
    <?php endif; ?>

    <form method="post" class="admin-user-edit-form" onsubmit="return validateForm(this);">
        <div class="admin-user-edit-form-item">
            <label for="username">ユーザーネーム<span class="required">*</span></label>
            <input type="text" id="username" name="username" value="<?php echo $user ? esc_attr($user->user_login) : ''; ?>" required pattern="^[a-zA-Z0-9@._-]+$" onkeyup="this.value=this.value.replace(/[^a-zA-Z0-9@._-]/g,'')">
            <p class="admin-form-note">※半角英数字、@、.、-、_が使用できます</p>
        </div>

        <div class="admin-user-edit-form-item">
            <label for="email">メールアドレス<span class="required">*</span></label>
            <input type="email" id="email" name="email" value="<?php echo $user ? esc_attr($user->user_email) : ''; ?>" required>
        </div>

        <div class="admin-user-edit-form-item">
            <label for="display_name">名前<span class="required">*</span></label>
            <input type="text" id="display_name" name="display_name" value="<?php echo $user ? esc_attr($user->display_name) : ''; ?>" required>
        </div>

        <div class="admin-user-edit-form-item">
            <label for="password"><?php echo $user_id ? 'パスワード（変更する場合のみ入力）' : 'パスワード<span class="required">*</span>'; ?></label>
            <div class="input-wrapper">
                <input type="password" id="password" name="password" <?php echo $user_id ? '' : 'required'; ?>>
                <span class="password-toggle" onmousedown="togglePassword(this, 'password')" onmouseup="togglePassword(this, 'password')" onmouseleave="togglePassword(this, 'password')">👁️</span>
            </div>
            <?php if ($user_id) : ?>
                <p class="admin-form-note">※変更する場合のみ入力してください</p>
            <?php endif; ?>
        </div>

        <div class="admin-user-edit-form-item">
            <label for="password_confirm"><?php echo $user_id ? 'パスワード（確認用）' : 'パスワード（確認用）<span class="required">*</span>'; ?></label>
            <div class="input-wrapper">
                <input type="password" id="password_confirm" name="password_confirm" <?php echo $user_id ? '' : 'required'; ?>>
                <span class="password-toggle" onmousedown="togglePassword(this, 'password_confirm')" onmouseup="togglePassword(this, 'password_confirm')" onmouseleave="togglePassword(this, 'password_confirm')">👁️</span>
            </div>
            <?php if ($user_id) : ?>
                <p class="admin-form-note">※変更する場合のみ入力してください</p>
            <?php endif; ?>
        </div>
        <div class="admin-user-edit-form-item">
            <label for="phone">電話番号</label>
            <input type="tel" id="phone" name="phone" value="<?php echo $user ? esc_attr(get_user_meta($user->ID, 'phone', true)) : ''; ?>">
        </div>


        <div class="admin-user-edit-form-item">
            <label for="acf_teacher_profile_img">プロフィール写真</label>
            <div class="image-preview-container">
                <?php 
                $image_id = $user ? get_field('acf_teacher_profile_img', 'user_' . $user->ID) : '';
                if ($image_id) {
                    $image_url = wp_get_attachment_image_url($image_id, 'medium');
                    if (!$image_url) {
                        $image_url = $image_id["url"]; // URLが直接保存されている場合
                    }
                } else {
                    $image_url = '';
                }
                
                
                ?>
                <input type="hidden" id="acf_teacher_profile_img" name="acf_teacher_profile_img" value="<?php echo esc_attr($image_id); ?>">
                <div id="profile-image-preview" style="margin: 10px 0;">
                    <?php if ($image_url) : ?>
                        <img src="<?php echo esc_url($image_url); ?>" style="max-width: 200px; height: auto;">
                    <?php endif; ?>
                </div>
                <button type="button" class="admin-preview-button" onclick="selectProfileImage()" style="background-color: #D6E9F5;">画像を選択</button>
                <button type="button" class="admin-preview-button" onclick="removeProfileImage()" style="background-color: #ffcccc; margin-left: 10px;">画像を削除</button>
            </div>
        </div>
        <div class="admin-user-edit-form-item">
            <label for="acf_teacher_explanation">プロフィール説明</label>
            <textarea id="acf_teacher_explanation" name="acf_teacher_explanation" rows="10" style="width: 100%;"><?php echo $user ? esc_textarea(get_field('acf_teacher_explanation', 'user_' . $user->ID)) : ''; ?></textarea>
        </div>

        <div class="admin-user-edit-form-item">
            <label for="acf_teacher_profile_transfer_destination">振込先</label>
            <textarea id="acf_teacher_profile_transfer_destination" name="acf_teacher_profile_transfer_destination" rows="10" style="width: 100%;"><?php echo $user ? esc_textarea(get_field('acf_teacher_profile_transfer_destination', 'user_' . $user->ID)) : ''; ?></textarea>
        </div>
        

        

        <div class="admin-user-edit-form-submit">
            <button type="submit" class="admin-preview-button"><?php echo $user_id ? '更新' : '作成'; ?></button>
            <?php if ($user_id) : ?>
                <button type="button" class="admin-preview-button" onclick="if(confirm('この相談者を削除してもよろしいですか？\n\n※この操作は取り消せません。')) location.href='<?php echo getURLSetSlag('admin-make-consultation-user-list'); ?>?delete_user_id=<?php echo $user_id; ?>'" style="background-color: #ff4444; margin-left: 10px;">削除</button>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- jQueryの直接読み込み -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function validateForm(form) {
    // 確認ダイアログの表示
    if (!confirm('<?php echo $user_id ? 'このユーザー情報を更新してもよろしいですか？' : '新しい相談者を作成してもよろしいですか？'; ?>')) {
        return false;
    }

    // ユーザーネームのバリデーション
    const userLogin = form.username.value;
    if (!/^[a-zA-Z0-9@._-]+$/.test(userLogin)) {
        alert('ユーザーネームは半角英数字、@、.、-、_のみ使用できます。');
        return false;
    }

    return true;
}

function togglePassword(element, inputName) {
    const input = document.querySelector(`input[name="${inputName}"]`);
    if (event.type === 'mousedown') {
        input.type = 'text';
        element.style.opacity = '0.6';
    } else {
        input.type = 'password';
        element.style.opacity = '1';
    }
}

// メディアアップローダー関連の関数
function selectProfileImage() {
    // 既存のメディアアップローダーを開く
    var frame = wp.media({
        title: 'プロフィール写真を選択',
        button: {
            text: '選択'
        },
        multiple: false
    });

    // 画像が選択されたときの処理
    frame.on('select', function() {
        var attachment = frame.state().get('selection').first().toJSON();
        document.getElementById('acf_teacher_profile_img').value = attachment.id;
        var preview = document.getElementById('profile-image-preview');
        preview.innerHTML = '<img src="' + attachment.url + '" style="max-width: 200px; height: auto;">';
    });

    frame.open();
}

function removeProfileImage() {
    document.getElementById('acf_teacher_profile_img').value = '';
    document.getElementById('profile-image-preview').innerHTML = '';
}
</script>
