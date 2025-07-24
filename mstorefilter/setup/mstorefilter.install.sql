CREATE TABLE IF NOT EXISTS `cot_mstorefilter_params` (
    `param_id` INT NOT NULL AUTO_INCREMENT,
    `param_name` VARCHAR(64) NOT NULL,
    `param_title` VARCHAR(255) NOT NULL,
    `param_type` ENUM('range', 'select', 'checkbox', 'radio') NOT NULL,
    `param_values` TEXT NOT NULL,
    `param_active` TINYINT(1) DEFAULT 1,
    PRIMARY KEY (`param_id`),
    UNIQUE KEY `param_name` (`param_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `cot_mstorefilter_params` (`param_id`, `param_name`, `param_title`, `param_type`, `param_values`, `param_active`) VALUES
(1, 'battery_capacity', 'Ёмкость АКБ', 'checkbox', '["5","10","11","12"]', 1),
(2, 'rama', 'Тип рамы', 'checkbox', '["Складная","Цельная"]', 1),
(3, 'power_motor', 'Мощность электромотора', 'range', '{"min":150,"max":10000}', 1),
(4, 'color', 'Цвет', 'radio', '["Красный","Синий","Зелёный","Чёрный"]', 1),
(5, 'wheel_size', 'Размер колёс', 'select', '["16 дюймов","20 дюймов","24 дюйма","26 дюймов"]', 1);

CREATE TABLE IF NOT EXISTS `cot_mstorefilter_params_values` (
    `value_id` INT NOT NULL AUTO_INCREMENT,
    `msitem_id` INT UNSIGNED NOT NULL,
    `param_id` INT NOT NULL,
    `param_value` TEXT NOT NULL,
    PRIMARY KEY (`value_id`),
    FOREIGN KEY (`msitem_id`) REFERENCES `cot_mstore`(`msitem_id`) ON DELETE CASCADE,
    FOREIGN KEY (`param_id`) REFERENCES `cot_mstorefilter_params`(`param_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `cot_mstorefilter_params_values` (`value_id`, `msitem_id`, `param_id`, `param_value`) VALUES
(1, 1, 1, '5'),
(2, 1, 1, '10'),
(3, 1, 2, 'Складная'),
(4, 1, 3, '500-1000'),
(5, 1, 4, 'Красный'),
(6, 1, 5, '20 дюймов');