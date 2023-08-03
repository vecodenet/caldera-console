<?php

declare(strict_types = 1);

/**
 * Caldera Console
 * Console abstraction layer, part of Vecode Caldera
 * @author  biohzrdmx <github.com/biohzrdmx>
 * @copyright Copyright (c) 2023 Vecode. All rights reserved
 */

namespace Caldera\Console;

use PhpToken;
use ReflectionClass;
use RuntimeException;
use InvalidArgumentException;

use Psr\Container\ContainerInterface;

use Caldera\Console\HasInput;
use Caldera\Console\HasOutput;
use Caldera\Console\Command\CommandInterface;

class Console {

	use HasInput,
		HasOutput;

	/**
	 * Paths array
	 * @var array<string>
	 */
	protected array $paths = [];

	/**
	 * Commands array
	 * @var array<string>
	 */
	protected array $commands = [];

	/**
	 * Loaded flag
	 */
	protected bool $loaded = false;

	/**
	 * ContainerInterface implementation
	 */
	protected ?ContainerInterface $container;

	/**
	 * Constructor
	 * @param ContainerInterface $container ContainerInterface implementation
	 */
	public function __construct(?ContainerInterface $container = null) {
		$this->container = $container;
	}

	/**
	 * Register a new path to load commands from
	 * @param  string $path Path to load commands from
	 * @return $this
	 */
	public function path(string $path) {
		if ( file_exists($path) && is_dir($path) ) {
			$this->paths[] = $path;
			$this->loaded = false;
		} else {
			throw new InvalidArgumentException('The specified path does not exist');
		}
		return $this;
	}

	/**
	 * Register a new command
	 * @param  string $handler Command handler class
	 * @return $this
	 */
	public function command(string $handler) {
		if ( class_exists($handler) ) {
			$reflector = new ReflectionClass($handler);
			if ( $reflector->implementsInterface(CommandInterface::class) ) {
				$properties = $reflector->getDefaultProperties();
				$signature = $properties['signature'] ?? null;
				if ($signature) {
					$this->commands[$signature] = $handler;
				} else {
					throw new RuntimeException("Command '{$handler}' does not have a valid signature");
				}
			} else {
				throw new InvalidArgumentException("Command '{$handler}' must implement CommandInterface");
			}
		} else {
			throw new RuntimeException("Class '{$handler}' does not exist");
		}
		return $this;
	}

	/**
	 * Call a command
	 * @param  string        $command Command to call
	 * @param  array<string> $argv    Arguments list
	 */
	public function call(string $command, array $argv = []): bool {
		$handled = false;
		if (! $this->loaded ) {
			$this->autoload();
		}
		if ($this->commands) {
			foreach ($this->commands as $name => $handler) {
				if ($command == $name) {
					$class = $this->container ? $this->container->get($handler) : new $handler();
					if ($class instanceof CommandInterface) {
						$parser = new Parser();
						$caller = new Caller($parser);
						$caller->call($class, $argv);
						$handled = true;
						break;
					}
				}
			}
			if (! $handled ) {
				throw new RuntimeException('No command was found that matches the given signature');
			}
		} else {
			throw new RuntimeException('No commands available');
		}
		return $handled;
	}

	/**
	 * Autoload commands from the registered directories
	 * @return $this
	 */
	protected function autoload() {
		# Now iterate the registered paths
		if ($this->paths) {
			foreach ($this->paths as $path) {
				$files = scandir($path);
				if ($files) {
					foreach ($files as $file) {
						if ( preg_match('/(.*)\.php/', $file, $matches) === 1 ) {
							$classes = $this->getDefinedClasses($path . DIRECTORY_SEPARATOR . $file);
							foreach ($classes as $class) {
								$this->command($class);
							}
						}
					}
				}
			}
		}
		$this->loaded = true;
		return $this;
	}

	/**
	 * Get the FQCN of defined classes in a given PHP file
	 * @param  string $path Path to file
	 * @return array<string>
	 */
	protected function getDefinedClasses(string $path): array {
		$classes = [];
		$namespace = '';
		$contents = file_get_contents($path);
		if ($contents) {
			$tokens = PhpToken::tokenize($contents);
			for ($i = 0; $i < count($tokens); $i++) {
				if ( $tokens[$i]->getTokenName() === 'T_NAMESPACE' ) {
					for ($j = $i + 1; $j < count($tokens); $j++) {
						if ( $tokens[$j]->getTokenName() === 'T_NAME_QUALIFIED' ) {
							$namespace = $tokens[$j]->text;
							break;
						}
					}
				}
				if ( $tokens[$i]->getTokenName() === 'T_CLASS' ) {
					for ($j = $i + 1; $j < count($tokens); $j++) {
						if ( $tokens[$j]->getTokenName() === 'T_WHITESPACE' ) {
							continue;
						}
						if ( $tokens[$j]->getTokenName() === 'T_STRING' ) {
							$classes[] = $namespace . '\\' . $tokens[$j]->text;
						} else {
							break;
						}
					}
				}
			}
		}
		return $classes;
	}
}
