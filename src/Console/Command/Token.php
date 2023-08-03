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

class Token implements JsonSerializable {

	/**
	 * Token raw representation
	 */
	protected string $raw = '';

	/**
	 * Token name
	 */
	protected string $name = '';

	/**
	 * Token is an option
	 */
	protected bool $option = false;

	/**
	 * Token value
	 */
	protected mixed $value = null;

	/**
	 * Constructor
	 * @param string $raw    Token raw representation
	 * @param bool   $option Token is an option
	 * @param string $name   Token name
	 * @param mixed  $value  Token value
	 */
	public function __construct(
		string $raw,
		bool $option,
		string $name = '',
		mixed $value = null
	) {
		$this->raw = $raw;
		$this->name = $name;
		$this->option = $option;
		$this->value = $value;
	}

	/**
	 * Get token raw representation
	 */
	public function getRaw(): string {
		return $this->raw;
	}

	/**
	 * Get token name
	 */
	public function getName(): string {
		return $this->name;
	}

	/**
	 * Get token value
	 */
	public function getValue(): mixed {
		return $this->value;
	}

	/**
	 * Check if token is an option
	 */
	public function isOption(): bool {
		return $this->option;
	}

	/**
	 * Check if token has a name
	 */
	public function hasName(): bool {
		return $this->name !== null;
	}

	/**
	 * Check if token has a value set
	 */
	public function hasValue(): bool {
		return $this->value !== null;
	}

	/**
	 * @inheritdoc
	 */
	public function jsonSerialize(): mixed {
		return [
			'name' => $this->name,
			'value' => $this->value,
			'option' => $this->option,
			'raw' => $this->raw,
		];
	}
}
