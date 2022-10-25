<div class="item">
    <div class="itemHead">
        <span class="itemTitle">
           <{if $displaytopictitle === true}> <{$story.topic_title}> - &nbsp; <{/if}> <h2><{$story.news_title}></h2>
        </span>
    </div>
    <h6><i><{$story.subtitle|default:''}></i></h6>

    <div class="itemInfo">
        <{if $story.files_attached|default:''}><{$story.attached_link}>&nbsp;<{/if}>
        <{if $story.poster|default:'' != ''}><span class="itemPoster"><{$lang_postedby}> <{$story.poster}> </span><{/if}>
        <span class="itemPostDate"><{$lang_on}> <{$story.posttime}></span>
        (<span class="itemStats"><{$story.hits}> <{$lang_reads}></span>)
        <{$news_by_the_same_author_link|default:''}>
        <!--<span class="itemTopic"><{$lang_topic|default:''}> <{$story.topic_title|default:''}></span>-->
    </div>
    <div class="itemBody">
        <{if $story.picture|default:'' != ''}>
            <img class="left" src="<{$story.picture}>" alt="<{$story.pictureinfo}>">
        <{else}>
            <{$story.imglink}>
        <{/if}>
        <div class="itemText"><{$story.text}></div>
        <div class="clear"></div>
    </div>
    <div class="itemFoot">
        <span class="itemAdminLink"><{$story.adminlink}></span>
        <{if $rates}><b><{$lang_ratingc}></b> <{$story.rating}> (<{$story.votes}>) -
            <a title="<{$lang_ratethisnews}>" href="<{$xoops_url}>/modules/news/ratenews.php?storyid=<{$story.id}>"
               rel="nofollow"><{$lang_ratethisnews}></a>
            - <{/if}>
        <span class="itemPermaLink"><{$story.morelink}></span>
    </div>
</div>
