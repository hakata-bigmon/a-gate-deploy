<?php

	require_once ("a-gate-functions.php");

	require_once (dirname(__FILE__)."/../../class/spiritTypeClass.php");
	require_once (dirname(__FILE__)."/../../class/spiritSheetClass.php");
	require_once (dirname(__FILE__)."/../../class/spiritUserClass.php");

	$spiritSheet = new spiritSheetClass();


	$spiritStatusArray = $spiritSheet->getSpiritAdminStatusID('cpt_spirit_status');//管理ステータス
	$spiritUSerStatusArray = $spiritSheet->getSpiritAdminStatusID('cpt_spirit_usestatus');//会員ステータス

	$spiritType = new SpiritTypeClass(); //管理データ
	$spiritTypeArray = $spiritType->getSpiritTypeKeyTypeNum();



	$userClass = new SpiritUserClass();
	
	$arami_sheet_id = "";
	$arami_data = "";
	$spirit_sheet_list = "";


	

	if(isset($_POST["arami_sheet_id"]) || isset($_GET["arami_sheet_id"]))
	{
        if(isset($_GET["arami_sheet_id"]))
        {
            $arami_sheet_id = $_GET["arami_sheet_id"];
        }else{
            $arami_sheet_id = $_POST["arami_sheet_id"];
        }

		//新規保存
		if(isset($_POST["save_sheet"]))
		{
			$spiritSheet->editAramiSheet( $arami_sheet_id , $_POST );
		}
	
		
		$arami_data = $spiritSheet->setAramiSheet($arami_sheet_id);
		$spirit_sheet_list = $spiritSheet->getSpritApplicantSpiritType($arami_data["施術"]);
	}

   
  //var_dump($spirit_sheet_list);
?>

<!-- DataTables用のCSSとJavaScript -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
<script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

<style>
    #aramiSheetTable {
        border-collapse: collapse;
        width: 100%;
		padding-top: 13px;
    }
    #aramiSheetTable th,
    #aramiSheetTable td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: center;
    }
    #aramiSheetTable thead th {
        background-color: #f5f5f5;
        border: 1px solid #ddd;
    }
    #aramiSheetTable tbody tr:nth-child(even) {
        background-color: #f9f9f9;
    }
    #aramiSheetTable tbody tr:hover {
        background-color: #f5f5f5;
    }
    
</style>

