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

use Caldera\Console\Command\Argument;

class ArgumentTest extends TestCase {

	public function testArgument() {
		$argument = new Argument('foo', 'bar', 'f', true, false, false, false, '/\w+/');
		$this->assertEquals('foo', $argument->getName());
		$this->assertEquals('bar', $argument->getDefault());
		$this->assertEquals('f', $argument->getShortcut());
		$this->assertEquals('/\w+/', $argument->getExpr());
		$this->assertTrue($argument->isOption());
		$this->assertFalse($argument->isBoolean());
		$this->assertFalse($argument->isRequired());
		$this->assertFalse($argument->isArray());
		$this->assertTrue($argument->hasDefault());
		$this->assertTrue($argument->hasShortcut());
		$this->assertFalse($argument->hasValue());
		# Set value
		$argument->setValue('baz');
		$this->assertTrue($argument->hasValue());
		$this->assertEquals('baz', $argument->getValue());
		# JSON encode
		$this->assertNotEmpty(json_encode($argument));
		# Try to push to non-array argument
		$this->expectException(RuntimeException::class);
		$argument->pushValue('baz');
	}

	public function testArrayArgument() {
		$argument = new Argument('foo', '', 'f', true, false, false, true, '/\w+/');
		# Push value
		$argument->pushValue('bar');
		$argument->pushValue('baz');
		$this->assertTrue($argument->hasValue());
		$this->assertEquals(['bar', 'baz'], $argument->getValue());
	}
}
