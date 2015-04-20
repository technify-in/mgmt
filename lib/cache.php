<?php

class BasicCache
{
  /** Writes/ Updates text to be cached
      Setting the right and unique key for a cached result is the responsibility of callee
      key can be the SQL query
   */
  function write($key, $text, $module = '')
  {
    global $db;
//echo $key;
//$db->debug = 1;
    BasicCache::_clean();
    //update should not happen, client is expected to first read cache then call write if read failed, so only insert is expected
    $hash = md5($key);

    $id = $db->getOne("select id from cache where hash='$hash'");
    if($id)
    {
      $rs = $db->Execute("select * from cache where hash='$hash'");
      //$db->Execute("update cache set text='$text' where id='$id'");
      $record['cachedata'] = $text;
      //$record['refkey'] = $key;
      //$record['module'] = $module;
      //$record['hash'] = $hash;
      //$record['modified'] = date("Y-m-d H:i:s");
      $db->Execute($db->GetUpdateSQL($rs, $record));
    }
    else
    {
      //$db->Execute("insert into cache set text='$text', key='$key', hash=md5($key), modified=NOW()");
      $record['module'] = $module;
      $record['cachedata'] = $text;
      $record['refkey'] = $key;
      $record['hash'] = $hash;
      $record['modified'] = date("Y-m-d H:i:s");
      $db->Execute($db->GetInsertSQL($db->Execute("select * from cache where id=-1"), $record));
    }
//$db->debug = 0;

  }

  /*  Checks if the cache for key specified exists,
      returns cached text 
      returns null if no cache
      key can be the SQL query
   */
  function read($key)
  {
    global $db;
//echo '<hr>'.$key.'<hr>';
//$db->debug = 1;
    BasicCache::_clean();
    $hash = md5($key);
    return $db->getOne("select cachedata from cache where hash='$hash'");
//$db->debug = 0;
  }

  /** Clean expired cache
   */
  function _clean()
  {
    global $db;
    //delete all older than 15 min
    $db->Execute("delete from cache where modified  < SUBTIME(NOW(),'".BasicCache::refreshTime()."')");
  }

  /** Returns refresh time/ cache life
   */
  function refreshTime()
  {
    return '10:00:00';
  }
}
?>

