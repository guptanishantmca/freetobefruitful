<?php
// Your code to enqueue parent theme styles
function enqueue_child_theme_styles()
{
    // Get the parent theme stylesheet
    $parent_style = 'parent-style'; // This is 'twentytwentyone-style' for the Twenty Twenty-One theme

    // Enqueue parent theme stylesheet
    wp_enqueue_style($parent_style, get_template_directory_uri() . '/style.css');

    // Enqueue child theme stylesheet
    wp_enqueue_style(
        'child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array($parent_style),
        wp_get_theme()->get('Version')
    );
}
add_action('wp_enqueue_scripts', 'enqueue_child_theme_styles');


add_shortcode("feeds", "callback_feeds");

function callback_feeds($atts)
{
    $current_page = max(1, get_query_var('paged'));

    $url = $atts['url'] . '?page=' . $current_page;


    $arguments = array(
        'method' => 'POST',
    );
    $response = wp_remote_get($url, $arguments);
    //echo '<pre>'; print_r(json_decode(wp_remote_retrieve_body($response)));
    if (is_wp_error($response)) {
        $error_message = $response->get_error_message();
        return 'Something went wrong:' . $error_message;
    }
    $results = json_decode(wp_remote_retrieve_body($response));


    if (isset($results->data) && !empty($results->data)) {
        $html = '<form id="myForm"><div class="flex flex-col md:flex-row items-center rounded-md border border-gray-200 w-full">
        <div class="flex flex-col md:flex-row gap-3 border-r py-3 px-6 w-full">
        
                <div class="space-y-2 grow"><label
                                class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                                for="query">Hakusanat</label><input type="text"
                                class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                                id="query" name="query" placeholder="Kirjoita hakusanoja"></div>
                <div class="space-y-2 grow"><label
                                class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                                for="picture">Valitse alue</label>
                                <select name="state" id="state" class="flex h-10 w-full items-center justify-between rounded-md border border-input bg-transparent px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50" >
                                </select>
                                </div>
                <div class="space-y-2 grow"><label
                                class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                                for="type">Valitse tyyppi</label>
                                <select name="type" id="type" class="flex h-10 w-full items-center justify-between rounded-md border border-input bg-transparent px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50" >
                                <option value="">Kaikki</option>
                                <option value="material">Materiaalit</option>
                                <option value="work">Työ</option>
                                </select>
                                </div>
                <!-- <div class="space-y-2 grow"><label
                                class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                                for="type">Järjestys</label>
                                </div>-->
       
                                </div>
        <div class="flex flex-row gap-3 items-center justify-center px-6 py-4" style="width:10%;"> 
        <button
                        class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 py-2 grow">Hae</button>
                        
       </div> 
</div></form>';

        $html .= '<div id="content">';
        $html .= '<div class="flex flex-col gap-6 w-full">';
        // Loop through the data and create list items
        foreach ($results->data as $item) {
            if ($item->category_type == 'Work') {
                $category_type = 'Työ';
            } else {
                $category_type = 'Materiaali';
            }

            if ($item->featured_image != '' && $item->featured_image != 'null') {
                $featured_image = 'https://chat.proppu.com/proppu/public/images/marketplace/material/' . $item->featured_image;
            } else {
                $featured_image = get_stylesheet_directory_uri() . '/images/placeholder.png';
            }

            if ($item->type == 'Offer') {
                $type = 'Tarjous';
            } else {
                $type = 'Työtarjouspyyntö';
            }
            $html .= '<a href="'.home_url('/' . 'feed-detail/?id='.$item->id). '">
            <div
                    class="flex flex-col md:flex-row gap-6 p-6 border border-gray-200 rounded-lg w-full hover:bg-gray-50 hover:border-gray-300">
                    <div class="w-[100px] md:w-[200px]"><img alt="No image"
                                    src="' . $featured_image . '" width="550"
                                    height="410" decoding="async" data-nimg="1" class="w-100" loading="lazy"
                                    style="color: transparent;"></div>
                    <div class="space-y-6">
                            <div>
                                    <h3 class="font-semibold text-primary">' . $item->title . '</h3>
                                    <p class="text-foreground text-break">' . wp_trim_words($item->description, 7, '...') . '
                                    </p>
                                    <p class="text-foreground text-break">Kohteet pääasiassa Helsingissä ja...</p>
                            </div>
                            <div class="flex flex-col md:flex-row gap-6">
                                    <ul class="flex flex-row gap-4 style-none">
                                            <li>
                                                    <div
                                                            class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-transparent bg-secondary text-secondary-foreground hover:bg-secondary/80">
                                                             ' . $category_type . '</div>
                                            </li>
                                            <li>
                                                    <div
                                                            class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-transparent bg-secondary text-secondary-foreground hover:bg-secondary/80">
                                                            ' . $type . '</div>
                                            </li>
                                            <li>
                                                    <div
                                                            class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-transparent bg-secondary text-secondary-foreground hover:bg-secondary/80">
                                                            ' . $item->state_identifier . '</div>
                                            </li>
                                    </ul>
                                    <ul class="flex flex-row gap-4 md:border-l md:pl-6 text-sm style-none">
                                            <li>Tarjous päättyy: ' . date('d.m.Y', strtotime($item->time_left)) . '</li>
                                    </ul>
                            </div>
                    </div>
            </div>
    </a>';
        }
        $html .= '</div>';
    } else {
        $html = 'No data found';
    }

    $pagination = paginate_links(array(
        'total' => $results->last_page,
        'current' => $results->current_page,
        'prev_text' => '&laquo; Edellinen',
        'next_text' => 'Seuraava &raquo;',
        'type' => 'array',
    ));

    // Output pagination links
    if ($pagination) {
        //  $html .=  '<div id="pagination">';
        $html .=  '<div class="pagination">';
        foreach ($pagination as $page_link) {
            $html .=  '<span class="page-link">' . $page_link . '</span>';
        }
        $html .=  '</div>';
        $html .=  '</div>';
    }

    return $html;
}


