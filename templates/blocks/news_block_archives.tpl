<div class="news-archives">
    <h2><{$smarty.const._NW_NEWSARCHIVES}></h2>
    <ul>
        <{foreach item=onedate from=$block.archives}>
            <li>
                <a title="<{$onedate.formated_month}> <{$onedate.year}>"
                   href="<{$xoops_url}>/modules/<{$xoops_dirname}>/archive.php?year=<{$onedate.year}>&amp;month=<{$onedate.month}>"><{$onedate.formated_month}> <{$onedate.year}></a>
            </li>
        <{/foreach}>
    </ul>
</div>
