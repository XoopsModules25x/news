<div class="news-whoswho">
    <h2><{$smarty.const._AM_NEWS_WHOS_WHO}></h2>

    <h3><{$smarty.const._NW_NEWS_LIST_OF_AUTHORS}></h3>
    <ul>
        <{foreach item=who from=$whoswho}>
            <li><a title="<{$who.name}>"
                   href="<{$xoops_url}>/modules/<{$xoops_dirname}>/newsbythisauthor.php?uid=<{$who.uid}>"><{$who.name}></a></li>
        <{/foreach}>
    </ul>
</div>
