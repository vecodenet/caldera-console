<?php

declare(strict_types = 1);

/**
 * Caldera Console
 * Console abstraction layer, part of Vecode Caldera
 * @author  biohzrdmx <github.com/biohzrdmx>
 * @copyright Copyright (c) 2023 Vecode. All rights reserved
 */

namespace Caldera\Console;

trait HasInput {

	/**
	 * Read an string from the terminal
	 * @param  string  $prompt Text to show to the user, optional
	 * @param  bool    $hidden Whether to hide the input or not
	 */
	public function getString(string $prompt = '', bool $hidden = false): string {
		$ret = '';
		do {
			printf('%s ', $prompt);
			if ($hidden) {
				$os = $this->getOsIdentifier();
				$windows = $os === 'WINNT' || $os === 'WIN32';
				if ($windows) {
					$ret = exec( dirname(__DIR__) . '/../bin/hiddeninput.exe' );
				} else {
					$mode = exec('stty -g');
					exec('stty -echo');
					$ret = fgets(STDIN);
					exec( sprintf('stty %s', $mode) );
				}
			} else {
				$ret = readline();
			}
		} while(!$ret);
		if ($hidden) {
			echo PHP_EOL;
		}
		return rtrim($ret);
	}

	/**
	 * Read a key from the terminal
	 * @param  array<string> $keys   Array of valid keys, optional
	 * @param  string        $prompt Text to show to the user, optional
	 */
	public function getKey(array $keys = [], string $prompt = ''): string {
		do {
			$ret = substr($this->getString($prompt), 0, 1);
			if (! $keys ) break;
			if (! in_array($ret, $keys) ) {
				$this->warning('Please press a valid key ['.implode('/', $keys).']');
			} else {
				break;
			}
		} while(1);
		return $ret;
	}

	/**
	 * Get OS identifier
	 * @return string
	 */
	protected function getOsIdentifier(): string {
		return PHP_OS;
	}
}
