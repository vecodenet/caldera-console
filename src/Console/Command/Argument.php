<?php

declare(strict_types = 1);

/**
 * Caldera Console
 * Console abstraction layer, part of Vecode Caldera
 * @author  biohzrdmx <github.com/biohzrdmx>
 * @copyright Copyright (c) 2023 Vecode. All rights reserved
 */

namespace Caldera\Console\Command;

use JsonSerializable;
use RuntimeException;

class Argument implements JsonSerializable {

	/**
	 * Argument name
	 */
	protected string $name = '';

	/**
	 * Argument default
	 */
	protected string $default = '';

	/**
	 * Argument shortcut
	 */
	protected string $shortcut = '';

	/**
	 * Argument is an option
	 */
	protected bool $option = false;

	/**
	 * Argument is boolean
	 */
	protected bool $boolean = false;

	/**
	 * Argument is required
	 */
	protected bool $required = false;

	/**
	 * Argument is an array
	 */
	protected bool $array = false;

	/**
	 * Argument expr
	 */
	protected string $expr = '';

	/**
	 * Argument value
	 */
	protected mixed $value = null;

	/**
	 * Constructor
	 * @param string $name     Argument name
	 * @param string $default  Argument default
	 * @param string $shortcut Argument shortcut
	 * @param bool   $option   Argument is an option
	 * @param bool   $boolean  Argument is boolean
	 * @param bool   $required Argument is required
	 * @param bool   $array    Argument is an array
	 * @param string $expr     Argument expr
	 */
	public function __construct(
		string $name = '',
		string $default = '',
		string $shortcut = '',
		bool $option = false,
		bool $boolean = false,
		bool $required = false,
		bool $array = false,
		string $expr = ''
	) {
		$this->name = $name;
		$this->default = $default;
		$this->shortcut = $shortcut;
		$this->option = $option;
		$this->boolean = $boolean;
		$this->required = $required;
		$this->array = $array;
		$this->expr = $expr;
	}

	/**
	 * Get argument name
	 */
	public function getName(): string {
		return $this->name;
	}

	/**
	 * Get argument default
	 */
	public function getDefault(): string {
		return $this->default;
	}

	/**
	 * Get argument shortcut
	 */
	public function getShortcut(): string {
		return $this->shortcut;
	}

	/**
	 * Get argument expr
	 */
	public function getExpr(): string {
		return $this->expr;
	}

	/**
	 * Get argument value
	 */
	public function getValue(): mixed {
		return $this->value;
	}

	/**
	 * Check if argument is an option
	 */
	public function isOption(): bool {
		return $this->option;
	}

	/**
	 * Check if argument is boolean
	 */
	public function isBoolean(): bool {
		return $this->boolean;
	}

	/**
	 * Check if argument is required
	 */
	public function isRequired(): bool {
		return $this->required;
	}

	/**
	 * Check if argument is an array
	 */
	public function isArray(): bool {
		return $this->array;
	}

	/**
	 * Check if argument has a default value
	 */
	public function hasDefault(): bool {
		return $this->default !== '';
	}

	/**
	 * Check if argument has a shortcut
	 */
	public function hasShortcut(): bool {
		return $this->shortcut !== '';
	}

	/**
	 * Check if argument has a value set
	 */
	public function hasValue(): bool {
		return $this->value !== null;
	}

	/**
	 * Set argument value
	 * @param  mixed $value Argument value
	 * @return $this
	 */
	public function setValue(mixed $value) {
		$this->value = $value;
		return $this;
	}

	/**
	 * Add argument value
	 * @param  mixed $value Argument value
	 * @return $this
	 */
	public function pushValue(mixed $value) {
		if ($this->array) {
			if (! $this->value ) {
				$this->value = [];
			}
			$this->value[] = $value;
		} else {
			throw new RuntimeException('This can only be called for array arguments');
		}
		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function jsonSerialize(): mixed {
		return [
			'name' => $this->name,
			'default' => $this->default,
			'shortcut' => $this->shortcut,
			'option' => $this->option,
			'boolean' => $this->boolean,
			'required' => $this->required,
			'array' => $this->array,
			'expr' => $this->expr,
			'value' => $this->value,
		];
	}
}
