<?php
/**
 */
require_once('init.php');

/**
 * Describe What this module does
 */
///The main title under which its items will be grouped
function DistributorInvoices_getMainMenu()
{
  require_once('session.php');
  
  return 'Action';
}

///The items to display under that menu
function DistributorInvoices_getMainMenuItems()
{
  require_once('session.php');
  $menus = null;

  $menus['submenu'][0]['url']  = "DistributorInvoices";
  $menus['submenu'][0]['name'] = "Distributor Invoices";
  $menus['submenu'][0]['icon'] = 'writingpad.png';

  return $menus;
}

//messages/ info blocks from this module for main page
function DistributorInvoices_infoblock()
{
  require_once('session.php');

  global $db, $template;  
  //check how many invoiced entered today
$db->debug = 1;
  $count = $db->getRow("select count(*) as 'count', sum(amount) as 'amt' from distributorinvoices where invoicedate between '".date('Y-m-d 0:0:0')."' and NOW()");
  if($count)
    return array( 'title' => gettext('Invoices'), 'content' => gettext("Invoices for Today").': '.$count['count'].' for amount '.$count['amt']);
  else
    return null;
}

//The default action does all
function DistributorInvoices_default()
{
  require_once('session.php');
  require_once('lib/adoDBPager.php');
  require_once('lib/autoform.php');

  global $db, $template;  


 //Definition of form
  $form = array(
    'dbtable' => 'distributorinvoices',   //data base table name
    'title' => 'Add New Invoice',  //Title of this form
    'name' => 'distributorinvoices', //name of form, no spaces
    'action' => '',  //form action, i.e submission URL
    'method' => 'POST', //method
    'savebuttonlabel' => 'Save', //lable of save button
    'resetbuttonlabel' => 'Reset', //label of reset button, leave blank if not required
    'fields' => array(
      array('type'   => 'select', 'name' => 'distributorid',
            'value' => $db->getAssoc("select id, name from distributors order by name"),
            'label' => 'Distributor', 'required' => true,  'dbcolumn'=>'distributorid'),
      array('type' => 'text', 'name' => 'invoicenumber', 'value' => '', 'label' => 'Invoice No', 'required' => true, 'inputtype' => 'text', 'size'=>"40", 'maxlength'=>"30", 'dbcolumn'=>'invoicenumber'),
      array('type' => 'text', 'name' => 'name', 'value' => date('Y-m-d'), 'label' => 'Invoice Date', 'tooltip' => 'Date as YYYY-MM-DD', 'required' => true, 'inputtype' => 'date', 'size'=>"40", 'maxlength'=>"30", 'dbcolumn'=>'invoicedate'),

      array('type' => 'textarea', 'name' => 'description', 'value' => '', 'label' => 'Description', 'tooltip' => 'Optional Description of Invoice', 'required' => false, 'dbcolumn'=>'description'),
      array('type' => 'text', 'name' => 'noofitems', 'value' => '', 'label' => 'No of Items in Invoice', 'tooltip' => 'Total No of Items that have been invoiced', 'required' => true, 'inputtype' => 'number', 'size'=>"40", 'maxlength'=>"4", 'dbcolumn'=>'noofitems'),
      array('type' => 'text', 'name' => 'amount', 'value' => '', 'label' => 'Invoiced Amount', 'tooltip' => 'Total Invoiced Amount', 'required' => true, 'inputtype' => 'number', 'size'=>"40", 'maxlength'=>"12", 'dbcolumn'=>'amount')
    )
  );

  //this will automatically check if the form in question has been submitted, so just call
  //$db->debug=true;
  $status = @saveClientFormData($form, $_POST, array('modified' => time()), array('message' => "Added new invoice ".$_POST['invoicenumber'], 'type' => 'Invoice'));
  if( is_array($status))
      $template->assign("message", implode('<br />', $status));
  else if ($status == true)
      $template->assign("message", 'Data saved');

  $sql = "SELECT  di.id, d.name as 'Distributor', `invoicenumber` as 'Inv. No', `invoicedate` as 'Date', `description` as 'Description', `noofitems` as 'Items in Invoice', `amount` as 'Invoiced Amount', (select count(*) from stockitems where distributorinvoiceid=di.id) as 'Items to Stock', (select sum(bp) from stockitems where distributorinvoiceid=di.id) as 'Item Value', ROUND((select SUM(bp*(1+tr.taxrate/100)) from stockitems si, taxrates tr WHERE si.taxrateid=tr.id and si.distributorinvoiceid=di.id),2) as 'Item Value w tax' FROM `distributorinvoices` di, distributors d WHERE d.id=di.distributorid  ORDER BY `invoicedate` DESC";

  $links = array(
    0 => array('label' => _t("Stock Item"), 'name' => _t("Add/ View"), 'link' => "?to=StockItems.addItemForInvoice&invoice")
  );
  $pager = new adoDBPager($db,$sql);
  $template->assign("pager", $pager->Render(70, $links));
  $template->assign("form", renderClientForm($form));
  $template->assign('count', $db->getOne("select count(*) from distributorinvoices"));

  $template->display('modules/DistributorInvoices.html');
}
?>
