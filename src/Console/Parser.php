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

use Caldera\Console\Command\Argument;
use Caldera\Console\Command\CommandInterface;
use Caldera\Console\Command\Token;

class Parser {

	/**
	 * Parse command arguments
	 * @param  CommandInterface $command Command to parse
	 * @return array<Argument>
	 */
	public function parseCommandArguments(CommandInterface $command): array {
		$items = [];
		# Get the command arguments
		$arguments = $command->getArguments();
		$tokens = explode(' ', $arguments);
		# Parse the command arguments
		$pattern = '/{(--)?(?:((?:[^\|}]?)+)\|)?((?:[^}\*\?=]?)+)(=)?((?:[^}\*\?]?)+)?(\?)?(\*)?}/i';
		foreach ($tokens as $token) {
			if ( preg_match($pattern, $token, $matches) === 1 ) {
				$name = $matches[3];
				$default = $matches[5] ? $matches[5] : '';
				$shortcut = $matches[2];
				$option = !!$matches[1];
				$boolean = !!$matches[1] && !$matches[4];
				$required = !($matches[6] ?? false);
				$array = !!($matches[7] ?? false);
				$expr = '';
				if ($option) {
					$expr = $shortcut ?'^(?:(?:-%1$s)|(?:--%2$s))' :  '(?:--%2$s)';
					$expr .= $boolean ? '' : '(?:[= ](.*))?';
					$expr = sprintf("/{$expr}/", $shortcut, $name);
				} else {
					$expr = '/^(?!-{1,2})(.*)/';
				}
				$type = $option ? 'options' : 'arguments';
				$key = "{$type}.{$name}";
				#
				$item = new Argument($name, $default, $shortcut, $option, $boolean, $required, $array, $expr);
				$items[$key] = $item;
			} else {
				throw new RuntimeException("Invalid argument format: '{$token}'");
			}
		}
		return $items;
	}

	/**
	 * Parse input arguments
	 * @param  array<string> $arguments Arguments array
	 * @return array<Token>
	 */
	public function parseInputArguments(array $arguments): array {
		$tokens = [];
		while ( $argument = array_shift($arguments) ) {
			preg_match('/^(-{1,2})?([^= ]+)?(?:[= ](.*))?/', $argument, $matches);
			$option = !empty( $matches[1] );
			if ($option) {
				$name = $matches[2] ?? '';
				$value = $matches[3] ?? null;
			} else {
				$name = '';
				$value = $matches[2] ?? null;
			}
			$token = new Token($argument, $option, $name, $value);
			$tokens[] = $token;
		}
		return $tokens;
	}
}
