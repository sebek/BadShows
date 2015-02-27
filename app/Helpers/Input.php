<?php namespace BadShows\Helpers;

class Input
{
    /**
     * Get postdata from php://input and tries to convert it to a array
     * from json.
     *
     * @return array|bool
     */
    private function getInput()
    {
        $inputString = file_get_contents("php://input");
        $inputJson = json_decode($inputString);

        // If it's valid json, return it as an array
        if (json_last_error() === JSON_ERROR_NONE) {
            return (array)$inputJson;
        }

        return false;
    }

    /**
     * Gets a value form the input by name
     *
     * @param $name
     * @return string|bool
     */
    public function get($name)
    {
        $inputs = $this->getInput();

        if (isset($inputs[$name])) {
            return $inputs[$name];
        }

        return false;
    }

    /**
     * Gets every inputted value
     *
     * @return array|bool
     */
    public function all()
    {
        return $this->getInput();
    }

}