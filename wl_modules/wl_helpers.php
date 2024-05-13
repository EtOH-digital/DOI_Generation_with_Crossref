<?php

function wl_get_ordinal($num){
	$ordinals = array(
	    1 => 'first', 2 => 'second', 3 => 'third', 4 => 'fourth', 5 => 'fifth',
	    6 => 'sixth', 7 => 'seventh', 8 => 'eighth', 9 => 'ninth', 10 => 'tenth',
	    11 => 'eleventh', 12 => 'twelfth', 13 => 'thirteenth', 14 => 'fourteenth',
	    15 => 'fifteenth', 16 => 'sixteenth', 17 => 'seventeenth', 18 => 'eighteenth',
	    19 => 'nineteenth', 20 => 'twentieth', 21 => 'twenty-first', 22 => 'twenty-second',
	    23 => 'twenty-third', 24 => 'twenty-fourth', 25 => 'twenty-fifth', 26 => 'twenty-sixth',
	    27 => 'twenty-seventh', 28 => 'twenty-eighth', 29 => 'twenty-ninth', 30 => 'thirtieth',
	    31 => 'thirty-first', 32 => 'thirty-second', 33 => 'thirty-third', 34 => 'thirty-fourth',
	    35 => 'thirty-fifth', 36 => 'thirty-sixth', 37 => 'thirty-seventh', 38 => 'thirty-eighth',
	    39 => 'thirty-ninth', 40 => 'fortieth', 41 => 'forty-first', 42 => 'forty-second',
	    43 => 'forty-third', 44 => 'forty-fourth', 45 => 'forty-fifth', 46 => 'forty-sixth',
	    47 => 'forty-seventh', 48 => 'forty-eighth', 49 => 'forty-ninth', 50 => 'fiftieth',
	    51 => 'fifty-first', 52 => 'fifty-second', 53 => 'fifty-third', 54 => 'fifty-fourth',
	    55 => 'fifty-fifth', 56 => 'fifty-sixth', 57 => 'fifty-seventh', 58 => 'fifty-eighth',
	    59 => 'fifty-ninth', 60 => 'sixtieth', 61 => 'sixty-first', 62 => 'sixty-second',
	    63 => 'sixty-third', 64 => 'sixty-fourth', 65 => 'sixty-fifth', 66 => 'sixty-sixth',
	    67 => 'sixty-seventh', 68 => 'sixty-eighth', 69 => 'sixty-ninth', 70 => 'seventieth',
	    71 => 'seventy-first', 72 => 'seventy-second', 73 => 'seventy-third', 74 => 'seventy-fourth',
	    75 => 'seventy-fifth', 76 => 'seventy-sixth', 77 => 'seventy-seventh', 78 => 'seventy-eighth',
	    79 => 'seventy-ninth', 80 => 'eightieth', 81 => 'eighty-first', 82 => 'eighty-second',
	    83 => 'eighty-third', 84 => 'eighty-fourth', 85 => 'eighty-fifth', 86 => 'eighty-sixth',
	    87 => 'eighty-seventh', 88 => 'eighty-eighth', 89 => 'eighty-ninth', 90 => 'ninetieth',
	    91 => 'ninety-first', 92 => 'ninety-second', 93 => 'ninety-third', 94 => 'ninety-fourth',
	    95 => 'ninety-fifth', 96 => 'ninety-sixth', 97 => 'ninety-seventh', 98 => 'ninety-eighth',
	    99 => 'ninety-ninth', 100 => 'hundredth'
	);

	if( array_key_exists($num, $ordinals) ){
		return $ordinals[$num];
	}
	return ;

}