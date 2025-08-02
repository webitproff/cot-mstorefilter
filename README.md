# Mstore Filter Plugin for Cotonti Siena v0.9.26

The **Mstore Filter** plugin provides dynamic filtering capabilities for the [Mstore](https://github.com/webitproff/cot-multistore) module in Cotonti Siena v0.9.26. It allows administrators to define custom filter parameters for products and enables users to filter products based on these parameters, such as custom attributes (e.g., battery capacity, frame type, or color). Price filtering is still in development. The plugin supports multiple filter types (range, select, checkbox, radio) and integrates seamlessly with the Mstore module.
# [Demo](https://multistore.previewit.work/mstore)

<img alt="Dynamic filter parameters for Mstore module for Cotonti v0.9.26" src="https://raw.githubusercontent.com/webitproff/cot-mstorefilter/refs/heads/main/mstorefilter.png">

## Features

### Admin Functionality
- **Parameter Management**: Add, edit, and delete filter parameters via the admin panel.
- **Flexible Parameter Types**: Supports four filter types:
  - **Range**: Numeric ranges (e.g., motor power) with minimum and maximum values.
  - **Select**: Dropdown list for single-option selection.
  - **Checkbox**: Multiple-option selection.
  - **Radio**: Single-option selection from a list.
- **JSON-based Parameter Values**: Define allowed values in JSON format (e.g., `["Red","Green","Blue"]` for select/checkbox/radio, or `{"min":150,"max":10000}` for range).
- **Activation Toggle**: Enable or disable filter parameters without deleting them.
- **Validation**: Ensures unique parameter codes and valid JSON for parameter values.
- **Logging**: Logs all filter-related actions (e.g., parameter saving, errors) to `mstorefilter.log` for debugging.

### User Functionality
- **Dynamic Filter Form**: Displays on the Mstore product list page, allowing users to filter products by active parameters.
- **Filter Types**:
  - **Range**: Interactive slider for selecting a value within a defined range.
  - **Select**: Dropdown menu for choosing one option.
  - **Checkbox**: Checkboxes for multiple selections.
  - **Radio**: Radio buttons for selecting one option.
- **Category Support**: Filters can be applied within specific Mstore categories.
- **Apply and Reset Buttons**: Buttons to apply filters or reset to default values.
- **Product Parameter Display**: Shows parameters on individual product pages (e.g., `Color: Black`, `Wheel Size: 24 inches`) if defined.
- **List Filtering**: Filtering is performed via SQL queries using `INNER JOIN` to link products with filter parameters. Supports conditions for ranges (maximum value), checkboxes (multiple selections), and single values (select/radio). Displays the number of found products or a message if no results are found.

## File Structure

```
mstorefilter/
├── inc/
│   └── mstorefilter.functions.php          # Core functions (logging, form generation, parameter loading, value formatting)
├── lang/
│   ├── mstorefilter.ru.lang.php            # Russian language file for admin and user interfaces
│   └── mstorefilter.en.lang.php            # English language file for admin and user interfaces
├── logs/                                   # Directory for log files (auto-created)
├── setup/
│   ├── mstorefilter.install.sql            # SQL for creating filter-related tables
│   └── mstorefilter.uninstall.sql          # SQL for removing filter-related tables
├── tpl/
│   ├── mstorefilter.admin.tpl              # Template for admin panel interface
│   └── mstorefilter.filterform.tpl         # Template for user-facing filter form
├── mstorefilter.admin.php                  # Admin panel logic for managing filter parameters
├── mstorefilter.global.php                 # Global plugin settings
├── mstorefilter.mstore.list.query.php      # Hook for handling SQL queries for filtering product lists, including dynamic condition generation
├── mstorefilter.mstore.add.add.done.php    # Hook for saving filter parameters on product add/edit
├── mstorefilter.mstore.add.edit.tags.php   # Hook for adding filter parameter fields to product add/edit forms
├── mstorefilter.mstore.tags.php            # Hook for displaying filter parameters on product pages
├── mstorefilter.mstoretags.php             # Hook for rendering the filter form on the product list page
└── mstorefilter.setup.php                  # Plugin configuration file
```

## Installation

1. **Download and Extract**:
   - Download the `cot-mstorefilter` plugin from [https://github.com/webitproff/cot-mstorefilter](https://github.com/webitproff/cot-mstorefilter).
   - Extract the `mstorefilter` folder to the `plugins/` directory of your Cotonti installation (e.g., `plugins/mstorefilter/`).

2. **Install the Plugin**:
   - Log in to the Cotonti admin panel.
   - Navigate to **Administration > Extensions > Plugins**.
   - Find **Mstore Filter** in the list and click **Install**.
   - The plugin will create the necessary database tables (`cot_mstorefilter_params` and `cot_mstorefilter_params_values`) as defined in `mstorefilter.install.sql`.

3. **Verify Dependencies**:
   - Ensure the **Mstore** module is installed and active, as this plugin requires it.

## Integration with Mstore Module

To fully integrate the Mstore Filter plugin with the Mstore module, update the following template files:

1. **mstore.tpl** (Product Detail Page):
   - Add the following code to display filter parameters for individual products:
     ```html
     <!-- IF {PHP|cot_plugin_active('mstorefilter')} -->
     <h3>{PHP.L.mstorefilter_paramsItem}</h3>
     <dl class="row">
         <!-- BEGIN: MSTORE_FILTER_PARAMS -->
         <dt class="col-sm-4">{PARAM_TITLE}</dt>
         <dd class="col-sm-8">{PARAM_VALUE}</dd>
         <!-- END: MSTORE_FILTER_PARAMS -->
     </dl>
     <!-- ENDIF -->
     ```

2. **mstore.list.tpl** (Product List Page):
   - Add the following code to display the filter form:
     ```html
     <!-- IF {PHP|cot_plugin_active('mstorefilter')} -->
     {MSTORE_FILTER_FORM}
     <!-- ENDIF -->
     ```
   - Optionally, add a message block for filter feedback:
     ```html
     <!-- IF {PHP|cot_plugin_active('mstorefilter')} -->
     <div class="alert {MSTOREFILTER_MESSAGE_CLASS}">
         {MSTOREFILTER_MESSAGE}
     </div>
     <!-- ENDIF -->
     ```

3. **mstore.edit.tpl** (Product Edit Page):
   - Add the following code to include filter parameter fields in the product edit form:
     ```html
     <!-- IF {PHP|cot_plugin_active('mstorefilter')} -->
     {MSTORE_FORM_FILTER_PARAMS}
     <!-- ENDIF -->
     ```

## Usage

### Admin Usage
1. **Access the Admin Panel**:
   - Go to **Administration > Other > Mstore Filter**.
2. **Add a Parameter**:
   - Click the **Add Parameter** form.
   - Fill in:
     - **Parameter Code**: Unique identifier (e.g., `battery_capacity`).
     - **Parameter Name**: User-facing name (e.g., `Battery Capacity`).
     - **Parameter Type**: Choose `range`, `select`, `checkbox`, or `radio`.
     - **Parameter Values**: Enter JSON-formatted values (e.g., `["5","10","12"]` for checkbox, `{"min":150,"max":10000}` for range).
     - **Active**: Check to enable the parameter.
   - Click **Add** to save.
3. **Edit or Delete Parameters**:
   - View the list of existing parameters.
   - Click **Edit** to modify a parameter or **Delete** to remove it (with confirmation).

### User Usage
1. **Access the Filter Form**:
   - On the Mstore product list page (`index.php?e=mstore`), users will see the filter form if active parameters exist. It is recommended to integrate the filter form into category-specific product list templates, e.g., `mstore.list.e-scooter.tpl`, `mstore.list.e-bike.tpl`, etc.
2. **Apply Filters**:
   - Select desired values (e.g., check boxes for battery capacity, select a color, adjust the range slider for motor power).
   - Click **Apply Filters** to filter the product list.
   - Click **Reset Filters** to clear selections.
3. **View Product Parameters**:
   - On individual product pages, users will see parameters (e.g., `Color: Black`, `Wheel Size: 24 inches`) if defined.

## Database Structure

- **`cot_mstorefilter_params`**:
  - `param_id`: Auto-incremented ID.
  - `param_name`: Unique code (e.g., `battery_capacity`).
  - `param_title`: Display name (e.g., `Battery Capacity`).
  - `param_type`: Type (`range`, `select`, `checkbox`, `radio`).
  - `param_values`: JSON string of allowed values.
  - `param_active`: Boolean (1 for active, 0 for inactive).
- **`cot_mstorefilter_params_values`**:
  - `value_id`: Auto-incremented ID.
  - `msitem_id`: Foreign key linking to `cot_mstore.msitem_id`.
  - `param_id`: Foreign key linking to `cot_mstorefilter_params.param_id`.
  - `param_value`: Stored value (e.g., `12` for checkbox, `900-1000` for range).

## Notes
- **JSON Validation**: Use tools like [jsonlint.com](https://jsonlint.com) to ensure valid JSON for parameter values.
- **PHP Compatibility**: Requires PHP 8.4, Cotonti Siena v0.9.26.
- **Logging**: Check `plugins/mstorefilter/logs/mstorefilter.log` for debugging.
- **Error Handling**: Invalid JSON or missing fields in the admin panel will trigger error messages.
- **Filtering**: Filters are applied via SQL queries using `INNER JOIN` to link products with parameters. Range filters check the maximum value, checkboxes support multiple selections, and select/radio require exact matches. If no filters are selected but the search button is clicked, a message indicates no parameters were chosen.

## License
BSD License (non-commercial version, the plugin is not for sale, you can modify the extension code as needed).

## Support
- **Issues**: Report bugs or request features at [https://github.com/webitproff/cot-mstorefilter](https://github.com/webitproff/cot-mstorefilter).
- **Help and Support**: For questions, post in Russian or English on the **[forum](https://abuyfile.com/en/forums/cotonti/custom/plugs)**.

## Author
- **Author**: Webitproff
- **GitHub**: [https://github.com/webitproff](https://github.com/webitproff)
- **Date**: 24 July 2025

----

# RU
# Плагин Mstore Filter для Cotonti Siena v0.9.26

Плагин **Mstore Filter** предоставляет возможности динамической фильтрации для модуля [Mstore](https://github.com/webitproff/cot-multistore) в Cotonti Siena v0.9.26. Он позволяет администраторам задавать пользовательские параметры фильтрации для товаров и дает пользователям возможность фильтровать товары по этим параметрам, таким как пользовательские атрибуты (например, ёмкость батареи, тип рамы или цвет). Фильтрация по цене находится в разработке. Плагин поддерживает несколько типов фильтров (диапазон, выбор, чекбоксы, радиокнопки) и легко интегрируется с модулем Mstore.


## Возможности

### Функционал для администратора
- **Управление параметрами**: Добавление, редактирование и удаление параметров фильтрации через административную панель.
- **Гибкие типы параметров**: Поддержка четырех типов фильтров:
  - **Диапазон**: Числовые диапазоны (например, мощность двигателя) с минимальным и максимальным значениями.
  - **Выпадающий список**: Выбор одного варианта из списка фиксированных значений.
  - **Чекбоксы**: Выбор одного или нескольких вариантов из списка.
  - **Радиокнопки**: Выбор одного варианта из списка.
- **Значения параметров в формате JSON**: Задание допустимых значений в формате JSON (например, `["Красный","Зелёный","Синий"]` для списка/чекбоксов/радиокнопок или `{"min":150,"max":10000}` для диапазона).
- **Переключатель активности**: Включение или отключение параметров фильтрации без их удаления.
- **Валидация**: Проверка уникальности кодов параметров и корректности JSON для значений параметров.
- **Логирование**: Все действия, связанные с фильтрами (например, сохранение параметров, ошибки), записываются в файл `mstorefilter.log` для отладки.

### Функционал для пользователей
- **Динамическая форма фильтрации**: Отображается на странице списка товаров Mstore, позволяя пользователям фильтровать товары по активным параметрам.
- **Типы фильтров**:
  - **Диапазон**: Интерактивный ползунок для выбора значения в заданном диапазоне.
  - **Выпадающий список**: Меню для выбора одного варианта.
  - **Чекбоксы**: Чекбоксы для множественного выбора.
  - **Радиокнопки**: Радиокнопки для выбора одного варианта.
- **Поддержка категорий**: Фильтры могут применяться в пределах определённых категорий Mstore.
- **Кнопки "Применить" и "Сбросить"**: Кнопки для применения фильтров или сброса до значений по умолчанию.
- **Отображение параметров товара**: На страницах отдельных товаров отображаются параметры (например, `Цвет: Чёрный`, `Размер колёс: 24 дюйма`), если они заданы.
- **Фильтрация списка товаров**: Фильтрация выполняется через SQL-запросы с использованием `INNER JOIN` для связи товаров с параметрами фильтра. Поддерживает условия для диапазонов (максимальное значение), чекбоксов (множественный выбор) и одиночных значений (select/radio). Показывает количество найденных товаров или сообщение об отсутствии результатов.

## Структура файлов

```
mstorefilter/
├── inc/
│   └── mstorefilter.functions.php          # Основные функции (логирование, генерация форм, загрузка параметров, форматирование значений)
├── lang/
│   ├── mstorefilter.ru.lang.php            # Файл русского языка для интерфейсов администратора и пользователей
│   └── mstorefilter.en.lang.php            # Файл английского языка для интерфейсов администратора и пользователей
├── logs/                                   # Папка для файлов логов (создаётся автоматически)
├── setup/
│   ├── mstorefilter.install.sql            # SQL для создания таблиц, связанных с фильтрами
│   └── mstorefilter.uninstall.sql          # SQL для удаления таблиц, связанных с фильтрами
├── tpl/
│   ├── mstorefilter.admin.tpl              # Шаблон интерфейса административной панели
│   └── mstorefilter.filterform.tpl         # Шаблон формы фильтрации для пользователей
├── mstorefilter.admin.php                  # Логика административной панели для управления параметрами фильтра
├── mstorefilter.global.php                 # Глобальные настройки плагина
├── mstorefilter.mstore.list.query.php      # Хук для обработки SQL-запросов фильтрации списка товаров, включая динамическую генерацию условий для фильтров
├── mstorefilter.mstore.add.add.done.php    # Хук для сохранения параметров фильтра при добавлении/редактировании товара
├── mstorefilter.mstore.add.edit.tags.php   # Хук для добавления полей параметров фильтра в формы добавления/редактирования товара
├── mstorefilter.mstore.tags.php            # Хук для отображения параметров фильтра на страницах товаров
├── mstorefilter.mstoretags.php             # Хук для отображения формы фильтра на странице списка товаров
└── mstorefilter.setup.php                  # Файл конфигурации плагина
```

## Установка

1. **Скачивание и распаковка**:
   - Скачайте плагин `cot-mstorefilter` с [https://github.com/webitproff/cot-mstorefilter](https://github.com/webitproff/cot-mstorefilter).
   - Распакуйте папку `mstorefilter` в директорию `plugins/` вашей установки Cotonti (например, `plugins/mstorefilter/`).

2. **Установка плагина**:
   - Войдите в административную панель Cotonti.
   - Перейдите в **Администрирование > Расширения > Плагины**.
   - Найдите **Mstore Filter** в списке и нажмите **Установить**.
   - Плагин создаст необходимые таблицы в базе данных (`cot_mstorefilter_params` и `cot_mstorefilter_params_values`), как указано в `mstorefilter.install.sql`.

3. **Проверка зависимостей**:
   - Убедитесь, что модуль **Mstore** установлен и активен, так как плагин требует его.

## Интеграция с модулем Mstore

Для полной интеграции плагина Mstore Filter с модулем Mstore обновите следующие файлы шаблонов:

1. **mstore.tpl** (Страница отдельного товара):
   - Добавьте следующий код для отображения параметров фильтра для отдельных товаров:
     ```html
     <!-- IF {PHP|cot_plugin_active('mstorefilter')} -->
     <h3>{PHP.L.mstorefilter_paramsItem}</h3>
     <dl class="row">
         <!-- BEGIN: MSTORE_FILTER_PARAMS -->
         <dt class="col-sm-4">{PARAM_TITLE}</dt>
         <dd class="col-sm-8">{PARAM_VALUE}</dd>
         <!-- END: MSTORE_FILTER_PARAMS -->
     </dl>
     <!-- ENDIF -->
     ```

2. **mstore.list.tpl** (Страница списка товаров):
   - Добавьте следующий код для отображения формы фильтрации:
     ```html
     <!-- IF {PHP|cot_plugin_active('mstorefilter')} -->
     {MSTORE_FILTER_FORM}
     <!-- ENDIF -->
     ```
   - При желании добавьте блок для сообщений о фильтрации:
     ```html
     <!-- IF {PHP|cot_plugin_active('mstorefilter')} -->
     <div class="alert {MSTOREFILTER_MESSAGE_CLASS}">
         {MSTOREFILTER_MESSAGE}
     </div>
     <!-- ENDIF -->
     ```

3. **mstore.edit.tpl** (Страница редактирования товара):
   - Добавьте следующий код для включения полей параметров фильтра в форму редактирования товара:
     ```html
     <!-- IF {PHP|cot_plugin_active('mstorefilter')} -->
     {MSTORE_FORM_FILTER_PARAMS}
     <!-- ENDIF -->
     ```

## Использование

### Использование администратором
1. **Доступ к административной панели**:
   - Перейдите в **Администрирование > Другое > Mstore Filter**.
2. **Добавление параметра**:
   - Нажмите на форму **Добавление параметра**.
   - Заполните:
     - **Код параметра**: Уникальный идентификатор (например, `battery_capacity`).
     - **Название параметра**: Название, отображаемое пользователям (например, `Ёмкость АКБ`).
     - **Тип параметра**: Выберите `range`, `select`, `checkbox` или `radio`.
     - **Значения параметра**: Введите значения в формате JSON (например, `["5","10","12"]` для чекбоксов, `{"min":150,"max":10000}` для диапазона).
     - **Активен**: Установите флажок, чтобы включить параметр.
   - Нажмите **Добавить** для сохранения.
3. **Редактирование или удаление параметров**:
   - Просмотрите список существующих параметров.
   - Нажмите **Редактировать** для изменения параметра или **Удалить** для его удаления (с подтверждением).

### Использование пользователями
1. **Доступ к форме фильтрации**:
   - На странице списка товаров Mstore (`index.php?e=mstore`) пользователи увидят форму фильтрации, если есть активные параметры. Рекомендуется интегрировать форму фильтрации в шаблон списка товаров конкретной категории, например, `mstore.list.e-scooter.tpl`, `mstore.list.e-bike.tpl` и т.д.
2. **Применение фильтров**:
   - Выберите желаемые значения (например, отметьте чекбоксы для ёмкости батареи, выберите цвет, настройте ползунок диапазона для мощности двигателя).
   - Нажмите **Применить фильтры** для фильтрации списка товаров.
   - Нажмите **Сбросить фильтры** для очистки выбранных значений.
3. **Просмотр параметров товара**:
   - На страницах отдельных товаров пользователи увидят параметры (например, `Цвет: Чёрный`, `Размер колёс: 24 дюйма`), если они заданы.

## Структура базы данных

- **`cot_mstorefilter_params`**:
  - `param_id`: Автоинкрементный идентификатор.
  - `param_name`: Уникальный код (например, `battery_capacity`).
  - `param_title`: Отображаемое название (например, `Ёмкость АКБ`).
  - `param_type`: Тип (`range`, `select`, `checkbox`, `radio`).
  - `param_values`: Строка JSON с допустимыми значениями.
  - `param_active`: Булево значение (1 — активно, 0 — неактивно).
- **`cot_mstorefilter_params_values`**:
  - `value_id`: Автоинкрементный идентификатор.
  - `msitem_id`: Внешний ключ, связанный с `cot_mstore.msitem_id`.
  - `param_id`: Внешний ключ, связанный с `cot_mstorefilter_params.param_id`.
  - `param_value`: Сохранённое значение (например, `12` для чекбокса, `900-1000` для диапазона).

## Примечания
- **Валидация JSON**: Используйте инструменты, такие как [jsonlint.com](https://jsonlint.com), для проверки корректности JSON для значений параметров.
- **Совместимость с PHP**: Требуется PHP 8.4, Cotonti Siena v0.9.26.
- **Логирование**: Проверяйте файл `plugins/mstorefilter/logs/mstorefilter.log` для отладки.
- **Обработка ошибок**: Некорректный JSON или отсутствие обязательных полей в административной панели вызовут сообщения об ошибках.
- **Фильтрация**: Фильтры применяются через SQL-запросы с использованием `INNER JOIN` для связи товаров с параметрами. Для диапазонов фильтрация проверяет максимальное значение, для чекбоксов — множественный выбор, для select/radio — точное совпадение. Если фильтры не выбраны, но нажата кнопка поиска, отображается сообщение об отсутствии параметров.

## Лицензия
Лицензия BSD (некоммерческая версия, плагин не для продажи, код расширения можете менять под свои нужды как угодно).

## Поддержка
- **Проблемы**: Сообщайте об ошибках или предлагайте улучшения на [https://github.com/webitproff/cot-mstorefilter](https://github.com/webitproff/cot-mstorefilter).
- **Помощь и поддержка**: Если у вас есть вопросы, вы можете задать их на русском или английском языке на **[форуме](https://abuyfile.com/en/forums/cotonti/custom/plugs)**.

## Автор
- **Автор**: Webitproff
- **GitHub**: [https://github.com/webitproff](https://github.com/webitproff)
- **Дата**: 24 июля 2025
