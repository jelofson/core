<?php
/**
* 
* Savant3 template for editing a bookmark.
* 
* @category Solar
* 
* @package Solar_App
* 
* @subpackage Solar_App_Bookmarks
* 
* @author Paul M. Jones <pmjones@solarphp.com>
* 
* @license LGPL
* 
* @version $Id$
* 
*/
?>
<?php include $this->template('header.php') ?>

<h2><?php $this->eprint($this->locale('EDIT_ITEM')) ?></h2>
<p>[ <?php
	echo $this->ahref(
		$this->backlink,
		$this->locale('BACKLINK')
	);
?> ]</p>

<!-- enclose in table to collapse the div -->
<table><tr><td>

	<?php if ($this->formdata->feedback): ?>
		<div style="background: #eee; padding: 4px; border: 2px solid red;">
			<?php foreach ((array) $this->formdata->feedback as $text) {
				echo "<p>" . $this->escape($text) . "</p>\n";
			} ?>
		</div>
	<?php endif ?>
	
	<?php
		$this->form('set', 'class', 'Savant3');
		echo $this->form('begin', $this->formdata->attribs);
		echo $this->form('hidden', 'op', $this->locale('Solar::OP_SAVE'));
		echo $this->form('auto', $this->formdata->elements);
		echo $this->form('group', 'begin');
		echo $this->form('submit', 'op', $this->locale('Solar::OP_SAVE'));
		echo $this->form('submit', 'op', $this->locale('Solar::OP_CANCEL'));
		echo $this->form('submit', 'op', $this->locale('Solar::OP_DELETE'));
		echo $this->form('group', 'end');
		echo $this->form('end');
	?>
	
</td><tr></table>

<?php include $this->template('footer.php') ?>