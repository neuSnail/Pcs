<?php
namespace Pcs\Frame\Http\View;
class View
{
    private $tpl;
    private $vars;

    public function __construct($tpl, $vars)
    {
        $this->tpl = $tpl;
        $this->vars = $vars;
    }

    public function getContent()
    {
        if (is_array($this->vars) && !empty($this->vars)) {
            extract($this->vars);
        }

        ob_start();
        require $this->tpl;
        return ob_get_clean();
    }
}