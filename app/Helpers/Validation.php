<?php namespace BadShows\Helpers;

use InvalidArgumentException;

/**
 * Class Validation
 * @package BadShows\Helpers
 *
 * Run validations against specified rules.
 * Implemented rules:
 *  + required
 *  + numeric
 *  + string
 *
 * Format:
 *      "values" => [
 *          "field" => "My Field Value"
 *      ]
 *
 *      "config" => [
 *          "field" => [
 *              "required",
 *              "string
 *          ]
 *      ]
 */
class Validation
{
    protected $errorMessages = [];

    /**
     * Run validation against values
     *
     * @param array $values
     * @param array $config
     * @return bool
     */
    public function run(array $values, array $config)
    {
        // Empty old error messages and set the validation return to true
        $this->clearErrorMessages();
        $validated = true;

        // If both values and configured rules are empty, we don't care
        if (empty($values) && empty($config)) {
            return true;
        }

        // Loop through rules and compare them against the values
        foreach ($config as $key => $rules) {
            foreach ($rules as $rule) {
                if ($this->checkRule($rule, $values, $key) === false) {
                    $validated = false;
                }
            }
        }

        return $validated;
    }

    /**
     * Run the rule specified or throw InvalidArgumentException
     *
     * @param $rule
     * @param $values
     * @param $key
     * @return mixed
     */
    public function checkRule($rule, $values, $key)
    {
        if (method_exists($this, $rule)) {
            return $this->{$rule}($values, $key);
        }

        throw new InvalidArgumentException("{$rule} isn't a valid validation rule.");
    }

    /**
     * Check if the value is required
     *
     * @param $values
     * @param $key
     * @return bool
     */
    public function required($values, $key)
    {
        // Check for the value
        if (isset($values[$key])) {
            return true;
        }

        $this->addErrorMessage("required", $key);
        return false;
    }

    /**
     * Check if the value is numeric
     *
     * @param $values
     * @param $key
     * @return bool
     */
    public function numeric($values, $key)
    {
        // We don't care about required
        if (isset($values[$key]) === false) {
            return true;
        }

        // Is the value a numerical value
        if (is_numeric($values[$key])) {
            return true;
        }

        $this->addErrorMessage("numeric", $key);
        return false;
    }

    /**
     * Check if the value is a string
     *
     * @param $values
     * @param $key
     * @return bool
     */
    public function string($values, $key)
    {
        // We don't care about required
        if (isset($values[$key]) === false) {
            return true;
        }

        // Is the value a string
        if (is_string($values[$key])) {
            return true;
        }

        $this->addErrorMessage("string", $key);
        return false;
    }

    public function clearErrorMessages()
    {
        $this->errorMessages = [];
    }

    public function addErrorMessage($type, $message)
    {
        $this->errorMessages[$type][] = $message;
    }

    public function getErrorMessages()
    {
        return $this->errorMessages;
    }
}
