<<<<<<< HEAD
<?php
class Route
{
    private $controller;
    private $method;

    public function __construct($controller = "",  $method ="")
    {
        $this->controller = $controller;
        $this->method = strtolower($method);
    }

    public function getController()
    {
        return $this->controller;
    }

    public function setController($controller)
    {
        $this->controller = $controller;

        return $this;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function setMethod($method)
    {
        $this->method = strtolower($method);

        return $this;
    }

    public function getRoute()
    {
        return $this->controller . "/" . $this->method;
    }
=======
<?php
class Route
{
    private $controller;
    private $method;

    public function __construct($controller = "",  $method ="")
    {
        $this->controller = $controller;
        $this->method = strtolower($method);
    }

    public function getController()
    {
        return $this->controller;
    }

    public function setController($controller)
    {
        $this->controller = $controller;

        return $this;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function setMethod($method)
    {
        $this->method = strtolower($method);

        return $this;
    }

    public function getRoute()
    {
        return $this->controller . "/" . $this->method;
    }
>>>>>>> 5374c17ca241c72e06f846efc99cc7bdcff53bc2
}