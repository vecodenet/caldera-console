<?php

declare(strict_types = 1);

/**
 * Caldera Console
 * Console abstraction layer, part of Vecode Caldera
 * @author  biohzrdmx <github.com/biohzrdmx>
 * @copyright Copyright (c) 2023 Vecode. All rights reserved
 */

namespace Caldera\Console\Command;

interface CommandInterface {

	/**
	 * Get command arguments
	 */
	public function getArguments(): string;

	/**
	 * Get current values
	 */
	public function getValues(): array;

	/**
	 * Set current values
	 * @param  array $values Values array
	 * @return $this
	 */
	public function setValues(array $values);

	/**
	 * Handle command
	 * @return mixed
	 */
	public function handle();
}
