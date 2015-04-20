<?php
/** PHP Auto Form Processing

    $form = array(
                'dbtable' => 'testtable',   //data base table name
                 //if updateon is set then do update else insert
                 'updateon' => array('id' => 1) //where clause of update
                'title' => 'Please Provide the Following Information',  //Title of this form
                'name' => 'helloworld', //name of form, no spaces
                'action' => '',  //form action, i.e submission URL
                'method' => 'POST', //method
                'extradata' => "class='testclass'", //any extra data to be appended to the form tag
                'savebuttonlabel' => 'Save', //lable of save button
                'resetbuttonlabel' => 'Reset', //label of reset button, leave blank if not required
                //If this a muti row autoform, else do not set
                'row_titles' => array(1 => 'April', 2 => 'May'); //how many columns for this form, key must start with an integer > 0

                  ///NOTE: Format of this array is
                    //           type:
                    //                      text field,
                    //                      select,
                    //                      textarea,
                    //                      label (This is simple a text label),
                    //                      hidden fields
                    //           name:  HTML Name and ID of the form element, no spaces allowed
                    //           value: Any initial value for this element
                    //           label: Any label to be placed before the form element
                      //           required: true/ false, if true during form validation this is marked as required
                    //            inputtype: number/ text, Allowed input type, Only for text and textarea elements
                    //             size: Size of this field
                    //             maxlength: of this field
                    //             rows: for textarea
                    //             cols: for textarea
                    //              dbcolumn: If this goes in a database, the name of corresponding database column name
                'fields' => array(
                                                array( 'type' => 'text', 'name' => 'name', 'value' => '', 'label' => 'Your Name', 'required' => true, 'inputtype' => 'text', 'size'=>"40", 'maxlength'=>"20", 'dbcolumn'=>'name'),
                                                array( 'type' => 'text', 'name' => 'age', 'value' => '', 'label' => 'Your Age', 'required' => true, 'inputtype' => 'number', 'size'=>"40", 'maxlength'=>"20", 'dbcolumn'=>'age'),
                                                array( 'type' => 'label', 'label' => 'al Information'),
                                                array(   'type' => 'select', 'name' => 'profession',
                                                                'value' => array( 0 => 'Select...', 1 => 'Self Employed', 2=> 'Office Slave'),
                                                                'label' => 'Your Profession', 'required' => true, 'inputtype' => 'number', 'dbcolumn'=>'profession'),
                                                array( 'type' => 'textarea', 'name' => 'experience', 'value' => '', 'label' => 'Your Experience', 'required' => true, 'inputtype' => 'text', 'rows'=>"10", 'cols'=>"20", 'dbcolumn'=>'experience'),
                                                array( 'type' => 'hidden', 'name' => 'testhidden', 'value' => '123')
                                        ),
            );
  */
function renderClientForm($form)
{
    global $template;
    $template->assign("form", $form);
    if(isset($form['row_labels']))
        return $template->fetch('autoform-multi.html');
    else
        return $template->fetch('autoform.html');
}

/**
        Calls the correct function, to save a single column form or a multi row form
  */
function saveClientFormData($form, $submitteddata = null, $additionaldata = null, $logentry = null, $savecallbackfunction = null)
{
    if(isset($form['row_labels']))
      return _saveClientFormDataMulti($form, $submitteddata, $additionaldata, $logentry, $savecallbackfunction);
    else
      return _saveClientFormDataSingle($form, $submitteddata, $additionaldata, $logentry, $savecallbackfunction);
}

/**
        Saves the single form data to a table
        Arguments:
                  $form: The array representing the form
                   $submitteddata: $_GET or $_POST or $_REQUEST default is $_POST
                  $additionaldata: Array of any additional data to be inserted in the table row, in addition to what has been received from $form
                  $logentry: The entry text to be included in the access log, if null no entry would be made
                                           message => log message
                                           type => the type of entry
                $savecallbackfunction: If a callback for saving data is provided, then use it, else go for default save funtionality
                                          arguments: array of key => value pairs of all submitted values
                                          returns: same as saveClientFormData

          Returns:
                          null => Nothing for this function to do
                          int => data saved successfully (return value is the insert ID for this row)
                          array => Array of error messages, if errors during processing
   */
