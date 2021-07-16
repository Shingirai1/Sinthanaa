<?php
/*******************************************************************************
	共通関数
																Fellow System
--------------------------------------------------------------------------------
 No.│   日付   │区分│                        内  容
━━┿━━━━━┿━━┿━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 001│2011.12.10│新規│[V1.00] 金井
 002│2015.03.10│新規│[V1.01] 中田　PHP4環境でのjson_encode(),json_decode()生成
 003│2016.09.11│新規│[V1.01] 越智　リクエストデータ取得関数（get_request_data()）生成
*******************************************************************************/

/*******************************************************************************
	デバッグ状態初期化
--------------------------------------------------------------------------------
	init_deb_status($spath, $session);
--------------------------------------------------------------------------------
	$spath				セッション・ファイル保存パス
	$sname				セッション名
	$sid				セッションID
--------------------------------------------------------------------------------
	参照	$_REQUEST['DEBUG'] $PhpCharSet
	更新	$_SESSION['DEBUG'] $Debug['TimeStart']
--------------------------------------------------------------------------------
　デバッグ・スイッチを参照してデバッグ状態を初期化する。
　デバッグ・スイッチはダンプする変数名を[,]区切りで指定し、最後にデバッグ・キー
を記述する。

[例]
　http://abc.com/?DEBUG=_SESSION,_REQUEST,6090825
　　　　　　　　　　　　　　　　　　　　　~~~~~~~デバッグ・キー
デバッグ・キー
　形式：kYYMMDD
　　[YYMMDD]：日付(西暦)
　　[k] 　　：日付1桁毎の合計の1桁毎の合計の1の位
　　　　　　　2009/08/25 → 090825 → 0+9+0+8+2+5=24 → 2+4=6
*******************************************************************************/
function init_deb_status($spath=NULL, $sname=NULL, $sid=NULL)
{
	global	$Debug, $PhpCharSet;

	// 処理開始時刻を記録
	$Debug['TimeStart'] = gettimeofday();

	// セッションを開始
	$spath && ini_set('session.save_path', $spath);
	$sname && ini_set('session.name', $sname);
	$sid && session_id($sid);
	session_start();

	// PHPバージョン関連
	if(version_compare(PHP_VERSION, '5.1.0') >= 0) {
		date_default_timezone_set('Asia/Tokyo');
	}

	// デバッグ状態を初期化
	// デバッグ・パラメータあり
	if(isset($_REQUEST['DEBUG'])) {
		// 日付1桁毎の合計①
		$date = localtime(time(), TRUE);
		$date = sprintf('%02u%02u%02u', $date['tm_year'] % 100, $date['tm_mon'] + 1, $date['tm_mday']);
		$sum = 0;
		for($i = 0; $i < strlen($date); $i++) {
			$sum += $date[$i];
		}
		// ①1桁毎の合計
		$temp = $sum.'';
		$sum = 0;
		for($i = 0; $i < strlen($temp); $i++) {
			$sum += $temp[$i];
		}
		// 最後のパラメータが算出値と等しければ、デバッグ状態を更新
		$date = $sum % 10 . $date;
		$temp = explode(',', $_REQUEST['DEBUG']);
		$_SESSION['DEBUG'] = (array_pop($temp) == $date ? join(',', $temp) : NULL);
	}
	// デバッグ・パラメータなし
	else {
		// デバッグ状態がセットされてなければデバッグ状態をクリア
		!isset($_SESSION['DEBUG']) && $_SESSION['DEBUG'] = NULL;
	}

	// PHPエラー出力を設定
	if($_SESSION['DEBUG']) {
		error_reporting(E_ALL);
		ini_set('display_errors', '1');
	}
	else {
		error_reporting(E_ERROR | E_CORE_ERROR | E_USER_ERROR);
		ini_set('display_errors', '0');
	}
}