add_action('wp_ajax_feeds_main', 'callback_feeds_main');
add_action('wp_ajax_nopriv_feeds_main', 'callback_feeds_main');
function callback_feeds_main($atts)
{



    if (isset($_REQUEST['paged'])) {
        $current_page = $_REQUEST['paged'];
    } else {
        $current_page = max(1, get_query_var('paged'));
    }

    if (isset($_REQUEST['search'])) {
        $search = $_REQUEST['search'];
    } else {
        $search = '';
    }
    if (isset($_REQUEST['state'])) {
        $state = $_REQUEST['state'];
    } else {
        $state = '';
    }

    if (isset($_REQUEST['type'])) {
        $type = $_REQUEST['type'];
    } else {
        $type = '';
    }
    $url = $atts['url'] . 'https://chat.proppu.com/proppu/public/api/global_feed?page=' . $current_page . '&search=' . $search . '&state=' . $state . '&type=' . $type;


    $arguments = array(
        'method' => 'POST',
        'search' => isset($_REQUEST['search']) ? $_REQUEST['search'] : '',
    );
    $response = wp_remote_get($url, $arguments);

    if (is_wp_error($response)) {
        $error_message = $response->get_error_message();
        return 'Something went wrong:' . $error_message;
    }
    $results = json_decode(wp_remote_retrieve_body($response));


    if (isset($results->data) && !empty($results->data)) {

        $html = '';
        $html .= '<div id="content">';
        $html .= '<div class="flex flex-col gap-6 w-full">';
        // Loop through the data and create list items
        foreach ($results->data as $item) {
            if ($item->category_type == 'Work') {
                $category_type = 'Työ';
            } else {
                $category_type = 'Materiaali';
            }

            if ($item->featured_image != '' && $item->featured_image != 'null') {
                $featured_image = 'https://chat.proppu.com/proppu/public/images/marketplace/material/' . $item->featured_image;
            } else {
                $featured_image = get_stylesheet_directory_uri() . '/images/placeholder.png';
            }

            if ($item->type == 'Offer') {
                $type = 'Tarjous';
            } else {
                $type = 'Työtarjouspyyntö';
            }
            $html .= '<a href="'.home_url('/' . 'feed-detail/?id='.$item->id). '">
            <div
                    class="flex flex-col md:flex-row gap-6 p-6 border border-gray-200 rounded-lg w-full hover:bg-gray-50 hover:border-gray-300">
                    <div class="w-[100px] md:w-[200px]"><img alt="No image"
                                    src="' . $featured_image . '" width="550"
                                    height="410" decoding="async" data-nimg="1" class="w-100" loading="lazy"
                                    style="color: transparent;"></div>
                    <div class="space-y-6">
                            <div>
                                    <h3 class="font-semibold text-primary">' . $item->title . '</h3>
                                    <p class="text-foreground text-break">' . wp_trim_words($item->description, 7, '...') . '
                                    </p>
                                    <p class="text-foreground text-break">Kohteet pääasiassa Helsingissä ja...</p>
                            </div>
                            <div class="flex flex-col md:flex-row gap-6">
                                    <ul class="flex flex-row gap-4 style-none">
                                            <li>
                                                    <div
                                                            class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-transparent bg-secondary text-secondary-foreground hover:bg-secondary/80">
                                                             ' . $category_type . '</div>
                                            </li>
                                            <li>
                                                    <div
                                                            class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-transparent bg-secondary text-secondary-foreground hover:bg-secondary/80">
                                                            ' . $type . '</div>
                                            </li>
                                            <li>
                                                    <div
                                                            class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-transparent bg-secondary text-secondary-foreground hover:bg-secondary/80">
                                                            ' . $item->state_identifier . '</div>
                                            </li>
                                    </ul>
                                    <ul class="flex flex-row gap-4 md:border-l md:pl-6 text-sm style-none">
                                            <li>Tarjous päättyy: ' . date('d.m.Y', strtotime($item->time_left)) . '</li>
                                    </ul>
                            </div>
                    </div>
            </div>
    </a>';
        }
        $html .= '</div>';
        //  $html .= '</div>';
    } else {
        $html = 'No data found';
    }

    $pagination = paginate_links(array(
        'total' => $results->last_page,
        'current' => $results->current_page,
        'prev_text' => '&laquo; Edellinen',
        'next_text' => 'Seuraava &raquo;',
        'type' => 'array',
    ));

    // Output pagination links
    if ($pagination) { //echo '<pre>';print_r($pagination);
        //$html .=  '<div id="pagination">';
        $html .=  '<div class="pagination">';
        foreach ($pagination as $page_link) {
            $html .=  '<span class="page-link">' . $page_link . '</span>';
        }
        $html .=  ' </div> ';
        $html .=  '</div>';
    }

    echo  $html;
?>
    <script>
        var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
        var loader = '<img src="<?php echo get_stylesheet_directory_uri() . '/images/Typing.gif'; ?>">';

        function cvf_load_all_posts(page) {
            var query = jQuery('[name="query"]').val();
            var state = jQuery('[name="state"]').val();
            var type = jQuery('[name="type"]').val();
            var data = {
                paged: page,

                action: "feeds_main",
                search: query,
                state: state,
                type: type
            };

            // Start the transition
            // jQuery("#content").fadeIn().css('background','#ccc');
            jQuery("#content").html(loader);

            // Data to receive from our server


            // Send the data
            jQuery.post(ajaxurl, data, function(response) {
                // If successful Append the data into our html container
                jQuery("#content").html(response);
                // End the transition
                jQuery("#content").css({
                    'background': 'none',
                    'transition': 'all 1s ease-out'
                });
            });
        }
        jQuery('.page-numbers').on('click', function(e) {
            e.preventDefault();
            var current_page = this.textContent;
            if (current_page == 'Seuraava »') {
                page = parseInt(page) + 1;
            } else if (current_page == '« Edellinen') {
                page = parseInt(page) - 1;
            } else {
                page = parseInt(current_page);
            }



            cvf_load_all_posts(page);

        });
    </script>
<?php
    die();
}


