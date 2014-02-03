<?php


include_once('WpGrass_LifeCycle.php');


class WpGrass_Plugin extends WpGrass_LifeCycle {

    const defaultDays = '365';
    const cleanDays = 7;

    /**
     * See: http://plugin.michael-simpson.com/?page_id=31
     * @return array of option meta data.
     */
    public function getOptionMetaData() {
        //  http://plugin.michael-simpson.com/?page_id=31
        return array(
            //'_version' => array('Installed Version'), // Leave this one commented-out. Uncomment to test upgrades.
            'days' => array(__('Full-grown grass takes', self::defaultDays))
            //'Donated' => array(__('I have donated to this plugin', 'my-awesome-plugin'), 'false', 'true'),
            //'CanSeeSubmitData' => array(__('Can See Submission data', 'my-awesome-plugin'),
                                        //'Administrator', 'Editor', 'Author', 'Contributor', 'Subscriber', 'Anyone')
        );
    }

//    protected function getOptionValueI18nString($optionValue) {
//        $i18nValue = parent::getOptionValueI18nString($optionValue);
//        return $i18nValue;
//    }

    protected function initOptions() {
        $options = $this->getOptionMetaData();
        if (!empty($options)) {
            foreach ($options as $key => $arr) {
                if (is_array($arr) && count($arr > 1)) {
                    $this->addOption($key, $arr[1]);
                }
            }
        }
    }

    public function getPluginDisplayName() {
        return 'WP-Grass';
    }

    protected function getMainPluginFileName() {
        return 'wp-grass.php';
    }

    /**
     * See: http://plugin.michael-simpson.com/?page_id=101
     * Called by install() to create any database tables if needed.
     * Best Practice:
     * (1) Prefix all table names with $wpdb->prefix
     * (2) make table names lower case only
     * @return void
     */
    protected function installDatabaseTables() {
        //        global $wpdb;
        //        $tableName = $this->prefixTableName('mytable');
        //        $wpdb->query("CREATE TABLE IF NOT EXISTS `$tableName` (
        //            `id` INTEGER NOT NULL");
    }

    /**
     * See: http://plugin.michael-simpson.com/?page_id=101
     * Drop plugin-created tables on uninstall.
     * @return void
     */
    protected function unInstallDatabaseTables() {
        //        global $wpdb;
        //        $tableName = $this->prefixTableName('mytable');
        //        $wpdb->query("DROP TABLE IF EXISTS `$tableName`");
    }


    public function getDaysSinceLastPost(){
        $args = array( 'numberposts' => '1' );
        $recent_post = current(wp_get_recent_posts( $args ));

        $days_diff = 0;

        if ($recent_post){
            $post_date = $recent_post['post_date'];

            $post_time = strtotime($post_date);

            $current_time = time();



            $days_diff = round(($current_time - $post_time)/(60 * 60 * 24));

        }

        return $days_diff;


    }
    /**
     * Called by addActionsAndFilters()
     * Main plugin functions add a grass div after the footer in themes.
     */
    public function growGrass() {

        $days_to_full_grown =  intval($this->getOption('days',self::defaultDays));
        $days_diff =  intval($this->getDaysSinceLastPost());

        $size = 0;



        // if 
        if ($days_diff>self::cleanDays){

            $new_size = $days_diff / $days_to_full_grown * 100;
            if ($new_size>100) {
                $new_size = 100;
            }
            else if ($new_size<10){
                $new_size = 10;
            }

            $size = $new_size;

        }


        echo '<div class="wp-grass"><div style="background-size:'.$size.'%"></div></div>';


    }


    /**
     * Perform actions when upgrading from version X to version Y
     * See: http://plugin.michael-simpson.com/?page_id=35
     * @return void
     */
    public function upgrade() {
    }

    public function addActionsAndFilters() {

        // Add options administration page
        // http://plugin.michael-simpson.com/?page_id=47
        add_action('admin_menu', array(&$this, 'addSettingsSubMenuPage'));

        // Example adding a script & style just for the options administration page
        // http://plugin.michael-simpson.com/?page_id=47
        //        if (strpos($_SERVER['REQUEST_URI'], $this->getSettingsSlug()) !== false) {
        //            wp_enqueue_script('my-script', plugins_url('/js/my-script.js', __FILE__));
        //            wp_enqueue_style('my-style', plugins_url('/css/my-style.css', __FILE__));
        //        }


        // Add Actions & Filters
        // http://plugin.michael-simpson.com/?page_id=37

        add_action('wp_footer', array(&$this, 'growGrass'));

        // Adding scripts & styles to all pages
        // Examples:
        //        wp_enqueue_script('jquery');
        wp_enqueue_style('grass-style', plugins_url('/css/grass.css', __FILE__));
        //        wp_enqueue_script('my-script', plugins_url('/js/my-script.js', __FILE__));


        // Register short codes
        // http://plugin.michael-simpson.com/?page_id=39


        // Register AJAX hooks
        // http://plugin.michael-simpson.com/?page_id=41

    }


}
