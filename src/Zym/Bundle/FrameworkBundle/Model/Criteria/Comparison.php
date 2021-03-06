<?php

/**
 * Zym Framework
 *
 * This file is part of the Zym package.
 *
 * @link      https://github.com/geoffreytran/zym for the canonical source repository
 * @copyright Copyright (c) 2014 Geoffrey Tran <geoffrey.tran@gmail.com>
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3 License
 */

namespace Zym\Bundle\FrameworkBundle\Model\Criteria;

class Comparison
{
    const EQ  = '=';
    const NEQ = '<>';
    const LT  = '<';
    const LTE = '<=';
    const GT  = '>';
    const GTE = '>=';

    /**
     * Operator
     *
     * One of the constants
     *
     * @var string
     */
    private $operator;

    /**
     * Right Expression
     *
     * @var string
     */
    private $rightExpr;

    /**
     * Construct
     *
     * @param string $operator
     * @param string $rightExpr
     */
    public function __construct($operator, $rightExpr)
    {
        $this->operator  = $operator;
        $this->rightExpr = $rightExpr;
    }

    /**
     * Get the operator
     *
     * @return string
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * Set the operator
     *
     * @param string $operator
     * @return Comparison
     */
    public function setOperator($operator)
    {
        $this->operator = $operator;
        return $this;
    }

    /**
     * Get the right expression
     *
     * @return string
     */
    public function getRightExpr()
    {
        return $this->rightExpr;
    }

    /**
     * Set the right expression
     *
     * @param string $rightExpr
     * @return Comparison
     */
    public function setRightExpr($rightExpr)
    {
        $this->rightExpr = $rightExpr;
        return $this;
    }
}