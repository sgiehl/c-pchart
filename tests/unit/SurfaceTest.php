<?php

namespace Test\CpChart;

use Codeception\Test\Unit;
use CpChart\Chart\Image;
use CpChart\Chart\Surface;
use UnitTester;

class SurfaceTest extends Unit
{
    /**
     * @var UnitTester
     */
    protected $tester;

    public function testChartRender()
    {
        $image = new Image(400, 400);
        $settings = ["R" => 179, "G" => 217, "B" => 91, "Dash" => 1, "DashR" => 199, "DashG" => 237, "DashB" => 111];
        $image->drawFilledRectangle(0, 0, 400, 400, $settings);
        $settings = ["StartR" => 194, "StartG" => 231, "StartB" => 44, "EndR" => 43, "EndG" => 107, "EndB" => 58, "Alpha" => 50];
        $image->drawGradientArea(0, 0, 400, 400, DIRECTION_VERTICAL, $settings);
        $image->drawGradientArea(0, 0, 400, 20, DIRECTION_VERTICAL, ["StartR" => 0, "StartG" => 0, "StartB" => 0, "EndR" => 50, "EndG" => 50, "EndB" => 50, "Alpha" => 100]);
        $image->drawRectangle(0, 0, 399, 399, ["R" => 0, "G" => 0, "B" => 0]);
        $image->setFontProperties(["FontName" => "Silkscreen.ttf", "FontSize" => 6]);
        $image->drawText(10, 13, "pSurface() :: 2D surface charts", ["R" => 255, "G" => 255, "B" => 255]);
        $image->setGraphArea(20, 40, 380, 380);
        $image->drawFilledRectangle(20, 40, 380, 380, ["R" => 255, "G" => 255, "B" => 255, "Surrounding" => -200, "Alpha" => 20]);
        $image->setShadow(true, ["X" => 1, "Y" => 1]);
        $mySurface = new Surface($image);
        $mySurface->setGrid(20, 20);
        $image->setFontProperties(["FontName" => "pf_arma_five.ttf", "FontSize" => 6]);
        $mySurface->writeXLabels();
        $mySurface->writeYLabels();
        for ($i = 0; $i <= 50; $i++) {
            $mySurface->addPoint(rand(0, 20), rand(0, 20), rand(0, 100));
        }
        $mySurface->computeMissing();
        $mySurface->drawSurface(["Border" => true, "Surrounding" => 40]);

        $filename = $this->tester->getOutputPathForChart('drawSurface.png');
        $image->render($filename);
        $image->stroke();

        $this->tester->seeFileFound($filename);
    }
}