/*******************************************************************************
	文字コード変換
--------------------------------------------------------------------------------
	$html = convert_code($html);
--------------------------------------------------------------------------------
	$html				HTMLデータ
--------------------------------------------------------------------------------
	参照	$_REQUEST['DEBUG'] $CharSet $Debug
--------------------------------------------------------------------------------
　HTMLデータの文字コードを変換する。
　デバッグ状態の場合、処理時間とダンプ結果を</body>と</html>の間に付加する。
*******************************************************************************/
function convert_code($html)
{
	static $PtnA = array(			// Aタグ置換対象検索パターン
		'/<\s*(a)\s[^>]*(href)\s*=\s*"([^\"]*)"/si'
	,	'/<\s*(a)\s[^>]*(href)\s*=\s*\'([^\']*)\'/si'
	,	'/<\s*(a)\s[^>]*(href)\s*=\s*([^\"\'>]*)\s*/si'
	);
	static $PtnIMG = array(			// IMGタグ
		'/<\s*(img)\s[^>]*(src)\s*=\s*"([^\"]*)"/si'
	,	'/<\s*(img)\s[^>]*(src)\s*=\s*\'([^\']*)\'/si'
	,	'/<\s*(img)\s[^>]*(src)\s*=\s*([^\"\'>]*)\s*/si'
	);
	static	$Call = 0;

	global	$PhpCharSet, $CharSet, $Debug;

	// 携帯ページ
	if($_SESSION['cell']) {

		// 文字コード
		$CharSet = 'SJIS';

		// 画像をリサイズ
		$html = preg_replace_callback($PtnIMG, 'resize_image', $html);

		// セッションIDを付加
		$sname = session_name();
		$sid = session_id();
		$html = preg_replace_callback($PtnA, 'add_sid', $html);
		$html = preg_replace('/<\s*form\s[^>]*>/si', "\$0<INPUT type=\"hidden\" name=\"{$sname}\" value=\"{$sid}\">", $html);
												   // ~~~ これは、パターン全体にマッチしたテキスト
	}

	// デバッグ・データを追加
	if($_SESSION['DEBUG']) {
		$end = gettimeofday();
		$start = $Debug['TimeStart'];
		$tmp = ($end['sec'] * 1000000 + $end['usec']) - ($start['sec'] * 1000000 + $start['usec']);
		$dlt = sprintf('%u.%06u[sec]', (int)($tmp / 1000000), $tmp % 1000000);
		$fmt = 'Y/m/d H:i:s.%.6s';
		$start = make_timestamp($fmt, $start);
		$end   = make_timestamp($fmt, $end);
		$ver = phpversion();
		$msg = dump_param($_SESSION['DEBUG'] ? explode(',', $_SESSION['DEBUG']) : array());
		$msg = <<<EOT
</footer>
<div id="debug" style="text-align:left; clear:both; font-family:monospace;">
<br>
*** PHP Version $ver ***<br>
開始 $start<br>
終了 $end<br>
時間 $dlt<br>
<br>
$msg
</div>
\n
EOT;
		//$html = preg_replace('/<\/BODY>\\s*<\/HTML>\\s*$/i', $msg, $html);
		$html = preg_replace('/<\/FOOTER>\\s/i', $msg, $html);
	}

	// HTMLデータの文字コードを変換
	if($CharSet != $PhpCharSet) {
		$charset = ($CharSet == 'SJIS' ? 'SJIS-win' : $CharSet);
		$html = mb_convert_encoding($html, $charset, $PhpCharSet);
	}

	// Contentヘッダを出力
	if(!$Call) {
		header('Content-Type: text/html; charset='.($CharSet == 'SJIS' ? 'Shift_JIS' : $CharSet));
		header('Cache-control: no-cache');
		header('Pragma: no-cache');
	}
	$Call++;

	// 変換データを返す
	return($html);
}

