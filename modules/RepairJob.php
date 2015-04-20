<?php
/**
 */
require_once('init.php');

/**
 * Describe What this module does
 */
///The main title under which its items will be grouped
function RepairJob_getMainMenu()
{
  require_once('session.php');
  
  return 'Action';
}

///The items to display under that menu
function RepairJob_getMainMenuItems()
{
  require_once('session.php');
  $menus = null;

  $menus['submenu'][0]['url']  = "RepairJob";
  $menus['submenu'][0]['name'] = "New Job";
  $menus['submenu'][0]['icon'] = 'repairjob.png';

  $menus['submenu'][1]['url']  = "RepairJob.list";
  $menus['submenu'][1]['name'] = "Old Jobs";
  $menus['submenu'][1]['icon'] = 'repairjob.png';

  return $menus;
}

//messages/ info blocks from this module for main page
function RepairJob_infoblock()
{
  require_once('session.php');
  return array( 'title' => gettext('RepairJob'), 'content' => gettext('Some text by RepairJob' ));
}

//Something for Dashboard
function RepairJob_Dashboard()
{
  require_once('session.php');
  global $db, $template;  

  if(!checkPermission('viewdashboard'))
    return null;
  else
  {
    if(isset($_GET['viewdetail'])) //have we been asked full detail, then render anything
      return $template->display('modules/RepairJob-Dashboard.html');
    else //send a brief line of info to dashboard
      return array('title' => 'RepairJob Dashboard','brief'=>'Something for dashboard', 'type' => 'Normal');
  }
}

