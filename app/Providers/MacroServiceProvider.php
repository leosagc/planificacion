<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Query\Builder;
use Maatwebsite\Excel\Sheet;
use Maatwebsite\Excel\Writer;

class MacroServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        Builder::macro('searchIn', function(array $needles, array $haystacks){
            $this->where(function($query) use ($needles, $haystacks){
                foreach($needles as $key => $needle){
                    $query->where(function($query) use ($needle, $haystacks){
                        foreach($haystacks as $key => $haystack){
                            $clause = $key == 0 ? 'where' : 'orWhere';
                            $query->$clause($haystack, "LIKE", '%'.$needle.'%');
                        }
                    });
                }
            });

            return $this;
        });

        Sheet::macro('setAlignment', function(Sheet $sheet, string $cellRange, string $vertical, string $horizontal){
            $sheet->getDelegate()
                ->getStyle($cellRange)
                ->getAlignment()
                ->setVertical($vertical);

            $sheet->getDelegate()
                ->getStyle($cellRange)
                ->getAlignment()
                ->setHorizontal($horizontal);

        });

        Sheet::macro('setBorders', function(Sheet $sheet, string $cellRange, string $hexcolor, string $style){
            $sheet->getDelegate()
                ->getStyle($cellRange)
                ->getBorders()
                ->getAllBorders()
                ->setBorderStyle($style)
                ->getColor()
                ->setARGB($hexcolor);
        });

        Sheet::macro('setRowHeight', function(Sheet $sheet, int $firstRow, int $lastRow, int $height){
            for($i = $firstRow; $i <= $lastRow; $i++){
                $sheet->getDelegate()
                    ->getRowDimension($i)
                    ->setRowHeight($height);
            }
        });

        Sheet::macro('setFont', function(Sheet $sheet, string $cellRange, string $fontName, string $color, int $size, string $bold){
            $sheet->getDelegate()
                ->getStyle($cellRange)
                ->getFont()
                ->setName($fontName)
                ->setSize($size)
                ->setBold($bold)
                ->getColor()
                ->setARGB($color);
        });

        Sheet::macro('setBackground', function(Sheet $sheet, string $cellRange, string $color, string $type){
            $sheet->getDelegate()
                ->getStyle($cellRange)
                ->getFill()
                ->setFillType($type)
                ->getStartColor()
                ->setARGB($color);
        });
    }
}
