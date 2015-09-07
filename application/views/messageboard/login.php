<div style="margin: auto; width: 200px">
<?php echo form_open("messageboard/login", array("id" => "login", "role" => "form", "class" => "form-horizontal")) ?>
	<div class="form_group">
		<label class="control-label" for="account">帳號</label>
		<input class="form-control" placeholder="帳號" type="text" name="account"/>
	</div>	
	<div class="form_group">
		<label class="control-label" for="password">密碼</label>
	    <input class="form-control" placeholder="密碼" type="password" name="password"/>
	</div>
	<br/>
	<div>
		<?php echo validation_errors(); ?>
	</div>
	<button class="btn btn-primary" type="submit">登入</button>
</form>
</div>