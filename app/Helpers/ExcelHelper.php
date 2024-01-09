<?php
namespace App\Helpers;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ExcelHelper{
    public function excelFileToArray($excelFile, $startRow = 0, $exportSheet = null, $debug = 0){
        $response = [];
        $inputFileType = IOFactory::identify($excelFile);
        $objReader = IOFactory::createReader($inputFileType);
        $objReader->setReadDataOnly(true);
        $spreadsheet = @$objReader->load($excelFile);

        for($sheetIndex = 0; $sheetIndex < $spreadsheet->getSheetCount(); $sheetIndex++){
            if($exportSheet && $exportSheet != $sheetIndex) continue;

            $worksheet = $spreadsheet->setActiveSheetIndex($sheetIndex);
            $highestRow = $worksheet->getHighestRow();
            $highestCol = $worksheet->getHighestColumn();
            $response[] = $worksheet->rangeToArray("A$startRow:$highestCol$highestRow", null, true, false, false);
        }

        return $response;
    }
}

