<?php

class Reporting {
  //Prefix to names of the workflow tables
  private $table = "";


  public function __construct($table) {
    $this->table = $table;
  }


  //Reporting
  //    Columns array optional
  //      Given column names from master table return only those columns at given state
  //
  //    Filters array optional
  //      shall be used for creating filter elements, user selectable
  //      If given shall be used in where clause
  //      e.g. array(
  //              'Consumer Category' => array(
  //                                      'values' => $db->getAssoc("select id, category from consumercategories"), 
  //                                      'default' => 0 //optional
  //                                    )
  //             )


  //    Other conditions columns optional
  //          array(
  //            "_maintable.consumercategoryid = 1",
  //            "`loadapplied` >= 500",
  //            "between 2013-01-01 and 2013-02-01",
  //          )

  //    TODO: order By can be handled on client side, json

  //we need reporting, ask for sql and return pager


  //open questions
  //  joins with other tables
  //  joins array optional
  //    array(
  //      table                     join condition
  //      'consumercategories c' => 'c.id=_maintable.consumercategoryid'
  //    )
  public function getResults() {
  
  }

  //renders current report as a grid
  public function renderGrid() {
  }
