<?php
/*******************************************************************************
	システム初期化
																Fellow System
--------------------------------------------------------------------------------
 No.│   日付   │区分│						内  容
━━┿━━━━━┿━━┿━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 001│2016.05.17│新規│[V1.00] 中田
 002│2016.06.04│追加│[V1.00] 越智	コピーライトを$Pageにセット
*******************************************************************************/
ini_set('include_path', 'cmn:../cmn:../../cmn:'.ini_get('include_path'));

// サイト内変数定義
include_once 'config.php';

include_once 'common.php';
include_once 'template.php';
// include_once 'dbmysql.php';
include_once 'dbmysqli.php';
include_once 'dbconfig.php';

/*******************************************************************************
サーバ文字セット
　サーバの文字セットを定義する。
*******************************************************************************/
$ServerCharSet = 'UTF-8';

/*******************************************************************************
PHPソース文字セット
　PHPソースの文字セットを定義する。
*******************************************************************************/
$PhpCharSet = 'UTF-8';

/*******************************************************************************
HTML文字セット
　HTMLの文字セットを定義する。
*******************************************************************************/
$CharSet = 'UTF-8';

/*******************************************************************************
	DB接続リソース
*******************************************************************************/
$Db = NULL;

/*******************************************************************************
	曜日
*******************************************************************************/
$DayOfWeek = array( '日','月','火','水','木','金','土' );

/*******************************************************************************
	年
*******************************************************************************/
$start_year = '2010';
$last_year = date('Y')+'8';

/*******************************************************************************
	メールアドレス
*******************************************************************************/

$to_mail   = 'nakata@fellow.co.jp';
$from_mail = 'nakata@fellow.co.jp';

