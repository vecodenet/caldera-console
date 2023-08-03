<?php

declare(strict_types = 1);

/**
 * Caldera Console
 * Console abstraction layer, part of Vecode Caldera
 * @author  biohzrdmx <github.com/biohzrdmx>
 * @copyright Copyright (c) 2023 Vecode. All rights reserved
 */

namespace Caldera\Console;

use Exception;
use Throwable;

class CallerException extends Exception {

	/**
	 * Caller instance
	 */
	protected Caller $caller;

	/**
	 * Constructor
	 * @param Caller $caller Caller instance
	 */
	public function __construct(Caller $caller, string $message = '', int $code = 0, ?Throwable $previous = null) {
		parent::__construct($message, $code, $previous);
		$this->caller = $caller;
	}

	/**
	 * Get caller instance
	 * @return Caller
	 */
	public function getCaller(): Caller {
		return $this->caller;
	}
}
