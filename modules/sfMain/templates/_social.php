<?php
/**
 * Социальные виджеты
 *
 * @param  string $url
 * @param  int    $vkontakteId
 */
?>

<div class="social-buttons">

<?php // FaceBook ?>
<object
    style="width: 112px; height: 64px; overflow: hidden; vertical-align: bottom;"
    data="http://www.facebook.com/plugins/like.php?href=<?php echo $url ?>&amp;layout=box_count&amp;show_faces=false&amp;width=100&amp;action=like&amp;colorscheme=light&amp;height=60">
</object>


<?php // Twitter ?>
<a href="http://twitter.com/share?url=<?php echo $url; ?>&amp;count=vertical" class="twitter-share-button">Tweet</a>
<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>


<?php // VKontakt ?>
<?php if (!empty($vkontakteId)): ?>
<script type="text/javascript" src="http://userapi.com/js/api/openapi.js?20"></script>
<script type="text/javascript">
  VK.init({apiId: <?php echo (int)$vkontakteId; ?>, onlyWidgets: true});
</script>

<span id="vk_like"></span>
<script type="text/javascript">
    VK.Widgets.Like("vk_like", {type: "mini", pageUrl: '<?php echo $url ?>'});
</script>
<?php endif; ?>

</div>
