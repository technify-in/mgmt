<?php
/**
    Example Usage:
    <img src="barchart.php?t=Election Results&w=400&h=400&x=SAD,INC,BJP,BSP,SAD(M),Me,Others&y=90,20,10,3,2,31,19&percent">

    t => title of chart
    w => Width of Image
    h => Height of Image
    x => x axis data, comma seperated
    y => y axis data, comma seperated
    percent => If set bar chart is in percent, else absolute data
*/
/**
 * Copyright (C) 2007  Ajay Pal Singh Atwal
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 */

	include("Image/Graph.php");

    $title  = isset($_GET['t'])?$_GET['t']:null;
    $xdata  = isset($_GET['x'])?explode(',', $_GET['x']):exit(1);
    $ydata  = isset($_GET['y'])?explode(',', $_GET['y']):exit(1);
    $width  = isset($_GET['w'])?(int)$_GET['w']:400;
    $height = isset($_GET['h'])?(int)$_GET['h']:300;
    $type_percentage = isset($_GET['percent'])?true:false;
    $type_shadow = isset($_GET['shadow'])?true:false;

    $Arial = null;
    // create the graph
    $Graph =& new Image_Graph($width, $height);

    if($title)
    {
        // add a TrueType font
        $Arial =& $Graph->addFont(new Image_Graph_Font_TTF("fonts/Vera.ttf"));
        // set the font size to 15 pixels
        $Arial->setSize(12);
        // add a title using the created font    
		    
	    // create the plotarea
	    $Graph->add(
            new Image_Graph_Layout_Vertical(
                new Image_Graph_Title($title, $Arial),
                $Plotarea = new Image_Graph_Plotarea(),
                5            
            )
        );
    }
    else
    {
        $Graph->add(
                $Plotarea = new Image_Graph_Plotarea()
        );
    }
    $colours = array(
                IMAGE_GRAPH_ORANGE,
                IMAGE_GRAPH_GREEN,
                IMAGE_GRAPH_BLUE,
                IMAGE_GRAPH_YELLOW,
                IMAGE_GRAPH_RED,
                IMAGE_GRAPH_BROWN,
                IMAGE_GRAPH_GREY,
                IMAGE_GRAPH_INDIGO,
                IMAGE_GRAPH_VIOLET,
                IMAGE_GRAPH_CYAN,
                IMAGE_GRAPH_DARKSEAGREEN,
                IMAGE_GRAPH_PURPLE,
                IMAGE_GRAPH_MAGENTA,
                IMAGE_GRAPH_PINK,
                IMAGE_GRAPH_BISQUE,
                IMAGE_GRAPH_OLIVE,
                IMAGE_GRAPH_ROSYBROWN,
                IMAGE_GRAPH_YELLOWGREEN,
                IMAGE_GRAPH_ALICEBLUE,
                IMAGE_GRAPH_BURLYWOOD
               );
			
	// create the dataset
	$Dataset =& new Image_Graph_Dataset_Trivial();
    $FillArray =& new Image_Graph_Fill_Array();

    //to work around a problem in label marking
    $xlables[0] = '';
    $xlables[0.5] = '';
    if($type_percentage)
        $sum = array_sum($ydata); //for %

    $thecount = count($xdata);

    if($type_percentage)
    {
        for($i = 0; $i < $thecount; $i++)
        {
            $Dataset->addPoint( $i+1, $ydata[$i]/$sum*100);
            $xlables[$i+1] = $xdata[$i];

            $FillArray->add(new Image_Graph_Fill_Gradient(IMAGE_GRAPH_GRAD_RADIAL, $colours[$i%count($colours)], IMAGE_GRAPH_WHITE, 200));
        }
    }
    else
    {
        for($i = 0; $i < $thecount; $i++)
        {
            $Dataset->addPoint( $i+1, $ydata[$i]);
            $xlables[$i+1] = $xdata[$i];

            $FillArray->add(new Image_Graph_Fill_Gradient(IMAGE_GRAPH_GRAD_RADIAL, $colours[$i%count($colours)], IMAGE_GRAPH_WHITE, 200));
        }
    }

    ///FIXME: fix for a problem when less than 4
    for($i = count($xlables); $i <= 4; $i++)
    {
        $Dataset->addPoint( $i+1, 0);
        $xlables[] = ' ';
    }
    $xlables[] = ' ';

    $AxisX =& $Plotarea->getAxis(IMAGE_GRAPH_AXIS_X);

    $AxisX->setDataPreprocessor(new Image_Graph_DataPreprocessor_Array($xlables));

    $AxisX->showArrow();
    $AxisFontX =& $Graph->add(new Image_Graph_Font_Vertical());
    $AxisX->setFont($AxisFontX);

//    $AxisX->setDataPreprocessor(new Image_Graph_DataPreprocessor_Sequential($xlables));
    //$AxisX->setLabelInterval(1);
    //$AxisX->showLabel(IMAGE_GRAPH_LABEL_ZERO);
    //$AxisX->forceMaximum(count($xlables)-2);

    $GridY =& $Plotarea->addGridY(new Image_Graph_Grid_Bars());  
    $GridY->setFillStyle(new Image_Graph_Fill_Gradient(IMAGE_GRAPH_GRAD_VERTICAL, IMAGE_GRAPH_WHITE, IMAGE_GRAPH_LIGHTGRAY, 100));

	// create the plot as bar chart using the dataset
	$Plot =& $Plotarea->addPlot(new Image_Graph_Plot_Bar($Dataset));
	$Plot->setFillStyle($FillArray);

/*    $Marker =& new Image_Graph_Marker_Array();
    $Graph->add($Marker);
    $Marker->add(new Image_Graph_Marker_Icon("./images/audi.png"));
    $Marker->add(new Image_Graph_Marker_Icon("./images/mercedes.png"));
    $Marker->add(new Image_Graph_Marker_Icon("./images/porsche.png"));
    $Marker->add(new Image_Graph_Marker_Icon("./images/bmw.png"));
    
    $Plot->setMarker($Marker);*/
    

    $AxisY =& $Plotarea->getAxis(IMAGE_GRAPH_AXIS_Y);
    if($type_percentage)
    {
        $AxisY->setDataPreprocessor(new Image_Graph_DataPreprocessor_Formatted("%0.0f%%"));
        $AxisY->forceMaximum(105);
        $AxisY->showArrow();
    }

    if($type_shadow)
        $Graph->showShadow();

	// output the Graph
	$Graph->done();
?>
