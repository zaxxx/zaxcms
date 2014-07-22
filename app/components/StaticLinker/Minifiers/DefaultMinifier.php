<?php

namespace ZaxCMS\Components\StaticLinker;
use Nette,
	Zax;

class DefaultMinifier extends Nette\Object implements IMinifier {

	/**
	 * @param $text
	 * @return string
	 */
	public function minify($text) {
		// JavaScript compressor by John Elliot <jj5@jj5.net>

		$replace = array(
			'#\'([^\n\']*?)/\*([^\n\']*)\'#' => "'\1/'+\'\'+'*\2'", // remove comments from ' strings
			'#\"([^\n\"]*?)/\*([^\n\"]*)\"#' => '"\1/"+\'\'+"*\2"', // remove comments from " strings
			//'#/\*.*?\*/#s'            => "",      // strip C style comments
			'#[\r\n]+#'               => "\n",    // remove blank lines and \r's
			'#\n([ \t]*//.*?\n)*#s'   => "\n",    // strip line comments (whole line only)
			'#([^\\])//([^\'"\n]*)\n#s' => "\\1\n",
			// strip line comments
			// (that aren't possibly in strings or regex's)
			'#\n\s+#'                 => "\n",    // strip excess whitespace
			'#\s+\n#'                 => "\n",    // strip excess whitespace
			'#(//[^\n]*\n)#s'         => "\\1\n", // extra line feed after any comments left
			// (important given later replacements)
			'#/([\'"])\+\'\'\+([\'"])\*#' => "/*" // restore comments in strings
		);

		$search = array_keys( $replace );
		$script = preg_replace( $search, $replace, $text );

		$replace = array(
			"&&\n" => "&&",
			"||\n" => "||",
			"(\n"  => "(",
			")\n"  => ")",
			"[\n"  => "[",
			"]\n"  => "]",
			"+\n"  => "+",
			",\n"  => ",",
			"?\n"  => "?",
			":\n"  => ":",
			";\n"  => ";",
			"{\n"  => "{",
//  "}\n"  => "}", (because I forget to put semicolons after function assignments)
			"\n]"  => "]",
			"\n)"  => ")",
			"\n}"  => "}",
			"\n\n" => "\n"
		);

		$search = array_keys( $replace );
		$script = str_replace( $search, $replace, $script );

		return trim( $script );
	}

}