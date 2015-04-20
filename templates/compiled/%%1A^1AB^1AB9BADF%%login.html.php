<?php /* Smarty version 2.6.18, created on 2015-04-19 18:05:21
         compiled from login.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 't', 'login.html', 4, false),)), $this); ?>
<!DOCTYPE HTML>
<html>
<head>
<title><?php echo $this->_tpl_vars['app_default_title']; ?>
 :: <?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_translate($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Please Authenticate<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_translate($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></title>
<meta name="viewport" content="width=device-width,initial-scale=1"/>
<link rel="stylesheet" href="<?php echo $this->_tpl_vars['skinpath']; ?>
css/jquery.mobile-1.4.0.min.css" />
<link rel="stylesheet" href="<?php echo $this->_tpl_vars['skinpath']; ?>
css/ios_inspired/styles.css" />
<link rel="stylesheet" href="<?php echo $this->_tpl_vars['skinpath']; ?>
css/styles.css" />
</head>

<body>

<div data-role="page">

  <div data-role="header">
    <h1><img src='<?php echo $this->_tpl_vars['skinpath']; ?>
icons/lock.png' width='16' /> <?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_translate($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Please Authenticate<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_translate($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></h1>
  </div>

  <div data-role="content"> 
    <center>
    <?php if ($this->_tpl_vars['login_message']): ?><p><?php echo $this->_tpl_vars['login_message']; ?>
</p><?php endif; ?>
    <form name="form1" action="login.php?" method="post" data-ajax="false">
     <table>
      <tr>
        <td class="name"><label><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_translate($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>User<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_translate($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></label></td>
        <td>
          <INPUT type="text" name="username" id="username" value="" size="25" maxlength="32" />
        </td>
      </tr>
      <tr>
        <td class="name"><label for="password"><?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_translate($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Password<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_translate($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></label></td>
        <td><input type="password" name="password" id="password" size="25" maxlength="32"></td>
      </tr>
      <tr>
       <td colspan="2"><input type='submit' name="bname_login" value='<?php $this->_tag_stack[] = array('t', array()); $_block_repeat=true;smarty_translate($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Login<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_translate($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>' />
      </td>
     </tr>
    </table>
   </form>
   </center>
  </div>
  <div data-role="footer">
		<b><center>&copy; 2013-14</center></b>
	</div>

</div>

<script src="<?php echo $this->_tpl_vars['skinpath']; ?>
js/jquery-2.1.0.min.js"></script>
<script src="<?php echo $this->_tpl_vars['skinpath']; ?>
js/jquery.mobile-1.4.0.min.js"></script>

</body>
</html>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'analytics.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>