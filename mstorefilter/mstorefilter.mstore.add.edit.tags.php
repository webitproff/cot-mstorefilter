<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=mstore.add.tags,mstore.edit.tags
Tags=mstore.add.tpl:{MSTORE_FORM_FILTER_PARAMS};mstore.edit.tpl:{MSTORE_FORM_FILTER_PARAMS}
[END_COT_EXT]
==================== */

defined('COT_CODE') or die('Wrong URL');

require_once cot_incfile('forms');
require_once cot_incfile('mstore', 'module');

global $db, $db_x, $t, $r;

if (!$t) {
    return;
}

$db_mstorefilter_params = $db_x . 'mstorefilter_params';
$db_mstorefilter_params_values = $db_x . 'mstorefilter_params_values';

// Получаем все активные параметры фильтра
$params = $db->query("SELECT * FROM $db_mstorefilter_params WHERE param_active = 1")->fetchAll();

$filter_params_html = '';
$item_id = cot_import('id', 'G', 'INT'); // не трогать

// Загружаем все сохранённые значения параметров сразу, сгруппированные по param_id
$saved_all = [];
if ($item_id > 0) {
    $rows = $db->query("SELECT param_id, param_value FROM $db_mstorefilter_params_values WHERE msitem_id = ?", [$item_id])->fetchAll();
    foreach ($rows as $row) {
        $saved_all[(int)$row['param_id']][] = $row['param_value'];
    }
}

foreach ($params as $param) {
    $param_id = (int)$param['param_id'];
    $param_name = $param['param_name'];
    $param_title = $param['param_title'];
    $param_type = $param['param_type'];

    // Параметры значений фильтра — json, например ["red","green","blue"] или {"min":0,"max":100}
    $param_values = json_decode($param['param_values'], true);

    // Проверка JSON
    if (json_last_error() !== JSON_ERROR_NONE) {
        cot_error("Неверный JSON в параметре '{$param_name}': " . json_last_error_msg());
        continue;
    }

    // Получаем сохранённые значения для данного параметра и товара
    $saved_values = $saved_all[$param_id] ?? [];

    $input = '';

    switch ($param_type) {
        case 'range':
            $min_val = '';
            $max_val = '';

            // Обычно значение сохраняется как строка "мин-мах"
            if (!empty($saved_values[0]) && strpos($saved_values[0], '-') !== false) {
                [$min_val, $max_val] = explode('-', $saved_values[0]);
            }

            $min_placeholder = isset($param_values['min']) ? (int)$param_values['min'] : '';
            $max_placeholder = isset($param_values['max']) ? (int)$param_values['max'] : '';

            $input = '
                <div class="row g-2">
                    <div class="col">
                        <input type="number" name="mstorefilter[' . htmlspecialchars($param_name) . '][min]" value="' . htmlspecialchars($min_val) . '" class="form-control" placeholder="от ' . $min_placeholder . '">
                    </div>
                    <div class="col">
                        <input type="number" name="mstorefilter[' . htmlspecialchars($param_name) . '][max]" value="' . htmlspecialchars($max_val) . '" class="form-control" placeholder="до ' . $max_placeholder . '">
                    </div>
                </div>';
            break;

        case 'select':
            $selected = $saved_values[0] ?? '';

            // Убедимся, что $param_values — массив опций
            if (!is_array($param_values)) {
                $param_values = [];
            }

            $input = cot_selectbox(
                $selected,
                "mstorefilter[" . htmlspecialchars($param_name) . "]",
                $param_values,
                $param_values,
                true,
                ['class' => 'form-select']
            );
            break;

        case 'checkbox':
            // Убедимся, что $param_values — массив опций
            if (!is_array($param_values)) {
                $param_values = [];
            }

            // Сохраняемые значения checkbox — массив
            $saved_values_arr = $saved_values;

            // Защита на случай, если saved_values — не массив
            if (!is_array($saved_values_arr)) {
                $saved_values_arr = [$saved_values_arr];
            }

            $input = cot_checklistbox(
                $saved_values_arr,
                "mstorefilter[" . htmlspecialchars($param_name) . "][]",
                $param_values,
                $param_values,
                ['class' => 'form-check-input']
            );
            break;

        case 'radio':
            $selected = $saved_values[0] ?? '';

            // Убедимся, что $param_values — массив опций
            if (!is_array($param_values)) {
                $param_values = [];
            }

            $input = cot_radiobox(
                $selected,
                "mstorefilter[" . htmlspecialchars($param_name) . "]",
                $param_values,
                $param_values,
                ['class' => 'form-check-input']
            );
            break;

        default:
            $value = $saved_values[0] ?? '';
            $input = '<input type="text" name="mstorefilter[' . htmlspecialchars($param_name) . ']" value="' . htmlspecialchars($value) . '" class="form-control">';
            break;
    }

    $filter_params_html .= '
        <div class="mb-3">
            <label class="form-label" for="param_' . htmlspecialchars($param_name) . '">' . htmlspecialchars($param_title) . '</label>
            ' . $input . '
        </div>';
}

$t->assign('MSTORE_FORM_FILTER_PARAMS', $filter_params_html);