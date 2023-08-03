<?php

declare(strict_types = 1);

/**
 * Caldera Console
 * Console abstraction layer, part of Vecode Caldera
 * @author  biohzrdmx <github.com/biohzrdmx>
 * @copyright Copyright (c) 2023 Vecode. All rights reserved
 */

namespace Caldera\Console;

use Closure;

use Caldera\Console\Command\Argument;
use Caldera\Console\Command\CommandInterface;
use Caldera\Console\Command\Token;

class Caller {

	/**
	 * Missing arguments array
	 * @var array<string>
	 */
	protected array $missing = [];

	/**
	 * Unknown arguments array
	 * @var array<string>
	 */
	protected array $unknown = [];

	/**
	 * Parser instance
	 */
	protected Parser $parser;

	/**
	 * Constructor
	 * @param Parser $parser Parser instance
	 */
	public function __construct(Parser $parser) {
		$this->parser = $parser;
	}

	/**
	 * Get a list of the missing required arguments
	 */
	public function getMissingArguments(): array {
		return $this->missing;
	}

	/**
	 * Get a list of the unknown arguments passed
	 */
	public function getUnknownArguments(): array {
		return $this->unknown;
	}

	/**
	 * Call command
	 * @param  CommandInterface $command CommandInterface implementation
	 * @param  array<string>    $argv    Command array
	 */
	public function call(CommandInterface $command, array $argv): void {
		$values = [];

		# Parse command and input arguments
		$arguments = $this->parser->parseCommandArguments($command);
		$tokens = $this->parser->parseInputArguments($argv);

		# Process the tokens
		if ($tokens) {
			# Consume the arguments one-by-one
			while ( $item = array_shift($arguments) ) {
				$tokens = $this->checkArgument($item, $tokens, function($item) use (&$values) {
					$type = $item->isOption() ? 'options' : 'arguments';
					$key = "{$type}.{$item->getName()}";
					$values[$key] = $item;
				});
			}
		} else {
			# If no tokens were present, try to detect missing arguments and pass default ones
			foreach ($arguments as $item) {
				if ( $item->hasDefault() ) {
					$item->setValue( $item->getDefault() );
					$type = $item->isOption() ? 'options' : 'arguments';
					$key = "{$type}.{$item->getName()}";
					$values[$key] = $item;
				}
				if ( $item->isRequired() && !$item->hasValue() ) {
					$this->missing[] = $item->getName();
				}
			}
		}

		# Check for unknown tokens
		if ($tokens) {
			$this->unknown = array_map(function($item) {
				return $item->getName();
			}, $tokens);
			$arguments = implode(', ', $this->unknown);
			throw new CallerException($this, "Unknown arguments: {$arguments}");
		}

		# Check for missing items
		if ($this->missing) {
			$arguments = implode(', ', $this->missing);
			throw new CallerException($this, "Missing required arguments: {$arguments}");
		}

		# Set values and call command
		$command->setValues($values);
		$command->handle();
	}

	/**
	 * Check argument
	 * @param  Argument     $argument Argument to check
	 * @param  array<Token> $tokens   Array of remaining tokens
	 * @param  Closure      $callback Callback closure
	 * @return array<Token>
	 */
	protected function checkArgument(Argument $argument, array $tokens, Closure $callback): array {
		$unmatched = [];
		while ( $token = array_shift($tokens) ) {
			if ( preg_match($argument->getExpr(), $token->getRaw(), $matches) === 1 ) {
				$value = $matches[1] ?? null;
				if ( $argument->isOption() && !$argument->isBoolean() && $value == null ) {
					# Try consuming the next value
					$token = array_shift($tokens);
					$value = $token->getRaw();
				}
				$value = $argument->isBoolean() ? true : $value;
				if ( $argument->isArray() ) {
					# Save the value as an array item
					$argument->pushValue($value);
					$callback($argument);
				} else {
					# Save the value and break
					$argument->setValue($value);
					$callback($argument);
					break;
				}
			} else {
				# Collect unmatched tokens
				$unmatched[] = $token;
				if (! $argument->isOption() ) {
					# For arguments, use the default value if set
					if ( $argument->hasDefault() ) {
						$argument->setValue( $argument->getDefault() );
						$callback($argument);
					}
					break;
				}
			}
		}
		if ( $argument->isRequired() && !$argument->hasValue() ) {
			$this->missing[] = $argument->getName();
		}
		return array_merge($unmatched, $tokens);
	}
}
