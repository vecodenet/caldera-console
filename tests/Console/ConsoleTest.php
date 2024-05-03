<?php

declare(strict_types = 1);

/**
 * Caldera Console
 * Console abstraction layer, part of Vecode Caldera
 * @author  biohzrdmx <github.com/biohzrdmx>
 * @copyright Copyright (c) 2023 Vecode. All rights reserved
 */

namespace Caldera\Tests\Console;

use InvalidArgumentException;
use RuntimeException;

use PHPUnit\Framework\TestCase;

use Caldera\Console\Console;
use Caldera\Tests\Console\Commands\TestCommand;

class ConsoleTest extends TestCase {

	public function testAddPaths() {
		$console = new Console();
		$console->path( dirname(__FILE__) . '/Commands' );
		$console->call('certbot', ['-d vecode.net']);
		# The command outputs a JSON, so let's check that
		$this->expectOutputRegex('/^{.*}$/');
		# Try with non-existing path
		$this->expectException(InvalidArgumentException::class);
		$console->path( dirname(__FILE__) . '/Unknown' );
		# Now include the invalid commands
		$console->path( dirname(__FILE__) . '/Commands/Invalid' );
		$this->expectException(RuntimeException::class, "Command 'Caldera\Tests\Console\Commands\Invalid\InvalidCommand' does not have a valid signature");
		$console->call('certbot', ['-d vecode.net']);
	}

	public function testAddPathsRecursive() {
		$console = new Console();
		$console->path( dirname(__FILE__) . '/Commands', true );
		$this->expectException(RuntimeException::class, "Command 'Caldera\Tests\Console\Commands\Invalid\InvalidCommand' does not have a valid signature");
		$console->call('certbot', ['-d vecode.net']);
	}

	public function testAddCommand() {
		$console = new Console();
		$console->command(TestCommand::class);
		$console->call('certbot', ['-d vecode.net']);
		# The command outputs a JSON, so let's check that
		$this->expectOutputRegex('/^{.*}$/');
		# Now with a non existing class
		$console = new Console();
		$this->expectException(RuntimeException::class, "Class 'Caldera\Tests\Console\Commands\Dummy\DummyCommand' does not exist");
		$console->command('Caldera\Tests\Console\Commands\Dummy\DummyCommand');
	}

	public function testNoArguments() {
		$console = new Console();
		$this->expectException(RuntimeException::class, "No commands available");
		$console->call('greet');
	}

	public function testEmpty() {
		$console = new Console();
		$this->expectException(RuntimeException::class, "No commands available");
		$console->call('certbot', ['-d vecode.net']);
	}
}