add_action('wp_ajax_feeds_detail', 'callback_feeds_detail');
add_action('wp_ajax_nopriv_feeds_detail', 'callback_feeds_detail');
function callback_feeds_detail($atts)
{
 


    if (isset($_REQUEST['id'])) {
        $id = $_REQUEST['id'];
    } else {
        $id = '';
    }

    $url = $atts['url'] . 'https://chat.proppu.com/proppu/public/api/feed_details/' . $id;


    $arguments = array(
        'method' => 'GET',
         
    );
    $response = wp_remote_get($url, $arguments);
 //print_r(json_decode(wp_remote_retrieve_body($response)));
    if (is_wp_error($response)) {
        $error_message = $response->get_error_message();
        return 'Something went wrong:' . $error_message;
    }
    $results = json_decode(wp_remote_retrieve_body($response));


    if (isset($results) && !empty($results)) {

        $html = '';
        $html .= '<div id="content">';
        $html .= '<div class="flex flex-col gap-6 w-full">';
        // Loop through the data and create list items
        foreach ($results as $item) {
            if ($item->tender_category_type == 'Work') {
                $category_type = 'Työ';
            } else {
                $category_type = 'Materiaali';
            }
             
            if ($item->tender_featured_image != '' && $item->tender_featured_image != 'null') {
                $featured_image = 'https://chat.proppu.com/proppu/public/images/marketplace/material/' . $item->tender_featured_image;
            } else {
                $featured_image = get_stylesheet_directory_uri() . '/images/placeholder.png';
            }

            if ($item->tender_type == 'Offer') {
                $type = 'Tarjous';
            } else {
                $type = 'Työtarjouspyyntö';
            }
            $html .= '<div class="space-y-12 text-start gap-12 w-full"><a class="flex flex-row gap-2" href="'.home_url('/' . 'kaikki-ilmoitukset').'"><span>←</span>Takaisin
            listaan</a>
        <h1 class="text-3xl md:text-5xl text-foreground text-left font-bold tracking-tight undefined">'.$item->tender_title.'</h1>
        <div class="flex flex-row rounded-md border">
            <ul class="flex flex-col w-full md:w-1/2 border-r border-r p-6">
                <li class="grid grid-cols-2 "><span>Tyyppi: </span>
                    <p class="font-bold">'.$type.'</p>
                </li>
                <li class="grid grid-cols-2"><span>Kohde:</span>
                    <p class="font-bold">'.$category_type.'</p>
                </li>
                <li class="grid grid-cols-2"><span>Sijainti:</span>
                    <p class="font-bold">'.$item->tender_state.'</p>
                </li>
                <li class="grid grid-cols-2 "><span>Vanhenee:</span>
                    <p class="font-bold">'.$item->tender_expiry_date.'</p>
                </li>
            </ul>
        </div>
        <p class="text-start">'.$item->tender_description.'</p>
        <div class="w-full flex justify-center"><a
                class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-14 rounded-md px-12 text-lg"
                href="https://app.proppu.com">Kirjaudu sisään ja jätä tarjous</a></div><img alt="No image"
            src="' . $featured_image . '" width="550" height="410" decoding="async" data-nimg="1"
            class="imageStyle" loading="lazy" style="color: transparent;">
    </div> ';
        }
        $html .= '</div>';
        //  $html .= '</div>';
    } else {
        $html = 'No data found';
    }

     

     
     

    echo  $html;
 
    die();
}

add_shortcode("latestfeeds", "callback_latest_feeds");

