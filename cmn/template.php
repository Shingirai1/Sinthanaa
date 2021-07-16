<?php
/*******************************************************************************
	HTMLテンプレート・クラス
																Fellow System
--------------------------------------------------------------------------------
[呼び出し形式]
(1)	$tpl = new HtmlTemplate();
	$code = $tpl->convert($path);
	eval($code);

(2)	$code = HtmlTemplate::convert($path);
	eval($code);

(3)	$tpl = new HtmlTemplate();
	$tpl->output($path);

(4)	HtmlTemplate::output($path);
--------------------------------------------------------------------------------
[テンプレート命令書式]
	#命令([arg]?)

	arg
		変数	変数のラベルを記述する。
					例) $aの場合
							a
				配列の場合は[.]区切りでキーを記述する。
					例) $a['b']の場合
							a.b
				配列キーには参照命令が記述可能である。
					例) $a[$b]の場合
							a.#REF(b?)
		式		式を記述する。
					例) $a+$bの場合
							a+b
				文字列を使用する場合は["]または[']で囲む。

[テンプレート命令仕様]
　命令は大文字/小文字を区別する。

コメント		#(text?)
　コメントとして処理(削除)する。

REF命令			#REF(arg?)
　argの値を参照する。

EXP命令			#EXP(arg?)
　argの式を実行する。

PUT命令			#PUT(arg?)
　argの値を出力する。
　[,]区切りで書式を指定できる。
	例) #PUT('%.2f',a?)

NPUT命令		#NPUT(arg?)
　argのnumber_format()実行結果を出力する。
　[,]区切りで少数点以下の桁数を指定できる。
	例) #NPUT(a,2?)

IF命令			#IF(arg?) text #FI
　argが真であればtextを出力する。

ELSE命令		#ELSE
　if命令の中に記述し、条件が偽の場合に#ELSE～#FIの間を出力する。

LOOP命令		#LOOP(arg?) text #POOL
　argの要素数分、textの出力を繰り返す。

KREF命令		#KREF(?)
　loop命令の中に記述し、argのキーを参照する。

KPUT命令		#KPUT(?)
　loop命令の中に記述し、argのキーを出力する。

LREF命令		#LREF(arg?)
　loop命令の中に記述し、argの要素を参照する。

LPUT命令		#LPUT(arg?)
　loop命令の中に記述し、argの要素を出力する。
　[,]区切りで書式を指定できる。
	例) #LPUT('%.2f',a?)

NLPUT命令		#NLPUT(arg?)
　loop命令の中に記述し、argの要素に対するnumber_format()実行結果を出力する。
　[,]区切りで少数点以下の桁数を指定できる。
	例) #NLPUT(a,2?)

INC命令			#INC(arg?)
　argのファイルをインクルードする。
--------------------------------------------------------------------------------
[注意事項]
　変換後のコードで次の変数を使用する。
	$_ht
--------------------------------------------------------------------------------
 No.│   日付   │区分│                        内  容
━━┿━━━━━┿━━┿━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 001│2010.11.09│新規│[V1.00] 金井
 002│2013.07.28│追加│[V1.01] 金井　output()追加など
 003│2014.02.14│追加│[V1.02] 金井　直接コール対応
 004│2014.11.15│修正│金井　直接コール対応のバグ修正
*******************************************************************************/
class HtmlTemplate
{
	var $str, $cnv;