<div class="admin-user-table-area">

	
	<div class="admin-title" style="margin-bottom: 15px;">
		<?php echo "粗見用シート詳細（一括編集）"; ?>
	</div>

	<div class="admin-preview-button-flex" style="justify-content: left;">
        

        <div class="admin-preview-button-flex-box">
            <button class="admin-preview-button" type="button" onclick="location.href='<?php echo getURLSetSlag('admin-arami-sheet-detail'); ?>?sheet_id=<?php echo $arami_sheet_id; ?>&detail=true'" style="color: black;background-color: lemonchiffon;width: 200px;height: 30px;">粗見シート確認</button>
        </div>

        

        <div class="admin-preview-button-flex-box">
            <button class="admin-preview-button" type="button" onclick="location.href='<?php echo getURLSetSlag('admin-arami-sheet-detail'); ?>?sheet_id=<?php echo $arami_sheet_id; ?>'" style="color: black;background-color: lemonchiffon;width: 220px;height: 30px;">粗見シート詳細（個別編集）</button>
        </div>
        <?php if($arami_data["登録者"] != ""){ ?>
            <div class="admin-preview-button-flex-box">
                <button class="admin-preview-button" type="button" onclick="location.href='<?php echo getURLSetSlag('admin-arami-sheet-target-sort'); ?>?sheet_id=<?php echo $arami_sheet_id; ?>'" style="color: black;background-color: lemonchiffon;width: 200px;height: 30px;">対象者並べ替え</button>
            </div>
        <?php } ?>
        <div class="admin-preview-button-flex-box">
            <button class="admin-preview-button" type="button" onclick="location.href='<?php echo getURLSetSlag('admin-arami-sheet-list'); ?>'" style="color: black;background-color: aliceblue;width: 260px;height: 30px;">粗見用シート一覧に戻る</button>
        </div>
       
    </div>

	<div class="admin-arami-sheet-edit-area">
		<form action="<?php echo getURLSetSlag("admin-arami-sheet-edit"); ?>" method="post">
			<input type="hidden" name="arami_sheet_id" value="<?php echo $arami_sheet_id; ?>">
			<input type="hidden" name="save_sheet" value="<?php echo $arami_sheet_id; ?>">
			<input type="submit" value="更　新" style="width: 400px;height: 40px;border-radius: 15px;font-weight: bold;font-size: 18px;background-color: red;color: white;margin-top: 50px;margin-bottom: 30px;">

			<div class="user-table-flex">
				<div class="user-table-item" style="font-size: 24px;">シート名<font color="red">(必須)</font></div>
				<input type="text" name="acf_arami_title" value="<?php echo $arami_data["タイトル"];?>" style="width: 350px;" required>
			</div>

			<div class="user-table-flex">
				<div class="user-table-item" style="font-size: 24px;">施術</div>
				<?php echo $spiritTypeArray[$arami_data["施術"]]["title"];?>　　<font color="red">＊この情報は変更できません</font>
			</div>

			<div class="user-table-flex">
				<div class="user-table-item" style="font-size: 24px;">実行日</div>
				<input type="date" name="acf_arami_end_day" value="<?php echo $arami_data["実行日"];?>" style="">
				　　<input type="checkbox" name="acf_arami_end_day_all" value="1" <?php if($arami_data["実行日全適用"] == "1"){ ?>checked<?php }?>>シート内の全ての対象者に適用（追加選択者にも適用されます）
			</div>

			<div class="user-table-flex">
				<div class="user-table-item" style="font-size: 24px;">実行予定日</div>
				<input type="date" name="acf_arami_scheduled_execution_date" value="<?php echo $arami_data["実行予定日"];?>" style="">
				　　<input type="checkbox" name="acf_arami_scheduled_execution_date_all" value="1" <?php if($arami_data["実行予定日全適用"] == "1"){ ?>checked<?php }?>>シート内の全ての対象者に適用（追加選択者にも適用されます）
			</div>

			<div class="user-table-flex">
				<div class="user-table-item" style="font-size: 24px;">管理ステータス</div>
				<select name="acf_arami_sheet_status">
					<option value="">施術者の変更なし</option>
					<?php foreach($spiritStatusArray as $key => $value){ ?>
						<option value="<?php echo $key;?>" <?php if($arami_data["ステータス"] == $key){ ?>selected<?php }?>><?php echo $value["title"];?></option>
					<?php }?>
				</select>
				　　<input type="checkbox" name="acf_arami_status_all" value="1" <?php if($arami_data["管理全適用"] == "1"){ ?>checked<?php }?>>シート内の全ての対象者に適用（追加選択者にも適用されます）
			</div>

			<div class="user-table-flex">
				<div class="user-table-item" style="font-size: 24px;">会員ステータス</div>
				<select name="acf_arami_sheet_member_status">
					<option value="">施術者の変更なし</option>
					<?php foreach($spiritUSerStatusArray as $key => $value){ ?>

                        <?php if($userClass->getMemberAramiStatusCanChange($key))continue;?>

						<option value="<?php echo $key;?>" <?php if($arami_data["会員ステータス"] == $key){ ?>selected<?php }?>><?php echo $value["title"];?></option>
					<?php }?>
				</select>
				　　<input type="checkbox" name="acf_arami_sheet_member_status_all" value="1" <?php if($arami_data["会員全適用"] == "1"){ ?>checked<?php }?>>シート内の全ての対象者に適用（追加選択者にも適用されます）
			</div>

			
			<?php if(count($spirit_sheet_list) > 0){ ?>
				
				<div style="font-weight: 600;margin-top: 80px;font-size: 24px;text-align: center;color: black;">粗見シートに追加する施術者を選択してください</div>
				<div style="text-align: center;font-size: 18px;margin-top: 10px;font-weight: 600;">
					(選択人数: <span id="selectedCount"><?php echo $arami_data["登録者数"]; ?></span>人)
				</div>

				<div style="display: flex; align-items: center; gap: 16px; margin-bottom: 10px;margin-top: 50px;">
					<div>
						<label style="font-weight: 600;">依頼日：</label>
						<input type="date" id="requestDateStart" style="padding: 5px;">
						<span>～</span>
						<input type="date" id="requestDateEnd" style="padding: 5px;">
					</div>
					<div>
						<label style="font-weight: 600;">ステータス：</label>
						<select id="statusFilter" style="padding: 5px;">
							<option value="">全て</option>
						</select>
					</div>
					<div>
						<label style="font-weight: 600;">会員ステータス：</label>
						<select id="memberStatusFilter" style="padding: 5px;">
							<option value="">全て</option>
						</select>
					</div>
					<div style="margin-left: 20px;">
						<select id="filterSelect" style="padding: 5px;">
							<option value="all">全て表示</option>
							<option value="selectable">選択できるものを表示</option>
						</select>
					</div>
					
				</div>

				<div style="font-size: 14px;margin-top: 20px;font-weight: 600;color: red;">チェックが入っているものは絞り込んだ場合でも表示されます</div>
				<div class="arami-sheet-target-choice-area">
					<table id="aramiSheetTable" class="display">
						<thead>
							<tr>
								<th></th>
								<th>ID</th>
								<th>対象者</th>
								<th>実行日</th>
								<th>実行予定日</th>
								<th>申込者</th>
								<th>依頼日</th>
								<th>ステータス</th>
								<th>会員ステータス</th>
								<th>粗見シート</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($spirit_sheet_list as $key => $value) { ?>
								<?php  
									$sheet_data = $userClass->getUserSpritApplicantSheet(get_field('acf_purespirit_id',$value),$value);//ユーザー情報


									if($sheet_data == "")continue;

									//選択できない
									$can_select = false;//選択可能

									if($sheet_data[$value]["粗見シート"] == "" || $sheet_data[$value]["粗見シート"] == $arami_sheet_id)
									{
										$can_select = true;//選択不可
									}

                                    //会員ステータスが未設定、シート入力中は含めないようにする
                                    if($userClass->getMemberAramiStatusCanSelect($sheet_data[$value]["会員ステータス"]))
                                    {
                                        continue;//選択不可
                                    }
									
								?>
								<tr <?php if(!$can_select){ ?>style="background-color: #a9a9a9;" data-selectable="false"<?php }else{ ?>data-selectable="true"<?php }?>>
									<td>
										<?php //var_dump($sheet_data[$value]["対象者"]);?>

										<?php if($can_select){ ?>
											<input type="checkbox" name="arami_sheet_target_id[]" value="<?php echo $value;?>" <?php if(is_array($arami_data["登録者"]) && in_array($value, $arami_data["登録者"])){ ?>checked<?php }?>>
										<?php } ?>
											
									</td>
									<td><?php echo $sheet_data[$value]["ID"];?></td>
									<td>
										<?php if($sheet_data[$value]["対象者"]["対象者情報"] == false){ ?>
											<?php echo $sheet_data[$value]["フル名前"];?>
										<?php }else{ ?>
											<?php echo $sheet_data[$value]["対象者"]["フル名前"];?>

										<?php }?>
									</td>
									
									<td><?php echo $sheet_data[$value]["実行日年月日"];?></td>
									<td><?php echo $sheet_data[$value]["実行予定日年月日"];?></td>
									<td><?php echo $sheet_data[$value]["フル名前"];?></td>
									<td><?php echo $sheet_data[$value]["依頼日年月日"];?></td>
									<td><?php echo $sheet_data[$value]["管理者ステータス表示"];?></td>
									<td><?php echo $sheet_data[$value]["会員ステータス表示"];?></td>
									<td><?php if($sheet_data[$value]["粗見シート"] != ""){ echo get_field("acf_arami_title" , $sheet_data[$value]["粗見シート"]); }?></td>
									
									
								</tr>
							<?php }?>
						</tbody>
            		</table>

				</div>
			<?php }else{ ?>
				<div style="font-weight: 600;margin-top: 100px;font-size: 24px;text-align: center;color: black;">選択できるシートはありません</div>

			<?php } ?>
		</form>


		<div style="text-align: center;">
			<?php if($arami_data["登録者数"] == 0){ ?>
				<form action="<?php echo getURLSetSlag("admin-arami-sheet-list"); ?>" method="post">
					<input type="hidden" name="delete_sheet_id" value="<?php echo $arami_sheet_id; ?>">
					<input type="submit" value="削除する" style="" class="admin-arami-sheet-edit-delete-button" style="cursor: pointer;">
				</form>
			<?php }else{ ?> 
				<button type="button" class="admin-arami-sheet-edit-delete-off-button">削除する</button>
				
				<div style="font-weight: 600;color: black;">施術者がいる場合はシートを削除できません</div>
			<?php } ?>
		</div>

	</div>
