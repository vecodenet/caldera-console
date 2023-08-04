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
	case fgDefault = '39';
	case fgBlack = '30';
	case fgRed = '31';
	case fgGreen = '32';
	case fgYellow = '33';
	case fgBlue = '34';
	case fgMagenta = '35';
	case fgCyan = '36';
	case fgLightGray = '37';
	case fgDarkGray = '90';
	case fgLightRed = '91';
	case fgLightGreen = '92';
	case fgLightYellow = '93';
	case fgLightBlue = '94';
	case fgLightMagenta = '95';
	case fgLightCyan = '96';
	case fgWhite = '97';
	case bgDefault = '49';
	case bgBlack = '40';
	case bgRed = '41';
	case bgGreen = '42';
	case bgYellow = '43';
	case bgBlue = '44';
	case bgMagenta = '45';
	case bgCyan = '46';
	case bgLightGray = '47';
	case bgDarkGray = '100';
	case bgLightRed = '101';
	case bgLightGreen = '102';
	case bgLightYellow = '103';
	case bgLightBlue = '104';
	case bgLightMagenta = '105';
	case bgLightCyan = '106';
	case bgWhite = '107';
}
