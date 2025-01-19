<div class="news-topics-directory">
    <h2><{$smarty.const._AM_NEWS_TOPICS_DIRECTORY}></h2>

    <div class="pad2">
        <ul>
            <{foreach item=topic from=$topics}>
                <li><{$topic.prefix}><a title="<{$topic.title}>"
                                        href="<{$xoops_url}>/modules/<{$xoops_dirname}>/index.php?storytopic=<{$topic.id}>"><{$topic.title}></a>
                    (<{$topic.news_count}>)
                </li>
            <{/foreach}>
        </ul>
    </div>
</div>
