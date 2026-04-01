<?php

if (!defined('ABSPATH')) {
    exit;
}

class RW_BS_Hours_Handler {
    public static function to_opening_hours_specification($hours) {
        if (!is_array($hours)) {
            return array();
        }

        $spec = array();

        foreach ($hours as $row) {
            $day = isset($row['day']) ? sanitize_text_field($row['day']) : '';
            $closed = !empty($row['closed']);
            $open = isset($row['open']) ? sanitize_text_field($row['open']) : '';
            $close = isset($row['close']) ? sanitize_text_field($row['close']) : '';

            if (!$day || $closed || !$open || !$close) {
                continue;
            }

            $spec[] = array(
                '@type' => 'OpeningHoursSpecification',
                'dayOfWeek' => 'https://schema.org/' . $day,
                'opens' => $open,
                'closes' => $close,
            );
        }

        return $spec;
    }

    public static function to_display_hours($hours) {
        if (!is_array($hours)) {
            return '';
        }

        $lines = array();

        foreach ($hours as $row) {
            $day = isset($row['day']) ? sanitize_text_field($row['day']) : '';
            $closed = !empty($row['closed']);
            $open = isset($row['open']) ? sanitize_text_field($row['open']) : '';
            $close = isset($row['close']) ? sanitize_text_field($row['close']) : '';

            if (!$day) {
                continue;
            }

            $lines[] = $closed ? ($day . ': Closed') : ($day . ': ' . $open . ' - ' . $close);
        }

        return implode("\n", $lines);
    }
}