/*******************************************************************************
	メール送信
--------------------------------------------------------------------------------
	$ok = send_mail($to, $from, $subject, $message, $charset);
--------------------------------------------------------------------------------
	$ok					送信結果
	$to					宛先メール・アドレス
	$from				送信元メール・アドレス
	$subject			主題
	$message			メッセージ
	$charset			文字コード
--------------------------------------------------------------------------------
　メールを送信してその結果を返す。

※ $fromが偽の場合、"info@{$_SERVER['SERVER_NAME']}"として処理する。
*******************************************************************************/
function send_mail($to, $from, $subject, $message, $charset='UTF-8')
{
	// 無効な文字は出力しない
	mb_substitute_character("none");

	// メールを送信
	$subject = mb_convert_kana($subject, 'KV');
	$sbj = '=?ISO-2022-JP?B?'.base64_encode(mb_convert_encoding($subject, 'ISO-2022-JP', $charset)).'?=';
	$version = phpversion();
	if(!$from) {
		$from = 'info@'.$_SERVER['SERVER_NAME'];
	}
	$headers = 'X-Mailer: PHP/'.$version
		. "\nReply-To: ".$from
		. "\nFrom: ".$from
		. "\nMIME-Version: 1.0"
		. "\nContent-Type: text/plain; charset=ISO-2022-JP"
		. "\nContent-Transfer-Encoding: 7bit";
	$message = str_replace("\r\n", "\n", $message);		// メーラにより[\r\n]が2行改行になる
	$message = mb_convert_kana($message, 'KV');
	$message = mb_convert_encoding($message, 'ISO-2022-JP', $charset);

	$ok = mail($to, $sbj, $message, $headers, '-f'.$from);
/*
	require_once 'Mail.php';
	$smtp = Mail::factory(
		'smtp'
	,	array(

			'host'		=> ''
		,	'port'		=> 587
		,	'auth'		=> TRUE
		,	'username'	=> ''
		,	'password'	=> ''
		,	'debug'		=> FALSE

			'host'		=> 'banana.fellow.co.jp'
		,	'port'		=> 587
		,	'auth'		=> TRUE
		,	'username'	=> 'nakata'
		,	'password'	=> 'd1fsw6k9'
		,	'debug'		=> FALSE

		)
	);
	$ret = $smtp->send(
		$to
	,	array (
			'To'		=> $to
		,	'From'		=> $from
		,	'Subject'	=> $sbj
		)
	,	$message
	);
	$ok = !PEAR::isError($ret);
*/
	// 送信結果を返す
	return($ok);
}

/*******************************************************************************
	画像ファイル・リサイズ
--------------------------------------------------------------------------------
	$data = resize_image($matches);
--------------------------------------------------------------------------------
	$matches			パターン・マッチ・データ配列
	$data				画像ファイル名
--------------------------------------------------------------------------------
　パターンにマッチした画像ファイルをリサイズし、そのファイル名に変更する。
*******************************************************************************/
function resize_image($matches)
{
	global	$Page;

	// 入力を取り出す
	$buf = $matches[0];		// そのもの
	$url = $matches[3];		// URL
	$url_org = $url;

	// 画像調整が必要
	$path = dirname($_SERVER['SCRIPT_FILENAME']);
	$file = str_replace('//', '/', "{$path}/{$url}");
	list($w, $h) = getimagesize($file);
	if($w > $Page['cpimgw']) {

		// 画像ファイルである
		$scale = $Page['cpimgw'] / $w;
		$dw = (int)($w * $scale + 0.5);
		$dh = (int)($h * $scale + 0.5);
		$src = imagecreatefromjpeg($file);
		if(!$src) {
			$src = imagecreatefromgif($file);
		}
		if(!$src) {
			$src = imagecreatefrompng($file);
		}
		if($src) {

			// リサイズが必要
			$type = 'jpg';
			$cp = ".cp.{$type}";
			$temp = explode('.', $url);
			array_pop($temp);
			$url = join('.', $temp).$cp;
			$temp = explode('.', $file);
			array_pop($temp);
			$img = join('.', $temp).$cp;
			$make = TRUE;
			if(file_exists($img)) {
				list($_w, $_h) = getimagesize($img);
				if($_w == $Page['cpimgw']) {
					$make = FALSE;
				}
			}
			if($make) {

				// 画像をリサイズ
				$dst = imagecreatetruecolor($dw, $dh);
				imagecopyresampled($dst, $src, 0, 0, 0, 0, $dw, $dh, $w, $h);
				switch($type) {
				case 'jpg':
					imagejpeg($dst, $img);
					break;
				case 'gif':
					imagegif($dst, $img);
					break;
				case 'png':
					imagepng($dst, $img);
					break;
				}
			}

			// 画像ファイル名をリサイズ・ファイル名に変更
			$buf = str_replace($url_org, $url, $buf);
		}
	}

	// 画像ファイル名変更データを返す
	return($buf);
}

