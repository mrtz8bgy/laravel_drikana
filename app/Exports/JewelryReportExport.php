<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

/**
 * خروجی Excel گزارش‌های ماژول جواهرات (گواهی‌نامه، تعمیرات، انتقال مالکیت).
 *
 * این کلاس «دادهٔ آماده» را می‌گیرد و آن را به فایل XLSX تبدیل می‌کند؛
 * کوئری‌ها در `Jewelry\Admin\ReportController@exportDataset` نوشته شده‌اند
 * تا منطق استخراج داده در یک جا بماند.
 */
class JewelryReportExport implements FromArray, WithHeadings
{
    /**
     * @var array<int,string>
     */
    protected $headings;

    /**
     * @var array<int,array>
     */
    protected $rows;

    /**
     * @param  array<int,string> $headings
     * @param  array<int,array>  $rows
     */
    public function __construct(array $headings, array $rows)
    {
        $this->headings = $headings;
        $this->rows     = $rows;
    }

    /**
     * @return array<int,array>
     */
    public function array() : array
    {
        return $this->rows;
    }

    /**
     * @return array<int,string>
     */
    public function headings() : array
    {
        return $this->headings;
    }
}
