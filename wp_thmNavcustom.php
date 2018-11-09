
<?php

/**
 * Class Name: thmNavcustom
 * GitHub URI: https://github.com/joshlminga/
 * Description: A custom WordPress nav walker class for Theme Based.
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

class thmNavcustom extends Walker_Nav_Menu
{

	//Starting Level
	function start_lvl( &$output, $depth = 0, $args = array() )
	{
		$indent = str_repeat("\t", $depth);//If sub Menu 1 tab or more depends on depth

		// If the level is sub menu add class hs-sub-menu
		
		// 1: Class for Second Level UL e.g <li> xxxx <ul -1st lvl> <li> <ul - 2nd lvl class='g-mt-minus-2'><li></li></ul -- 2nd lvl end><li><ul -end 1st lvl>
		$submenu = ($depth > 0) ? 'g-mt-minus-2' : 'g-mt-18 g-mt-8--lg--scrolling';
		// 2: Class for first Level UL e.g <li> xxxx <ul -1st lvl class='g-mt-18 g-mt-8--lg--scrolling''> <li> </li><ul -end 1st lvl></li>

		//Attributes for 2nd and 1st level UL
		if ($depth > 0) {
			$attributes = 'id="nav-submenu--features--sliders" aria-labelledby="nav-link--features--sliders"';
		}else{

			$attributes = 'id="nav-submenu-pages" aria-labelledby="nav-link-pages"';
		}

		//Output the Attributes add additional common classes here
		$output .= "\n$indent<ul class=\"hs-sub-menu list-unstyled u-shadow-v11 
					g-brd-top g-brd-primary g-brd-top-2 g-min-width-220 $submenu depth-$depth\" $attributes>\n"; 

	}


	//Start Elevent
	function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0)
	{

		$indent = ( $depth ) ? str_repeat("\t", $depth ) : ''; // Check if li-Element has submenu in it

		//Attributes for 1st lvl LI element with sub-menu <ul> <li 1st level> eeee <ul> <li>ccc</li> </ul> </li></ul>
		$li_attributes = 'data-animation-in="fadeIn" data-animation-out="fadeOut"'; // Attributes of the menu item
		$class_names = $values = '';// Class of the menu item

		$classes = empty($item->classes) ? array('') : (array) $item->classes; // If classes are empty set empty array if not convert the string to array 
		$classes[] = $classes;

		//1: Added class for any li element that has submenu
		//1: Added class for any li element that has no submenu
		$classes[] = ($args->walker->has_children) ? 'hs-has-sub-menu' : ''; // Assign values to the item that has submenu

		$classes[] = ($item->current || $item->current_item_ancestor) ? 'active' : ''; // If item element is active assign class else leave empty

		//Add li element ID
		$classes[] = 'menu-item-' .$item->ID;


		//Check if Submenu has another submenu
		if ($depth && $args->walker->has_children) {
			$classes[] = 'dropdown-item';
		}else{

			//if the Li element is Parent and has No Submenu
			if ($depth == 0 &&  empty($args->walker->has_children)) {
				$classes[] = "nav-item hs-has-mega-menu g-mx-10--lg g-mx-14--xl has-4433";
			}
			//if the li element is Parent adn has submenu
			elseif (!empty($args->walker->has_children)) {
				$classes[] = 'nav-item hs-has-sub-menu g-mx-10--lg g-mx-14--xl';
			}
			//if li element is part of the submenu element
			else{
				$classes[] = "dropdown-item";
			}
		}


		// Allow filtering the classes. 
		$class_names =  join( ' ', apply_filters( 'nav_menu_css_class', array_filter($classes), $item, $args));

		// Form a string of classes in format: class="class_names".
		$class_names = 'class="' .esc_attr($class_names). '"';


		//Item ID
		$id = apply_filters('nav_menu_item_id', 'menu-item-' .$item->ID, $item, $args);
		$id = 'id="' .esc_attr($id) .'"';

		$output .= $indent .'<li ' .$id .$values .$class_names .$li_attributes . '>';


		//Link attribute
		$attribute = !empty($item->attr_title)? 'title="' .$item->attr_title.'"' : '';
		$attribute .= !empty($item->target) ? 'target="' .$item->target. '"' : '';
		$attribute .= !empty($item->xfn) ? 'rel="' .$item->xfn. '"' : '';
		$attribute .= !empty($item->url) ? 'href="' .$item->url. '"' : '';

		//Bootstrap attributes
		if ($args->walker->has_children) {
			//anchor tag a href class & attributes for all element which has drop-down
			$attribute .= 'class="nav-link" aria-haspopup="true" aria-expanded="false" aria-controls="nav-submenu--features--sliders"';
		}else{

			if ($depth == 0) {
				//For any anchor tag a href class & attributes for all element which is parent and has no child
				$attribute .= 'class="nav-link g-py-7 g-px-0"';
			}else{
				//For any anchor tag a href class & attributes for all element which is a child 
				$attribute .= 'class="nav-link"';
			}
		}

		$menuitems = $args->before;
		$menuitems .= '<a ' .$attribute .' >';
		$menuitems .= $args->before_link . apply_filters('the_title',$item->title, $item->ID) .$args->after_link;

		//Check if the current menu item has ul | means has submenu || This will create drop down icon
		//1: A drop down icon (caret) can be added here for a drop down anchor tag a href
		//2: If not drop-down close the anchor tag a href
		$menuitems .= ($depth == 0 && $args->walker->has_children)? '</a>':'</a>';
		$menuitems .= $args->after;

		$output .= apply_filters('walker_nav_menu_start_el', $menuitems, $item, $depth, $args);
		
	}

/*
	//End Element
	function end_el()
	{
	}

	//End Level
	function end_lvl()
	{
	}
*/

}