/*******************************************************************************
	セッションID付加
--------------------------------------------------------------------------------
	$data = add_sid($matches);
--------------------------------------------------------------------------------
	$matches			パターン・マッチ・データ配列
	$data				セッションID付加データ
--------------------------------------------------------------------------------
　パターンにマッチしたデータにセッションIDを付加する。
*******************************************************************************/
function add_sid($matches)
{
	// 入力を取り出す
	$buf = $matches[0];		// そのもの
	$url = $matches[3];		// URL
	$url_org = $url;

	// 付加済みであれば終了
	$sname = session_name();
	$sid = session_id();
	if(strstr($url, "{$sname}=")) {
		return($buf);
	}

	// 電話番号であれば終了
	// メール・アドレスであれば終了
	$temp = explode(':', $url);
	foreach(array('tel','mailto') as $name) {
		if(strcasecmp($name, $temp[0]) == 0) {
			return($buf);
		}
	}

	// セッションIDを付加
	// 　ジャンプ位置部分を削除
	$p = strpos($url, '#');
	$ref = NULL;
	if($p !== FALSE){
		$ref = substr($url, $p);
		$url = substr($url, 0, $p);
	}
	// 　URLなし
	if(strlen($url) == 0) {
		return($buf);
	}
	// 　セパレータを付加
	$url .= (!strstr($url, '?') ? '?' : '&');
	// 　セッションIDを付加
	$url .= "{$sname}={$sid}";
	// 　ジャンプ位置を復活
	if($ref) {
		$url .= $ref;
	}
	// 　URL部分を置換
	$data = str_replace($url_org, $url, $buf);

	// セッションID付加データを返す
	return($data);
}

/*******************************************************************************
	タイムスタンプ作成
--------------------------------------------------------------------------------
	$ts = make_timestamp($fmt, $tm);
--------------------------------------------------------------------------------
	$ts					タイムスタンプ
	$fmt				出力形式(date関数の出力形式)
	$tm					時間データ配列(gettimeofday関数の戻り値)
--------------------------------------------------------------------------------
　タイムスタンプを作成して返す。
　μsecを出力する場合は、出力形式にprintfの文字列変換指定子で指定する。
[例]
　$ts = make_timestamp('Y/m/d H:i:s.%.3s');
　　→　2007/03/06 15:37:41.014
*******************************************************************************/
function make_timestamp($fmt, $tm=NULL)
{
	// タイムスタンプを作成
	empty($tm) && $tm = gettimeofday();
	$fmt = sprintf($fmt, sprintf('%06u', $tm['usec']));
	$ts = date($fmt, $tm['sec']);

	// タイムスタンプを返す
	return($ts);
}

/*******************************************************************************
	パラメータ・ダンプ
--------------------------------------------------------------------------------
	$html = dump_param($list, $tag);
--------------------------------------------------------------------------------
	$html				パラメータ・ダンプ結果
	$list				パラメータ名配列
	$tag				HTMLタグ追加指定
--------------------------------------------------------------------------------
　パラメータをダンプしてその結果を返す。
*******************************************************************************/
function dump_param($list, $tag=TRUE)
{
	static	$StsInit = FALSE;

	// 未初期化状態であれば、サブルーチンを定義
	if(!$StsInit) {
		$StsInit = TRUE;
		// インデント作成関数
		function make_indent($ind, $indent) {
			$html = NULL;
			for($count = $indent; $count; $count--) {
				$html .= $ind;
			}
			return($html);
		}
		// 配列ダンプ関数
		function dump_array($info, $param, $globals, $indent) {
			$html_indent = make_indent($info['IND'], $indent);
			$html = NULL;
			foreach($param as $key=>$val) {
				$temp = $val;
				if(is_object($temp)) {
					$temp = 'object';
				}
				$html .= $html_indent.$key.' : ['.$temp.']'.$info['BR'];
				if(($key == 'GLOBALS') && ($indent == 1) && $globals) {
					continue;
				}
				if(is_array($val)) {
					$indent++;
					$html .= dump_array($info, $val, $globals, $indent);
					$indent--;
				}
			}
			return($html);
		}
	}

	// パラメータ・ダンプ処理ループ
	$info = $tag
		? array('B0'=>'<b>', 'B1'=>'</b>', 'BR'=>'<br>', 'IND'=>'&nbsp;&nbsp;')
		: array('B0'=>NULL , 'B1'=>NULL  , 'BR'=>NULL  , 'IND'=>'  ')
	;
	$html = NULL;
	$indent = 0;
	foreach($list as $name) {

		// パラメータは配列ではない
		$globals = ($name == 'GLOBALS');
		$param = ($globals ? $GLOBALS : (isset($GLOBALS[$name]) ? $GLOBALS[$name] : NULL));
		if(!is_array($param)) {

			// パラメータをダンプ
			$html .= $info['B0'].'$'.$name.$info['B1'].' : ['.$param.']'.$info['BR'];
		}

		// パラメータは配列
		else {

			// パラメータ配列をダンプ
			$html .= '--- '.$info['B0'].'$'.$name.$info['B1'].' ---'.$info['BR'];
			$indent++;
			$html .= dump_array($info, $param, $globals, $indent);
			$indent--;
		}
	}

	// ダンプ結果を返す
	return($html);
}