/*******************************************************************************	// 004 --> 追加
	アクセス種別

*******************************************************************************/
/*
$TypeAccess = array(
	'I' => 'ログイン'
,	'O' => 'ログアウト'
);

$TypePage = array();

$InfPage = array(
	'0' => array( 'php'=>'index.php',                               'name'=>'トップ' )

,	'1' => array( 'php'=>'event-info/eventinfo-mng.php',            'name'=>'イベント情報管理：一覧' )
,	'2' => array( 'php'=>'event-info/eventinfo-mng-detail.php',     'name'=>'イベント情報管理：詳細' )

,	'3' => array( 'php'=>'news/news-mng.php',                       'name'=>'新着情報管理：一覧' )
,	'4' => array( 'php'=>'news/news-mng-detail.php',                'name'=>'新着情報管理：詳細' )

,	'5' => array( 'php'=>'link/link-mng.php',                       'name'=>'リンク管理：一覧' )
,	'6' => array( 'php'=>'link/link-mng-detail.php',                'name'=>'リンク管理：詳細' )

,	'7' => array( 'php'=>'self-pro/index.php',                      'name'=>'自主事業一覧' )
,	'8' => array( 'php'=>'shared-use/index.php',                    'name'=>'主道場空き状況' )
,	'9' => array( 'php'=>'media/index.php',                         'name'=>'画像管理' )
,	'a' => array( 'php'=>'publicity/index.php',                     'name'=>'武道館だより' )
,	'b' => array( 'php'=>'download/index.php',                      'name'=>'各種ダウンロード' )
,	'c' => array( 'php'=>'data/index.php',                          'name'=>'資料管理' )
,	'd' => array( 'php'=>'config/config.php',                       'name'=>'構成定義' )
,	'e' => array( 'php'=>'access/index.php',                        'name'=>'アクセス・ログ' )
);

foreach($InfPage as $k=>$v) {
	$TypePage[$k] = $v['php'];
	$TypeAccess[$k] = $v['name'];
}

$TypePage2 = array_flip($TypePage);

$TypeAccess2 = array_flip($TypeAccess);												// 004 <--
*/
/*******************************************************************************
	ページ管理情報
*******************************************************************************/
$Page = array(
	'lines'			=> 20								// 一覧表表示行数
,	'phpst'			=> 120								// PHPセッション有効時間
,	'phpsp'			=>									// PHPセッション・ファイル保存パス
//'/var/www/html/tmp/'
'C:\intern\tmp'
,	'auth'			=> NULL								// 認証情報
,	'top'			=> FALSE							// トップ・ページ状態
,	'base'			=> NULL								// ベース・ページURL
,	'css'			=> NULL								// CSSファイル名
,	'focus'			=> NULL								// フォーカス情報
,	'UA'			=>									// ユーザ・エージェント
		htmlspecialchars(@$_SERVER['HTTP_USER_AGENT'])
,	'URL'			=> NULL								// URL
,	'HTTPS'			=>									// HTTPS
		((@$_SERVER['HTTPS'] == 'on') || (@$_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https'))
,	'referer'		=>									// HTTP_REFERER
		htmlspecialchars(@$_SERVER['HTTP_REFERER'])
,	'request_uri'	=>									// URI
		htmlspecialchars(@$_SERVER['REQUEST_URI'])
,	'script_name'	=>									// スクリプト名
		htmlspecialchars(@$_SERVER['SCRIPT_NAME'])
,	'IPaddress'		=>									// IPアドレス　002-->
		htmlspecialchars(@$_SERVER['REMOTE_ADDR'])
,	'scname'		=> NULL
,	'base_name'		=>									// ベース名
		basename(@$_SERVER['SCRIPT_NAME'])
,	'root'			=> NULL								// ルート・パス(カレント・ディレクトリからの相対パス)
,	'path'			=> NULL								// ルート・パス
,	'TOP'			=> FALSE							//トップページ判定
,	'iPhone'		=> FALSE							// iPhone状態
,	'iPad'			=> FALSE							// iPad状態
,	'iOS'			=> FALSE							// iOS(iPhone/iPad)状態
,	'cell'			=> FALSE							// 携帯電話アクセス状態
,	'cpimgw'		=> 230								// 携帯画像最大幅[px]
,	'icimgw'		=> 400								// 個人登録証写真画像最大幅[px]
,	'rsintrvl'		=> 30								// 予約時間間隔(分単位)
,	'ivintrvl'		=> 5								// 個人利用予約時間間隔(分単位)　予約時間間隔の約数
,	'error'			=> array()							// エラー状態
,	'login'			=> array()							// ログイン状態
,	'edit'			=> FALSE							// 編集状態
,	'news'			=> FALSE							// NEWS用JQueryUIの呼び出し
,	'vpWidth'		=> 820								// iOS viewport width
,	'selYear'		=> array()							// 検索領域年数
,	'selMonth'		=> array()							// 検索領域月数
,	'lat'		=> NULL									//
,	'lng'		=> NULL									//
,	'input'			=> array(							// inputタグ属性
		'text'			=> 'text'
	,	'srch'			=> 'text'
	,	'url'			=> 'text'
	,	'mail'			=> 'text'
	,	'tel'			=> 'text'
	,	'nmbr'			=> 'text'
	,	'pswd'			=> 'password'
	,	'date'			=> 'text'
	,	'time'			=> 'text'
	)
,	'iiphone'		=> array(							// iPhone用inputタグ属性
		'text'			=> 'text'
	,	'srch'			=> 'search'
	,	'url'			=> 'url'
	,	'mail'			=> 'email'
	,	'tel'			=> 'text'
	,	'nmbr'			=> 'text" pattern="[0-9]*'
	,	'pswd'			=> 'password'
	,	'date'			=> 'text'
	,	'time'			=> 'text'
	)
,	'iipad'			=> array(							// iPad用inputタグ属性
		'text'			=> 'text'
	,	'srch'			=> 'search'
	,	'url'			=> 'url'
	,	'mail'			=> 'email'
	,	'tel'			=> 'tel'
	,	'nmbr'			=> 'text" pattern="[0-9]*'
	,	'pswd'			=> 'password'
	,	'date'			=> 'text" pattern="[0-9]*'
	,	'time'			=> 'text" pattern="[0-9]*'
	)
);

include_once 'phpst.php';


/*******************************************************************************
	初期化処理
--------------------------------------------------------------------------------
	init($params);
--------------------------------------------------------------------------------
	$params				パラメータ名配列
--------------------------------------------------------------------------------
	更新	$Page
--------------------------------------------------------------------------------
　初期化処理を行う。
*******************************************************************************/
function init($params=array())
{
	static	$StsInit = FALSE;
	global	$PhpCharSet, $CharSet, $Page, $MsgUpdate, $MsgError, $MsgAlert;
	global	$TypeAccess, $TypePage2;

	// 初期化していない
	if(!$StsInit) {

		// 初期化済みをセット
		$StsInit = TRUE;

		//Mauthの情報を保存
//		$Page['Mauth'] = @$_SESSION['Mauth'];
		// システムのログイン状態を確認
		session_start();
		$Page['login']['usidno'] = @$_SESSION['login']['usidno'];
		session_write_close();

		// 動作環境を設定
		update_sys_session(TRUE);
		ini_set('max_execution_time', 30);						// タイムアウト時間[s]
		ini_set('session.gc_maxlifetime', $Page['phpst']*60);	// GC実行待機時間[s] デフォルト1440
		ini_set('session.gc_probability', 1);					// GC実行確立分子
		ini_set('session.gc_divisor', 1);						// GC実行確立分母

		// デバッグ状態を初期化
		init_deb_status($Page['phpsp'], 'FeLlOw', (@$_REQUEST['FeLlOw'] ? $_REQUEST['FeLlOw'] : NULL));
//		init_deb_status();
		$Page['DEBUG'] = ($_SESSION['DEBUG'] != NULL);

		// ページ管理情報を調整
		$Page['base'] = @$_SESSION['base'];
		$parts = explode('.', $Page['base_name']);
		array_pop($parts);
		$css = 'ie_' . join('.', $parts) . '.css';
		if(file_exists($css)) {
			$Page['css'] = $css;
		}

		// マルチバイト文字環境を設定
		ini_set('mbstring.language', 'Japanese');
//		ini_set('mbstring.internal_encoding', $PhpCharSet);
//		ini_set('mbstring.http_input', 'pass');
//		ini_set('mbstring.http_output', 'pass');
		ini_set('mbstring.encoding_translation', 'off');
		ini_set('mbstring.substitute_character', 'none');
		ini_set('mbstring.detect_order', 'SJIS,EUC-JP,JIS,UTF-8,ASCII');

		// 出力バッファリングを設定
		ob_start('convert_code');

		// 入力の余分な空白を削除
		foreach($_REQUEST as $k=>$v) {
			convert_string($_REQUEST[$k], 'trim', FALSE);
		}
	}

/*	// ログアウト処理
	if(@$_REQUEST['logout']) {
		unset($_SESSION['login']);
	}
*/
	// ログアウト処理
//	if(@$_REQUEST['logout'] || (!$Page['login']['usidno'] && (@$_SESSION['login']['info']['usidno'] >= 0))) {
//	if(@$_REQUEST['logout'] || !$Page['login']['user']) {
	if(@$_REQUEST['logout']) {
		$Page['login']['logout'] = $_SESSION['login']['info']['usidno'];
		unset($_SESSION['login']);
		$Page['login']['usidno'] = FALSE;
		update_sys_session();
	}
	else {
		$Page['login']['logout'] = FALSE;
	}

	// ページ管理情報などをセット
	$MsgUpdate = $MsgError = $MsgAlert = NULL;
	$Page['params'] = $params = array_merge($params, array('user','mode','update','page','dtpage'));
	foreach($params as $name) {
		global	$$name;
		$$name = $Page[$name] = @$_REQUEST[$name];
	}
	$Page['sname'] = session_name();
	$Page['sid']   = session_id();

	$temp = explode('.', $Page['base_name']);
	$Page['scname'] = $temp[0];

	$temp = explode('/', $_SERVER['REQUEST_URI']);
	$id = ($Page['HTTPS'] ? 2 : 1);
//	$id = 2;

	switch($temp[$id]) {
		case DOCUMENT_ROOT_DIR_NAME	: $dbname = 'fellow_web_db';  $Page['env'] = '開発環境';  break;
		default					: $dbname = 'fellow_web_db';  $Page['env'] = NULL;        break;
	}
	if(!$Page['HTTPS']){
		$sub	 = ($temp[$id]		 == DOCUMENT_ROOT_DIR_NAME ? $temp[$id + 1] : $temp[$id]);
		$admin = ($temp[$id + 1] == 'fs-admin'		 ? $temp[$id + 2] : $temp[$id + 1]);
	}else{
		$sub = ($temp[$id] == DOCUMENT_ROOT_DIR_NAME ? $temp[$id + 2] : $temp[$id + 1]);
		$admin = ($temp[$id + 1] == 'fs-admin' ? $temp[$id + 2] : $temp[$id + 1]);
	}
	$Page['dbname'] = $dbname;

	foreach(array(
		'sub','company',
		'config','news') as $name) {
		// 管理画面内のディレクトリ名チェック
		if($admin == $name) {
			$Page['root'] = '../';
			$Page['path'] = '../../';
			break;
		}
		// フロントページでのディレクトリ名チェック
		if($sub == $name) {
			$Page['root'] = '../';
			$Page['path'] = '../../';
			break;
		}
	}

	$parts = explode('/', $_SERVER['SCRIPT_NAME']);									// 004 -->
	$parts = array_slice($parts, ($parts[2] == 'new-fs-dev' ? 4 : 3));
	$script = join('/', $parts);
//	$Page['access']['php'] = str_replace('.php', '', $script);
	$Page['access']['php'] = $script;
	$Page['access']['type'] = @$TypePage2[$Page['access']['php']];
	$Page['access']['name'] = @$TypeAccess[$Page['access']['type']];				// 004 <--

	// コピーライトをセット
	$Page['copyright'] = get_copyright();
}

/*******************************************************************************
	システム・セッション変数更新
--------------------------------------------------------------------------------
	update_sys_session($save);
--------------------------------------------------------------------------------
	$save				保存要求
--------------------------------------------------------------------------------
　システムのセッション変数を更新する。
*******************************************************************************/
function update_sys_session($save=FALSE)
{
	static	$Params = array(
		'session.save_path'
	,	'session.name'
	,	'session.gc_maxlifetime'
	,	'session.gc_probability'
	,	'session.gc_divisor'
	);
	global	$Page;

	// 保存要求
	if($save) {

		// システム・セッション情報を保存
		foreach($Params as $name) {
			$Page['php'][$name] = ini_get($name);
		}
		$Page['php']['sid'] = session_id();
	}

	// 更新要求
	else {

		// システム・セッションを更新
		foreach($Params as $name) {
			$params[$name] = ini_get($name);
		}
		$params['sid'] = session_id();
		session_write_close();

		foreach($Params as $name) {
			ini_set($name, $Page['php'][$name]);
		}
		$Page['php']['sid'] && session_id($Page['php']['sid']);
		session_start();
		$_SESSION['login']['usidno'] = $Page['login']['usidno'];
		session_write_close();

		foreach($Params as $name) {
			ini_set($name, $params[$name]);
		}
		$params['sid'] && session_id($params['sid']);
		session_start();
	}
}

/*******************************************************************************
	SSL状態判定
--------------------------------------------------------------------------------
	check_ssl($ssl);
--------------------------------------------------------------------------------
	$ssl				SSL指定 (TRUE:SSL)
--------------------------------------------------------------------------------
　SSL状態が異なる場合、SSL状態を切り替える。
*******************************************************************************/
function check_ssl($ssl)
{
	global	$Page;

	// SSL状態が異なる
	if(($ssl && !$Page['HTTPS']) || (!$ssl && $Page['HTTPS'])) {

	// SSL状態を切り替え
	if($ssl){
		$temp = ($ssl ? 's' : NULL);
		header("Location: http{$temp}://{$_SERVER['SERVER_NAME']}{$_SERVER['REQUEST_URI']}");
	}else{
		header("Location:  http://{$_SERVER['SERVER_NAME']}{$_SERVER['REQUEST_URI']}");
	}
		// 終了*
		exit(0);
	}
}

/*******************************************************************************
	DB接続
--------------------------------------------------------------------------------
	connect_db($error, $login);
--------------------------------------------------------------------------------
	$error				エラー処理関数名
	$login				ログイン判定指定
--------------------------------------------------------------------------------
	更新	$Db $Page['dbname']
--------------------------------------------------------------------------------
　DBに接続する。
*******************************************************************************/
function connect_db($error=NULL, $login=FALSE)
{
	static	$StsInit = FALSE;
	global	$Db, $Page, $PhpCharSet, $CharSet, $MsgError;
	global	$TypeAccess2;

	// DBに接続
	$Db = db_connect(array(
		'conn' => array(
			'db'	=> $Page['dbname']
		,	'host'	=> 'localhost'
		,	'user'	=> 'root'
//		,	'pswd'	=> 'fellowsystem030207'
		,	'pswd'	=> ''
		,	'char'	=> 'utf8'
		)
	,	'error'	=> $error
	));

	// ログアウト判定
/*	if(@$Page['login']['logout']) {
		$sql = 'insert into access'
			. '(acsscreate'
			. ',acssid'
			. ',acssname'
			. ',acsstype'
			. ',acssipad'
			. ')values'
			. '(current_timestamp'
			. ",'{$Page['login']['logout']}'"
			. ",'{$Page['Mauth']['status']['name']}'"
			. ",'{$TypeAccess2['ログアウト']}'"
			. ",'{$Page['IPaddress']}'"
			. ')'
		;
		db_query($Db, $sql);
	}*/

	// ログイン判定
	foreach(array('userid','passwd','mode') as $name) {
		$$name = @$_REQUEST[$name];
		convert_string($$name, 'db_escape_string', FALSE);
	}

	$Page['param']['userid'] = $userid;

	if($login) {
/*		if(!$Page['HTTPS']) {
			header("Location: https://{$_SERVER['SERVER_NAME']}{$_SERVER['REQUEST_URI']}");
			exit(0);
		}*/
		if(!@$_SESSION['login']['user']) {
			$_SESSION['login']['user'] = NULL;
			if($mode == 'login') {
				$sql = 'select *'
					. ' from config'
					. " where cfstsdel='0'"
					. " and ususerid='{$userid}'"
					. " and uspasswd='{$passwd}'"
				;
				if(!$_SESSION['login']['user']) {
					$dev = get_config($Db, 'administrator', 'cfvalue');
					$parts = explode(' ', $dev);
					if(($parts[0] == $userid) && ($parts[1] == $passwd)) {
						$_SESSION['login']['user']  = $userid;
						$_SESSION['login']['type']  = 'administrator';
						$_SESSION['login']['info']  = array('usidno' => 1);
						$_SESSION['login']['dev']   = FALSE;
						$_SESSION['login']['admin'] = TRUE;
					}
				}
				if(!$_SESSION['login']['user']) {
					$dev = get_config($Db, 'editor', 'cfvalue');
					$parts = explode(' ', $dev);
					if(($parts[0] == $userid) && ($parts[1] == $passwd)) {
						$_SESSION['login']['user']  = $userid;
						$_SESSION['login']['type']  = 'editor';
						$_SESSION['login']['info']  = array('usidno' => 3);
						$_SESSION['login']['dev']   = FALSE;
						$_SESSION['login']['admin'] = FALSE;
						$_SESSION['login']['editor'] = TRUE;
					}
				}
				if(!$_SESSION['login']['user']) {
					$dev = get_config($Db, 'developer', 'cfvalue', '1');
					$parts = explode(' ', $dev);
					if(($parts[0] == $userid) && ($parts[1] == $passwd)) {
						$_SESSION['login']['user']  = $userid;
						$_SESSION['login']['type']  = 'developer';
						$_SESSION['login']['info']  = array('usidno' => 2);
						$_SESSION['login']['dev']   = TRUE;
						$_SESSION['login']['admin'] = TRUE;
					}
				}
				if(!$_SESSION['login']['user']) {
					$Page['edit'] = TRUE;
					if(!$MsgError) {
						$MsgError = '未登録、もしくはパスワードが違います。';
					}
				}/*else{
					$sql = 'insert into access'
						. '(acsscreate'
						. ',acssid'
						. ',acssname'
						. ',acsstype'
						. ',acssipad'
						. ')values'
						. '(current_timestamp'
						. ",'{$_SESSION['login']['info']['usidno']}'"
						. ",'{$Page['Mauth']['status']['name']}'"
						. ",'{$TypeAccess2['ログイン']}'"
						. ",'{$Page['IPaddress']}'"
						. ')'
					;
					db_query($Db, $sql);
				}*/
			}
			else {
				$Page['edit'] = TRUE;
			}
		}
	}
	$Page['login'] = @$_SESSION['login'];
/*
	// 暗号化認証情報を復号
	if($Page['user']) {
		$ok = decode_auth($id, $pw, $Page['user']);
		if($ok) {
			$sql = 'select *'
				. ' from cmnuser'
				. " where usstsdel='0'"
				. ' and coalesce(usauth2,0)<>0'
				. " and ususerid='{$id}'"
			;
			$ok = db_get_data($user, $Db, $sql);
			if($ok) {
				$user = $user[0];
			}
		}
		if($ok && ($pw == md5($user['uspasswd']))) {
			$Page['auth'] = $user;
		}
	}
*/
	// 携帯情報などをセット
	$sql = 'select cfvalue from config'
		. " where cfstsdel='0'"
		. " and cflabel like 'cellPhone%'"
	;
	$rows = db_get_data($cell, $Db, $sql);
	if($rows) {
		foreach($cell as $value) {
			if(preg_match($value['cfvalue'], $Page['UA'])) {
				$Page['cell'] = TRUE;
				break;
			}
		}
	}
	if(isset($_REQUEST['pagetype'])) {
		$_SESSION['cell'] = ($_REQUEST['pagetype'] == 'cp');
		$_SESSION['pagetype'] = $_REQUEST['pagetype'];
		if(empty($_SESSION['pagetype'])) {
			unset($_SESSION['cell']);
			$_SESSION['cell'] = $Page['cell'];
		}
	}
	else if(!isset($_SESSION['cell'])) {
		$_SESSION['cell'] = $Page['cell'];
	}
	$Page['iPhone'] = preg_match('/iPhone/', $Page['UA']);
	$Page['iPad'] = preg_match('/iPad/', $Page['UA']);
	$Page['iOS'] = ($Page['iPhone'] || $Page['iPad']);
	$Page['URL'] = ($Page['HTTPS'] ? 'https' : 'http').'://'.$_SERVER['HTTP_HOST'].$Page['request_uri'];
	if($Page['iPhone']) {
		$Page['input'] = $Page['iiphone'];
	}
	else if($Page['iPad']) {
		$Page['input'] = $Page['iipad'];
	}
	$temp = explode('/', $Page['script_name']);
/*	switch($temp[2]) {
	case 'rcp':
	case 'adm':
		$_SESSION['cell'] = FALSE;
		break;
	}*/
	$Page['cpimgw'] = get_config($Db, 'widthCellPhone', 'cfvalue');
	$Page['icimgw'] = get_config($Db, 'widthCardPhoto', 'cfvalue');

	// 未初期化状態
	if(!$StsInit) {
		$StsInit = TRUE;

		// 入力文字コード変換関数
		function convert_input(&$str, $dst, $src) {
			if(is_array($str)) {
				foreach($str as $k=>$v) {
					convert_input($v, $dst, $src);
					$str[$k] = $v;
				}
			}
			else {
				$str = mb_convert_encoding($str, $dst, $src);
			}
		}

		// 入力とPHPの文字コードが異なる
		$charset = ($_SESSION['cell'] ? 'SJIS' : $CharSet);
		if($charset != $PhpCharSet) {

			// 入力文字コードを変換
			if($charset == 'SJIS') {
				$charset = 'SJIS-win';
			}
			convert_input($_REQUEST, $PhpCharSet, $charset);
			foreach($Page['params'] as $name) {
				global	$$name;
				$$name = $Page[$name] = @$_REQUEST[$name];
			}
		}
	}

	// ログインが必要であれば、ログイン・ページを表示して終了
	if($login && !$_SESSION['login']['user']) {
		$title = "ログインページ";
//		check_ssl(TRUE);
		$top = TRUE;
		$Admin = TRUE;

		foreach(array('title') as $name) {
			convert_string($$name, 'htmlspecialchars', TRUE);
			$Page[$name] = $$name;
		}
		$Page['contents'] = "tpl/login.tpl.html";
		$tpl = new HtmlTemplate();
		$code = $tpl->convert("tpl/index.tpl.html");
		eval($code);
		exit(0);
	}

	// ログインしていなければ終了
	if(!@$_SESSION['login']['info']['usidno']) {
		return;
	}
	// アクセス情報を記録
/*	$sql = 'insert into access'
		. '(acsscreate'
		. ',acssid'
		. ',acssname'
		. ',acsstype'
		. ',acssipad'
		. ')values'
		. '(current_timestamp'
		. ",'{$_SESSION['login']['info']['usidno']}'"
		. ",'{$Page['Mauth']['status']['name']}'"
		. ",'{$Page['access']['type']}'"
		. ",'{$Page['IPaddress']}'"
		. ')'
	;
	db_query($Db, $sql);*/
}

/*******************************************************************************
	DBエラー処理
--------------------------------------------------------------------------------
	dberr_main($msg, $sql);
--------------------------------------------------------------------------------
　DBエラーを処理する。
*******************************************************************************/
function dberr_main($msg, $sql=NULL)
{
	global	$MsgError, $Db;

	// エラー状態を更新
	$MsgError .= ($MsgError ? '<br>' : NULL).'DBアクセス・エラーです.'.($_SESSION['DEBUG'] ? "<br>{$sql}" : NULL);

	// ロールバック
	db_rollback($Db);
}
/*******************************************************************************
	検索領域年数
--------------------------------------------------------------------------------
	select_year();
--------------------------------------------------------------------------------
　検索対象になる年数を生成する。
*******************************************************************************/

function select_year(){
	global $start_year ,$last_year;
	$year =array();
	for($i = $start_year ; $i <= $last_year ; $i ++){
		$year[] = $i;
		}
		return $year;
	}

/*******************************************************************************
	検索領域月数
--------------------------------------------------------------------------------
	select_month();
--------------------------------------------------------------------------------
　検索対象になる月数を生成する。
*******************************************************************************/

function select_month(){
	$month =array();
	for($i = 1 ; $i <= 12 ; $i ++){
		$month[] = $i;
		}
		return $month;
	}

/*******************************************************************************
	検索領域日数
--------------------------------------------------------------------------------
	select_day();
--------------------------------------------------------------------------------
　検索対象になる日数を生成する。
*******************************************************************************/

function select_day(){
	$month =array();
	for($i = 1 ; $i <= 31 ; $i ++){
		$day[] = $i;
		}
		return $day;
	}

/*******************************************************************************
	カレントディレクトリの判定
--------------------------------------------------------------------------------
	current();
--------------------------------------------------------------------------------
　カレントディレクトリを判定する。
*******************************************************************************/

function current_level(){
	$tmp = explode('/', $_SERVER['SCRIPT_NAME']);

	switch($tmp[1]) {
		case DOCUMENT_ROOT_DIR_NAME : $current = $tmp[2];  break;
		default          : $current = $tmp[1];  break;
	}

	return $current;
}

/*******************************************************************************
	同一ファイルの名前変更
--------------------------------------------------------------------------------
	unique_filename();
--------------------------------------------------------------------------------
　同一ファイル名がある場合、(n)付に書き換える。
*******************************************************************************/

function unique_filename($org_path, $num=0){

	if( $num > 0){
		$info = pathinfo($org_path);
		$path = $info['dirname'] . "/" . $info['filename'] . "(" . $num . ")";
		if(isset($info['extension'])) $path .= "." . $info['extension'];
	} else {
		$path = $org_path;
	}

	if(file_exists($path)){
		$num++;
		return unique_filename($org_path, $num);
	} else {
		return $path;
	}
}

/*******************************************************************************
	ファイルサイズの変換
--------------------------------------------------------------------------------
	byte_format();
--------------------------------------------------------------------------------
　ファイルサイズ(Byte)を単位で示す。
*******************************************************************************/

function byte_format($size, $dec=-1, $separate=false){
	$units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
	$digits = ($size == 0) ? 0 : floor( log($size, 1024) );

	$over = false;
	$max_digit = count($units) -1 ;

	if($digits == 0){
		$num = $size;
	} else if(!isset($units[$digits])) {
		$num = $size / (pow(1024, $max_digit));
		$over = true;
	} else {
		$num = $size / (pow(1024, $digits));
	}

	if($dec > -1 && $digits > 0) $num = sprintf("%.{$dec}f", $num);
	if($separate && $digits > 0) $num = number_format($num, $dec);

	return ($over) ? $num . $units[$max_digit] : $num . $units[$digits];
}

/*******************************************************************************
	コピーライト用の年数を返却
--------------------------------------------------------------------------------
	get_copyright($format)
--------------------------------------------------------------------------------
	$format				表示フォーマット
	$then				開始年
--------------------------------------------------------------------------------
	コピーライトを作成して返却
*******************************************************************************/

function get_copyright($format = '%s-%s', $then = 2006) {
	$now = date('Y');
	return ($then < $now) ? sprintf($format, $then, $now) : $then;
}

/*******************************************************************************
	get_directory_uri($key)
	--------------------------------------------------------------------------------
		$key		ディレクトリを一意に特定するキー
	--------------------------------------------------------------------------------
　キーを元に、ファイルの格納ディレクトリパスを返す
*******************************************************************************/

function get_directory_uri($key = null){
	// ディレクトリリスト定義
	$_dir_list = array(
		'news_img' => 'images/uploads/'
		, 'test' => 'images/test/'
	);
	$temp = explode('/', $_SERVER['REQUEST_URI']);

	// 開発環境, 管理画面判定
	$_judge = array(
//		'dev' => in_array('new-fs-dev', $temp)
		'admin' => in_array('fs-admin', $temp)
	);
	$_dir_list = array_map(function($dir) use ($_judge){
//		if ($_judge['dev'])		 $dir = '../' . $dir;
		if ($_judge['admin'])	$dir = '../' . $dir;
		return $dir;
	}, $_dir_list);

	// 一覧（全部）取得
	if (is_null($key)) 	return $_dir_list;

	// キーの存在確認
	if (!array_key_exists($key, $_dir_list))	 return false;

	return $_dir_list[$key];
}
