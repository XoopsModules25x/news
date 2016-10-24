<div class="mainmenu news-mainmenu">
    <{foreach item=topic from=$block.topics}>
        <h2><a class="menuMain" title="<{$topic.title}>"
               href="<{$xoops_url}>/modules/news/index.php?storytopic=<{$topic.id}>"><{$topic.title}> <{$topic.news_count}></a><br>
        </h2>
    <{/foreach}>
</div>
