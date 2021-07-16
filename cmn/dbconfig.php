<?php
/*******************************************************************************
	構成定義関数
																Fellow System
--------------------------------------------------------------------------------
 No.│   日付   │区分│                        内  容
━━┿━━━━━┿━━┿━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 001│2010.11.15│新規│[V1.00] 金井
*******************************************************************************/

/*******************************************************************************
	構成定義値取得
--------------------------------------------------------------------------------
	$config = get_config($db, $label, $name, $developer);
--------------------------------------------------------------------------------
	$config				構成定義値
	$db					DB接続リソース
	$label				ラベル
	$name				フィールド名
	$developer			開発者専用状態
--------------------------------------------------------------------------------
　構成定義値を返す。
*******************************************************************************/
function get_config($db, $label, $name=NULL, $developer='0')
{
	// 構成定義値を検索
	if($db) {
		$sql = 'select cfidno,cfcreate,cfupdate,cflabel,cfname,cfvalue,cfnote'
			. ' from config'
			. " where cfstsdel='0'"
			. " and cflabel='".db_escape_string($label)."'"
		;
		db_get_data($data, $db, $sql);
	}
	else {
		$data = array();
	}

	// 構成定義値を返す
	return($name ? @$data[0][$name] : @$data[0]);
}

/*******************************************************************************
	構成定義値設定
--------------------------------------------------------------------------------
	$ok = set_config($db, $config);
--------------------------------------------------------------------------------
	$ok					正常終了状態(FALSE:同一ラベルあり)
	$db					DB接続リソース
	$config				構成定義値
		cfidno				構成定義ID
								>0	該当IDの構成定義を変更
								=0	追加
								<0	該当ラベルの構成定義を追加または変更
		cfdevlop			管理者専用
		cfname				名称
		cflabel				ラベル
		cfvalue				値
		cfnote			備考
--------------------------------------------------------------------------------
　構成定義値を設定する。
*******************************************************************************/
function set_config($db, $config)
{
	$ok = TRUE;

	// 構成定義値を調整
	foreach(array('cfname','cflabel','cfvalue','cfnote') as $name) {
		$config[$name] = db_escape_string($config[$name]);
	}

	// トランザクション開始
	db_begin($db);

	// 構成定義テーブルをロック
	db_lock($db, 'config');

	// 該当ID構成定義変更指定
	if($config['cfidno'] > 0) {

		// 構成定義を検索
		$sql = 'select cfidno from config'
			. " where cfstsdel='0'"
			. " and cflabel='{$config['cflabel']}'"
			. " and cfidno<>{$config['cfidno']}"
		;
		$rows = db_get_data($data, $db, $sql);

		// 同一ラベルが存在しなければ、構成定義値を更新
		if(!$rows) {
			$sql = 'update config set'
				. ' cfupdate=current_timestamp'
				. ",cflabel='{$config['cflabel']}'"
				. ",cfname='{$config['cfname']}'"
				. ",cfvalue='{$config['cfvalue']}'"
				. ",cfnote='{$config['cfnote']}'"
				. ",cfdevlop='{$config['cfdevlop']}'"
				. " where cfidno={$config['cfidno']} and ("
				.     "cflabel<>'{$config['cflabel']}'"
				. " or cfname<>'{$config['cfname']}'"
				. " or cfvalue<>'{$config['cfvalue']}'"
				. " or cfnote<>'{$config['cfnote']}'"
				. " or cfdevlop<>'{$config['cfdevlop']}'"
				. ')'
			;
			db_query($db, $sql);
		}
		else {
			$ok = FALSE;
		}
	}

	// 構成定義追加指定
	else if($config['cfidno'] == 0) {

		// 構成定義を検索
		$sql = 'select cfidno from config'
			. " where cfstsdel='0'"
			. " and cfdevlop='{$config['cfdevlop']}'"
			. " and cflabel='{$config['cflabel']}'"
		;
		$rows = db_get_data($data, $db, $sql);

		// 同一ラベルが存在しなければ、構成定義値を追加
		if(!$rows) {
			$sql = 'insert into config '
				. '(cfcreate,cfstsdel,cflabel,cfname,cfvalue,cfnote,cfdevlop)'
				. ' values '
				. '(current_timestamp'
				. ",'0'"
				. ",'{$config['cflabel']}'"
				. ",'{$config['cfname']}'"
				. ",'{$config['cfvalue']}'"
				. ",'{$config['cfnote']}'"
				. ",'{$config['cfdevlop']}'"
				. ')'
			;
			db_query($db, $sql);
		}
		else {
			$ok = FALSE;
		}
	}

	// 該当ラベル構成定義変更指定
	else {

		// 構成定義を検索
		$sql = 'select cfidno from config'
			. " where cfstsdel='0'"
			. " and cflabel='{$config['cflabel']}'"
		;
		$rows = db_get_data($data, $db, $sql);

		// 同一ラベルありなら、構成定義値を更新
		if($rows) {
			$sql = 'update config set'
				. ' cfupdate=current_timestamp'
				. ",cfname='{$config['cfname']}'"
				. ",cfvalue='{$config['cfvalue']}'"
				. ",cfnote='{$config['cfnote']}'"
				. " where cfidno={$data[0]['cfidno']}"
			;
		}

		// 同一ラベルなしなので、構成定義値を追加
		else {
			$sql = 'insert into config '
				. '(cfcreate,cfstsdel,cflabel,cfname,cfvalue,cfnote)'
				. ' values '
				. '(current_timestamp'
				. ",'0'"
				. ",'{$config['cflabel']}'"
				. ",'{$config['cfname']}'"
				. ",'{$config['cfvalue']}'"
				. ",'{$config['cfnote']}'"
				. ')'
			;
		}
		db_query($db, $sql);
	}

	// コミット
	db_commit($db);

	// 正常終了状態を返す
	return($ok);
}

/*******************************************************************************
	構成定義削除
--------------------------------------------------------------------------------
	$ok = del_config($db, $label);
--------------------------------------------------------------------------------
	$ok					正常終了状態
	$db					DB接続リソース
	$label				ラベル
--------------------------------------------------------------------------------
　構成定義を削除する。
*******************************************************************************/
function del_config($db, $label)
{
	$ok = TRUE;

	// トランザクション開始
	db_begin($db);

	// 構成定義テーブルをロック
	db_lock($db, 'config');

	// 構成定義値を検索
	$sql = 'select cfidno from config'
		. " where cfstsdel='0'"
		. " and cflabel='".db_escape_string($label)."'"
	;
	$rows = db_get_data($data, $db, $sql);

	// データありなら、構成定義を削除
	if($rows) {
		$sql = 'update config set'
			. ' cfupdate=current_timestamp'
			. ",cfstsdel='1'"
			. " where cfidno={$data[0]['cfidno']}"
		;
		db_query($db, $sql);
	}

	// コミット
	db_commit($db);

	// 正常終了状態を返す
	return($ok);
}
?>
