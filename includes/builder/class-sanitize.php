<?php
namespace fx_builder\builder;
use fx_builder\Sanitize as Fs;
if ( ! defined( 'WPINC' ) ) {
    die;
}

/**
 * Sanitize Functions.
 * @since 1.0.0
 */
class Sanitize {
    /* ROWS DATAS
    ------------------------------------------ */
    public static function rows_data( $input ) {
        if ( ! is_array( $input ) || empty( $input ) ) {
            return [];
        }

        $rows = [];

        foreach ( $input as $row_id => $row_data ) {
            $default = [
                'id'                  => $row_id,
                'index'               => '',
                'state'               => 'open',
                'col_num'             => '1',
                'layout'              => '1',
                'col_1'               => '',
                'col_2'               => '',
                'col_3'               => '',
                'col_4'               => '',
                'col_5'               => '',
                'row_title'           => '',
                'row_width'           => 'default',
                'row_html_height'     => '',
                'row_html_id'         => '',
                'row_html_class'      => '',
                'row_column_align'    => 'start',
                'row_column_gap'      => '',
                'row_column_gap_unit' => '',
            ];

            $rows[ $row_id ]                    = wp_parse_args( $row_data, $default );
            $rows[ $row_id ]['id']              = wp_strip_all_tags( $rows[ $row_id ]['id'] );
            $rows[ $row_id ]['index']           = wp_strip_all_tags( $rows[ $row_id ]['index'] );
            $rows[ $row_id ]['state']           = self::state( $rows[ $row_id ]['state'] );
            $rows[ $row_id ]['col_num']         = Functions::get_col_num( $rows[ $row_id ]['layout'] );
            $rows[ $row_id ]['layout']          = self::layout( $rows[ $row_id ]['layout'] );
            $rows[ $row_id ]['col_1']           = self::ids( $rows[ $row_id ]['col_1'] );
            $rows[ $row_id ]['col_2']           = self::ids( $rows[ $row_id ]['col_2'] );
            $rows[ $row_id ]['col_3']           = self::ids( $rows[ $row_id ]['col_3'] );
            $rows[ $row_id ]['col_4']           = self::ids( $rows[ $row_id ]['col_4'] );
            $rows[ $row_id ]['col_5']           = self::ids( $rows[ $row_id ]['col_5'] );
            $rows[ $row_id ]['row_title']       = sanitize_text_field( $rows[ $row_id ]['row_title'] );
            $rows[ $row_id ]['row_width']       = sanitize_text_field( $rows[ $row_id ]['row_width'] );
            $rows[ $row_id ]['row_html_height'] = sanitize_text_field( $rows[ $row_id ]['row_html_height'] );
            $rows[ $row_id ]['row_html_id']     = sanitize_html_class( $rows[ $row_id ]['row_html_id'] );
            $rows[ $row_id ]['row_html_class']  = self::html_classes( $rows[ $row_id ]['row_html_class'] );

            $rows[ $row_id ]['row_column_align']    = sanitize_text_field( $rows[ $row_id ]['row_column_align'] );
            $rows[ $row_id ]['row_column_gap']      = sanitize_text_field( $rows[ $row_id ]['row_column_gap'] );
            $rows[ $row_id ]['row_column_gap_unit'] = sanitize_text_field( $rows[ $row_id ]['row_column_gap_unit'] );
        }

        return $rows;
    }


    /* ITEMS DATAS
    ------------------------------------------ */
    public static function items_data( $input ) {
        if ( ! is_array( $input ) || empty( $input ) ) {
            return [];
        }
        $items = [];
        foreach ( $input as $item_id => $item_data ) {
            $default = [
                'item_id'    => $item_id,
                'item_index' => '',
                'item_state' => 'open',
                'item_type'  => 'text',
                'row_id'     => '',
                'col_index'  => 'col_1',
                'content'    => '',
            ];

            $items[ $item_id ]               = wp_parse_args( $item_data, $default );
            $items[ $item_id ]['item_id']    = wp_strip_all_tags( $items[ $item_id ]['item_id'] );
            $items[ $item_id ]['item_index'] = wp_strip_all_tags( $items[ $item_id ]['item_index'] );
            $items[ $item_id ]['item_state'] = self::state( $items[ $item_id ]['item_state'] );
            $items[ $item_id ]['item_type']  = self::item_type( $items[ $item_id ]['item_type'] );
            $items[ $item_id ]['row_id']     = wp_strip_all_tags( $items[ $item_id ]['row_id'] );
            $items[ $item_id ]['col_index']  = self::item_col_index( $items[ $item_id ]['col_index'] );
            $items[ $item_id ]['content']    = wp_kses_post( $items[ $item_id ]['content'] );
        }
        return $items;
    }


    /* Other Sanitize Functions
    ------------------------------------------ */

    /**
     * Sanitize State
     */
    public static function state( $input ) {
        $default = 'open';
        $valid   = [ 'open', 'close' ];
        if ( in_array( $input, $valid ) ) {
            return $input;
        }
        return $default;
    }

    /**
     * Sanitize Layout
     */
    public static function layout( $layout ) {
        $default = '1';
        $valid   = [ '1', '12_12', '13_23', '23_13', '13_13_13', '14_14_14_14', '15_15_15_15_15' ];
        if ( in_array( $layout, $valid ) ) {
            return $layout;
        }
        return $default;
    }

    /**
     * Sanitize IDs
     */
    public static function ids( $input ) {
        $output = explode( ',', $input );
        $output = array_map( 'wp_strip_all_tags', $output );
        $output = implode( ',', $output );
        return $output;
    }

    /**
     * Sanitize Col Number from Layout
     */
    public static function item_type( $input ) {
        $default = 'text';
        $valid   = [ 'text' ];
        if ( in_array( $input, $valid ) ) {
            return $input;
        }
        return $default;
    }

    /**
     * Sanitize Col Index
     */
    public static function item_col_index( $input ) {
        $default = 'col_1';
        $valid   = [ 'col_1', 'col_2', 'col_3', 'col_4', 'col_5' ];
        if ( in_array( $input, $valid ) ) {
            return $input;
        }
        return $default;
    }

    /**
     * Sanitize HTML Classes
     */
    public static function html_classes( $classes ) {
        $classes = explode( ' ', $classes );
        $classes = array_map( 'sanitize_html_class', $classes );
        $classes = implode( ' ', $classes );
        return $classes;
    }


    /**
     * Sanitize Version
     */
    public static function version( $input ) {
        $output = sanitize_text_field( $input );
        $output = str_replace( ' ', '', $output );
        return trim( esc_attr( $output ) );
    }

    /**
     * Sanitize Custom CSS
     */
    public static function css( $css ) {
        $css = preg_replace( '@<(script|style)[^>]*?>.*?</\\1>@si', '', $css );
        $css = wp_kses( $css, [] );
        $css = esc_html( $css );
        $css = str_replace( '&gt;', '>', $css );
        $css = str_replace( '&quot;', '"', $css );
        $css = str_replace( '&amp;', '&', $css );
        $css = str_replace( '&amp;#039;', "'", $css );
        $css = str_replace( '&#039;', "'", $css );
        return $css;
    }
} // end class
