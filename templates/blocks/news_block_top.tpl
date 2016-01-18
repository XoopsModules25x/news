<{if $block.displayview==2}>        <{* Classical view *}>
    <style type="text/css">
    #fullSupport {
        padding: 1.5em;
        background: <{$block.color2}>;
        min-height: 300px;
    }

    <{if $block.tabskin==1}>
    <{* Bar Style *}>
    #tabNavigation {
        background: #F90;
        border-bottom: 1px solid #000;
        border-top: 1px solid #000;
        list-style: none outside none;
        color: inherit;
        margin: 0;
        padding: 0
    }

    html #tabNavigation/* */  {
        padding: 4px 0 4px 0
    }

    html > body #tabNavigation {
        margin: 0;
        padding: 4px 0 4px 0
    }

    #tabNavigation li {
        display: inline;
        line-height: 1em
    }

    #tabNavigation a, #tabNavigation a:link, #tabNavigation a:visited {
        background: <{$block.color4}>;
        border-bottom: 1px solid #000;
        border-right: 1px solid #000;
        color: #FFF;
        cursor: pointer;
        height: 1em;
        margin: -1px 0 -1px 0;
        padding: 3px 6px 3px 6px;
        text-decoration: none
    }

    html #tabNavigation a/* */, html #tabNavigation a:link/* */, html #tabNavigation a:visited/* */  {
        border-bottom: none;
        height: auto;
        margin: 0
    }

    html > body #tabNavigation a, html > body #tabNavigation a:link, html > body #tabNavigation a:visited {
        border-bottom: none;
        padding: 4px 6px 4px 6px
    }

    \head + body #tabNavigation a, \head + body #tabNavigation a:link, \head + body #tabNavigation a:visited {
        padding: 3px 6px 3px 6px
    }

    #tabNavigation a:hover {
        background: <{$block.color5}>;
        color: inherit
    }

    #tabNavigation a:active {
        background: #CCC;
        border-right: 1px solid #000;
        color: inherit
    }

    #tabNavigation .selectedTab a, #tabNavigation .selectedTab a:link, #tabNavigation .selectedTab a:visited, #tabNavigation .selectedTab a:hover {
        background: <{$block.color3}>;
        border-bottom: none;
        border-right: 1px solid #000;
        border-top: 1px solid #000;
        color: #000;
        cursor: text;
        padding: 3px 5px 4px 5px
    }

    html > body #tabNavigation .selectedTab a, html > body #tabNavigation .selectedTab a:link, html > body #tabNavigation .selectedTab a:visited {
        padding: 4px 5px 5px 5px
    }

    \head + body #tabNavigation .selectedTab a, \head + body #tabNavigation .selectedTab a:link, \head + body #tabNavigation .selectedTab a:visited, \head + body #tabNavigation .selectedTab a:hover {
        padding: 3px 5px 4px 5px
    }

    .fixTabsIE {
        visibility: hidden
    }

    <{elseif $block.tabskin==2}>
    <{* Beveled *}>
    #tabNavigation {
        border-bottom: 1px solid #000;
        list-style: none outside none;
        margin: 0;
        padding: 0
    }

    html #tabNavigation/* */  {
        padding: 4px 0 2px 0
    }

    html > body #tabNavigation {
        padding: 3px 0 1px 0
    }

    head + body #tabNavigation {
        padding: 4px 0 2px 0
    }

    #tabNavigation li {
        border-left: 1px solid #000;
        border-right: 1px solid #000;
        border-top: 1px solid #000;
        display: inline;
        height: 1em;
        margin: 0 0 0 3px;
        padding: 0;
        z-index: 1000
    }

    html #tabNavigation li/* */  {
        height: auto
    }

    html > body #tabNavigation li {
        height: auto;
        margin: 0 -5px 0 -3px;
        padding: 3px 5px 2px 5px
    }

    html > body ul[id]#tabNavigation li {
        margin: 0 0 0 3px;
        padding: 3px 0 2px 0
    }

    #tabNavigation a, #tabNavigation a:link, #tabNavigation a:visited {
        background: <{$block.color4}>;
        border-left: 1px solid #CCC;
        border-right: 1px solid #CCC;
        border-top: 1px solid #CCC;
        color: #FFF;
        height: 1em;
        padding: 2px 4px 2px 4px;
        text-decoration: none
    }

    html #tabNavigation a/* */, html #tabNavigation a:link/* */, html #tabNavigation a:visited/* */  {
        height: auto
    }

    #tabNavigation a:hover {
        background: <{$block.color5}>;
        border-left: 1px solid #888;
        border-right: 1px solid #888;
        border-top: 1px solid #888;
        color: #FFF
    }

    #tabNavigation a:active {
        background: #C60;
        border-left: 1px solid #E80;
        border-right: 1px solid #E80;
        border-top: 1px solid #E80;
        color: #FFF
    }

    html > body #tabNavigation li.selectedTab {
        margin: 0 -5px 0 -3px;
        padding: 3px 5px 2px 5px
    }

    html > body ul[id]#tabNavigation li.selectedTab {
        margin: 0 0 0 3px;
        padding: 3px 0 2px 0
    }

    #tabNavigation .selectedTab a, #tabNavigation .selectedTab a:link, #tabNavigation .selectedTab a:visited, #tabNavigation .selectedTab a:hover {
        background: <{$block.color3}>;
        border-left: 1px solid #FC3;
        border-right: 1px solid #FC3;
        border-top: 1px solid #FC3;
        color: #FFF;
        margin: -2px 0 0 0;
        padding: 3px 4px 3px 4px;
        position: relative;
        top: 2px
    }

    html #tabNavigation .selectedTab a/* */, html #tabNavigation .selectedTab a:link/* */, html #tabNavigation .selectedTab a:visited/* */, html #tabNavigation .selectedTab a:hover/* */  {
        margin: -1px 0 0 0;
        top: 1px
    }

    html > body #tabNavigation .selectedTab a, html > body #tabNavigation .selectedTab a:link, html > body #tabNavigation .selectedTab a:visited, html > body #tabNavigation .selectedTab a:hover {
        padding: 2px 4px 2px 4px;
        top: 0
    }

    head:first-child + body #tabNavigation .selectedTab a, head:first-child + body #tabNavigation .selectedTab a:link, head:first-child + body #tabNavigation .selectedTab a:visited, head:first-child + body #tabNavigation .selectedTab a:hover {
        margin: -1px 0 0 0;
        padding: 2px 4px 4px 4px;
        top: 0
    }

    head:first-child + body ul[id]#tabNavigation .selectedTab a, head:first-child + body ul[id]#tabNavigation .selectedTab a:link, head:first-child + body ul[id]#tabNavigation .selectedTab a:visited, head:first-child + body ul[id]#tabNavigation .selectedTab a:hover {
        padding: 3px 4px 3px 4px;
        top: 1px
    }

    .fixTabsIE {
        visibility: hidden
    }

    <{elseif $block.tabskin==3}>
    <{* Classic *}>
    ul, li {
        list-style: disc;
        margin: 0 10px 0 10px
    }

    #tabNavigation {
        background: #789;
        color: inherit;
        list-style: none outside none;
        margin: 0;
        padding: 0
    }

    html #tabNavigation/* */  {
        padding: 6px 0 6px 1px
    }

    html > body #tabNavigation {
        margin: 0;
        padding: 6px 0 6px 1px;
    }

    #tabNavigation li {
        display: inline;
        line-height: 1em;
        margin: 0;
        padding: 0
    }

    #tabNavigation a, #tabNavigation a:link, #tabNavigation a:visited {
        background: url(<{$block.imagesurl}>unselectedEnd.gif) <{$block.color4}> no-repeat scroll top right;
        color: #FFF;
        cursor: pointer;
        height: 1em;
        padding: 5px 21px 5px 2px;
        text-decoration: none;
        z-index: 1000
    }

    html #tabNavigation a/* */, html #tabNavigation a:link/* */, html #tabNavigation a:visited/* */  {
        height: auto;
        margin: 0;
        padding: 5px 21px 5px 2px
    }

    #tabNavigation a:hover {
        background: url(<{$block.imagesurl}>unselectedEnd.gif) <{$block.color5}> no-repeat scroll top right;
        color: #FFF;
        text-decoration: underline
    }

    #tabNavigation a:active {
        background: url(<{$block.imagesurl}>unselectedEnd.gif) #789 no-repeat scroll top right;
        color: #567;
        text-decoration: none
    }

    #tabNavigation li.selectedTab {
        background: url(<{$block.imagesurl}>selectedStart.gif) #FFF no-repeat scroll top left;
        color: inherit;
        margin: 0 0 0 -22px;
        padding: 0 0 0 23px
    }

    html > body #tabNavigation li.selectedTab {
        background: url(<{$block.imagesurl}>selectedStart.gif) #FFF no-repeat scroll top left;
        color: inherit;
        margin: 0 0 0 -22px;
        padding: 5px 1px 5px 22px
    }

    html > body ul[id]#tabNavigation li.selectedTab {
        background: url(<{$block.imagesurl}>selectedStart.gif) #FFF no-repeat scroll top left;
        color: inherit;
        margin: 0 0 0 -22px;
        padding: 5px 0 5px 23px
    }

    #tabNavigation .selectedTab a, #tabNavigation .selectedTab a:link, #tabNavigation .selectedTab a:visited, #tabNavigation .selectedTab a:hover {
        background: transparent url(<{$block.imagesurl}>selectedEnd.gif) no-repeat scroll top right;
        border-bottom: none;
        color: #000;
        cursor: text;
        padding: 5px 21px 5px 2px;
        text-decoration: none
    }

    html #tabNavigation .selectedTab a/* */, html #tabNavigation .selectedTab a:link/* */, html #tabNavigation .selectedTab a:visited/* */, html #tabNavigation .selectedTab a:hover/* */  {
        padding: 5px 21px 5px 1px
    }

    #tabNavigation .fixTabsIE a, #tabNavigation .fixTabsIE a:link, #tabNavigation .fixTabsIE a:visited, #tabNavigation .fixTabsIE a:hover {
        display: none;
    }

    <{elseif $block.tabskin==4}>
    <{* Folders *}>
    #tabNavigation {
        border-bottom: 1px solid #C60;
        list-style: none outside none;
        margin: 0;
        padding: 0 0 0 20px
    }

    \html #tabNavigation/* */  {
        margin: 0;
        padding: 3px 0 3px 20px
    }

    html > body #tabNavigation {
        margin: 0;
        padding: 0 0 1px 20px
    }

    \head + body #tabNavigation {
        padding: 0 0 3px 20px
    }

    html > body ul[id] #tabNavigation {
        padding: 0 0 0 20px
    }

    #tabNavigation li, #subNavigation li {
        display: inline;
        list-style: none outside none
    }

    #tabNavigation .preloadUnselected {
        background: transparent url(<{$block.imagesurl}>unselected.gif);
    }

    #tabNavigation .preloadSelected {
        background: transparent url(<{$block.imagesurl}>selected.gif);
    }

    #tabNavigation .preloadHover {
        background: transparent url(<{$block.imagesurl}>hover.gif);
    }

    #tabNavigation .preloadActive {
        background: transparent url(<{$block.imagesurl}>active.gif);
    }

    html > body #tabNavigation li {
        background: transparent url(<{$block.imagesurl}>unselected.gif) no-repeat top left;
        border-right: 1px solid #666;
        display: block;
        float: left;
        height: 1em;
        margin: 3px 5px 3px -15px;
        padding: 3px 5px 5px 27px
    }

    head:first-child + body #tabNavigation li {
        background: none;
        border-right: none;
        display: inline;
        float: none;
        margin: 0;
        padding: 0
    }

    #tabNavigation a, #tabNavigation a:link, #tabNavigation a:visited {
        background: transparent url(<{$block.imagesurl}>unselected.gif) no-repeat top left;
        border-right: 1px solid #666;
        color: #FFF;
        display: inline;
        height: 1em;
        margin: 0 0 0 -15px;
        padding: 3px 5px 3px 27px;
        text-decoration: none
    }

    html > body #tabNavigation a, html > body #tabNavigation a:link, html > body #tabNavigation a:visited {
        border-right: none;
        margin: 0;
        padding: 0
    }

    head:first-child + body #tabNavigation a, head:first-child + body #tabNavigation a:link, head:first-child + body #tabNavigation a:visited {
        border-right: 1px solid #666;
        margin: 0 0 0 -15px;
        padding: 3px 5px 3px 27px;
        position: relative;
        z-index: 50
    }

    #tabNavigation a:hover {
        background: transparent url(<{$block.imagesurl}>hover.gif) no-repeat top left;
        border-right: 1px solid #333;
        color: #FFF;
        text-decoration: none
    }

    html > body #tabNavigation a:hover {
        border-right: none;
        text-decoration: underline
    }

    head:first-child + body #tabNavigation a:hover {
        border-right: 1px solid #333;
        padding: 4px 5px 3px 27px;
        position: relative;
        text-decoration: none;
        z-index: 5000
    }

    #tabNavigation a:active {
        background: transparent url(<{$block.imagesurl}>active.gif) no-repeat top left;
        color: #FFF;
        text-decoration: none
    }

    html > body #tabNavigation a:active {
        text-decoration: underline
    }

    head:first-child + body #tabNavigation a:active {
        text-decoration: none
    }

    html > body #tabNavigation li.selectedTab {
        background: transparent url(<{$block.imagesurl}>selected.gif) no-repeat top left;
        border-right: 1px solid #C60;
        display: block;
        float: left;
        height: 1em;
        margin: 3px 5px 5px -15px;
        padding: 3px 5px 5px 27px
    }

    head:first-child + body #tabNavigation li.selectedTab {
        background: none;
        border-right: none;
        display: inline;
        float: none;
        margin: 0;
        padding: 0
    }

    #tabNavigation .selectedTab a, #tabNavigation .selectedTab a:link, #tabNavigation .selectedTab a:visited {
        background: transparent url(<{$block.imagesurl}>selected.gif) no-repeat top left;
        border-right: 1px solid #C60;
        color: #FFF;
        cursor: text;
        display: inline;
        height: 1em;
        margin: 0 0 0 -15px;
        padding: 3px 5px 3px 27px
    }

    html > body #tabNavigation .selectedTab a, html > body #tabNavigation .selectedTab a:link, html > body #tabNavigation .selectedTab a:visited {
        border-right: none;
        margin: 0;
        padding: 0
    }

    head:first-child + body #tabNavigation .selectedTab a, head:first-child + body #tabNavigation .selectedTab a:link, head:first-child + body #tabNavigation .selectedTab a:visited, head:first-child + body #tabNavigation .selectedTab a:hover {
        background: transparent url(<{$block.imagesurl}>selected.gif) no-repeat top left;
        border-right: 1px solid #C60;
        margin: 0 0 0 -15px;
        padding: 3px 5px 3px 27px;
        position: relative;
        z-index: 10000
    }

    \html head:first-child + body #tabNavigation .selectedTab a, \html head:first-child + body #tabNavigation .selectedTab a:link, \html head:first-child + body #tabNavigation .selectedTab a:visited, \html head:first-child + body #tabNavigation .selectedTab a:hover {
        padding: 4px 5px 5px 27px
    }

    .fixTabsIE {
        visibility: hidden
    }

    <{elseif $block.tabskin==5}>
    <{* MacOs *}>
    #tabNavigation {
        background: #CCC;
        border-bottom: 1px solid #999;
        border-top: 1px solid #FFF;
        color: inherit;
        list-style: none outside none;
        margin: 0;
        padding: 0;
    }

    html #tabNavigation/* */  {
        padding: 4px 0 4px 0
    }

    html > body #tabNavigation {
        margin: 0;
        padding: 4px 0 4px 0
    }

    #tabNavigation li {
        display: inline;
        line-height: 1em
    }

    #tabNavigation a, #tabNavigation a:link, #tabNavigation a:visited {
        background: inherit;
        border: 1px solid #FFF;
        border-right-color: #999;
        border-bottom-color: #999;
        color: #000;
        cursor: pointer;
        height: 1em;
        margin: -1px 0 -1px 0;
        padding: 3px 6px 3px 6px;
        text-decoration: none;
        white-space: normal;
    }

    html #tabNavigation a/* */, html #tabNavigation a:link/* */, html #tabNavigation a:visited/* */  {
        height: auto;
        margin: 0
    }

    html > body #tabNavigation a, html > body #tabNavigation a:link, html > body #tabNavigation a:visited {
        padding: 4px 6px 4px 6px
    }

    \head + body #tabNavigation a, \head + body #tabNavigation a:link, \head + body #tabNavigation a:visited {
        padding: 3px 6px 3px 6px
    }

    #tabNavigation a:hover {
        background: <{$block.color5}>;
        border: 1px solid #CCC;
        border-right-color: #666;
        border-bottom-color: #666;
        color: inherit
    }

    #tabNavigation a:active {
        background: #CCC;
        border: 1px solid #999;
        border-right-color: #FFF;
        border-bottom-color: #FFF;
        color: inherit
    }

    #tabNavigation .selectedTab a, #tabNavigation .selectedTab a:link, #tabNavigation .selectedTab a:visited, #tabNavigation .selectedTab a:hover {
        background: <{$block.color3}>;
        border: 1px solid #FFF;
        border-right-color: #999;
        border-bottom-color: #999;
        color: #000;
        cursor: text;
        font-weight: bold
    }

    #tabNavigation .fixTabsIE a, #tabNavigation .fixTabsIE a:link, #tabNavigation .fixTabsIE a:visited {
        visibility: hidden
    }

    html #tabNavigation .fixTabsIE a/* */, html #tabNavigation .fixTabsIE a:link/* */, html #tabNavigation .fixTabsIE a:visited/* */  {
        background: #CCC;
        border-bottom: none;
        border-left: 1px solid #FFF;
        border-right: none;
        border-top: none;
        color: inherit;
        cursor: text;
        margin: 0;
        padding: 3px 6px 3px 6px;
        visibility: visible
    }

    <{elseif $block.tabskin==6}>
    <{* Plain *}>
    #tabNavigation {
        border-bottom: 1px solid #000;
        font: normal 11px Verdana, Geneva, Arial, Helvetica, sans-serif;
        margin: 0;
        padding: 0 0 18px 0;
    }

    ul#tabNavigation li {
        display: inline;
        list-style: none outside none;
    }

    ul#tabNavigation a, ul#tabNavigation a:link, ul#tabNavigation a:visited {
        background: <{$block.color4}>;
        border: 1px solid #000;
        color: #000;
        float: left;
        margin: 0 0 0 5px;
        padding: 2px 6px 2px 6px;
        text-decoration: none
    }

    ul#tabNavigation a:hover, ul#tabNavigation a:focus {
        background: <{$block.color5}>;
        color: #FFF;
    }

    ul#tabNavigation a:active {
        background: #FFF;
        border-bottom: none;
        border-left: 1px solid #000;
        border-right: 1px solid #000;
        border-top: 1px solid #000;
        color: #00F;
        padding: 2px 6px 3px 6px
    }

    ul#tabNavigation li.selectedTab a, ul#tabNavigation li.selectedTab a:link, ul#tabNavigation li.selectedTab a:visited {
        background: <{$block.color3}>;
        border-bottom: none;
        border-left: 1px solid #000;
        border-right: 1px solid #000;
        border-top: 1px solid #000;
        color: #000;
        cursor: text;
        margin: 0 0 0 5px;
        padding: 2px 6px 3px 6px
    }

    ul#tabNavigation li.fixTabsIE {
        display: none;
        visibility: hidden
    }

    <{elseif $block.tabskin==7}>
    <{* Rounded *}>
    #tabNavigation {
        background: #FFF;
        border-bottom: 1px solid #000;
        color: inherit;
        list-style: none outside none;
        margin: 1px 0 0 0;
        padding: 0;
    }

    html #tabNavigation/* */  {
        padding: 4px 0 4px 0
    }

    html > body #tabNavigation {
        margin: 0;
        padding: 4px 0 4px 0
    }

    #tabNavigation li {
        background: url(<{$block.imagesurl}>unselected_left.gif) #C60 no-repeat scroll top left;
        color: inherit;
        display: inline;
        line-height: 1em;
        margin: 0 0 0 2px;
        padding: 0
    }

    html > body #tabNavigation li {
        margin: 0 0 0 -6px;
        padding: 3px 0 3px 8px
    }

    html > body ul[id]#tabNavigation li {
        margin: 0 0 0 2px;
        padding: 3px 0 3px 0
    }

    #tabNavigation a, #tabNavigation a:link, #tabNavigation a:visited {
        background: transparent url(<{$block.imagesurl}>unselected_right.gif) no-repeat scroll top right;
        border-bottom: 1px solid #000;
        color: #FFF;
        cursor: pointer;
        height: 1em;
        margin: -1px 0 -1px 0;
        padding: 3px 8px 3px 8px;
        text-decoration: none
    }

    html #tabNavigation a/* */, html #tabNavigation a:link/* */, html #tabNavigation a:visited/* */  {
        border-bottom: none;
        height: auto;
        margin: 0 0 0 4px;
        padding: 3px 8px 3px 4px
    }

    #tabNavigation a:hover {
        background: transparent url(<{$block.imagesurl}>unselected_right.gif) no-repeat scroll top right;
        color: #FFF;
        text-decoration: underline
    }

    #tabNavigation a:active {
        background: transparent url(<{$block.imagesurl}>unselected_right.gif) no-repeat scroll top right;
        color: #000;
        text-decoration: underline
    }

    #tabNavigation li.selectedTab {
        background: transparent url(<{$block.imagesurl}>selected_left_F90.gif) no-repeat scroll top left;
        color: inherit;
        padding: 0
    }

    html > body #tabNavigation li.selectedTab {
        margin: 0 0 0 -6px;
        padding: 4px 0 4px 8px
    }

    html > body ul[id]#tabNavigation li.selectedTab {
        margin: 0 0 0 2px;
        padding: 4px 0 4px 0
    }

    #tabNavigation .selectedTab a, #tabNavigation .selectedTab a:link, #tabNavigation .selectedTab a:visited, #tabNavigation .selectedTab a:hover {
        background: transparent url(<{$block.imagesurl}>selected_right_F90.gif) no-repeat scroll top right;
        border-bottom: none;
        color: #000;
        cursor: text;
        padding: 4px 8px 4px 8px;
        text-decoration: none
    }

    html #tabNavigation .selectedTab a/* */, html #tabNavigation .selectedTab a:link/* */, html #tabNavigation .selectedTab a:visited/* */, html #tabNavigation .selectedTab a:hover/* */  {
        padding: 4px 8px 4px 4px
    }

    .fixTabsIE {
        visibility: hidden
    }

    <{elseif $block.tabskin==8}>
    <{* ZDnet *}>
    #tabNavigation {
        list-style: none outside none;
        margin: 0;
        padding: 4px 0 3px 0
    }

    @media all {
        #tabNavigation {
            text-align: center
        }
    }

    #tabNavigation li {
        background: #000;
        display: inline;
        line-height: 1em;
        margin: 0 4px 0 4px;
        padding: 0;
        position: relative;
        top: 10px
    }

    html #tabNavigation li/* */  {
        line-height: 1.2em;
        top: 6px
    }

    html > body #tabNavigation li {
        margin: 0 2px 0 4px;
        padding: 4px 0 4px 0
    }

    #tabNavigation a, #tabNavigation a:link, #tabNavigation a:visited {
        background: <{$block.color4}>;
        border: 1px solid #FFF;
        bottom: 2px;
        color: #FFF;
        cursor: pointer;
        display: inline;
        height: 1em;
        margin: 0 4px 0 0;
        padding: 3px 5px 3px 5px;
        position: relative;
        right: 2px;
        text-decoration: none
    }

    html #tabNavigation a/* */, html #tabNavigation a:link/* */, html #tabNavigation a:visited/* */  {
        height: auto;
        margin: 0 -4px 0 0
    }

    html > body #tabNavigation a, html > body #tabNavigation a:link, html > body #tabNavigation a:visited {
        margin: 0
    }

    #tabNavigation a:hover {
        background: <{$block.color5}>;
        border: 1px solid #FFF;
        bottom: 1px;
        color: #FFF;
        padding: 3px 5px 3px 5px;
        position: relative;
        right: 1px
    }

    #tabNavigation a:active {
        background: #666;
        border: 1px solid #FFF;
        bottom: 0;
        color: #FFF;
        padding: 3px 5px 3px 5px;
        position: relative;
        right: 0
    }

    #tabNavigation li.selectedTab {
        background: <{$block.color3}>;
        display: inline;
        margin: 0 4px 0 4px;
        position: relative;
        top: 4px
    }

    #tabNavigation .selectedTab a, #tabNavigation .selectedTab a:link, #tabNavigation .selectedTab a:visited, #tabNavigation .selectedTab a:hover {
        background: #F90;
        border-bottom: none;
        border-left: 1px solid #000;
        border-right: 1px solid #000;
        border-top: 1px solid #000;
        bottom: 0;
        color: #FFF;
        cursor: text;
        margin: 0 5px 0 0;
        padding: 3px 5px 0 5px;
        position: relative;
        right: 0
    }

    html #tabNavigation .selectedTab a/* */, html #tabNavigation .selectedTab a:link/* */, html #tabNavigation .selectedTab a:visited/* */, html #tabNavigation .selectedTab a:hover/* */  {
        margin: 0 -2px 0 0
    }

    .fixTabsIE {
        visibility: hidden
    }

    <{/if}>
    </style>
    <{* ************************************** Tabs creation ************************************** *}>
    <ul id="tabNavigation">
        <{foreach item=onetab from=$block.tabs}>
            <{if $block.current_tab == $onetab.id}>
                <li class="selectedTab"><a href='#'><{$onetab.title}></a></li>
            <{else}>
                <li><a href="<{$block.url}>NewsTab=<{$onetab.id}>"><{$onetab.title}></a></li>
            <{/if}>
        <{/foreach}>
        <li class="fixTabsIE"><a href="javascript:void(0);">&nbsp;</a></li>
    </ul>
    <{if $block.current_is_spotlight}>
        <div style="border-top: 1px solid rgb(0, 0, 0); background: <{$block.color1}> none repeat scroll 0%; -moz-background-clip: initial; -moz-background-origin: initial; -moz-background-inline-policy: initial;"><{$block.spotlight.author}> <{$block.lang_on}> <{$block.spotlight.date}> <{if $block.use_rating}> - <{$block.spotlight.rating}>/10 (<{$block.spotlight.number_votes}>)<{/if}>
            , <{$block.spotlight.hits}> <{$block.lang_reads}><br/></div>
    <{else}>
        <div style="border-top: 1px solid rgb(0, 0, 0); background: <{$block.color1}> none repeat scroll 0%; -moz-background-clip: initial; -moz-background-origin: initial; -moz-background-inline-policy: initial;">
            <{foreach item=onesummary from=$block.smallheader}>
                <{$onesummary}>&nbsp;
            <{/foreach}>
            <br/></div>
    <{/if}>
    <{* ************************************** Body of the current tab ************************************** *}>
    <div id="fullSupport">
        <{if $block.current_is_spotlight && $block.tabs.id==0}>
            <table border='0'>
                <tr>
                    <td colspan='2'>
                        <table border='0'>
                            <tr>
                                <td><img src='<{$block.spotlight.topic_image}>' border='0' alt=''/></td>
                                <td align='left'><{$block.spotlight.topic_description}></td>
                            </tr>
                        </table>
                        <div class="itemBody">
                            <ul>
                                <li><{$block.spotlight.title_with_link}></li>
                            </ul>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><{$block.spotlight.image}>&nbsp;</td>
                    <td><p class="note"><{$block.spotlight.text}></p></td>
                </tr>
            </table>
            <br/>
            <div style="text-align: center;">
                <hr width='85%'/>
            </div>
            <ul>
                <{foreach item=onenews from=$block.spotlight.news}>
                    <li><{$onenews.date}> - <{$onenews.title_with_link}></li>
                <{/foreach}>
            </ul>
        <{else}>
            <table border='0'>
                <tr>
                    <td><img src='<{$block.topic_image}>' border='0' alt=''/></td>
                    <td align='left'><{$block.topic_description}></td>
                </tr>
            </table>
            <{foreach item=onenews from=$block.news}>
                <div class="itemBody">
                    <ul>
                        <li><{$onenews.title}></li>
                    </ul>
                    <span class="itemStats">&nbsp;&nbsp;<{$onenews.author}> <{$block.lang_on}> <{$onenews.date}> - <{if $block.use_rating}> <{$onenews.rating}>/10 (<{$onenews.number_votes}>)<{/if}>
                        , <{$onenews.hits}> <{$block.lang_reads}></span></div>
                <p class="note"><{$onenews.text}></p>
            <{/foreach}>
        <{/if}>
    </div>