function _saveClientFormDataSingle($form, $submitteddata = null, $additionaldata = null, $logentry = null, $savecallbackfunction = null)
{
    global $db;

    //default source is POST
    if(!$submitteddata) $submitteddata = $_POST;
    //check if this form has been submitted, else return
    if(!isset($submitteddata['op']) or $submitteddata['op'] != $form['name'].'_submitted')
        return null;
    else
    {
      $record = array(); // Initialize an array to hold the record data to insert
      $nondbrecord = array(); //All non database fields go in this
      $errormessages = null; //validation error messages, if any
      //Now check each field and prepare a record of submitted data also validate
      foreach($form['fields'] as $f)
      {
          switch($f['type'])
          {
              case 'text':
              case 'textarea':
              case 'select':
              case 'hidden':
                //check is there is value for this in form submission
                $value = isset($submitteddata[$f['name']])?$submitteddata[$f['name']]:null;
                if($f['required'] and $value == null) $errormessages[] = "$f[label] is required";
                if(isset($f['dbcolumn'])) //if DB column is specified
                  $record[$f['dbcolumn']] = addslashes(strip_tags(trim($value)));
                else
                  $nondbrecord[$f['name']] = addslashes(strip_tags(trim($value)));
                break;
              //All other ignored
          }
      }
      //Any additional data
      if($additionaldata and is_array($additionaldata))
      {
          foreach($additionaldata as $key => $value)
          {
              $record[$key] = $value;
          }
      }

      if(!$errormessages)
      {
          //if a callback for saving data is provided, then use it, else go for default funtionality
          if($savecallbackfunction)
          {
              //include all non Db records also for custom call
              if($nondbrecord and is_array($nondbrecord))
              {
                  foreach($nondbrecord as $key => $value)
                  {
                      $record[$key] = $value;
                  }
              }
              return $savecallbackfunction($record);
          }
          else
          {
            if(isset($form['updateon']))
            {
                $id = $form['updateon'][key($form['updateon'])];
                //Select the recordset to modify from the database
                $sql = "SELECT * FROM ".$form['dbtable']." WHERE ".key($form['updateon'])."='".$id."'";
                $rs = $db->Execute($sql);
                $sql = $db->GetUpdateSQL($rs, $record);
                if(!$sql) return null; //nothing to update
                if(!$db->Execute($sql))
                    $errormessages[]  = "Error updating data, try again-".MYSQL_ERROR();
                else
                {
                    if($logentry)
            addActionLog($logentry['message'], $logentry['type'], $_SESSION['userid'], $id);
                    return $id;
                }
            }
            else
            {
              //Select an empty record from the database
              $sql = "SELECT * FROM ".$form['dbtable']." WHERE id = -1";
              $rs = $db->Execute($sql);
              $sql = $db->GetInsertSQL($rs, $record);
              //echo $sql;
              if(!$db->Execute($sql))
                  $errormessages[]  = "Error saving data, try again-".MYSQL_ERROR();
              else
              {
                  $insertid = $db->Insert_ID();
                  if($logentry)
          addActionLog($logentry['message'], $logentry['type'], $_SESSION['userid'], $insertid);
                  return $insertid;
              }
            }
          }
       }

      return $errormessages;
    }
}

/**
        Saves the multi form data to a table
        Arguments:
                  $form: The array representing the form // multiform
                   $submitteddata: $_GET or $_POST or $_REQUEST default is $_POST
                  $additionaldata: Array of any additional data to be inserted in the table row, in addition to what has been received from $form
                  $logentry: The entry text to be included in the access log, if null no entry would be made
                                           message => log message
                                           type => the type of entry
                $savecallbackfunction: If a callback for saving data is provided, then use it, else go for default save funtionality
                                          arguments: array of key => value pairs of all submitted values
                                          returns: same as saveClientFormData

          Returns:
                          null => Nothing for this function to do
                          int => data saved successfully (return value is the insert ID for this row)
                          array => Array of error messages, if errors during processing
   */
