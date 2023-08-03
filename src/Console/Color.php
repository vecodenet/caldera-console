<?php

declare(strict_types = 1);

/**
 * Caldera Console
 * Console abstraction layer, part of Vecode Caldera
 * @author  biohzrdmx <github.com/biohzrdmx>
 * @copyright Copyright (c) 2023 Vecode. All rights reserved
 */

namespace Caldera\Console;

enum Color: string {
	case fgBlack  = '30m';
	case fgBlue   = '34m';
	case fgGreen  = '32m';
	case fgCyan   = '36m';
	case fgRed    = '31m';
	case fgPurple = '35m';
	case fgYellow = '33m';
	case fgGray   = '37m';
	case bgBlack  = '37;40m';
	case bgBlue   = '37;44m';
	case bgGreen  = '37;42m';
	case bgCyan   = '37;46m';
	case bgRed    = '37;41m';
	case bgPurple = '37;45m';
	case bgYellow = '37;43m';
	case bgGray   = '37;47m';
}
