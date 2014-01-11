<?php namespace Holmes\Minutes;
/**
 * Class Minutes
 * A helper class, to make it easier to calculate the number of minutes
 * in an hour/day/week/month/year
 *
 * Usage for things like setting cache in laravel, where you need to specify the
 * time in minutes, and don't want to always have to do the calculations yourself
 *
 * @package Holmes\Minutes
 * @author Alan Holmes <alan@aeholmes.co.uk>
 */

use Holmes\Minutes\MinutesException;
use DateTime;

class Minutes {

  /**
   * Instance
   *
   * @var \Holmes\Minutes\Minutes
   */
  protected static $instance;

  /**
   * Returns the number of minutes in the given hours
   *
   * @param int $hours how many hours to calculate
   * @return int number of minutes in given hours
   */
  static public function hours($hours = 1)
  {
    return 60 * static::validNumber($hours);
  }

  /**
   * Returns the number of minutes in the given days
   *
   * @param int $days how many days to calculate
   * @return int number of minutes in given days
   */
  static public function days($days = 1)
  {
    return static::hours(24) * static::validNumber($days);
  }

  /**
   * Returns the number of minutes in the given weeks
   *
   * @param int $weeks how many weeks to calculate
   * @return int number of minutes in given weeks
   */
  static public function weeks($weeks = 1)
  {
    return static::days(7) * static::validNumber($weeks);
  }

  /**
   * Returns the number of minutes in the given months
   *
   * @param int $months how many months to calculate
   * @return int number of minutes in given months
   */
  static public function months($months = 1)
  {
    // Months are aren't as standard as the other methods
    // as there isn't a set number of days in a month
    // so we need to calculate how many days there are
    // in the number of months we have been given
    $days = static::numDaysBetweenDates(date('Y-m-d'), date('Y-m-d', strtotime('+ '.(static::validNumber($months)).' months')));

    return static::days($days);
  }

  /**
   * Returns the number of minutes in the given years
   *
   * @param int $years how many years to calculate
   * @return int number of minutes in given years
   */
  static public function years($years = 1)
  {
    // Years are aren't as standard as the other methods
    // as there isn't a set number of days in a year (leap year)
    // so we need to calculate how many days there are
    // in the number of years we have been given
    $days = static::numDaysBetweenDates(date('Y-m-d'), date('Y-m-d', strtotime('+ '.(static::validNumber($years)).' years')));

    return static::days($days);
  }

  /**
   * Helper function to check that a number is valid
   * Used by most methods in this class
   *
   * @param int|float $number the number to check
   * @param int|float $default a default value if the number is not valid
   * @return int|float
   */
  static protected function validNumber($number, $default = 1)
  {
    return is_numeric($number) ? $number : $default;
  }

  /**
   * Returns the number of days between the given dates
   *
   * @param string $start the start date (Y-m-d)
   * @param string $end the end date (Y-m-d)
   * @return int the number of days between the given dates
   * @author Alan Holmes
   */
  static protected function numDaysBetweenDates($start, $end)
  {
    $datetime1 = new DateTime($start);
    $datetime2 = new DateTime($end);
    return $datetime1->diff($datetime2)->days;
  }

  /**
   * Handle dynamic method calls
   * Checks if the method called has a plural version
   * and calls that with the argument of 1
   *
   * Allows you to call things like: Minutes::day()
   * instead of Minutes::days(1)
   *
   * @param string $name the method called
   * @param array $args any arguments passed through
   *
   * @return mixed value from the plural method
   * @throws MinutesException when method cannot be found
   */
  public static function __callStatic($name, $args)
  {
    // create an instance of this class
    $instance = static::$instance;
    if (!$instance) {
      $instance = static::$instance = new static;
    }

    // see if we can find a plural for this method and call it
    $method = $name.'s';
    if (method_exists($instance, $method)) {
      return $instance->$method(1);
    } else {
      throw new MinutesException('Call to undefined method ' . __CLASS__ . '::' . $name);
    }
  }
}