<?php namespace Holmes\Minutes;
/**
 * Class MinutesException
 * Simple exception handler to echo out any errors we throw
 *
 * @package Holmes\Minutes
 * @author Alan Holmes <alan@aeholmes.co.uk>
 */

use Exception;

class MinutesException extends Exception {

  public function __construct($message, $code = 0, Exception $previous = null)
  {
    parent::__construct($message, $code, $previous);
  }
}