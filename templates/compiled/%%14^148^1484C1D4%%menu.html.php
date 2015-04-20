<?php /* Smarty version 2.6.18, created on 2015-04-19 18:05:30
         compiled from menu.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 't', 'menu.html', 4, false),)), $this); ?>
<!DOCTYPE HTML>
<html>
<head>
<title><?php echo $this->_tpl_vars['app_default_title']; ?>
 : <?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_translate($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Welcome<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_translate($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></title>
<meta name="viewport" content="width=device-width,initial-scale=1"/>
<link rel="stylesheet" href="<?php echo $this->_tpl_vars['skinpath']; ?>
css/jquery.mobile-1.4.0.min.css" />
<link rel="stylesheet" href="<?php echo $this->_tpl_vars['skinpath']; ?>
css/ios_inspired/styles.css" />
<link rel="stylesheet" href="<?php echo $this->_tpl_vars['skinpath']; ?>
css/styles.css" />
<link rel="stylesheet" type="text/css" href="<?php echo $this->_tpl_vars['skinpath']; ?>
css/jquery.datetimepicker.css"/ >
<script src="<?php echo $this->_tpl_vars['skinpath']; ?>
js/jquery-2.1.0.min.js"></script>
<script src="<?php echo $this->_tpl_vars['skinpath']; ?>
js/jquery.mobile-1.4.0.min.js"></script>
<script src="<?php echo $this->_tpl_vars['skinpath']; ?>
js/jquery.datetimepicker.js"></script>
</head>
<body>

<div data-role="page" id='mainmenu'>
  <div data-role="header">
    <h1><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_translate($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Welcome<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_translate($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?> <?php echo $this->_tpl_vars['username']; ?>
</h1>
  </div>

  <div data-role="content">
    <ul data-role="listview" data-filter="true">
     <li>
      <a href='main.php' data-transition="pop">
	     <img src='<?php echo $this->_tpl_vars['skinpath']; ?>
icons/home.png'/>
	     <h4><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_translate($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Overview<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_translate($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></h4>
      </a>
     </li>
    <?php $_from = $this->_tpl_vars['menufields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['fields'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['fields']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['fielditems']):
        $this->_foreach['fields']['iteration']++;
?>
      <?php $_from = $this->_tpl_vars['fielditems']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['items'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['items']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['menuitem']):
        $this->_foreach['items']['iteration']++;
?>
       <?php if ($this->_tpl_vars['menuitem']['submenu']): ?>         <?php $_from = $this->_tpl_vars['menuitem']['submenu']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['items2'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['items2']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['submenu']):
        $this->_foreach['items2']['iteration']++;
?>
     <li>
      <a href='module.php?to=<?php echo $this->_tpl_vars['submenu']['url']; ?>
&adodb_next_page=1' data-transition="slide">
		   <?php if ($this->_tpl_vars['submenu']['icon']): ?><img src='<?php echo $this->_tpl_vars['skinpath']; ?>
icons/<?php echo $this->_tpl_vars['submenu']['icon']; ?>
'/><?php endif; ?>
		   <h4><?php echo $this->_tpl_vars['submenu']['name']; ?>
</h4>
      </a>
     </li>
        <?php endforeach; endif; unset($_from); ?>
       <?php else: ?>
     <li>
      <a href='module.php?to=<?php echo $this->_tpl_vars['menuitem']['url']; ?>
&adodb_next_page=1' data-transition="slide">
		   <?php if ($this->_tpl_vars['menuitem']['icon']): ?><img src='<?php echo $this->_tpl_vars['skinpath']; ?>
icons/<?php echo $this->_tpl_vars['menuitem']['icon']; ?>
'/><?php endif; ?>
		   <h4><?php echo $this->_tpl_vars['menuitem']['name']; ?>
</h4>
      </a>
     </li>
       <?php endif; ?>
      <?php endforeach; endif; unset($_from); ?>
     <?php endforeach; endif; unset($_from); ?>
     <li>
      <a href='mydetails.php' data-transition="slide">
	     <img src='<?php echo $this->_tpl_vars['skinpath']; ?>
icons/userinfo.png'/>
	     <h4><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_translate($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Settings<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_translate($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></h4>
      </a>
     </li>
     <li>
      <a href='logout.php'>
		   <img src='<?php echo $this->_tpl_vars['skinpath']; ?>
icons/door.png'/>
		   <h4><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_translate($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Exit<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_translate($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></h4>
      </a>
     </li>
     <li>
      <a href='#helppopup' data-rel="dialog" data-transition="pop">
		   <img src='<?php echo $this->_tpl_vars['skinpath']; ?>
icons/help.png'/>
		   <h4><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_translate($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Help<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_translate($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></h4>
      </a>
     </li>
	</ul>
  </div>
</div>

<div data-role="page" id="helppopup">
	<div data-role="header">
	 <h1><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_translate($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Need Help<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_translate($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></h1>
	</div>
	<div data-role="content">	
	  <h2><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_translate($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Contact the Following<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_translate($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></h2>
    <center>
	    <p><img src='<?php echo $this->_tpl_vars['skinpath']; ?>
icons/help.png' width='50%'/></p>
      <p>Pray to your GOD.</p>
	    <p><a href="#mainmenu" data-rel="back" data-role="button">Close</a></p>
    </center>
	</div>
  <div data-role="footer">
		<h4>Thank You!</h4>
	</div>
 </div>
</div>
</body>
</html>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'analytics.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>