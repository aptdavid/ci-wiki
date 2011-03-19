
<h3><?=$page->title?></h3>

<?= $errors ?>

<form method="post" action="<?=site_url()?>/wiki/<?=$page->title?>" accept-charset="utf-8">
  <input type="hidden" name="id" value="<?=$page->id?>" />
  <input type="hidden" name="namespace" value="<?=$namespace?>" />
  <label for="title"><?=lang('page_name')?></label><br/>
  <?=$namespace . ($namespace == '' ? '' : '::')?><input name="title" size="50" value="<?=$page_name?>" /><br/>
  <label for="bodytext"><?=lang('body')?></label><br/>
  <textarea name="bodytext" cols="80" rows="20"><?=$page->body?></textarea><br/>
  <input class="ok" type="submit" name="save" value="<?=lang('save')?>" />
  <input type="submit" name="cancel" value="<?=lang('cancel')?>" />
</form>