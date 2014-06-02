<?php
class Template {
    private $args;
    private $file;

    public function __get($name) {
        return $this->args[$name];
    }

    public function __construct($file, $args = array()) {
        $this->file = $file;
        $this->args = $args;
    }

    public function render() {
        include $this->file;
    }
    
    public function get() {
        ob_start();
        include $this->file;
        return ob_get_clean();        
    }
}

function get_template($name, $args = array())
{
    $template = new Template($name, $args);
    return $template->get(); 
}

function print_template($name, $args = array())
{
    $template = new Template($name, $args);
    $template->render();
}
?>