<?php

namespace EventEspresso\tests\mocks\core\services\dependencies\composites;

use EventEspresso\core\services\request\RequestInterface;

/**
 * OofOuchOwieDecorator
 *
 * @package EventEspresso\tests\mocks\core\services\dependencies\composites;
 * @author  Brent Christensen
 * @since   $VID:$
 */
abstract class OofOuchOwieDecorator extends OofOuchOwie
{
    /**
     * @var OofOuchOwieInterface $oofOuchOwie
     */
    protected $oofOuchOwie;

    /**
     * @param OofOuchOwieInterface $oofOuchOwie
     * @param RequestInterface     $request
     */
    public function __construct(OofOuchOwieInterface $oofOuchOwie, RequestInterface $request)
    {
        $this->oofOuchOwie = $oofOuchOwie;
        parent::__construct($request);
    }

    /**
     * @since $VID:$
     * @return string
     */
    public function oofOuchOwie()
    {
        return $this->oofOuchOwie->oofOuchOwie() . $this->request->getRequestParam($this->key);
    }
}