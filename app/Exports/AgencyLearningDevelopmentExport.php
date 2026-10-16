<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class AgencyLearningDevelopmentExport implements FromView, WithDrawings, WithColumnWidths, WithTitle, WithEvents
{
    private const FIRST_DATA_ROW = 13;

    private const MONTHS = [
        'January',
        'February',
        'March',
        'April',
        'May',
        'June',
        'July',
        'August',
        'September',
        'October',
        'November',
        'December',
    ];

    // Ayos ng status sa loob ng bawat office
    private const STATUS_ORDER = ['REGULAR', 'CO-TERMINOUS', 'CASUAL', 'CONTRACTUAL', 'HONORARIUM', 'ELECTIVE'];

    // vwEmployee.status  =>  label sa Excel (gaya ng template)
    private const STATUS_LABEL = [
        'REGULAR'      => 'PERMANENT',
        'CO-TERMINOUS' => 'COTERMINOUS',
        'CASUAL'       => 'CASUAL',
        'CONTRACTUAL'  => 'JOB ORDER',
        'HONORARIUM'   => 'HONORARIUM',
        'ELECTIVE'     => 'ELECTIVE',
    ];

    private array $layout = [];
    private int $totalRow;

    public function __construct(private Collection $employees, private int $year)
    {
        $this->buildLayout();
    }

    /** Flat na listahan ng rows, para iisa ang row numbers ng blade at ng AfterSheet. */
    private function buildLayout(): void
    {
        $row = self::FIRST_DATA_ROW;
        $no  = 1;

        foreach ($this->employees as $office => $statuses) {
            $this->layout[] = ['type' => 'office', 'row' => $row++, 'label' => trim($office)];

            $statusKeys = $statuses->keys()->sortBy(function ($s) {
                $i = array_search($s, self::STATUS_ORDER, true);
                return $i === false ? 99 : $i;
            });

            foreach ($statusKeys as $status) {
                $this->layout[] = [
                    'type'  => 'status',
                    'row'   => $row++,
                    'label' => '          ' . (self::STATUS_LABEL[$status] ?? $status),
                ];

                // foreach ($statuses[$status] as $emp) {
                //     $months = array_map(
                //         fn($m) => (int) data_get($emp['trainings_by_month'], "{$m}.count", 0),
                //         self::MONTHS
                //     );

                //     $this->layout[] = [
                //         'type'     => 'employee',
                //         'row'      => $row++,
                //         'no'       => $no++,
                //         'name'     => $emp['name'],
                //         'position' => $emp['position'],
                //         'months'   => $months,
                //     ];
                // }
                foreach ($statuses[$status] as $emp) {
                    $months  = [];
                    $details = [];

                    foreach (self::MONTHS as $i => $m) {
                        $months[$i] = (int) data_get($emp['trainings_by_month'], "{$m}.count", 0);

                        $details[$i] = collect(data_get($emp['trainings_by_month'], "{$m}.trainings", []))
                            ->map(fn($t) => '• ' . $t['learning_intervention'] . ' (' . $t['start_date'] . ')')
                            ->implode("\n");
                    }

                    $this->layout[] = [
                        'type'     => 'employee',
                        'row'      => $row++,
                        'no'       => $no++,
                        'name'     => $emp['name'],
                        'position' => $emp['position'],
                        'months'   => $months,
                        'details'  => $details,   // <-- bago
                    ];
                }
            }
        }

        $this->totalRow = $row;
        $this->layout[] = ['type' => 'total', 'row' => $row];
    }

    public function view(): View
    {
        return view('excel.agency-learning-development', [
            'layout' => $this->layout,
            'year'   => $this->year,
        ]);
    }

    public function title(): string
    {
        return "AS OF DEC 31, {$this->year}";
    }

    public function columnWidths(): array
    {
        $w = ['A' => 5, 'B' => 44.86, 'C' => 48, 'P' => 6.71];
        foreach (range('D', 'O') as $col) {
            $w[$col] = 5.57;
        }
        return $w;
    }

    public function drawings(): array
    {
        // Excel: pixels ≈ column width * 7 + 5
        $px = fn(float $w) => (int) round($w * 7 + 5);

        // lapad ng A hanggang O (dito nagtatapos ang green bar)
        $width = $px(5) + $px(40.86) + $px(48) + 12 * $px(5.57);

        $header = new Drawing();
        $header->setName('Header')->setPath(public_path('assets/image/ald-header.png'));
        $header->setResizeProportional(false)->setWidth($width)->setHeight(65)
            ->setCoordinates('A1');

        return [$header];
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $s    = $event->sheet->getDelegate();
                $last = $this->totalRow;

                // ---------- Base ----------
                $s->getStyle("A1:P{$last}")->getFont()->setName('Century Gothic')->setSize(10);
                $s->getStyle("A3:P{$last}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                $s->freezePane('A13');
                $s->getPageSetup()->setOrientation('landscape');
                $s->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(11, 12);

                // ---------- Row heights (gaya ng template) ----------
                $s->getRowDimension(1)->setRowHeight(45.75);
                // $s->getRowDimension(1)->setRowHeight(49.5);   // dati 45.75
                $s->getRowDimension(2)->setRowHeight(18);
                $s->getRowDimension(3)->setRowHeight(23.25);
                foreach (range(4, 10) as $r) {
                    $s->getRowDimension($r)->setRowHeight(10.5);
                }
                $s->getRowDimension(11)->setRowHeight(12.75);
                $s->getRowDimension(12)->setRowHeight(16.5);

                // ---------- Title ----------
                $s->mergeCells('A2:P2');
                $s->getStyle('A2')->getFont()->setBold(true)->setSize(12);
                $s->getStyle('A2')->getAlignment()->setHorizontal('center')->setVertical('center');

                // ---------- Legend ----------
                $s->mergeCells('D3:P3');
                $s->getStyle('A3:D3')->getFont()->setBold(true)->setSize(10);
                $s->getStyle('A3:P3')->getAlignment()->setHorizontal('center')->setVertical('center')->setWrapText(true);
                foreach (range(4, 10) as $r) {
                    $s->mergeCells("D{$r}:P{$r}");
                    $s->getStyle("B{$r}:D{$r}")->getFont()->setBold(true)->setSize(8);
                    $s->getStyle("B{$r}:D{$r}")->getAlignment()->setHorizontal('left')->setVertical('center');
                    $s->getStyle("A{$r}")->getAlignment()->setHorizontal('center')->setVertical('center');
                }

                // ---------- Table header (rows 11-12) ----------
                foreach (['A11:A12', 'B11:B12', 'C11:C12', 'D11:O11', 'P11:P12'] as $range) {
                    $s->mergeCells($range);
                }
                $s->getStyle('A11:P12')->getFont()->setBold(true);
                $s->getStyle('A11:P12')->getAlignment()->setHorizontal('center')->setVertical('center')->setWrapText(true);
                $s->getStyle('B11:C11')->getFont()->setSize(11);

                // ---------- Body ----------
                foreach ($this->layout as $item) {
                    $r = $item['row'];

                    switch ($item['type']) {
                        case 'office':
                        case 'status':
                            $s->mergeCells("A{$r}:C{$r}");
                            $s->getStyle("A{$r}:C{$r}")->getFill()->setFillType(Fill::FILL_SOLID)
                                ->getStartColor()->setRGB($item['type'] === 'office' ? 'FBE5D6' : 'E2EFDA');
                            $s->getStyle("A{$r}")->getFont()->setBold(true);
                            $s->getStyle("A{$r}")->getAlignment()->setHorizontal('left')->setVertical('center');
                            $s->getRowDimension($r)->setRowHeight(24.95);
                            break;

                        case 'employee':
                            $s->getRowDimension($r)->setRowHeight(24.95);
                            $s->getStyle("A{$r}")->getAlignment()->setHorizontal('center')->setVertical('center');
                            $s->getStyle("B{$r}:C{$r}")->getAlignment()->setVertical('center')->setWrapText(true);
                            $s->getStyle("D{$r}:P{$r}")->getAlignment()->setHorizontal('center')->setVertical('center');

                            // Siguraduhing number ang No. at counts, at formula ang TOTAL
                            $s->setCellValueExplicit("A{$r}", $item['no'], DataType::TYPE_NUMERIC);

                            foreach ($item['months'] as $i => $count) {
                                if ($count > 0) {
                                    $col  = chr(ord('D') + $i);
                                    $cell = "{$col}{$r}";

                                    $s->setCellValueExplicit($cell, $count, DataType::TYPE_NUMERIC);

                                    // Comment: listahan ng trainings sa buwan na ito
                                    $comment = $s->getComment($cell);
                                    $comment->setAuthor('ALD Report');
                                    $comment->getText()->createTextRun($item['details'][$i]);
                                    $comment->setWidth('320pt');
                                    $comment->setHeight((30 + $count * 28) . 'pt');
                                }
                            }

                            $s->setCellValueExplicit("P{$r}", "=SUM(D{$r}:O{$r})", DataType::TYPE_FORMULA);
                            break;

                        case 'total':
                            $first = self::FIRST_DATA_ROW;
                            $end   = $r - 1;
                            $s->mergeCells("A{$r}:C{$r}");
                            $s->getStyle("A{$r}:P{$r}")->getFont()->setBold(true);
                            $s->getStyle("A{$r}")->getAlignment()->setHorizontal('right')->setVertical('center');
                            $s->getStyle("D{$r}:P{$r}")->getAlignment()->setHorizontal('center')->setVertical('center');
                            $s->getRowDimension($r)->setRowHeight(24.95);
                            foreach (range('D', 'P') as $col) {
                                $s->setCellValueExplicit("{$col}{$r}", "=SUM({$col}{$first}:{$col}{$end})", DataType::TYPE_FORMULA);
                            }
                            break;
                    }
                }
            },
        ];
    }
}
