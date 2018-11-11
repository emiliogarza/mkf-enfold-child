<?php
/**
 * Info Circle
 * Shortcode that allows to display a bunch of circles and stuff
 * Based on Accordion Slider, slideshow_accordion.php but a different presentation.
 */

if ( !class_exists( 'avia_sc_info_circles' ) )
{
	class avia_sc_slider_accordion extends aviaShortcodeTemplate
	{
			static $slide_count = 0;

			/**
			 * Create the config array for the shortcode button
			 */
			function shortcode_insert_button()
			{
				$this->config['name']			= __('Info Circles', 'avia_framework' );
				$this->config['tab']			= __('Media Elements', 'avia_framework' );
				$this->config['icon']			= AviaBuilder::$path['imagesURL']."sc-fullscreen.png";
				$this->config['order']			= 20;
				$this->config['target']			= 'avia-target-insert';
				$this->config['shortcode'] 		= 'av_info_circles';
				$this->config['shortcode_nested'] = array('av_circle');
				$this->config['tooltip'] 	    = __('Display an info circle component', 'avia_framework' );
				$this->config['drag-level'] 	= 3;
				$this->config['preview'] 		= false;
			}

			/**
			 * Popup Elements
			 *
			 * If this function is defined in a child class the element automatically gets an edit button, that, when pressed
			 * opens a modal window that allows to edit the element properties
			 *
			 * @return void
			 */
			function popup_elements()
			{
				$this->elements = array(

					array(
							"type" 	=> "tab_container", 'nodescription' => true
						),

					array(
							"type" 	=> "tab",
							"name"  => __("Slide Content" , 'avia_framework'),
							'nodescription' => true
						),

					array(
							"type" 			=> "modal_group",
							"id" 			=> "content",
							'container_class' =>"avia-element-fullwidth avia-multi-img",
							"modal_title" 	=> __("Edit Form Element", 'avia_framework' ),
							"add_label"		=>  __("Add single image", 'avia_framework' ),
							"std"			=> array(),
							'creator'		=>array(

										"name" => __("Add Images", 'avia_framework' ),
										"desc" => __("Here you can add new Images to the info circle slideshow.", 'avia_framework' ),
										"id" 	=> "id",
										"type" 	=> "multi_image",
										"title" => __("Add multiple Images",'avia_framework' ),
										"button" => __("Insert Images",'avia_framework' ),
										"std" 	=> ""
										),

							'subelements' 	=> array(

									array(
									"name" 	=> __("Choose another Image",'avia_framework' ),
									"desc" 	=> __("Either upload a new, or choose an existing image from your media library",'avia_framework' ),
									"id" 	=> "id",
									"fetch" => "id",
									"type" 	=> "image",
									"title" => __("Change Image",'avia_framework' ),
									"button" => __("Change Image",'avia_framework' ),
									"std" 	=> ""),

									array(
										"name" 	=> __("Caption Title Font Size", 'avia_framework' ),
										"desc" 	=> __("Select a custom font size. Leave empty to use the default", 'avia_framework' ),
										"id" 	=> "custom_title_size",
										"type" 	=> "select",
										"required"=> array('title','not','inactive'),
										"std" 	=> "",
										"subtype" => AviaHtmlHelper::number_array(10,40,1, array( __("Default Size", 'avia_framework' )=>''), 'px'),
									),

									 array(
									"name" 	=> __("Caption Text", 'avia_framework' ),
									"desc" 	=> __("Enter some additional caption text", 'avia_framework' ) ,
									"id" 	=> "content",
									"type" 	=> "textarea",
									"std" 	=> "",
									),
									array(
										"name" 	=> __("Image Link?", 'avia_framework' ),
										"desc" 	=> __("Where should the Image link to?", 'avia_framework' ),
										"id" 	=> "link",
										"type" 	=> "linkpicker",
										"fetchTMPL"	=> true,
										"subtype" => array(
															__('Open Image in Lightbox', 'avia_framework' ) =>'lightbox',
															__('Set Manually', 'avia_framework' ) =>'manually',
															__('Single Entry', 'avia_framework' ) => 'single',
															__('Taxonomy Overview Page',  'avia_framework' ) => 'taxonomy',
															),
										"std" 	=> ""),

										array(
										"name" 	=> __("Open Link in new Window?", 'avia_framework' ),
										"desc" 	=> __("Select here if you want to open the linked page in a new window", 'avia_framework' ),
										"id" 	=> "link_target",
										"type" 	=> "select",
										"std" 	=> "",
										"required"=> array('link','not_empty_and','lightbox'),
										"subtype" => AviaHtmlHelper::linking_options()),

								)

					),
					array(
						"name" 	=> __("Font Color", 'avia_framework' ),
						"desc" 	=> __("Select a color for the text in the center-circle.", 'avia_framework' ),
						"id" 	=> "fontColor",
						"type" 	=> "colorpicker",
						"rgba" 	=> false,
						"std" 	=> "#444444"
					),

					array(
						"name" 	=> __("Color", 'avia_framework' ),
						"desc" 	=> __("Select a color for the center-circle background and highlight color", 'avia_framework' ),
						"id" 	=> "color",
						"type" 	=> "colorpicker",
						"rgba" 	=> false,
						"std" 	=> "#444444"
					),

					array(
						"name" 	=> __("Overlay Opacity",'avia_framework' ),
						"desc" 	=> __("Set the opacity of your overlay: 0.1 is barely visible, 1.0 is opaque ", 'avia_framework' ),
						"id" 	=> "overlay_opacity",
						"type" 	=> "select",
						"std" 	=> "0.5",
						"subtype" => array(   __('0.1','avia_framework' )=>'0.1',
											  __('0.2','avia_framework' )=>'0.2',
											  __('0.3','avia_framework' )=>'0.3',
											  __('0.4','avia_framework' )=>'0.4',
											  __('0.5','avia_framework' )=>'0.5',
											  __('0.6','avia_framework' )=>'0.6',
											  __('0.7','avia_framework' )=>'0.7',
											  __('0.8','avia_framework' )=>'0.8',
											  __('0.9','avia_framework' )=>'0.9',
											  __('1.0','avia_framework' )=>'1',
											  )
						  ),

					array(
						"name" 	=> __("Autorotation active?",'avia_framework' ),
						"desc" 	=> __("Check if the info circle slideshow should rotate by default",'avia_framework' ),
						"id" 	=> "autoplay",
						"type" 	=> "select",
						"std" 	=> "true",
						"subtype" => array(__('Yes','avia_framework' ) =>'true',__('No','avia_framework' ) =>'false')),

					array(
						"name" 	=> __("Slideshow autorotation duration",'avia_framework' ),
						"desc" 	=> __("Images will be shown the selected amount of seconds.",'avia_framework' ),
						"id" 	=> "interval",
						"type" 	=> "select",
						"std" 	=> "5",
						"required"=> array('autoplay','contains','true'),
						"subtype" =>
						array('3'=>'3','4'=>'4','5'=>'5','6'=>'6','7'=>'7','8'=>'8','9'=>'9','10'=>'10','15'=>'15','20'=>'20','30'=>'30','40'=>'40','60'=>'60','100'=>'100')),

					array(
							"type" 	=> "close_div",
							'nodescription' => true
						),



					array(
									"type" 	=> "tab",
									"name"	=> __("Screen Options",'avia_framework' ),
									'nodescription' => true
								),


								array(
								"name" 	=> __("Element Visibility",'avia_framework' ),
								"desc" 	=> __("Set the visibility for this element, based on the device screensize.", 'avia_framework' ),
								"type" 	=> "heading",
								"description_class" => "av-builder-note av-neutral",
								),

								array(
										"desc" 	=> __("Hide on large screens (wider than 990px - eg: Desktop)", 'avia_framework'),
										"id" 	=> "av-desktop-hide",
										"std" 	=> "",
										"container_class" => 'av-multi-checkbox',
										"type" 	=> "checkbox"),

								array(

										"desc" 	=> __("Hide on medium sized screens (between 768px and 989px - eg: Tablet Landscape)", 'avia_framework'),
										"id" 	=> "av-medium-hide",
										"std" 	=> "",
										"container_class" => 'av-multi-checkbox',
										"type" 	=> "checkbox"),

								array(

										"desc" 	=> __("Hide on small screens (between 480px and 767px - eg: Tablet Portrait)", 'avia_framework'),
										"id" 	=> "av-small-hide",
										"std" 	=> "",
										"container_class" => 'av-multi-checkbox',
										"type" 	=> "checkbox"),

								array(

										"desc" 	=> __("Hide on very small screens (smaller than 479px - eg: Smartphone Portrait)", 'avia_framework'),
										"id" 	=> "av-mini-hide",
										"std" 	=> "",
										"container_class" => 'av-multi-checkbox',
										"type" 	=> "checkbox"),


									array(	"name" 	=> __("Font Size for medium sized screens", 'avia_framework' ),
						            "id" 	=> "av-medium-font-size-title",
						            "type" 	=> "select",
						            "subtype" => AviaHtmlHelper::number_array(10,120,1, array( __("Default", 'avia_framework' )=>'' , __("Hidden", 'avia_framework' )=>'hidden' ), "px"),
						            "std" => ""),

						            array(	"name" 	=> __("Font Size for small screens", 'avia_framework' ),
						            "id" 	=> "av-small-font-size-title",
						            "type" 	=> "select",
						            "subtype" => AviaHtmlHelper::number_array(10,120,1, array( __("Default", 'avia_framework' )=>'', __("Hidden", 'avia_framework' )=>'hidden'), "px"),
						            "std" => ""),

									array(	"name" 	=> __("Font Size for very small screens", 'avia_framework' ),
						            "id" 	=> "av-mini-font-size-title",
						            "type" 	=> "select",
						            "subtype" => AviaHtmlHelper::number_array(10,120,1, array( __("Default", 'avia_framework' )=>'', __("Hidden", 'avia_framework' )=>'hidden'), "px"),
						            "std" => ""),


						        array(
									"name" 	=> __("Caption Content Font Size",'avia_framework' ),
									"desc" 	=> __("Set the font size for the element content, based on the device screensize.", 'avia_framework' ),
									"type" 	=> "heading",
									"description_class" => "av-builder-note av-neutral",
									),

									array(	"name" 	=> __("Font Size for medium sized screens", 'avia_framework' ),
						            "id" 	=> "av-medium-font-size",
						            "type" 	=> "select",
						            "subtype" => AviaHtmlHelper::number_array(10,120,1, array( __("Default", 'avia_framework' )=>'', __("Hidden", 'avia_framework' )=>'hidden'), "px"),
						            "std" => ""),

						            array(	"name" 	=> __("Font Size for small screens", 'avia_framework' ),
						            "id" 	=> "av-small-font-size",
						            "type" 	=> "select",
						            "subtype" => AviaHtmlHelper::number_array(10,120,1, array( __("Default", 'avia_framework' )=>'', __("Hidden", 'avia_framework' )=>'hidden'), "px"),
						            "std" => ""),

									array(	"name" 	=> __("Font Size for very small screens", 'avia_framework' ),
						            "id" 	=> "av-mini-font-size",
						            "type" 	=> "select",
						            "subtype" => AviaHtmlHelper::number_array(10,120,1, array( __("Default", 'avia_framework' )=>'', __("Hidden", 'avia_framework' )=>'hidden'), "px"),
						            "std" => ""),


							array(
									"type" 	=> "close_div",
									'nodescription' => true
								),
					array(
						"type" 	=> "close_div",
						'nodescription' => true
							),


				);

			}

			/**
			 * Editor Element - this function defines the visual appearance of an element on the AviaBuilder Canvas
			 * Most common usage is to define some markup in the $params['innerHtml'] which is then inserted into the drag and drop container
			 * Less often used: $params['data'] to add data attributes, $params['class'] to modify the className
			 *
			 *
			 * @param array $params this array holds the default values for $content and $args.
			 * @return $params the return array usually holds an innerHtml key that holds item specific markup.
			 */
			function editor_element($params)
			{
				$params['innerHtml'] = "<img src='".$this->config['icon']."' title='".$this->config['name']."' />";
				$params['innerHtml'].= "<div class='avia-element-label'>".$this->config['name']."</div>";

				return $params;
			}




			/**
			 * Editor Sub Element - this function defines the visual appearance of an element that is displayed within a modal window and on click opens its own modal window
			 * Works in the same way as Editor Element
			 * @param array $params this array holds the default values for $content and $args.
			 * @return $params the return array usually holds an innerHtml key that holds item specific markup.
			 */
			function editor_sub_element($params)
			{
				$img_template 		= $this->update_template("img_fakeArg", "{{img_fakeArg}}");
				$template 			= $this->update_template("title", "{{title}}");
				$content 			= $this->update_template("content", "{{content}}");
				$thumbnail = isset($params['args']['id']) ? wp_get_attachment_image($params['args']['id']) : "";


				$params['innerHtml']  = "";
				$params['innerHtml'] .= "<div class='avia_title_container'>";
				$params['innerHtml'] .= "		<span class='avia_slideshow_image' {$img_template} >{$thumbnail}</span>";
				$params['innerHtml'] .= "		<div class='avia_slideshow_content'>";
				$params['innerHtml'] .= "			<h4 class='avia_title_container_inner' {$template} >".$params['args']['title']."</h4>";
				$params['innerHtml'] .= "			<p class='avia_content_container' {$content}>".stripslashes($params['content'])."</p>";
				$params['innerHtml'] .= "		</div>";
				$params['innerHtml'] .= "</div>";



				return $params;
			}




			/**
			 * Frontend Shortcode Handler
			 *
			 * @param array $atts array of attributes
			 * @param string $content text within enclosing form of shortcode element
			 * @param string $shortcodename the shortcode found, when == callback name
			 * @return string $output returns the modified html string
			 */
			function shortcode_handler($atts, $content = "", $shortcodename = "", $meta = "")
			{
				extract(AviaHelper::av_mobile_sizes($atts)); //return $av_font_classes, $av_title_font_classes and $av_display_classes

				$atts = shortcode_atts(array(
				'link'			=> '',
				'wc_prod_visible'	=>	'',
				'prod_order_by'		=>	'',
				'prod_order'		=>	'',
				'size'			=> '',
				'items'    	 	=> '',
				'autoplay'		=> 'false',
				'title'			=> 'active',
				'excerpt'		=> '',
				'interval'		=> 5,
				'offset'		=> 0,
				'custom_title_size' => '',
				'color' => '',
				'fontColor' => '',
				'overlay_opacity' => '',
				'custom_excerpt_size' => '',
				'accordion_align'	=> '',

				'av-desktop-hide'	=>'',
				'av-medium-hide'	=>'',
				'av-small-hide'		=>'',
				'av-mini-hide'		=>'',

				'av-medium-font-size-title'	=>'',
				'av-small-font-size-title'	=>'',
				'av-mini-font-size-title'	=>'',

				'av-medium-font-size'	=>'',
				'av-small-font-size'	=>'',
				'av-mini-font-size'		=>'',

				'handle'		=> $shortcodename,
				'content'		=> ShortcodeHelper::shortcode2array($content, 1)

				), $atts, $this->config['shortcode']);

				extract($atts);
				$output  	= "";
			    $class = "";


				$skipSecond = false;
				avia_sc_slider_accordion::$slide_count++;

				$params['class'] = "molecular";
				$params['open_structure'] = false;

				$params['custom_markup'] = $atts['custom_markup'] = $meta['custom_markup'];

				//we dont need a closing structure if the element is the first one or if a previous fullwidth element was displayed before
				if($meta['index'] == 0) $params['close'] = false;
				if(!empty($meta['siblings']['prev']['tag']) && in_array($meta['siblings']['prev']['tag'], AviaBuilder::$full_el_no_section )) $params['close'] = false;

				$params['id'] = "info_circle_".avia_sc_slider_full::$slide_count;

				$params['style'] = "style='--molecularColor:".$color.";--molecularCenterCircleOpacity:".$overlay_opacity.";--molecularFontColor:".$fontColor.";'";


				$slider  = new infocircle($atts);
				$slide_html = $slider->html();


				//if the element is nested within a section or a column dont create the section shortcode around it
				if(!ShortcodeHelper::is_top_level()) return $slide_html;


				$output .=  avia_new_section($params);
				$output .= 	$slide_html;
				$output .= "</div>"; //close section


				//if the next tag is a section dont create a new section from this shortcode
				if(!empty($meta['siblings']['next']['tag']) && in_array($meta['siblings']['next']['tag'],  AviaBuilder::$full_el ))
				{
				    $skipSecond = true;
				}

				//if there is no next element dont create a new section.
				if(empty($meta['siblings']['next']['tag']))
				{
				    $skipSecond = true;
				}

				if(empty($skipSecond)) {

				$output .= avia_new_section(array('close'=>false, 'id' => "after_full_slider_".avia_sc_slider_full::$slide_count));

				}

				return $output;

			}

	}
}

