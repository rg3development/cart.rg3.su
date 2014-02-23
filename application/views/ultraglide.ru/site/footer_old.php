    <div id="fb-root"></div>

    <script>
        (function(d, s, id) {
          var js, fjs = d.getElementsByTagName(s)[0];
          if (d.getElementById(id)) return;
          js = d.createElement(s); js.id = id;
          js.src = "//connect.facebook.net/en_EN/all.js#xfbml=1";
          fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
    </script>

    <div class="footer f2">

        <div class="soc">
            <table>
                <tr>
                    <td>
                        <div class="fb-block">
                            <div class="fb-like" data-href="http://www.ultraglide.ru" data-send="false" data-layout="button_count" data-width="150" data-show-faces="false" data-font="segoe ui"></div>
                        </div>
                    </td>
                    <td>
                        <!-- Place this tag where you want the +1 button to render. -->
                        <div class="g-plusone" data-size="small"></div>
                        <!-- Place this tag after the last +1 button tag. -->
                        <script type="text/javascript">
                            (function() {
                                var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
                                po.src = 'https://apis.google.com/js/plusone.js';
                                var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
                            })();
                        </script>
                        <!-- </div> -->
                    </td>
                    <td>
                        <div>
                            <!-- Put this script tag to the <head> of your page -->
                            <script type="text/javascript" src="//vk.com/js/api/openapi.js?86"></script>

                            <script type="text/javascript">
                                VK.init({apiId: 3532295, onlyWidgets: true});
                            </script>

                            <!-- Put this div tag to the place, where the Like block will be -->
                            <div id="vk_like"></div>
                            <script type="text/javascript">
                                VK.Widgets.Like("vk_like", {type: "button", height: 18});
                            </script>
                        </div>
                    </td>
                    <td>
                        <div>
                            <a href="https://twitter.com/share" class="twitter-share-button" data-dnt="true" data-count="none" data-via="twitterapi">
                                Tweet
                            </a>
                            <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <p>
            <a href="http://www.erichkrause.ru/" class="foo" target="_blank">www.erichkrause.ru</a>
        </p>

    </div>
</div>

<div id="mask" class="mask"></div>

<div id="video-big" class="video-big">
    <div id="ytapiplayer"></div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $(".various").fancybox({
            // maxWidth    : 560,
            // maxHeight   : 315,
            fitToView   : false,
            width       : 560,
            height      : 315,
            autoSize    : false,
            closeClick  : false,
            openEffect  : 'none',
            closeEffect : 'none'
        });
    });
</script>

<script type="text/javascript">
    <!--
    function stopError() {
        return true;
    }
    window.onerror = stopError;
    -->
    //presentationCycle.init();
    var params = { allowScriptAccess: "always" };
    var atts = { id: "myytplayer" };
    swfobject.embedSWF("http://www.youtube.com/v/AMtqTAm3AH4?&enablejsapi=1&playerapiid=ytplayer", "ytapiplayer", "560", "315", "8", null, null, params, atts);

    function onYouTubePlayerReady(playerId) {
        ytplayer = document.getElementById("myytplayer");
        ytplayer.addEventListener("onStateChange", "onytplayerStateChange");
    }

    function onytplayerStateChange(newState) {
        if (newState == 0) {
            $('#video-big').hide();
            $('#mask').hide();
            $('#video-big').css('left','-100000px');
        }
    }

    $("#video-img").click(function(){
        $('#video-big').css('left','30%')
        $('#video-big').show();
        $('#mask').show();
    });

    $("#mask").click(function(){
        $('#video-big').hide();
        $('#mask').hide();
        $('#video-big').css('left','-100000px');
        ytplayer.stopVideo();
    });
</script>

<script type="text/javascript">
    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', 'UA-36865647-21']);
    _gaq.push(['_trackPageview']);

    (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
    })();
</script>

</body>
</html>