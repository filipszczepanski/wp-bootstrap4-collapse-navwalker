<?php

/**
* Class Name: CollapseBootstrap4Navwalker
* GitHub URI: https://github.com/....
* Description: A custom WordPress nav walker class for Bootstrap 4 (v4.0.0-alpha.1) nav menus in a custom theme using the WordPress built in menu manager
* Version: 0.1
* Author: Filip Szczepanski
* License: GPL-2.0+
* License URI: http://www.gnu.org/licenses/gpl-2.0.txt
*/

class WP_Bootstrap4_Collapse_Walker extends Walker_Nav_Menu{
  var $parent_item_id = 0;
  var $parent_item_depth = false;
  var $parent_has_current_child = false;




  /**
	 * Starts the list before the elements are added.
	 *
	 * @since 3.0.0
	 *
	 * @see Walker::start_lvl()
	 *
	 * @param string   $output Passed by reference. Used to append additional content.
	 * @param int      $depth  Depth of menu item. Used for padding.
	 * @param stdClass $args   An object of wp_nav_menu() arguments.
	 */
  public function start_lvl( &$output, $depth = 0, $args = array()) {
    if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
      $t = ''; $n = '';
    } else {
      $t = "\t"; $n = "\n";
    }
		$indent = str_repeat( $t, $depth );
    $collapse_in_class = $this->parent_has_current_child ? 'in' : '';
    $collapse_id = '';
    if (!empty($this->parent_item_id)) {
      $collapse_id = $this->collapse_id($this->parent_item_id);
    }
    $collapse_block = sprintf('<ul id="%s" class="nav collapse %s" aria-labelledby="link_%s" role="tabpanel">' ."\n", $collapse_id, $collapse_in_class, $collapse_id);
    $output .= $n.$indent.$collapse_block.$n;
  }

  /**
	 * Ends the list of after the elements are added.
	 *
	 * @since 3.0.0
	 *
	 * @see Walker::end_lvl()
	 *
	 * @param string   $output Passed by reference. Used to append additional content.
	 * @param int      $depth  Depth of menu item. Used for padding.
	 * @param stdClass $args   An object of wp_nav_menu() arguments.
	 */
	public function end_lvl( &$output, $depth = 0, $args = array() ) {
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = str_repeat( $t, $depth );
		$output .= "$indent</ul>{$n}";
	}

  /**
	 * Starts the element output.
	 *
	 * @since 3.0.0
	 * @since 4.4.0 The {@see 'nav_menu_item_args'} filter was added.
	 *
	 * @see Walker::start_el()
	 *
	 * @param string   $output Passed by reference. Used to append additional content.
	 * @param WP_Post  $item   Menu item data object.
	 * @param int      $depth  Depth of menu item. Used for padding.
	 * @param stdClass $args   An object of wp_nav_menu() arguments.
	 * @param int      $id     Current item ID.
	 */
  public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0) {

    $classes = empty( $item->classes ) ? array() : (array) $item->classes;

    if ($this->parent_item_depth !== $depth || $this->parent_item_id !== $item->ID) {
      $this->parent_item_depth = $depth;
      $this->parent_item_id = $item->ID;
      $this->parent_has_current_child = (in_array('current-menu-ancestor', $classes));
      $this->start_el($output,$item,$depth,$args,$item->ID);
    } else {
        if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
    			$t = '';
    			$n = '';
    		} else {
    			$t = "\t";
    			$n = "\n";
    		}
    		$indent = ( $depth ) ? str_repeat( $t, $depth ) : '';
        $this->parent_item_depth = 0;
        $classes[] = 'menu-item-' . $item->ID;
        $classes[] = 'nav-item';
        if (in_array('current-menu-item', $classes)) {
          $classes[] = ' active';
        }

    		/**
    		 * Filters the arguments for a single nav menu item.
    		 *
    		 * @since 4.4.0
    		 *
    		 * @param stdClass $args  An object of wp_nav_menu() arguments.
    		 * @param WP_Post  $item  Menu item data object.
    		 * @param int      $depth Depth of menu item. Used for padding.
    		 */
//    		$args = apply_filters( 'nav_menu_item_args', $args, $item, $depth );

    		/**
    		 * Filters the CSS class(es) applied to a menu item's list item element.
    		 *
    		 * @since 3.0.0
    		 * @since 4.1.0 The `$depth` parameter was added.
    		 *
    		 * @param array    $classes The CSS classes that are applied to the menu item's `<li>` element.
    		 * @param WP_Post  $item    The current menu item.
    		 * @param stdClass $args    An object of wp_nav_menu() arguments.
    		 * @param int      $depth   Depth of menu item. Used for padding.
    		 */
        $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );


        $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

        /**
    		 * Filters the ID applied to a menu item's list item element.
    		 *
    		 * @since 3.0.1
    		 * @since 4.1.0 The `$depth` parameter was added.
    		 *
    		 * @param string   $menu_id The ID that is applied to the menu item's `<li>` element.
    		 * @param WP_Post  $item    The current menu item.
    		 * @param stdClass $args    An object of wp_nav_menu() arguments.
    		 * @param int      $depth   Depth of menu item. Used for padding.
    		 */
        $id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args, $depth );
        $id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

        $item_output = $indent . '<li' . $id . $class_names .'>';

        $atts = array();
        $atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
        $atts['target'] = ! empty( $item->target )     ? $item->target     : '';
        $atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
        $atts['href']   = ! empty( $item->url )        ? $item->url        : '';

        if ($depth === 0) {
          $atts['class'] = 'nav-link';
        } elseif ($depth > 0) {
          $atts['class'] = 'link-item';
        }


        if ($args->walker->has_children) {
          $atts['data-toggle']  = 'collapse';
          $atts['aria-expanded']  = 'false';
          $atts['aria-controls']  = $this->collapse_id($item->ID);
          $atts['role']  = 'tab';
          $atts['href']  = '#'.$this->collapse_id($item->ID);
          $atts['id']  = 'link_'.$this->collapse_id($item->ID);
        }

        if (in_array('current-menu-item', $item->classes) && in_array('nav-item', $item->classes)) {
            $atts['class'] .= ' active';
        }

        /**
         * Filters the HTML attributes applied to a menu item's anchor element.
         *
         * @since 3.6.0
         * @since 4.1.0 The `$depth` parameter was added.
         *
         * @param array $atts {
         *     The HTML attributes applied to the menu item's `<a>` element, empty strings are ignored.
         *
         *     @type string $title  Title attribute.
         *     @type string $target Target attribute.
         *     @type string $rel    The rel attribute.
         *     @type string $href   The href attribute.
         * }
         * @param WP_Post  $item  The current menu item.
         * @param stdClass $args  An object of wp_nav_menu() arguments.
         * @param int      $depth Depth of menu item. Used for padding.
         */
        $atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

        $attributes = '';
        foreach ( $atts as $attr => $value ) {
            if ( ! empty( $value ) ) {
                $value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }
        /** This filter is documented in wp-includes/post-template.php */
    		$title = apply_filters( 'the_title', $item->title, $item->ID );


        /**
    		 * Filters a menu item's title.
    		 *
    		 * @since 4.4.0
    		 *
    		 * @param string   $title The menu item's title.
    		 * @param WP_Post  $item  The current menu item.
    		 * @param stdClass $args  An object of wp_nav_menu() arguments.
    		 * @param int      $depth Depth of menu item. Used for padding.
    		 */
    		$title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );

        $item_output .= $args->before;
        $item_output .= '<a'. $attributes .'>';
        $item_output .= $args->link_before . $title . $args->link_after;
        $item_output .= '</a>';
        $item_output .= $args->after;

        /**
    		 * Filters a menu item's starting output.
    		 *
    		 * The menu item's starting output only includes `$args->before`, the opening `<a>`,
    		 * the menu item's title, the closing `</a>`, and `$args->after`. Currently, there is
    		 * no filter for modifying the opening and closing `<li>` for a menu item.
    		 *
    		 * @since 3.0.0
    		 *
    		 * @param string   $item_output The menu item's starting HTML output.
    		 * @param WP_Post  $item        Menu item data object.
    		 * @param int      $depth       Depth of menu item. Used for padding.
    		 * @param stdClass $args        An object of wp_nav_menu() arguments.
    		 */
        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
      }

  }

  /**
	 * Ends the element output, if needed.
	 *
	 * @since 3.0.0
	 *
	 * @see Walker::end_el()
	 *
	 * @param string   $output Passed by reference. Used to append additional content.
	 * @param WP_Post  $item   Page data object. Not used.
	 * @param int      $depth  Depth of page. Not Used.
	 * @param stdClass $args   An object of wp_nav_menu() arguments.
	 */
  public function end_el( &$output, $item, $depth = 0, $args = array() ) {
    if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$output .= "</li>{$n}";
  }

  private function collapse_id($nav_id) {
    return 'collapse_'.$nav_id;
  }
} // Walker_Nav_Menu