function _saveClientFormDataMulti($form, $submitteddata = null, $additionaldata = null, $logentry = null, $savecallbackfunction = null)
{
    global $db;
    //default source is POST
    if(!$submitteddata) $submitteddata = $_POST;
    //check if this form has been submitted, else return
    if(!isset($submitteddata['op']) or $submitteddata['op'] != $form['name'].'_submitted')
        return null;
    else
    {
      $errormessages = null; //validation error messages, if any, for all iterations
      //Now check each field and prepare a record of submitted data, also validate
      //do this for each row of a multi form
      foreach($form['row_labels'] as $id => $label)
      {
          $record = null; // Initialize an array to hold the record data to insert, for this iteration
          $nondbrecord = array(); //All non database fields go in this
          $error = false; //error for this iteration
          //go only if the enabling checkbox is checked for this row of multiform
          if(isset($submitteddata[$form['name'].'_autoform_ch'.$id]) and $submitteddata[$form['name'].'_autoform_ch'.$id])
          {
              foreach($form['fields'] as $f)
              {
                  switch($f['type'])
                  {
                      case 'text':
                      case 'textarea':
                      case 'select':
                      case 'hidden':
                        //check is there is value for this in form submission
                        $value = isset($submitteddata[$f['name'].$id])?$submitteddata[$f['name'].$id]:null;
                        if($f['required'] and $value == null)
                        {
                          $error = true; //this iteration has errors
                          $errormessages[] = "$label / ".$f['label']." is required";
                        }
                        if(isset($f['dbcolumn'])) //if DB column is specified
                          $record[$f['dbcolumn']] = addslashes(strip_tags(trim($value)));
                        else
                          $nondbrecord[$f['name']] = addslashes(strip_tags(trim($value)));
                        break;
                      //All other ignored
                  }
              }
              //Any additional data sent via function call, it is also possible for a form to have hidden data only, but that enabled checkbox should be on
              if($additionaldata and is_array($additionaldata))
              {
                  foreach($additionaldata as $key => $value)
                  {
                      $record[$key] = $value;
                  }
              }
          }

          if(!$error and $record and is_array($record))
          {
              //if a callback for saving data is provided, then use it, else go for default funtionality
              if($savecallbackfunction)
              {
                  //include all non Db records also for custom call
                  if($nondbrecord and is_array($nondbrecord))
                  {
                      foreach($nondbrecord as $key => $value)
                      {
                          $record[$key] = $value;
                      }
                  }
                  //save whatever has been returned
                  $errormessages = $savecallbackfunction($record);
              }
              else
              {
                  //backward compatibility, default to insert new value
                  if(!isset($form['type'])) $form['type'] = 'insert';
                  //check if this is an update or insert
                  switch($form['type'])
                  {
                      case 'update':
                      {
                        $id = $form['updateon'][key($form['updateon'])];
                        //Select the recordset to modify from the database
                        $sql = "SELECT * FROM ".$form['dbtable']." WHERE ".key($form['updateon'])."='".$id."'";
                        $rs = $db->Execute($sql);
                        $sql = $db->GetUpdateSQL($rs, $record);
                        if(!$sql) //nothing to update
                        {
                            if(!$db->Execute($sql))
                            {
                                $error = true;
                                $errormessages[]  = "Error updating data, try again-".MYSQL_ERROR();
                            }
                            else
                            {
                                if($logentry)
              addActionLog($logentry['message'], $logentry['type'], $_SESSION['userid'], $id);
                            }
                        }
                      }
                      break;

                    case 'insert':
                    {
                        //Select an empty record from the database
                        $sql = "SELECT * FROM ".$form['dbtable']." WHERE id = -1";
                        $rs = $db->Execute($sql);
                        $sql = $db->GetInsertSQL($rs, $record);
                        //echo $sql;
                        if(!$db->Execute($sql))
                        {
                            $error = true;
                            $errormessages[]  = "Error saving data, try again-".MYSQL_ERROR();
                        }
                        else
                        {
                            $insertid = $db->Insert_ID();
                            if($logentry)
                                addActionLog($logentry['message'], $logentry['type'], $_SESSION['userid'], $insertid);
                        }
                      }
                      break;
                  }
              }
          }
      }
      return $errormessages;
    }
}

/** To check if the form in question has been submitted
       Does nothing except checking the form submittion, form should be porcessed
  */
function checkIfFormSubmitted($form, $submitteddata = null)
{
    global $_POST;
    //default source is POST
    if(!$submitteddata) $submitteddata = $_POST;

    //check if this form has been submitted, else return
    if(!isset($submitteddata['op']) or $submitteddata['op'] != $form['name'].'_submitted')
        return false;
    else
        return true;
}
?>
