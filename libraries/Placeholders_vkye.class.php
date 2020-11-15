<?php

defined('_EXEC') or die;

class Placeholders_vkye
{
    private $buffer;
    private $format;

    public function __construct($buffer)
    {
        $this->buffer = $buffer;
        $this->format = new Format();
    }

    public function run()
    {
        $this->buffer = $this->replace_header();
        $this->buffer = $this->replace_placeholders();

        return $this->buffer;
    }

    private function replace_header()
    {
        return $this->format->include_file($this->buffer, 'header');
    }

    private function replace_placeholders()
    {
        $replace = [
            '{$_vkye_seo_title}' => System::settings('seo', 'title', $GLOBALS['_vkye_module'], true),
            '{$_vkye_seo_keywords}' => System::settings('seo', 'keywords', $GLOBALS['_vkye_module'], true),
            '{$_vkye_seo_description}' => System::settings('seo', 'description', $GLOBALS['_vkye_module'], true)
        ];

        return $this->format->replace($replace, $this->buffer);
    }
}
