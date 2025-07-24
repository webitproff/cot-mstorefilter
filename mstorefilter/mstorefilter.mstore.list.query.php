<?php
/**
 * [BEGIN_COT_EXT]
 * Hooks=mstore.list.query
 * [END_COT_EXT]
 */
defined('COT_CODE') or die('Wrong URL');

require_once cot_incfile('mstore', 'module');
require_once cot_incfile('mstorefilter', 'plug', 'functions');

global $db, $db_x, $join_condition, $where;

$db_mstorefilter_params = $db_x . 'mstorefilter_params';
$db_mstorefilter_params_values = $db_x . 'mstorefilter_params_values';

mstorefilter_log("mstorefilter.mstore.list.query.php started");

// Получаем активные параметры фильтра из базы
$filter_params = $db->query("SELECT * FROM $db_mstorefilter_params WHERE param_active = 1 ORDER BY param_id ASC")->fetchAll();

$filter_conditions = [];

foreach ($filter_params as $param) {
    $param_id = (int)$param['param_id'];
    $param_name = $param['param_name'];
    $param_type = $param['param_type'];

    $filter_key = "filter_$param_name";

    if (!isset($_GET[$filter_key]) || $_GET[$filter_key] === '') {
        continue;
    }

    if ($param_type === 'range') {
        $range = explode(',', $_GET[$filter_key]);
        if (count($range) === 2) {
            $min = (float)trim($range[0]);
            $max = (float)trim($range[1]);

            $filter_conditions[] = "(fpv.param_id = $param_id AND 
                CAST(SUBSTRING_INDEX(fpv.param_value, '-', 1) AS UNSIGNED) <= $max AND 
                CAST(SUBSTRING_INDEX(fpv.param_value, '-', -1) AS UNSIGNED) >= $min)";
            
            mstorefilter_log("Applied range: $param_name = $min-$max");
        }
    } elseif ($param_type === 'checkbox') {
        $values = $_GET[$filter_key];
        if (is_array($values)) {
            $escaped = array_map([$db, 'quote'], $values);
            $in = implode(',', $escaped);
            $filter_conditions[] = "(fpv.param_id = $param_id AND fpv.param_value IN ($in))";
            mstorefilter_log("Applied checkbox: $param_name = " . implode(', ', $values));
        }
    } else { // select или radio
        $value = trim($_GET[$filter_key]);
        if ($value !== '') {
            $escaped = $db->quote($value);
            $filter_conditions[] = "(fpv.param_id = $param_id AND fpv.param_value = $escaped)";
            mstorefilter_log("Applied $param_type: $param_name = $value");
        }
    }
}

if (!empty($filter_conditions)) {
    // Добавляем JOIN для связи товаров с параметрами фильтра
    $join_condition .= " INNER JOIN $db_mstorefilter_params_values AS fpv ON fpv.msitem_id = p.msitem_id ";

    // Собираем условия фильтра через OR
    $filter_sql = implode(' OR ', $filter_conditions);

    if (!isset($where) || !is_array($where)) {
        $where = [];
    }
    $where[] = "($filter_sql)";

    mstorefilter_log("Filter applied with " . count($filter_conditions) . " conditions");
} else {
    mstorefilter_log("No filter conditions applied");
}
