<?php
/**
 */
require_once('init.php');

/**
 * Describe What this module does
 */
///The main title under which its items will be grouped
function StockItems_getMainMenu()
{
  require_once('session.php');
  
  return 'Action';
}

///The items to display under that menu
function StockItems_getMainMenuItems()
{
  require_once('session.php');
  $menus = null;

  $menus['submenu'][0]['url']  = "StockItems";
  $menus['submenu'][0]['name'] = "Stock";
  $menus['submenu'][0]['icon'] = 'chart.png';

  return $menus;
}

//messages/ info blocks from this module for main page
function StockItems_infoblock()
{
  require_once('session.php');

  global $db, $template;  
  //check how many invoiced entered today
$db->debug = 1;
  $count = $db->getRow("select count(*) as 'count', sum(amount) as 'amt' from StockItems where invoicedate between '".date('Y-m-d 0:0:0')."' and NOW()");
  if($count)
    return array( 'title' => gettext('Stock'), 'content' => gettext("Current Stock").': '.$count['count'].' for amount '.$count['amt']);
  else
    return null;
}

function StockItems_default()
{
  require_once('session.php');
  require_once('lib/adoDBPager.php');
  global $db, $template;  

  $sql = "SELECT si.id, `itemname` as 'Item', `type` as 'Type', `sku` as 'SKU', `barcode` as 'Barcode', `description` as 'Description', `bp` as 'Base Price', `mrp` as 'MRP', quantity as 'In Qty', (select count(*) from salesinvoiceitems where stockitemid=si.id) 'Sale Qty'
FROM `stockitems` si, stockitemtypes st WHERE si.stockitemtypeid=st.id"; //distributorinvoiceid

  $pager = new adoDBPager($db,$sql);
  $template->assign("pager", $pager->Render(100));
  $template->assign('count', $db->getOne("select count(*) from stockitems"));

  $template->display('modules/StockItems.html');  
}

//adds items in stock from an invoice
function StockItems_addItemForInvoice()
{
  require_once('session.php');
  require_once('lib/adoDBPager.php');
  require_once('lib/autoform.php');

  global $db, $template;  

  $iid = isset($_GET['invoiceid'])?(int)$_GET['invoiceid']:exit(1);
  $distributor = $db->getRow("select invoicenumber, invoicedate, d.name from distributorinvoices di, distributors d where di.distributorid=d.id and di.id='$iid'");
  if(!$distributor) return;

  $template->assign('distributor', $distributor);
  //Definition of form
  //make barcode as last item to ask for, will submit the form
  $form = array(
    'dbtable' => 'stockitems',   //data base table name
    'title' => 'Add New Item',  //Title of this form
    'name' => 'StockItems', //name of form, no spaces
    'action' => "?to=StockItems.addItemForInvoice&invoiceid=$iid",  //form action, i.e submission URL
    'method' => 'POST', //method
    'savebuttonlabel' => 'Save', //lable of save button
    'resetbuttonlabel' => 'Reset', //label of reset button, leave blank if not required
    'fields' => array(
      array('type'   => 'select', 'name' => 'stockitemtypeid',
            'value' => $db->getAssoc("select id, CONCAT((select type from stockitemtypes where id=st.parentcategoryid),' - ',type) from stockitemtypes st where parentcategoryid!=0 order by parentcategoryid, type"),
            'label' => 'Type of Item', 'required' => true,  'dbcolumn'=>'stockitemtypeid'),
      array('type' => 'text', 'name' => 'itemname', 'value' => '', 'tooltip' => 'Brand Name of Item', 'label' => 'Item', 'required' => false, 'inputtype' => 'text', 'size'=>"40", 'maxlength'=>"64", 'dbcolumn'=>'itemname'),
      array('type' => 'text', 'name' => 'sku', 'value' => '', 'label' => 'SKU', 'required' => false, 'inputtype' => 'text', 'size'=>"40", 'maxlength'=>"128", 'dbcolumn'=>'sku'),
      array('type' => 'text', 'name' => 'bp', 'value' => '', 'label' => 'Base Price', 'required' => false, 'inputtype' => 'number', 'size'=>"40", 'maxlength'=>"20", 'dbcolumn'=>'bp'),
      array('type'   => 'select', 'name' => 'taxrateid',
            'value' => $db->getAssoc("select id, description from taxrates where NOW() between validfrom and validto order by id"),
            'label' => 'Tax Rate', 'required' => true,  'dbcolumn'=>'taxrateid'),

      array('type' => 'text', 'name' => 'mrp', 'value' => '', 'label' => 'Max Retail Price', 'required' => true, 'inputtype' => 'number', 'size'=>"40", 'maxlength'=>"20", 'dbcolumn'=>'mrp'),
      array('type' => 'text', 'name' => 'quantity', 'value' => '', 'label' => 'Quantity', 'required' => true, 'inputtype' => 'number', 'size'=>"40", 'maxlength'=>"5", 'dbcolumn'=>'quantity'),

      array('type' => 'text', 'name' => 'barcode', 'value' => '', 'label' => 'Barcode', 'required' => false, 'inputtype' => 'text', 'size'=>"40", 'maxlength'=>"128", 'dbcolumn'=>'barcode'),

      array('type' => 'textarea', 'name' => 'description', 'value' => '', 'label' => 'Description', 'tooltip' => 'Optional: Description of Item', 'required' => false, 'dbcolumn'=>'description'),
    )
  );

  //this will automatically check if the form in question has been submitted, so just call
  //$db->debug=true;
  $status = @saveClientFormData($form, $_POST, array('distributorinvoiceid' => $iid, 'modified' => time()), array('message' => "Added new invoice item".$_POST['itemname'], 'type' => 'Stock Items'));
  if( is_array($status))
      $template->assign("message", implode('<br />', $status));
  else if ($status == true)
      $template->assign("message", 'Data saved');

  $sql = "SELECT si.id, `itemname` as 'Item', `type` as 'Type', `sku` as 'SKU', `barcode` as 'Barcode', si.`description` as 'Description', `bp` as 'BP', tr.description as 'Tax', ROUND((bp*(1+tr.taxrate/100)),2) as 'DP', `mrp` as 'MRP', quantity as 'Qty' 
FROM `stockitems` si, stockitemtypes st, taxrates tr WHERE si.taxrateid=tr.id and si.stockitemtypeid=st.id and distributorinvoiceid='$iid'";

  $pager = new adoDBPager($db,$sql);
  $template->assign("pager", $pager->Render(70));
  $template->assign("form", renderClientForm($form));
  $template->assign('count', $db->getOne("select count(*) from stockitems where distributorinvoiceid='$iid'"));

  $template->display('modules/StockItems-addItemForInvoice.html');
}
?>
