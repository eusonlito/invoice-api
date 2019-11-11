<?php declare(strict_types=1);

namespace App\Services\Html;

use DOMDocument;

class Nodes
{
    /**
     * @param string $html
     *
     * @return \DOMDocument
     */
    public static function DOMDocument(string $html): DOMDocument
    {
        libxml_use_internal_errors(true);

        $DOM = new DOMDocument;
        $DOM->recover = true;
        $DOM->preserveWhiteSpace = false;
        $DOM->substituteEntities = false;
        $DOM->loadHtml('<?xml encoding="UTF-8">'.$html, LIBXML_NOBLANKS | LIBXML_ERR_NONE);

        libxml_use_internal_errors(false);

        return $DOM;
    }
}
