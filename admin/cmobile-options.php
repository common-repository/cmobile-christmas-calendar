<?php
class cmob_cc_MySettingsPage
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;
    private $dynamic_urls;
    private $coupon_urls;

    /**
     * Start up
     */
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
        
        //$this->dynamic_urls="shit";
        //add_action( 'admin_enqueue_scripts', array($this,'softlights_color_picker' ));

    }
        /**
        * load dynamic url:s from the API
        * Only for the PRO calendar
	    */
        public function load_dynamic_urls()
        {
            //Get the folder from 
            if (isset( $this->options['pro_api_key'] ))
            {
                $apiKey=$this->options['pro_api_key'];
                $apiUrl      = "https://qr.claroappstore.com/api";
                $action      = "qrcodes";
                $folder      =$this->options['pro_folder'];
                
            
                $jsonurl     = "$apiUrl/$action?key=$apiKey&folder=$folder";
                $json        = file_get_contents($jsonurl, 0, null, null);
                $json_output = json_decode($json);
            
                $this->dynamic_urls= $json_output;

            }
            else $this->dynamic_urls='N/A';
        }
        /**
        * load coupons from the API
        * Only for the PRO calendar
	    */
        public function load_coupon_urls()
        {
            if (isset( $this->options['pro_secret_api_key'] ))
            {
                https://qr.claroappstore.com/api/coupon/list?secretkey=cc9ec9423710a1c2a3e960dc087a749a
                $apiSecretKey=$this->options['pro_secret_api_key'];
                $apiUrl      = "https://qr.claroappstore.com/api/coupon";
                $action      = "list";
                $folder      ="adventcalender-cmobile";
                
            
                $jsonurl     = "$apiUrl/$action?secretkey=$apiSecretKey";
                $json        = file_get_contents($jsonurl, 0, null, null);
                $json_output = json_decode($json);
            
                $this->coupon_urls= $json_output;

            }
            else $this->coupon_urls='N/A';
        
        }
    /**
    * Check api keys
    * Only for the PRO calendar
    */
    public function check_api_key()
    {
        
        if (isset( $this->options['pro_api_key'] ))
        {
            $api_key=$this->options['pro_api_key'];
            
            if($api_key!='')
            {
                //check secret api key
                if (isset( $this->options['pro_secret_api_key'] ))
                {
                    $secret_api_key=$this->options['pro_secret_api_key'];
                    
                    if($secret_api_key!='')
                    {
                        return true;
                    }
                }
               
            }
            return "undefined"; 
        }
        return "undefined";
    }
    /**
     * Add options page
     */
    public function add_plugin_page()
    {
        // This page will be under "Settings"
        add_options_page(
            'Settings Admin', 
            'Christmas Calendar', 
            'manage_options', 
            'my-setting-admin', 
            array( $this, 'create_admin_page' )
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        // Set class property
        $this->options = get_option( 'cmob_cc_option' );
        ?>
        <div class="wrap">
            <?php printf(__( '%s Christmas Calendar - digital marketing %s', 'cmobile-christmascalendar' ), '<h1>', '</h1>' ); ?>
            
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'cmob_cc_option_group' );
                do_settings_sections( 'my-setting-admin' );
                submit_button();
            ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {        
        
        register_setting(
            'cmob_cc_option_group', // Option group
            'cmob_cc_option', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'setting_section_id', // ID
            __('Your unique calendar!','cmobile-christmascalendar'), // Title
            array( $this, 'print_section_info' ), // Callback
            'my-setting-admin' // Page
        );  

        add_settings_field(
            'pro_api_key', // ID
            __('Your API Key <br>(Only for pro version)','cmobile-christmascalendar'), // Title 
            array( $this, 'pro_api_key_callback' ), // Callback
            'my-setting-admin', // Page
            'setting_section_id' // Section           
        );     
        add_settings_field(
            'pro_secret_api_key', // ID
            __('Your Secret API Key <br>(Only for pro version)','cmobile-christmascalendar'), // Title 
            array( $this, 'pro_secret_api_key_callback' ), // Callback
            'my-setting-admin', // Page
            'setting_section_id' // Section           
        );     
        add_settings_field(
            'pro_folder', // ID
            __('Christmas Calender folder<br>(Only for pro version)','cmobile-christmascalendar'), // Title 
            array( $this, 'pro_folder_callback' ), // Callback
            'my-setting-admin', // Page
            'setting_section_id' // Section           
        );     
        add_settings_field(
            'total_days_radio', // ID
            __('Days in calendar','cmobile-christmascalendar'), // Title 
            array( $this, 'total_days_radio_callback' ), // Callback
            'my-setting-admin', // Page
            'setting_section_id' // Section           
        );  
        add_settings_field(
            'fake_date', // ID
            __('Fake the date (for testing) <br>(format:YYYY-MM-DD)','cmobile-christmascalendar'), // Title 
            array( $this, 'fake_date_callback' ), // Callback
            'my-setting-admin', // Page
            'setting_section_id' // Section           
        );     
        add_settings_field(
            'include_day_dropdown', // ID
            __('Include dropdown with previous days','cmobile-christmascalendar'), // Title 
            array( $this, 'include_day_dropdown_callback' ), // Callback
            'my-setting-admin', // Page
            'setting_section_id' // Section           
        );  
        add_settings_field(
            'include_day', // ID
            __('Include countdown timer','cmobile-christmascalendar'), // Title 
            array( $this, 'include_countdown_callback' ), // Callback
            'my-setting-admin', // Page
            'setting_section_id' // Section           
        );  
        
        add_settings_field(
            'day1_url_description', // ID
            '', // Title 
            array( $this, 'day1_url_description_callback' ), // Callback
            'my-setting-admin', // Page
            'setting_section_id' // Section           
        );  

        
        for ($x = 0; $x <= 24; $x++)
        {
            $args     = array (
                'id' => "day".strval($x+1)."_url",
                'qr_index' => $x
            );
            $title=__('URL Day','cmobile-christmascalendar');
            $titel_final=$title . ' ' . strval($x+1);
            add_settings_field(
                $args['id'], // ID
                $titel_final, // Title 
                array( $this, 'day_url_callback' ), // Callback
                'my-setting-admin', // Page
                'setting_section_id',//Section
                $args       
            ); 
        }
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        $new_input = array();
        
        if( isset( $input['pro_api_key'] ) )
        $new_input['pro_api_key'] = sanitize_text_field( $input['pro_api_key'] );

        if( isset( $input['pro_secret_api_key'] ) )
        $new_input['pro_secret_api_key'] = sanitize_text_field( $input['pro_secret_api_key'] );

        if( isset( $input['pro_folder'] ) )
        $new_input['pro_folder'] = sanitize_text_field( $input['pro_folder'] );
 
        if( isset( $input['total_days_radio'] ) )
            $new_input['total_days_radio'] = sanitize_text_field( $input['total_days_radio'] );

        if( isset( $input['fake_date'] ) )
            $new_input['fake_date'] = sanitize_text_field( $input['fake_date'] );

        if( isset( $input['include_day_dropdown'] ) )
            $new_input['include_day_dropdown'] = sanitize_text_field( $input['include_day_dropdown'] );
        
        if( isset( $input['include_countdown'] ) )
            $new_input['include_countdown'] = sanitize_text_field( $input['include_countdown'] );
        
        if( isset( $input['title'] ) )
            $new_input['title'] = sanitize_text_field( $input['title'] );
       
        if( isset( $input['day1_url'] ) )
            $new_input['day1_url'] = sanitize_text_field( $input['day1_url'] );
            
        if( isset( $input['day2_url'] ) )
            $new_input['day2_url'] = sanitize_text_field( $input['day2_url'] );
        
            if( isset( $input['day3_url'] ) )
            $new_input['day3_url'] = sanitize_text_field( $input['day3_url'] );
            
        if( isset( $input['day4_url'] ) )
            $new_input['day4_url'] = sanitize_text_field( $input['day4_url'] );

        if( isset( $input['day5_url'] ) )
            $new_input['day5_url'] = sanitize_text_field( $input['day5_url'] );

        if( isset( $input['day6_url'] ) )
            $new_input['day6_url'] = sanitize_text_field( $input['day6_url'] );

        if( isset( $input['day7_url'] ) )
            $new_input['day7_url'] = sanitize_text_field( $input['day7_url'] );

        if( isset( $input['day8_url'] ) )
            $new_input['day8_url'] = sanitize_text_field( $input['day8_url'] );

        if( isset( $input['day9_url'] ) )
            $new_input['day9_url'] = sanitize_text_field( $input['day9_url'] );

        if( isset( $input['day10_url'] ) )
            $new_input['day10_url'] = sanitize_text_field( $input['day10_url'] );

        if( isset( $input['day11_url'] ) )
            $new_input['day11_url'] = sanitize_text_field( $input['day11_url'] );

        if( isset( $input['day12_url'] ) )
            $new_input['day12_url'] = sanitize_text_field( $input['day12_url'] );
        
            if( isset( $input['day13_url'] ) )
            $new_input['day13_url'] = sanitize_text_field( $input['day13_url'] );
            
        if( isset( $input['day14_url'] ) )
            $new_input['day14_url'] = sanitize_text_field( $input['day14_url'] );
        
            if( isset( $input['day15_url'] ) )
            $new_input['day15_url'] = sanitize_text_field( $input['day15_url'] );
            
        if( isset( $input['day16_url'] ) )
            $new_input['day16_url'] = sanitize_text_field( $input['day16_url'] );

        if( isset( $input['day17_url'] ) )
            $new_input['day17_url'] = sanitize_text_field( $input['day17_url'] );

        if( isset( $input['day18_url'] ) )
            $new_input['day18_url'] = sanitize_text_field( $input['day18_url'] );

        if( isset( $input['day19_url'] ) )
            $new_input['day19_url'] = sanitize_text_field( $input['day19_url'] );

        if( isset( $input['day20_url'] ) )
            $new_input['day20_url'] = sanitize_text_field( $input['day20_url'] );

        if( isset( $input['day21_url'] ) )
            $new_input['day21_url'] = sanitize_text_field( $input['day21_url'] );

        if( isset( $input['day22_url'] ) )
            $new_input['day22_url'] = sanitize_text_field( $input['day22_url'] );

        if( isset( $input['day23_url'] ) )
            $new_input['day23_url'] = sanitize_text_field( $input['day23_url'] );

        if( isset( $input['day24_url'] ) )
            $new_input['day24_url'] = sanitize_text_field( $input['day24_url'] );

        if( isset( $input['day25_url'] ) )
            $new_input['day25_url'] = sanitize_text_field( $input['day25_url'] );

        
        return $new_input;
    }

    /** 
     * Print the Section text
     */
    public function print_section_info()
    {
        printf(__( 'Enter your settings below and place the shortcodes in your page (only once per page):<br>[cmob_ccholder]<br>[cmob_cc]', 'cmobile-christmascalendar' ));
        printf(__( "<br><h3>Note! <a href='https://cportal.cmmqr.com/user/login'>Login</a> to your PRO service to get your API keys and folder name.</h3>", 'cmobile-christmascalendar' ));
        
    }
    
     /** 
     * Get the settings option array and print the API key value
     */
     public function pro_api_key_callback()
     {
         printf(
             '<input type="text" id="pro_api_key" size="40" name="cmob_cc_option[pro_api_key]" value="%s" />',
             isset( $this->options['pro_api_key'] ) ? esc_attr( $this->options['pro_api_key']) : ''
         );
     }

     /** 
     * Get the settings option array and print secret API key value
     */
     public function pro_secret_api_key_callback()
     {
         printf(
             '<input type="text" id="pro_secret_api_key" size="40" name="cmob_cc_option[pro_secret_api_key]" value="%s" />',
             isset( $this->options['pro_secret_api_key'] ) ? esc_attr( $this->options['pro_secret_api_key']) : ''
         );
     }

     /** 
     * Get the settings option array and print secret API key value
     */
    public function pro_folder_callback()
    {
        printf(
            '<input type="text" id="pro_folder" size="40" name="cmob_cc_option[pro_folder]" value="%s" />',
            isset( $this->options['pro_folder'] ) ? esc_attr( $this->options['pro_folder']) : ''
        );
    }
     
    /** 
     * Get the settings option array and print the value of the total day
     */
    public function total_days_radio_callback()
    {
        $days_text=__('days','cmobile-christmascalendar');
        $items = array("24", "25");
        $options = isset( $this->options['total_days_radio'] ) ? esc_attr( $this->options['total_days_radio']) : '24';
	    foreach($items as $item) {
		    $checked = ($options==$item) ? ' checked="checked" ' : '24';
		    echo "<label><input ".$checked." value='$item' name='cmob_cc_option[total_days_radio]' type='radio' />$item $days_text</label><br />";
	    }

    }
    /** 
     * Get the settings option array and print the value of fake date
     */
    public function fake_date_callback()
    {
        printf(
            '<input type="text" id="fake_date" size="40" name="cmob_cc_option[fake_date]" value="%s" />',
            isset( $this->options['fake_date'] ) ? esc_attr( $this->options['fake_date']) : ''
        );
    }
    /** 
     * Get the settings option array and print the value of countdown on/off
     */
    public function include_countdown_callback()
    {
        $items = array("on", "");
        $options = isset( $this->options['include_countdown'] ) ? esc_attr( $this->options['include_countdown']) : 'on';
        
	    foreach($items as $item) {
            if($item=='on')
            {
                $onoff='On';
            }
            else
            {
                $onoff='Off';
            }
		    $checked = ($options==$item) ? ' checked="checked" ' : '';
		    echo "<label><input ".$checked." value='$item' name='cmob_cc_option[include_countdown]' type='radio' />$onoff</label><br />";
	    }
       
    }
    /** 
     * Get the settings option array and print the value of include dropdown
     */
    public function include_day_dropdown_callback()
    {
        $items = array("on", "");
        $options = isset( $this->options['include_day_dropdown'] ) ? esc_attr( $this->options['include_day_dropdown']) : 'on';
        
	    foreach($items as $item) {
            if($item=='on')
            {
                $onoff='On';
            }
            else
            {
                $onoff='Off';
            }
		    $checked = ($options==$item) ? ' checked="checked" ' : '';
		    echo "<label><input ".$checked." value='$item' name='cmob_cc_option[include_day_dropdown]' type='radio' />$onoff</label><br />";
	    }
       
    }
    /** 
     * Get the settings option array and print the description of URL:s
     */
     public function day1_url_description_callback()
     {
        $this->load_dynamic_urls();
        $this->load_coupon_urls();
        if($this->check_api_key()==="undefined")
        {
            $txt1=__('<p>Fill in your destination URL:s and engage your visitors.</p>','cmobile-christmascalendar');
            $txt2=__("<p>For more features <a href='https://christmas.cmobile.se' target='_blank'>UPGRADE to our PRO version:</a></p>",'cmobile-christmascalendar');
            $txtli1=__('<ul><li>Create your own Coupons</li>','cmobile-christmascalendar');
            $txtli2=__('<li>Get live statistics (graph)</li>','cmobile-christmascalendar');
            $txtli3=__('<li>and a lot lot more</li></ul>','cmobile-christmascalendar');
            printf(
                $txt1 . $txt2 . $txtli1 . $txtli2 . $txtli3   
            );
         }
         else
         {
            printf(__(
                '<p>Fill in your destination URL:s in your <a href="https://cportal.cmmqr.com/user/login ">backend</a></p>'
            ));
            printf(__(
                '<p><h3>Note!</h3> Remember to refresh plugin settings after changes of url:s and after that save changes.</p>'
            ));
         }
     }
    
    /** 
     * Get the settings option array and print the values of all 24/25 days
     */
    public function day_url_callback(array $args)
    {
        $id   = $args['id'];
        $qr_index = $args['qr_index'];

        if($this->check_api_key()==="undefined")
        {
            printf(
                '<input type="text" id="'.$id.'" size="40" name="cmob_cc_option['.$id.']" value="%s" />',
                isset( $this->options[$id] ) ? esc_attr( $this->options[$id]) : 'https://www.yoururl.com'
            );
        }
        else
        {
            //Get day from id
            //coupons (same name but starts with c_) override normal qr codes 
            $qrday=substr(strrchr($this->dynamic_urls->result->qrcodes[$qr_index]->id,"-"),1);
            $hit=0;
            
            foreach ($this->coupon_urls->result as $obj)
            {
                $qrdayCoupon=substr(strrchr($obj->shorturl,"-"),1);
                
                if($qrdayCoupon==$qrday) 
                {
                    $hit=1; //coupon exists, override qr code
                    $shorturl=$obj->shorturl;
                    $voucher=$obj->voucher;
                    break;
                }
            }
          
            if ($hit>0)
            {
                
                $this->options[$id]=$shorturl;
                
                printf(
                    '<input type="hidden" id="'.$id.'" size="40" name="cmob_cc_option['.$id.']" value="%s" />',
                    isset( $this->options[$id] ) ? esc_attr( $this->options[$id]) : 'shit'
                );
                echo "COUPON: ".$shorturl." (Voucher:".$voucher.")";
            }
            else
            {
                $this->options[$id]=$this->dynamic_urls->result->qrcodes[$qr_index]->shorturl;
                
                printf(
                    '<input type="hidden" id="'.$id.'" size="40" name="cmob_cc_option['.$id.']" value="%s" />',
                    isset( $this->options[$id] ) ? esc_attr( $this->options[$id]) : ''
                );
                printf(
                    $this->dynamic_urls->result->qrcodes[$qr_index]->shorturl .'  =>  '.$this->dynamic_urls->result->qrcodes[$qr_index]->url.'<a href="'.$this->dynamic_urls->result->qrcodes[$qr_index]->qr.'" target="_blank">   (Download QR-code</a>)'
            );
        }
        }
    }
    
}

if( is_admin() )
    $cmob_cc_my_settings_page = new cmob_cc_MySettingsPage();

  