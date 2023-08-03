<?php

declare(strict_types = 1);

/**
 * Caldera Console
 * Console abstraction layer, part of Vecode Caldera
 * @author  biohzrdmx <github.com/biohzrdmx>
 * @copyright Copyright (c) 2023 Vecode. All rights reserved
 */

namespace Caldera\Tests\Console;

use RuntimeException;

use PHPUnit\Framework\TestCase;

use Caldera\Console\Parser;
use Caldera\Console\Command\Argument;
use Caldera\Console\Command\Token;

use Caldera\Tests\Console\Commands\Invalid\MalformedCommand;
use Caldera\Tests\Console\Commands\TestCommand;

class ParserTest extends TestCase {

	public function testParseCommandArguments() {
		$parser = new Parser();
		$command = new TestCommand();
		$arguments = $parser->parseCommandArguments($command);
		$this->assertContainsOnlyInstancesOf(Argument::class, $arguments);

		$parser = new Parser();
		$command = new MalformedCommand();
		$this->expectException(RuntimeException::class);
		$arguments = $parser->parseCommandArguments($command);
	}

	public function testParseInputArguments() {
		$parser = new Parser();
		$args = [
			'run',
			'--certonly',
			'--dry-run',
			'-d=localhost',
			'-d=example.org',
		];
		$tokens = $parser->parseInputArguments($args);
		$this->assertContainsOnlyInstancesOf(Token::class, $tokens);
	}
}
