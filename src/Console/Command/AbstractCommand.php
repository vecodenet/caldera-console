<?php

declare(strict_types = 1);

/**
 * Caldera Console
 * Console abstraction layer, part of Vecode Caldera
 * @author  biohzrdmx <github.com/biohzrdmx>
 * @copyright Copyright (c) 2023 Vecode. All rights reserved
 */

namespace Caldera\Console\Command;

abstract class AbstractCommand implements CommandInterface {

	/**
	 * Command signature
	 */
	protected string $signature = '';

	/**
	 * Command arguments
	 */
	protected string $arguments = '';

	/**
	 * Current values
	 * @var array<Argument>
	 */
	protected array $values = [];

	/**
	 * @inheritdoc
	 */
	public function getArguments(): string {
		return $this->arguments;
	}

	/**
	 * @inheritdoc
	 */
	public function getValues(): array {
		return $this->values;
	}

	/**
	 * @inheritdoc
	 */
	public function setValues(array $values) {
		$this->values = $values;
		return $this;
	}

	/**
	 * Get argument value
	 * @param  string $name    Argument name
	 * @param  mixed  $default Default value
	 */
	public function getArgument(string $name, mixed $default = null): mixed{
		$key = "arguments.{$name}";
		$argument = $this->values[$key] ?? null;
		return $argument ? $argument->getValue() : $default;
	}

	/**
	 * Get option value
	 * @param  string $name    Option name
	 * @param  mixed  $default Default value
	 */
	public function getOption(string $name, mixed $default = null): mixed {
		$key = "options.{$name}";
		$option = $this->values[$key] ?? null;
		return $option ? $option->getValue() : $default;
	}

	/**
	 * Check if an argument is set
	 * @param  string  $name Argument name
	 */
	public function hasArgument(string $name): bool {
		$key = "arguments.{$name}";
		return isset( $this->values[$key] );
	}

	/**
	 * Check if an option is set
	 * @param  string  $name Option name
	 */
	public function hasOption(string $name): bool {
		$key = "options.{$name}";
		return isset( $this->values[$key] );
	}
}