/*******************************************************************************
	文字列変換
--------------------------------------------------------------------------------
	convert_string($str, $func, $trim);
--------------------------------------------------------------------------------
	$str				文字列(参照渡し)
	$func				変換関数
	$trim				前後空白削除指定
--------------------------------------------------------------------------------
　文字列を変換する。
*******************************************************************************/
function convert_string(&$str, $func=NULL, $trim=FALSE)
{
	// 変換関数がなければ終了
	if(!$func) {
		return;
	}

	// 配列
	if(is_array($str)) {

		// 配列の値を変換
		foreach($str as $key=>$value) {
			convert_string($value, $func, $trim);
			$str[$key] = $value;
		}
	}

	// 配列でない(文字列)
	else {
		$str = $func($trim ? trim($str) : $str);
	}
}

/*******************************************************************************
	日時変換
--------------------------------------------------------------------------------
	date_time($date, $time, $tmstmp, $h24);
--------------------------------------------------------------------------------
	$date				変換日付(YYYY-MM-DD 参照渡し)
	$time				変換時刻(hh:mm 参照渡し)
	$tmstmp				タイムスタンプ(YYYY-MM-DD hh:mm:ss)
	$h24				24時指定(0時を24時に変換)
--------------------------------------------------------------------------------
　日時を変換する。
*******************************************************************************/
function date_time(&$date, &$time, $tmstmp, $h24=FALSE)
{
	// 日時を変換
	list($date, $temp) = explode(' ', $tmstmp);
	$tm = explode(':', $temp);
	if($h24 && ($tm[0] + $tm[1] == 0)) {
		$dt = explode('-', $date);
		$tm = localtime(mktime(0, 0, 0, $dt[1], $dt[2]-1, $dt[0]), TRUE);
		$y = $tm['tm_year'] + 1900;
		$m = $tm['tm_mon'] + 1;
		$d = $tm['tm_mday'];
		$date = sprintf('%u-%02u-%02u', $y, $m, $d);
		$tm[0] = '24';
		$tm[1] = '00';
	}
	$time = "{$tm[0]}:{$tm[1]}";
}

/*******************************************************************************
	分算出
--------------------------------------------------------------------------------
	$min = get_min($time);
--------------------------------------------------------------------------------
	$min				分
	$time				時刻
--------------------------------------------------------------------------------
　[hh:mm]形式の時刻から分を算出して返す。
*******************************************************************************/
function get_min($time)
{
	// 分を算出
	$tm = explode(':', $time);
	$min = $tm[0] * 60 + @$tm[1];

	// 分を返す
	return($min);
}

/*******************************************************************************
	メッセージ出力フラッシュ
--------------------------------------------------------------------------------
	put_flush($msg, $chg, $out);
--------------------------------------------------------------------------------
	$msg				メッセージ
	$chg				モード切替要求
	$out				モード
--------------------------------------------------------------------------------
　メッセージを出力してフラッシュする。
　モード切替要求が真の場合、出力モードを切り替える。
*******************************************************************************/
function put_flush($msg, $chg=FALSE, $out=FALSE)
{
	static	$Output = FALSE;

	// モードを切替
	if($chg) {
		$Output = $out;
	}

	// 出力モードでなければ終了
	if(!$Output) {
		return;
	}

	// メッセージを出力
	echo $msg;

	// 出力バッファをフラッシュ
	ob_flush();
	flush();
}

