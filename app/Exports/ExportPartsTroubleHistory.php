<?php

namespace App\Exports;

use DB;
use App\Models\PartTroubleHistory;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class ExportPartsTroubleHistory implements FromCollection, WithHeadings, WithEvents, WithCustomStartCell{
    protected $from;
    protected $to;
    protected $situation;
    protected $section;
    protected $defect;
    protected $model;

    protected $mergeRanges = [];

    protected $records;

    protected $recordStartRows;

    public function __construct($from, $to, $situation, $section, $defect, $model){

        $this->from = $from;
        $this->to   = $to;
        $this->situation = $situation;
        $this->section = $section;
        $this->defect = $defect;
        $this->model = $model;

        // 🔒 LOAD ONCE
        $this->records = PartTroubleHistory::with(['defects.defect_item', 'improvements'])
            ->whereBetween('date_encountered', [
                $this->from . ' 00:00:00',
                $this->to   . ' 23:59:59'
            ])
            ->when($this->situation !== 'ALL', function ($query) {
                $query->where('situation', $this->situation);
            })
            ->when($this->section !== 'ALL', function ($query) {
                $query->where('section', $this->section);
            })
            ->when($this->defect !== 'ALL', function ($query) {
                $query->whereHas('defects', function ($q) {
                    $q->where('defect_id', $this->defect);
                });
            })
            ->when($this->model !== 'ALL', function ($query) {
                $query->where('model', $this->model);
            })
            ->orderBy('date_encountered', 'ASC')
            ->get();
    }

    protected function getRecords(){

        if ($this->records === null) {
            $this->records = PartTroubleHistory::with(['defects.defect_item', 'improvements'])
                ->whereBetween('date_encountered', [
                    $this->from . ' 00:00:00',
                    $this->to   . ' 23:59:59'
                ])
                ->when($this->situation !== 'ALL', function ($query) {
                    $query->where('situation', $this->situation);
                })
                ->when($this->section !== 'ALL', function ($query) {
                    $query->where('section', $this->section);
                })
                ->when($this->defect !== 'ALL', function ($query) {
                    $query->whereHas('defects', function ($q) {
                        $q->where('defect_id', $this->defect);
                    });
                })
                ->when($this->model !== 'ALL', function ($query) {
                    $query->where('model', $this->model);
                })
                ->orderBy('date_encountered', 'ASC')
                ->get();
        }
        return $this->records;
    }

    public function collection(){

        // 🔒 RESET state (CRITICAL)
        $this->mergeRanges = [];
        $this->recordStartRows = [];

        // --- Handle empty records ---
        if (empty($this->records) || $this->records->count() === 0) {
            $rows = new Collection();
            $rows->push([
                'No data available', '', '', '', '', '', '', '', '', '', '', ''
            ]);

            // Add merge range so we can merge A3:L3 later
            $this->mergeRanges[] = 'A3:L3';

            return $rows;
        }

        // --- 2️⃣ Proceed normally ---
        $rows = new Collection();
        $currentRow = 3;

        foreach ($this->records as $record){
            $defects = collect($record->defects);

            if ($defects->isEmpty()) {
                continue;
            }

            // $defect = $record->defects->first();
            $improvements = collect($record->improvements);

            $rowCount = max($improvements->count(), 1);
            $startRow = $currentRow;
            $endRow   = $currentRow + $rowCount - 1;

            $this->recordStartRows[$record->id] = $startRow;

            // foreach ($defects as $defect) {
                for ($i = 0; $i < $rowCount; $i++) {
                    $improvement = $improvements->get($i);

                    $rows->push([
                        $record->situation,
                        $record->section,
                        $record->date_encountered,
                        $record->model,
                        // $defect->defect_item->defect_name ?? '',
                        '', // defect name
                        '', // image column
                        '', // no_of_occurrence
                        // $defect->no_of_occurrence ?? '',
                        $improvement ? $improvement->factor : '',
                        $improvement ? $improvement->cause : '',
                        $improvement ? $improvement->analysis : '',
                        $improvement ? $improvement->counter_measure : '',
                        $improvement ? $improvement->implementation_date : '',
                        // $improvement ? $improvement->remarks : '',
                    ]);
                }
            // }

            // merge only if truly multi-row
            if ($rowCount > 1) {
                foreach (['A','B','C','D','E','F','G'] as $col) {
                    $this->mergeRanges[] = "{$col}{$startRow}:{$col}{$endRow}";
                }
            }

            $currentRow = $endRow + 1;
        }

        return $rows;
    }

    public function headings(): array{
        return [
            'Situation',
            'Section',
            'Series / Model Name',
            'Date Encountered',
            'Mode of Defect',
            'Illustration of Defect',
            'No. of Occurrence',
            'Factor',
            'Cause',
            'Analysis',
            'Counter Measure',
            'Implementation Date',
        ];
    }

    public function startCell(): string{
        return 'A2';
    }

    public function registerEvents(): array {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();
                $lastCol = 'L';
                $lastRow = $sheet->getHighestRow();

                /**
                 * COLUMN WIDTH
                 * Excel column width ≈ pixels / 7
                 * 100px ÷ 7 ≈ 14.3
                 */
                $sheet->getColumnDimension('F')->setWidth(60); // Illustration column

                /**
                 * ROW HEIGHT
                 * Excel row height is in points
                 * 100px ≈ 75 points
                 */
                for ($row = 3; $row <= $lastRow; $row++) {
                    $sheet->getRowDimension($row)->setRowHeight(100);
                }

                /**
                 * Center alignment for illustration column
                 */
                $sheet->getStyle("F3:F{$lastRow}")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setVertical(Alignment::VERTICAL_CENTER);

                // 1. Title row
                $sheet->setCellValue('A1', 'TS-F1 PRODUCTS PAST TROUBLE HISTORY');
                $sheet->mergeCells('A1:L1');
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
                $sheet->getStyle('A1')->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setVertical(Alignment::VERTICAL_CENTER);

                // 2. Header row formatting (row 2)
                $headerRow = 2;
                $sheet->getStyle("A{$headerRow}:{$lastCol}{$headerRow}")
                    ->getFont()->setBold(true);
                $sheet->getStyle("A{$headerRow}:{$lastCol}{$headerRow}")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setVertical(Alignment::VERTICAL_CENTER)
                    ->setWrapText(false);
                $sheet->getStyle("A{$headerRow}:{$lastCol}{$headerRow}")
                    ->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('FFFF00'); // Yellow

                // 3. Apply merges from collection
                foreach ($this->mergeRanges as $range) {
                    $sheet->mergeCells($range);
                }

                // 4. Apply borders to entire table
                $sheet->getStyle("A{$headerRow}:{$lastCol}{$lastRow}")
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN);

                // 5. Auto-size columns
                foreach (range('A', $lastCol) as $col) {
                    // Skip Illustration column (F)
                    if ($col === 'F') {
                        continue;
                    }

                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }

                // 6. Data rows formatting (row 3 onward)
                $sheet->getStyle("A" . ($headerRow + 1) . ":{$lastCol}{$lastRow}")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setVertical(Alignment::VERTICAL_CENTER)
                    ->setWrapText(true);

                // Skip image insertion if no records
                if ($this->records->count() === 0) {
                    $sheet->getStyle("A3:L{$lastRow}")->getAlignment()
                        ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                        ->setVertical(Alignment::VERTICAL_CENTER);
                    return; // nothing more to do
                }

                // --- INSERT IMAGES ---
                foreach ($this->records as $record) {

                    $startRow = $this->recordStartRows[$record->id] ?? null;
                    if (!$startRow) {
                        continue;
                    }

                    $defect = $record->defects;
                    if (!$defect) {
                        continue;
                    }

                    $imagePath = storage_path(
                        'app/public/file_attachments/' . $record->defects->illustration_of_defect
                    );

                    if (!file_exists($imagePath)){
                        continue;
                    }

                    // 1️⃣ Patch defect name (Column E)
                    $sheet->setCellValue("E{$startRow}", $defect->defect_item->defect_name ?? '');

                    // 2️⃣ Patch no_of_occurrence (Column G)
                    $sheet->setCellValue("G{$startRow}", $defect->no_of_occurrence ?? '');

                    // 3️⃣ Insert image (Column F)
                    $drawing = new Drawing();
                    $drawing->setPath($imagePath);
                    $drawing->setCoordinates("F{$startRow}");
                    $drawing->setWidth(80);
                    $drawing->setHeight(100);
                    $drawing->setOffsetX(10);
                    $drawing->setOffsetY(10);

                    $drawing->setWorksheet($sheet);
                }
            }
        ];
    }
}
