<div class="item">
    <div class="itemHead">
        <span class="itemTitle">
            <h2><{$story.news_title}></h2>
        </span>
    </div>
    <h6><i><{$story.subtitle}></i></h6>

    <div class="itemInfo">
        <{if $story.files_attached}><{$story.attached_link}>&nbsp;<{/if}>
        <{if $story.poster != ''}><span class="itemPoster"><{$lang_postedby}> <{$story.poster}></span><{/if}>
        <span class="itemPostDate"><{$lang_on}> <{$story.posttime}></span>
        (<span class="itemStats"><{$story.hits}> <{$lang_reads}></span>)
        <{$news_by_the_same_author_link}>
        <!--<span class="itemTopic"><{$lang_topic}> <{$story.topic_title}></span>-->
    </div>
    <div class="itemBody">
        <{if $story.picture != ''}>
            <img class="left" src="<{$story.picture}>" alt="<{$story.pictureinfo}>"/>
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
