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

use Caldera\Console\Command\Token;

class TokenTest extends TestCase {

	public function testToken() {
		$token = new Token('--foo=bar', true, 'foo', 'bar');
		$this->assertEquals('--foo=bar', $token->getRaw());
		$this->assertEquals('foo', $token->getName());
		$this->assertEquals('bar', $token->getValue());
		$this->assertTrue($token->isOption());
		$this->assertTrue($token->hasName());
		$this->assertTrue($token->hasValue());
		$this->assertNotEmpty(json_encode($token));
	}
}
