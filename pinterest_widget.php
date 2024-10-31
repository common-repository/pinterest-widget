<?php
 /*
Plugin Name: Pinterest Widget
Plugin URI: https://github.com/ujw0l/Pinterest_widget
Description: Pinterest RSS Widget
Version: 3.5.0
Author: Ujwol Bastakoti
Author URI: http://ujw0l.github.io/
text-domain: pinterest-widget
License: GPLv2
*/


class pinterest_widget extends WP_Widget{
		
		public function __construct() {
			parent::__construct(
					'pinterest_widget', // Base ID
					'Pinterest  Widget', // Name
					array( 'description' => __( 'Pinterest Feed Widget', 'pinterest-widget' ), ) // Args
			);
			
		}
		
		//function to detemine default number of pin to display
		public function default_pin_count($pinCount,$currentPinCount){
		    if(isset($currentPinCount))
		    {
		        if($pinCount == $currentPinCount)
		        {
		            echo 'selected="selected"';
		        }
		    }
		}
		
		
		public function form( $instance ) {
			if ( isset( $instance[ 'title' ] ) ) {
			
					$title = $instance[ 'title' ];
				}
			else {
					$title = __( 'Pinterest Feed Widget', 'pinterest-widget' );
				}
		?>
		    <p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:','pinterest-widget' ); ?></label> 
				
				<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		    </p>
		    <p>
				<label for="<?php echo $this->get_field_id( 'pinterest_username' ); ?>"><?php _e( 'Pinterest Username:','pinterest-widget' ); ?></label> 
				
				<input class="widefat" id="<?php echo $this->get_field_id( 'pinterest_username' ); ?>" name="<?php echo $this->get_field_name( 'pinterest_username' ); ?>" type="text" value="<?php echo esc_attr( $instance['pinterest_username' ] ); ?>" />
		    </p>
		    <p>
				<label for="<?php echo $this->get_field_id( 'pinterest_pin_board' ); ?>"><?php _e( 'Pin Board:' ,'pinterest-widget'); ?></label> 
				
				<input class="widefat" id="<?php echo $this->get_field_id( 'pinterest_pin_board' ); ?>" name="<?php echo $this->get_field_name( 'pinterest_pin_board' ); ?>" type="text" value="<?php echo esc_attr( $instance['pinterest_pin_board' ] ); ?>" />
		    </p>
		    <p>
				<label for="<?php echo $this->get_field_id( 'pinterest_pin_count' ); ?>"><?php _e( 'No of pins to be displayed:','pinterest-widget' ); ?></label>
				<?php   if(1 < $instance['pinterest_pin_count' ] ) : $pinCount= esc_attr( $instance['pinterest_pin_count' ] ); else: $pinCount=  '1'; endif; ?> 
				<input class="widefat" id="<?php echo $this->get_field_id( 'pinterest_pin_count' ); ?>" name="<?php echo $this->get_field_name( 'pinterest_pin_count' ); ?>" type="number" min='1' value="<?php echo esc_attr( $instance['pinterest_pin_count' ] ); ?>" />
		    </p>
		     <p>
        		    <ol>
        		    <b>
        			<li>In User name enter your username.</li>
        			<li>In board enter pin board as it is.</li>
        			<li>If you leave board empty it will display pin based on you username.</li>
        			<li>Do not forget to make your account public, private pin won't display.</li>
        			</ol>
        			</b>
		    </p>
		    
		<?php 
		}
		
		/**
		 * Sanitize widget form values as they are saved.
		 *
		 * @see WP_Widget::update()
		 *
		 * @param array $new_instance Values just sent to be saved.
		 * @param array $old_instance Previously saved values from database.
		 *
		 * @return array Updated safe values to be saved.
		 */
		public function update( $new_instance, $old_instance ) {
			$instance = array();
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['pinterest_username'] = strip_tags($new_instance['pinterest_username']);
			$instance['pinterest_pin_board'] =  strip_tags($new_instance['pinterest_pin_board']);
			$instance['pinterest_pin_count'] = strip_tags($new_instance['pinterest_pin_count']);
			return $instance;
		}
		
		
		//function to get contents from pinterest css
		public function process_pinterest_feed($username,$pinboard){
			include_once( ABSPATH . WPINC . '/feed.php' );
			// Get a SimplePie feed object from the specified feed source.
			if(isset($pinboard)&& !empty($pinboard)){
			    
			    $feedurl = 'http://pinterest.com/'.$username.'/'.str_replace(' ','-',trim ($pinboard)).'.rss';
			    //echo($feedurl);
			}
			else{
		    $feedurl = 'http://pinterest.com/'.$username.'/feed.rss';
			}
            $rss = fetch_feed($feedurl);
			return $rss;
		}
		
		//function to resgister css and javascript file
		public function pinterest_widget_register_custom_js_css(){
			wp_enqueue_style( 'dashicons' );
			wp_enqueue_style('pinterestCss', plugins_url('css/pinterest_rss.css',__FILE__ ));
			wp_enqueue_script('masonaryJs', plugins_url('js/js-masonry.js',__FILE__ ),array(),'', true);
			wp_enqueue_script('pinterestJs', plugins_url('js/pinterest-widget.js',__FILE__ ),array('masonaryJs','masonry','imagesloaded'),'', true);
			
		}
		/**
		 * Front-end display of widget.
		 *
		 * @see WP_Widget::widget()
		 *
		 * @param array $args     Widget arguments.
		 * @param array $instance Saved values from database.
		 */
		public function widget( $args, $instance ) {
		  
		   $this->pinterest_widget_register_custom_js_css();
		    
			extract( $args );
			$title = apply_filters( 'widget_title', $instance['title'] );
		
			
			echo $before_widget;
			if ( ! empty( $title ) )
			    echo $before_title . $title . $after_title;
			
				$feed = $this->process_pinterest_feed($instance['pinterest_username'],$instance['pinterest_pin_board']);
				
			     $pinCount =  1 <  $instance['pinterest_pin_count']  ?  $instance['pinterest_pin_count'] : '1';
				 $buttonStyle = 'text-decoration:none;padding:5px;color:rgba(255,0,0,1); border-radius:2px;background-color:rgba(255,255,255,1);font-size:30px;';
				 ?>
	<fieldset class="pinterest_feed_fieldset" style="display:none;">
	<legend align="center" style="overflow:hidden;"  >
		<a id="pinterest_widget_follow" style="<?=$buttonStyle?>"   title="<?=__('Click here to follow me','pinterest-widget')?>" class='pinterest_link' href='http://pinterest.com/<?=$instance['pinterest_username']?>' target='_blank'></a>
	</legend>			 
<div id='pinterest_widget_feed'  class='pinterest_feeds' data-pin-count ='<?=$pinCount?>' >

	
				 <?php
			    
				$a = 0;
				 foreach($feed->get_items() as $item):
					 echo "<div  class='feed_item'   data-feed-title = ".$item->get_title().">".$item->get_content()."</div>";
			         if(++$a >= $pinCount) break;
				 endforeach;
			?>
		</div>
		</fieldset>
		
		

			<?php
			echo $after_widget;
		  
		}//end of function widget
		
		
}

/**
 * function resgiter widget as plguin
 */
function register_pinterest_widget(){
    register_widget( "pinterest_widget" );
}

add_action( 'widgets_init', 'register_pinterest_widget' );		
?>