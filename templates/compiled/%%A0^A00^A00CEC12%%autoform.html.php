<?php /* Smarty version 2.6.18, created on 2015-04-19 18:05:34
         compiled from autoform.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'math', 'autoform.html', 17, false),array('function', 'html_options', 'autoform.html', 68, false),)), $this); ?>
<h1><?php echo $this->_tpl_vars['form']['title']; ?>
</h1>
<form method='<?php echo $this->_tpl_vars['form']['method']; ?>
' id='<?php echo $this->_tpl_vars['form']['name']; ?>
' name='<?php echo $this->_tpl_vars['form']['name']; ?>
' action='<?php echo $this->_tpl_vars['form']['action']; ?>
' onsubmit="return checkform_<?php echo $this->_tpl_vars['form']['name']; ?>
(document.forms['<?php echo $this->_tpl_vars['form']['name']; ?>
']);"  <?php echo $this->_tpl_vars['form']['extradata']; ?>
>
<!--Fields in this form-->
<div class="ui-field-contain">
    <p align='right'><small>Fields marked with a star are required.</small></p>
</div>
<?php $this->assign('ccount', '0'); ?>
<?php $_from = $this->_tpl_vars['form']['fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['fields'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['fields']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['field']):
        $this->_foreach['fields']['iteration']++;
?>
    <?php if ($this->_tpl_vars['field']['type'] == 'hidden'): ?>
      <input type='hidden' name='<?php echo $this->_tpl_vars['field']['name']; ?>
' id='<?php echo $this->_tpl_vars['field']['name']; ?>
' value='<?php echo $this->_tpl_vars['field']['value']; ?>
' />
    <?php elseif ($this->_tpl_vars['field']['type'] == 'html'): ?>
      <div class="ui-field-contain">
        <?php echo $this->_tpl_vars['field']['html']; ?>

      </div>
    <?php elseif ($this->_tpl_vars['field']['type'] == 'text'): ?>
            <?php echo smarty_function_math(array('equation' => ($this->_tpl_vars['ccount'])."+1",'assign' => 'ccount'), $this);?>

    <div class="ui-field-contain">
      <label for="<?php echo $this->_tpl_vars['field']['name']; ?>
"><?php echo $this->_tpl_vars['ccount']; ?>
.&nbsp;<?php echo $this->_tpl_vars['field']['label']; ?>
</label>
      <div id="<?php echo $this->_tpl_vars['field']['name']; ?>
_" class="ui-helper-clearfix">
        <input data-clear-btn="true" <?php if ($this->_tpl_vars['field']['name']): ?>value='<?php echo $this->_tpl_vars['field']['value']; ?>
'<?php endif; ?> name='<?php echo $this->_tpl_vars['field']['name']; ?>
' id='<?php echo $this->_tpl_vars['field']['name']; ?>
' <?php if ($this->_tpl_vars['field']['value']): ?>value="<?php echo $this->_tpl_vars['field']['value']; ?>
"<?php endif; ?> <?php if ($this->_tpl_vars['field']['size']): ?>size='<?php echo $this->_tpl_vars['field']['size']; ?>
'<?php endif; ?> <?php if ($this->_tpl_vars['field']['maxlength']): ?>maxlength="<?php echo $this->_tpl_vars['field']['maxlength']; ?>
"<?php endif; ?> <?php if ($this->_tpl_vars['field']['tooltip']): ?>title="<?php echo $this->_tpl_vars['field']['tooltip']; ?>
"<?php endif; ?> <?php if ($this->_tpl_vars['field']['inputtype'] == 'date'): ?>type="date"<?php else: ?>type="text"<?php endif; ?>/> <?php if ($this->_tpl_vars['field']['required']): ?>*<?php endif; ?>
      </div>
      <?php if ($this->_tpl_vars['field']['inputtype'] == 'number'): ?>
            <script><?php echo '
                  $(\'#'; ?>
<?php echo $this->_tpl_vars['field']['name']; ?>
<?php echo '\').keypress(function(event) {
                    if (event.charCode && (event.charCode < 48 || event.charCode > 57) && event.charCode != 46 && event.charCode != 45){ event.preventDefault();}
                  });
            '; ?>
</script>
      <?php elseif ($this->_tpl_vars['field']['inputtype'] == 'date'): ?>
            <script><?php echo '
              $(document).ready(function(){$(\'#'; ?>
<?php echo $this->_tpl_vars['field']['name']; ?>
<?php echo '\').datetimepicker({timepicker:false,
 format:\'Y-m-d\'})});
            '; ?>
</script>
      <?php elseif ($this->_tpl_vars['field']['inputtype'] == 'datetime'): ?>
            <script><?php echo '
              $(document).ready(function(){$(\'#'; ?>
<?php echo $this->_tpl_vars['field']['name']; ?>
<?php echo '\').datetimepicker({timepicker:true,
 format:\'Y-m-d H:i:s\'})});
            '; ?>
</script>
       <?php elseif ($this->_tpl_vars['field']['inputtype'] == 'autocomplete' && $this->_tpl_vars['field']['source']): ?>
          <script><?php echo '
                    $(function() {
                            $( "#'; ?>
<?php echo $this->_tpl_vars['field']['name']; ?>
<?php echo '" ).autocomplete({
                                source: "'; ?>
<?php echo $this->_tpl_vars['field']['source']; ?>
<?php echo '",
                                minLength: 2,
//                                select: function( event, ui ) {
//                                }
                            });
                });
          '; ?>
</script>
       <?php endif; ?>
     </div>
    <?php elseif ($this->_tpl_vars['field']['type'] == 'textarea'): ?>
            <?php echo smarty_function_math(array('equation' => ($this->_tpl_vars['ccount'])."+1",'assign' => 'ccount'), $this);?>

    <div class="ui-field-contain">
      <label for="<?php echo $this->_tpl_vars['field']['name']; ?>
"><?php echo $this->_tpl_vars['ccount']; ?>
.&nbsp;<?php echo $this->_tpl_vars['field']['label']; ?>
</label>
      <textarea name='<?php echo $this->_tpl_vars['field']['name']; ?>
' id='<?php echo $this->_tpl_vars['field']['name']; ?>
' <?php if ($this->_tpl_vars['field']['rows']): ?>rows='<?php echo $this->_tpl_vars['field']['rows']; ?>
'<?php endif; ?> <?php if ($this->_tpl_vars['field']['cols']): ?>cols='<?php echo $this->_tpl_vars['field']['cols']; ?>
'<?php endif; ?> <?php if ($this->_tpl_vars['field']['tooltip']): ?>title="<?php echo $this->_tpl_vars['field']['tooltip']; ?>
"<?php endif; ?>  ><?php if ($this->_tpl_vars['field']['value']): ?><?php echo $this->_tpl_vars['field']['value']; ?>
<?php endif; ?></textarea><?php if ($this->_tpl_vars['field']['required']): ?>*<?php endif; ?>
    </div>
    <?php elseif ($this->_tpl_vars['field']['type'] == 'select'): ?>
            <?php echo smarty_function_math(array('equation' => ($this->_tpl_vars['ccount'])."+1",'assign' => 'ccount'), $this);?>

    <div class="ui-field-contain">
      <label for="<?php echo $this->_tpl_vars['field']['name']; ?>
"><?php echo $this->_tpl_vars['ccount']; ?>
.&nbsp;<?php echo $this->_tpl_vars['field']['label']; ?>
</label>
      <select name="<?php echo $this->_tpl_vars['field']['name']; ?>
" id='<?php echo $this->_tpl_vars['field']['name']; ?>
' <?php if ($this->_tpl_vars['field']['tooltip']): ?>title="<?php echo $this->_tpl_vars['field']['tooltip']; ?>
"<?php endif; ?> >
          <?php if ($this->_tpl_vars['field']['noselect']): ?>
            <option value='<?php echo $this->_tpl_vars['field']['noselect']['value']; ?>
'><?php echo $this->_tpl_vars['field']['noselect']['option']; ?>
</option>
          <?php endif; ?>
          <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['field']['value'],'selected' => $this->_tpl_vars['field']['selected']), $this);?>

      </select><?php if ($this->_tpl_vars['field']['required']): ?>*<?php endif; ?>
    </div>
    <?php elseif ($this->_tpl_vars['field']['type'] == 'displayonly'): ?>
      <?php echo smarty_function_math(array('equation' => ($this->_tpl_vars['ccount'])."+1",'assign' => 'ccount'), $this);?>

    <div class="ui-field-contain">
      <label for="<?php echo $this->_tpl_vars['field']['name']; ?>
"><?php echo $this->_tpl_vars['ccount']; ?>
.&nbsp;<?php echo $this->_tpl_vars['field']['label']; ?>
</label>
      <div id="<?php echo $this->_tpl_vars['field']['name']; ?>
" <?php if ($this->_tpl_vars['field']['tooltip']): ?>title="<?php echo $this->_tpl_vars['field']['tooltip']; ?>
"<?php endif; ?> >
      <?php echo $this->_tpl_vars['field']['value']; ?>

      </div>
    </div>
    <?php elseif ($this->_tpl_vars['field']['type'] == 'label'): ?>
    <div class="ui-field-contain">
     <?php echo $this->_tpl_vars['field']['label']; ?>

    </div>
    <?php endif; ?>
<?php endforeach; endif; unset($_from); ?>
<fieldset class="ui-grid-a">
  <?php if ($this->_tpl_vars['form']['savebuttonlabel']): ?>
    <div class="ui-block-a"><input value="<?php echo $this->_tpl_vars['form']['savebuttonlabel']; ?>
" data-theme="d" type="submit"></div>
  <?php endif; ?>
  <?php if ($this->_tpl_vars['form']['resetbuttonlabel']): ?>
    <div class="ui-block-b"><input value="<?php echo $this->_tpl_vars['form']['resetbuttonlabel']; ?>
" data-theme="e" type="reset"></div>
  <?php endif; ?>
</fieldset>
<input type='hidden' name='op' value='<?php echo $this->_tpl_vars['form']['name']; ?>
_submitted' />
</form>
<SCRIPT language='javascript'>
//<!--
function checkform_<?php echo $this->_tpl_vars['form']['name']; ?>
(f)
{
  var message = "";
<?php $_from = $this->_tpl_vars['form']['fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['fields'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['fields']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['field']):
        $this->_foreach['fields']['iteration']++;
?>
 <?php if ($this->_tpl_vars['field']['required'] && ( $this->_tpl_vars['field']['type'] == 'text' || $this->_tpl_vars['field']['type'] == 'textarea' )): ?>
  if (f.<?php echo $this->_tpl_vars['field']['name']; ?>
.value.length == 0)
  {
    message = message + " * <?php echo $this->_tpl_vars['field']['label']; ?>
\n";
  }
 <?php endif; ?>
 <?php if ($this->_tpl_vars['field']['required'] && ( $this->_tpl_vars['field']['type'] == 'select' )): ?>
  if (f.<?php echo $this->_tpl_vars['field']['name']; ?>
.value == 0)
  {
    message = message + " * Please make a selection for  <?php echo $this->_tpl_vars['field']['label']; ?>
\n";
  }
 <?php endif; ?>
<?php endforeach; endif; unset($_from); ?>

  if ( message.length > 0 )
  {
    message = "Please fill the following information\n" + message;
    alert( message );
    return false;
  }
  else
  {
    return true;
  }
}
// -->
</SCRIPT>