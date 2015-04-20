<?php /* Smarty version 2.6.18, created on 2015-04-19 18:06:31
         compiled from mydetails.html */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'header-backtomenu.html', 'smarty_include_vars' => array('title' => 'My Details')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<div data-role="content">
  <img src="images/pictures/default.jpg" class="employee-pic" width="100"/>
	<div class="employee-details">
		<h3><?php echo $this->_tpl_vars['username']; ?>
</h3>
		<p>Your role <?php echo $this->_tpl_vars['myrole']; ?>
</p>
		<p><?php echo $this->_tpl_vars['myactionlogcount']; ?>
 activities logged</p>
    <p><?php echo $this->_tpl_vars['resultpager']; ?>
</p>
	</div>
</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'footer.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>