	// テンプレート変換
	public function convert($path)
	{
		static $Search0 = array(
		);
		static $Replace0 = array(
		);
		static $Search1 = array(
			array(
				// #(text?)
				'/#\(.*?\?\)\s*/s'
				// #ELSE
			,	'/#ELSE/'
				// #REF(arg?)
			,	'/#REF\(([^#]+?)\?\)/e'
				// #KREF(?)
			,	'/#KREF\(\?\)/e'
				// #LREF(arg?)
			,	'/#LREF\(([^#]*?)\?\)/e'
				// #INC(arg?)
			,	'/#INC\(([^#]+?)\?\)\s*/e'
			)
		,	array(
				// #EXP(arg?)
				'/#EXP\(([^#]+?)\?\)/e'
				// #PUT(arg?)
			,	'/#PUT\(([^#]+?)\?\)/e'
				// #NPUT(arg?)
			,	'/#NPUT\(([^#]+?)\?\)/e'
				// #KPUT(?)
			,	'/#KPUT\(\?\)/e'
				// #LPUT(arg?)
			,	'/#LPUT\(([^#]*?)\?\)/e'
				// #NLPUT(arg?)
			,	'/#NLPUT\(([^#]*?)\?\)/e'
			)
		,	array(
				// #をShArPに変換
				'/#/'
			)
		,	array(
				// #IF #LOOP 関連を元に戻す
				'/ShArPIF/'
			,	'/ShArPFI/'
			,	'/ShArPLOOP/'
			,	'/ShArPPOOL/'
			)
		,	array(
				// #IF(arg?) text #FI
				'/#IF\(([^#]+?)\?\)([^#]+?)#FI/se'
				// #LOOP(arg?) text #POOL
			,	'/#LOOP\(([^#]+?)\?\)([^#]+?)#POOL/se'
			)
		);
		static $Replace1 = array(
			array(
				// #(text?)
				NULL
				// #ELSE
			,	'<?php }else{ ?>'
				// #REF(arg)
			,	"self::ref('\\1')"
				// #KREF()
			,	"self::kref()"
				// #LREF(arg)
			,	"self::lref('\\1')"
				// #INC(arg)
			,	"self::inc('\\1')"
			)
		,	array(
				// #EXP(arg)
				"self::exp('\\1')"
				// #PUT(arg)
			,	"self::put('\\1')"
				// #NPUT(arg)
			,	"self::nput('\\1')"
				// #KPUT()
			,	"self::kput()"
				// #LPUT(arg)
			,	"self::lput('\\1')"
				// #NLPUT(arg)
			,	"self::nlput('\\1')"
			)
		,	array(
				'ShArP'
			)
		,	array(
				'#IF'
			,	'#FI'
			,	'#LOOP'
			,	'#POOL'
			)
		,	array(
				// #IF(arg) text #FI
				"self::_if('\\1','\\2')"
				// #LOOP(arg) text #POOL
			,	"self::loop('\\1','\\2')"
			)
		);
		static $Search2 = array(
			// #
			'/ShArP/'
			// #*\(
		,	'/#([^\\\]+)\\\\\(/'
		);
		static $Replace2 = array(
			// #
			'#'
			// #*\(
		,	'#$1('
		);

		// 直接コール
		if(empty($this)) {
			$tpl = new HtmlTemplate();
			return($tpl->convert($path));
		}

		// テンプレート・ファイル読み込み
		$str = file_get_contents($path);

		// 置換処理ループ
		$this->str = array();
		$this->cnv = array();
		if(!empty($Search0)) {
			$str = preg_replace($Search0, $Replace0, $str);
		}
		foreach($Search1 as $id=>$search) {
			$replace = $Replace1[$id];
			while(TRUE) {
				$temp = preg_replace($search, $replace, $str);
				if($temp == $str) {
					break;
				}
				$str = $temp;
			}
		}

		// 変換部を復帰
		for($id = count($this->cnv) - 1; $id >= 0; $id--) {
			$str = str_replace('CoNvErT'.$id, $this->cnv[$id], $str);
		}

		// 文字列を復帰
		for($id = count($this->str) - 1; $id >= 0; $id--) {
			$str = str_replace('StRiNg'.$id, $this->str[$id], $str);
		}

		// #などを復帰・変換
		$str = preg_replace($Search2, $Replace2, $str);

		// 置換結果を返す
		return(' $_ht[\'keys\']=array(); $_ht[\'vals\']=array(); ?>'.$str);
	}

	// テンプレート変換・出力
	public function output($path)
	{
		// 直接コール
		if(empty($this)) {
			$tpl = new HtmlTemplate();
			$tpl->output($path);
			return;
		}
		// グローバル変数の見える化
		foreach($GLOBALS as $_k=>$_v) {
			global $$_k;
		}
		// テンプレートを変換して出力
		$code = self::convert($path);
		$retv = eval($code);
		if($retv === FALSE) {
			$temp = str_replace(array('<','>'), array('&lt;','&gt;'), preg_replace('/^.*?\?\>/', NULL, $code));
			echo <<<EOT
<div style="color:#F00">構文エラーだと思われます。</div>
<pre>{$temp}</pre>
EOT;
		}
	}

