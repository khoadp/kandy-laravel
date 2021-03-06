<div class="row {{$class}} cd-tabs" id="{{$id}}" {{$htmlOptionsAttributes}} >
<input type="hidden" class="kandy_current_username" value="{{$options['user']['name']}}"/>
<div class="chat-heading">
    <div class="contact-heading">
        <label>Contacts:</label>
        <select onchange="kandy_contactFilterChanged($(this).val())">
            <option value="all">All</option>
            <option value="offline">Offline</option>
            <option value="available">Available</option>
            <option value="unavailable">Unavailable</option>
            <option value="away">Away</option>
            <option value="out-to-lunch">Out To Lunch</option>
            <option value="busy">Busy</option>
            <option value="on-vacation">On Vacation</option>
            <option value="be-right-back">Be Right Back</option>
        </select>
    </div>
    <div class="chat-with-message">
        Chatting with <span class="chat-friend-name"></span>
    </div>
    <div class="clear-fix"></div>
</div>
<nav>
    <ul class="cd-tabs-navigation">
    </ul>
</nav>

<ul class="cd-tabs-content">
</ul>

<div style="clear: both;"></div>
</div>

<script>

    var wrapDivId = "{{ $id }}";
    var liTabWrapClass = "cd-tabs-navigation";
    var liContentWrapClass = "cd-tabs-content";
    var liTabWrapSelector = "#" + wrapDivId + " ." + liTabWrapClass;
    var liContentWrapSelector = "#" + wrapDivId + " ." + liContentWrapClass;
    var tabContentWrapper = $(liContentWrapSelector);

    var userHoldingAttribute = "data-content";
    var activeClass = "selected";

    /**
     * Get a contact template
     *
     * @param user
     * @param active
     * @returns {string}
     */
    var getLiContact = function (user, active) {
        // Set false as default
        var id = user.replace(/[.@]/g, '_');
        var liClass = (typeof active !== 'undefined') ? active : "";
        var result = '<li id="'+ id +'" class="' + liClass + '"><a ' + userHoldingAttribute + '="' + user + '" href="#">' + user + '</a><i class="status"></i></li>';
        return result
    }

    /**
     * Get contact content template
     *
     * @param user
     * @returns {string}
     */
    var getLiContent = function (user) {
        var result =
            '<li ' + userHoldingAttribute + '="' + user + '">\
                <div class="kandyMessages" data-user="' + user + '">\
                </div>\
                <div >\
                    Messages:\
                </div>\
                <div class="{{ $options['message']['class'] }}">\
                            <form class="send-message" data-user="' + user + '">\
                        <div class="input-message">\
                            <input class="imMessageToSend chat-input" type="text" data-user="' + user + '">\
                        </div>\
                        <div class="button-send">\
                            <input class="btnSendMessage chat-input" type="submit" value="Send"  data-user="' + user + '" >\
                        </div>\
                    </form>\
                </div>\
            </li>';
        return result;
    }
    var kandy_contactFilterChanged = function(val){
        var liUserchat = $(".kandyChat .cd-tabs-navigation li");
        $.each(liUserchat, function(index, target){
            var liClass = $(target).attr('class');
            var currentClass = "kandy-chat-status-" + val;
            if(val == "all"){
                $(target).show();
            } else if(liClass == currentClass){
                $(target).show();
            } else {
                $(target).hide();
            }
        });
    }
    $(document).ready(function () {
        $("form.send-message").live("submit", function (e) {
            var username = $(this).attr('data-user');
            kandy_sendIm(username);
            e.preventDefault();
        })

        /**
         * Chat Tabs
         *
         */

        $('.cd-tabs-navigation a').live('click', function (event) {
            event.preventDefault();
            var selectedItem = $(this);
            if (!selectedItem.hasClass('selected')) {
                var selectedTab = selectedItem.data('content'),
                    selectedContent = tabContentWrapper.find('li[data-content="' + selectedTab + '"]'),
                    slectedContentHeight = selectedContent.innerHeight();

                $('.cd-tabs-navigation a').removeClass('selected');
                selectedItem.addClass('selected');
                selectedContent.addClass('selected').siblings('li').removeClass('selected');

                // Set focus
                selectedContent.find(".imMessageToSend").focus();

                // Set chat heading
                $(".chat-with-message").show();
                $(".chat-friend-name").html(selectedItem.html());

                //animate tabContentWrapper height when content changes
                tabContentWrapper.animate({
                    'height': slectedContentHeight
                }, 200);
            }
        });

        //hide the .cd-tabs::after element when tabbed navigation has scrolled to the end (mobile version)
        checkScrolling($('.cd-tabs nav'));

        $(window).on('resize', function () {
            checkScrolling($('.cd-tabs nav'));
        });

        $('.cd-tabs nav').on('scroll', function () {
            checkScrolling($(this));
        });

        function checkScrolling(tabs) {
            var totalTabWidth = parseInt(tabs.children('.cd-tabs-navigation').width()),
                tabsViewport = parseInt(tabs.width());
            if (tabs.scrollLeft() >= totalTabWidth - tabsViewport) {
                tabs.parent('.cd-tabs').addClass('is-ended');
            } else {
                tabs.parent('.cd-tabs').removeClass('is-ended');
            }
        }
    })
</script>