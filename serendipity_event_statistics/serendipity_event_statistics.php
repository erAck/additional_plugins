<?php

if (IN_serendipity !== true) { die ("Don't hack!"); }

@serendipity_plugin_api::load_language(dirname(__FILE__));

class serendipity_event_statistics extends serendipity_event
{
    var $title = PLUGIN_EVENT_STATISTICS_NAME;

    function introspect(&$propbag)
    {
        global $serendipity;

        $propbag->add('name',          PLUGIN_EVENT_STATISTICS_NAME);
        $propbag->add('description',   PLUGIN_EVENT_STATISTICS_DESC);
        $propbag->add('stackable',     false);
        $propbag->add('author',        'Arnan de Gans, Garvin Hicking, Fredrik Sandberg, kalkin, Matthias Mees, Ian');
        $propbag->add('version',       '1.66');
        $propbag->add('requirements',  array(
            'serendipity' => '1.6',
            'smarty'      => '2.6.7',
            'php'         => '4.1.0'
        ));
        $propbag->add('groups', array('STATISTICS'));
        $propbag->add('event_hooks',   array(
            'backend_sidebar_entries' => true,
            'backend_sidebar_admin_appearance' => true,
            'backend_sidebar_entries_event_display_statistics' => true,
            'frontend_configure' => true,
            'css_backend'        => true
        ));

        $propbag->add('legal',    array(
            'services' => array(
            ),
            'frontend' => array(
                'Saves user visitor data to the local database (visitors) for statistical analysis. Tracks IP, User Agent, HTTP Referer',
            ),
            'backend' => array(
            ),
            'cookies' => array(
            ),
            'stores_user_input'     => true,
            'stores_ip'             => true,
            'uses_ip'               => true,
            'transmits_user_input'  => false
        ));
        $propbag->add('configuration', array('max_items','ext_vis_stat','stat_all','banned_bots'));
    }

    function introspect_config_item($name, &$propbag)
    {
        switch($name) {
            case 'max_items':
                $propbag->add('type',        'string');
                $propbag->add('name',        PLUGIN_EVENT_STATISTICS_MAX_ITEMS);
                $propbag->add('description', PLUGIN_EVENT_STATISTICS_MAX_ITEMS_DESC);
                $propbag->add('default',     20);
                break;


            case 'ext_vis_stat':
                $select = array('no'     => PLUGIN_EVENT_STATISTICS_EXT_OPT1,
                                'yesBot' => PLUGIN_EVENT_STATISTICS_EXT_OPT2,
                                'yesTop' => PLUGIN_EVENT_STATISTICS_EXT_OPT3);

                $propbag->add('type',          'select');
                $propbag->add('name',          PLUGIN_EVENT_STATISTICS_EXT_ADD);
                $propbag->add('description',   PLUGIN_EVENT_STATISTICS_EXT_ADD_DESC);
                $propbag->add('select_values', $select);
                $propbag->add('default',       'no');

                break;

            case 'stat_all':
                $select = array('no' => PLUGIN_EVENT_STATISTICS_EXT_ALL1,
                                'yes' => PLUGIN_EVENT_STATISTICS_EXT_ALL2);

                $propbag->add('type',          'select');
                $propbag->add('name',          PLUGIN_EVENT_STATISTICS_EXT_ALL);
                $propbag->add('description',   PLUGIN_EVENT_STATISTICS_EXT_ALL_DESC);
                $propbag->add('select_values', $select);
                $propbag->add('default',       'yes');

                break;

           case 'banned_bots':
                $select = array('yes' => PLUGIN_EVENT_STATISTICS_BANNED_HOSTS1,
                                'no' => PLUGIN_EVENT_STATISTICS_BANNED_HOSTS2);

                $propbag->add('type',          'select');
                $propbag->add('name',          PLUGIN_EVENT_STATISTICS_BANNED_HOSTS);
                $propbag->add('description',   PLUGIN_EVENT_STATISTICS_BANNED_HOSTS_DESC);
                $propbag->add('select_values', $select);
                $propbag->add('default',       'yes');

                break;
        }

        return true;
    }

    function generate_content(&$title) {
        $title = $this->title;
    }

