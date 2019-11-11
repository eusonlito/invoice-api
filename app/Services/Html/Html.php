<?php declare(strict_types=1);

namespace App\Services\Html;

class Html
{
    /**
     * @param string $html
     *
     * @return string
     */
    public static function fix(string $html): string
    {
        return Nodes::DOMDocument($html)->saveHTML();
    }
}
