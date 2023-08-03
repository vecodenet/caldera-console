<?php

declare(strict_types = 1);

/**
 * Caldera Console
 * Console abstraction layer, part of Vecode Caldera
 * @author  biohzrdmx <github.com/biohzrdmx>
 * @copyright Copyright (c) 2023 Vecode. All rights reserved
 */

namespace Caldera\Tests\Console\Commands\Invalid;

use Caldera\Console\Command\AbstractCommand;

class MalformedCommand extends AbstractCommand {

	protected string $signature = 'certbot';

	protected string $arguments = 'ACTION --verbose --dry-run';

	public function handle() {
		//
	}
}
