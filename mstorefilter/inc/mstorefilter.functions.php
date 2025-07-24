<?php
/**
 * Mstore Filter Plugin: Core Functions
 *
 * @package MstoreFilter
 * @copyright (c) webitproff
 * @license BSD
 */

defined('COT_CODE') or die('Wrong URL');
// Requirements
require_once cot_langfile('mstorefilter', 'plug');
require_once cot_incfile('mstore', 'module');

function mstorefilter_log($message, $file = 'mstorefilter.log')
{
	global $cfg;

	$log_dir = $cfg['plugins_dir'] . '/mstorefilter/logs';
	if (!is_dir($log_dir)) {
		mkdir($log_dir, 0777, true);
	}

	$log_file = $log_dir . '/' . $file;

	$datetime = date('Y-m-d H:i:s');
	//$ip = $_SERVER['REMOTE_ADDR'] ?? 'CLI';
	$log_entry = "[$datetime] - $message\n";

	file_put_contents($log_file, $log_entry, FILE_APPEND);
}

function mstorefilter_format_param_value($param_type, $values, $param_name = '')
{
    if (empty($values)) {
        return '';
    }
    switch ($param_type) {
        case 'range':
            if (!empty($values[0]) && strpos($values[0], '-') !== false) {
                [$min, $max] = explode('-', $values[0]);
                return "$min - $max" . ($param_name === 'power_motor' ? ' Вт' : '');
            }
            break;
        case 'checkbox':
            return implode(', ', $values);
        case 'select':
        case 'radio':
            return !empty($values[0]) ? $values[0] : '';
        default:
            return !empty($values[0]) ? $values[0] : '';
    }
    return '';
}

function mstorefilter_form_fields(array $values): string
{
    global $L;
    $types = [
        'range' => $L['mstorefilter_range'],
        'select' => $L['mstorefilter_select'],
        'checkbox' => $L['mstorefilter_checkbox'],
        'radio' => $L['mstorefilter_radio'],
    ];

    $html = '';

    // param_name
    $html .= '<div class="mb-3">';
    $html .= '<label for="param_name" class="form-label">' . $L['mstorefilter_param_name'] . '</label>';
    $html .= '<input type="text" id="param_name" name="param_name" class="form-control" value="' . htmlspecialchars($values['param_name'] ?? '') . '" required>';
    $html .= '</div>';

    // param_title
    $html .= '<div class="mb-3">';
    $html .= '<label for="param_title" class="form-label">' . $L['mstorefilter_param_title'] . '</label>';
    $html .= '<input type="text" id="param_title" name="param_title" class="form-control" value="' . htmlspecialchars($values['param_title'] ?? '') . '" required>';
    $html .= '</div>';

    // param_type
    $html .= '<div class="mb-3">';
    $html .= '<label for="param_type" class="form-label">' . $L['mstorefilter_param_type'] . '</label>';
    $html .= '<select id="param_type" name="param_type" class="form-select">';
    foreach ($types as $key => $label) {
        $selected = (isset($values['param_type']) && $values['param_type'] === $key) ? ' selected' : '';
        $html .= '<option value="' . $key . '"' . $selected . '>' . $label . '</option>';
    }
    $html .= '</select>';
    $html .= '</div>';

    // param_values
    $html .= '<div class="mb-3">';
    $html .= '<label for="param_values" class="form-label">' . $L['mstorefilter_param_values'] . '</label>';
    $html .= '<textarea id="param_values" name="param_values" class="form-control" rows="3" required>' . htmlspecialchars($values['param_values'] ?? '') . '</textarea>';
    $html .= '<label for="param_values" class="form-label">' . $L['mstorefilter_param_values_hint'] . '</label>';
    $html .= '</div>';

    // param_active
    $checked = !empty($values['param_active']) ? ' checked' : '';
    $html .= '<div class="form-check mb-3">';
    $html .= '<input type="checkbox" id="param_active" name="param_active" class="form-check-input" value="1"' . $checked . '>';
    $html .= '<label for="param_active" class="form-check-label">Active</label>';
    $html .= '</div>';

    return $html;
}


/**
 * Загрузка параметров из БД
 */
function mstorefilter_load_item_params($msitem_id)
{
    global $db, $db_x;

    $db_mstorefilter_params = $db_x . 'mstorefilter_params';
    $db_mstorefilter_params_values = $db_x . 'mstorefilter_params_values';

    $sql = $db->query("SELECT v.param_id, p.param_name, p.param_type, v.param_value, p.param_values
        FROM $db_mstorefilter_params_values v
        INNER JOIN $db_mstorefilter_params p ON p.param_id = v.param_id
        WHERE v.msitem_id = ?", [$msitem_id]);

    $result = [];

    foreach ($sql->fetchAll() as $row) {
        $name = $row['param_name'];
        $type = $row['param_type'];
        $val = $row['param_value'];

        if ($type === 'checkbox') {
            $result[$name][] = $val;
        } elseif ($type === 'range') {
            [$min, $max] = explode('-', $val);
            $result[$name]['min'] = (int)$min;
            $result[$name]['max'] = (int)$max;
        } else {
            $result[$name] = $val;
        }
    }

    mstorefilter_log("Загружены параметры из БД для msitem_id=$msitem_id: " . print_r($result, true));
    return $result;
}

/**
 * Sanitizes price input to ensure valid float value
 *
 * @param string $price
 * @return float
 */
function cot_mstore_sanitize_price($price)
{
    $price = str_replace(',', '.', $price);
    return (float)preg_replace('/[^0-9.]/', '', $price);
}

/**
 * Gets max price from mstore items
 *
 * @return float
 */
function cot_mstore_filter_maxprice()
{
    global $db, $db_mstore;
    $max = $db->query("SELECT MAX(msitem_cost) FROM $db_mstore")->fetchColumn();
    return $max ? ceil($max) : 1000000;
}



