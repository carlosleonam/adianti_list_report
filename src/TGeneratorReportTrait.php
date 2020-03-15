<?php


/**
 *
 */
trait TGeneratorReportTrait
{

    public function onGenerateHtml($param = null)
    {
        $result_test = $this->checkHugeQtRows();
        if ($result_test[0]) {
            if (is_string($result_test[1])) {
                AdditionalFunctions::swalert('Alerta!', $result_test[1] ,'error');
            } else {
                AdditionalFunctions::swalert('Excesso!','Mais de 1.000 registros selecionados!','error');
            }
            return false;
        }
        // Array Columns Width
        $array_widths = array_slice( AdditionalFunctions::checkCookieForTDatagrid('profile_tdatagrid_'. self::$formName .'_col_width'), self::$buttons_columns);
        $array_totals = $array_widths;
        $array_totals = array_fill( 0, count( $array_totals ), false );
        foreach (self::$columns_with_total as $column) {
            $array_totals[ $column -self::$buttons_columns ] = true;
        }
        $report_generator = new TGeneratorReport(
            self::$database,
            self::$activeRecord,
            $this->datagrid->getColumns(),
            'Listagem de ' . AdditionalFunctions::getFormName( $this->form ),
            $this->onSearchToSession(),
            'html'
            ,
            null,
            $array_widths,
            $array_totals
        );
    }


    public function onGeneratePdf($param = null)
    {
        $result_test = $this->checkHugeQtRows();
        if ($result_test[0]) {
            if (is_string($result_test[1])) {
                AdditionalFunctions::swalert('Alerta!', $result_test[1] ,'error');
            } else {
                AdditionalFunctions::swalert('Excesso!','Mais de 1.000 registros selecionados!','error');
            }
            return false;
        }
        // Array Columns Width
        $array_widths = array_slice( AdditionalFunctions::checkCookieForTDatagrid('profile_tdatagrid_'. self::$formName .'_col_width'), self::$buttons_columns);
        $array_totals = $array_widths;
        $array_totals = array_fill( 0, count( $array_totals ), false );
        foreach (self::$columns_with_total as $column) {
            $array_totals[ $column -self::$buttons_columns ] = true;
        }
        $report_generator = new TGeneratorReport(
            self::$database,
            self::$activeRecord,
            $this->datagrid->getColumns(),
            'Listagem de ' . AdditionalFunctions::getFormName( $this->form ),
            $this->onSearchToSession(),
            'pdf'
            ,
            'L',
            $array_widths,
            $array_totals
        );
    }


    public function onGenerateRtf($param = null)
    {
        $result_test = $this->checkHugeQtRows();
        if ($result_test[0]) {
            if (is_string($result_test[1])) {
                AdditionalFunctions::swalert('Alerta!', $result_test[1] ,'error');
            } else {
                AdditionalFunctions::swalert('Excesso!','Mais de 1.000 registros selecionados!','error');
            }
            return false;
        }
        // Array Columns Width
        $array_widths = array_slice( AdditionalFunctions::checkCookieForTDatagrid('profile_tdatagrid_'. self::$formName .'_col_width'), self::$buttons_columns);
        $array_totals = $array_widths;
        $array_totals = array_fill( 0, count( $array_totals ), false );
        foreach (self::$columns_with_total as $column) {
            $array_totals[ $column -self::$buttons_columns ] = true;
        }
        $report_generator = new TGeneratorReport(
            self::$database,
            self::$activeRecord,
            $this->datagrid->getColumns(),
            'Listagem de ' . AdditionalFunctions::getFormName( $this->form ),
            $this->onSearchToSession(),
            'rtf'
            ,
            'L',
            $array_widths,
            $array_totals
        );
    }


    public function onGenerateXls($param = null)
    {
        $result_test = $this->checkHugeQtRows();
        if ($result_test[0]) {
            if (is_string($result_test[1])) {
                AdditionalFunctions::swalert('Alerta!', $result_test[1] ,'error');
            } else {
                AdditionalFunctions::swalert('Excesso!','Mais de 1.000 registros selecionados!','error');
            }
            return false;
        }
        // Array Columns Width
        $array_widths = array_slice( AdditionalFunctions::checkCookieForTDatagrid('profile_tdatagrid_'. self::$formName .'_col_width'), self::$buttons_columns);
        $array_totals = $array_widths;
        $array_totals = array_fill( 0, count( $array_totals ), false );
        foreach (self::$columns_with_total as $column) {
            $array_totals[ $column -self::$buttons_columns ] = true;
        }
        $report_generator = new TGeneratorReport(
            self::$database,
            self::$activeRecord,
            $this->datagrid->getColumns(),
            'Listagem de ' . AdditionalFunctions::getFormName( $this->form ),
            $this->onSearchToSession(),
            'xls'
            ,
            'L',
            $array_widths,
            $array_totals
        );
    }


    public function checkHugeQtRows()
    {
        $filters = $this->onSearchToSession();

        if (!$filters) {
            $result = [];
            $result[] = true;
            $result[] = 'Não foi efetuada nenhuma Filtragem. Efetue uma busca antes de tentar imprimir!';
            return $result;
        }

        try
        {
            TTransaction::open(self::$database); // open a transaction

        // creates a repository for Clientes
            $repository = new TRepository(self::$activeRecord);

            // creates a criteria
            $criteria = new TCriteria;

            if($filters = TSession::getValue(__CLASS__.'_filters'))
            {
                foreach ($filters as $filter)
                {
                    $criteria->add($filter);
                }
            }

            // load the objects according to criteria
            $objects_count = $repository->count($criteria); //, FALSE);

            TTransaction::close(); // close the transaction
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            TTransaction::rollback(); // undo all pending operations
        }

        if ($objects_count > 1000) {
            // return true;
            $result = [];
            $result[] = true;
            $result[] = null;
            return $result;
        } else {
            return false;
        }

    }

    public function onSearchToSession()
    {
        return TSession::getValue(__CLASS__.'_filters');
    }


}
