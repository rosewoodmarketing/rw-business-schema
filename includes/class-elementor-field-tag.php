<?php

if (!defined('ABSPATH')) {
    exit;
}

if (class_exists('Elementor\\Core\\DynamicTags\\Tag') && !class_exists('RW_BS_Elementor_Field_Tag')) {
    class RW_BS_Elementor_Field_Tag extends \Elementor\Core\DynamicTags\Tag {
        public function get_name() {
            return 'rw-bs-field';
        }

        public function get_title() {
            return 'RW Business Field';
        }

        public function get_group() {
            return 'site';
        }

        public function get_categories() {
            if (class_exists('Elementor\\Modules\\DynamicTags\\Module')) {
                return array(\Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY);
            }

            return array('text');
        }

        protected function register_controls() {
            $options = array(
                'company_name'    => 'Company Name',
                'address_full'    => 'Address (Full)',
                'street'          => 'Street',
                'city'            => 'City',
                'state'           => 'State',
                'postal_code'     => 'Postal Code',
                'country'         => 'Country',
                'phone_primary'   => 'Primary Phone',
                'phone_secondary' => 'Secondary Phone',
                'email_primary'   => 'Primary Email',
                'email_secondary' => 'Secondary Email',
                'price_range'     => 'Price Range',
                'hours_display'   => 'Display Hours',
                'rating_value'    => 'Rating Value',
                'rating_count'    => 'Rating Count',
            );

            $groups = RW_BS_Type_Definitions::get_groups();
            foreach ($groups as $fields) {
                foreach ($fields as $key => $label) {
                    $options[$key] = $label;
                }
            }

            $location_options = class_exists('RW_BS_Locations')
                ? RW_BS_Locations::get_location_choices()
                : array();

            $this->add_control(
                'rw_bs_source',
                array(
                    'label' => 'Source',
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => 'auto',
                    'options' => array(
                        'auto' => 'Auto',
                        'global' => 'Global Settings',
                        'current_location' => 'Current Location',
                        'specific_location' => 'Specific Location',
                    ),
                )
            );

            $this->add_control(
                'rw_bs_location_id',
                array(
                    'label' => 'Location',
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => '',
                    'options' => $location_options,
                    'condition' => array(
                        'rw_bs_source' => 'specific_location',
                    ),
                )
            );

            $this->add_control(
                'rw_bs_field',
                array(
                    'label' => 'Field',
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'options' => $options,
                )
            );
        }

        private function get_resolved_settings() {
            $source = $this->get_settings('rw_bs_source');
            $location_id = absint($this->get_settings('rw_bs_location_id'));

            if (!class_exists('RW_BS_Locations')) {
                return rw_bs_get_settings();
            }

            if ($source === 'global') {
                return rw_bs_get_settings();
            }

            if ($source === 'specific_location' && $location_id > 0) {
                return RW_BS_Locations::get_location_settings($location_id);
            }

            if ($source === 'current_location') {
                return RW_BS_Locations::get_context_settings();
            }

            if (!empty(rw_bs_get_field('multi_location'))) {
                return RW_BS_Locations::get_context_settings();
            }

            return rw_bs_get_settings();
        }

        public function render() {
            $field = $this->get_settings('rw_bs_field');
            if (!$field) {
                return;
            }

            $settings = $this->get_resolved_settings();

            // Virtual composite field — full address on one line.
            if ($field === 'address_full') {
                echo esc_html(rw_bs_get_full_address_from_settings($settings));
                return;
            }

            if (isset($settings[$field]) && is_scalar($settings[$field])) {
                echo esc_html((string) $settings[$field]);
                return;
            }

            $type_fields = isset($settings['type_fields']) && is_array($settings['type_fields']) ? $settings['type_fields'] : array();
            if (isset($type_fields[$field]) && is_scalar($type_fields[$field])) {
                echo esc_html((string) $type_fields[$field]);
            }
        }
    }
}
