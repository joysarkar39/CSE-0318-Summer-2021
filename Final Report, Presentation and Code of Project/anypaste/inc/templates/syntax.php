<select id="syntax" name="syntax" class="mdl-textfield__input">
	<option value="" selected="selected"></option>
<?php
require_once 'corex/autoload.php';

$syntax_lang = DB::operation()->query("SELECT * FROM syntax");
if ($syntax_lang->count()) {
	foreach ($syntax_lang->results() as $syntax) {
		echo '<option value="'.$syntax->initial.'" >';
		echo $syntax->name;
		echo '</option>';
	}
}

?>
</select>