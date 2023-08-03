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

class TestCommand extends AbstractCommand {

	use HasOutput;

	protected string $signature = 'certbot';

	protected string $arguments = '{action=run?} {--certonly?} {--standalone?} {--webroot=?} {--test-cert?} {--dry-run?} {--manual?} {--d|domain=*} {--version?}';

	public function handle() {
		$values = $this->getValues();
		echo json_encode($values);
	}
}
