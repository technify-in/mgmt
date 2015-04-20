<?php

//workflow state id 0 is a special case means start new workflow task
abstract class Workflow
{
  //Friendly name for this workflow
  protected $wfFriendlyName = "";
  //Prefix to names of the workflow tables
  protected $wftable = "";

  //suffix to wftable where actual data resides i.e. tasks reside
  protected $wftable_data = '_data';
  //the column which represents the current state id of a task foriegn key from $wftable_data to $wftable_workflow
  //TODO: if it is zero in $wftable_data that means an undefined state (for historical records we will set it to 0, and allow user to choose as one time measure)
  protected $wftable_workflow_stateid = 'workflowstateid';

  //reference to task id
  protected $wftable_taskid = 'taskid';

  //table with states is _workflow
  protected $wftable_workflow = '_workflow';

  //history table is _workflow_log
  protected $wftable_log = '_log';

  public function __construct($wftable, $wfFriendlyName = null)
  {
    if(strlen($wftable) == 0)
      throw "Workflow name (table prefix) not specified";
    $this->wftable = $wftable;
    $this->wfFriendlyName = $wfFriendlyName;
  }

  //returns all workflow states as menu items
  public function getMenuItems($module)
  {
    global $db;

    $menus = null;

    //special case new task
    $menus['submenu'][0]['url']  = "$module&".$this->wftable_workflow_stateid."=0";
    $menus['submenu'][0]['name'] = gettext('Start New');
    $menus['submenu'][0]['icon'] = 'on.gif';

    $sql = "select `id`, `shortdescription`, `icon` from ".$this->wftable.$this->wftable_workflow." order by displayorder";
    foreach($db->getAll($sql) as $s)
    {
      $menus['submenu'][$s['id']]['url']  = "$module&".$this->wftable_workflow_stateid."=".$s['id'];
      $menus['submenu'][$s['id']]['name'] = $s['shortdescription'];
      $menus['submenu'][$s['id']]['icon'] = $s['icon']?$s['icon']:'default_menu.gif';
    }
    return $menus;
  }

  //either displays the report or the form for transition to next state
  //inputs: if $wftable_workflow_stateid then report by calling getReportingQuery and list in action -> Process
  //        if $_GET['processworkflow'] and $wftable_taskid is set then render form by 
  //          a) first calling getNextState on current data of task to determine next state 
  //          b) then call getStateChangeFormColumns for inputs required to transition to next state
  //        if only $wftable_taskid is display that task current status and history
  public function process()
  {
    global $db, $template, $_GET;

    if(isset($_GET[$this->wftable_workflow_stateid])) 
    {
      if((int)$_GET[$this->wftable_workflow_stateid] == 0)
        return 'New';
      else if($db->getOne("select id from ".$this->wftable.$this->wftable_workflow." where id='".(int)$_GET[$this->wftable_workflow_stateid]."'"))
      {
        $workflowstateid = (int)$_GET[$this->wftable_workflow_stateid];
        return 'report'.$workflowstateid;
      }
      else
        throw "For Workflow ".$this->wftable." Workflow State ".(int)$_GET[$this->wftable_workflow_stateid]." not found";
    }
    else if(isset($_GET[$this->wftable_taskid]))
    {
      if((int)$_GET[$this->wftable_taskid] == 0)
      {
        //new 
        return 'New';
      }
      //is it exisiting taskid for further processing
      else if($db->getOne("select id from ".$this->wftable.$this->wftable_data." where id='".(int)$_GET[$this->wftable_taskid]."'") )
      {
        $taskid = (int)$_GET[$this->wftable_taskid];
        if(isset($_GET['processworkflow']))
        {
          //check existing data for this $taskid
          return 'form';
        }
        else //view that $taskid only along with history
          return 'task report';
      }
      else
        throw "For Workflow ".$this->wftable." Workflow Task ".(int)$_GET[$this->wftable_taskid]." not found";
    }
    else
      throw "Unable to process Workflow ".$this->wftable." GET variables ".(int)$_GET[$this->wftable_taskid]." or processworkflow/ taskid not specified";
  }

  //decides sequence of events in workflow given a task, checks values of various variables and decideds next state when an application is processed for next state
  public abstract function getNextState($taskid);

  //to be over ridden by implementing class
  //given a workflowstateid will return query for reporting for all tasks at that state
  public abstract function getReportingQuery($workflowstateid);

  //given new state id returns array of autoform elements (fields)/ DB columns to be affected see autoform
  /**
      input: id of data column, taskid
             toworkflowid workflowid to which this is proposed to be changed
      returns array(
             array( 
                    'lable' => name of the form lable see autoform
                    'columnname' => column name in DB see autoform
                    'dbcolumn' => mapped database column 
                    'type' => display/ text/ select/ textarea etc see autoform
                    'inputtype' => number/ date/ datetime etc see autoform
                    'value' => see autoform
                    'selected'
                    'required
   */
  public abstract function getStateChangeFormColumns($taskid, $toworkflowstateid);
}
?>