    function event_hook($event, &$bag, &$eventData, $addData = null) {
        global $serendipity;

        $hooks = &$bag->get('event_hooks');

        if (isset($hooks[$event])) {
            switch($event) {

                case 'frontend_configure':
                    if ($this->get_config('ext_vis_stat') == 'no') {
                        return;
                    }

                    //checking if db tables exists, otherwise install them
                    $tableChecker = serendipity_db_query("SELECT counter_id FROM {$serendipity['dbPrefix']}visitors LIMIT 1", true);
                    if (!is_array($tableChecker)) {
                        $this->createTables();
                    }

                    if ((int)$this->get_config('db_indices_created', '0') == 0) {
                        $this->updateTables();
                    }
                    if ((int)$this->get_config('db_indices_created', '1') == 1) {
                        $this->updateTables(1);
                    }

                    //Unique visitors are beeing registered and counted here. Calling function below.
                    $sessionChecker = serendipity_db_query("SELECT count(sessID) FROM {$serendipity['dbPrefix']}visitors WHERE '".serendipity_db_escape_string(session_id())."' = sessID GROUP BY sessID", true);
                    if (!is_array($sessionChecker) || (is_array($sessionChecker)) && ($sessionChecker[0] == 0)) {

                        $referer = $useragent = $remoteaddr = 'unknown';

                        // gathering intel
                        if ($_SERVER['REMOTE_ADDR']) {
                            $remoteaddr = $_SERVER['REMOTE_ADDR'];
                        }
                        if ($_SERVER['HTTP_USER_AGENT']) {
                            $useragent = substr($_SERVER['HTTP_USER_AGENT'], 0, 255);
                        }
                        if ($_SERVER['HTTP_REFERER']) {
                            $referer = substr($_SERVER['HTTP_REFERER'], 0, 255);
                        }

                        $found = 0;

                        // avoiding banned browsers
                        if ($this->get_config('banned_bots') == 'yes') {
                            // excludelist botagents
                            $banned_array = array(
                                    '1'     =>     "unknown",
                                    '2'     =>     "bot",
                                    '3'     =>     "slurpy",
                                    '4'     =>     "agent 007",
                                    '5'     =>     "ichiro",
                                    '6'     =>     "ia_archiver",
                                    '7'     =>     "zyborg",
                                    '8'     =>     "linkwalker",
                                    '9'     =>     "crawler",
                                    '10'    =>     "python",
                                    '11'    =>     "w3c_validator",
                                    '12'    =>     "almaden",
                                    '13'    =>     "topicspy",
                                    '14'    =>     "poodle predictor",
                                    '15'    =>     "link checker pro",
                                    '16'    =>     "xenu link sleuth",
                                    '17'    =>     "iconsurf",
                                    '18'    =>     "zoe indexer",
                                    '19'    =>     "grub-client",
                                    '20'    =>     "spider",
                                    '21'    =>     "pompos",
                                    '22'    =>     "virus_detector",
                                    '23'    =>     "bot",
                                    '24'    =>     "Wells Search II",
                                    '25'    =>     "Dumbot",
                                    '26'    =>     "GeoBot",
                                    '27'    =>     "DigExt",
                                    '28'    =>     "Jeeves/Teoma",
                                    '29'    =>     "FeedBurner",
                                    '30'    =>     "Technorati",
                                    '31'    =>     "Java/1.5.0_10",
                                    '32'    =>     "Java/1.5.0_06",
                                    '33'    =>     "MarsEdit",
                                    '34'    =>     "Blogslive",
                                    '35'    =>     "XMLRPCCocoa",
                                    '36'    =>     "Google",
                                    '37'    =>     "MagpieRSS",
                                    '38'    =>     "Sphere Scout",
                                    '39'    =>     "BlogCorpusReader",
                                    '41'    =>     "libwww-perl",
                                    '42'    =>     "WordPress",
                                    '43'    =>     "ping.wordblog.de",
                                    '44'    =>     "PEAR HTTP_Request",
                                    '45'    =>     "Java/1.5.0_07",
                                    '46'    =>     "BlogPulseLive(support@blogpulse.com)",
                                    '47'    =>     "TrackBack",
                                    '48'    =>     "Blogdimension",
                                    '49'    =>     "Yahoo"
                                    );

                            foreach($banned_array AS $ban) {
                                if (stristr($useragent, $ban) !== false) {
                                    $found = 1;
                                    break;
                                }
                            }
                        }

                        if ($found == 0){
                            $this->countVisitor($useragent, $remoteaddr, $referer);
                        }
                    } else {
                        // Update visitor timestamp
                        $this->updateVisitor();
                    }

                break;

                case 'css_backend':
?>

.serendipity_statistics table {
    background: #eaeaea;
    background-image: -webkit-linear-gradient(#fff, #eaeaea);
    background-image: linear-gradient(#fff, #eaeaea);
    border-color: #ddd #bbb #999;
    color: #222;
    text-shadow: #fff 0 1px 1px;
    width: 100%;
}
.stats_imagecell {
    vertical-align: bottom;
}
.stats_header {
    width: auto;
    display: block;
    background: #eee;
}
.stats_header span {
    text-align: right;
    width: 50%;
    float: right;
}
.serendipity_statistics .wide_box dl {
    clear: left;
    display: table;
    width: 100%;
}
.serendipity_statistics .wide_box dt {
    display: table-row;
}
.serendipity_statistics .wide_box dd {
    margin-left: 0;
    padding: 0px 0.5em 0.5em 0;
}

<?php
                    break;

                case 'backend_sidebar_entries':
                    if ($serendipity['version'][0] < 2) {
?>
                        <li><a href="?serendipity[adminModule]=event_display&amp;serendipity[adminAction]=statistics"><?php echo PLUGIN_EVENT_STATISTICS_NAME; ?></a></li>
<?php
                    }
                    break;

                case 'backend_sidebar_admin_appearance':
                    if ($serendipity['version'][0] > 1) {
?>
                        <li><a href="?serendipity[adminModule]=event_display&amp;serendipity[adminAction]=statistics"><?php echo PLUGIN_EVENT_STATISTICS_NAME; ?></a></li>
<?php
                    }
                    break;

                case 'backend_sidebar_entries_event_display_statistics':
                    $max_items    = $this->get_config('max_items');
                    $ext_vis_stat = $this->get_config('ext_vis_stat');

                    if (!$max_items || !is_numeric($max_items) || $max_items < 1) {
                        $max_items = 20;
                    }

                    if ($ext_vis_stat == 'yesTop') {
                        $this->extendedVisitorStatistics($max_items);
                    }


                    if ($this->get_config('stat_all') == 'yes') {
                        $first_entry    = serendipity_db_query("SELECT timestamp FROM {$serendipity['dbPrefix']}entries ORDER BY timestamp ASC limit 1", true);
                        $last_entry     = serendipity_db_query("SELECT timestamp FROM {$serendipity['dbPrefix']}entries ORDER BY timestamp DESC limit 1", true);
                        $total_count    = serendipity_db_query("SELECT count(id) FROM {$serendipity['dbPrefix']}entries", true);
                        $draft_count    = serendipity_db_query("SELECT count(id) FROM {$serendipity['dbPrefix']}entries WHERE isdraft = 'true'", true);
                        $publish_count  = serendipity_db_query("SELECT count(id) FROM {$serendipity['dbPrefix']}entries WHERE isdraft = 'false'", true);
                        $author_rows    = serendipity_db_query("SELECT author, count(author) as entries FROM {$serendipity['dbPrefix']}entries GROUP BY author ORDER BY author");
                        $category_count = serendipity_db_query("SELECT count(categoryid) FROM {$serendipity['dbPrefix']}category", true);
                        $cat_sql        = "SELECT c.category_name, count(e.id) as postings
                                                        FROM {$serendipity['dbPrefix']}entrycat ec,
                                                             {$serendipity['dbPrefix']}category c,
                                                             {$serendipity['dbPrefix']}entries e
                                                        WHERE ec.categoryid = c.categoryid AND ec.entryid = e.id
                                                        GROUP BY ec.categoryid, c.category_name
                                                        ORDER BY postings DESC";
                        $category_rows  = serendipity_db_query($cat_sql);

                        $image_count = serendipity_db_query("SELECT count(id) FROM {$serendipity['dbPrefix']}images", true);
                        $image_rows  = serendipity_db_query("SELECT extension, count(id) AS images FROM {$serendipity['dbPrefix']}images GROUP BY extension ORDER BY images DESC");

                        $subscriber_count = count(serendipity_db_query("SELECT count(id) FROM {$serendipity['dbPrefix']}comments WHERE type = 'NORMAL' AND subscribed = 'true' GROUP BY email"));
                        $subscriber_rows  = serendipity_db_query("SELECT e.timestamp, e.id, e.title, count(c.id) as postings
                                                        FROM {$serendipity['dbPrefix']}comments c,
                                                             {$serendipity['dbPrefix']}entries e
                                                        WHERE e.id = c.entry_id AND type = 'NORMAL' AND subscribed = 'true'
                                                        GROUP BY e.id, c.email, e.title, e.timestamp
                                                        ORDER BY postings DESC
                                                        LIMIT $max_items");

                        $comment_count = serendipity_db_query("SELECT count(id) FROM {$serendipity['dbPrefix']}comments WHERE type = 'NORMAL'", true);
                        $comment_rows  = serendipity_db_query("SELECT e.timestamp, e.id, e.title, count(c.id) as postings
                                                        FROM {$serendipity['dbPrefix']}comments c,
                                                             {$serendipity['dbPrefix']}entries e
                                                        WHERE e.id = c.entry_id AND type = 'NORMAL'
                                                        GROUP BY e.id, e.title, e.timestamp
                                                        ORDER BY postings DESC
                                                        LIMIT $max_items");

                        $commentor_rows = serendipity_db_query("SELECT author, max(email) as email, max(url) as url, count(id) as postings
                                                        FROM {$serendipity['dbPrefix']}comments c
                                                        WHERE type = 'NORMAL'
                                                        GROUP BY author
                                                        ORDER BY postings DESC
                                                        LIMIT $max_items");

                        $tb_count = serendipity_db_query("SELECT count(id) FROM {$serendipity['dbPrefix']}comments WHERE type = 'TRACKBACK'", true);
                        $tb_rows  = serendipity_db_query("SELECT e.timestamp, e.id, e.title, count(c.id) as postings
                                                        FROM {$serendipity['dbPrefix']}comments c,
                                                             {$serendipity['dbPrefix']}entries e
                                                        WHERE e.id = c.entry_id AND type = 'TRACKBACK'
                                                        GROUP BY e.timestamp, e.id, e.title
                                                        ORDER BY postings DESC
                                                        LIMIT $max_items");

                        $tbr_rows = serendipity_db_query("SELECT author, max(email) as email, max(url) as url, count(id) as postings
                                                        FROM {$serendipity['dbPrefix']}comments c
                                                        WHERE type = 'TRACKBACK'
                                                        GROUP BY author
                                                        ORDER BY postings DESC
                                                        LIMIT $max_items");

                        $length      = serendipity_db_query("SELECT SUM(LENGTH(body) + LENGTH(extended)) FROM {$serendipity['dbPrefix']}entries", true);
                        $length_rows = serendipity_db_query("SELECT id, title, (LENGTH(body) + LENGTH(extended)) as full_length FROM {$serendipity['dbPrefix']}entries ORDER BY full_length DESC LIMIT $max_items");
?>
    <h2><?php echo PLUGIN_EVENT_STATISTICS_OUT_STATISTICS; ?></h2>

    <div class="serendipity_statistics clearfix">
        <section>
            <h3><?php echo ENTRIES; ?></h3>

            <dl>
                <dt><?php echo PLUGIN_EVENT_STATISTICS_OUT_FIRST_ENTRY; ?></dt>
                <dd><?php echo serendipity_formatTime(DATE_FORMAT_ENTRY . ' %H:%m', $first_entry[0]); ?></dd>
                <dt><?php echo PLUGIN_EVENT_STATISTICS_OUT_LAST_ENTRY; ?></dt>
                <dd><?php echo serendipity_formatTime(DATE_FORMAT_ENTRY . ' %H:%m', $last_entry[0]); ?></dd>
                <dt><?php echo PLUGIN_EVENT_STATISTICS_OUT_TOTAL_ENTRIES; ?></dt>
                <dd><?php echo $total_count[0]; ?> <?php echo PLUGIN_EVENT_STATISTICS_OUT_ENTRIES; ?>
                    <dl>
                        <dt><?php echo PLUGIN_EVENT_STATISTICS_OUT_TOTAL_PUBLIC; ?></dt>
                        <dd><?php echo $publish_count[0]; ?> <?php echo PLUGIN_EVENT_STATISTICS_OUT_ENTRIES; ?></dd>
                        <dt><?php echo PLUGIN_EVENT_STATISTICS_OUT_TOTAL_DRAFTS; ?></dt>
                        <dd><?php echo $draft_count[0]; ?> <?php echo PLUGIN_EVENT_STATISTICS_OUT_ENTRIES; ?></dd>
                    </dl>
                </dd>
            </dl>
        </section>

        <section>
            <h3><?php echo PLUGIN_EVENT_STATISTICS_OUT_PER_AUTHOR; ?></h3>

            <dl>
<?php
                    if (is_array($author_rows)) {
                        foreach($author_rows AS $author => $author_stat) {
?>
                <dt><?php echo $author_stat['author']; ?></dt>
                <dd><?php echo $author_stat['entries']; ?> <?php echo PLUGIN_EVENT_STATISTICS_OUT_ENTRIES; ?> (<?php echo 100*round($author_stat['entries'] / max($total_count[0], 1), 3); ?>%)</dd>
<?php
                        }
                    }
?>
            </dl>
        </section>

        <section>
            <h3><?php echo PLUGIN_EVENT_STATISTICS_OUT_CATEGORIES; ?></h3>

            <p><?php echo $category_count[0]; ?> <?php echo PLUGIN_EVENT_STATISTICS_OUT_CATEGORIES2; ?></p>

            <h4><?php echo PLUGIN_EVENT_STATISTICS_OUT_DISTRIBUTION_CATEGORIES; ?></h4>

            <dl>
<?php
                    if (is_array($category_rows)) {
                        foreach($category_rows AS $category => $cat_stat) {
?>
                <dt><?php echo $cat_stat['category_name']; ?></dt>
                <dd><?php echo $cat_stat['postings']; ?> <?php echo PLUGIN_EVENT_STATISTICS_OUT_DISTRIBUTION_CATEGORIES2; ?></dd>
<?php
                        }
                    }
?>
            </dl>
        </section>

        <section>
            <h3><?php echo PLUGIN_EVENT_STATISTICS_OUT_UPLOADED_IMAGES; ?></h3>

            <p><?php echo $image_count[0]; ?> <?php echo PLUGIN_EVENT_STATISTICS_OUT_UPLOADED_IMAGES2; ?></p>

            <h4><?php echo PLUGIN_EVENT_STATISTICS_OUT_DISTRIBUTION_IMAGES; ?></h4>

            <dl>
<?php
                    if (is_array($image_rows)) {
                        foreach($image_rows AS $image => $image_stat) {
?>
                <dt><?php echo $image_stat['extension']; ?></dt>
                <dd><?php echo $image_stat['images']; ?> <?php echo PLUGIN_EVENT_STATISTICS_OUT_DISTRIBUTION_IMAGES2; ?></dd>
<?php
                        }
                    }
?>
            </dl>
        </section>

        <section>
            <h3><?php echo COMMENTS; ?></h3>

            <dl>
                <dt><?php echo PLUGIN_EVENT_STATISTICS_OUT_COMMENTS; ?></dt>
                <dd><?php echo $comment_count[0]; ?> <?php echo PLUGIN_EVENT_STATISTICS_OUT_COMMENTS2; ?></dd>
            </dl>

            <h4><?php echo PLUGIN_EVENT_STATISTICS_OUT_COMMENTS3; ?></h4>

            <dl>
<?php
                    if (is_array($comment_rows)) {
                        foreach($comment_rows AS $comment => $com_stat) {
?>
                <dt><a href="<?php echo serendipity_archiveURL($com_stat['id'], $com_stat['title'], 'serendipityHTTPPath', true, array('timestamp' => $com_stat['timestamp'])); ?>"><?php echo $com_stat['title']; ?></a></dt>
                <dd><?php echo $com_stat['postings']; ?> <?php echo PLUGIN_EVENT_STATISTICS_OUT_COMMENTS2; ?></dd>
<?php
                        }
                    }
?>
            </dl>
        </section>

        <section>
            <h3><?php echo PLUGIN_EVENT_STATISTICS_OUT_TOPCOMMENTS; ?></h3>

            <dl>
<?php
                    if (is_array($commentor_rows)) {
                        foreach($commentor_rows AS $comment => $com_stat) {
                            $link_start = '';
                            $link_end   = '';
                            $link_url   = '';

                            if (!empty($com_stat['email'])) {
                                $link_start = '<a href="mailto:' . (function_exists('serendipity_specialchars') ? serendipity_specialchars($com_stat['email']) : htmlspecialchars($com_stat['email'], ENT_COMPAT, LANG_CHARSET)) . '">';
                                $link_end   = '</a>';
                            }

                            if (!empty($com_stat['url'])) {
                                if (substr($com_stat['url'], 0, 7) != 'http://' && substr($com_stat['url'], 0, 8) != 'https://') {
                                    $com_stat['url'] = 'http://' . $com_stat['url'];
                                }

                                $link_url = ' (<a href="' . (function_exists('serendipity_specialchars') ? serendipity_specialchars($com_stat['url']) : htmlspecialchars($com_stat['url'], ENT_COMPAT, LANG_CHARSET)) . '">' . PLUGIN_EVENT_STATISTICS_OUT_LINK . '</a>)';
                            }

                            if (empty($com_stat['author'])) {
                                $com_stat['author'] = ANONYMOUS;
                            }
?>
                <dt><?php echo $link_start . $com_stat['author'] . $link_end . $link_url; ?> </dt>
                <dd><?php echo $com_stat['postings']; ?> <?php echo PLUGIN_EVENT_STATISTICS_OUT_COMMENTS2; ?></dd>
<?php
                        }
                    }
?>
            </dl>
        </section>

        <section>
            <h3><?php echo PLUGIN_EVENT_STATISTICS_OUT_SUBSCRIBERS; ?></h3>

            <p><?php echo $subscriber_count; ?> <?php echo PLUGIN_EVENT_STATISTICS_OUT_SUBSCRIBERS2; ?></p>

            <h4><?php echo PLUGIN_EVENT_STATISTICS_OUT_TOPSUBSCRIBERS; ?></h4>

            <dl>
<?php
                    if (is_array($subscriber_rows)) {
                        foreach($subscriber_rows AS $subscriber => $subscriber_stat) {
?>
                <dt><a href="<?php echo serendipity_archiveURL($subscriber_stat['id'], $subscriber_stat['title'], 'serendipityHTTPPath', true, array('timestamp' => $subscriber_stat['timestamp'])); ?>"><?php echo $subscriber_stat['title']; ?></a></dt>
                <dd><?php echo $subscriber_stat['postings']; ?> <?php echo PLUGIN_EVENT_STATISTICS_OUT_TOPSUBSCRIBERS2; ?></dd>
<?php
                        }
                    }
?>
            </dl>
        </section>

        <section>
            <h3><?php echo PLUGIN_EVENT_STATISTICS_OUT_TRACKBACKS; ?></h3>

            <p><?php echo $tb_count[0]; ?> <?php echo PLUGIN_EVENT_STATISTICS_OUT_TRACKBACKS2; ?></p>

            <h4><?php echo PLUGIN_EVENT_STATISTICS_OUT_TOPTRACKBACK; ?></h4>

            <dl>
<?php
                    if (is_array($tb_rows)) {
                        foreach($tb_rows AS $tb => $tb_stat) {
?>
                <dt><a href="<?php echo serendipity_archiveURL($tb_stat['id'], $tb_stat['title'], 'serendipityHTTPPath', true, array('timestamp' => $tb_stat['timestamp'])); ?>"><?php echo $tb_stat['title']; ?></a></dt>
                <dd><?php echo $tb_stat['postings']; ?> <?php echo PLUGIN_EVENT_STATISTICS_OUT_TOPTRACKBACK2; ?></dd>
<?php
                        }
                    }
?>
            </dl>
        </section>

        <section>
            <h3><?php echo PLUGIN_EVENT_STATISTICS_OUT_TOPTRACKBACKS3; ?></h3>

            <dl>
<?php
                    if (is_array($tbr_rows)) {
                        foreach($tbr_rows AS $tb => $tb_stat) {
                            if (empty($tb_stat['author'])) {
                                $tb_stat['author'] = ANONYMOUS;
                            }
?>
                <dt><a href="<?php echo (function_exists('serendipity_specialchars') ? serendipity_specialchars($tb_stat['url']) : htmlspecialchars($tb_stat['url'], ENT_COMPAT, LANG_CHARSET)); ?>"><?php echo (function_exists('serendipity_specialchars') ? serendipity_specialchars($tb_stat['author']) : htmlspecialchars($tb_stat['author'], ENT_COMPAT, LANG_CHARSET)); ?></a></dt>
                <dd><?php echo $tb_stat['postings']; ?> <?php echo PLUGIN_EVENT_STATISTICS_OUT_TOPTRACKBACK2; ?></dd>
<?php
                        }
                    }
?>
            </dl>
        </section>

        <section>
            <h3><?php echo PLUGIN_EVENT_STATISTICS_OUT_AVERAGES; ?></h3>

            <dl>
                <dt><?php echo PLUGIN_EVENT_STATISTICS_OUT_COMMENTS_PER_ARTICLE; ?></dt>
                <dd><?php echo round($comment_count[0] / max($publish_count[0], 1), 2); ?> <?php echo PLUGIN_EVENT_STATISTICS_OUT_COMMENTS_PER_ARTICLE2; ?></dd>
                <dt><?php echo PLUGIN_EVENT_STATISTICS_OUT_TRACKBACKS_PER_ARTICLE; ?></dt>
                <dd><?php echo round($tb_count[0] / max($publish_count[0], 1), 2); ?> <?php echo PLUGIN_EVENT_STATISTICS_OUT_TRACKBACKS_PER_ARTICLE2; ?></dd>
                <dt><?php echo PLUGIN_EVENT_STATISTICS_OUT_ARTICLES_PER_DAY; ?></dt>
                <dd><?php echo round($publish_count[0] / ((time() - $first_entry[0]) / (60*60*24)), 2);?> <?php echo PLUGIN_EVENT_STATISTICS_OUT_ARTICLES_PER_DAY2; ?></dd>
                <dt><?php echo PLUGIN_EVENT_STATISTICS_OUT_ARTICLES_PER_WEEK; ?></dt>
                <dd><?php echo round($publish_count[0] / ((time() - $first_entry[0]) / (60*60*24*7)), 2);?> <?php echo PLUGIN_EVENT_STATISTICS_OUT_ARTICLES_PER_WEEK2; ?></dd>
                <dt><?php echo PLUGIN_EVENT_STATISTICS_OUT_ARTICLES_PER_MONTH; ?></dt>
                <dd><?php echo round($publish_count[0] / ((time() - $first_entry[0]) / (60*60*24*31)), 2);?> <?php echo PLUGIN_EVENT_STATISTICS_OUT_ARTICLES_PER_MONTH2; ?></dd>
                <dt><?php echo PLUGIN_EVENT_STATISTICS_OUT_CHARS; ?></dt>
                <dd><?php echo $length[0]; ?> <?php echo PLUGIN_EVENT_STATISTICS_OUT_CHARS2; ?></dd>
                <dt><?php echo PLUGIN_EVENT_STATISTICS_OUT_CHARS_PER_ARTICLE; ?></dt>
                <dd><?php echo round($length[0] / max($publish_count[0], 1), 2); ?> <?php echo PLUGIN_EVENT_STATISTICS_OUT_CHARS_PER_ARTICLE2; ?></dd>
            </dl>
        </section>

        <section>
            <h3><?php printf(PLUGIN_EVENT_STATISTICS_OUT_LONGEST_ARTICLES, $max_items); ?></h3>

            <dl>
<?php
                    if (is_array($length_rows)) {
                        foreach($length_rows AS $tb => $length_stat) {
?>
                <dt><a href="<?php echo serendipity_archiveURL($length_stat['id'], $length_stat['title'], 'serendipityHTTPPath', true, array('timestamp' => $length_stat['timestamp'])); ?>"><?php echo $length_stat['title']; ?></a></dt>
                <dd><?php echo $length_stat['full_length']; ?> <?php echo PLUGIN_EVENT_STATISTICS_OUT_CHARS2; ?></dd>
<?php
                        }
                    }
?>
            </dl>
        </section>

        <section>
            <h3><?php echo PLUGIN_EVENT_STATISTICS_TOP_REFERRER; ?></h3>

            <?php echo serendipity_displayTopReferrers($max_items, true); ?>
        </section>

        <section>
            <h3><?php echo PLUGIN_EVENT_STATISTICS_TOP_EXITS; ?></h3>

            <?php echo serendipity_displayTopExits($max_items, true); ?>
        </section>
    <?php serendipity_plugin_api::hook_event('event_additional_statistics', $eventData, array('maxitems' => $max_items)); ?>
    </div>
<?php
                    }

                    if ($ext_vis_stat == 'yesBot') {
                        $this->extendedVisitorStatistics($max_items);
                    }

                    return true;
                    break;

                default:
                    return false;
                    break;
            }
        } else {
            return false;
        }
    }

    //Statistics
    function updatestats($action) {
        global $serendipity;

        list($year, $month, $day) = explode('-', date('Y-m-d'));
        $sql = serendipity_db_query("SELECT COUNT(year) AS result FROM {$serendipity['dbPrefix']}visitors_count WHERE year='$year' AND month='$month' AND day='$day'", true);

        $sql_hit_update = "UPDATE {$serendipity['dbPrefix']}visitors_count SET hits = hits+1 WHERE year='$year' AND month='$month' AND day='$day'";
        $sql_day_new    = "INSERT INTO {$serendipity['dbPrefix']}visitors_count (year, month, day, visits, hits) VALUES ('$year','$month','$day',1,1)";
        $sql_day_update = "UPDATE {$serendipity['dbPrefix']}visitors_count SET visits = visits+1, hits = hits+1 WHERE year='$year' AND month='$month' AND day='$day'";
        switch($action) {
            case "update":
                if($sql['result'] >= 1) {
                    serendipity_db_query($sql_hit_update);
                } else {
                    serendipity_db_query($sql_day_new);
                }
            break;
            case "new":
                if($sql['result'] >= 1) {
                       serendipity_db_query($sql_day_update);
                } else {
                    serendipity_db_query($sql_day_new);
                }
            break;
        }
    }

    function updateVisitor() {
        global $serendipity;

        $this->updatestats('update');

        $time = date('H:i');
        $day  = date('Y-m-d');
        return serendipity_db_query("UPDATE {$serendipity['dbPrefix']}visitors SET time = '$time', day  = '$day' WHERE sessID = '" . serendipity_db_escape_string(strip_tags(session_id())) . "'");
    }

    function countVisitor($useragent, $remoteaddr, $referer){
        global $serendipity;

        $thedate = date('Y-m-d');
        $ip      = strip_tags($remoteaddr);
        $ip_how_often = serendipity_db_query("SELECT COUNT(ip) AS result FROM {$serendipity['dbPrefix']}visitors WHERE ip ='$ip' and day='$thedate'", true);

        if($ip_how_often['result'] >=1){
            $this->updatestats('update');
       } else {
            $this->updatestats('new');
        }
        $values = array(
            'sessID' => strip_tags(session_id()),
            'day'    => $thedate,
            'time'   => date('H:i'),
            'ref'    => strip_tags($referer),
            'browser'=> strip_tags($useragent),
            'ip'     => strip_tags($remoteaddr)
        );

        serendipity_db_insert('visitors', $values);

        // updating the referrer-table
        if (strlen($referer) >= 1) {

            //retrieving the referrer base URL
            $temp_array = explode('?', $referer);
            $urlA = $temp_array[0];

            //removing "http://" & trailing subdirectories
            $temp_array3 = explode('//', $urlA);
            $urlB = $temp_array3[1];
            $temp_array4 = explode('/', $urlB);
            $urlB = $temp_array4[0];

            //removing www
            $urlC = serendipity_db_escape_string(str_replace('www.', '', $urlB));

            if(strlen($urlC) < 1) {
                $urlC = 'unknown';
            }

            //updating db
            $q = serendipity_db_query("SELECT count(refs) AS referrer FROM {$serendipity['dbPrefix']}refs WHERE refs = '$urlC' GROUP BY refs", true);
            if ($q['referrer'] >= 1){
                serendipity_db_query("UPDATE {$serendipity['dbPrefix']}refs SET count=count+1 WHERE (refs = '$urlC')");
            } else {
                serendipity_db_query("INSERT INTO {$serendipity['dbPrefix']}refs (refs, count) VALUES ('$urlC', 1)");
            }
        }

    } //end of function countVisitor

    // Calculate daily stats
    function statistics_getdailystats() {
        global $serendipity;

        list($year, $month) = explode('-', date("Y-m"));
        $sql = "SELECT SUM(visits) AS dailyvisit FROM {$serendipity['dbPrefix']}visitors_count WHERE day";
        for ($i=1; $i<32; $i++)    {
            $myDay = ($i < 10) ? "0" . $i : $i;
            $sqlfire = $sql . " = '$myDay' AND year = '$year' AND month = '$month'";
            $res = serendipity_db_query($sqlfire, true);
            $container[$i] = $res['dailyvisit'];
        }
        return $container;
    }

    // Calculate monthly stats
    function statistics_getmonthlystats() {
        global $serendipity;

        $year = date("Y");
        $sql = "SELECT SUM(visits) AS monthlyvisit FROM {$serendipity['dbPrefix']}visitors_count WHERE month";
        for ($i=1; $i<13; $i++)    {
            $myMonth = ($i < 10) ? "0" . $i : $i;
            $sqlfire = $sql . " = '$myMonth' AND year = '$year'";
            $res = serendipity_db_query($sqlfire, true);
            $container[$i] = $res['monthlyvisit'];
        }
        return $container;
    }

    function extendedVisitorStatistics($max_items){

        global $serendipity;

        // ---------------QUERIES for Viewing statistics ----------------------------------------------
        $day = date('Y-m-d');
        list($year, $month, $day) = explode('-', $day);

        $visitors_count_firstday = serendipity_db_query("SELECT day FROM {$serendipity['dbPrefix']}visitors ORDER BY counter_id ASC LIMIT 1", true);
        $visitors_count_today    = serendipity_db_query("SELECT visits FROM {$serendipity['dbPrefix']}visitors_count WHERE year = '".$year."' AND month = '".$month."' AND day = '".$day."'", true);
        $visitors_count          = serendipity_db_query("SELECT SUM(visits) FROM {$serendipity['dbPrefix']}visitors_count", true);
        $hits_count_today        = serendipity_db_query("SELECT hits FROM {$serendipity['dbPrefix']}visitors_count WHERE year = '".$year."' AND month = '".$month."' AND day = '".$day."'", true);
        $hits_count              = serendipity_db_query("SELECT SUM(hits) FROM {$serendipity['dbPrefix']}visitors_count", true);
        $visitors_latest         = serendipity_db_query("SELECT counter_id, day, time, ref, browser, ip FROM {$serendipity['dbPrefix']}visitors ORDER BY counter_id DESC LIMIT ".$max_items."");
        $top_refs                = serendipity_db_query("SELECT refs, count FROM {$serendipity['dbPrefix']}refs ORDER BY count DESC LIMIT 20");
        ?>
        <h2><?php echo PLUGIN_EVENT_STATISTICS_OUT_EXT_STATISTICS; ?></h2>

        <div class="serendipity_statistics extended_statistics clearfix">
            <section>
                <h3><?php echo PLUGIN_EVENT_STATISTICS_EXT_VISITORS; ?></h3>

                <p><?php echo PLUGIN_EVENT_STATISTICS_EXT_VISSINCE." ".$visitors_count_firstday[0]; ?></p>

                <span class="msg_notice"><span class="icon-info-circled" aria-hidden="true"></span> <?php echo PLUGIN_EVENT_STATISTICS_EXT_COUNTDESC; ?></span>

                <dl>
                    <dt><?php echo PLUGIN_EVENT_STATISTICS_EXT_VISTODAY; ?></dt>
                    <dd><?php echo $visitors_count_today[0]; ?></dd>
                    <dt><?php echo PLUGIN_EVENT_STATISTICS_EXT_VISTOTAL; ?></dt>
                    <dd><?php echo $visitors_count[0]; ?></dd>
                    <dt><?php echo PLUGIN_EVENT_STATISTICS_EXT_HITSTODAY; ?></dt>
                    <dd><?php echo $hits_count_today[0]; ?></dd>
                    <dt><?php echo PLUGIN_EVENT_STATISTICS_EXT_HITSTOTAL; ?></dt>
                    <dd><?php echo $hits_count[0]; ?></dd>
                <dl>
            </section>

            <section>
                <h3><?php echo PLUGIN_EVENT_STATISTICS_EXT_TOPREFS; ?></h3>
        <?php
            $i=1;
            if (is_array($top_refs)) {
                echo '<ol>';
                foreach($top_refs AS $key => $row) {
                    echo '<li><a href="http://'.$row['refs'].'" target="_blank">'.$row['refs'].'</a> ('.$row['count'].')</li>';
                }
                echo '</ol>';
            } else {
                echo "<span class='msg_notice'><span class='icon-info-circled' aria-hidden='true'></span> ".PLUGIN_EVENT_STATISTICS_EXT_TOPREFS_NONE."</span>";
            }
        ?>
            </section>

            <section class="wide_box">
                <h3><?php echo PLUGIN_EVENT_STATISTICS_EXT_MONTHGRAPH;?></h3>

        <?php if ($visitors_count[0] > 0) { ?>
                <table>
                    <tbody>
                    <tr>
                        <th scope="row"><?php echo MONTHS; ?></th>
                <?php
                    $mon = array('1' => 'Jan', '2' => 'Feb', '3' => 'Mar', '4' => 'Apr', '5' => 'May', '6' => 'Jun', '7' => 'Jul', '8' => 'Aug', '9' => 'Sep', '10' => 'Oct', '11' => 'Nov', '12' => 'Dec');
                    for ($i = 1; $i < 13; $i++) {
                        echo '<td>'. serendipity_strftime('%b', mktime(0, 0, 0, $i, 1, 2000)) .'</td>';
                    }
                ?>
                    </tr>
                    <tr>
                        <th scope="row">Visits</th>
                <?php
                    $num = $this->statistics_getmonthlystats();
                    for ($i=1; $i < 13; $i++) {
                        echo '<td>' . $num[$i] . '</td>';
                    }
                ?>
                    </tr>
                    <tr>
                        <th scope="row">+/~/-</th>
                <?php
                    $num = $this->statistics_getmonthlystats();
                    $rep = $num;
                    rsort($rep);

                    for ($i=1; $i < 13; $i++) {
                        $maxVisHeigh = 100/$rep[0]*2;
                        $monthHeight = round($num[$i]*$maxVisHeigh);
                        echo '<td class="stats_imagecell"><img src="plugins/serendipity_event_statistics/';
                        if ($num[$i]*$maxVisHeigh/2 <= 33) {
                            echo 'red.png';
                        } else if ($num[$i]*$maxVisHeigh/2 > 33 && $num[$i]*$maxVisHeigh/2 < 66) {
                            echo 'yellow.png';
                        } else {
                            echo 'green.png';
                        }
                        echo '" width="8" height="'.$monthHeight.'" style="height:'.$monthHeight.'px" alt="';
                        if ($num[$i]*$maxVisHeigh/2 <= 33) {
                            echo '-';
                        } else if ($num[$i]*$maxVisHeigh/2 > 33 && $num[$i]*$maxVisHeigh/2 < 66) {
                            echo '~';
                        } else {
                            echo '+';
                        }
                        echo '" /></td>';
                    }
                ?>
                    </tr>
                    </tbody>
                </table>
        <?php } ?>
            </section>

            <section class="wide_box">
                <h3><?php echo PLUGIN_EVENT_STATISTICS_EXT_DAYGRAPH;?></h3>

        <?php if ($visitors_count[0] > 0) { ?>
                <table>
                    <tbody>
                    <tr>
                        <th scope="row"><?php echo DAYS; ?></th>
                <?php
                    for ($i=1; $i < 32; $i++) {
                        echo '<td>'. $i .'</td>';
                    }
                ?>
                    </tr>
                    <tr>
                        <th scope="row">Visits</th>
                <?php
                    $num = $this->statistics_getdailystats();
                    for ($i=1; $i < 32; $i++) {
                        echo '<td>' . $num[$i] . '</td>';
                    }
                ?>
                    </tr>
                    <tr>
                        <th scope="row">+/~/-</th>
                <?php
                    $num = $this->statistics_getdailystats();
                    $rep = $num;
                    rsort($rep);

                    for ($i=1; $i < 32; $i++) {
                        $maxVisHeigh = 100/$rep[0]*2;
                        $dailyHeight = round($num[$i]*$maxVisHeigh);
                        echo '<td class="stats_imagecell"><img src="plugins/serendipity_event_statistics/';
                        if ($num[$i]*$maxVisHeigh/2 <= 33) {
                            echo 'red.png';
                        } else if ($num[$i]*$maxVisHeigh/2 > 33 && $num[$i]*$maxVisHeigh/2 < 66) {
                            echo 'yellow.png';
                        } else {
                            echo 'green.png';
                        }
                        echo '" width="8" height="'.$dailyHeight.'" style="height:'.$dailyHeight.'px" alt="';
                        if ($num[$i]*$maxVisHeigh/2 <= 33) {
                            echo '-';
                        } else if ($num[$i]*$maxVisHeigh/2 > 33 && $num[$i]*$maxVisHeigh/2 < 66) {
                            echo '~';
                        } else {
                            echo '+';
                        }
                        echo '" /></td>';
                    }
                ?>
                    </tr>
                </table>
        <?php } ?>
            </section>

            <section class="wide_box">
                <h3><?php echo PLUGIN_EVENT_STATISTICS_EXT_VISLATEST;?></h3>

                <dl>
<?php
    $i=1;
    if (is_array($visitors_latest)) {
        foreach ($visitors_latest AS $key => $row) {
                echo '<dt class="stats_header">'.$row['day'].' ('.$row['time'].')';
                echo "<span>" . ($row['ip'] ? gethostbyaddr($row['ip']) : '-') . "</span>\n";
                echo "</dt>\n";

            if($row['ref'] != 'unknown'){
                echo "<dd><a href=\"".$row['ref']."\">".$row['ref']."</a></dd>\n";
            }
            if($row['ref'] == 'unknown'){
                echo "<dd>".$row['ref']."</dd>\n";
            }
                echo "<dd>".$row['browser']."</dd>\n";
        }
    }
?>
                </dl>
            </section>
        </div>
<?php
    } //end of function extendedVisitorStatistics()

    function createTables() {
        global $serendipity;

        //create table xxxx_visitors
        $q   = "CREATE TABLE {$serendipity['dbPrefix']}visitors (
            counter_id {AUTOINCREMENT} {PRIMARY},
            sessID varchar(35) not null default '',
            day varchar(10) not null default '',
            time varchar(5) not null default '',
            ref varchar(255) default null,
            browser varchar(255) default null,
            ip varchar(45) default null
        )";

       serendipity_db_schema_import($q);

        //create table xxxx_visitors_counts
        $q   = "CREATE TABLE {$serendipity['dbPrefix']}visitors_count (
            year int(4) not null,
            month int(2) not null,
            day int(2) not null,
            visits int(11) not null,
            hits int(11) not null
        )";

       serendipity_db_schema_import($q);

        //create table xxxx_refs
        $q   = "CREATE TABLE {$serendipity['dbPrefix']}refs (
            id {AUTOINCREMENT} {PRIMARY},
            refs varchar(255) not null default '',
            count int(11) not null default '0'
        )";
        serendipity_db_schema_import($q);

        $this->updateTables();
    } //end of function createTables()

    function updateTables($dbic=0) {
        global $serendipity;

        if ($dbic == 0) {
            //create indices
            $q   = "CREATE INDEX visitorses ON {$serendipity['dbPrefix']}visitors (sessID);";
            serendipity_db_schema_import($q);
            $q   = "CREATE INDEX visitorday ON {$serendipity['dbPrefix']}visitors (day);";
            serendipity_db_schema_import($q);
            $q   = "CREATE INDEX visitortime ON {$serendipity['dbPrefix']}visitors (time);";
            serendipity_db_schema_import($q);
            $q   = "CREATE INDEX visitortimeb ON {$serendipity['dbPrefix']}visitors_count (year, month, day);";
            serendipity_db_schema_import($q);
            $q   = "CREATE INDEX refsrefs ON {$serendipity['dbPrefix']}refs (refs(191));";
            serendipity_db_schema_import($q);
            $q   = "CREATE INDEX refscount ON {$serendipity['dbPrefix']}refs (count);";
            serendipity_db_schema_import($q);

            $this->set_config('db_indices_created', '2');
        }

        if ($dbic == 1) {
            if (preg_match('@(postgres|pgsql)@i', $serendipity['dbType'])) {
                $q = "ALTER TABLE {$serendipity['dbPrefix']}visitors ALTER COLUMN ip TYPE VARCHAR(45)";
            } else {
                $q = "ALTER TABLE {$serendipity['dbPrefix']}visitors CHANGE COLUMN ip ip VARCHAR(45)";
            }
            serendipity_db_schema_import($q);

            $this->set_config('db_indices_created', '2');
        }
    }


    function dropTables() {

        global $serendipity;

        // Drop tables
        $q   = "DROP TABLE ".$serendipity['dbPrefix']."visitors";
        $sql = serendipity_db_schema_import($q);
        $q   = "DROP TABLE ".$serendipity['dbPrefix']."visitors_count";
        $sql = serendipity_db_schema_import($q);
        $q   = "DROP TABLE ".$serendipity['dbPrefix']."refs";
        $sql = serendipity_db_schema_import($q);

    } //end of function dropTables

    function install(){

        $this->createTables();

    }

    function uninstall(&$propbag){

        $this->dropTables();

    }

}

/* vim: set sts=4 ts=4 expandtab : */