<{else}>    <{* ************************************** Classical view ************************************** *}>
    <table>
        <{if $block.spotlight}>
            <tr>
                <td>
                    <table>
                        <tr>
                            <td colspan='2'>
                                <table border='0'>
                                    <tr>
                                        <td><img src='<{$block.spotlight.topic_image}>' border='0' alt='<{$block.spotlight.title}>'/></td>
                                        <td align='left'><{$block.spotlight.topic_description}></td>
                                    </tr>
                                </table>
                                <span style="color: #FF6600; "><b><{$block.spotlight.title}></b></span> <{$block.spotlight.author}>
                                <{if $block.sort=='counter'}>
                                    (<{$block.spotlight.hits}>)
                                <{elseif $block.sort=='published'}>
                                    (<{$block.spotlight.date}>)
                                <{else}>
                                    (<{$block.spotlight.rating}>)
                                <{/if}>
                            </td>
                        </tr>
                        <tr>
                            <td><{$block.spotlight.image}></td>
                            <td><{$block.spotlight.text}></td>
                        </tr>
                        <tr>
                            <td colspan='2'>
                                <{if $block.spotlight.read_more}>
                                    <hr width='98%'/>
                                    <div align='right'><a href="<{$xoops_url}>/modules/news/article.php?storyid=<{$block.spotlight.id}>"><{$block.lang_read_more}></a> &nbsp;&nbsp;&nbsp;</div>
                                    <hr width='98%'/>
                                <{/if}>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        <{/if}>
        <tr>
            <td>

                <{foreach item=news from=$block.stories}>
                    <{if $news.id != $block.spotlight.id}>
                        <h2>
						   <span>
							<{if $block.sort=='counter'}>
                                [<{$news.hits}>]
                            <{elseif $block.sort=='published'}>
                                [<{$news.date}>]
                            <{else}>
                                [<{$news.rating}>]
                            <{/if}>
							</span>
                            <a href="<{$xoops_url}>/modules/news/article.php?storyid=<{$news.id}>" <{$news.infotips}> ><{$news.title}></a>
                        </h2>
                        <{if $news.teaser}><p><{$news.teaser}></p><{/if}>

                    <{/if}>
                <{/foreach}>

            </td>
        </tr>
    </table>
<{/if}>
