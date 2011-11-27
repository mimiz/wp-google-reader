<?php
class WidgetGoogleReader extends WP_Widget
{
	private $_GrSharedOptions = array('shared', 'starred', 'tag', 'read', 'reading-list');
	
	
	/**
	 * Widget setup.
	 */
	function WidgetGoogleReader() {
		
//		/* Widget settings. */
		$widget_ops = array( 'classname' => 'WidgetGoogleReader', 'description' => __('Add your Google Reader items to your blog', 'WidgetGoogleReader') );
		parent::WP_Widget(false, $name = 'Google Reader', $widget_ops  );	
	}
	
	function form( $instance ) {
//		var_dump($instance);
//		exit;
		/* Set up some default widget settings. */
		$defaults = array(
			'title' => 'Google Reader',
			'cache' => 0,
			'type' => 'shared',
			'nbelement' => 5,
			'tag' => ''
          );
		$instance = wp_parse_args( (array) $instance, $defaults ); 
		include('form.php');
	}
	
	/**
	 * Display the widget on the screen.
	 */
	function widget( $args, $instance ) {
		if (!defined('WP-GRCORE_LIBRARY')) {
			_phpversion_google_reader_core_library('Error : library Core not loaded');
			
		}
		try{
			$this->_init($instance);
			if($instance["cache"] == 1 &&  get_option('googlereadercachedir') != ''  &&  get_option('googlereadercachelifetime') != '')
			{
				$cachename = 'googlereader_'.md5($instance['type'].'_'.$instance['nbelement'].'_'.$instance['title'].'_'.$instance['tag']);
				if( ( $feeds = $this->_cache->load($cachename)) === false)
				{
					$feeds = $this->_gr->import($instance['type'], $instance['tag']);
					$this->_cache->save($feeds, $cachename);
				}
			}else{
				$feeds = $this->_gr->import($instance['type'], $instance['tag']);
			}
			
			
			echo $args['before_widget'];
		    echo $args['before_title'] . $instance['title'] . $args['after_title'];
		    echo "<ul>";
			foreach($feeds as $feed)
			{
				if($feed->link('alternate') == '')
				{
					// If link is empty i don't create the link :)
					echo '<li>' .  $feed->title() . '</li>';
				}else{
					echo '<li><a target="_blank" href="'. $feed->link('alternate') .'">' .  $feed->title() . '</a></li>';
				}
			}  
			echo '</ul>';
		    echo $args['after_widget'];
		}catch(Exception $e)
		{
			echo $args['before_widget'];
		    echo $args['before_title'] . $instance['title'] . $args['after_title'];
		    echo "<p>";
			echo __('An Error Occured please check your settings', 'google-reader');
			echo '</p>';
		    echo $args['after_widget'];
		}
	}
	
	/**
	 * Update the widget settings.
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		/* Strip HTML tags for the following: */
		$instance['title'] = strip_tags( $new_instance['title'] );
		if(isset($new_instance['cache'])){
			$instance['cache'] = 1;
		}else{
			$instance['cache'] = 0;
		}
		$instance['type'] = strip_tags( $new_instance['type'] );
		$instance['nbelement'] = intval($new_instance['nbelement']);
		$instance['tag'] = strip_tags( $new_instance['tag'] );
		return $instance;
	}
	
	public function _init($instance)
	{
	
		if($instance['cache'] == 1 &&  get_option('googlereadercachedir') != ''  &&  get_option('googlereadercachelifetime') != '' )
		{
			$frontendOptions = array(
			   'lifetime' => get_option('googleredercachelifetime'), // cache lifetime of 2 hours
			   'automatic_serialization' => true
			);
			 
			$backendOptions = array(
			    'cache_dir' => get_option('googlereadercachedir') // Directory where to put the cache files
			);
			// getting a Zend_Cache_Core object
			$this->_cache = Zend_Cache::factory('Core',
			                             'File',
			                             $frontendOptions,
			                             $backendOptions);
		}
		$this->_gr = new Core_Gdata_Reader(get_option('googlereaderlogin'), get_option('googlereaderpassword'), array('n'=>5));
		
		$this->_baseUrl = "http://www.google.com/reader/atom/user/-";
	}
	
	/**
	 * 
	 * @var Core_Gdata_Reader
	 */
	private $_gr;
	private $_baseUrl;
	/**
	 * @var Zend_Cache_Core
	 */
	private $_cache;
		
}