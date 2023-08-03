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
use InvalidArgumentException;

use PHPUnit\Framework\TestCase;

use Caldera\Console\Color;
use Caldera\Console\HasOutput;

class HasOutputTest extends TestCase {

	use HasOutput;

	public function testLine() {
		$this->expectOutputString("foo" . PHP_EOL);
		$this->line('foo');
	}

	public function testBlank() {
		$this->expectOutputString(PHP_EOL);
		$this->blank();
	}

	public function testInfo() {
		$this->expectOutputString("\033[36mfoo\033[0m" . PHP_EOL);
		$this->info('foo');
	}

	public function testWarning() {
		$this->expectOutputString("\033[33mfoo\033[0m" . PHP_EOL);
		$this->warning('foo');
	}

	public function testSuccess() {
		$this->expectOutputString("\033[32mfoo\033[0m" . PHP_EOL);
		$this->success('foo');
	}

	public function testError() {
		$this->expectOutputString("\033[31mfoo\033[0m" . PHP_EOL);
		$this->error('foo');
	}

	public function testColorizeInvalidFg() {
		$this->expectException(InvalidArgumentException::class);
		$this->colorize('foo', Color::bgRed);
	}

	public function testColorizeInvalidBg() {
		$this->expectException(InvalidArgumentException::class);
		$this->colorize('foo', Color::fgRed, Color::fgBlack);
	}

	public function testColorize() {
		$colorized = $this->colorize('foo', Color::fgRed, Color::bgYellow);
		$this->assertEquals("\033[37;43m\033[31mfoo\033[0m", $colorized);
	}

	public function testFormat() {
		$text = $this->format('Hello {foo}!', ['foo' => 'world']);
		$this->assertEquals("Hello world!", $text);
		$text = $this->format('Hello {foo}!', ['foo' => 'world', 'bar' => 'baz']);
		$this->assertEquals("Hello world!", $text);
		$this->expectException(RuntimeException::class);
		$text = $this->format('Hello {foo} {bar}!', ['foo' => 'world']);
		$this->assertEquals("Hello world!", $text);
	}

	public function testProgressBar() {
		$this->expectOutputRegex('/56%/');
		$this->progressBar(56);
	}
}
