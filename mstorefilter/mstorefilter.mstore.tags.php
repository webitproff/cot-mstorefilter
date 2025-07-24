<?php
/**
 * [BEGIN_COT_EXT]
 * Hooks=mstore.tags
 * Tags=mstore.tpl:{MSTORE_FILTER_PARAMS}
 * [END_COT_EXT]
 */
defined('COT_CODE') or die('Wrong URL');

require_once cot_incfile('mstore', 'module');
require_once cot_incfile('mstorefilter', 'plug', 'functions');

global $db, $db_x, $t, $msitem;

$db_mstorefilter_params = $db_x . 'mstorefilter_params';
$db_mstorefilter_params_values = $db_x . 'mstorefilter_params_values';

if (!$t) {
    mstorefilter_log('Ошибка: шаблон $t не определён');
    return;
}

// Получаем msitem_id
$msitem_id = isset($msitem['msitem_id']) ? (int)$msitem['msitem_id'] : cot_import('id', 'G', 'INT');
mstorefilter_log("msitem_id = $msitem_id");

if ($msitem_id > 0) {
    // Получаем активные параметры
    $params = $db->query("SELECT param_id, param_name, param_title, param_type FROM $db_mstorefilter_params WHERE param_active = 1")->fetchAll();
    mstorefilter_log("Найдено параметров: " . count($params));

    // Получаем сохранённые значения
    $saved_all = [];
    $rows = $db->query("SELECT param_id, param_value FROM $db_mstorefilter_params_values WHERE msitem_id = ?", [$msitem_id])->fetchAll();
    mstorefilter_log("Найдено значений для msitem_id=$msitem_id: " . count($rows));
    foreach ($rows as $row) {
        $saved_all[(int)$row['param_id']][] = $row['param_value'];
    }

    // Формируем массив для шаблона
    $filter_params = [];
    foreach ($params as $param) {
        $param_id = (int)$param['param_id'];
        $param_name = $param['param_name'];
        $param_title = $param['param_title'];
        $param_type = $param['param_type'];
        $saved_values = $saved_all[$param_id] ?? [];

        $formatted_value = mstorefilter_format_param_value($param_type, $saved_values, $param_name);
        mstorefilter_log("Параметр $param_name ($param_type): $formatted_value");

        if ($formatted_value !== '') {
            $filter_params[] = [
                'PARAM_TITLE' => htmlspecialchars($param_title),
                'PARAM_VALUE' => htmlspecialchars($formatted_value)
            ];
        }
    }

    mstorefilter_log("Передано параметров в шаблон: " . count($filter_params));
foreach ($filter_params as $param) {
    $t->assign($param); // присваиваем напрямую PARAM_TITLE и PARAM_VALUE
    $t->parse('MAIN.MSTORE_FILTER_PARAMS');
}
} else {
    mstorefilter_log("Ошибка: msitem_id не определён или равен 0");
}
?>