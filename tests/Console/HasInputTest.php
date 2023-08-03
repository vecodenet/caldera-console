<?php

declare(strict_types = 1);

/**
 * Caldera Console
 * Console abstraction layer, part of Vecode Caldera
 * @author  biohzrdmx <github.com/biohzrdmx>
 * @copyright Copyright (c) 2023 Vecode. All rights reserved
 */

namespace Caldera\Console {

	use Caldera\Tests\Console\HasInputTest;

	function readline() {
		return is_array(HasInputTest::$readline) ? array_shift(HasInputTest::$readline) : HasInputTest::$readline;
	}

	function exec() {
		return HasInputTest::$exec;
	}

	function fgets() {
		return HasInputTest::$fgets;
	}
}

namespace Caldera\Tests\Console {

	use PHPUnit\Framework\TestCase;

	use Caldera\Console\HasInput;
	use Caldera\Console\HasOutput;
	use PHPUnit\Framework\MockObject\MockObject;

	class HasInputTest extends TestCase {

		public static $readline = 'foo';
		public static $exec = 'foo';
		public static $fgets = 'foo';

		public function testGetString() {
			$proxy = new Proxy();
			$this->expectOutputString('Input something: ');
			$string = $proxy->getString('Input something:');
			$this->assertEquals('foo', $string);
		}

		public function testGetStringHidden() {
			# Default
			$proxy = new Proxy();
			ob_start();
			$string = $proxy->getString('Enter your name', true);
			ob_get_clean();
			$this->assertEquals('foo', $string);

			# Mock Windows
			/** @var Proxy|MockObject */
			$proxy = $this->getMockBuilder(Proxy::class)
				->onlyMethods(['getOsIdentifier'])
				->getMock();
			$proxy->method('getOsIdentifier')->will($this->returnValue('WINNT'));
			ob_start();
			$string = $proxy->getString('Enter your name', true);
			ob_get_clean();
			$this->assertEquals('foo', $string);

			# Mock other OSes
			/** @var Proxy|MockObject */
			$proxy = $this->getMockBuilder(Proxy::class)
				->onlyMethods(['getOsIdentifier'])
				->getMock();
			$proxy->method('getOsIdentifier')->will($this->returnValue('UNIX'));
			ob_start();
			$string = $proxy->getString('Enter your name', true);
			ob_get_clean();
			$this->assertEquals('foo', $string);
		}

		public function testGetKey() {
			self::$readline = 'y';
			$proxy = new Proxy();
			ob_start();
			$key = $proxy->getKey(['y', 'n'], 'Press Y or N');
			$buffer = ob_get_clean();
			$this->assertEquals('Press Y or N ', $buffer);
			$this->assertEquals('y', $key);
		}

		public function testGetKeyWrong() {
			$proxy = new Proxy();
			self::$readline = ['x', 'y'];
			ob_start();
			$key = $proxy->getKey(['y', 'n'], 'Press Y or N');
			$buffer = ob_get_clean();
			$this->assertTrue(str_contains($buffer, 'Please press a valid key'));
			$this->assertEquals('y', $key);
		}
	}

	class Proxy {

		use HasInput, HasOutput;
	}
}
