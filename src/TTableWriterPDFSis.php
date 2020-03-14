<?php
/**
 * PDF Writer
 *
 * @version    5.7
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 *
 * Extends by
 * Leonam, Carlos (https://github.com/carlosleonam)
 */

class TTableWriterPDFSis extends TTableWriterPDF
{
    private $pdf;
    public $arg1;
    public $arg2;


    /**
     * Constructor
     * @param $widths Array with column widths
     */
    public function __construct($widths, $orientation='P', $format = 'A4')
    {
        parent::__construct($widths, $orientation, $format);
        // cria o objeto FPDF
        $this->pdf = new FPDF($orientation, 'pt', $format);
        $this->pdf->Open();
        $this->pdf->AddPage();
    }


    /**
     * Set Header callback
     */
    // public function setHeaderCallback( $callback )
    public function setHeaderCallback( $callback, $arg1 = null, $arg2 = null )
    {
        $this->arg1 = $arg1;
        $this->arg2 = $arg2;
        // call the first time
        call_user_func($callback, $this);
        $this->pdf->setHeaderCallback($callback, $this);
    }

}
