<?php
/**
 * HTML Table writer
 *
 * @version    5.7
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 *
 * Extends by
 * Leonam, Carlos (https://github.com/carlosleonam)
 */

class TTableWriterHTMLSis extends TTableWriterHTML
{
    public $arg1;
    public $arg2;

    /**
     * Set Header callback
     */
    public function setHeaderCallback( $callback, $arg1 = null, $arg2 = null )
    {
        $this->arg1 = $arg1;
        $this->arg2 = $arg2;
        call_user_func($callback, $this);
    }

}
