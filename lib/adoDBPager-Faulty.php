<?php
require_once(_ABS_APPLICATION_PATH.'lib/adodb/adodb-pager.inc.php');

class adoDBPager extends ADODB_Pager
{
  var $links;
  var $getString;
  var $tableid;

  function RenderGrid()
  {
    global $gSQLBlockRows; // used by rs2html to indicate how many rows to display
    require_once(_ABS_APPLICATION_PATH.'lib/eventRStohtml-Faulty.inc.php');

    //require_once('lib/userRStohtml.inc.php');
    //include_once(ADODB_DIR.'/tohtml.inc.php');
    ob_start();
    $gSQLBlockRows = $this->rows;

    userrs2html($this->rs, $this->links,
    $this->gridAttributes,$this->gridHeader,$this->htmlSpecialChars, true, $this->tableid);
    $s = ob_get_contents();
    ob_end_clean();
    return $s;
  }

   function RenderLayout($header,$grid,$footer,$attributes='width=100% border=1')
   {
     return "<table  cellspacing=0 class='aodb_table_list'  ".$attributes."><tr><th><center>".
                      $header.
            "</center></th></tr><tr><td>".
                      $grid.
            "</td></tr><tr><th><div class=number><small>".
                      $footer.
            "</small></div></th></tr></table>";
   }

   function setTableID($thetableid)
   {
     $this->tableid = $thetableid;
   }

   function Render($rows=10, $links = null, $getvars = null)
   {
     //Add get vars in the get string used by next/ last etc links
     if(!$getvars)
     {
      global $_GET;
      $getvars = $_GET;
     }
     $this->getString = null;
    foreach($getvars as $key => $val)
    {
      switch($key)
      {
        case ($this->id."_next_page"):
          break;
        default:
          $this->getString .= "$key=$val&";
      }
    }
    $this->links = $links;
     //$this->first = '<code>'._FIRST.'</code>';
     //$this->prev  = '<code>'._PREV.'</code>';
     //$this->next  = '<code>'._NEXT.'</code>';
     //$this->last  = '<code>'._LAST.'</code>';
     $this->gridAttributes = 'width=100% border=0 class=oddrowbg';
     //$this->id = 'gw';
     $this->showPageLinks = false;

     global $ADODB_COUNTRECS;
     $this->rows = $rows;

     if ($this->db->dataProvider == 'informix') $this->db->cursorType = IFX_SCROLL;

     $savec = $ADODB_COUNTRECS;
     if ($this->db->pageExecuteCountRows) $ADODB_COUNTRECS = true;
     if ($this->cache)
       $rs = &$this->db->CachePageExecute($this->cache,$this->sql,$rows,$this->curr_page);
     else
       $rs = &$this->db->PageExecute($this->sql,$rows,$this->curr_page);
             $ADODB_COUNTRECS = $savec;

     $this->rs = &$rs;
     if (!$rs) 
     {
        return "<b>Query failed: $this->sql</b>";
     }
     if (!$rs->EOF && (!$rs->AtFirstPage() || !$rs->AtLastPage())) 
        $header = $this->RenderNav();
     else
        $header = "&nbsp;";

     $grid = $this->RenderGrid();
     $footer = $this->RenderPageCount();
     $rs->Close();
     $this->rs = false;

     return $this->RenderLayout($header,$grid,$footer);
   }

   function RenderPageCount()
   {
     if (!$this->db->pageExecuteCountRows) return '';
       $lastPage = $this->rs->LastPageNo();
     if ($lastPage == -1) $lastPage = 1; // check for empty rs.
     if ($this->curr_page > $lastPage) $this->curr_page = 1;
     return "$this->page ".$this->curr_page."/".$lastPage."";
   }
        
  //---------------------------
  // Display link to first page
  function Render_First($anchor=true)
  {
    global $PHP_SELF;
    if ($anchor) 
    {
    ?>
      <a href="<?php echo $PHP_SELF,'?',$this->getString,$this->id;?>_next_page=1"><?php echo $this->first;?></a> &nbsp; 
    <?php
    } 
    else 
    {
      print "$this->first &nbsp; ";
    }
  }
        
  //--------------------------
  // Display link to next page
  function render_next($anchor=true)
  {
    global $PHP_SELF;

    if ($anchor) 
    {
    ?>
      <a href="<?php echo $PHP_SELF,'?',$this->getString,$this->id,'_next_page=',$this->rs->AbsolutePage() + 1 ?>"><?php echo $this->next;?></a> &nbsp; 
    <?php
    } 
    else 
    {
      print "$this->next &nbsp; ";
    }
  }
        
  //------------------
  // Link to last page
  // 
  // for better performance with large recordsets, you can set
  // $this->db->pageExecuteCountRows = false, which disables
  // last page counting.
  function render_last($anchor=true)
  {
    global $PHP_SELF;
        
    if (!$this->db->pageExecuteCountRows) return;

    if ($anchor)
    {
    ?>
      <a href="<?php echo $PHP_SELF,'?',$this->getString,$this->id,'_next_page=',$this->rs->LastPageNo() ?>"><?php echo $this->last;?></a> &nbsp; 
    <?php
    } 
    else 
    {
      print "$this->last &nbsp; ";
    }
  }
        
  //---------------------------------------------------
  // original code by "Pablo Costa" <pablo@cbsp.com.br> 
  function render_pagelinks()
  {
    global $PHP_SELF;
    $pages        = $this->rs->LastPageNo();
    $linksperpage = $this->linksPerPage ? $this->linksPerPage : $pages;
    for($i=1; $i <= $pages; $i+=$linksperpage)
    {
      if($this->rs->AbsolutePage() >= $i)
      {
        $start = $i;
      }
    }
    $numbers = '';
    $end = $start+$linksperpage-1;
    $link = $this->id . "_next_page";
    if($end > $pages) $end = $pages;
                        
                        
    if ($this->startLinks && $start > 1) 
    {
      $pos = $start - 1;
      $numbers .= "<a href=$PHP_SELF?".$this->getString."$link=$pos>$this->startLinks</a>  ";
    } 
                        
    for($i=$start; $i <= $end; $i++) 
    {
      if ($this->rs->AbsolutePage() == $i)
        $numbers .= "<font color=$this->linkSelectedColor><b>$i</b></font>  ";
      else 
        $numbers .= "<a href=$PHP_SELF?".$this->getString."$link=$i>$i</a>  ";

    }
    if ($this->moreLinks && $end < $pages) 
      $numbers .= "<a href=$PHP_SELF?".$this->getString."$link=$i>$this->moreLinks</a>  ";
    print $numbers . ' &nbsp; ';
  }
  // Link to previous page
  function render_prev($anchor=true)
  {
    global $PHP_SELF;
    if ($anchor) 
    {
    ?>
      <a href="<?php echo $PHP_SELF,'?',$this->getString,$this->id,'_next_page=',$this->rs->AbsolutePage() - 1 ?>"><?php echo $this->prev;?></a> &nbsp; 
    <?php 
    } 
    else 
    {
      print "$this->prev &nbsp; ";
    }
  }
}
?>