/*******************************************************************************
	和暦取得
--------------------------------------------------------------------------------
	$wareki = wareki($date);
--------------------------------------------------------------------------------
	$wareki				和暦
		era					元号
		year				年
		month				月
		day					日
		dow					曜日
	$date				西暦(YYYY-MM-DD形式)
	$del0				前ゼロ削除指定
--------------------------------------------------------------------------------
　西暦を和暦に変換して返す。
*******************************************************************************/
function wareki($date, $del0 = FALSE)
{
	global	$Db, $DayOfWeek;

	// 和暦を求める
	$wareki = array(
		'era'	=> NULL
	,	'year'	=> NULL
	,	'month'	=> NULL
	,	'day'	=> NULL
	);

	$temp = explode(' ', $date);
	list($year, $month, $day) = explode('-', $temp[0]);
	$ad = mktime(0, 0, 0, $month, $day, $year);
	list($s, $n, $h, $d, $m, $y, $dow) = localtime($ad);
	$dow = @$DayOfWeek[$dow];

	$sql = 'select *'
		. ' from config'
		. " where cfstsdel='0'"
		. " and cflabel like 'eraName%'"
		. ' order by cfvalue desc'
	;
	$rows = db_get_data($era, $Db, $sql);
	if($rows) {
		foreach($era as $info) {
			list($y, $m, $d) = explode('-', $info['cfvalue']);
			if($ad >= mktime(0, 0, 0, $m, $d, $y)) {
				$wareki['era']   = $info['cfname'];
				$wareki['year']  = $year - $y + 1;
				$wareki['month'] = $month;
				$wareki['day']   = $day;
				$wareki['dow']   = $dow;
				if($del0) {
					$wareki['year']  += 0;
					$wareki['month'] += 0;
					$wareki['day']   += 0;
				}
				break;
			}
		}
	}

	// 和暦を返す
	return($wareki);
}

/*******************************************************************************
	休日状態取得
--------------------------------------------------------------------------------
	$sts = holiday($date, $group, $week, $check);
--------------------------------------------------------------------------------
	$sts				休日状態(≠0:休日)
	$date				日付(YYYY-MM-DD形式)
	$group				グループ番号(0:祝日)
	$week				曜日指定(日月火水木金土祝の順)
	$check				前後判定指定
--------------------------------------------------------------------------------
　休日の状態を返す。
*******************************************************************************/
function holiday($date, $group=1, $week='00000001', $check=TRUE)
{
	global	$Db, $Page;

	// パラメータを初期化
	list($year, $month, $day) = explode('-', $date);
	list($s, $n, $h, $d, $m, $y, $doweek) = localtime(mktime(0, 0, 0, $month, $day, $year));
	$number = (int)(($day + 6) / 7);

	// 曜日判定
	$sts = (empty($week[$doweek]) ? 0 : $doweek + 1);

	// 休日でない
	if(!$sts) {

		// 休日判定
		$where = NULL;
		if($group) {
			if(!empty($week[7])) {
				$where .= " and (hdgroup=0 or hdgroup={$group})";
			}
			else {
				$where .= " and (hdgroup={$group})";
			}
		}
		else {
			if(!empty($week[7])) {
				$where .= " and (hdgroup=0)";
			}
		}
		$sql = "select hddate,hdmonth,hdday,hdnumber,hddoweek,hdwekday"
			. " from holiday"
			. ",(select hdname as name from holiday"
			.  " where hdstsdel<>'1'"
			.  $where
			.  " group by hdname) as x"
			. " where hdstsdel<>'1' and hdname=name and hdenfrc<='{$date}'"
			. $where
			. " and (hddate='{$date}' or"
			.  " (hddate is null and (hdmonth={$month} or hdmonth=0)"
			.  " and (hdday={$day} or (hddoweek={$doweek} and hdnumber={$number}))))"
			. " order by hdenfrc desc,hdwekday desc"
			. " limit 1"
		;
		$rows = db_get_data($data, $Db, $sql);
		if($rows) {
			// 平日
			if($data[0]['hdwekday']) {
				$rows = 0;
			}
		}
		$sts = ($rows ? $doweek + 1 : 0);

		// 祝日ではなく、前後判定あり
		if(!$sts && $check) {

			// 前日が日曜日で祝日であれば、祝日にする
			list($s, $n, $h, $d, $m, $y, $w) = localtime(mktime(0, 0, 0, $month, $day-1, $year));
			$y += 1900;
			$m += 1;
			$back = "{$y}-{$m}-{$d}";
			$hdback = holiday($back, $group, '00000001', FALSE);
			if(($w == 0) && $hdback) {
				$sts = $w + 1;
			}

			// 前日および翌日が祝日であれば、祝日にする
			if(!$sts && $hdback) {
				list($s, $n, $h, $d, $m, $y, $w) = localtime(mktime(0, 0, 0, $month, $day+1, $year));
				$y += 1900;
				$m += 1;
				$next = "{$y}-{$m}-{$d}";
				if(holiday($next, $group, '00000001', FALSE)) {
					$sts = $w + 1;
				}
			}
		}
	}

	// 休日状態を返す
	return($sts);
}

