<?php
/*
 * Created Date: Tuesday, October 22nd 2019, 7:48:20 am
 * Author: Leonam, Carlos
 *
 * Copyright (c) 2019 SisSoftwaresWEB
 */

TScript::create("
var arrOfTable = [],
    i = 0;
$('[id^=\"tdatagrid_\"] tr:nth-child(1) td').each(function () {
    mWid = $(this).width();
    arrOfTable.push(mWid);
});
var width_cols = escape(arrOfTable.join(','));
document.cookie=\"profile_tdatagrid_". self::$formName ."_col_width = \" + width_cols;
");
