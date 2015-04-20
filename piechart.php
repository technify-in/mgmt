<?php
/**
 * Example Usage:
    <img src="piechart.php?t=Election Results&w=400&h=400&x=SAD,INC,BJP,BSP,SAD(M),Me,Others&y=90,20,10,3,2,31,19">

    t => title of chart
    w => Width of Image
    h => Height of Image
    x => x axis data, comma seperated
    y => y axis data, comma seperated
    shadow => enable graph shadow

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
    $type_shadow = isset($_GET['shadow'])?true:false;

	// create the graph
	$Graph =& new Image_Graph($width, $height);

    // add a title using the created font    
	// create the plotarea
    if($title)
    {
        // add a TrueType font
        $Arial =& $Graph->addFont(new Image_Graph_Font_TTF("fonts/Vera.ttf"));
        // set the font size to 15 pixels
        $Arial->setSize(10);

        $PlotArea = new Image_Graph_Layout_Vertical
                    (
                        new Image_Graph_Title($title, $Arial),
                        $Plotarea = new Image_Graph_Plotarea(),
                        5
                    );
        $Graph->add($PlotArea);
    }
    else
    {
        $Plotarea = new Image_Graph_Plotarea();
        $Graph->add($Plotarea);
    }


    $FillArray =& new Image_Graph_Fill_Array();

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

	// create the 1st dataset
	$Dataset1 =& new Image_Graph_Dataset_Trivial();
    for($i = 0; $i < count($xdata); $i++)
    {
        $Dataset1->addPoint($xdata[$i], $ydata[$i]);

        $FillArray->add(new Image_Graph_Fill_Gradient(IMAGE_GRAPH_GRAD_RADIAL, $colours[$i%count($colours)], IMAGE_GRAPH_WHITE, 200));
    }

	// create the 1st plot as smoothed area chart using the 1st dataset
    $Bars = new Image_Graph_Plot_Pie($Dataset1);
	$Plot1 =& $Plotarea->addPlot($Bars);
	$Plotarea->hideAxis();
	
	// create a Y data value marker
	$Marker =& $Plot1->add(new Image_Graph_Marker_Value());
	// fill it with white
	$Marker->setFillColor(IMAGE_GRAPH_WHITE);
	// and use black border
	$Marker->setBorderColor(IMAGE_GRAPH_BLACK);

    $PointingMarker =& $Plot1->add(new Image_Graph_Marker_Pointing_Angular(20, $Marker));
	// and use the marker on the 1st plot
	$Plot1->setMarker($PointingMarker);

	// format value marker labels as percentage values
	$Plot1->Radius = 2;
	
	$Plot1->setFillStyle($FillArray);
    if($type_shadow)
        $Graph->showShadow();

	// output the Graph
	$Graph->done();
?>