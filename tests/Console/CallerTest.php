<?php

declare(strict_types = 1);

/**
 * Caldera Console
 * Console abstraction layer, part of Vecode Caldera
 * @author  biohzrdmx <github.com/biohzrdmx>
 * @copyright Copyright (c) 2023 Vecode. All rights reserved
 */

namespace Caldera\Tests\Console;

use PHPUnit\Framework\TestCase;

use Caldera\Console\Caller;
use Caldera\Console\CallerException;
use Caldera\Console\Parser;
use Caldera\Tests\Console\Commands\TestCommand;

class CallerTest extends TestCase {

	public function testCaller() {
		$parser = new Parser();
		$caller = new Caller($parser);

		$command = new TestCommand();
		$args = [
			'run',
			'--certonly',
			'--dry-run',
			'-d=localhost',
			'-d=example.org',
		];

		$caller->call($command, $args);

		$this->assertEquals('run', $command->getArgument('action'));
		$this->assertEquals(true, $command->getOption('certonly'));
		$this->assertEquals(true, $command->getOption('dry-run'));
		$this->assertEquals(false, $command->getOption('version'));
		$this->assertEquals(['localhost', 'example.org'], $command->getOption('domain'));
		$this->assertTrue($command->hasArgument('action'));
		$this->assertTrue($command->hasOption('certonly'));
		$this->assertFalse($command->hasOption('version'));
	}

	public function testCallerMissingArguments() {
		$parser = new Parser();
		$caller = new Caller($parser);

		$command = new TestCommand();

		# Some args
		try {
			$args = [
				'run'
			];
			$caller->call($command, $args);
			$this->fail('A CallerException should have been thrown');
		} catch (CallerException $e) {
			$this->assertContains('domain', $e->getCaller()->getMissingArguments());
		}

		# No args at all
		try {
			$args = [];
			$caller->call($command, $args);
			$this->fail('A CallerException should have been thrown');
		} catch (CallerException $e) {
			$this->assertContains('domain', $e->getCaller()->getMissingArguments());
		}
	}

	public function testCallerUnknownArguments() {
		$parser = new Parser();
		$caller = new Caller($parser);

		$command = new TestCommand();

		# Wrongly-named args
		try {
			$args = [
				'--cert-only',
				'--dryrun',
				'-d=localhost',
				'-d',
				'example.org',
			];
			$caller->call($command, $args);
			$this->fail('A CallerException should have been thrown');
		} catch (CallerException $e) {
			$this->assertContains('cert-only', $e->getCaller()->getUnknownArguments());
		}
	}
}
