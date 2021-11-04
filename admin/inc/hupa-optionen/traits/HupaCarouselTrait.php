<?php


namespace Hupa\StarterTheme;
defined('ABSPATH') or die();

/**
 * ADMIN DATABASE HANDLE
 * @package Hummelt & Partner WordPress Theme
 * Copyright 2021, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 * https://www.hummelt-werbeagentur.de/
 */
trait HupaCarouselTrait
{

    protected array $carousel_default_values;
    //Carousel
    protected int $carousel_aktiv = 1;
    protected int $carousel_controls = 1;
    protected int $carousel_indicator = 1;
    protected int $carousel_data_animate = 1;
    protected int $carousel_data_autoplay = 1;
    protected int $carousel_margin_aktiv = 0;
    protected int $carousel_full_width = 0;
    protected int $carousel_select_bg = 0;
    protected int $carousel_caption_bg = 0;
    protected string $carousel_container_height = '65vh';
    protected string $carousel_image_size = 'large';
    protected int $carousel_lazy_load = 1;

    //Slider
    protected int $carousel_id;
    protected int $slider_position = 0;
    protected int $slider_img_id = 0;
    protected string $slide_button = '';
    protected string $slider_font_color = '#ffffff';
    protected int $slider_aktiv = 1;
    protected int $slider_caption_aktiv = 0;
    protected int $slider_data_interval = 6000;
    protected string $slider_data_alt = '';
    protected string $slider_data_title = '';

    protected string $slider_first_ani = '';
    protected string $slider_first_font = 'Roboto';
    protected int $slider_first_font_style = 3;
    protected int $slider_first_font_size = 32;
    protected string $slider_first_height = '1.5';
    protected string $slider_first_caption = '';
    protected int $first_selector = 1;
    protected string $slider_first_css = '';

    protected string $slider_second_ani = '';
    protected string $slider_second_font = 'Roboto';
    protected int $slider_second_font_style = 3;
    protected int $slider_second_font_size = 16;
    protected string $slider_second_font_height = '1.5';
    protected string $slider_second_caption = '';
    protected int $second_selector = 1;
    protected string $slider_second_css = '';


    protected function get_carousel_default_settings(): array
    {
        return $this->carousel_default_values = [
            'carousel' => [
                'aktiv' => $this->carousel_aktiv,
                'controls' => $this->carousel_controls,
                'indicator' => $this->carousel_indicator,
                'data_animate' => $this->carousel_data_animate,
                'data_autoplay' => $this->carousel_data_autoplay,
                'margin_aktiv' => $this->carousel_margin_aktiv,
                'full_width' => $this->carousel_full_width,
                'select_bg' => $this->carousel_select_bg,
                'caption_bg' => $this->carousel_caption_bg,
                'container_height' => $this->carousel_container_height,
                'carousel_image_size' => $this->carousel_image_size,
                'carousel_lazy_load' => $this->carousel_lazy_load
            ],
            'slider' => [
                'slider_position' => $this->slider_position,
                'slider_img_id' => $this->slider_img_id,
                'slide_button' => $this->slide_button,
                'font_color' => $this->slider_font_color,
                'aktiv' => $this->slider_aktiv,
                'caption_aktiv' => $this->slider_caption_aktiv,
                'data_interval' => $this->slider_data_interval,
                'slider_data_alt' => $this->slider_data_alt,

                'slider_first_ani' => $this->slider_first_ani,
                'first_font' => $this->slider_first_font,
                'first_style' => $this->slider_first_font_style,
                'first_size' => $this->slider_first_font_size,
                'first_height' => $this->slider_first_height,
                'slider_first_caption' => $this->slider_first_caption,
                'first_selector' => $this->first_selector,
                'slider_first_css' => $this->slider_first_css,

                'slider_second_ani' => $this->slider_second_ani,
                'second_font' => $this->slider_second_font,
                'second_style' => $this->slider_second_font_style,
                'second_size' => $this->slider_second_font_size,
                'second_height' => $this->slider_second_font_height,
                'slider_second_caption' => $this->slider_second_caption,
                'slider_second_css' => $this->slider_second_css

            ]
        ];
    }


}