function callback_latest_feeds($atts)
{ 
    $form_post_id = $atts['form_post_id'];
    $data = array();
    global $wpdb;
    $cfdb         = apply_filters('cfdb7_database', $wpdb);
    $search       = empty($_REQUEST['search']) ? false : esc_sql($_REQUEST['search']);
    //$toimiala       = empty($_REQUEST['toimiala']) ? false : esc_sql($_REQUEST['toimiala']);
    //$sijainti       = empty($_REQUEST['sijainti']) ? false : esc_sql($_REQUEST['sijainti']);
    $form_id = empty($_REQUEST['form_id']) ? false : esc_sql($_REQUEST['form_id']);
    $table_name   = $wpdb->prefix . 'db7_forms';
    $page         = 1;
    $page         = $page - 1;
    $start        = $page * 100;
    //$form_post_id = 1758;

    $orderby = isset($_GET['orderby']) ? 'form_date' : 'form_id';
    $order   = isset($_GET['order']) && $_GET['order'] == 'asc' ? 'ASC' : 'DESC';

    
    //if (!empty($search) || !empty($toimiala) || !empty($sijainti)) {
        if (!empty($search) || !empty($form_id)) {    
        $where_conditions = array();
    
        // Add conditions for each parameter
        if (!empty($search)) {
            $where_conditions[] = "form_value LIKE '%$search%'";
        }
    
        if (!empty($form_id)) {
            $where_conditions[] = "form_post_id = $form_id";
        }else{
            $where_conditions[] = "form_post_id IN( '$form_post_id')";
        }
    
        // if (!empty($sijainti)) {
        //     $where_conditions[] = "form_value LIKE '%$sijainti%'";
        // }
    
        // Combine conditions with 'AND'
        $where_clause = implode(' AND ', $where_conditions);
        
        $results = $cfdb->get_results("SELECT * FROM $table_name 
                        WHERE 
                        $where_clause
                        
                        AND form_value LIKE '%\"cfdb7_status\";s:4:\"read\"%' 
                        ORDER BY $orderby $order
                        LIMIT $start,100", OBJECT
        );
    } else {
        // If no parameters are provided, retrieve all records
        $results = $cfdb->get_results("SELECT * FROM $table_name 
        WHERE 
        form_value LIKE '%\"cfdb7_status\";s:4:\"read\"%' 
        AND form_post_id IN($form_post_id)
        ORDER BY $orderby $order
        LIMIT $start,100", OBJECT
        );
    }
  
    $column_results       = $cfdb->get_results( "
            SELECT * FROM $table_name 
            WHERE form_post_id = $form_post_id ORDER BY form_id DESC LIMIT 1", OBJECT 
        );

        $first_row            = isset($results[0]) ? unserialize( $results[0]->form_value ): 0 ;
        $columns              = array();
        $rm_underscore        = apply_filters('remove_underscore_data', true); 

        if( !empty($first_row) ){
            //$columns['form_id'] = $results[0]->form_id;
            $columns['cb']      = '<input type="checkbox" />';
            $column_titles = array();
            foreach ($first_row as $key => $value) {

                $matches = array();
                $key     = esc_html( $key );

                if ( $key == 'cfdb7_status' ) continue;

                if( $rm_underscore ) preg_match('/^_.*$/m', $key, $matches);
                if( ! empty($matches[0]) ) continue;

                $key_val       = str_replace( array('your-', 'cfdb7_file'), '', $key);
                $key_val       = str_replace( array('_', '-'), ' ', $key_val);
                $columns[$key] = ucwords( $key_val );
                
                $column_titles[] = $key_val;

                if ( sizeof($columns) > 4) break;
            }
            $columns['form-date'] = 'Date';
            
            
        }

    foreach ($results as $result) {

        $form_value = unserialize($result->form_value);

        $link = "<b><a href=admin.php?page=cfdb7-list.php&fid=%s&ufid=%s>%s</a></b>";
        if (isset($form_value['cfdb7_status']) && ($form_value['cfdb7_status'] === 'read')) {
            $link = "<a href=admin.php?page=cfdb7-list.php&fid=%s&ufid=%s>%s</a>";
        }

        $fid                    = $result->form_post_id;
        $form_values['form_id'] = $result->form_id;

        foreach ($column_titles as $col_title) {
            $form_value[$col_title] = isset($form_value[$col_title]) ?
                $form_value[$col_title] : '';
        }

        foreach ($form_value as $k => $value) {

            $ktmp = $k;

            $can_foreach = is_array($value) || is_object($value);

            if ($can_foreach) {

                foreach ($value as $k_val => $val) {
                    $val                = esc_html($val);
                    $form_values[$ktmp] = (strlen($val) > 150) ? substr($val, 0, 150) . '...' : $val;
                    //$form_values[$ktmp] = sprintf($link, $fid, $result->form_id, $form_values[$ktmp]);
                }
            } else {
                $value = esc_html($value);
                $form_values[$ktmp] = (strlen($value) > 150) ? substr($value, 0, 150) . '...' : $value;
                //$form_values[$ktmp] = sprintf($link, $fid, $result->form_id, $form_values[$ktmp]);
            }
        }
        //$form_values['form-date'] = sprintf($link, $fid, $result->form_id, $result->form_date);
        $form_values['form-date'] = $result->form_date;
        $data[] = $form_values;
    }
    
    $html = '';
    $html = '<form id="myForm" ><div class="flex flex-col md:flex-row items-center rounded-md border border-gray-200 w-full">
        <div class="flex flex-col md:flex-row gap-3 border-r py-3 px-6 w-full">
        
                <div class="space-y-2 grow"><label
                                class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                                for="search">Vapaasanahaku</label><input type="text"
                                class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                                id="search" name="search" value="'.$search.'" placeholder="Kirjoita hakusanoja"></div>
                <div class="space-y-2 grow"><label
                class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                for="toimiala">Kategoria</label>
                <select name="form_id" id="form_id" class="flex h-10 w-full items-center justify-between rounded-md border border-input bg-transparent px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50" >
                <option value="">Keksinnöt ja liikeideat</option>
                <option value="1812">Myynti ilmoitukset</option>
                <option value="1757">Osto ilmoitukset</option>
                <option value="1776">Kehitä yritystä</option>
                </select>
                </div>                                
                <!-- <div class="space-y-2 grow"><label
                                class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                                for="toimiala">Valitse toimiala</label>
                                <select name="toimiala" id="toimiala" class="flex h-10 w-full items-center justify-between rounded-md border border-input bg-transparent px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50" >
                                <option value="">Keksinnöt ja liikeideat</option><option value="Palveluyritykset">Palveluyritykset</option><option value="Autovuokraamoja">Autovuokraamoja</option><option value="Autojen ehostus ja sisätilojen puhdistus">Autojen ehostus ja sisätilojen puhdistus</option><option value="Ajoneuvojen huoltokorjaamot">Ajoneuvojen huoltokorjaamot</option><option value="ATK-ohjelmointi, ATK-ylläpito (ITC)">ATK-ohjelmointi, ATK-ylläpito (ITC)</option><option value="Arkkitehti- ja rakennusuunnittelu">Arkkitehti- ja rakennusuunnittelu</option><option value="Hautaustoimisto">Hautaustoimisto</option><option value="Henkilöstövuokraus">Henkilöstövuokraus</option><option value="Insinööritoimisto">Insinööritoimisto</option><option value="Isännöintitoimistot">Isännöintitoimistot</option><option value="Kustannustoiminta">Kustannustoiminta</option><option value="Kauneudenhoito">Kauneudenhoito</option><option value="Kiinteistöalan palvelut">Kiinteistöalan palvelut</option><option value="Kiinteistövälitys ja -vuokraus">Kiinteistövälitys ja -vuokraus</option><option value="Kiinteistösijoitus">Kiinteistösijoitus</option><option value="Koneiden, laitteiden, tavaroiden vuokraus">Koneiden, laitteiden, tavaroiden vuokraus</option><option value="Konsultointi, koulutus">Konsultointi, koulutus</option><option value="Kuljetusliikkeet, taksit">Kuljetusliikkeet, taksit</option><option value="Kuntosalit ja muut liikunta-alan yritykset">Kuntosalit ja muut liikunta-alan yritykset</option><option value="Lasten päivä- ja iltapäiväkerhot">Lasten päivä- ja iltapäiväkerhot</option><option value="LVI- asennus, huolto, suunnittelu">LVI- asennus, huolto, suunnittelu</option><option value="Maarakennus ja suunnittelu">Maarakennus ja suunnittelu</option><option value="Matkatoimisto">Matkatoimisto</option><option value="Mainostoimisto, markkinointi">Mainostoimisto, markkinointi</option><option value="Pesulat">Pesulat</option><option value="Matkailu- ja ohjelmapalvelu">Matkailu- ja ohjelmapalvelu</option><option value="Perintäpalvelut">Perintäpalvelut</option><option value="Parturi, kampaamo">Parturi, kampaamo</option><option value="Rakentaminen">Rakentaminen</option><option value="Raskaan ajoneuvokaluston korjaus- ja huolto">Raskaan ajoneuvokaluston korjaus- ja huolto</option><option value="Siivous, puhtaanapito">Siivous, puhtaanapito</option><option value="Sisustussuunnitelu">Sisustussuunnitelu</option><option value="Sähköasennus, -huolto, -suunnittelu">Sähköasennus, -huolto, -suunnittelu</option><option value="Terveydenhoito, sosiaaliala">Terveydenhoito, sosiaaliala</option><option value="Tili-, laki-, konsultointitoimistot">Tili-, laki-, konsultointitoimistot</option><option value="Ympäristönhuolto">Ympäristönhuolto</option><option value="Vartiointi ja turvallisuuspalvelut">Vartiointi ja turvallisuuspalvelut</option><option value="Muu palvelualan yritys">Muu palvelualan yritys</option><option value="Ravitsemus- ja majoitusala">Ravitsemus- ja majoitusala</option><option value="Elintarvikkeiden myynti, tuonti, vienti">Elintarvikkeiden myynti, tuonti, vienti</option><option value="Kioskit, grillit">Kioskit, grillit</option><option value="Majoitusyritys, hotellit">Majoitusyritys, hotellit</option><option value="Pitopalvelu, liikuva elintarvikemyynti">Pitopalvelu, liikuva elintarvikemyynti</option><option value="Ravintolat, kahvilat">Ravintolat, kahvilat</option><option value="Teollisuusyritykset, tuotteiden valmistus">Teollisuusyritykset, tuotteiden valmistus</option><option value="Ajoneuvojen valmistus">Ajoneuvojen valmistus</option><option value="Elektroniikkateollisuus">Elektroniikkateollisuus</option><option value="Elintarviketeollisuus">Elintarviketeollisuus</option><option value="Energiatuotteiden valmistus">Energiatuotteiden valmistus</option><option value="Huonekaluteollisuus">Huonekaluteollisuus</option><option value="Keittiö- ja saniteettitilojen kalusteiden valmistus">Keittiö- ja saniteettitilojen kalusteiden valmistus</option><option value="Kemikaaliteollisuus">Kemikaaliteollisuus</option><option value="Kivi- ja mineraaliteollisuus">Kivi- ja mineraaliteollisuus</option><option value="Koneiden ja laitteiden valmistus">Koneiden ja laitteiden valmistus</option><option value="Konepaja">Konepaja</option><option value="Kumi- ja muovituotteiden valmistus">Kumi- ja muovituotteiden valmistus</option><option value="Lasiteollisuus">Lasiteollisuus</option><option value="Leipomot">Leipomot</option><option value="Maa-, metsä- ja riistatalous">Maa-, metsä- ja riistatalous</option><option value="Metallituotteiden valmistus">Metallituotteiden valmistus</option><option value="Nahkatuotteiden valmistus">Nahkatuotteiden valmistus</option><option value="Pakkausteollisuus">Pakkausteollisuus</option><option value="Paino- tai paperituotteiden valmistus">Paino- tai paperituotteiden valmistus</option><option value="Pakkausteollisuus">Pakkausteollisuus</option><option value="Pintakäsittely">Pintakäsittely</option><option value="Puutarha">Puutarha</option><option value="Puutavaran ja puutuotteiden valmistus">Puutavaran ja puutuotteiden valmistus</option><option value="Sähköisten ja optisten laitteiden valmistus">Sähköisten ja optisten laitteiden valmistus</option><option value="Turvetuotanto, turvealueet">Turvetuotanto, turvealueet</option><option value="Tekstiileiden tai vaatteiden valmistus">Tekstiileiden tai vaatteiden valmistus</option><option value="Muu valmistavateollisuus">Muu valmistavateollisuus</option><option value="Vähittäis- ja tukkukaupat, maahantuojat, edustukset">Vähittäis- ja tukkukaupat, maahantuojat, edustukset</option><option value="Urheilu- ja ulkoiluvälineet, polkupyöräliikkeet">Urheilu- ja ulkoiluvälineet, polkupyöräliikkeet</option><option value="Kirja-, konttoritarvike- ja paperikaupat">Kirja-, konttoritarvike- ja paperikaupat</option><option value="Puutarhat, maatilat">Puutarhat, maatilat</option><option value="Taide, askartelu, kehystämöt">Taide, askartelu, kehystämöt</option><option value="Lemmikkieläinkaupat">Lemmikkieläinkaupat</option><option value="Kirpputorit, antikat, käytetyn tavaran kauppa">Kirpputorit, antikat, käytetyn tavaran kauppa</option><option value="Agentuuriliike">Agentuuriliike</option><option value="Ajoneuvojen myynti, huolto, varaosat">Ajoneuvojen myynti, huolto, varaosat</option><option value="ATK-laitteiden myynti tai tuonti">ATK-laitteiden myynti tai tuonti</option><option value="Huonekalu- ja sisustusliikkeet">Huonekalu- ja sisustusliikkeet</option><option value="Huoltoasema, polttoaineiden myynti">Huoltoasema, polttoaineiden myynti</option><option value="Kodinkoneiden myynti ja huolto">Kodinkoneiden myynti ja huolto</option><option value="Keittiö- ja saniteettitilojen kalusteiden vähittäiskauppa">Keittiö- ja saniteettitilojen kalusteiden vähittäiskauppa</option><option value="Kelloliike">Kelloliike</option><option value="Kukkakaupat, kukkakioskit">Kukkakaupat, kukkakioskit</option><option value="Kultasepänliike">Kultasepänliike</option><option value="Maahantuonti">Maahantuonti</option><option value="Terveys-, hyvinvointi ja luontaistuoteliikkeet">Terveys-, hyvinvointi ja luontaistuoteliikkeet</option><option value="Painotuotteiden myynti ja kustannus">Painotuotteiden myynti ja kustannus</option><option value="Pienkoneiden myynti ja huolto">Pienkoneiden myynti ja huolto</option><option value="Sisustustavaraliike, -rakentaminen">Sisustustavaraliike, -rakentaminen</option><option value="Tekninen kauppa">Tekninen kauppa</option><option value="Vaatteiden, tekstiilien myynti">Vaatteiden, tekstiilien myynti</option><option value="Verkkokauppa">Verkkokauppa</option><option value="Muut tukku- ja vähittäiskaupat">Muut tukku- ja vähittäiskaupat</option><option value="Muut yritykset">Muut yritykset</option><option value="Valmisyhtiö / Pöytälaatikkoyhtiö">Valmisyhtiö / Pöytälaatikkoyhtiö</option><option value="Muu toimiala">Muu toimiala</option></select>
                                </div>
                <div class="space-y-2 grow"><label
                                class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                                for="sijainti">Valitse sijainti</label>
                                <select name="sijainti" id="sijainti" class="flex h-10 w-full items-center justify-between rounded-md border border-input bg-transparent px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50" >
                                <option value="">Kaikki</option>
                                <option value="Koko Suomi">Koko Suomi</option><option value="Ahvenanmaa">Ahvenanmaa</option><option value="Etelä-Suomi">Etelä-Suomi</option><option value="Etelä-Karjala">Etelä-Karjala</option><option value="Itä-Uusimaa">Itä-Uusimaa</option><option value="Kanta-Häme">Kanta-Häme</option><option value="Kymenlaakso">Kymenlaakso</option><option value="Päijät-Häme">Päijät-Häme</option><option value="Uusimaa">Uusimaa</option><option value="Uusimaa – Pääkaupunkiseutu">Uusimaa – Pääkaupunkiseutu</option><option value="Itä-Suomi">Itä-Suomi</option><option value="Etelä-Savo">Etelä-Savo</option><option value="Pohjois-Karjala">Pohjois-Karjala</option><option value="Pohjois-Savo">Pohjois-Savo</option><option value="Länsi-Suomi">Länsi-Suomi</option><option value="Etelä-Pohjanmaa">Etelä-Pohjanmaa</option><option value="Keski-Pohjanmaa">Keski-Pohjanmaa</option><option value="Keski-Suomi">Keski-Suomi</option><option value="Pirkanmaa">Pirkanmaa</option><option value="Pohjanmaa">Pohjanmaa</option><option value="Satakunta">Satakunta</option><option value="Varsinais-Suomi">Varsinais-Suomi</option><option value="Lappi">Lappi</option><option value="Oulu">Oulu</option><option value="Kainuu">Kainuu</option><option value="Pohjois-Pohjanmaa">Pohjois-Pohjanmaa</option><option value="Espanja">Espanja</option><option value="Costa del Sol">Costa del Sol</option><option value="Viro">Viro</option><option value="Tallinna">Tallinna</option><option value="Ruotsi">Ruotsi</option>
                                </select>
                                </div>-->
               
                                </div>
        <div class="flex flex-row gap-3 items-center justify-center px-6 py-4" style="width:10%;"> 
        <button
                        class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 py-2 grow">Hae</button>
                        
       </div> 
</div></form>';
        $html .= '<div id="content">';
        $html .= '<div class="flex flex-col gap-6 w-full">';
    foreach($data as $key=>$val){
        $html .= '<a href="'.home_url('/' . 'kaikki-ilmoitukset-yksityiskohta?form_id=').$val['form_id'].'">
        <div
                class="flex flex-col md:flex-row gap-6 p-6 border border-gray-200 rounded-lg w-full hover:bg-gray-50 hover:border-gray-300">
               
                <div class="space-y-6">
                        <div>
                                <h3 class="font-semibold text-primary">'.$val['ilmoituksen-otsikko'].'</h3>
                                <p class="text-foreground ">'.$val['toimiala'].'
                                </p>
                                <p class="text-foreground ">'.$val['sijainti'].'</p>
                                <p class="text-foreground ">'.$val['hintapyynt'].'</p>
                        </div>
                         
                </div>
        </div>
</a>';

        
    }
    $html .= '</div>';
    $html .= '</div>';
//echo '<pre>';print_r($data);
    return $html;
}

