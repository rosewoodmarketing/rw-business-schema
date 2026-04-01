<?php

if (!defined('ABSPATH')) {
    exit;
}

class RW_BS_Type_Definitions {
    public static function get_types() {
        return array(
            'LocalBusiness' => array(
                'label' => 'Local Business',
                'group' => 'none',
                'fields' => array(),
            ),
            'Restaurant' => array(
                'label' => 'Restaurant',
                'group' => 'food',
                'fields' => array('servesCuisine', 'hasMenu', 'acceptsReservations'),
            ),
            'CafeOrCoffeeShop' => array(
                'label' => 'Cafe / Coffee Shop',
                'group' => 'food',
                'fields' => array('servesCuisine', 'hasMenu', 'acceptsReservations'),
            ),
            'Bakery' => array(
                'label' => 'Bakery',
                'group' => 'food',
                'fields' => array('servesCuisine', 'hasMenu'),
            ),
            'Hotel' => array(
                'label' => 'Hotel',
                'group' => 'lodging',
                'fields' => array('checkinTime', 'checkoutTime', 'numberOfRooms', 'petsAllowed', 'starRating'),
            ),
            'MedicalClinic' => array(
                'label' => 'Medical Clinic',
                'group' => 'medical',
                'fields' => array('medicalSpecialty', 'availableService'),
            ),
            'FinancialService' => array(
                'label' => 'Financial Service',
                'group' => 'financial',
                'fields' => array('feesAndCommissionsSpecification'),
            ),
            'Store' => array(
                'label' => 'Store',
                'group' => 'none',
                'fields' => array(),
            ),
            'LegalService' => array(
                'label' => 'Legal Service',
                'group' => 'none',
                'fields' => array(),
            ),
            'BeautySalon' => array(
                'label' => 'Beauty Salon',
                'group' => 'none',
                'fields' => array(),
            ),
            'AutomotiveBusiness' => array(
                'label' => 'Automotive Business',
                'group' => 'none',
                'fields' => array(),
            ),
        );
    }

    public static function get_type_keys() {
        return array_keys(self::get_types());
    }

    public static function get_groups() {
        return array(
            'food' => array(
                'servesCuisine' => 'Serves Cuisine',
                'hasMenu' => 'Menu URL',
                'acceptsReservations' => 'Accepts Reservations',
            ),
            'lodging' => array(
                'checkinTime' => 'Check-in Time',
                'checkoutTime' => 'Check-out Time',
                'numberOfRooms' => 'Number Of Rooms',
                'petsAllowed' => 'Pets Allowed',
                'starRating' => 'Star Rating',
            ),
            'medical' => array(
                'medicalSpecialty' => 'Medical Specialty',
                'availableService' => 'Available Service',
            ),
            'financial' => array(
                'feesAndCommissionsSpecification' => 'Fees And Commissions URL',
            ),
        );
    }
}
