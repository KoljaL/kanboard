<?php

namespace Kanboard\Model;

use Kanboard\Core\Base;

/**
 * Color model
 *
 * @package  Kanboard\Model
 * @author   Frederic Guillot
 */
class ColorModel extends Base
{
    /**
     * Default colors
     *
     * @access protected
     * @var array
     */
    protected $default_colors = array(
        'yellow' => array(
            'name' => 'Yellow',
            'background' => 'rgb(230, 192, 123)',
            'border' => 'rgb(161, 134, 86)',
        ),
        'blue' => array(
            'name' => 'Blue',
            'background' => 'rgb(97, 174, 238)',
            'border' => 'rgb(67, 121, 166)',
        ),
        'green' => array(
            'name' => 'Green',
            'background' => 'rgb(152, 195, 121)',
            'border' => 'rgb(106, 136, 84)',
        ),
        'purple' => array(
            'name' => 'Purple',
            'background' => 'rgb(198, 120, 221)',
            'border' => 'rgb(138, 84, 154)',
        ),
        'red' => array(
            'name' => 'Red',
            'background' => 'rgb(190, 80, 70)',
            'border' => 'rgb(133, 56, 49)',
        ),
        'orange' => array(
            'name' => 'Orange',
            'background' => 'rgb(209, 154, 102)',
            'border' => 'rgb(146, 107, 71)',
        ),
        'grey' => array(
            'name' => 'Grey',
            'background' => 'rgb(238, 238, 238)',
            'border' => 'rgb(204, 204, 204)',
        ),
        // 'brown' => array(
        //     'name' => 'Brown',
        //     'background' => '#d7ccc8',
        //     'border' => '#4e342e',
        // ),
        // 'deep_orange' => array(
        //     'name' => 'Deep Orange',
        //     'background' => '#ffab91',
        //     'border' => '#e64a19',
        // ),
        'dark_grey' => array(
            'name' => 'Dark Grey',
            'background' => '#cfd8dc',
            'border' => '#455a64',
        ),
        'pink' => array(
            'name' => 'Pink',
            'background' => '#e06c75',
            'border' => '#9c4b51',
        ),
        'teal' => array(
            'name' => 'Teal',
            'background' => '#56b6c2',
            'border' => '#3c7f87',
        ),
        // 'cyan' => array(
        //     'name' => 'Cyan',
        //     'background' => '#b2ebf2',
        //     'border' => '#00bcd4',
        // ),
        // 'lime' => array(
        //     'name' => 'Lime',
        //     'background' => '#e6ee9c',
        //     'border' => '#afb42b',
        // ),
        // 'light_green' => array(
        //     'name' => 'Light Green',
        //     'background' => '#dcedc8',
        //     'border' => '#689f38',
        // ),
        // 'amber' => array(
        //     'name' => 'Amber',
        //     'background' => '#ffe082',
        //     'border' => '#ffa000',
        // ),
    );

    /**
     * Find a color id from the name or the id
     *
     * @access public
     * @param  string  $color
     * @return string
     */
    public function find($color)
    {
        $color = strtolower($color);

        foreach ($this->default_colors as $color_id => $params) {
            if ($color_id === $color) {
                return $color_id;
            } elseif ($color === strtolower($params['name'])) {
                return $color_id;
            }
        }

        return '';
    }

    /**
     * Get color properties
     *
     * @access public
     * @param  string  $color_id
     * @return array
     */
    public function getColorProperties($color_id)
    {
        if (isset($this->default_colors[$color_id])) {
            return $this->default_colors[$color_id];
        }

        return $this->default_colors[$this->getDefaultColor()];
    }

    /**
     * Get available colors
     *
     * @access public
     * @param  bool $prepend
     * @return array
     */
    public function getList($prepend = false)
    {
        $listing = $prepend ? array('' => t('All colors')) : array();

        foreach ($this->default_colors as $color_id => $color) {
            $listing[$color_id] = t($color['name']);
        }

        $this->hook->reference('model:color:get-list', $listing);

        return $listing;
    }

    /**
     * Get the default color
     *
     * @access public
     * @return string
     */
    public function getDefaultColor()
    {
        return $this->configModel->get('default_color', 'yellow');
    }

    /**
     * Get the default colors
     *
     * @access public
     * @return array
     */
    public function getDefaultColors()
    {
        return $this->default_colors;
    }

    /**
     * Get border color from string
     *
     * @access public
     * @param  string   $color_id   Color id
     * @return string
     */
    public function getBorderColor($color_id)
    {
        $color = $this->getColorProperties($color_id);
        return $color['border'];
    }

    /**
     * Get background color from the color_id
     *
     * @access public
     * @param  string   $color_id   Color id
     * @return string
     */
    public function getBackgroundColor($color_id)
    {
        $color = $this->getColorProperties($color_id);
        return $color['background'];
    }

    /**
     * Get CSS stylesheet of all colors
     *
     * @access public
     * @return string
     */
    public function getCss()
    {
        $buffer = '';

        foreach ($this->default_colors as $color => $values) {
            $buffer .= '.task-board.color-'.$color.', .task-summary-container.color-'.$color.', .color-picker-square.color-'.$color.', .task-board-category.color-'.$color.', .table-list-category.color-'.$color.', .task-tag.color-'.$color.' {';
            $buffer .= 'background-color: '.$values['background'].';';
            $buffer .= 'border-color: '.$values['border'];
            $buffer .= '}';
            $buffer .= 'td.color-'.$color.' { background-color: '.$values['background'].'}';
            $buffer .= '.table-list-row.color-'.$color.' {border-left: 5px solid '.$values['border'].'}';
        }

        return $buffer;
    }
}
