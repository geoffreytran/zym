<?php
/**
 * Zym
 *
 * LICENSE
 *
 * This file is intellectual property of Zym and may not
 * be used without permission.
 *
 * @category  Zym
 * @copyright Copyright (c) 2011 Zym. (http://www.zym.com/)
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