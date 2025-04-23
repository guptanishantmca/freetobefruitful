<?php
class Custom_Nav_Walker extends Walker_Nav_Menu {
    function start_lvl(&$output, $depth = 0, $args = null) {
        $output .= "\n<ul>\n";
    }

    function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $classes = empty($item->classes) ? [] : (array) $item->classes;

        // Add custom active classes if this is the current item
        if (in_array('current-menu-item', $classes) || in_array('current_page_item', $classes)) {
            $classes[] = 'nav-selected';
            $classes[] = 'nav-path-selected';
        }

        $class_names = implode(' ', array_filter($classes));
        $class_attr = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

        $output .= '<li>';
        $output .= '<a href="' . esc_url($item->url) . '" target="_self"' . $class_attr . '>';
        $output .= esc_html($item->title);
        $output .= '</a>';
    }

    function end_el(&$output, $item, $depth = 0, $args = null) {
        $output .= "</li>\n";
    }
}
