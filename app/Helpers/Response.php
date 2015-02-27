<?php namespace BadShows\Helpers;

class Response
{
    // Path to the views
    protected $viewPath;

    /**
     * Sets the viewPath with basePath, as it is constructed
     *
     * @param $basePath
     */
    public function __construct($basePath)
    {
        $this->viewPath = $basePath . "/app/Views";
    }

    /**
     * Takes a response as a string or an array.
     * If it's an array it will be converted to a json string.
     *
     * Will also set the http response code.
     * Defaults to 200.
     *
     * @param     $response
     * @param int $code
     *
     * @return string
     */
    public function send($response, $code = 200, $headers = null)
    {
        if (is_array($response)) {
            $response = json_encode($response);
        }

        // If there's any headers
        if (!empty($headers)) {
            foreach ($headers as $header) {
                $key = key($header);
                $value = $header[$key];
                header("$key: $value");
            }
        }

        http_response_code($code);
        echo $response;
        return;
    }

    /**
     * Grabs the content of the view and just sends it to the client
     * No templating, no php in the views
     *
     * @param string $view
     *
     * @return string
     */
    public function make($view)
    {
        // Set the view path
        $view = "{$this->viewPath}/{$view}.view.html";

        // If the view exits
        if (file_exists($view)) {
            // Just grab the content and send it
            return $this->send(file_get_contents($view));
        }

        // Or if it's missing
        return $this->send("Missing view!", 404);
    }
}