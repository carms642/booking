<form action="command/edititem.php" method="post">
	<div class="details">
		<div class="titleblock">
			<div class="titlepanel">
				<input name="oldcode" type="hidden" value="<?php print $this->item['code'] ?>" />
				<input name="name" type="text" class="title text" value="<?php print $this->item['name'] ?>" placeholder="Name"
					maxlength="50" required />
				<input name="code" type="text" class="text" value="<?php print $this->item['code'] ?>" placeholder="Code" maxlength="20"
					required />
			</div>
			<a class="adminbutton" href="command/deleteitem?code=<?php print $this->item['code'] ?>"
				onclick="return confirm('Delete this item?')">Delete</a>
		</div>

		<?php if ($this->item['image']): ?>
		<img class="itemimage" src="uploads/<?php print $this->item['image'] ?>" />
		<?php endif;?>
	</div>
	<div style="-webkit-flex: 1; -moz-flex: 1; flex: 1;">
		<div class="field">
			<div class="fieldname">Tags</div>
			<div>
				<div id="tags">
					<?php foreach($this->item['tags'] as $tag): ?>
					<div class="tag largetag" id="tag<?php print $tag['id']?>">
						<input name="tags[]" type="hidden" value="<?php print $tag['id'] ?>" />
						<div>
							<?php print $tag['text'] ?>
						</div>
						<a class="tagx" href="javascript:remove_tag(<?php print $tag['id'] ?>)">x</a>
					</div>
					<?php endforeach;?>
				</div>
			</div>
		</div>
		<div class="field">
			<div class="fieldname"></div>
			<div>						
				<input class="fieldinput" style="margin-right: 0" type="text" id="tagName" list="tagList" /><button id="tagButton" class="button" style="margin-left: 0; border-left: 0; border-top-left-radius: 0; border-bottom-left-radius: 0" onclick="tag_click(); return false;">Add Tag</button>

				<datalist id="tagList">
					<?php foreach($this->tags as $tag): ?>
					<?php if(!in_array($tag, $this->item['tags'])): ?>
					<option value="<?php print $tag['text'] ?>" id="tagopt<?php print $tag['id']?>" />
					<?php else: ?>
					<option value="<?php print $tag['text'] ?>" id="tagopt<?php print $tag['id']?>" disabled />
					<?php endif; ?>
					<?php endforeach;?>
				</datalist>
			</div>
		</div>
		<div class="fieldinfo">Description about tags goes here</div>

		<div class="field">
			<label for="description" class="fieldname">Description</label>
			<textarea class="fieldinput" id="description" name="description" rows="3"><?php print $this->item['description'] ?></textarea>
		</div>
		<div class="field">
			<label for="contact" class="fieldname">Contact</label><select class="fieldinput" id="contact" name="contact">
				<?php foreach($this->users as $user): ?>
				<?php if($user['id'] == $this->item['contact']): ?>
				<option value="<?php print $user['id'] ?>" selected="selected">
					<?php print $user['name']?>
				</option>
				<?php else: ?>
				<option value="<?php print $user['id'] ?>">
					<?php print $user['name']?>
				</option>
				<?php endif; ?>
				<?php endforeach;?>
			</select>
		</div>
		<div class="field">
			<label for="serial" class="fieldname">Serial Number</label>
			<input class="fieldinput" class="text" id="serial" name="serial" value="<?php print $this->item['serial'] ?>" />
		</div>
		<div class="fieldinfo">Optional</div>
		<div class="field">
			<div class="fieldname"></div>
			<?php if($this->item['bookable']): ?>
			<input type="checkbox" name="bookable" id="bookable" checked="checked" value="1" />
			<?php else: ?>
			<input type="checkbox" name="bookable" id="bookable" value="1" />
			<?php endif; ?>
			<label for="bookable">Allow this item to be Booked</label>
		</div>
		<div class="field">
			<div class="fieldname"></div>
			<?php if($this->item['controlled']): ?>
			<input type="checkbox" name="controlled" id="controlled" checked="checked" value="1" />
			<?php else: ?>
			<input type="checkbox" name="controlled" id="controlled" value="1" />
			<?php endif; ?>
			<label for="controlled">Only allow this item to be collected/returned by the contact and administrators</label>
		</div>		
	</div>

	<input type="submit" class="book" style="float: right" value="Save Changes" />
</form>
