<?php 

namespace frontend\controllers\actions\site;

use frontend\components\PriceListComponent;
use frontend\services\CalculatorService;
use Yii;
use yii\base\Action;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;


class PricelistAction extends Action 
{
    public function run()
    {
        $calculator  = Yii::$container->get(CalculatorService::class);
        $pricesTypes = $calculator->getPrices();
        $smallWholesalePriceStartFrom = $pricesTypes['price']['max'] + 1;
        $wholesalePriceStartFrom = $pricesTypes['small_wholesale_price']['max'] + 1;
        $date = (new \DateTime)->format('d,m,Y');
        
        /** @var PriceListComponent $priceList */
        $priceList = Yii::$app->priceList->create();

        $reader = IOFactory::createReader('Xlsx');
        $spreadsheet = $reader->load(Yii::getAlias('@app/price-template.xlsx'));
        $spreadsheet->getProperties()
            ->setTitle('Прайс лист')
            ;
        $sheet = $spreadsheet->getActiveSheet();
        $categoryStyle = array_merge($sheet->getStyle('A3')->exportArray(), [
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color'    => ['argb' => '92D050'],
            ]
        ]);
        $productStyle  = array_merge($sheet->getStyle('A4')->exportArray(), []);
        $productSmallWholesalePriceStyle = array_merge($sheet->getStyle('A4')->exportArray(), [
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color'    => ['argb' => 'dfebf7'],
            ]
        ]);
        $productWholesalePriceStyle = array_merge($sheet->getStyle('A4')->exportArray(), [
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color'    => ['argb' => 'fff2cd'],
            ]
        ]);
        $productPriceStyle = array_merge($sheet->getStyle('A4')->exportArray(), [
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color'    => ['argb' => 'd9d9d9'],
            ]
        ]);

        $rowIndex = 5;
        foreach($priceList->listing as $item) {
            $isSku = ($item['type'] ?? null) === 'sku';
            $isCategory = ($item['type'] ?? null) === 'category';
            if($isSku) {
                $skuName = sprintf('%s %s %s', $item['product_name'], $item['sku_code'], $item['sku_name']);
                $skuSmallWholesalePrice = $item['small_wholesale_price'];
                $skuWholesalePrice = $item['wholesale_price'];
                $skuPrice = $item['price'];
                $sheet->setCellValue('A'.$rowIndex, $skuName);
                $sheet->getStyle('A'.$rowIndex)->applyFromArray($productStyle);

                $sheet->setCellValue('B'.$rowIndex, $skuSmallWholesalePrice);
                $sheet->getStyle('B'.$rowIndex)->applyFromArray($productSmallWholesalePriceStyle);
                
                $sheet->setCellValue('C'.$rowIndex, $skuWholesalePrice);
                $sheet->getStyle('C'.$rowIndex)->applyFromArray($productWholesalePriceStyle);
                
                $sheet->setCellValue('D'.$rowIndex, $skuPrice);
                $sheet->getStyle('D'.$rowIndex)->applyFromArray($productPriceStyle);
            }
            if($isCategory) {
                $countProducts = intval($item['count_products'] ?? null);
                if($countProducts < 0) {
                    $countProducts = 0;
                }
                if(!$countProducts) {
                    continue;
                }

                $prefix = $item['deep_level'] > 0 ? str_repeat("-", $item['deep_level']): '';
                $sheet->setCellValue('A'.$rowIndex, $prefix . ' ' .$item['name']);
                $sheet->getStyle('A'.$rowIndex)->applyFromArray($categoryStyle);
                $sheet->getStyle('B'.$rowIndex)->applyFromArray($categoryStyle);
                $sheet->getStyle('C'.$rowIndex)->applyFromArray($categoryStyle);
                $sheet->getStyle('D'.$rowIndex)->applyFromArray($categoryStyle);    
            }
            $rowIndex++;
        }

        $sheet->removeRow(3, 1);
        $sheet->removeRow(3, 1);
        $sheet->getCell('A1')->setValue(str_replace('<%d%>', $date, $sheet->getCell('A1')->getValue()));
        $sheet->getCell('B2')->setValue(str_replace('<%sw%>', $smallWholesalePriceStartFrom, $sheet->getCell('B2')->getValue()));
        $sheet->getCell('C2')->setValue(str_replace('<%w%>', $wholesalePriceStartFrom, $sheet->getCell('C2')->getValue()));
    
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="berkut-pricelist.xlsx"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }    

    public function _run()
    {
        /** @var PriceListComponent $priceList */
        $priceList = Yii::$app->priceList->create();
        

        $spreadsheet = new Spreadsheet();

        $spreadsheet->getProperties()
            ->setTitle('Прайс лист')
            ;

        $spreadsheet = $this->rr($priceList->columns, $priceList->listing, $spreadsheet);

        $spreadsheet->getActiveSheet()
            ->setTitle('Прайс лист');

        $spreadsheet->setActiveSheetIndex(0);

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="berkut-pricelist.xls"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $writer = IOFactory::createWriter($spreadsheet, 'Xls');
        $writer->save('php://output');
        exit;
    }    

    private function rr($columns, $list, Spreadsheet $spreadsheet): Spreadsheet
    {
        $baseRow = 1;
        $chars = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U'];
        $columnsValues = array_values($columns);
        $columnsKeys = array_keys($columns);
        $sheet = $spreadsheet->setActiveSheetIndex(0);

        foreach($columnsValues as $key => $title) {
            $sheet->setCellValue(sprintf('%s%s', $chars[$key], $baseRow), $title);
        }
        $baseRow++;

        foreach($list as $item) {
            $type = $item['type'];
            if($type === 'category') {
                $prefix = $item['deep_level'] > 0 ? str_repeat("-", $item['deep_level']): '';
                $sheet->setCellValue(sprintf('%s%s', $chars[0], $baseRow), $prefix . ' ' .$item['name']);
            } else if($type === 'sku') {
                foreach($columnsKeys as $key => $code) {
                    $value = is_array($item[$code]) 
                        ? implode(', ', $item[$code])
                        : strval($item[$code]);
                        
                    $sheet->setCellValue(sprintf('%s%s', $chars[$key], $baseRow), $value);
                }
            }
            $baseRow++;
        }

        return $spreadsheet;
    }
}