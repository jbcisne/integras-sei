<?php
namespace Jb\Core\Generic;

class Element
{
    private $content;

    public function __construct($content)
    {
        $this->content = $content;
    }

    public function content()
    {
        return $this->content;
    }
}
