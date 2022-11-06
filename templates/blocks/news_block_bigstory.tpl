<div class="news-bigstory">
    <p><{$block.message}></p>
    <{if isset($block.story_id) && $block.story_id|default:'' != ''}>
        <h2>
            <a href="<{$xoops_url}>/modules/news/article.php?storyid=<{$block.story_id}>"<{$block.htmltitle}>><{$block.story_title}></a>
        </h2>
    <{/if}>
</div>
