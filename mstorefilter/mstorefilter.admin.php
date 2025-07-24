<?php
/**
 * [BEGIN_COT_EXT]
 * Hooks=tools
 * [END_COT_EXT]
 */

defined('COT_CODE') or die('Wrong URL');

require_once cot_incfile('mstore', 'module');
require_once cot_incfile('forms');

global $db, $db_x, $L;
cot_block(Cot::$usr['isadmin']);
$adminHelp = Cot::$L['mstore_help'];
$db_mstorefilter_params = $db_x . 'mstorefilter_params';

$action = cot_import('a', 'G', 'ALP');
$param_id = cot_import('id', 'G', 'INT');

$t = new XTemplate(cot_tplfile('mstorefilter.admin', 'plug'));

$form_values = [
    'param_id' => '',
    'param_name' => '',
    'param_title' => '',
    'param_type' => 'range',
    'param_values' => '{"min":0,"max":100}',
    'param_active' => 1,
];

$edit_mode = false;

// Импорт значения param_id как можно раньше (важно!)
$param_id = cot_import('id', 'G', 'INT');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $param_id = cot_import('id', 'P', 'INT');
}

// Обработка формы добавления/редактирования
if ($_SERVER['REQUEST_METHOD'] === 'POST' && in_array($action, ['add', 'edit'])) {
    $param_name = cot_import('param_name', 'P', 'ALP');
    $param_title = cot_import('param_title', 'P', 'TXT');
    $param_type = cot_import('param_type', 'P', 'ALP');
    $param_values = cot_import('param_values', 'P', 'TXT');
    $param_active = cot_import('param_active', 'P', 'BOL') ? 1 : 0;

    if ($param_name && $param_title && in_array($param_type, ['range', 'select', 'checkbox', 'radio'])) {
        $values_decoded = json_decode($param_values, true);

        if ($param_type === 'range' && (!isset($values_decoded['min']) || !isset($values_decoded['max']))) {
            cot_error('Для типа range нужны min и max');
        } elseif (in_array($param_type, ['select', 'checkbox', 'radio']) && !is_array($values_decoded)) {
            cot_error('Некорректные значения для типа select, checkbox или radio');
        } else {
            $data = [
                'param_name' => $param_name,
                'param_title' => $param_title,
                'param_type' => $param_type,
                'param_values' => $param_values,
                'param_active' => $param_active,
            ];

            if ($action === 'add') {
                $db->insert($db_mstorefilter_params, $data);
                cot_message('Параметр добавлен');
            } elseif ($action === 'edit' && $param_id > 0) {
                $db->update($db_mstorefilter_params, $data, 'param_id = ?', [$param_id]);
                cot_message('Параметр обновлён');
            }

            cot_redirect(cot_url('admin', 'm=other&p=mstorefilter', '', true));
            exit;
        }
    } else {
        cot_error('Заполните все поля корректно');
    }
}

// Обработка удаления
if ($action === 'delete' && $param_id > 0) {
    $db->delete($db_mstorefilter_params, 'param_id = ?', [$param_id]);
    cot_message('Параметр удалён');
    cot_redirect(cot_url('admin', 'm=other&p=mstorefilter', '', true));
    exit;
}

// Получаем данные для редактирования
if ($action === 'edit' && $param_id > 0) {
    $row = $db->query("SELECT * FROM $db_mstorefilter_params WHERE param_id = ?", [$param_id])->fetch();
    if ($row) {
        $form_values = $row;
        $edit_mode = true;
    }
}

// Получаем список параметров
$parameters = $db->query("SELECT * FROM $db_mstorefilter_params ORDER BY param_id DESC")->fetchAll();

// Присваиваем переменные в шаблон
$t->assign([
    'FORM_ACTION' => $edit_mode
        ? cot_url('admin', 'm=other&p=mstorefilter&a=edit&id=' . $form_values['param_id'])
        : cot_url('admin', 'm=other&p=mstorefilter&a=add'),
    'FORM_FIELDS' => mstorefilter_form_fields($form_values),
    'CANCEL_URL' => cot_url('admin', 'm=other&p=mstorefilter'),
    'EDIT_MODE' => $edit_mode,
    'FORM_PARAM_ID' => $edit_mode ? $form_values['param_id'] : '',
]);

// Пробегаемся по параметрам для таблицы
foreach ($parameters as $param) {
    $t->assign([
        'PARAM_ID' => $param['param_id'],
        'PARAM_NAME' => htmlspecialchars($param['param_name']),
        'PARAM_TITLE' => htmlspecialchars($param['param_title']),
        'PARAM_TYPE' => $param['param_type'],
        'PARAM_VALUES' => htmlspecialchars($param['param_values']),
        'PARAM_ACTIVE' => $param['param_active'] ? $L['Yes'] : $L['No'],
        'PARAM_EDIT_URL' => cot_url('admin', 'm=other&p=mstorefilter&a=edit&id=' . $param['param_id']),
        'PARAM_DELETE_URL' => cot_confirm_url(cot_url('admin', 'm=other&p=mstorefilter&a=delete&id=' . $param['param_id'])),
    ]);
    $t->parse('MAIN.PARAM_ROW');
}

cot_display_messages($t);

if ($edit_mode) {
    $t->parse('MAIN.EDIT_FORM');
} else {
    $t->parse('MAIN.ADD_FORM');
}

$t->parse('MAIN');
$adminMain = $t->text('MAIN');