<?php

namespace Clarkson\WPCLITwigTranslations;

use Twig\Environment;
use Twig\Node\Expression\NameExpression;
use Twig\Node\Node;
use Twig\NodeVisitor\AbstractNodeVisitor;

class NodeVisitor extends AbstractNodeVisitor{

    protected function doEnterNode(Node $node, Environment $env) { 
        if($node instanceof NameExpression){
            $node->setAttribute('always_defined', true);
        }
        return $node;
    }

    protected function doLeaveNode(Node $node, Environment $env) {
        return $node;
     }

    public function getPriority() { 
        return 255;
    }
}