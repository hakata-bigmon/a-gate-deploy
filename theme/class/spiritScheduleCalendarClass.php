<?php 


require_once (dirname(__FILE__)."/spiritScheduleClass.php");

class SpiritScheduleCalendarClass
{

	/****************************************************
	**  全てのカレンダーのベース表示                 $make_calendar(編集画面かどうか)
	******************************************************/
	public function dispAllCalendarBase( $schedule_aray , $make_calendar = false,$select_cattegory = array())
	{

		$spiritSchedule = new SpiritScheduleClass(); //スケジュールクラス

		// 現在の年月を取得
		$year = isset($_GET['year']) ? $_GET['year'] : date('Y');
		$month = isset($_GET['month']) ? $_GET['month'] : date('n');
		
		// スケジュールの詳細データを取得
		$schedule_details = array();
		if ($schedule_aray) {
			foreach ($schedule_aray as $year_data) {
				foreach ($year_data as $month_data) {
					foreach ($month_data as $day_data) {
						foreach ($day_data as $schedule_id) {
							if (!isset($schedule_details[$schedule_id])) {
								$schedule_details[$schedule_id] = $spiritSchedule->getSpritScheduledetail($schedule_id);
							}
						}
					}
				}
			}
		}

		//var_dump($schedule_details);
		
		// 月初めの曜日と月の日数を取得
		$firstDayOfMonth = date('w', strtotime($year . '-' . $month . '-1'));
		$daysInMonth = date('t', strtotime($year . '-' . $month . '-1'));
		
		// PHPでホームURLを変数として定義
		$home_url = home_url();
		// 管理者権限情報を追加
		$is_admin = current_user_can('administrator');
		?>
		<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
		<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/css/disp-calendar.css?<?php echo date('Ymd H:i:s'); ?>">
		<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
		<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
		<script>
		// JavaScriptでPHPから渡されたホームURLを変数として保持
		const wpHomeUrl = '<?php echo $home_url; ?>';
		// 管理者権限情報を追加
		const isAdmin = <?php echo $is_admin ? 'true' : 'false'; ?>;
		// PHPのクラス定数をJavaScript変数として定義
		const SCHEDULE_DISP_OVER = '<?php echo SpiritScheduleClass::SCHEDULE_DISP_OVER; ?>';
		const SCHEDULE_DISP_NOT = '<?php echo SpiritScheduleClass::SCHEDULE_DISP_NOT; ?>';

		// スケジュールの詳細データをグローバル変数として保持
		let scheduleDetails = <?php echo json_encode($schedule_details); ?>;
		const initialScheduleData = <?php echo json_encode($schedule_aray); ?>;

		// スケジュールデータを更新する関数
		function updateScheduleData(year, month) {
			// 現在のスケジュールデータを使用
			return initialScheduleData;
		}

		// スケジュールIDから表示名を取得する関数
		function getScheduleDisplayName(scheduleId) {


			if(scheduleDetails[scheduleId]["予約完了"] == true)
			{
				if(scheduleDetails[scheduleId]["施術グループ"] == <?php echo SpiritTypeClass::SPIRIT_TYPE_NAME_SODAN; ?>)
				{
					return scheduleDetails[scheduleId] ? scheduleDetails[scheduleId]["実行時間表示"] + "　" + scheduleDetails[scheduleId]["予約者データ"][0]["名前"] : 'ID: ' + scheduleId;
				}
				else{
					return scheduleDetails[scheduleId] ? scheduleDetails[scheduleId]["実行時間表示"] + "　" + scheduleDetails[scheduleId]["表示名"] : 'ID: ' + scheduleId;
				}
			}
			else{
				return scheduleDetails[scheduleId] ? scheduleDetails[scheduleId]["実行時間表示"] + "　" + scheduleDetails[scheduleId]["表示名"] : 'ID: ' + scheduleId;
			}
		}

		// スケジュールの表示スタイルを決定する関数を修正
		function getScheduleStyle(schedule) {
			const details = scheduleDetails[schedule];
			if (!details) return '';
			
			const today = new Date();
			today.setHours(0, 0, 0, 0);
			
			// 締切年月日をDate型に変換
			const deadlineStr = details["締切年月日"].replace(/年|月/g, '-').replace(/日/g, '');
			const deadline = new Date(deadlineStr);
			deadline.setHours(0, 0, 0, 0);
			
			// 今日が締切日より後かどうかチェック
			let isExpired = today > deadline;

			if(details["表示ステータス"] == SCHEDULE_DISP_OVER || details["表示ステータス"] == SCHEDULE_DISP_NOT){
				isExpired = true;
			}
			
			// 予約完了または締切の場合は灰色、期限切れの場合は薄いグレー、そうでない場合は通常の色
			if (details["予約完了"] === true) {
				return 'background-color: #ff00d2; color: #ffffff;';
			} else if (isExpired) {
				return 'background-color: #F5F5F5; color: #999999;';
			} else {
				return 'background-color: ' + details["表示カラー"] + ';';
			}
		}

		// DOMの読み込み完了後に実行
		jQuery(function($) {
			// カレンダーピッカーの初期化
			$("#datepicker").datepicker({
				dateFormat: 'yy-mm-dd',
				monthNames: ["1月", "2月", "3月", "4月", "5月", "6月", "7月", "8月", "9月", "10月", "11月", "12月"],
				dayNames: ["日曜日", "月曜日", "火曜日", "水曜日", "木曜日", "金曜日", "土曜日"],
				dayNamesMin: ["日", "月", "火", "水", "木", "金", "土"],
				dayNamesShort: ["日", "月", "火", "水", "木", "金", "土"],
				showMonthAfterYear: true,
				yearSuffix: '年',
				beforeShow: function(input, inst) {
					// ボタンの位置を基準にカレンダーを表示
					var buttonOffset = $("#datepicker-btn").offset();
					setTimeout(function() {
						inst.dpDiv.css({
							top: buttonOffset.top + 40,
							left: buttonOffset.left
						});
					}, 0);
				},
				onSelect: function(dateText) {
					const date = new Date(dateText);
					const year = date.getFullYear();
					const month = date.getMonth() + 1;
					
					document.getElementById('calendar-year').textContent = year;
					document.getElementById('calendar-month').textContent = month;
					updateCalendarGrid(year, month);
				}
			});

			// カレンダーボタンクリックイベント
			$("#datepicker-btn").click(function() {
				$("#datepicker").datepicker("show");
			});
		});
		</script>

		<div class="calendar-container">
			<!-- モーダル用のHTML追加 -->
			<div id="scheduleModal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5);">
				<div style="background-color: white; margin: 15% auto; padding: 20px; border-radius: 5px; width: 600px; position: relative;">
					<span onclick="closeModal()" style="position: absolute; right: 10px; top: 5px; cursor: pointer; font-size: 20px;">&times;</span>
					<h3 id="modalTitle" style="margin-top: 0;"></h3>
					<div id="modalContent"></div>
				</div>
			</div>
			<div style="width: 95%; margin: 0 auto;">
				<div class="calendar-header" style="margin-bottom: 20px; position: relative;">
					<div id="calendarFilters"></div>
					<div style="text-align: center; margin-bottom: 15px;color: #888888;">
						<h2 style="margin: 0;"><span id="calendar-year"><?php echo $year; ?></span>年<span id="calendar-month"><?php echo $month; ?></span>月</h2>
					</div>
					<div style="display: flex; justify-content: space-between; align-items: stretch; margin-bottom: 10px;">
						<div style="display: flex;">
							<div style="display: flex;">
								<button onclick="changeMonth(-1)" style="border: 1px solid #ddd; background: white; padding: 8px 15px; margin-right: 5px; cursor: pointer; height: 35px; width: 40px; display: flex; align-items: center; justify-content: center; border-radius: 3px;">&lt;</button>
								<button onclick="changeMonth(1)" style="border: 1px solid #ddd; background: white; padding: 8px 15px; cursor: pointer; height: 35px; width: 40px; display: flex; align-items: center; justify-content: center; border-radius: 3px;">&gt;</button>
							</div>
							<button onclick="goToToday()" style="border: 1px solid #ddd; background: white; padding: 8px 15px; margin-left: 5px; cursor: pointer; height: 35px; display: flex; align-items: center; justify-content: center;">今月</button>
							<button id="datepicker-btn" style="border: 1px solid #ddd; background: white; padding: 8px 15px; margin-left: 5px; cursor: pointer; height: 35px; display: flex; align-items: center; justify-content: center;">
								<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
									<path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
								</svg>
							</button>
						</div>

						

						<div style="display: flex; gap: 10px;">
							<button onclick="switchView('month')" id="month-view-btn" style="border: none; background: #CCCCCC; padding: 8px 25px; cursor: pointer; height: 35px; display: flex; align-items: center; justify-content: center; border-radius: 20px; color: #888888; min-width: 80px;font-weight: 600;">月</button>
							<button onclick="switchView('week')" id="week-view-btn" style="border: none; background: white; padding: 8px 25px; cursor: pointer; height: 35px; display: flex; align-items: center; justify-content: center; border-radius: 20px; border: 1px solid #CCCCCC; min-width: 80px;color: #888888;font-weight: 600;">週</button>
						</div>
					</div>
					<input type="text" id="datepicker" style="position: absolute; opacity: 0; height: 0; padding: 0; margin: 0; border: none;">
				</div>
				
				<div id="calendar-grid">
					<table class="calendar-table">
						<thead>
							<tr>
								<th style="width: 14.28%; background-color: #E01010; color: white; border: 1px solid #ddd;">日</th>
								<th style="width: 14.28%; background-color: #EFEFEF; border: 1px solid #ddd;color: #888888;">月</th>
								<th style="width: 14.28%; background-color: #EFEFEF; border: 1px solid #ddd;color: #888888;">火</th>
								<th style="width: 14.28%; background-color: #EFEFEF; border: 1px solid #ddd;color: #888888;">水</th>
								<th style="width: 14.28%; background-color: #EFEFEF; border: 1px solid #ddd;color: #888888;">木</th>
								<th style="width: 14.28%; background-color: #EFEFEF; border: 1px solid #ddd;color: #888888;">金</th>
								<th style="width: 14.28%; background-color: #6EB9CB; color: white; border: 1px solid #ddd;">土</th>
							</tr>
						</thead>
						<tbody id="calendar-body">
						<?php
						$dayCount = 1;
						$cellCount = 0;
						$today = date('Y-m-d');
						
						// カレンダーの日付部分を生成
						for ($i = 0; $i < 6; $i++) {
							echo '<tr>';
							for ($j = 0; $j < 7; $j++) {
								$cellCount++;
								
								// セルのスタイル
								$cellStyle = 'background-color: white; vertical-align: top; min-height: 120px; padding: 5px; border: 1px solid #ddd; max-width: 100px; min-width: 100px; overflow: hidden;';
								
								// 日曜日のセルの場合、背景色を変更
								if ($j == 0) {
									$cellStyle = 'background-color: white; vertical-align: top; min-height: 120px; padding: 5px; border: 1px solid #ddd; max-width: 100px; min-width: 100px; overflow: hidden;';
								}
								
								if ($cellCount <= $firstDayOfMonth || $dayCount > $daysInMonth) {
									// 空のセル
									echo '<td style="' . $cellStyle . '"></td>';
								} else {
									// 今日の日付かどうかチェック
									$currentDate = sprintf('%04d-%02d-%02d', $year, $month, $dayCount);
									$isToday = ($currentDate === $today);
									
									// 日付を表示
									echo '<td style="' . $cellStyle . '">';
									if ($isToday) {
										echo '<div style="font-size: 16px; margin-bottom: 5px; display: inline-block; width: 24px; height: 24px; line-height: 24px; text-align: center; border-radius: 50%; background-color: rgba(255, 0, 0, 0.1);"><strong>' . $dayCount . '</strong></div>';
									} else {
										echo '<div style="font-size: 16px; margin-bottom: 5px;color: #888888;">' . $dayCount . '</div>';

										
									}
									
									// スケジュールデータの表示
									if (isset($schedule_aray[$year][$month][$dayCount])) {
										echo '<div style="min-height: 100px; display: flex; flex-direction: column;">';
										foreach ($schedule_aray[$year][$month][$dayCount] as $schedule) {
											$schedule_data = $spiritSchedule->getSpritScheduledetail($schedule);
											$scheduleStyle = '';
											$schedule_disp_str = $schedule_data["実行時間表示"] ."　" . $schedule_data["表示名"];

											
											if ($schedule_data["予約完了"] === true) {

												if($make_calendar == true)
												{
													$scheduleStyle = 'background-color: #ff00d2; color: #ffffff;';

													if($schedule_data["施術グループ"] == SpiritTypeClass::SPIRIT_TYPE_NAME_SODAN) 
													{
														$schedule_disp_str = $schedule_data["実行時間表示"] ."　" . $schedule_data["予約者データ"][0]["名前"];

													}
												}else{
													$scheduleStyle = 'background-color: #F5F5F5; color: #999999;';
												}
												

											}else if ($schedule_data["表示ステータス"] == SpiritScheduleClass::SCHEDULE_DISP_OVER || $schedule_data["表示ステータス"] == SpiritScheduleClass::SCHEDULE_DISP_NOT) {
												$scheduleStyle = 'background-color: #F5F5F5; color: #999999;';
											
											} else {
												$scheduleStyle = 'background-color: ' . $schedule_data["表示カラー"] . ';';
											}
											echo '<div onclick="showModal(' . $schedule . ')" style="' . $scheduleStyle . ' margin-bottom: 2px; padding: 2px 5px; border-radius: 3px; word-wrap: break-word; overflow-wrap: break-word; white-space: normal; font-size: 12px; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; cursor: pointer;">'. $schedule_disp_str . '</div>';
										}
										echo '</div>';
									} else {
										echo '<div style="min-height: 100px;"></div>';
									}
									
									echo '</td>';
									$dayCount++;
								}
							}
							echo '</tr>';
							
							// 月の最終日を過ぎたら終了
							if ($dayCount > $daysInMonth) {
								break;
							}
						}
						?>
						</tbody>
					</table>
				</div>
			</div>
		</div>

		<script>
		function goToToday() {
			if (currentView === 'month') {
				const today = new Date();
				const year = today.getFullYear();
				const month = today.getMonth() + 1;
				
				document.getElementById('calendar-year').textContent = year;
				document.getElementById('calendar-month').textContent = month;
				updateCalendarGrid(year, month);
			} else {
				// 週表示の場合は今週に移動
				updateWeekView(null, null, new Date());
			}
		}

		function changeMonth(direction) {
			if (currentView === 'month') {
				let yearElement = document.getElementById('calendar-year');
				let monthElement = document.getElementById('calendar-month');
				let year = parseInt(yearElement.textContent);
				let month = parseInt(monthElement.textContent);

				// 月を更新
				month += direction;

				// 年をまたぐ場合の処理
				if (month < 1) {
					month = 12;
					year--;
				} else if (month > 12) {
					month = 1;
					year++;
				}

				// 年月を更新
				yearElement.textContent = year;
				monthElement.textContent = month;

				// スケジュールデータを更新
				const updatedScheduleData = updateScheduleData(year, month);
				
				// 現在のフィルター状態を取得して適用
				applyFilters();
			} else {
				// 週表示の場合は週を移動
				const currentDate = getCurrentWeekStartDate();
				const newDate = new Date(currentDate);
				newDate.setDate(currentDate.getDate() + (direction * 7));
				updateWeekView(null, null, newDate);
			}
		}

		function getCurrentWeekStartDate() {
			const yearElement = document.getElementById('calendar-year');
			const monthElement = document.getElementById('calendar-month');
			const year = parseInt(yearElement.textContent);
			const month = parseInt(monthElement.textContent);
			
			// 現在表示されている週の開始日を取得
			const displayedDate = new Date(year, month - 1, 1);
			const tbody = document.getElementById('calendar-body');
			if (tbody.firstElementChild) {
				const firstDateCell = tbody.firstElementChild.children[1];
				if (firstDateCell) {
					const dateText = firstDateCell.textContent;
					displayedDate.setDate(parseInt(dateText));
				}
			}
			return displayedDate;
		}

		let currentView = 'month';

		function switchView(view) {
			currentView = view;
			const monthBtn = document.getElementById('month-view-btn');
			const weekBtn = document.getElementById('week-view-btn');
			const todayBtn = document.querySelector('button[onclick="goToToday()"]');
			
			// Update button styles
			if (view === 'month') {
				monthBtn.style.background = '#CCCCCC';
				monthBtn.style.color = 'white';
				monthBtn.style.border = 'none';
				weekBtn.style.background = 'white';
				weekBtn.style.color = '#000000';
				weekBtn.style.border = '1px solid #CCCCCC';
				todayBtn.textContent = '今月';
				
				// 月表示の場合はヘッダーを表示
				document.querySelector('.calendar-table thead').style.display = '';
			} else {
				monthBtn.style.background = 'white';
				monthBtn.style.color = '#000000';
				monthBtn.style.border = '1px solid #CCCCCC';
				weekBtn.style.background = '#CCCCCC';
				weekBtn.style.color = 'white';
				weekBtn.style.border = 'none';
				todayBtn.textContent = '今週';
				
				// 週表示の場合はヘッダーを非表示
				document.querySelector('.calendar-table thead').style.display = 'none';
			}
			
			// Update calendar view
			updateCalendarGrid(
				parseInt(document.getElementById('calendar-year').textContent),
				parseInt(document.getElementById('calendar-month').textContent)
			);
		}

		function updateCalendarGrid(year, month) {
			if (currentView === 'month') {
				updateMonthView(year, month);
			} else {
				updateWeekView(year, month);
			}
		}

		function updateMonthView(year, month) {
			let dayCount = 1;
			let cellCount = 0;
			
			// 月初めの曜日を取得（0=日曜日, 1=月曜日, ..., 6=土曜日）
			let firstDay = new Date(year, month - 1, 1).getDay();
			let daysInMonth = new Date(year, month, 0).getDate();
			
			// 今日の日付を取得
			const today = new Date();
			const isCurrentMonth = today.getFullYear() === year && today.getMonth() === month - 1;
			const todayDate = today.getDate();
			
			// スケジュールデータを取得
			const scheduleData = <?php echo json_encode($schedule_aray); ?>;
			
			let html = '';
			
			for (let i = 0; i < 6; i++) {
				html += '<tr>';
				for (let j = 0; j < 7; j++) {
					cellCount++;
					
					// セルのスタイル
					let cellStyle = 'background-color: white; vertical-align: top; border: 1px solid #ddd; overflow: hidden;';
					
					if (cellCount <= firstDay || dayCount > daysInMonth) {
						// 空のセル
						html += '<td style="' + cellStyle + '"></td>';
					} else {
						// 今日の日付かどうかチェック
						const isToday = isCurrentMonth && dayCount === todayDate;
						
						// 日付を表示
						html += '<td style="' + cellStyle + '">';
						if (isToday) {
							html += '<div style="font-size: 16px; margin-bottom: 5px;"><span class="today-date">' + dayCount + '</span></div>';

						} else {
							html += '<div style="font-size: 16px; margin-bottom: 5px;color: #888888;">' + dayCount + '</div>';
						}
						
						// スケジュールデータの表示
						if (scheduleData && scheduleData[year] && scheduleData[year][month] && scheduleData[year][month][dayCount]) {
							//html += '<div style="min-height: 100px; display: flex; flex-direction: column;">';
							html += '<div class="schedule-container">';
							scheduleData[year][month][dayCount].forEach(schedule => {
								// フィルターされたスケジュールのみを表示
								if (window.scheduleDetails && window.scheduleDetails[schedule]) {
									const scheduleStyle = getScheduleStyle(schedule);
									//html += '<div onclick="showModal(' + schedule + ')" style="' + scheduleStyle + ' margin-bottom: 2px; padding: 2px 5px; border-radius: 3px; word-wrap: break-word; overflow-wrap: break-word; white-space: normal; font-size: 12px; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; cursor: pointer;">' + getScheduleDisplayName(schedule) + '</div>';
									html += '<div onclick="showModal(' + schedule + ')" class="schedule-item" style="' + scheduleStyle + '">' + getScheduleDisplayName(schedule) + '</div>';
								}
							});
							html += '</div>';
						} else {
							html += '<div style="min-height: 100px;"></div>';
						}
						
						html += '</td>';
						dayCount++;
					}
				}
				html += '</tr>';
				
				// 月の最終日を過ぎたら終了
				if (dayCount > daysInMonth) {
					break;
				}
			}
			
			// カレンダーを更新
			document.getElementById('calendar-body').innerHTML = html;
		}

		function updateWeekView(year, month, startDate = null) {
			// 表示する週の開始日を設定
			const weekStart = startDate || new Date();
			weekStart.setDate(weekStart.getDate() - weekStart.getDay()); // 日曜日まで戻る
			
			const dayNames = ["日", "月", "火", "水", "木", "金", "土"];
			//let html = '<table style="width: 100%; border-collapse: collapse; table-layout: fixed;">';
			let html = '<table class="week-view-table">';
			
			// スケジュールデータを取得
			const scheduleData = <?php echo json_encode($schedule_aray); ?>;
			
			// 各日の行を生成
			for (let i = 0; i < 7; i++) {
				const currentDate = new Date(weekStart);
				currentDate.setDate(weekStart.getDate() + i);
				
				// 今日の日付かどうかチェック
				const today = new Date();
				const isToday = currentDate.getFullYear() === today.getFullYear() &&
							   currentDate.getMonth() === today.getMonth() &&
							   currentDate.getDate() === today.getDate();
				
				html += '<tr style="height: 150px;">';
				
				// 曜日セル - 背景色を設定
				const bgColor = i === 0 ? '#E01010' : i === 6 ? '#6EB9CB' : '#EFEFEF';
				const textColor = (i === 0 || i === 6) ? 'white' : '#888888';
				html += '<td style="width: 30px; padding: 5px; border: 1px solid #ddd; text-align: center; background-color: ' + bgColor + ';">';
				html += '<div style="color: ' + textColor + ';">' + dayNames[i] + '</div>';
				html += '</td>';
				
				// 日付セル
				html += '<td style="width: 30px; padding: 5px; border: 1px solid #ddd; text-align: center;">';
				if (isToday) {
					html += '<div style="display: inline-block; width: 24px; height: 24px; line-height: 24px; text-align: center; border-radius: 50%; background-color: rgba(255, 0, 0, 0.1);"><strong>' + currentDate.getDate() + '</strong></div>';
				} else {
					html += '<div style="color: #888888;">' + currentDate.getDate() + '</div>';
				}
				html += '</td>';
				
				// スケジュールセル
				html += '<td style="padding: 10px; border: 1px solid #ddd; word-wrap: break-word; overflow-wrap: break-word;">';
				
				const currentYear = currentDate.getFullYear();
				const currentMonth = currentDate.getMonth() + 1;
				const currentDateNum = currentDate.getDate();

				if (scheduleData && scheduleData[currentYear] && 
					scheduleData[currentYear][currentMonth] && 
					scheduleData[currentYear][currentMonth][currentDateNum]) {
					scheduleData[currentYear][currentMonth][currentDateNum].forEach((schedule, index) => {
						// フィルターされたスケジュールのみを表示
						if (window.scheduleDetails && window.scheduleDetails[schedule]) {
							const scheduleStyle = getScheduleStyle(schedule);
							html += '<div onclick="showModal(' + schedule + ')" style="' + scheduleStyle + ' margin-bottom: 5px; padding: 8px; border-radius: 3px; word-wrap: break-word; overflow-wrap: break-word; white-space: normal; font-size: 12px; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; cursor: pointer;">' + getScheduleDisplayName(schedule) + '</div>';
						}
					});
				}
				
				html += '</td>';
				html += '</tr>';
			}
			
			html += '</table>';
			
			// カレンダーを更新
			document.getElementById('calendar-body').innerHTML = html;
			
			// 表示している週の年月を更新
			document.getElementById('calendar-year').textContent = weekStart.getFullYear();
			document.getElementById('calendar-month').textContent = weekStart.getMonth() + 1;
		}

		// モーダル表示関数を修正
		function showModal(scheduleId) {
			const modal = document.getElementById('scheduleModal');
			const modalTitle = document.getElementById('modalTitle');
			const modalContent = document.getElementById('modalContent');
			
			if (scheduleDetails[scheduleId]) {
				const details = scheduleDetails[scheduleId];
				modalTitle.textContent = details["表示名"];
				let content = '<div style="display: flex; margin-bottom: 5px;"><div style="width: 130px;">日時</div><div>' + details["実行年月日"] + ' ' + details["実行時間表示"] + '～</div></div>';
				content += '<div style="border-bottom: 1px solid #ddd; margin: 8px 0;"></div>';
				content += '<div style="display: flex; margin-bottom: 5px;"><div style="width: 130px;">メニュー</div><div>' + details["表示名"] + '</div></div>';
				content += '<div style="border-bottom: 1px solid #ddd; margin: 8px 0;"></div>';
				
				// 価格情報を追加
				content += '<div style="display: flex; margin-bottom: 5px;"><div style="width: 130px;">価格</div><div>' + 
					(Number(details["価格"]) === 0 ? '無料' : Number(details["価格"]).toLocaleString() + '円') + '</div></div>';
				content += '<div style="border-bottom: 1px solid #ddd; margin: 8px 0;"></div>';

				// 施術時間が0以上の場合は所要時間として表示
				if (Number(details["施術時間"]) > 0) {
					content += '<div style="display: flex; margin-bottom: 5px;"><div style="width: 130px;">所要時間</div><div>' + details["施術時間"] + '分</div></div>';
					content += '<div style="border-bottom: 1px solid #ddd; margin: 8px 0;"></div>';
				}

				if (details["担当者名前"] && details["担当者名前"] !== '') {
					content += '<div style="display: flex; margin-bottom: 5px;"><div style="width: 130px;">担当者</div><div>' + details["担当者名前"] + '</div></div>';
					content += '<div style="border-bottom: 1px solid #ddd; margin: 8px 0;"></div>';
				} else {
					content += '<div style="display: flex; margin-bottom: 5px;"><div style="width: 130px;">予約人数</div><div>' + details["予約人数"] + '/' + details["人数"] + '人</div></div>';
					content += '<div style="border-bottom: 1px solid #ddd; margin: 8px 0;"></div>';
				}
				
				if (details["場所"]) {
					content += '<div style="display: flex; margin-bottom: 5px;"><div style="width: 130px;">場所</div><div>' + details["場所ステータス"]["名前"] + '</div></div>';
					content += '<div style="margin-left: 80px;">' + details["場所ステータス"]["住所"] + '</div>';
					if (details["場所ステータス"]["MAP"]) {
						content += '<div style="margin-left: 130px+; margin-top: 5px;"><a href="' + details["場所ステータス"]["MAP"] + '" target="_blank" style="color: #0066cc; text-decoration: none;">Google Mapで見る</a></div>';
					}
					content += '<div style="border-bottom: 1px solid #ddd; margin: 8px 0;"></div>';
				}

				// 相談グループで予約完了の場合、予約者データの最初の要素の名前を表示し、編集モードの時のみ
				if (details["施術グループ"] === "<?php echo SpiritTypeClass::SPIRIT_TYPE_NAME_SODAN; ?>" && details["予約完了"] === true && <?php echo $make_calendar ? 'true' : 'false'; ?>) {
					const reservationName = details["予約者データ"][0]["名前"];
					content += '<div style="display: flex; margin-bottom: 5px;"><div style="width: 130px;">予約者</div><div>' + reservationName + '</div></div>';
					content += '<div style="border-bottom: 1px solid #ddd; margin: 8px 0;"></div>';

					const reservationlMail = details["予約者データ"][0]["メール"];
					content += '<div style="display: flex; margin-bottom: 5px;"><div style="width: 130px;">メールアドレス</div><div>' + reservationlMail + '</div></div>';
					content += '<div style="border-bottom: 1px solid #ddd; margin: 8px 0;"></div>';

					const reservationTel = details["予約者データ"][0]["電話番号"];
					content += '<div style="display: flex; margin-bottom: 5px;"><div style="width: 130px;">電話番号</div><div>' + reservationTel + '</div></div>';
					content += '<div style="border-bottom: 1px solid #ddd; margin: 8px 0;"></div>';
				}

				// 確認ボタンを追加
				content += '<div style="text-align: center; margin-top: 20px;">';
				// URLパラメータを取得
				const urlParams = new URLSearchParams(window.location.search);
				const checkUserParam = urlParams.get('check_user');
				const urlSuffix = checkUserParam ? '?check_user=' + checkUserParam : '';
				
				content += '<form id="confirmForm" method="POST" target="_blank" action="' + 
					(window.location.pathname.startsWith('/users/') ? 
						wpHomeUrl + '/users/user-schedule-page' + urlSuffix : 
						(details["施術グループ"] === "<?php echo SpiritTypeClass::SPIRIT_TYPE_NAME_DAY; ?>" ? 
							wpHomeUrl + '/admin-spirit-schedule-edit' + urlSuffix : 
							wpHomeUrl + '/admin-spirit-explanation-schedule-edit' + urlSuffix)) + '">';
				content += '<input type="hidden" name="edit_schedule" value="' + details["ID"] + '">';
				content += '<input type="hidden" name="read" value="true">';
				content += '<button type="submit" style="background-color: #4CAF50; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer;">確認する</button>';

				// 管理者用の選択ボタンを追加（管理者権限があり、予約完了でない場合のみ表示）
				if (isAdmin && window.location.pathname.includes('admin') && !details["予約完了"] && details["表示ステータス"] != SCHEDULE_DISP_NOT && details["表示ステータス"] != SCHEDULE_DISP_OVER) {
					// 締切日チェックを追加
					const today = new Date();
					today.setHours(0, 0, 0, 0);
					const deadlineStr = details["実締切"].replace(/年|月/g, '-').replace(/日/g, '');
					const deadline = new Date(deadlineStr);
					deadline.setHours(0, 0, 0, 0);
					
					if (today <= deadline) {
						content += '<div style="text-align: center; margin-top: 10px;">';
						<?php if ($make_calendar) { ?>
						content += '<button type="button" onclick="selectSchedule(' + scheduleId + ')" style="background-color: #2196F3; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer;width: 93px;margin-top: 30px;">選　択</button>';
						<?php } ?>
						content += '</div>';
					}
				}
				
				content += '</div>';
				
				modalContent.innerHTML = content;
			} else {
				modalTitle.textContent = 'スケジュール詳細';
				modalContent.innerHTML = 'スケジュールID: ' + scheduleId;
			}
			
			modal.style.display = 'block';
		}

		// スケジュール選択関数を修正
		function selectSchedule(scheduleId) {
			const details = scheduleDetails[scheduleId];
			if (details) {
				// スケジュール表示エリアに表示名を設定
				const displayArea = document.getElementById('schedule-display-area');
				if (displayArea) {
					displayArea.innerHTML = "【選択中スケジュール】" + details["表示名"];
				}

				// .target-form内のinput要素を更新
				const targetForms = document.querySelectorAll('.target-form');
				targetForms.forEach(form => {
					const scheduleIdInput = form.querySelector('input[name="schedule_id"]');
					if (scheduleIdInput) {
						scheduleIdInput.value = details["ID"];
					}
					const categoryTypeInput = form.querySelector('input[name="category_type"]');
					if (categoryTypeInput) {
						categoryTypeInput.value = details["施術名"];
					}
					// フォームの表示/非表示を切り替え
					form.style.display = 'block';
				});

				// .target-form外のsprit_typeのinput要素を更新
				const spritTypeInput = document.querySelector('input[name="sprit_type"]');
				if (spritTypeInput) {
					spritTypeInput.value = details["ID"];
				}

				// モーダルを閉じる
				closeModal();
			}
		}

		function closeModal() {
			const modal = document.getElementById('scheduleModal');
			modal.style.display = 'none';
		}

		// モーダル外クリックで閉じる
		window.onclick = function(event) {
			const modal = document.getElementById('scheduleModal');
			if (event.target == modal) {
				modal.style.display = 'none';
			}
		}

		// DOMの読み込み完了後にフィルターを初期化
		document.addEventListener('DOMContentLoaded', function() {
			// フィルター要素を追加
			document.getElementById('calendarFilters').innerHTML = generateFilterHtml();
			
			// フィルターの変更イベントを設定
			document.getElementById('displayNameFilter').addEventListener('change', applyFilters);
			document.getElementById('staffFilter').addEventListener('change', applyFilters);
		});

		// フィルター用のHTMLを生成する関数
		function generateFilterHtml() {
			const displayNames = new Set();
			const staffNames = new Set();

			<?php if(count($select_cattegory) > 0){?>
				<?php foreach($select_cattegory as $cattegory){?>
					displayNames.add(<?php echo $cattegory;?>);
				<?php }?>
			<?php }?>
			// スケジュールデータから表示名と担当者の一覧を取得
			Object.values(scheduleDetails).forEach(detail => {

				<?php if(count($select_cattegory) == 0){?>
					if (detail["施術名表示"]) {
						displayNames.add(detail["施術名表示"]);
					}
				<?php }?>
				if (detail["担当者名前"]) {
					staffNames.add(detail["担当者名前"]);
				}
			});
			
			return `
			  <div style="background: #f8fbff; border-radius: 12px; padding: 18px 20px 12px 20px; margin-bottom: 18px; box-shadow: 0 1px 6px #e0e7ef; max-width: 500px; margin-left:auto; margin-right:auto;">
			    <div style="font-weight: bold; color: #1976d2; font-size: 1.1rem; margin-bottom: 12px; letter-spacing: 0.05em;">
				絞り込み
			    </div>
			    <div style="display: flex; gap: 10px; flex-wrap: wrap; align-items: flex-end;">
			      <div style="flex: 1; min-width: 160px;">
			        <label style="font-size: 0.95em; color: #234a6f; margin-bottom: 3px; display: block;">メニュー</label>
			        <select id="displayNameFilter" style="padding: 6px; border: 1px solid #b0c4de; border-radius: 6px; width: 100%;">
			          <option value="">メニューで絞り込み</option>
			          ${Array.from(displayNames).sort().map(name => 
			            `<option value="${name}">${name}</option>`
			          ).join('')}
			        </select>
			      </div>
			      <div style="flex: 1; min-width: 160px;">
			        <label style="font-size: 0.95em; color: #234a6f; margin-bottom: 3px; display: block;">担当者</label>
			        <select id="staffFilter" style="padding: 6px; border: 1px solid #b0c4de; border-radius: 6px; width: 100%;">
			          <option value="">担当者で絞り込み</option>
			          ${Array.from(staffNames).sort().map(name => 
			            `<option value="${name}">${name}</option>`
			          ).join('')}
			        </select>
			      </div>
			      <div style="display: flex; flex-direction: column; gap: 6px; min-width: 90px; align-items: flex-end;">
			        <button onclick="clearFilters()" style="padding: 7px 18px; border: none; border-radius: 6px; background: #ececec; color: #1976d2; font-weight: bold; font-size: 1em; cursor: pointer;">クリア</button>
			      </div>
			    </div>
			  </div>
			`;
		}

		// フィルターを適用する関数
		function applyFilters() {
			const displayNameFilter = document.getElementById('displayNameFilter').value;
			const staffFilter = document.getElementById('staffFilter').value;
			
			// フィルター条件に基づいてスケジュールをフィルタリング
			const filteredSchedules = {};
			Object.entries(scheduleDetails).forEach(([id, detail]) => {
				const matchesDisplayName = !displayNameFilter || detail["表示名"] === displayNameFilter;
				const matchesStaff = !staffFilter || detail["担当者名前"] === staffFilter;
				
				if (matchesDisplayName && matchesStaff) {
					filteredSchedules[id] = detail;
				}
			});
			
			// カレンダーを更新
			updateCalendarWithFiltered(filteredSchedules);
		}

		// フィルターをクリアする関数
		function clearFilters() {
			document.getElementById('displayNameFilter').value = '';
			document.getElementById('staffFilter').value = '';
			updateCalendarWithFiltered(scheduleDetails);
		}

		// フィルター適用後のカレンダー更新関数を修正
		function updateCalendarWithFiltered(filteredDetails) {
			const year = parseInt(document.getElementById('calendar-year').textContent);
			const month = parseInt(document.getElementById('calendar-month').textContent);
			
			// スケジュールデータを一時的に置き換え
			window.scheduleDetails = filteredDetails;
			
			// カレンダーを更新
			if (currentView === 'month') {
				updateMonthView(year, month);
			} else {
				updateWeekView(year, month);
			}
		}
		</script>
		<?php
	}

