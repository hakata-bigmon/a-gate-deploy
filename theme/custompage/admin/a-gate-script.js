// 郵便番号取得
function getAddress() {
    var zipcode = document.getElementById('zipcode').value;
    if (!zipcode) {
        alert("郵便番号を入力してください");
        return;
    }
    
    $.ajax({
        url: "https://zipcloud.ibsnet.co.jp/api/search?zipcode=" + zipcode,
        dataType: "jsonp",
        success: function(response) {
            if (response.status === 200 && response.results) {
                var result = response.results[0];
                var address = result.address1 + result.address2 + result.address3;
                // document.getElementById('address').value = "'" + address + "'";
                document.getElementById('address').value = address;
            } else {
                alert("住所が見つかりませんでした");
            }
        },
        error: function() {
            alert("住所の取得に失敗しました");
        }
    });
}




// ユーザー情報チェック
function check_user(){

    const regex = /^[a-zA-Z0-9_.+-]+@([a-zA-Z0-9][a-zA-Z0-9-]*[a-zA-Z0-9]*\.)+[a-zA-Z]{2,}$/;   // メアドチェック
    var check_ok = true;
    
    // 入力チェック
    var disp_moji = "";
    // var data = <?php //echo json_encode($check_data);?>;
    // var u_id = document.getElementsByName('input_user_id')[0].value;
    var u_mal = document.getElementsByName('input_user_email')[0].value;
    // var u_pass = document.getElementsByName('input_user_pass')[0].value;
    var tel1 = document.getElementsByName('input_tel_1')[0].value;
    var tel2 = document.getElementsByName('input_tel_2')[0].value;
    var tel3 = document.getElementsByName('input_tel_3')[0].value;
    var tel_none = document.getElementsByName('check_phone_none')[0].value;

    var emailInput = document.getElementById("input_must_mail");
    // LINE IDの入力フィールド
    var lineInput = document.getElementById("input_must_line");

    
    // LINE mail入力チェック
    // 入力フィールドが空の場合にコンソールにメッセージを表示する
    if (!emailInput.value && !lineInput.value) {
        alert("メールアドレスかLINE IDを入力してください");
        check_ok = false;
    }else if(!regex.test(u_mal) && !lineInput.value ){
        // LINE ID未設定　かつ　メアド入力アリ
        alert("メールアドレスが正しく入力されていません");
        check_ok = false;
    }else if (!tel1.value && !tel2.value && !tel3.value && !tel_none) {
        alert("電話番号を正しく入力してください");
        check_ok = false;
        
    // }else if(!u_pass.match(/^(?=.*?[a-z])(?=.*?[A-Z])(?=.*?\d)[a-zA-Z\d]{6,100}$/)) {
    //     alert("パスワードが6文字以上で大文字を含む半角英数字で入力されていません");
    }else{

        // 既存チェック
        // for(var i = 0; i < data.length ; i++){
        //     if(u_id == data[i]['user_login']){
        //         alert("アカウントが被っています。");
        //         return;
        //     }else if (u_mal == data[i]['user_email']){
        //         alert("メールアドレスが被っています。");
        //         return;
        //     }
        // }
        
        // document.post_new_user_input.submit();  //form名をdocumentの後ろに付けてsubmit
        
    }

    if(!check_ok) return false;



}

// フォームアクション変更
function changeFormActionAndSubmit() {
    // フォームのaction属性を変更
    var form = document.getElementById('post_new_user_input');
    form.action = "<?php echo getURLSetSlag('admin-member-list').'?introduction_member=on'; ?>";
    
    // フォームを送信
    form.submit();
}

// モーダル
function click_modal(moji, form_id) {

    var modal = document.getElementById("modal");
    var scrollY = window.scrollY || window.pageYOffset; // 現在のスクロール位置を取得

    // 画面中央に配置するために必要な調整値を計算
    modal.style.top = scrollY + "px"; // topの値を調整

    document.getElementById("disp-text").innerText = moji;
    modal.style.display = "block";

    $('.js-modal').fadeIn();

    $('.js-modal-ok').on('click', function () {
        $('.js-modal').fadeOut();
        var form = document.getElementById(form_id);
        form.submit();
    });

    $('.js-modal-back').on('click', function () {
        $('.js-modal').fadeOut();
    });
}
function ret_click_modal(moji) {

    return new Promise((resolve, reject) => {
        var modal = document.getElementById("modal");
        var scrollY = window.scrollY || window.pageYOffset; // 現在のスクロール位置を取得

        // 画面中央に配置するために必要な調整値を計算
        modal.style.top = scrollY + "px"; // topの値を調整

        document.getElementById("disp-text").innerText = moji;
        modal.style.display = "block";

        $('.js-modal').fadeIn();

        // OKボタンが押されたとき
        $('.js-modal-ok').on('click', function () {
            $('.js-modal').fadeOut();
            resolve(true);  // Promiseを解決して`true`を返す
        });

        // 戻るボタンが押されたとき
        $('.js-modal-back').on('click', function () {
            $('.js-modal').fadeOut();
            resolve(false);  // Promiseを解決して`false`を返す
        });
    });
}

// 郵便番号取得
function getAddress() {
    var zipcode = document.getElementById('zipcode').value;
    if (!zipcode) {
        alert("郵便番号を入力してください");
        return;
    }
    
    $.ajax({
        url: "https://zipcloud.ibsnet.co.jp/api/search?zipcode=" + zipcode,
        dataType: "jsonp",
        success: function(response) {
            if (response.status === 200 && response.results) {
                var result = response.results[0];
                var address = result.address1 + result.address2 + result.address3;
                // document.getElementById('address').value = "'" + address + "'";
                document.getElementById('address').value = address;
            } else {
                alert("住所が見つかりませんでした");
            }
        },
        error: function() {
            alert("住所の取得に失敗しました");
        }
    });
}

//保存せず進む確認
async function goCheck(url) {
    // ret_click_modalが完了するまで待機
    let res = await ret_click_modal('保存ボタンを押さない場合は入力した内容が\n削除されるので注意してください。');
    console.log(res);

    // OKが押された場合、次の処理に進む
    if (res) {
        location.href = url;
    }
}


// 入力変更チェック
function chengeCheck(url){
    
    // // フォーム内のすべての入力フィールドの初期値を保存
    // const inputs = document.querySelectorAll('#post_change_user_input input[type="text"]');
    // const initialValues = {};

    // inputs.forEach(input => {
    //     initialValues[input.name] = input.value;
    // });


    document.getElementById('form-back').addEventListener('click', function() {
        let hasChanged = false;

        // 入力フィールドの現在の値と初期値を比較
        inputs.forEach(input => {
            if (input.value.trim() !== initialValues[input.name].trim()) {
                hasChanged = true;
            }
        });

        
        console.log(hasChanged);

        // 値が変更されていればアラートを表示
        if (hasChanged) {
            document.getElementById('modal').style.display = 'block';
            document.getElementById('moda-back').innerHTML = '入力に戻る';

            goCheck(url);
            
        // }else{
        //     location.href = url;

        }

    });
}