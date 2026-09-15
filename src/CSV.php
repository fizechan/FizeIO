<?php

namespace Fize\IO;

/**
 * CSV导出类
 *
 * 支持大数据量，避免浏览器端逐页拉取导致卡死
 */
class CSV
{

    protected $output;

    public function __construct($filename)
    {
        $this->init();

        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: no-store, no-cache, must-revalidate');
        header('Pragma: no-cache');

        $this->output = fopen('php://output', 'w');
        fwrite($this->output, chr(0xEF) . chr(0xBB) . chr(0xBF));
    }

    protected function init()
    {
        if (function_exists('set_time_limit')) {
            set_time_limit(0);
        }

        while (ob_get_level() > 0) {
            ob_end_clean();
        }
    }

    public function put($fields)
    {
        $row = [];
        foreach ($fields as $field) {
            $row[] = self::formatValue($field);
        }
        fputcsv($this->output, $row);
    }

    public function flush()
    {
        fflush($this->output);
    }

    public function close()
    {
        fclose($this->output);
    }

    public static function formatValue($value): string
    {
        if (is_array($value)) {
            $value = implode(',', $value);
        }

        $value = (string)$value;
        if ($value !== '' && in_array($value[0], ['=', '+', '-', '@'])) {
            $value = "\t" . $value;
        }

        return $value;
    }
}