add_shortcode("latestfeedsdetail", "callback_latest_feeds_detail");
function callback_latest_feeds_detail($atts)
{
 
    $data = array();
    global $wpdb;
    $cfdb         = apply_filters('cfdb7_database', $wpdb);
    $id       = empty($_REQUEST['form_id']) ? false : esc_sql($_REQUEST['form_id']);
  
    $table_name   = $wpdb->prefix . 'db7_forms';
    $page         = 1;
    $page         = $page - 1;
    $start        = $page * 100;
    //$form_post_id = 1758;

    $orderby = isset($_GET['orderby']) ? 'form_date' : 'form_id';
    $order   = isset($_GET['order']) && $_GET['order'] == 'asc' ? 'ASC' : 'DESC';

    
        $results = $cfdb->get_results("SELECT * FROM $table_name 
        WHERE 
         form_id = '$id'", OBJECT
        );
    //echo '<pre>';print_r ($results);
    $column_results       = $cfdb->get_results( "
            SELECT * FROM $table_name 
            WHERE form_id = $id ORDER BY form_id DESC LIMIT 1", OBJECT 
        );

        $first_row            = isset($results[0]) ? unserialize( $results[0]->form_value ): 0 ;
        $columns              = array();
        $rm_underscore        = apply_filters('remove_underscore_data', true); 

        if( !empty($first_row) ){
            //$columns['form_id'] = $results[0]->form_id;
            $columns['cb']      = '<input type="checkbox" />';
            $column_titles = array();
            foreach ($first_row as $key => $value) {

                $matches = array();
                $key     = esc_html( $key );

                if ( $key == 'cfdb7_status' ) continue;

                if( $rm_underscore ) preg_match('/^_.*$/m', $key, $matches);
                if( ! empty($matches[0]) ) continue;

                $key_val       = str_replace( array('your-', 'cfdb7_file'), '', $key);
                $key_val       = str_replace( array('_', '-'), ' ', $key_val);
                $columns[$key] = ucwords( $key_val );
                
                $column_titles[] = $key_val;

                if ( sizeof($columns) > 4) break;
            }
            $columns['form-date'] = 'Date';
            
            
        }

        foreach ($results as $result) {

            $form_value = unserialize($result->form_value);
    
            $link = "<b><a href=admin.php?page=cfdb7-list.php&fid=%s&ufid=%s>%s</a></b>";
            if (isset($form_value['cfdb7_status']) && ($form_value['cfdb7_status'] === 'read')) {
                $link = "<a href=admin.php?page=cfdb7-list.php&fid=%s&ufid=%s>%s</a>";
            }
    
            $fid                    = $result->form_post_id;
            $form_values['form_id'] = $result->form_id;
    
            foreach ($column_titles as $col_title) {
                $form_value[$col_title] = isset($form_value[$col_title]) ?
                    $form_value[$col_title] : '';
            }
    
            foreach ($form_value as $k => $value) {
    
                $ktmp = $k;
    
                $can_foreach = is_array($value) || is_object($value);
    
                if ($can_foreach) {
    
                    foreach ($value as $k_val => $val) {
                        $val                = esc_html($val);
                        $form_values[$ktmp] = (strlen($val) > 150) ? substr($val, 0, 150) . '...' : $val;
                        $form_values[$ktmp] = sprintf($link, $fid, $result->form_id, $form_values[$ktmp]);
                    }
                } else {
                    $value = esc_html($value);
                    $form_values[$ktmp] = (strlen($value) > 150) ? substr($value, 0, 150) . '...' : $value;
                    $form_values[$ktmp] = sprintf($link, $fid, $result->form_id, $form_values[$ktmp]);
                }
            }
            $form_values['form-date'] = sprintf($link, $fid, $result->form_id, $result->form_date);
            $data[] = $form_values;
        }
        $html = '';
     
        $html .= '<div class="flex flex-col gap-6 w-full">';
        foreach($data as $key=>$val){
            $html .= '<div class="space-y-12 text-start gap-12 w-full"><a class="flex flex-row gap-2" href="'.home_url('/' . 'kaikki-ilmoitukset').'"><span>←</span>Takaisin
            listaan</a>
        <h1 class="text-3xl md:text-5xl text-foreground text-left font-bold tracking-tight undefined">'.$val['ilmoituksen-otsikko'].'</h1>
        <div class="flex flex-row rounded-md border">
            <ul class="flex flex-col w-full md:w-full border-r border-r p-6">
                <li class="grid grid-cols-2 "><span>Toimiala: </span>
                    <p class="font-bold">'.$val['toimiala'].'</p>
                </li>
                <li class="grid grid-cols-2"><span>Sijainti:</span>
                    <p class="font-bold">'.$val['sijainti'].'</p>
                </li>';
                if(isset($val['hintapyynt']) && $val['hintapyynt'] !=''){
                    $html .= '<li class="grid grid-cols-2"><span>Hintapyyntö:</span>
                        <p class="font-bold">'.$val['hintapyynt'].'</p>
                    </li>';
                }
                if(isset($val['liikevaihto']) && $val['liikevaihto'] !=''){
                $html .= '<li class="grid grid-cols-2"><span>Liikevaihto:</span>
                    <p class="font-bold">'.$val['liikevaihto'].'</p>
                </li>';
                }
                if(isset($val['liikevaihtoennuste']) && $val['liikevaihtoennuste'] !=''){
                $html .= '<li class="grid grid-cols-2"><span>Liikevaihtoennuste:</span>
                    <p class="font-bold">'.$val['liikevaihtoennuste'].'</p>
                </li>';
                }
                if(isset($val['myyntikate']) && $val['myyntikate'] !=''){
                $html .= '<li class="grid grid-cols-2"><span>Myyntikate:</span>
                    <p class="font-bold">'.$val['myyntikate'].'</p>
                </li>';
                }
                if(isset($val['Kayttokate']) && $val['Kayttokate'] !=''){
                $html .= '<li class="grid grid-cols-2"><span>Kayttokate:</span>
                    <p class="font-bold">'.$val['Kayttokate'].'</p>
                </li>';
                } 
                
                if(isset($val['taseenloppusumma']) && $val['taseenloppusumma'] !=''){
                $html .= '<li class="grid grid-cols-2"><span>Taseen loppusumma:</span>
                    <p class="font-bold">'.$val['taseenloppusumma'].'</p>
                </li>';
                }
                
                if(isset($val['ennaperintarekisterointi']) && $val['ennaperintarekisterointi'] !=''){
                $html .= '<li class="grid grid-cols-2"><span>Ennaperintarekisterointi:</span>
                    <p class="font-bold">'.$val['ennaperintarekisterointi'].'</p>
                </li>';
                }
                
                if(isset($val['alv-rekisterointi']) && $val['alv-rekisterointi'] !=''){
                $html .= '<li class="grid grid-cols-2"><span>Alv Rekisterointi:</span>
                    <p class="font-bold">'.$val['alv-rekisterointi'].'</p>
                </li>';
                }
                
                if(isset($val['tyonantajareksiterointi']) && $val['tyonantajareksiterointi'] !=''){
                $html .= '<li class="grid grid-cols-2"><span>Tyonantajareksiterointi:</span>
                    <p class="font-bold">'.$val['tyonantajareksiterointi'].'</p>
                </li>';
                }
                
                if(isset($val['pankkitili']) && $val['pankkitili'] !=''){
                $html .= '<li class="grid grid-cols-2"><span>Pankkitili:</span>
                    <p class="font-bold">'.$val['pankkitili'].'</p>
                </li>';
                }
                
                if(isset($val['uhat']) && $val['uhat'] !=''){
                $html .= '<li class="grid grid-cols-2"><span>Uhat:</span>
                    <p class="font-bold">'.$val['uhat'].'</p>
                </li>';
                }
                
                if(isset($val['mahdollisuudet']) && $val['mahdollisuudet'] !=''){
                $html .= '<li class="grid grid-cols-2"><span>Mahdollisuudet:</span>
                    <p class="font-bold">'.$val['mahdollisuudet'].'</p>
                </li>';
                }
                
                if(isset($val['vahvuudet']) && $val['vahvuudet'] !=''){
                $html .= '<li class="grid grid-cols-2"><span>Vahvuudet:</span>
                    <p class="font-bold">'.$val['vahvuudet'].'</p>
                </li>';
                }
                
                if(isset($val['heikkoudet']) && $val['heikkoudet'] !=''){
                $html .= '<li class="grid grid-cols-2"><span>Heikkoudet:</span>
                    <p class="font-bold">'.$val['heikkoudet'].'</p>
                </li>';
                }
                
                if(isset($val['taman-hetken']) && $val['taman-hetken'] !=''){
                $html .= '<li class="grid grid-cols-2"><span>Taman Hetken:</span>
                    <p class="font-bold">'.$val['taman-hetken'].'</p>
                </li>';
                }
                
                if(isset($val['asiakastyypit']) && $val['asiakastyypit'] !=''){
                $html .= '<li class="grid grid-cols-2"><span>Asiakastyypit:</span>
                    <p class="font-bold">'.$val['asiakastyypit'].'</p>
                </li>';
                }
                
                if(isset($val['asiakasmaara']) && $val['asiakasmaara'] !=''){
                $html .= '<li class="grid grid-cols-2"><span>Asiakastyypit:</span>
                    <p class="font-bold">'.$val['asiakasmaara'].'</p>
                </li>';
                }
                
                if(isset($val['asiakastyypit']) && $val['asiakastyypit'] !=''){
                $html .= '<li class="grid grid-cols-2"><span>Asiakasmaara:</span>
                    <p class="font-bold">'.$val['asiakastyypit'].'</p>
                </li>';
                }
                
                if(isset($val['tarjouspyyntojen-maara']) && $val['tarjouspyyntojen-maara'] !=''){
                $html .= '<li class="grid grid-cols-2"><span>Tarjouspyyntojen Maara:</span>
                    <p class="font-bold">'.$val['tarjouspyyntojen-maara'].'</p>
                </li>';
                }
                
                if(isset($val['yrittajan-rooli-ja-osaaminen']) && $val['yrittajan-rooli-ja-osaaminen'] !=''){
                $html .= '<li class="grid grid-cols-2"><span>Yrittajan Rooli Ja Osaaminen:</span>
                    <p class="font-bold">'.$val['yrittajan-rooli-ja-osaaminen'].'</p>
                </li>';
                }
                
                if(isset($val['Henkilokunnan-maara']) && $val['Henkilokunnan-maara'] !=''){
                $html .= '<li class="grid grid-cols-2"><span>Henkilokunnan Maara:</span>
                    <p class="font-bold">'.$val['Henkilokunnan-maara'].'</p>
                </li>';
                }
                
                if(isset($val['mita-tavoittelet']) && $val['mita-tavoittelet'] !=''){
                $html .= '<li class="grid grid-cols-2"><span>Mita Tavoittelet:</span>
                    <p class="font-bold">'.$val['mita-tavoittelet'].'</p>
                </li>';
                }
                
                if(isset($val['henkilokunnanmaara']) && $val['henkilokunnanmaara'] !=''){
                $html .= '<li class="grid grid-cols-2"><span>Henkilökunnan määrä:</span>
                    <p class="font-bold">'.$val['henkilokunnanmaara'].'</p>
                </li>';
                }
                
                if(isset($val['millainen-yritys-on-kyseessa']) && $val['millainen-yritys-on-kyseessa'] !=''){
                $html .= '<li class="grid grid-cols-2"><span>Millainen Yritys On Kyseessa:</span>
                    <p class="font-bold">'.$val['millainen-yritys-on-kyseessa'].'</p>
                </li>';
                }
                if(isset($val['millaista-yritysta-haet']) && $val['millaista-yritysta-haet'] !=''){
                $html .= '<li class="grid grid-cols-2"><span>Millaista yritystä haet:</span>
                    <p class="font-bold">'.$val['millaista-yritysta-haet'].'</p>
                </li>';
                }
                 
                $html .= '</ul>
        </div> </div> ';
    
            
        } 
        $html .= '</div>';  
     

    return  $html;
 
    die();
}
  ?>