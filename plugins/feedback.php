<p>
	This tool is provided for maintaining the quality of the system, 
	please use it to give us your feedbacks to us.
</p>
<form action="<?=$submitLink?>&action=feedback" method="post" target="debugTargetFrame" onsubmit="return validateBugForm(this,'feedback')">
    <input type="hidden" name="cmd" value="bugReport" />
    <input type="hidden" name="token" value="<?=md5($token)?>" />
    <input type="hidden" name="date" value="<?=date("Y-m-d")?>" />
    <input type="hidden" name="time" value="<?=date("H:i:s")?>" />
    <label>
		<input type="email" name="email" placeholder="@" class='emailfield required' />
		Email *
	</label>
     <label>
        <input type="phone" name="phone" placeholder="+91" class='phonefield' />
        Phone
    </label>
	<label>
		<textarea name='msg' class='required'></textarea>
		Text *
	</label>
	<label>
		Will you like to be contacted by us :
		<label style='display: inline;margin: 0px;'><input type="radio" name="contact_me" value="yes" checked>Yes</label>
		<label style='display: inline;margin: 0px;'><input type="radio" name="contact_me" value="no">No</label>
	</label>
	<br/><br/><hr/>
	<div align=center>
		<button class='nostyle closeBtn' type='button'>Close</button>
		<button class='nostyle' type='submit'>Submit</button>
	</div>
</form>