if ( !class_exists( 'infocircle' ) )
{
	class infocircle
	{
		static  $slider = 0; 				//slider count for the current page
		protected $config;	 				//base config set on initialization
		protected $slides = array();	 	//entries or image slides
		protected $slide_count = 0;			//number of slides
		protected $id_array = array();
		function __construct($config)
		{

			$this->screen_options = AviaHelper::av_mobile_sizes($config); //return $av_font_classes, $av_title_font_classes and $av_display_classes

			$this->config = array_merge(array(
				'link'			=> '',
				'wc_prod_visible'	=>	'',
				'prod_order_by'		=>	'',
				'prod_order'		=>	'',
				'size'			=> '',
				'items'    	 	=> '',
				'autoplay'		=> 'false',
				'interval'		=> 5,
				'offset'		=> 0,
				'title'			=> 'active',
				'excerpt'		=> '',
				'content'		=> array(),
				'custom_title_size' => '',
				'custom_excerpt_size' => '',
				'custom_markup' => '',
				'color' => '',
				'accordion_align'=> ''
				), $config);

			$this->config = apply_filters('avf_infocircle_config', $this->config);

			$this->get_image_based_slides();
		}

		function get_image_based_slides()
		{
			foreach($this->config['content'] as $key => $slide)
			{
				if(!isset($slide['attr']['link'])) $slide['attr']['link'] = "lightbox";

				$this->slides[$key] = new stdClass();
				$this->slides[$key]->post_excerpt	= $slide['content'];
				$this->slides[$key]->av_attachment	= wp_get_attachment_image( $slide['attr']['id'] , $this->config['size'] , false, array('class' => 'molecular__image'));
				$this->slides[$key]->av_permalink	= isset($slide['attr']['link']) ? AviaHelper::get_url($slide['attr']['link'], $slide['attr']['id']) : "";
				$this->slides[$key]->av_target		= empty($slide['attr']['link_target']) ? "" : "target='".$slide['attr']['link_target']."'" ;
			}
		}


		function extract_terms()
		{
			if(isset($this->config['link']))
			{
				$this->config['link'] = explode(',', $this->config['link'], 2 );
				$this->config['taxonomy'] = $this->config['link'][0];

				if(isset($this->config['link'][1]))
				{
					$this->config['categories'] = $this->config['link'][1];
				}
				else
				{
					$this->config['categories'] = array();
				}
			}
		}

		function query_entries($params = array(), $return = false)
		{
			global $avia_config;

			if(empty($params)) $params = $this->config;

			if(empty($params['custom_query']))
            {
				$query = array();

				if(!empty($params['categories']))
				{
					//get the portfolio categories
					$terms 	= explode(',', $params['categories']);
				}

				$page = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : get_query_var( 'page' );
				if(!$page || $params['paginate'] == 'no') $page = 1;

				//if we find no terms for the taxonomy fetch all taxonomy terms
				if(empty($terms[0]) || is_null($terms[0]) || $terms[0] === "null")
				{
					$terms = array();
					$allTax = get_terms( $params['taxonomy']);
					foreach($allTax as $tax)
					{
						$terms[] = $tax->term_id;
					}
				}

				if($params['offset'] == 'no_duplicates')
                {
                    $params['offset'] = 0;
                    if(empty($params['ignore_dublicate_rule'])) $no_duplicates = true;
                }

				if(empty($params['post_type'])) $params['post_type'] = get_post_types();
				if(is_string($params['post_type'])) $params['post_type'] = explode(',', $params['post_type']);

				$orderby = 'date';
				$order = 'DESC';

				// Meta query - replaced by Tax query in WC 3.0.0
				$meta_query = array();
				$tax_query = array();

				// check if taxonomy are set to product or product attributes
				$tax = get_taxonomy( $params['taxonomy'] );

				if( is_object( $tax ) && isset( $tax->object_type ) && in_array( 'product', (array) $tax->object_type ) )
				{
					$avia_config['woocommerce']['disable_sorting_options'] = true;

					avia_wc_set_out_of_stock_query_params( $meta_query, $tax_query, $params['wc_prod_visible'] );

						//	sets filter hooks !!
					$ordering_args = avia_wc_get_product_query_order_args( $params['prod_order_by'], $params['prod_order'] );

					$orderby = $ordering_args['orderby'];
					$order = $ordering_args['order'];
				}

				if( ! empty( $terms ) )
				{
					$tax_query[] =  array(
										'taxonomy' 	=>	$params['taxonomy'],
										'field' 	=>	'id',
										'terms' 	=>	$terms,
										'operator' 	=>	'IN'
								);
				}


				$query = array(	'orderby'		=>	$orderby,
								'order'			=>	$order,
								'paged'			=>	$page,
								'post_type'		=>	$params['post_type'],
//								'post_status'	=>	'publish',
								'offset'		=>	$params['offset'],
								'posts_per_page' =>	$params['items'],
								'post__not_in'	=>	( ! empty( $no_duplicates ) ) ? $avia_config['posts_on_current_page'] : array(),
								'meta_query'	=>	$meta_query,
								'tax_query'		=>	$tax_query
							);

			}
			else
			{
				$query = $params['custom_query'];
			}


			$query   = apply_filters('avf_accordion_entries_query', $query, $params);
			$result = new WP_Query( $query );
			$entries = $result->posts;

			if(!empty($entries) && empty($params['ignore_dublicate_rule']))
			{
				foreach($entries as $entry)
	            {
					 $avia_config['posts_on_current_page'][] = $entry->ID;
	            }
			}

			if( function_exists( 'WC' ) )
			{
				avia_wc_clear_catalog_ordering_args_filters();
				$avia_config['woocommerce']['disable_sorting_options'] = false;
			}

			if($return)
			{
				return $entries;
			}
			else
			{
				$this->slides = $entries;
			}
		}




		function html()
		{
			extract($this->screen_options);

			$slideCount = count($this->slides);
			$output 	= "";
			$currentDegreeCount = 0;
			$selectedItem = '';

			if($slideCount == 0) return $output;

			$diffDeg = 90 / $slideCount;
			$firstSlideDegrees = 90 + $diffDeg;


			$output .= "<div class='molecular__innercircle' style='background-image: url(".$this->slides[0]->av_permalink.");'></div>";

			foreach($this->slides as $key => $slide)
			{

				$counter  = $key + 1;
				if ($counter == 1) {
					$rotateValue = $firstSlideDegrees;
					$currentDegreeCount = $firstSlideDegrees;
					$selectedItem = ' molecular__molecule--selected';
				}
				else {
					$rotateValue = $currentDegreeCount + ($diffDeg * 2);
					$currentDegreeCount = $rotateValue;
					$selectedItem = '';
				}

				$output .= "<a href='".$slide->av_permalink."' ".$slide->av_target." class='molecular__molecule{$selectedItem}' data-url='".$slide->av_permalink."' style='--molecularRotateValue:{$rotateValue}deg;--molecularNegativeRotateValue:-{$rotateValue}deg'>";

				$output .= $slide->av_attachment;

				$output .= !empty($slide->post_excerpt) ? "<p class='molecular__paragraph'>".$slide->post_excerpt."</p>" : "";

				$output .= "</a>";

			}
			return $output;
		}
	}
}



















