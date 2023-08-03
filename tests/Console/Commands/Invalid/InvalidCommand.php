<?php

declare(strict_types = 1);

/**
 * Caldera Console
 * Console abstraction layer, part of Vecode Caldera
 * @author  biohzrdmx <github.com/biohzrdmx>
 * @copyright Copyright (c) 2023 Vecode. All rights reserved
 */

namespace Caldera\Tests\Console\Commands\Invalid;

use Caldera\Console\Command\CommandInterface;

class InvalidCommand implements CommandInterface {

	public function getArguments(): string {
		return '';
	}

	public function getValues(): array {
		return [];
	}

	public function setValues(array $values) {
		return $this;
	}

	public function handle() {
		//
	}
}
