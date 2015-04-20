<?php /* Smarty version 2.6.18, created on 2015-04-19 18:05:34
         compiled from header-backtomenu.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 't', 'header-backtomenu.html', 18, false),)), $this); ?>
<!DOCTYPE HTML>
<html>
<head>
<title><?php echo $this->_tpl_vars['app_default_title']; ?>
 :: <?php echo $this->_tpl_vars['title']; ?>
</title>
<meta name="viewport" content="width=device-width,initial-scale=1"/>
<link rel="stylesheet" href="<?php echo $this->_tpl_vars['skinpath']; ?>
css/jquery.mobile-1.4.0.min.css" />
<link rel="stylesheet" href="<?php echo $this->_tpl_vars['skinpath']; ?>
css/ios_inspired/styles.css" />
<link rel="stylesheet" type="text/css" href="<?php echo $this->_tpl_vars['skinpath']; ?>
css/jquery.datetimepicker.css"/ >
<link rel="stylesheet" href="<?php echo $this->_tpl_vars['skinpath']; ?>
css/styles.css" />
<script src="<?php echo $this->_tpl_vars['skinpath']; ?>
js/jquery-2.1.0.min.js"></script>
<script src="<?php echo $this->_tpl_vars['skinpath']; ?>
js/jquery.mobile-1.4.0.min.js"></script>
<script src="<?php echo $this->_tpl_vars['skinpath']; ?>
js/jquery.datetimepicker.js"></script>
</head>
<body>
<div data-role="page">
<div data-role="header">
  <?php if ($this->_tpl_vars['backtourl']): ?>
  <a href="<?php echo $this->_tpl_vars['backtourl']; ?>
" data-icon="back" data-direction="reverse" <?php if ($this->_tpl_vars['exiteffect']): ?>data-transition="<?php echo $this->_tpl_vars['exiteffect']; ?>
"<?php else: ?>data-transition="slide"<?php endif; ?>><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_translate($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Back<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_translate($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></a>
  <?php else: ?>
  <a href="menu.php" data-icon="back" data-direction="reverse" <?php if ($this->_tpl_vars['exiteffect']): ?>data-transition="<?php echo $this->_tpl_vars['exiteffect']; ?>
"<?php else: ?>data-transition="slide"<?php endif; ?>><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_translate($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Back<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_translate($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></a>
  <?php endif; ?>
	<h1><?php echo $this->_tpl_vars['title']; ?>
</h1>
</div><!-- /header -->
<div data-role="content">