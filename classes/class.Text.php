<?php

/**
 * Helpers for texts
 */

class Text {
	
	/**
	 * Transforms a text in HTML, replacing links with <a> tags
	 *
	 * @param string $txt
	 * @param string
	 */
	public static function inHTML($txt){
		$txt = htmlspecialchars($txt);
		$txt = nl2br($txt);
		// Links
		$txt = preg_replace('#(?<=^|[^a-z"/])((?:https?://|www\.)[^\s\(\),<>]*)(?=[\s\(\),<>]|$)#im', '<a href="\\1">\\1</a>', $txt);
		$txt = str_replace('<a href="www.', '<a href="http://www.', $txt);
		return $txt;
	}
	
	
	/**
	 * Removes accents from a text
	 *
	 * @param string $txt
	 * @param string
	 */
	public static function removeAccents($txt){
		$txt = str_replace('œ', 'oe', $txt);
		$txt = str_replace('Œ', 'Oe', $txt);
		$txt = str_replace('æ', 'ae', $txt);
		$txt = str_replace('Æ', 'Ae', $txt);
		mb_regex_encoding('UTF-8');
		$txt = mb_ereg_replace('[ÀÁÂÃÄÅĀĂǍẠẢẤẦẨẪẬẮẰẲẴẶǺĄ]', 'A', $txt);
		$txt = mb_ereg_replace('[àáâãäåāăǎạảấầẩẫậắằẳẵặǻą]', 'a', $txt);
		$txt = mb_ereg_replace('[ÇĆĈĊČ]', 'C', $txt);
		$txt = mb_ereg_replace('[çćĉċč]', 'c', $txt);
		$txt = mb_ereg_replace('[ÐĎĐ]', 'D', $txt);
		$txt = mb_ereg_replace('[ðďđ]', 'd', $txt);
		$txt = mb_ereg_replace('[ÈÉÊËĒĔĖĘĚẸẺẼẾỀỂỄỆ]', 'E', $txt);
		$txt = mb_ereg_replace('[èéêëēĕėęěẹẻẽếềểễệ]', 'e', $txt);
		$txt = mb_ereg_replace('[ĜĞĠĢ]', 'G', $txt);
		$txt = mb_ereg_replace('[ĝğġģ]', 'g', $txt);
		$txt = mb_ereg_replace('[ĤĦ]', 'H', $txt);
		$txt = mb_ereg_replace('[ĥħ]', 'h', $txt);
		$txt = mb_ereg_replace('[ÌÍÎÏĨĪĬĮİǏỈỊ]', 'I', $txt);
		$txt = mb_ereg_replace('[ìíîïĩīĭįıǐỉị]', 'i', $txt);
		$txt = str_replace('Ĵ', 'J', $txt);
		$txt = str_replace('ĵ', 'j', $txt);
		$txt = str_replace('Ķ', 'K', $txt);
		$txt = str_replace('ķ', 'k', $txt);
		$txt = mb_ereg_replace('[ĹĻĽĿŁ]', 'L', $txt);
		$txt = mb_ereg_replace('[ĺļľŀł]', 'l', $txt);
		$txt = mb_ereg_replace('[ÑŃŅŇ]', 'N', $txt);
		$txt = mb_ereg_replace('[ñńņňŉ]', 'n', $txt);
		$txt = mb_ereg_replace('[ÒÓÔÕÖØŌŎŐƠǑǾỌỎỐỒỔỖỘỚỜỞỠỢ]', 'O', $txt);
		$txt = mb_ereg_replace('[òóôõöøōŏőơǒǿọỏốồổỗộớờởỡợ]', 'o', $txt);
		$txt = mb_ereg_replace('[ŔŖŘ]', 'R', $txt);
		$txt = mb_ereg_replace('[ŕŗř]', 'r', $txt);
		$txt = mb_ereg_replace('[ŚŜŞŠ]', 'S', $txt);
		$txt = mb_ereg_replace('[śŝşš]', 's', $txt);
		$txt = mb_ereg_replace('[ŢŤŦ]', 'T', $txt);
		$txt = mb_ereg_replace('[ţťŧ]', 't', $txt);
		$txt = mb_ereg_replace('[ÙÚÛÜŨŪŬŮŰŲƯǓǕǗǙǛỤỦỨỪỬỮỰ]', 'U', $txt);
		$txt = mb_ereg_replace('[ùúûüũūŭůűųưǔǖǘǚǜụủứừửữự]', 'u', $txt);
		$txt = mb_ereg_replace('[ŴẀẂẄ]', 'W', $txt);
		$txt = mb_ereg_replace('[ŵẁẃẅ]', 'w', $txt);
		$txt = mb_ereg_replace('[ÝŶŸỲỸỶỴ]', 'Y', $txt);
		$txt = mb_ereg_replace('[ýÿŷỹỵỷỳ]', 'y', $txt);
		$txt = mb_ereg_replace('[ŹŻŽ]', 'Z', $txt);
		$txt = mb_ereg_replace('[źżž]', 'z', $txt);
		return $txt;
	}
	
	
	/**
	 * Convert a string to use it in a URL
	 *
	 * @param string $txt
	 * @param string
	 */
	public static function forUrl($txt){
		$txt = self::removeAccents($txt);
		$txt = strtolower($txt);
		$txt = preg_replace('#[^a-z0-9]+#', '-', $txt);
		$txt = trim($txt, '-');
		return $txt;
	}
	
	
	/**
	 * Extract a summary of a text, optionaly with a keyword highlighted
	 *
	 * @param string $txt	Text
	 * @param int $l		Max length of the summary
	 * @param string|array $limiter		Prefix and Suffix. e.g: "..." or ["", "..."]
	 * @param string $keyword	Highlighted keyword
	 * @return string	Summary
	 */
	public static function summary($txt, $l, $limiter='', $keyword=null){
		$start = 0;
		$txt = trim(preg_replace('#\s+#', ' ', $txt));
		$keyword = trim(preg_replace('#\s+#', ' ', $keyword));
		
		if(is_array($limiter)){
			$limiterLeft = $limiter[0];
			$limiterRight = $limiter[1];
		}else{
			$limiterLeft = $limiter;
			$limiterRight = $limiter;
		}
		
		if(strlen($txt)<=$l)
			return $txt;
		
		if(isset($keyword)){
			$txt_ = strtolower(text::removeAccents($txt));
			$keyword = strtolower(text::removeAccents($keyword));
			$p = strpos($txt_, $keyword);
			
			if($p === false && strpos($keyword, ' ') !== false){
				$keyword = preg_split('# +#', $keyword);
				foreach($keyword as $keyword_){
					$p = strpos($txt_, $keyword_);
					if($p !== false){
						$keyword = $keyword_;
						break;
					}
				}
			}
			
			if($p !== false){
				$start = round($p + strlen($keyword)/2 - $l/3);
				if($start < 0)
					$start = 0;
				if($start > strlen($txt)-$l)
					$start = strlen($txt)-$l;
				
				if($start != 0){
					if(($pos = strpos($txt, ' ', $start)) !== false)
						$start = strpos($txt, ' ', $start)+1;
				}
			}
		}
		
		if($start+$l >= strlen($txt)){
			$txt = substr($txt, $start);
			$limiterRight = '';
		}else{
			$txt = substr($txt, $start, $l);
			if(($pos = strrpos($txt, ' ')) !== false)
				$txt = substr($txt, 0, $pos);
		}
		
		if($start==0)
			$limiterLeft = '';
		
		return
			$limiterLeft.
			trim($txt).
			$limiterRight;
	}
	
}