//The default action does all
function RepairJob_default()
{
  require_once('session.php');
  require_once('lib/adoDBPager.php');
  require_once('lib/autoform.php');

  global $db, $template, $SmartyTemplateDir, $SmartyTheme;  


  //Product Models
  $productmodels = null;
  foreach($db->getAssoc("select id, type from stockitemtypes where parentcategoryid=0 order by type") as $id => $type)
    $productmodels[$type] = $db->getAssoc("select id, type from stockitemtypes where parentcategoryid=$id order by type");


  $problemsreportedtypes = null;
  foreach($db->getAssoc("select id, type from problemsreportedtypes") as $id => $type)
    $problemsreportedtypes .= "<option value=\'$id\'>$type</option>";

  $productaccessories = null;
  foreach($db->getAssoc("select id, type from productaccessories") as $id => $type)
    $productaccessories .= "<option value=\'$id\'>$type</option>";
  
  //Definition of form
  $form = array(
    'dbtable' => 'repairjobs',   //data base table name
    'title' => 'Add New jobs',  //Title of this form
    'name' => 'repairjobs', //name of form, no spaces
    'action' => '?to=RepairJob',  //form action, i.e submission URL
    'method' => 'POST', //method
    'savebuttonlabel' => 'Save', //lable of save button
    'resetbuttonlabel' => 'Reset', //label of reset button, leave blank if not required
    'fields' => array(
      array('type' => 'text', 'name' => 'customername', 'value' => '', 'label' => 'Customer', 'tooltip' => 'Name of Customer', 'required' => true, 'inputtype' => 'text', 'size'=>"40", 'maxlength'=>"60", 'dbcolumn'=>'name'),
      array('type' => 'textarea', 'name' => 'address', 'value' => '', 'label' => 'Address', 'required' => false, 'dbcolumn'=>'address'),
      array('type' => 'text', 'name' => 'phone', 'value' => '', 'label' => 'Phone', 'tooltip' => 'Mobile Number of Customer', 'required' => true, 'inputtype' => 'number', 'size'=>"40", 'maxlength'=>"20", 'dbcolumn'=>'mobileno'),
      array('type' => 'text', 'name' => 'email', 'value' => '', 'label' => 'Email Address', 'tooltip' => 'Email Address of Customer', 'required' => true, 'inputtype' => 'text', 'size'=>"40", 'maxlength'=>"30", 'dbcolumn'=>'email'),

      array('type' => 'text', 'name' => 'inwarddate', 'value' => date('Y-m-d H:i:s'), 'label' => 'In Date', 'tooltip' => 'Date as YYYY-MM-DD H:m:s', 'required' => true, 'inputtype' => 'datetime', 'size'=>"40", 'maxlength'=>"30", 'dbcolumn'=>'inwarddate'),

      array('type'   => 'select', 'name' => 'productmodelid',
            'value' => $productmodels,
            'label' => 'Product Model', 'required' => true,  'dbcolumn'=>'productmodelid'),
      array('type' => 'text', 'name' => 'productserialnumber', 'value' => '', 'label' => 'Product Serial Number', 'tooltip' => 'Serial Number of Product', 'required' => true, 'inputtype' => 'text', 'size'=>"40", 'maxlength'=>"128", 'dbcolumn'=>'productserialnumber'),
      array('type'   => 'select', 'name' => 'productconditionid',
            'value' => $db->getAssoc("select id, type from productconditiontypes order by id"),
            'label' => 'Condition', 'required' => true,  'dbcolumn'=>'productconditionid'),

      array('type' => 'text', 'name' => 'name', 'value' => date('Y-m-d'), 'label' => 'Date of Purchase', 'tooltip' => 'Date as YYYY-MM-DD', 'required' => true, 'inputtype' => 'date', 'size'=>"40", 'maxlength'=>"30", 'dbcolumn'=>'dateofpurchase'),
      //HTML Dynamic Fields
      //Problems as described by customer
      array('type' => 'html', 'html' => '<script>$(document).ready(function() {
    var max_fields      = 10; //maximum input boxes allowed
    var wrapper         = $(".input_fields_wrap"); //Fields wrapper
    var add_button      = $(".add_field_button"); //Add button ID
   
    var x = 1; //initlal text box count
    $(add_button).click(function(e){ //on add input button click
        e.preventDefault();
        if(x < max_fields){ //max input box allowed
            x++; //text box increment
            $(wrapper).append(\'<div>Part: <select name="problemsreportedtypes[]">'.$problemsreportedtypes.'</select> &nbsp; Problem: <input type="text" name="problemsreporteddescriptions[]"/><a href="#" class="remove_field"><img width="42" src="'.$SmartyTemplateDir.$SmartyTheme.'icons/delete.png" alt="X"></a></div>\'); //add input box
        }
    });
   
    $(wrapper).on("click",".remove_field", function(e){ //user click on remove text
        e.preventDefault(); $(this).parent(\'div\').remove(); x--;
    })
});</script>
<div class="input_fields_wrap">
  <button class="add_field_button">Add Customer Reported Problems</button>
</div>'),

      //Problems as diagnosed by collection centre
      array('type' => 'html', 'html' => '<script>$(document).ready(function() {
    var max_fields1      = 20; //maximum input boxes allowed
    var wrapper1         = $(".input_fields_wrap1"); //Fields wrapper
    var add_button1      = $(".add_field_button1"); //Add button ID
   
    var x1 = 1; //initlal text box count
    $(add_button1).click(function(e){ //on add input button click
        e.preventDefault();
        if(x1 < max_fields1){ //max input box allowed
            x1++; //text box increment
            $(wrapper1).append(\'<div>Part: <select name="productaccessories[]">'.$productaccessories.'</select> &nbsp; Working: <input type="checkbox" name="productaccessorycondition[]" /><a href="#" class="remove_field1"><img width="42" src="'.$SmartyTemplateDir.$SmartyTheme.'icons/delete.png" alt="X"></a></div>\'); //add input box
        }
    });
   
    $(wrapper1).on("click",".remove_field1", function(e){ //user click on remove text
        e.preventDefault(); $(this).parent(\'div\').remove(); x--;
    })
});</script>
<div class="input_fields_wrap1">
  <button class="add_field_button1">Add Diagnosed Problems</button>
</div>')
    ),

  );

  //this will automatically check if the form in question has been submitted, so just call
  //$db->debug=true;
  if(checkIfFormSubmitted($form, $_POST))
  {
    $status = @saveClientFormData($form, $_POST, array('modified' => time(), 'modifiedby' => $_SESSION['userid']), array('message' => "Added new Repair Job from ".addslashes(strip_tags($_POST['name'])), 'type' => 'Repair Job'));
    //$status = _RepairJob_save($_POST);
    if( is_array($status))
        $template->assign("message", implode('<br />', $status));
    else if ($status == true)
        $template->assign("message", 'Data saved');
  }
/*
  $status = @saveClientFormData($form, $_POST, array('modified' => time(), 'modifiedby' => $_SESSION['userid']), array('message' => "Added new Repair Job from ".addslashes(strip_tags($_POST['name'])), 'type' => 'Repair Job'), '_RepairJob_save');
  if( is_array($status))
      $template->assign("message", implode('<br />', $status));
  else if ($status == true)
      $template->assign("message", 'Data saved');
*/
  $template->assign("form", renderClientForm($form));
  $template->assign('count', $db->getOne("select count(*) from repairjobs"));

  $template->display('modules/RepairJob.html');
}

function _RepairJob_save($data)
{
  //print_r($data);
  $m = null;

  foreach($data as $id => $v)
  {
    switch($id)
    {
      case "problemsreportedtypes":
        foreach($v as $vid => $vv)
          $m[] = "$id [$vid] => $vv";
        break;
      case "problemsreporteddescriptions":
        foreach($v as $vid => $vv)
          $m[] = "$id [$vid] => $vv";
        break;
      case "productaccessories":
        foreach($v as $vid => $vv)
          $m[] = "$id [$vid] => $vv";
        break;
      case "productaccessorycondition":
        foreach($v as $vid => $vv)
          $m[] = "$id [$vid] => $vv";
        break;

      default:
        $m[] = "$id = $v";
    }
  }
  return $m;
}

function RepairJob_list()
{
  require_once('session.php');
  require_once('lib/adoDBPager.php');
  require_once('lib/autoform.php');

  global $db, $template;  

  $sql = "SELECT rj.id, inwarddate as 'Date Received', dateofdeposit as 'Deposite Date', dateofreturn as 'Return', outwarddate as 'Delivery Date', st.type as 'Product Model', pc.type as 'Condition', `name` as 'Customer', `address` as 'Address', `mobileno` as 'Mobile', `email` as 'Email' FROM `repairjobs` rj, stockitemtypes st, productconditiontypes pc WHERE pc.id=rj.productconditionid and st.id=rj.productmodelid ORDER BY `inwarddate` DESC";

  $pager = new adoDBPager($db,$sql);
  $template->assign("pager", $pager->Render(70));
  $template->assign('count', $db->getOne("select count(*) from repairjobs"));

  $template->display('modules/RepairJob.html');
}
?>
