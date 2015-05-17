<form action="<?=$submitLink?>&action=debug" method="post" target="debugTargetFrame" onsubmit="return validateBugForm(this,'debug')">
    <input type="hidden" name="cmd" value="bugReport">
    <input type="hidden" name="token" value="<?=md5($token)?>">
    <input type="hidden" name="date" value="<?=date("Y-m-d")?>">
    <input type="hidden" name="time" value="<?=date("H:i:s")?>">
    <input type="hidden" name="url" value="<?=$lc->encode(_url())?>">
    <div class='screenshots attachment' style='display:none;'>
    </div>
    <?php
    	foreach ($debug as $key => $value) {
    		echo "<input type='hidden' name='$key' value='$value' />";
    	}
    ?>
    <label>
		<input type="email" name="email" class='emailfield required' placeholder='@' />
		Email *
	</label>
	<label>
		<textarea name='msg' class='required'></textarea>
		Text *
	</label>
	<label>
		<select name='category' class="nostyle required">
			<option value='bug'>Bug Report</option>
			<option value='feature'>Feature Request</option>
		</select>
		Category *
	</label>
	<?php if($cfg['allow_attachment']=='true') { ?>
	<label>
		<input type="file" name="attachment" value="" class='filefield' />
		Attachment
	</label>
	<?php } ?>
	<label>
		<a id='screenshotsLoader' class='buttonElement half color_orange'>Take Screenshots</a>
		<a id='screenshotsCount' class='buttonElement half color_green'></a>
		Screenshots
	</label>
	<br/><br/><hr/>
	<div align=center>
		<button class='nostyle closeBtn' type='button'>Close</button>
		<button class='nostyle' type='submit'>Submit</button>
	</div>
</form>
