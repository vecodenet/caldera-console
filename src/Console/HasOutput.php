<?php

declare(strict_types = 1);

/**
 * Caldera Console
 * Console abstraction layer, part of Vecode Caldera
 * @author  biohzrdmx <github.com/biohzrdmx>
 * @copyright Copyright (c) 2023 Vecode. All rights reserved
 */

namespace Caldera\Console;

use RuntimeException;
use InvalidArgumentException;

use Caldera\Console\Color;

trait HasOutput {

	/**
	 * Output a blank line
	 * @return $this
	 */
	public function blank() {
		echo PHP_EOL;
		return $this;
	}

	/**
	 * Show an info message
	 * @param  string $message The message to show
	 * @param  array  $context Placeholder values
	 * @return $this
	 */
	public function line(string $message, array $context = []) {
		printf("%s%s", $this->format($message, $context), PHP_EOL);
		return $this;
	}

	/**
	 * Show an info message
	 * @param  string $message The message to show
	 * @param  array  $context Placeholder values
	 * @return $this
	 */
	public function info(string $message, array $context = []) {
		$str = $this->colorize( $this->format($message, $context), Color::fgCyan );
		printf('%s%s', $str, PHP_EOL);
		return $this;
	}

	/**
	 * Show a success message
	 * @param  string $message The message to show
	 * @param  array  $context Placeholder values
	 * @return $this
	 */
	public function success(string $message, array $context = []) {
		$str = $this->colorize( $this->format($message, $context), Color::fgGreen );
		printf('%s%s', $str, PHP_EOL);
		return $this;
	}

	/**
	 * Show an error message
	 * @param  string $message The message to show
	 * @param  array  $context Placeholder values
	 * @return $this
	 */
	public function error(string $message, array $context = []) {
		$str = $this->colorize( $this->format($message, $context), Color::fgRed );
		printf('%s%s', $str, PHP_EOL);
		return $this;
	}

	/**
	 * Show a warning message
	 * @param  string $message The message to show
	 * @param  array  $context Placeholder values
	 * @return $this
	 */
	public function warning(string $message, array $context = []) {
		$str = $this->colorize( $this->format($message, $context), Color::fgYellow );
		printf('%s%s', $str, PHP_EOL);
		return $this;
	}

	/**
	 * Generates a colorized string
	 * @param  string $str        String to colorize
	 * @param  Color  $text_color Color to use for text
	 * @param  Color  $back_color Color to use for background
	 */
	public function colorize(string $str, Color $text_color, ?Color $back_color = null): string {
		if ( str_starts_with($text_color->name, 'bg') ) {
			throw new InvalidArgumentException('You must specify a foreground color constant');
		}
		if (! $back_color ) {
			$ret = sprintf('%s%s%s', $this->escape($text_color->value), $str, $this->escape('0m'));
		} else {
			if ( str_starts_with($back_color->name, 'fg') ) {
				throw new InvalidArgumentException('You must specify a background color constant');
			}
			$ret = sprintf('%s%s%s%s', $this->escape($back_color->value), $this->escape($text_color->value), $str, $this->escape('0m'));
		}
		return $ret;
	}

	/**
	 * Show a progress bar
	 * @param  int    $done     Percent done (0...100)
	 * @param  int    $width    Width of the progress bar in characters
	 * @param  string $progress Character for the progress indicator
	 * @param  string $filled   Character for a filled step
	 * @param  string $empty    Character for an empty step
	 * @return $this
	 */
	public function progressBar(int $done, int $width = 28, string $progress = '>', string $filled = '=', string $empty = '-') {
		$percent = floor(($done / 100) * 100);
		$size = floor(($done / 100) * $width);
		$left = $width - $size;
		$bar = "[%'{$filled}{$size}s{$progress}%'{$empty}{$left}s]";
		printf("%s%s{$bar} - %s%% ", $this->escape('0G'), $this->escape('2K'), '', '', $percent);
		return $this;
	}

	/**
	 * Format a variable for output
	 * @param  string $message The message to show
	 * @param  array  $context Placeholder values
	 */
	protected function format(string $message, array $context = []): string {
		return preg_replace_callback('/\{(.+?)\}/', function($matches) use ($context) {
			if (! isset( $context[ $matches[1] ] ) ) {
				throw new RuntimeException("Value for '$matches[1]' is not set");
			}
			$replacement = $context[ $matches[1] ];
			return is_object($replacement) || is_array($replacement) ? var_export($replacement, true) : $replacement;
		}, $message);
	}

	/**
	 * Generate an escape sequence
	 * @param  string $code ANSI code for the escape sequence
	 */
	protected function escape(string $code): string {
		return "\033[{$code}";
	}
}
