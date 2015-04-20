<?php /* Smarty version 2.6.18, created on 2015-04-19 18:05:34
         compiled from modules/DistributorInvoices.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 't', 'modules/DistributorInvoices.html', 3, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'header-backtomenu.html', 'smarty_include_vars' => array('title' => 'Manage Distributor Invoices')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<div class="ui-body ui-body-a ui-corner-all" data-theme="b">
 <h3><img src="<?php echo $this->_tpl_vars['skinpath']; ?>
/icons/logo.png" width="32" height="32"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_translate($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Invoices<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_translate($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></h3>
  <p>Total <?php echo $this->_tpl_vars['count']; ?>
 records
   <br /><?php echo $this->_tpl_vars['message']; ?>

  </p>
</div>

<?php if ($this->_tpl_vars['pager']): ?>
<div data-role="collapsible" data-collapsed="false">
  <h4><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_translate($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Existing Records<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_translate($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></h4>
  <p><?php echo $this->_tpl_vars['pager']; ?>
</p>
</div>
<?php endif; ?>
<?php if ($this->_tpl_vars['form']): ?>
<div data-role="collapsible">
  <h4><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_translate($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Add New<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_translate($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></h4>
  <p><?php echo $this->_tpl_vars['form']; ?>
</p>
</div>
<?php endif; ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'footer.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>