	// 引数変換
	private function conv_arg($arg)
	{
		$str = preg_replace(
			array("/('.+?')/e", '/(".+?")/e', '/([^\+\-\*\/\%\=\!\<\>\&\|\~\^\:\?\(\)\;\,\s]+)/e')
		,	"self::conv_arg_sub('\\1')"
		,	preg_replace('/\\\"/', '"', $arg)
		);
		return($str);
	}
	private function conv_arg_sub($label)
	{
		$param = preg_replace('/\\\"/', '"', $label);

		// 文字列
		if(($param[0] == "'") || ($param[0] == '"')){
			$mark = 'StRiNg'.count($this->str);
			$this->str[] = $param;
			return($mark);
		}
		// 数値
		if(is_numeric($param)) {
			return($param);
		}
		// 演算子など
		switch($param) {
		case 'and':
		case 'xor':
		case 'or':
		case 'array':
			return($param);
		}
		// 変数
		$val = NULL;
		foreach(explode(".", $param) as $id=>$key) {
			if(($key[0] == '$') || (substr($key, 0, 6) == 'StRiNg') || is_numeric($key)) {
				if($id) {
					$key = "[{$key}]";
				}
			}
			else{
				if(!$id) {
					$key = '$'.$key;
				}
				else{
					$key = "['{$key}']";
				}
			}
			$val .= $key;
		}
		return($val);
	}

	// #REF
	private function ref($arg)
	{
		return(self::conv_arg($arg));
	}

	// #EXP
	private function exp($arg)
	{
		return('<?php '.self::conv_arg($arg).'; ?>');
	}

	// #PUT
	private function put($arg)
	{
		if(	preg_match("/('.+?')\\s*,\\s*(.+)/", $arg, $fmt)
		 || preg_match('/(".+?")\s*,\s*(.+)/', $arg, $fmt)
		 || preg_match("/([^'\"]+?)\\s*,\\s*(.+)/", $arg, $fmt)
		) {
			return('<?php printf('.self::conv_arg($arg).'); ?>');
		}
		else {
			return('<?php echo '.self::conv_arg($arg).'; ?>');
		}
	}

	// #NPUT
	private function nput($arg)
	{
		return('<?php echo number_format('.self::conv_arg($arg).'); ?>');
	}

	// #IF
	private function _if($exp, $text)
	{
		$str = '<?php if('.self::conv_arg($exp).'){ ?>'.preg_replace('/\\\"/', '"', $text).'<?php } ?>';
		$mark = "CoNvErT".count($this->cnv);
		$this->cnv[] = $str;
		return($mark);
	}

	// #LOOP
	private function loop($exp, $text)
	{
		$str = '<?php $_ht[\'keys\'][]=@$_ht[\'key\']; $_ht[\'vals\'][]=@$_ht[\'val\'];'
			. ' foreach('.self::conv_arg($exp).' as $_ht[\'key\']=>$_ht[\'val\']){ ?>'.preg_replace('/\\\"/', '"', $text).'<?php }'
			. ' $_ht[\'key\']=array_pop($_ht[\'keys\']); $_ht[\'val\']=array_pop($_ht[\'vals\']); ?>'
		;
		$mark = "CoNvErT".count($this->cnv);
		$this->cnv[] = $str;
		return($mark);
	}

	// #KREF
	private function kref()
	{
		return("\$_ht['key']");
	}

	// #KPUT
	private function kput()
	{
		return("<?php echo(\$_ht['key']); ?>");
	}

	// #LREF
	private function lref($arg)
	{
		$label = '_ht.val' . ($arg ? '.'.$arg : NULL);
		return(self::conv_arg($label));
	}

	// #LPUT
	private function lput($arg)
	{
		if(	preg_match("/('.+?')\\s*,\\s*(.+)/", $arg, $fmt)
		 || preg_match('/(".+?")\s*,\s*(.+)/', $arg, $fmt)
		 || preg_match("/([^'\"]+?)\\s*,\\s*(.+)/", $arg, $fmt)
		) {
			$fmt[2] = str_replace('%', '%%', $fmt[2]);
			$label = $fmt[1] . ',_ht.val' . ($fmt[2] ? '.'.$fmt[2] : NULL);
			return('<?php printf('.self::conv_arg($label).'); ?>');
		}
		else {
			$label = '_ht.val' . ($arg ? '.'.$arg : NULL);
			return('<?php echo '.self::conv_arg($label).'; ?>');
		}
	}

	// #NLPUT
	private function nlput($arg)
	{
		$label = '_ht.val' . ($arg ? '.'.$arg : NULL);
		return('<?php echo number_format('.self::conv_arg($label).'); ?>');
	}

	// #INC
	private function inc($arg)
	{
		// 変数・文字列式対応
		$temp = explode('$', $arg);
		if(isset($temp[1])) {
			foreach($temp as $v) {
				$temp = explode('[', $v);
				global ${$temp[0]};
			}
			eval('$arg='.$arg.'; ?>');
		}

		return(file_get_contents($arg));
	}
}