</div>
<script>
    $(document).ready(function() {
    console.log('Document ready');
    
    // グローバル変数
    var table;
    var selectedIds = {};
    
    // 初期状態で既に選択されているチェックボックスの状態を保存
    <?php if(is_array($arami_data["登録者"]) && !empty($arami_data["登録者"])) { ?>
        <?php foreach($arami_data["登録者"] as $key => $value) { ?>
            selectedIds['<?php echo $value; ?>'] = true;
        <?php } ?>
    <?php } ?>
    
    // カスタムフィルター関数を定義
    function customFilterFunction(settings, searchData, index, rowData, counter) {
        console.log('customFilterFunction CALLED', index);
        
        // 現在の行の要素を取得
        var row = $(table.row(index).node());
        var isSelectable = row.data('selectable') === true;
        
        // 行データを取得（DataTablesの内部データ構造）
        var status = searchData[7];  // ステータス列
        var memberStatus = searchData[8]; // 会員ステータス列
        var requestDate = parseJPDate(searchData[6]); // 依頼日の列
        
        // フィルター値の取得
        var selectValue = $('#filterSelect').val();
        var statusVal = $('#statusFilter').val();
        var memberStatusVal = $('#memberStatusFilter').val();
        
        // デバッグ出力
        console.log({
            row: index,
            status: status,
            memberStatus: memberStatus,
            statusVal: statusVal,
            memberStatusVal: memberStatusVal,
            selectValue: selectValue
        });
        
        // チェックされている行は常に表示
        var isChecked = false;
        var checkboxInput = row.find('input[type="checkbox"]');
        if (checkboxInput.length > 0) {
            var value = checkboxInput.val();
            isChecked = selectedIds[value] === true;
        }
        if (isChecked) {
            console.log('Row ' + index + ' is checked, showing');
            return true;
        }
        
        // 選択状態のフィルター
        if (selectValue === 'selectable' && !isSelectable) {
            console.log('Row ' + index + ' is not selectable, hiding');
            return false;
        }
        
        // 日付のフィルター
        var startDate = $('#requestDateStart').val();
        var endDate = $('#requestDateEnd').val();
        if (startDate && requestDate && requestDate < startDate) {
            console.log('Row ' + index + ' date is before start date, hiding');
            return false;
        }
        if (endDate && requestDate && requestDate > endDate) {
            console.log('Row ' + index + ' date is after end date, hiding');
            return false;
        }
        
        // ステータスフィルター
        if (statusVal && status !== statusVal) {
            console.log('Row ' + index + ' status does not match, hiding');
            return false;
        }
        
        // 会員ステータスフィルター
        if (memberStatusVal && memberStatus !== memberStatusVal) {
            console.log('Row ' + index + ' member status does not match, hiding');
            return false;
        }
        
        console.log('Row ' + index + ' matched all filters, showing');
        return true;
    }

    // 日本語日付をYYYY-MM-DDに変換する関数
    function parseJPDate(jpDate) {
        if (!jpDate || jpDate.trim() === '') return '';
        var m = jpDate.match(/^([0-9]{4})年([0-9]{1,2})月([0-9]{1,2})日$/);
        if (!m) return '';
        var y = m[1];
        var mon = ('0' + m[2]).slice(-2);
        var d = ('0' + m[3]).slice(-2);
        return y + '-' + mon + '-' + d;
    }

    // フィルター関数をDataTablesに登録
    // 最初にクリアして再登録（念のため）
    $.fn.dataTable.ext.search = [];
    $.fn.dataTable.ext.search.push(customFilterFunction);
    console.log('Filter function registered');

    // DataTablesを初期化
    table = $('#aramiSheetTable').DataTable({
        language: {
            url: "https://cdn.datatables.net/plug-ins/1.11.5/i18n/ja.json"
        },
        order: [[1, 'desc']], // ID列を降順でソート
        pageLength: 50, // 1ページあたりの表示件数
        lengthMenu: [[10, 30, 50, 100, 500, -1], [10, 30, 50, 100, 500, "全件"]],
        columnDefs: [
            {
                targets: 0, // チェックボックス列
                orderable: false, // ソート無効
                searchable: false // 検索対象外
            }
        ],
        // ここではsearchingをtrueにする（内部検索機能を有効にする）
        searching: true,
        // 検索ボックスを非表示にする
        dom: 'lBfrtip',
        // 初期化完了後のコールバック
        initComplete: function() {
            console.log('Table initialized');
            // ステータスとフィルターの準備
            fillStatusOptions();
            // 保存されたフィルターを復元
            restoreFilters();
            // テーブルの準備完了後にフィルターを適用
            setTimeout(function() {
                applyDateFilter();
            }, 100);
        }
    });
    
    console.log('Table variable initialized:', table);
    
    // ステータス・会員ステータスの選択肢を自動生成
    function fillStatusOptions() {
        console.log('Filling status options');
        var statusSet = new Set();
        var memberStatusSet = new Set();
        
        // テーブルの全行を走査
        table.rows().every(function(rowIdx) {
            var data = this.data();
            if (data[7] && data[7].trim() !== '') statusSet.add(data[7]);
            if (data[8] && data[8].trim() !== '') memberStatusSet.add(data[8]);
        });
        
        console.log('Status values:', Array.from(statusSet));
        console.log('Member status values:', Array.from(memberStatusSet));
        
        // セットからオプションを生成
        $('#statusFilter').empty().append('<option value="">全て</option>');
        $('#memberStatusFilter').empty().append('<option value="">全て</option>');
        
        statusSet.forEach(function(val) {
            $('#statusFilter').append('<option value="'+val+'">'+val+'</option>');
        });
        
        memberStatusSet.forEach(function(val) {
            $('#memberStatusFilter').append('<option value="'+val+'">'+val+'</option>');
        });
    }
    
    // チェックボックスの選択数を更新する関数
    function updateSelectedCount() {
        var count = Object.keys(selectedIds).length;
        $('#selectedCount').text(count);
    }
    
    // チェックボックスの状態変更を監視
    $('#aramiSheetTable').on('change', 'input[type="checkbox"]', function() {
        var value = $(this).val();
        
        if ($(this).is(':checked')) {
            selectedIds[value] = true;
        } else {
            delete selectedIds[value];
        }
        
        updateSelectedCount();
    });
    
    // DataTablesの描画イベントでチェックボックスの状態を復元
    table.on('draw', function() {
        console.log('Table draw event');
        $('input[name="arami_sheet_target_id[]"]').each(function() {
            var value = $(this).val();
            if (selectedIds[value]) {
                $(this).prop('checked', true);
            }
        });
    });
    
    // 初期表示時に選択数を更新
    updateSelectedCount();

    // フィルターの適用
    function applyDateFilter() {
        console.log('applyDateFilter called');
        table.draw();
        console.log('Table draw completed');
    }

    // ステータスと会員ステータス選択肢の変更を監視
    $('#statusFilter').on('change', function() {
        console.log('Status filter changed to:', $(this).val());
        applyDateFilter();
    });
    
    $('#memberStatusFilter').on('change', function() {
        console.log('Member status filter changed to:', $(this).val());
        applyDateFilter();
    });

    // 日付フィルターの変更を監視
    $('#requestDateStart').on('change', function() {
        console.log('Start date changed to:', $(this).val());
        applyDateFilter();
    });
    
    $('#requestDateEnd').on('change', function() {
        console.log('End date changed to:', $(this).val());
        applyDateFilter();
    });

    // 選択状態のフィルター変更を監視
    $('#filterSelect').on('change', function() {
        console.log('Filter select changed to:', $(this).val());
        applyDateFilter();
    });

    // フィルター値をローカルストレージに保存
    function saveFilters() {
        localStorage.setItem('aramiSheet_filterSelect', $('#filterSelect').val());
        localStorage.setItem('aramiSheet_requestDateStart', $('#requestDateStart').val());
        localStorage.setItem('aramiSheet_requestDateEnd', $('#requestDateEnd').val());
        localStorage.setItem('aramiSheet_statusFilter', $('#statusFilter').val());
        localStorage.setItem('aramiSheet_memberStatusFilter', $('#memberStatusFilter').val());
    }
    
    // フィルター変更時に保存
    $('#filterSelect, #requestDateStart, #requestDateEnd, #statusFilter, #memberStatusFilter').on('change', saveFilters);

    // ページロード時にフィルター値を復元
    function restoreFilters() {
        var filterSelect = localStorage.getItem('aramiSheet_filterSelect');
        var requestDateStart = localStorage.getItem('aramiSheet_requestDateStart');
        var requestDateEnd = localStorage.getItem('aramiSheet_requestDateEnd');
        var statusFilter = localStorage.getItem('aramiSheet_statusFilter');
        var memberStatusFilter = localStorage.getItem('aramiSheet_memberStatusFilter');
        
        if (filterSelect) $('#filterSelect').val(filterSelect);
        if (requestDateStart) $('#requestDateStart').val(requestDateStart);
        if (requestDateEnd) $('#requestDateEnd').val(requestDateEnd);
        if (statusFilter) $('#statusFilter').val(statusFilter);
        if (memberStatusFilter) $('#memberStatusFilter').val(memberStatusFilter);
        
        console.log('Filters restored:', {
            filterSelect: $('#filterSelect').val(),
            requestDateStart: $('#requestDateStart').val(),
            requestDateEnd: $('#requestDateEnd').val(),
            statusFilter: $('#statusFilter').val(),
            memberStatusFilter: $('#memberStatusFilter').val()
        });
    }
    
    // ボタン用のコンテナを作成
    var buttonContainer = $('<div>')
        .css({
            'margin-top': '10px',
            'margin-bottom': '10px',
            'display': 'flex',
            'gap': '10px'
        })
        .insertAfter($('#memberStatusFilter').parent());
    
    /*
    // クリアボタンを追加
    $('<button>')
        .attr('id', 'clearFilters')
        .text('フィルターをクリア')
        .css({
            'padding': '5px 10px'
        })
        .on('click', function(e) {
            e.preventDefault();
            $('#filterSelect').val('all');
            $('#requestDateStart').val('');
            $('#requestDateEnd').val('');
            $('#statusFilter').val('');
            $('#memberStatusFilter').val('');
            saveFilters();
            applyDateFilter();
        })
        .appendTo(buttonContainer);
        
    // デバッグボタンを追加
    $('<button>')
        .attr('id', 'debugFilters')
        .text('フィルターをデバッグ')
        .css({
            'padding': '5px 10px',
            'background-color': '#ffcccc'
        })
        .on('click', function(e) {
            e.preventDefault();
            console.log('Debug button clicked');
            console.log('Current filters:', {
                filterSelect: $('#filterSelect').val(),
                requestDateStart: $('#requestDateStart').val(),
                requestDateEnd: $('#requestDateEnd').val(),
                statusFilter: $('#statusFilter').val(),
                memberStatusFilter: $('#memberStatusFilter').val()
            });
            
            // 手動でフィルターを適用
            table.draw();
        })
        .appendTo(buttonContainer);
        */
});
</script>