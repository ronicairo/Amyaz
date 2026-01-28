<?php

namespace App\Doctrine\Functions;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\SqlWalker;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\TokenType;

class CollateFunction extends FunctionNode
{
    public $expression1 = null;
    public $expression2 = null;

    public function parse(Parser $parser): void
    {
        // Match the function name (e.g., BINARY_COMPARE)
        $parser->match(TokenType::T_IDENTIFIER);

        // Match open parenthesis
        $parser->match(TokenType::T_OPEN_PARENTHESIS);

        // Parse the first expression
        $this->expression1 = $parser->StringPrimary();

        // Match comma
        $parser->match(TokenType::T_COMMA);

        // Parse the second expression
        $this->expression2 = $parser->StringPrimary();

        // Match close parenthesis
        $parser->match(TokenType::T_CLOSE_PARENTHESIS);
    }

    public function getSql(SqlWalker $sqlWalker): string
    {
        if ($this->expression1 === null || $this->expression2 === null) {
            throw new \RuntimeException('BinaryCompareFunction properties not properly set.');
        }


        return sprintf(
            'BINARY %s = BINARY %s',
            $this->expression1->dispatch($sqlWalker),
            $this->expression2->dispatch($sqlWalker)
        );
    }
}