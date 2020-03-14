<?php
/**
 * Report Generator from Adianti TDatagrid
 *
 * @author Leonam, Carlos (https://github.com/carlosleonam)
 * @license MIT
 */
class TGeneratorReport
{

    public $database;
    public $active_record;
    public $datagrid_columns;
    public $form_title;
    public $filters;
    public $type_relat;
    public $widths;
    public $position;
    public $totals;
    public $order;


    /**
     * __construct
     *
     * @param string $database
     * @param string $active_record
     * @param object $datagrid_columns
     * @param string $form_title
     * @param TCriteria $filters
     * @param string $type_relat
     * @param string $position
     * @param array $widths
     * @param array $totals
     * @param string $order
     */
    function __construct( $database, $active_record, $datagrid_columns, $form_title, $filters, $type_relat = 'pdf', $position = 'L', $widths = null, $totals = null, $order = null )
	{
        $this->database = $database;
        $this->active_record = $active_record;
        $this->datagrid_columns = $datagrid_columns;
        $this->form_title = $form_title;
        $this->filters = $filters;
        $this->type_relat = $type_relat;
        $this->position = $position;
        $this->widths = $widths;
        $this->totals = $totals;
        $this->order = $order;

        $this->onGenerate( $type_relat );

    }


    public function onGenerate($format)
    {
        try
        {
            $filters = $this->filters;
            // open a transaction with database 'small_erp'
            TTransaction::open($this->database);
            $param = [];

            if ($this->order) {
                $param['order'] = $this->order['order'];
                if (isset($this->order['direction'])) {
                    $param['direction'] = $this->order['direction'];
                }
            }
            // creates a repository for Pessoa
            $repository = new TRepository( $this->active_record );
            // creates a criteria
            $criteria = new TCriteria;

            $criteria->setProperties($param);

            $filtragem = null;
            if ($filters)
            {
                foreach ($filters as $filter)
                {
                    $criteria->add($filter);
                }
                $filtragem = $criteria->dump();
            }

            // load the objects according to criteria
            $objects = $repository->load($criteria, FALSE);

            if ($objects)
            {
                $colunas_datagrid = $this->datagrid_columns;

                if($this->widths){
                    $widths = $this->widths;
                } else {
                    $widths = array_fill(0,count($colunas_datagrid),200); // Largura das colunas no relatório :: Pegar da Listagem
                }

                if($this->totals){
                    $totals = $this->totals;
                } else {
                    $totals = array_fill(0,count($colunas_datagrid),false); // Largura das colunas no relatório :: Pegar da Listagem
                }

                $totals_sum = array_fill(0,count($colunas_datagrid),0);

                switch ($format)
                {
                    case 'html':
                        // $tr = new TTableWriterHTML($widths);
                        $tr = new TTableWriterHTMLSis($widths);
                        break;
                    case 'xls':
                        $tr = new TTableWriterXLSSis($widths);
                        break;
                    case 'pdf':
                        // $tr = new TTableWriterPDFSis($widths, 'L');
                        $tr = new TTableWriterPDFSis($widths, $this->position);
                        break;
                    case 'rtf':
                        if (!class_exists('PHPRtfLite_Autoloader'))
                        {
                            PHPRtfLite::registerAutoloader();
                        }
                        $tr = new TTableWriterRTFSis($widths, $this->position);
                        break;
                }

                if (!empty($tr))
                {
                    // create the document styles
                    $tr->addStyle('title', 'Helvetica', '10', 'B',   '#000000', '#dbdbdb');
                    $tr->addStyle('filter', 'Arial', '6', '',    '#808080', '#f0f0f0');
                    $tr->addStyle('datap', 'Arial', '10', '',    '#333333', '#f0f0f0');
                    $tr->addStyle('datai', 'Arial', '10', '',    '#333333', '#ffffff');
                    $tr->addStyle('header', 'Helvetica', '16', 'B',   '#5a5a5a', '#ffffff');
                    $tr->addStyle('footer', 'Helvetica', '10', 'B',  '#5a5a5a', '#ffffff');
                    $tr->addStyle('break', 'Helvetica', '10', 'B',  '#ffffff', '#9a9a9a');
                    $tr->addStyle('total', 'Helvetica', '10', 'I',  '#000000', '#c7c7c7');
                    $tr->addStyle('total_final', 'Helvetica', '10', 'BI',  '#000000', '#c7c7c7');
                    $tr->addStyle('breakTotal', 'Helvetica', '10', 'I',  '#000000', '#c6c8d0');

                    // $report_title = $title_form_current;
                    $report_title = $this->form_title;
                    $report_title .= isset($filtragem) ? '<|>(' . $filtragem . ')': '';

                    // Quando o relatório a ser gerado for PDF
                    if ($format == 'pdf')
                    {
                        // Adiciona o cabeçalho
                        $tr->setHeaderCallback(
                            function($tr)
                            {
                                $pdf = $tr->getNativeWriter();

                                // Define a fonte/ estilos
                                $pdf->SetFont('Arial','B',15);

                                // Define o posicionamento do texto
                                $pdf->Cell(80);

                                if (strpos( $tr->arg1, '<|>' ) !== false ) {
                                    $parts = explode( '<|>', $tr->arg1 );
                                    $title = $parts[0];
                                    $filter = $parts[1];
                                } else {
                                    $title = $tr->arg1;
                                    $filter = null;
                                }

                                // Texto do cabeçalho
                                $pdf->Cell(0,10, utf8_decode( $title ) ,0,0,'C');

                                if ($filter) {
                                    $pdf->Ln(20);
                                    $pdf->SetFont('Arial','I',6);
                                    $pdf->Cell(0,10, 'filtragem: ' . utf8_decode( $filter ) ,0,0,'R');
                                }

                                // Line break
                                $pdf->Ln(20);

                            }
                            , $report_title, $widths
                        );

                        // Adiciona o footer do relatório
                        $tr->setFooterCallback(
                            function($tr)
                            {
                                $pdf = $tr->getNativeWriter();

                                // Necessário para obter o número total de páginas
                                $pdf->AliasNbPages();

                                // Posiciona o footer no final da página
                                $pdf->SetY(-40);

                                // Define o estilho do footer
                                $pdf->SetFont('Arial'   ,'B',12);
                                $pdf->Cell(110);

                                // Obtém o número da página atual
                                $numero = $pdf->PageNo();

                                // Footer
                                $pdf->Cell(0,10, utf8_decode("Página: {$numero}/{nb}") ,0,0,'R');

                                // Line break
                                $pdf->Ln(20);
                            }
                        );
                    }
                    else // Para os demais formatos
                    {
                         $tr->setHeaderCallback(
                            function($tr)
                            {

                                if (strpos( $tr->arg1, '<|>' ) !== false ) {
                                    $parts = explode( '<|>', $tr->arg1 );
                                    $title = $parts[0];
                                    $filter = $parts[1];
                                } else {
                                    $title = $tr->arg1;
                                    $filter = null;
                                }
                                $tr->addRow();
                                $tr->addCell($title, 'center', 'header', count($tr->arg2));
                                if ($filter) {
                                    $tr->addRow();
                                    $tr->addCell($filter, 'right', 'filter', count($tr->arg2));
                                }

                            }
                            , $report_title, $widths
                        );

                        $tr->setFooterCallback(
                            function($tr)
                            {
                                $tr->addRow();
                                $tr->addCell(date('Y-m-d h:i:s'), 'right', 'footer', count($tr->arg2));
                            }
                            , $report_title, $widths
                        );
                    }


                    // add titles row
                    $tr->addRow();

                    foreach ($colunas_datagrid as $key => $coluna) {
                        // Pegando o nome da coluna
                        $reflectionProperty = new \ReflectionProperty(TDataGridColumn::class, 'label');
                        $reflectionProperty->setAccessible(true);
                        // Once the property is made accessible, you can read it..
                        $label_column = $reflectionProperty->getValue($coluna);

                        if ($format == 'pdf' || $format == 'rtf') {
                            $label_column = filter_var( $label_column, FILTER_SANITIZE_STRING );
                        }

                        $tr->addCell($label_column, 'left', 'title');
                    }

                    $grandTotal = [];
                    $breakTotal = [];
                    $breakValue = null;
                    $firstRow = true;

                    // controls the background filling
                    $colour = false;

                    // Iterate with objects returned by TRepository
                    foreach ($objects as $object)
                    {
                        $style = $colour ? 'datap' : 'datai';

                        $firstRow = false;

                        $tr->addRow();

                        foreach ($colunas_datagrid as $key => $coluna) {
                            // Pegando o nome da coluna
                            $reflectionProperty = new \ReflectionProperty(TDataGridColumn::class, 'name');
                            $reflectionProperty->setAccessible(true);
                            // Once the property is made accessible, you can read it..
                            $name_column = $reflectionProperty->getValue($coluna);

                            // Pegando o alinhamento da coluna
                            $reflectionProperty = new \ReflectionProperty(TDataGridColumn::class, 'align');
                            $reflectionProperty->setAccessible(true);
                            // Once the property is made accessible, you can read it..
                            $align_column = $reflectionProperty->getValue($coluna);

                            $transformer = $coluna->getTransformer();

                            $content = $object->$name_column;

                            // Check if anable SUM this column
                            if ($totals[ $key ]) {
                                if ($transformer) {
                                    $value_process = call_user_func($transformer, $content, $object, null);
                                    $totals_sum[ $key ] += gf::getNumberFromText( $value_process, true );
                                } else {
                                    $totals_sum[ $key ] += $content;
                                }
                            }

                            if ($transformer)
                            {
                                // apply the transformer functions over the data
                                // $content = call_user_func($transformer, $content, null, null);
                                $content = call_user_func($transformer, $content, $object, null);
                            }

                            if ($format == 'pdf' || $format == 'rtf') {
                                $content = filter_var( $content, FILTER_SANITIZE_STRING );
                            }

                            $tr->addCell($content, $align_column, $style);
                        }

                        $colour = !$colour;
                    }

                    if ($totals) {

                        $tr->addRow();

                        foreach ($colunas_datagrid as $key => $coluna) {

                            if ($totals[ $key ]) {
                                $tr->addCell( gf::numeroBR( $totals_sum[ $key ] ) , 'right', 'total_final');
                            } else {
                                $tr->addCell('', 'center', 'total');
                            }
                        }
                    }



                    $file = 'report_'.uniqid().".{$format}";
                    // stores the file
                    if (!file_exists("app/output/{$file}") || is_writable("app/output/{$file}"))
                    {
                        $tr->save("app/output/{$file}");
                    }
                    else
                    {
                        throw new Exception(_t('Permission denied') . ': ' . "app/output/{$file}");
                    }

                    TPage::openFile("app/output/{$file}");

                    // shows the success message
                    new TMessage('info', _t('Report generated. Please, enable popups'));
                }
            }
            else
            {
                new TMessage('error', _t('No records found'));
            }

            // close the transaction
            TTransaction::close();
        }
        catch (Exception $e) // in case of exception
        {
            // shows the exception error message
            new TMessage('error', $e->getMessage());
            // undo all pending operations
            TTransaction::rollback();
        }
    }

}
