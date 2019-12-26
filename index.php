<?php
/**
 * Created by PhpStorm.
 * User: Marius
 * Date: 2019-11-28
 * Time: 18:03
 */

require_once('DatesManager.php');
(new DatesManager())->createDates()->writeToCSV();