	/****************************************************
	**  Ajaxリクエスト用のカレンダーグリッド生成
	******************************************************/
	public function generateCalendarGridAjax($year, $month)
	{
		// 月初めの曜日と月の日数を取得
		$firstDayOfMonth = date('w', strtotime($year . '-' . $month . '-1'));
		$daysInMonth = date('t', strtotime($year . '-' . $month . '-1'));
		
		$dayCount = 1;
		$cellCount = 0;
		
		// カレンダーの日付部分を生成
		for ($i = 0; $i < 6; $i++) {
			echo '<tr>';
			for ($j = 0; $j < 7; $j++) {
				$cellCount++;
				
				// セルのスタイル
				$cellStyle = 'background-color: white; vertical-align: top; min-height: 120px; padding: 5px; border: 1px solid #ddd; max-width: 100px; min-width: 100px; overflow: hidden;';
				
				if ($cellCount <= $firstDayOfMonth || $dayCount > $daysInMonth) {
					// 空のセル
					echo '<td style="' . $cellStyle . '"></td>';
				} else {
					// 日付を表示
					echo '<td style="' . $cellStyle . '">';
					echo '<div style="font-size: 16px; margin-bottom: 5px;">' . $dayCount . '</div>';
					echo '<div style="min-height: 100px;"></div>';
					echo '</td>';
					$dayCount++;
				}
			}
			echo '</tr>';
			
			// 月の最終日を過ぎたら終了
			if ($dayCount > $daysInMonth) {
				break;
			}
		}
	}

