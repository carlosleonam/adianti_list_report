<?php

        /**
         * Botôes para Impressão da TDataGrid
         */

        // header actions
        $dropdown = new TDropDown('Exportar/Imprimir', 'fa:list');
        $dropdown->setPullSide('right');
        $dropdown->setButtonClass('btn btn-default waves-effect dropdown-toggle');
        $dropdown->addAction( 'HTML', new TAction([$this, 'onGenerateHtml'], ['register_state' => 'false', 'static'=>'1']), 'fa:table #69aa46' );
        $dropdown->addAction( 'PDF', new TAction([$this, 'onGeneratePdf'], ['register_state' => 'false', 'static'=>'1']), 'far:file-pdf #d44734' );
        $dropdown->addAction( 'RTF(doc)', new TAction([$this, 'onGenerateRtf'], ['register_state' => 'false', 'static'=>'1']), 'far:file-word #324bcc' );
        $dropdown->addAction( 'XLS', new TAction([$this, 'onGenerateXls'], ['register_state' => 'false', 'static'=>'1']), 'far:file-excel #cc7932' );
        $panel->addHeaderWidget( $dropdown );
