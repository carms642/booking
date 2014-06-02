<script>
var tags = [];
<?php foreach($this->tags as $tag): ?>
	tags[<?php print $tag['id'] ?>] = "<?php print $tag['text'] ?>";
<?php endforeach;?>

function tag_click()
{
	var tagSelect = document.getElementById("tagName");
	for(tag in tags)
	{
		if(tags[tag].toLowerCase() == tagSelect.value.toLowerCase())
		{
			add_tag(tag);
			tagSelect.value = "";
			return;
		}
	}

	add_tag(tagSelect.value);

	tagSelect.value = "";
}

function add_tag(tagid)
{
	var tagOpt = document.getElementById("tagopt" + tagid);
	if(tagOpt)
	{
		tagOpt.disabled = true;
	}

	if(document.getElementById("tag" + tagid) != null)
	{
		return;
	}

	var tagElem = document.createElement("div");
	tagElem.className="tag largetag";
	tagElem.id = "tag" + tagid;

	var tagInput = document.createElement("input");
	tagInput.name="tags[]";
	tagInput.type="hidden";
	tagInput.value=tagid;

	var tagLink = document.createElement("div");
	if(tagid in tags)
	{
		tagLink.textContent = tags[tagid];
	}
	else
	{
		tagLink.textContent = tagid;
	}

	var tagLinkx = document.createElement("a");
	tagLinkx.className="tagx";
	tagLinkx.textContent = "x";
	tagLinkx.href = "javascript:remove_tag(" + tagid + ")";

	tagElem.appendChild(tagInput);
	tagElem.appendChild(tagLink);
	tagElem.appendChild(tagLinkx);

	var tagsElem = document.getElementById("tags");
	tagsElem.appendChild(tagElem);

	var notagsElem = document.getElementById("notags");
	notagsElem.style.display = 'none';
}

function remove_tag(tagid)
{
	var tagElem = document.getElementById("tag" + tagid);
	tagElem.parentNode.removeChild(tagElem);

	var tagOpt = document.getElementById("tagopt" + tagid);
	tagOpt.disabled = false;

	var tagsElem = document.getElementById("tags");
	if(tagsElem.childElementCount == 0)
	{
		var notagsElem = document.getElementById("notags");
		notagsElem.style.display = 'block';
	}
}

</script>

<form action="command/edititem.php" method="post" enctype="multipart/form-data">
	<div class="details">
		<div class="titleblock">
			<div class="titlepanel">
				<input name="oldcode" type="hidden" value="<?php print $this->item['code'] ?>" />
				<input name="name" type="text" class="title text" value="<?php print $this->item['name'] ?>" placeholder="Name"
					maxlength="50" required />
				<input name="code" type="text" class="text" value="<?php print $this->item['code'] ?>" placeholder="Code" maxlength="20"
					required />
                <?php if($this->item): ?>
    			<a class="adminbutton" href="command/deleteitem.php?code=<?php print $this->item['code'] ?>"
    				onclick="return confirm('Delete this item?')">Delete Item</a>
    			<?php endif;?>
			</div>
		</div>

		<?php if ($this->item['image']): ?>
		<div><img class="itemimage" src="uploads/<?php print $this->item['image'] ?>" /></div>
		<?php endif;?>
	</div>
	<div>
		<div class="field">
			<div class="fieldname">Tags</div><div class="fielditem">
			<div>
				<div id="notags" <?php if(count($this->item['tags']) > 0) { print "style='display:none'"; }?>>No tags</div>
				<div id="tags">
					<?php if(count($this->item['tags']) > 0):?>
					<?php foreach($this->item['tags'] as $tag): ?>
					<div class="tag largetag" id="tag<?php print $tag['id']?>">
						<input name="tags[]" type="hidden" value="<?php print $tag['id'] ?>" />
						<div>
							<?php print $tag['text'] ?>
						</div>
						<a class="tagx" href="javascript:remove_tag(<?php print $tag['id'] ?>)">x</a>
					</div>
					<?php endforeach;?>
					<?php endif;?>
				</div></div>
			</div>
		</div>
		<div class="field">
			<div class="fieldname"></div><div class="fielditem">
            <input class="fieldinput" style="margin-right: 0" type="text" id="tagName" list="tagList" autocomplete="off" /><button id="tagButton" class="button" style="margin-left: 0; border-left: 0; border-top-left-radius: 0; border-bottom-left-radius: 0" onclick="tag_click(); return false;">Add Tag</button>

				<datalist id="tagList">
					<?php foreach($this->tags as $tag): ?>
					<option value="<?php print $tag['text'] ?>" id="tagopt<?php print $tag['id']?>" <?php if($this->item['tags'] && in_array($tag, $this->item['tags'])) { print "disabled"; } ?> />
					<?php endforeach;?>
				</datalist>
			</div>
		</div>
		<div class="fieldinfo">Description about tags goes here</div>

		<div class="field">
			<label for="description" class="fieldname">Description</label><div class="fielditem">
			<textarea class="fieldinput" id="description" name="description" rows="3"><?php print $this->item['description'] ?></textarea></div>
		</div>
		<div class="field">
			<label for="image" class="fieldname">Image</label><div class="fielditem">
			<input type="file" name="image" id="image" class="fieldinput" accept="image/*"></div>
		</div>
		<div class="fieldinfo">Optional</div>
		<div class="field">
			<label for="contact" class="fieldname">Contact</label><div class="fielditem">
            <select class="fieldinput" id="contact" name="contact">
				<?php foreach($this->users as $user): ?>
				<option value="<?php print $user['id'] ?>" <?php if($user['id'] == $this->item['contact']) { print "selected=\"selected\""; } ?>>
					<?php print $user['name']?>
				</option>
				<?php endforeach;?>
			</select>
		</div></div>
		<div class="field">
			<label for="serial" class="fieldname">Serial Number</label><div class="fielditem">
			<input class="fieldinput" class="text" id="serial" name="serial" value="<?php print $this->item['serial'] ?>" />
		</div></div>
		<div class="fieldinfo">Optional</div>
		<div class="field">
			<div class="fieldname"></div><div class="fielditem">
			<input type="checkbox" class="fieldinput" name="bookable" id="bookable" <?php if($this->item['bookable']) { print "checked=\"checked\""; } ?> value="1" />
			<label for="bookable"><span></span>Allow this item to be Booked</label>
		</div></div>
		<div class="field">
			<div class="fieldname"></div><div class="fielditem">
			<input type="checkbox" class="fieldinput" name="controlled" id="controlled" <?php if($this->item['controlled']) { print "checked=\"checked\""; } ?> value="1" />
			<label for="controlled"><span></span>Only allow this item to be collected/returned by the contact and administrators</label>
		</div></div>
	</div>

	<div class="right">
	<input type="submit" class="book fieldbutton"value="Save Changes" />
	</div>
</form>