<?php

require_once ("a-gate-functions.php");
require_once (dirname(__FILE__)."/../../class/spiritTypeClass.php");
require_once(dirname(__FILE__) . "/../../class/spiritSheetClass.php");

$spiritType = new SpiritTypeClass(); //管理データ
$spiritTypeArray = $spiritType->getSpiritTypeKeyTypeNum();

$spiritSheet = new SpiritSheetClass(); //管理データ



//新規作成
if(isset($_POST['make_sheet'])){
    $spiritSheet->newAramiSheet( $_POST );
}

//削除(1人もいない時のみ)
if(isset($_POST['delete_sheet_id']))
{
    wp_delete_post($_POST['delete_sheet_id'], true);
}

//シート取得
$sheet_array = $spiritSheet->getAramiSheet(  );

//var_dump($_POST);

?>


<div class="admin-user-table-area">

    <div class="admin-title">
        粗見シート一覧
    </div>

    <div class="admin-arami-sheet-list-area">

        <?php if(!isset($_POST['new_sheet'])){?>
            <div style="margin-bottom: 20px;">
                <form action="<?php echo getURLSetSlag("admin-arami-sheet-list"); ?>" method="post" >

                <input type="hidden" name="new_sheet" value="">

                    <input type="submit" value="新規作成" class="admin-new-submit-button">

                </form>


            </div>
        <?php } ?>

        <?php if(isset($_POST['new_sheet'])){?>

            <div class="admin-arami-sheet-list-new-sheet-area">

                <form action="<?php echo getURLSetSlag("admin-arami-sheet-list"); ?>" method="post" onsubmit="return confirm('新規作成してもよろしいですか？');">

                    <input type="hidden" name="make_sheet" value="">
                    <input type="hidden" name="unix_time" value="<?php echo time(); ?>">

                    <div class="admin-arami-sheet-list-new-sheet-box">

                        <div class="admin-arami-sheet-list-new-sheet-title">施術</div>

                        <div class="admin-arami-sheet-list-new-sheet-select">
                            <select name="acf_arami_type_num" id="acf_arami_type_num" required>
                                <option value="">未設定</option>
                                <?php foreach ($spiritTypeArray as $key => $value) { ?>
                                    <?php if($value['arami'] == "")continue; ?>
                                    <?php if($value['ID'] != 7644 && $value['ID'] != 77)continue; ?>
                                    <option value="<?php echo $value['ID']; ?>"><?php echo $value['title']; ?></option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="admin-arami-sheet-list-new-sheet-title">シート名</div>
                    
                        <div class="admin-arami-sheet-list-new-sheet-text">
                            <input type="text" name="acf_arami_title" id="acf_arami_title" value="" style="width: 500px;" required>
                        </div>

                        <div class="admin-arami-sheet-list-new-sheet-button">
                            <input type="submit" value="作成" class="admin-arami-sheet-list-new-sheet-button-submit">
                        </div>
                    </div>
                </form>

                <div style="">
                    <button class="" type="button" style="width: 150px;border-radius: 8px;margin-left: 40px;background-color: dimgrey;color: white;" onclick="window.location.href='<?php echo getURLSetSlag("admin-arami-sheet-list"); ?>'">戻る</button>
                </div>
            </div>


        <?php } ?>
        

        

        <div class="admin-arami-sheet-table-area">
            <!-- DataTables用のCSSとJavaScript -->
            <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
            <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
            <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
            <script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

            <!-- 絞り込みフィルター -->
            <div style="margin-bottom: 15px; padding: 10px; background-color: #f8f9fa; border-radius: 5px;">
                <div style="display: flex; gap: 20px; align-items: center;">
                    <div>
                        <label for="treatmentFilter" style="margin-right: 10px; font-weight: bold;">施術で絞り込み:</label>
                        <select id="treatmentFilter" style="padding: 5px; border: 1px solid #ddd; border-radius: 3px; min-width: 200px;">
                            <option value="">全て表示</option>
                        </select>
                    </div>
                    <div>
                        <label for="memberStatusFilter" style="margin-right: 10px; font-weight: bold;">会員ステータスで絞り込み:</label>
                        <select id="memberStatusFilter" style="padding: 5px; border: 1px solid #ddd; border-radius: 3px; min-width: 200px;">
                            <option value="">全て表示</option>
                        </select>
                    </div>
                </div>
            </div>

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

            <table id="aramiSheetTable" class="display">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>編集</th>
                        <th>シート名</th>
                        <th>施術</th>
                        <th>登録人数</th>
                        <th>ステータス</th>
                        <th>会員ステータス</th>
                        <th>実行日</th>
                        <th>実行予定日</th>
                        <th>作成日</th>
                        
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($sheet_array as $key => $value) { ?>
                        <?php $arami_data = $spiritSheet->setAramiSheet($value);?>
                        <tr>
                            <td><?php echo $arami_data["ID"];?></td>
                            <td>
                                <form action="<?php echo getURLSetSlag("admin-arami-sheet-edit"); ?>" method="post">
                                    <input type="hidden" name="arami_sheet_id" value="<?php echo $arami_data["ID"]; ?>">
                                    <input type="submit" value="編集" class="admin-arami-sheet-list-new-sheet-button-submit" style="width: 60px;">
                                </form>
                            </td>
                            <td style="font-weight: 600;">
                                <?php if($arami_data["登録者数"] > 0){ ?>
                                    <a href="<?php echo getURLSetSlag("admin-arami-sheet-detail"); ?>?sheet_id=<?php echo $arami_data["ID"];?>" target="_blank">
                                        <?php echo $arami_data["タイトル"];?>
                                    </a>
                                <?php }else{ ?>
                                    <?php echo $arami_data["タイトル"];?>
                                <?php } ?>
                            </td>
                            <td><?php echo $spiritTypeArray[$arami_data["施術"]]["title"];?></td>
                            <td><?php echo $arami_data["登録者数"];?>人</td>
                            <td><?php echo $arami_data["ステータス表示"];?></td>
                            <td><?php echo $arami_data["会員ステータス表示"];?></td>
                            <td><?php echo $arami_data["実行日年月日"];?></td>
                            <td><?php echo $arami_data["実行予定日年月日"];?></td>
                            <td><?php echo $arami_data["作成日年月日"];?></td>
                           
                        </tr>
                    <?php }?>
                </tbody>
            </table>

            <script>
                $(document).ready(function() {
                    var table = $('#aramiSheetTable').DataTable({
                        language: {
                            url: "https://cdn.datatables.net/plug-ins/1.11.5/i18n/ja.json"
                        },
                        order: [[0, 'desc']], // ID列を降順でソート
                        pageLength: 50, // 1ページあたりの表示件数
                        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "全件"]],
                        columnDefs: [
                            {
                                targets: 1, // 編集列
                                orderable: false, // ソート無効
                                searchable: false // 検索対象外
                            }
                        ]
                    });

                    // 施術の絞り込みフィルター
                    $('#treatmentFilter').on('change', function() {
                        var selectedTreatment = $(this).val();
                        if (selectedTreatment) {
                            table.column(3).search('^' + selectedTreatment + '$', true, false).draw();
                        } else {
                            table.column(3).search('').draw();
                        }
                    });

                    // 会員ステータスの絞り込みフィルター
                    $('#memberStatusFilter').on('change', function() {
                        var selectedMemberStatus = $(this).val();
                        if (selectedMemberStatus) {
                            table.column(6).search('^' + selectedMemberStatus + '$', true, false).draw();
                        } else {
                            table.column(6).search('').draw();
                        }
                    });

                    // 施術の種類を取得してフィルターに追加
                    table.column(3).data().unique().sort().each(function(d, j) {
                        if (d) {
                            $('#treatmentFilter').append('<option value="' + d + '">' + d + '</option>');
                        }
                    });

                    // 会員ステータスの種類を取得してフィルターに追加
                    table.column(6).data().unique().sort().each(function(d, j) {
                        if (d) {
                            $('#memberStatusFilter').append('<option value="' + d + '">' + d + '</option>');
                        }
                    });
                });
            </script>
        </div>
        


    </div>


</div>