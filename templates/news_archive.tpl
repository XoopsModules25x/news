<div class="news-archive">
    <table>
        <tr>
            <th><{$lang_newsarchives}></th>
        </tr>
        <{foreach item=year from=$years}>
            <{foreach item=month from=$year.months}>
                <tr class="even">
                    <td>
                        <a title="<{$month.string}> <{$year.number}>" href="<{$xoops_url}>/modules/news/archive.php?year=<{$year.number}>&amp;month=<{$month.number}>"><{$month.string}> <{$year.number}></a>
                    </td>
                </tr>
            <{/foreach}>
        <{/foreach}>
    </table>

    <{if $show_articles == true}>
        <table>
            <tr>
                <th><{$lang_articles}></th>
                <th align="center"><{$lang_actions}></th>
                <th align="center"><{$lang_views}></th>
                <th align="center"><{$lang_date}></th>
            </tr>
            <{foreach item=story from=$stories}>
                <tr class="<{cycle values="even,odd"}>">
                    <td><{$story.title}></td>
                    <td align="center"><a title="<{$lang_printer}>" href="<{$story.print_link}>" rel="nofollow"><img src="<{xoModuleIcons16 printer.png}>" border="0" alt="<{$lang_printer}>"/></a>
                        <a title="<{$lang_sendstory}>" href="<{$story.mail_link}>" target="_top"/><img src="<{xoModuleIcons16 mail_forward.png}>" border="0" alt="<{$lang_sendstory}>"/></a></td>
                    <td align="center"><{$story.counter}></td>
                    <td align="center"><{$story.date}></td>
                </tr>
            <{/foreach}>
        </table>
        <div><{$lang_storytotal}></div>
    <{/if}>
</div>