	public function show() {
		$year = $this->year;
		$month = $this->month;
		$firstDayOfMonth = new DateTime("$year-$month-01");
		$lastDayOfMonth = new DateTime("$year-$month-" . $firstDayOfMonth->format('t'));
		
		$firstDayOfWeek = clone $firstDayOfMonth;
		// 月曜日から開始するように調整
		if ($firstDayOfMonth->format('N') == 7) { // 日曜日の場合
			$firstDayOfWeek->modify('+1 day'); // 月曜日に移動
		} else {
			$firstDayOfWeek->modify('last monday'); // 前の月曜日に移動
		}
		
		$lastDayOfWeek = clone $lastDayOfMonth;
		// 土曜日で終わるように調整
		if ($lastDayOfMonth->format('N') < 6) {
			$lastDayOfWeek->modify('next saturday');
		}
		
		$currentDay = clone $firstDayOfWeek;
		$calendar = '<tbody>';
		
		while ($currentDay <= $lastDayOfWeek) {
			if ($currentDay->format('N') == 1) { // 月曜日の場合
				$calendar .= '<tr>';
			}
			
			if ($currentDay->format('N') >= 1 && $currentDay->format('N') <= 6) { // 月曜から土曜まで
				$dayClass = '';
				if ($currentDay->format('N') == 6) { // 土曜日
					$dayClass = 'saturday';
				}
				
				if ($currentDay->format('Y-m') == "$year-$month") {
					$calendar .= '<td class="' . $dayClass . '" style="height: 100px; border: 1px solid #ddd; vertical-align: top; padding: 5px;">';
					$calendar .= $this->createDateCell($currentDay);
					$calendar .= '</td>';
				} else {
					$calendar .= '<td class="other-month ' . $dayClass . '" style="height: 100px; border: 1px solid #ddd; vertical-align: top; padding: 5px; background-color: #f9f9f9;">';
					$calendar .= $this->createDateCell($currentDay);
					$calendar .= '</td>';
				}
			}
			
			if ($currentDay->format('N') == 6) { // 土曜日の場合
				$calendar .= '</tr>';
			}
			
			$currentDay->modify('+1 day');
		}
		
		$calendar .= '</tbody>';
		return $calendar;
	}

}









?>


