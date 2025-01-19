<div class="news-author">
    <h2><{$lang_news_by_this_author}> <{$author_name_with_link}></h2>
    <br><img src='<{$user_avatarurl}>' border='0' alt=''>
    <br>
    <table width='100%' border='0'>
        <{foreach item=topic from=$topics|default:null}>
            <tr>
                <{if isset($news_rating)}>
                <th colspan='4'><{else}>
                <th colspan='3'><{/if}><{$topic.topic_link}></th>
            </tr>
            <tr>
                <td><{$lang_date}></td>
                <td><{$lang_title}></td>
                <td><{$lang_hits}></td><{if isset($news_rating)}>
                <td><{$lang_rating}></td><{/if}>
            </tr>
            <{foreach item=article from=$topic.news|default:null}>
                <tr>
                    <td><{$article.published}></td>
                    <td><{$article.article_link}></td>
                    <td align='right'><{$article.hits}></td><{if isset($news_rating)}>
                    <td align='right'><{$article.rating}></td><{/if}>
                </tr>
            <{/foreach}>
            <tr>
                <td colspan='2' align='left'><{$topic.topic_count_articles}></td>
                <td align='right'><{$topic.topic_count_reads}></td><{if isset($news_rating)}>
                <td>&nbsp;</td><{/if}>
            </tr>
            <tr><{if isset($news_rating)}>
                <td colspan='4'><{else}>
                <td colspan='3'><{/if}>&nbsp;</td>
            </tr>
        <{/foreach}>
    </table>
</div>
