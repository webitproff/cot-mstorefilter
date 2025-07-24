<?php
/**
 * [BEGIN_COT_EXT]
 * Hooks=mstore.list.query
 * [END_COT_EXT]
 */
defined('COT_CODE') or die('Wrong URL');

require_once cot_incfile('mstore', 'module');
require_once cot_incfile('mstorefilter', 'plug', 'functions');

global $db, $db_x, $join_condition, $where, $group_by;

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
        $value = isset($_GET[$filter_key]) ? (float)trim($_GET[$filter_key]) : 0;
        if ($value === 0.0) {
            mstorefilter_log("Skipped range filter: $param_name (value: $value)");
            continue;
        }
        $filter_conditions[] = "(fpv.param_id = $param_id AND 
            CAST(SUBSTRING_INDEX(fpv.param_value, '-', -1) AS UNSIGNED) <= $value)";
        mstorefilter_log("Applied range: $param_name <= $value");
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

// Проверяем, применены ли фильтры или нажата кнопка поиска
$is_search_clicked = isset($_GET['search']);
$is_filter_applied = !empty($filter_conditions);

if ($is_search_clicked && !$is_filter_applied) {
    // Если кнопка поиска нажата, но фильтры не выбраны
    $L['mstorefilter_message'] = 'Вы не выбрали параметры для фильтра';
    $L['mstorefilter_message_class'] = 'alert-info';
    mstorefilter_log("Search clicked but no filter parameters selected");
} elseif ($is_filter_applied) {
    // Применяем фильтры с INNER JOIN и OR для условий
    $join_condition .= " INNER JOIN $db_mstorefilter_params_values AS fpv ON fpv.msitem_id = p.msitem_id ";
    $filter_sql = implode(' OR ', $filter_conditions);

    if (!isset($where) || !is_array($where)) {
        $where = [];
    }
    $where[] = "($filter_sql)";

    mstorefilter_log("Filter applied with " . count($filter_conditions) . " conditions");

    // Подсчитываем количество уникальных товаров
    $sql_count = "SELECT COUNT(DISTINCT p.msitem_id) AS total_items 
                  FROM {$db_x}mstore AS p 
                  $join_condition 
                  WHERE " . implode(' AND ', $where);
    $total_items = $db->query($sql_count)->fetchColumn();
    mstorefilter_log("Found $total_items items after filtering");

    // Формируем сообщение о результатах фильтрации
    $L['mstorefilter_message'] = $total_items > 0 
        ? "Найдено $total_items товаров по вашим параметрам" 
        : "Товаров не найдено по вашим параметрам";
    $L['mstorefilter_message_class'] = $total_items > 0 ? 'alert-success' : 'alert-warning';
	$group_by = 'p.msitem_id';
} else {
    // Если фильтры не применены и поиск не начат, не показываем сообщение
    $L['mstorefilter_message'] = '';
    $L['mstorefilter_message_class'] = '';
    mstorefilter_log("No filter conditions applied");
}
?>