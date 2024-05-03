<?php

declare(strict_types = 1);

/**
 * Caldera Console
 * Console abstraction layer, part of Vecode Caldera
 * @author  biohzrdmx <github.com/biohzrdmx>
 * @copyright Copyright (c) 2023 Vecode. All rights reserved
 */

namespace Caldera\Tests\Console\Commands;

use Caldera\Console\Command\AbstractCommand;
use Caldera\Console\HasOutput;

class NoArgumentsCommand extends AbstractCommand {

	use HasOutput;

	protected string $signature = 'greet';

	public function handle() {
		$this->info('Hello!');
	}
}
