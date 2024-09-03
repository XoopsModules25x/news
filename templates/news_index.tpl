<div class="news-index">
    <{if $topic_rssfeed_link|default:'' != ''}>
        <div align='right'><{$topic_rssfeed_link}></div>
    <{/if}>

    <{if $displaynav === true}>
        <div style="text-align: center;">
            <form name="form1" action="<{$xoops_url}>/modules/<{$xoops_dirname}>/index.php" method="get">
                <{$topic_select}> <select name="storynum"><{$storynum_options}></select> <input type="submit" value="<{$lang_go}>" class="formButton">
            </form>
            <hr>
        </div>
    <{/if}>

    <{if $topic_description|default:'' != ''}>
        <div style="text-align: center;"><{$topic_description}></div>
    <{/if}>

    <div style="margin: 10px;"><{$pagenav}></div>
    <table width='100%' border='0'>
        <tr>
            <{section name=i loop=$columns}>
                <td width="<{$columnwidth}>%"><{foreach item=story from=$columns[i]|default:null}><{include file="db:news_item.tpl" story=$story}><{/foreach}></td>
            <{/section}>
        </tr>
    </table>

    <div class="pagenav"><{$pagenav}></div>
    <{include file='db:system_notification_select.tpl'}>
</div>
