<?php
/**
 * class-affiliates-leader-widget.php
 * 
 * Copyright (c) 2010, 2011 "eggemplo" Antonio Blanco www.eggemplo.com
 * 
 * This code is released under the GNU General Public License.
 * See COPYRIGHT.txt and LICENSE.txt.
 * 
 * This code is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * This header and all notices must be kept intact.
 * 
 * @author Antonio Blanco
 * @package affiliates-leader
 * @since affiliates-leader 1.0.0 
 */

/**
 * Widget
 */
class AffiliatesLeaderWidget extends WP_Widget {

	/**
	 * Creates the widget.
	 */
	function __construct() {
		parent::__construct ( false, $name = 'Affiliates Leader' );
		add_action ( 'wp_print_styles', array (
				__CLASS__,
				'_print_styles' 
		) );
	}

	/**
	 * Enqueues required stylesheets.
	 */
	public static function _print_styles() {
		wp_enqueue_style ( 'affiliates-leader', AFFILIATES_LEADER_PLUGIN_URL . 'css/affiliates-leader.css', array () );
	}

	/**
	 * Widget output
	 *
	 * @see WP_Widget::widget()
	 */
	function widget($args, $instance) {
		global $affiliates_db, $wpdb;

		extract ( $args );
		$title = isset ( $instance ['title'] ) ? apply_filters ( 'widget_title', $instance ['title'] ) : '';
		$widget_id = $args ['widget_id'];
		echo $before_widget;
		if (! empty ( $title )) {
			echo $before_title . $title . $after_title;
		}

		$limit = $instance ['limit'];
		$order = $instance ['order'];

		$aff_table = $affiliates_db->get_tablename( 'affiliates' );
		$ref_table = $affiliates_db->get_tablename( 'referrals' );
		$status = "active";

		$totals = $wpdb->get_results( "SELECT `affiliate_id`, `currency_id`, SUM(`amount`) as 'total'  FROM `" . $ref_table . "` WHERE `status` = 'accepted' GROUP BY `affiliate_id` ORDER BY `total` " . $order . ' LIMIT ' . $limit, ARRAY_A );

		if ( ( $totals ) && ( sizeof( $totals ) > 0 ) ) {
			echo '<ul>';
			foreach ( $totals as $total ) {
				if( $affiliate = affiliates_get_affiliate( $total['affiliate_id'] ) ) {
					echo '<li>' . $affiliate['name'] . ' (' . $total['total'] . ' ' . $total['currency_id'] . ')</li>';
				}
			}
			echo '</ul>';
		} else {
			echo '<p>' . __ ( 'No Affiliates' ) . '</p>';
		}

		echo $after_widget;
	}

	/**
	 * Save widget options
	 *
	 * @see WP_Widget::update()
	 */
	function update($new_instance, $old_instance) {
		$settings = $old_instance;

		// title
		if (! empty ( $new_instance ['title'] )) {
			$settings ['title'] = strip_tags ( $new_instance ['title'] );
		} else {
			unset ( $settings ['title'] );
		}

		// limit
		if (! empty ( $new_instance ['limit'] )) {
			$settings ['limit'] = strip_tags ( $new_instance ['limit'] );
		} else {
			unset ( $settings ['limit'] );
		}

		// order
		if (! empty ( $new_instance ['order'] )) {
			$settings ['order'] = strip_tags ( $new_instance ['order'] );
		} else {
			unset ( $settings ['order'] );
		}

		return $settings;
	}

	/**
	 * Output admin widget options form
	 *
	 * @see WP_Widget::form()
	 */
	function form($instance) {
		$title = isset ( $instance ['title'] ) ? esc_attr ( $instance ['title'] ) : '';
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'affiliates-leader' ); ?></label>
			<input class="widefat"
				id="<?php echo $this->get_field_id( 'title' ); ?>"
				name="<?php echo $this->get_field_name( 'title' ); ?>" type="text"
				value="<?php echo $title; ?>" />
		</p>
		<?php
			$limit = isset ( $instance ['limit'] ) ? esc_attr ( $instance ['limit'] ) : '5';
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'limit' ); ?>"><?php _e( 'Number of affiliates to display:', 'affiliates-leader' ); ?></label>
			<input class="" size="3"
				id="<?php echo $this->get_field_id( 'limit' ); ?>"
				name="<?php echo $this->get_field_name( 'limit' ); ?>" type="text"
				value="<?php echo $limit; ?>" />
		</p>
		<?php
				$order = isset ( $instance ['order'] ) ? esc_attr ( $instance ['order'] ) : 'DESC';
				$selectdesc = ($order == 'DESC') ? "selected" : "";
				$selectasc = ($order == 'ASC') ? "selected" : "";
				?>
		<p>
			<label for="<?php echo $this->get_field_id( 'order' ); ?>"><?php _e( 'Order:', 'affiliates-leader' ); ?></label>
			<select class="widefat"
				id="<?php echo $this->get_field_id( 'order' ); ?>"
				name="<?php echo $this->get_field_name( 'order' ); ?>">
				<option value="DESC" <?php echo $selectdesc;?>><?php _e( 'Desc', 'affiliates-leader' );?></option>
				<option value="ASC" <?php echo $selectasc;?>><?php _e( 'Asc', 'affiliates-leader' );?></option>
			</select>
		</p>
		<?php
	}
}
?>