/*******************************************************************************
	認証情報暗号化
--------------------------------------------------------------------------------
	$code = encode_auth($id, $pw);
--------------------------------------------------------------------------------
	$code				暗号化コード
	$id					ID
	$pw					パスワード
--------------------------------------------------------------------------------
　認証情報を暗号化して返す。
*******************************************************************************/
function encode_auth($id, $pw)
{
	// 認証情報を暗号化
	$temp = $id . ' HiMiTu' . md5($pw);
	$code = base64_encode(gzdeflate($temp, 9));

	// 暗号化コードを返す
	return($code);
}

/*******************************************************************************
	認証情報復号
--------------------------------------------------------------------------------
	$ok = decode_auth($id, $pw, $code);
--------------------------------------------------------------------------------
	$ok					判定結果(真:正常)
	$id					ID(参照渡し)
	$pw					パスワード(参照渡し MD5ハッシュ値)
	$code				暗号化コード
--------------------------------------------------------------------------------
　暗号化された認証情報を複合して返す。
　パスワードはMD5ハッシュ値である。
*******************************************************************************/
function decode_auth(&$id, &$pw, $code)
{
	// 暗号化コードを復号
	$temp = explode(' HiMiTu', gzinflate(base64_decode($code)));

	// 認証情報を返す
	$id  = @$temp[0];
	$pw  = @$temp[1];

	// 判定結果を返す
	return(count($temp) == 2);
}
/*******************************************************************************
	json_encode(),json_decode()の生成
--------------------------------------------------------------------------------
　PHP4の環境でJSON文字列のエンコード、デコードを行う。
*******************************************************************************/

// json_encode()関数が存在しないなら
if (!function_exists('json_encode')) {
	// JSON.phpを読み込んで
	require_once 'JSON.php';
// json_encode()関数を定義する
	function json_encode($value) {
		$s = new Services_JSON();
		return $s->encodeUnsafe($value);
	}
	// json_decode()関数を定義する
	function json_decode($json, $assoc = false) {
		$s = new Services_JSON($assoc ? SERVICES_JSON_LOOSE_TYPE : 0);
		return $s->decode($json);
	}
}
/*******************************************************************************
	get_request_data(&$datas, array $postkeys = array(), array $getkeys = array())
	--------------------------------------------------------------------------------
		$datas				リクエストデータ格納用変数
		$postkeys			POSTデータを取得するキー
		$getkeys			GETデータを取得するキー
	--------------------------------------------------------------------------------
	--------------------------------------------------------------------------------
　POSTデータ、GETデータから、任意のキーデータを取得
*******************************************************************************/
function  get_request_data(&$datas, array $postkeys = array(), array $getkeys = array()) {
	if (isset($postkeys)) {
		foreach($postkeys as $key){
			if(isset($_POST[$key])){
				$datas[$key] = filter_input(INPUT_POST, $key);
			}
		}
	} else if (isset($getkeys)) {
		foreach($getkeys as $key){
			if(isset($_GET[$key])){
				$datas[$key] = filter_input(INPUT_GET, $key);
			}
		}
	}
}
?>
