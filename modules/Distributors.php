<?php
/**
 */
require_once('init.php');

/**
 * Describe What this module does
 */
///The main title under which its items will be grouped
function Distributors_getMainMenu()
{
  require_once('session.php');
  
  return 'Action';
}

///The items to display under that menu
function Distributors_getMainMenuItems()
{
  require_once('session.php');
  $menus = null;

  $menus['submenu'][0]['url']  = "Distributors";
  $menus['submenu'][0]['name'] = "Manage Distributors";
  $menus['submenu'][0]['icon'] = 'chat.png';

  return $menus;
}

//The default action does all
function Distributors_default()
{
  require_once('session.php');
  require_once('lib/adoDBPager.php');
  require_once('lib/autoform.php');

  global $db, $template;  


 //Definition of form
  $form = array(
    'dbtable' => 'distributors',   //data base table name
    'title' => 'Add New Distributor',  //Title of this form
    'name' => 'distributors', //name of form, no spaces
    'action' => '',  //form action, i.e submission URL
    'method' => 'POST', //method
    'savebuttonlabel' => 'Save', //lable of save button
    'resetbuttonlabel' => 'Reset', //label of reset button, leave blank if not required
    'fields' => array(
      array('type' => 'text', 'name' => 'name', 'value' => '', 'label' => 'Distributor', 'tooltip' => 'Name of Distributor Firm/ Company', 'required' => true, 'inputtype' => 'text', 'size'=>"40", 'maxlength'=>"30", 'dbcolumn'=>'name'),
      array('type' => 'textarea', 'name' => 'address', 'value' => '', 'label' => 'Address', 'required' => false, 'dbcolumn'=>'address'),
      array('type' => 'text', 'name' => 'phone', 'value' => '', 'label' => 'Phone', 'tooltip' => 'Phone Number of Distributor', 'required' => true, 'inputtype' => 'text', 'size'=>"40", 'maxlength'=>"20", 'dbcolumn'=>'phone'),
      array('type' => 'text', 'name' => 'email', 'value' => '', 'label' => 'Email Address', 'tooltip' => 'Email Address of Distributor', 'required' => true, 'inputtype' => 'text', 'size'=>"40", 'maxlength'=>"30", 'dbcolumn'=>'email'),

      array('type' => 'text', 'name' => 'contactperson', 'value' => '', 'label' => 'Contact Person', 'tooltip' => 'Name of Contact Person', 'required' => true, 'inputtype' => 'text', 'size'=>"40", 'maxlength'=>"30", 'dbcolumn'=>'contactperson'),
      array('type' => 'text', 'name' => 'mobile', 'value' => '', 'label' => 'Contact Person Mobile', 'tooltip' => 'Mobile Number of Contact Person', 'required' => true, 'inputtype' => 'number', 'size'=>"40", 'maxlength'=>"12", 'dbcolumn'=>'mobile'),
      array('type' => 'text', 'name' => 'tin', 'value' => '', 'label' => 'TIN', 'tooltip' => 'TIN of Distributor', 'required' => true, 'inputtype' => 'text', 'size'=>"40", 'maxlength'=>"20", 'dbcolumn'=>'tin'),
    )
  );

  //this will automatically check if the form in question has been submitted, so just call
  //$db->debug=true;
  $status = @saveClientFormData($form, $_POST, array('modified' => time()), array('message' => "Added new Distributor ".$_POST['name'], 'type' => 'Distributors'));
  if( is_array($status))
      $template->assign("message", implode('<br />', $status));
  else if ($status == true)
      $template->assign("message", 'Data saved');

  $sql = "SELECT id, `name` as 'Distributor', `address` as 'Address', `contactperson` as 'Contact Person', `mobile` as 'Mobile', `phone` as 'Phone', `email` as 'Email', tin as 'TIN' FROM `distributors` ORDER BY `name`";

  $pager = new adoDBPager($db,$sql);
  $template->assign("pager", $pager->Render(70));
  $template->assign("form", renderClientForm($form));
  $template->assign('count', $db->getOne("select count(*) from distributors"));

  $template->display('modules/Distributors.html');
}
?>
