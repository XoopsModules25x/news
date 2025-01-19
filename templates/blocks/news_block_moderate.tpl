<table class="outer" cellspacing="1">
    <tr>
        <th align="center"><{$block.lang_story_title}></th>
        <th align="center"><{$block.lang_story_topic}></th>
        <th align="center"><{$block.lang_story_date}></th>
        <th align="center"><{$block.lang_story_author}></th>
        <th align="center"><{$block.lang_story_action}></th>
    </tr>
    <{foreach item=news from=$block.stories|default:null}>
        <tr class="<{cycle values="even,odd"}>">
            <td align="left"><{$news.title}></td>
            <td align="left"><{$news.topic_title}></td>
            <td align="center"><{$news.date}></td>
            <td align="left"><{$news.author}></td>
            <td align="center"><{$news.action}></td>
        </tr>
    <{/foreach}>
</table>
