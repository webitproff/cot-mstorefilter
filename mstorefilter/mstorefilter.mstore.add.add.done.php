<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=mstore.add.add.done,mstore.edit.update.done
[END_COT_EXT]
==================== */

defined('COT_CODE') or die('Wrong URL');

require_once cot_incfile('mstore', 'module');
require_once cot_incfile('mstorefilter', 'plug', 'functions');

global $db, $db_x;

$db_mstorefilter_params = $db_x . 'mstorefilter_params';
$db_mstorefilter_params_values = $db_x . 'mstorefilter_params_values';

$msitem_id = (int)cot_import('id', 'G', 'INT');
if (!$msitem_id && !empty($r['msitem_id'])) {
    $msitem_id = (int)$r['msitem_id'];
}
if (!$msitem_id && !empty($item['msitem_id'])) {
    $msitem_id = (int)$item['msitem_id'];
}

function flatten_checkbox_values($values) {
    $flat = [];
    foreach ($values as $v) {
        if (is_array($v)) {
            $flat = array_merge($flat, flatten_checkbox_values($v));
        } else {
            if ($v !== 'nullval' && $v !== '' && $v !== null) {
                $flat[] = $v;
            }
        }
    }
    return $flat;
}

mstorefilter_log('Начало сохранения параметров. msitem_id=' . $msitem_id);
mstorefilter_log('POST mstorefilter=' . print_r($_POST['mstorefilter'], true));

if ($msitem_id > 0 && isset($_POST['mstorefilter']) && is_array($_POST['mstorefilter'])) {
    $db->delete($db_mstorefilter_params_values, "msitem_id = ?", [$msitem_id]);
    mstorefilter_log("Удалены старые параметры для msitem_id=$msitem_id");

    $params = $db->query("SELECT param_id, param_name, param_type, param_values FROM $db_mstorefilter_params WHERE param_active = 1")->fetchAll();
    mstorefilter_log("Получено " . count($params) . " активных параметров");

    foreach ($params as $param) {
        $param_id = (int)$param['param_id'];
        $param_name = $param['param_name'];
        $param_type = $param['param_type'];
        $param_values_raw = $param['param_values'];

        if (!isset($_POST['mstorefilter'][$param_name]) || empty($_POST['mstorefilter'][$param_name])) {
            mstorefilter_log("Пропущен параметр $param_name (нет в POST)");
            continue;
        }

        $param_value = $_POST['mstorefilter'][$param_name];
        mstorefilter_log("Обработка $param_name ($param_type) = " . print_r($param_value, true));

        if ($param_type === 'range') {
            $min = isset($param_value['min']) && $param_value['min'] !== '' ? (int)$param_value['min'] : null;
            $max = isset($param_value['max']) && $param_value['max'] !== '' ? (int)$param_value['max'] : null;
            if (($min !== null && $min >= 0) || ($max !== null && $max >= 0)) {
                $range_val = ($min !== null ? $min : '') . '-' . ($max !== null ? $max : '');
                $db->insert($db_mstorefilter_params_values, [
                    'msitem_id' => $msitem_id,
                    'param_id' => $param_id,
                    'param_value' => $range_val
                ]);
                mstorefilter_log("Сохранён range $param_name: $range_val");
            } else {
                mstorefilter_log("Неверный диапазон для $param_name: min=$min max=$max");
            }
        } elseif ($param_type === 'checkbox') {
            if (is_array($param_value)) {
                $valid_values = json_decode($param_values_raw, true);
                if (!is_array($valid_values)) {
                    $valid_values = [];
                }
                $flat_values = flatten_checkbox_values($param_value);
                foreach ($flat_values as $value) {
                    if (is_scalar($value) && in_array($value, $valid_values)) {
                        $db->insert($db_mstorefilter_params_values, [
                            'msitem_id' => $msitem_id,
                            'param_id' => $param_id,
                            'param_value' => (string)$value
                        ]);
                        mstorefilter_log("Сохранён checkbox $param_name: $value");
                    } else {
                        mstorefilter_log("Пропущен checkbox $param_name: $value");
                    }
                }
            } else {
                mstorefilter_log("Ожидался массив для checkbox $param_name");
            }
        } elseif ($param_type === 'select') {
            if (is_scalar($param_value) && !empty($param_value)) {
                $valid_values = json_decode($param_values_raw, true);
                if (!is_array($valid_values)) {
                    $valid_values = [];
                }
                if (in_array($param_value, $valid_values)) {
                    $db->insert($db_mstorefilter_params_values, [
                        'msitem_id' => $msitem_id,
                        'param_id' => $param_id,
                        'param_value' => (string)$param_value
                    ]);
                    mstorefilter_log("Сохранён select $param_name: $param_value");
                } else {
                    mstorefilter_log("Недопустимое значение select $param_name: $param_value");
                }
            } else {
                mstorefilter_log("Ожидалось скалярное значение для select $param_name");
            }
        } elseif ($param_type === 'radio') {
            if (is_scalar($param_value) && !empty($param_value)) {
                $valid_values = json_decode($param_values_raw, true);
                if (!is_array($valid_values)) {
                    $valid_values = [];
                }
                if (in_array($param_value, $valid_values)) {
                    $db->insert($db_mstorefilter_params_values, [
                        'msitem_id' => $msitem_id,
                        'param_id' => $param_id,
                        'param_value' => (string)$param_value
                    ]);
                    mstorefilter_log("Сохранён radio $param_name: $param_value");
                } else {
                    mstorefilter_log("Недопустимое значение radio $param_name: $param_value");
                }
            } else {
                mstorefilter_log("Ожидалось скалярное значение для radio $param_name");
            }
        } else {
            mstorefilter_log("Неизвестный тип параметра $param_name: $param_type");
        }
    }
} else {
    mstorefilter_log('msitem_id <= 0 или mstorefilter не передан');
}
?>