<?php

namespace Clarkson\WPCLITwigTranslations;

use Twig\Extension\AbstractExtension;

class Extension extends AbstractExtension{
    public function getNodeVisitors() {
        return [new NodeVisitor()];
    }
}