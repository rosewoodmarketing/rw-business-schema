<?php

if (!defined('ABSPATH')) {
    exit;
}

class RW_BS_Type_Definitions {
    public static function get_types() {
        return array(
            'LocalBusiness' => array(
                'label'       => 'Local Business',
                'description' => 'A particular physical business or branch of an organization.',
                'group'       => 'none',
                'fields'      => array(),
            ),
            'AnimalShelter' => array(
                'label'       => 'Animal Shelter',
                'description' => 'An organization providing shelter and care for stray or surrendered animals.',
                'group'       => 'none',
                'fields'      => array(),
            ),
            'ArchiveOrganization' => array(
                'label'       => 'Archive Organization',
                'description' => 'An organization that maintains and provides access to archival records or collections.',
                'group'       => 'none',
                'fields'      => array(),
            ),
            'AutomotiveBusiness' => array(
                'label'       => 'Automotive Business',
                'description' => 'A car dealership, repair shop, gas station, or other automotive service business.',
                'group'       => 'none',
                'fields'      => array(),
                'subtypes'    => array(
                    'AutoBodyShop' => array(
                        'label'       => 'Auto Body Shop',
                        'description' => 'An automotive shop specializing in collision and body repair.',
                        'group'       => 'none',
                        'fields'      => array(),
                    ),
                    'AutoDealer' => array(
                        'label'       => 'Auto Dealer',
                        'description' => 'A dealership that sells new or used automobiles.',
                        'group'       => 'none',
                        'fields'      => array(),
                    ),
                    'AutoPartsStore' => array(
                        'label'       => 'Auto Parts Store',
                        'description' => 'A retail store that sells automobile parts and accessories.',
                        'group'       => 'none',
                        'fields'      => array(),
                    ),
                    'AutoRental' => array(
                        'label'       => 'Auto Rental',
                        'description' => 'A business that rents cars or other motor vehicles to customers.',
                        'group'       => 'none',
                        'fields'      => array(),
                    ),
                    'AutoRepair' => array(
                        'label'       => 'Auto Repair',
                        'description' => 'A business that repairs and services automobiles.',
                        'group'       => 'none',
                        'fields'      => array(),
                    ),
                    'AutoWash' => array(
                        'label'       => 'Auto Wash',
                        'description' => 'A business that provides vehicle washing and detailing services.',
                        'group'       => 'none',
                        'fields'      => array(),
                    ),
                    'GasStation' => array(
                        'label'       => 'Gas Station',
                        'description' => 'An automotive business that sells motor fuel and may offer convenience services.',
                        'group'       => 'none',
                        'fields'      => array(),
                    ),
                    'MotorcycleDealer' => array(
                        'label'       => 'Motorcycle Dealer',
                        'description' => 'A dealership that sells new or used motorcycles.',
                        'group'       => 'none',
                        'fields'      => array(),
                    ),
                    'MotorcycleRepair' => array(
                        'label'       => 'Motorcycle Repair',
                        'description' => 'A repair shop specializing in motorcycle maintenance and repair.',
                        'group'       => 'none',
                        'fields'      => array(),
                    ),
                ),
            ),
            'Bakery' => array(
                'label'       => 'Bakery',
                'description' => 'A food establishment that produces and sells baked goods such as bread, cakes, and pastries.',
                'group'       => 'food',
                'fields'      => array('servesCuisine', 'hasMenu'),
            ),
            'BeautySalon' => array(
                'label'       => 'Beauty Salon',
                'description' => 'A salon offering hair, nail, skin, or other beauty treatments.',
                'group'       => 'none',
                'fields'      => array(),
            ),
            'CafeOrCoffeeShop' => array(
                'label'       => 'Cafe / Coffee Shop',
                'description' => 'A cafe or coffee shop serving beverages and light food items.',
                'group'       => 'food',
                'fields'      => array('servesCuisine', 'hasMenu', 'acceptsReservations'),
            ),
            'ChildCare' => array(
                'label'       => 'Child Care',
                'description' => 'A childcare center or daycare facility providing supervised care for children.',
                'group'       => 'none',
                'fields'      => array(),
            ),
            'Dentist' => array(
                'label'       => 'Dentist',
                'description' => 'A dental practice providing oral health care services.',
                'group'       => 'none',
                'fields'      => array(),
            ),
            'DryCleaningOrLaundry' => array(
                'label'       => 'Dry Cleaning / Laundry',
                'description' => 'A business offering dry cleaning, laundry, or garment care services.',
                'group'       => 'none',
                'fields'      => array(),
            ),
            'EmergencyService' => array(
                'label'       => 'Emergency Service',
                'description' => 'An emergency service provider such as a fire station, ambulance service, or crisis center.',
                'group'       => 'none',
                'fields'      => array(),
                'subtypes'    => array(
                    'FireStation' => array(
                        'label'       => 'Fire Station',
                        'description' => 'A fire station housing firefighting equipment and personnel.',
                        'group'       => 'none',
                        'fields'      => array(),
                    ),
                    'Hospital' => array(
                        'label'       => 'Hospital',
                        'description' => 'A hospital providing inpatient medical treatment and emergency care.',
                        'group'       => 'medical',
                        'fields'      => array('medicalSpecialty', 'availableService'),
                    ),
                    'PoliceStation' => array(
                        'label'       => 'Police Station',
                        'description' => 'A police station providing law enforcement services to the community.',
                        'group'       => 'none',
                        'fields'      => array(),
                    ),
                ),
            ),
            'EmploymentAgency' => array(
                'label'       => 'Employment Agency',
                'description' => 'An agency that helps match job seekers with employers.',
                'group'       => 'none',
                'fields'      => array(),
            ),
            'EntertainmentBusiness' => array(
                'label'       => 'Entertainment Business',
                'description' => 'A business primarily providing entertainment such as a cinema, theater, or comedy club.',
                'group'       => 'none',
                'fields'      => array(),
                'subtypes'    => array(
                    'AdultEntertainment' => array(
                        'label'       => 'Adult Entertainment',
                        'description' => 'An adult entertainment venue or club.',
                        'group'       => 'none',
                        'fields'      => array(),
                    ),
                    'AmusementPark' => array(
                        'label'       => 'Amusement Park',
                        'description' => 'An amusement or theme park offering rides and attractions.',
                        'group'       => 'none',
                        'fields'      => array(),
                    ),
                    'ArtGallery' => array(
                        'label'       => 'Art Gallery',
                        'description' => 'A gallery displaying and selling works of art.',
                        'group'       => 'none',
                        'fields'      => array(),
                    ),
                    'Casino' => array(
                        'label'       => 'Casino',
                        'description' => 'A gambling establishment offering casino games and activities.',
                        'group'       => 'none',
                        'fields'      => array(),
                    ),
                    'ComedyClub' => array(
                        'label'       => 'Comedy Club',
                        'description' => 'A venue hosting stand-up comedy and comedic performances.',
                        'group'       => 'none',
                        'fields'      => array(),
                    ),
                    'MovieTheater' => array(
                        'label'       => 'Movie Theater',
                        'description' => 'A cinema or movie theater showing films to a paying audience.',
                        'group'       => 'none',
                        'fields'      => array(),
                    ),
                    'NightClub' => array(
                        'label'       => 'Night Club',
                        'description' => 'A nightclub or dance club open in the evening with music and entertainment.',
                        'group'       => 'none',
                        'fields'      => array(),
                    ),
                ),
            ),
            'FinancialService' => array(
                'label'       => 'Financial Service',
                'description' => 'A business providing financial services such as banking, insurance, or investment advice.',
                'group'       => 'financial',
                'fields'      => array('feesAndCommissionsSpecification'),
                'subtypes'    => array(
                    'AccountingService' => array(
                        'label'       => 'Accounting Service',
                        'description' => 'A business providing accounting, bookkeeping, or financial reporting services.',
                        'group'       => 'financial',
                        'fields'      => array('feesAndCommissionsSpecification'),
                    ),
                    'AutomatedTeller' => array(
                        'label'       => 'Automated Teller (ATM)',
                        'description' => 'An automated teller machine (ATM) providing self-service banking transactions.',
                        'group'       => 'none',
                        'fields'      => array(),
                    ),
                    'BankOrCreditUnion' => array(
                        'label'       => 'Bank / Credit Union',
                        'description' => 'A bank or credit union offering deposit, loan, and financial services.',
                        'group'       => 'financial',
                        'fields'      => array('feesAndCommissionsSpecification'),
                    ),
                    'InsuranceAgency' => array(
                        'label'       => 'Insurance Agency',
                        'description' => 'An agency that sells or brokers insurance products and policies.',
                        'group'       => 'financial',
                        'fields'      => array('feesAndCommissionsSpecification'),
                    ),
                ),
            ),
            'FoodEstablishment' => array(
                'label'       => 'Food Establishment',
                'description' => 'A food-related business. Use a more specific type (Restaurant, Bakery, Cafe) when available.',
                'group'       => 'food',
                'fields'      => array('servesCuisine', 'hasMenu', 'acceptsReservations'),
                'subtypes'    => array(
                    'BarOrPub' => array(
                        'label'       => 'Bar / Pub',
                        'description' => 'A bar or pub primarily serving alcoholic beverages.',
                        'group'       => 'food',
                        'fields'      => array('servesCuisine', 'hasMenu'),
                    ),
                    'Brewery' => array(
                        'label'       => 'Brewery',
                        'description' => 'A brewery that produces and sells beer, often with an on-site taproom.',
                        'group'       => 'food',
                        'fields'      => array('servesCuisine', 'hasMenu'),
                    ),
                    'Distillery' => array(
                        'label'       => 'Distillery',
                        'description' => 'A distillery that produces spirits and may offer tastings or tours.',
                        'group'       => 'food',
                        'fields'      => array('hasMenu'),
                    ),
                    'FastFoodRestaurant' => array(
                        'label'       => 'Fast Food Restaurant',
                        'description' => 'A quick-service restaurant offering fast food items.',
                        'group'       => 'food',
                        'fields'      => array('servesCuisine', 'hasMenu'),
                    ),
                    'IceCreamShop' => array(
                        'label'       => 'Ice Cream Shop',
                        'description' => 'A shop specializing in ice cream, gelato, or frozen desserts.',
                        'group'       => 'food',
                        'fields'      => array('servesCuisine', 'hasMenu'),
                    ),
                    'Winery' => array(
                        'label'       => 'Winery',
                        'description' => 'A winery that produces and sells wine, often with tastings and tours.',
                        'group'       => 'food',
                        'fields'      => array('hasMenu'),
                    ),
                ),
            ),
            'GovernmentOffice' => array(
                'label'       => 'Government Office',
                'description' => 'A government office or public administrative office.',
                'group'       => 'none',
                'fields'      => array(),
                'subtypes'    => array(
                    'PostOffice' => array(
                        'label'       => 'Post Office',
                        'description' => 'A post office providing postal, shipping, and related government services.',
                        'group'       => 'none',
                        'fields'      => array(),
                    ),
                ),
            ),
            'HealthAndBeautyBusiness' => array(
                'label'       => 'Health and Beauty Business',
                'description' => 'A business in the health and beauty sector such as a spa, gym, or wellness center.',
                'group'       => 'none',
                'fields'      => array(),
                'subtypes'    => array(
                    'DaySpa' => array(
                        'label'       => 'Day Spa',
                        'description' => 'A spa offering day treatments such as massages, facials, and body treatments.',
                        'group'       => 'none',
                        'fields'      => array(),
                    ),
                    'HairSalon' => array(
                        'label'       => 'Hair Salon',
                        'description' => 'A salon specializing in hair cutting, styling, and coloring services.',
                        'group'       => 'none',
                        'fields'      => array(),
                    ),
                    'HealthClub' => array(
                        'label'       => 'Health Club',
                        'description' => 'A membership-based fitness facility offering exercise equipment, classes, and wellness services.',
                        'group'       => 'none',
                        'fields'      => array(),
                    ),
                    'NailSalon' => array(
                        'label'       => 'Nail Salon',
                        'description' => 'A salon specializing in manicures, pedicures, and nail care.',
                        'group'       => 'none',
                        'fields'      => array(),
                    ),
                    'TattooParlor' => array(
                        'label'       => 'Tattoo Parlor',
                        'description' => 'A business providing tattoo and body piercing services.',
                        'group'       => 'none',
                        'fields'      => array(),
                    ),
                ),
            ),
            'HomeAndConstructionBusiness' => array(
                'label'       => 'Home & Construction Business',
                'description' => 'A business providing home improvement, construction, or contracting services.',
                'group'       => 'none',
                'fields'      => array(),
                'subtypes'    => array(
                    'Electrician' => array(
                        'label'       => 'Electrician',
                        'description' => 'An electrical contractor providing installation, repair, and maintenance services.',
                        'group'       => 'none',
                        'fields'      => array(),
                    ),
                    'GeneralContractor' => array(
                        'label'       => 'General Contractor',
                        'description' => 'A general contracting business managing construction and renovation projects.',
                        'group'       => 'none',
                        'fields'      => array(),
                    ),
                    'HVACBusiness' => array(
                        'label'       => 'HVAC Business',
                        'description' => 'A business specializing in heating, ventilation, and air conditioning systems.',
                        'group'       => 'none',
                        'fields'      => array(),
                    ),
                    'HousePainter' => array(
                        'label'       => 'House Painter',
                        'description' => 'A business providing interior and exterior painting services.',
                        'group'       => 'none',
                        'fields'      => array(),
                    ),
                    'Locksmith' => array(
                        'label'       => 'Locksmith',
                        'description' => 'A locksmith providing lock installation, repair, and emergency entry services.',
                        'group'       => 'none',
                        'fields'      => array(),
                    ),
                    'MovingCompany' => array(
                        'label'       => 'Moving Company',
                        'description' => 'A business providing residential or commercial moving and relocation services.',
                        'group'       => 'none',
                        'fields'      => array(),
                    ),
                    'Plumber' => array(
                        'label'       => 'Plumber',
                        'description' => 'A plumbing business providing installation, repair, and maintenance of plumbing systems.',
                        'group'       => 'none',
                        'fields'      => array(),
                    ),
                    'RoofingContractor' => array(
                        'label'       => 'Roofing Contractor',
                        'description' => 'A contractor specializing in roof installation, repair, and replacement.',
                        'group'       => 'none',
                        'fields'      => array(),
                    ),
                ),
            ),
            'Hotel' => array(
                'label'       => 'Hotel',
                'description' => 'A hotel or other paid lodging establishment.',
                'group'       => 'lodging',
                'fields'      => array('checkinTime', 'checkoutTime', 'numberOfRooms', 'petsAllowed', 'starRating'),
            ),
            'InternetCafe' => array(
                'label'       => 'Internet Cafe',
                'description' => 'An establishment providing public internet access and related services.',
                'group'       => 'none',
                'fields'      => array(),
            ),
            'LegalService' => array(
                'label'       => 'Legal Service',
                'description' => 'A business providing legal advice or representation such as a law firm.',
                'group'       => 'none',
                'fields'      => array(),
                'subtypes'    => array(
                    'Attorney' => array(
                        'label'       => 'Attorney',
                        'description' => 'A law office or attorney providing legal representation and advice.',
                        'group'       => 'none',
                        'fields'      => array(),
                    ),
                    'Notary' => array(
                        'label'       => 'Notary',
                        'description' => 'A notary public providing document authentication and notarization services.',
                        'group'       => 'none',
                        'fields'      => array(),
                    ),
                ),
            ),
            'Library' => array(
                'label'       => 'Library',
                'description' => 'A library that lends books, media, and other materials to the public.',
                'group'       => 'none',
                'fields'      => array(),
            ),
            'LodgingBusiness' => array(
                'label'       => 'Lodging Business',
                'description' => 'A lodging business such as a motel, hostel, or bed-and-breakfast.',
                'group'       => 'lodging',
                'fields'      => array('checkinTime', 'checkoutTime', 'numberOfRooms', 'petsAllowed', 'starRating'),
                'subtypes'    => array(
                    'BedAndBreakfast' => array(
                        'label'       => 'Bed & Breakfast',
                        'description' => 'A small lodging establishment offering overnight stays with breakfast included.',
                        'group'       => 'lodging',
                        'fields'      => array('checkinTime', 'checkoutTime', 'numberOfRooms', 'petsAllowed', 'starRating'),
                    ),
                    'Campground' => array(
                        'label'       => 'Campground',
                        'description' => 'A campground or camping site offering outdoor accommodation.',
                        'group'       => 'lodging',
                        'fields'      => array('checkinTime', 'checkoutTime', 'petsAllowed'),
                    ),
                    'Hostel' => array(
                        'label'       => 'Hostel',
                        'description' => 'A hostel providing budget accommodation, often with shared facilities.',
                        'group'       => 'lodging',
                        'fields'      => array('checkinTime', 'checkoutTime', 'numberOfRooms', 'petsAllowed', 'starRating'),
                    ),
                    'Motel' => array(
                        'label'       => 'Motel',
                        'description' => 'A roadside motel providing lodging with convenient parking.',
                        'group'       => 'lodging',
                        'fields'      => array('checkinTime', 'checkoutTime', 'numberOfRooms', 'petsAllowed', 'starRating'),
                    ),
                    'Resort' => array(
                        'label'       => 'Resort',
                        'description' => 'A resort offering accommodation along with recreational amenities and activities.',
                        'group'       => 'lodging',
                        'fields'      => array('checkinTime', 'checkoutTime', 'numberOfRooms', 'petsAllowed', 'starRating'),
                    ),
                    'VacationRental' => array(
                        'label'       => 'Vacation Rental',
                        'description' => 'A property rented on a short-term basis for vacation or holiday stays.',
                        'group'       => 'lodging',
                        'fields'      => array('checkinTime', 'checkoutTime', 'numberOfRooms', 'petsAllowed'),
                    ),
                ),
            ),
            'MedicalBusiness' => array(
                'label'       => 'Medical Business',
                'description' => 'A medical organization or practice such as a hospital, pharmacy, or health system.',
                'group'       => 'medical',
                'fields'      => array('medicalSpecialty', 'availableService'),
                'subtypes'    => array(
                    'CommunityHealth' => array(
                        'label'       => 'Community Health',
                        'description' => 'A community health center providing primary care to a local community.',
                        'group'       => 'medical',
                        'fields'      => array('medicalSpecialty', 'availableService'),
                    ),
                    'Dermatology' => array(
                        'label'       => 'Dermatology',
                        'description' => 'A dermatology practice specializing in skin, hair, and nail conditions.',
                        'group'       => 'medical',
                        'fields'      => array('medicalSpecialty', 'availableService'),
                    ),
                    'DietNutrition' => array(
                        'label'       => 'Diet & Nutrition',
                        'description' => 'A practice or clinic specializing in dietary advice and nutritional services.',
                        'group'       => 'medical',
                        'fields'      => array('medicalSpecialty', 'availableService'),
                    ),
                    'Emergency' => array(
                        'label'       => 'Emergency (Medical)',
                        'description' => 'An emergency medical care provider or emergency room.',
                        'group'       => 'medical',
                        'fields'      => array('medicalSpecialty', 'availableService'),
                    ),
                    'Geriatric' => array(
                        'label'       => 'Geriatric Care',
                        'description' => 'A medical practice specializing in the care of elderly patients.',
                        'group'       => 'medical',
                        'fields'      => array('medicalSpecialty', 'availableService'),
                    ),
                    'Gynecologic' => array(
                        'label'       => 'Gynecology',
                        'description' => 'A gynecology practice providing care for the female reproductive system.',
                        'group'       => 'medical',
                        'fields'      => array('medicalSpecialty', 'availableService'),
                    ),
                    'Midwifery' => array(
                        'label'       => 'Midwifery',
                        'description' => 'A midwifery service providing prenatal, labor, and postnatal care.',
                        'group'       => 'medical',
                        'fields'      => array('medicalSpecialty', 'availableService'),
                    ),
                    'Nursing' => array(
                        'label'       => 'Nursing',
                        'description' => 'A nursing facility or nursing care service provider.',
                        'group'       => 'medical',
                        'fields'      => array('medicalSpecialty', 'availableService'),
                    ),
                    'Obstetric' => array(
                        'label'       => 'Obstetrics',
                        'description' => 'A medical practice specializing in pregnancy, childbirth, and postpartum care.',
                        'group'       => 'medical',
                        'fields'      => array('medicalSpecialty', 'availableService'),
                    ),
                    'Oncologic' => array(
                        'label'       => 'Oncology',
                        'description' => 'A medical practice specializing in the diagnosis and treatment of cancer.',
                        'group'       => 'medical',
                        'fields'      => array('medicalSpecialty', 'availableService'),
                    ),
                    'Optician' => array(
                        'label'       => 'Optician',
                        'description' => 'A business providing eyewear fitting, dispensing, and lens services.',
                        'group'       => 'medical',
                        'fields'      => array('availableService'),
                    ),
                    'Optometric' => array(
                        'label'       => 'Optometry',
                        'description' => 'An optometry practice providing eye exams and vision care services.',
                        'group'       => 'medical',
                        'fields'      => array('medicalSpecialty', 'availableService'),
                    ),
                    'Otolaryngologic' => array(
                        'label'       => 'Otolaryngology (ENT)',
                        'description' => 'A medical practice specializing in ear, nose, and throat conditions.',
                        'group'       => 'medical',
                        'fields'      => array('medicalSpecialty', 'availableService'),
                    ),
                    'Pediatric' => array(
                        'label'       => 'Pediatrics',
                        'description' => 'A medical practice specializing in the care of infants, children, and adolescents.',
                        'group'       => 'medical',
                        'fields'      => array('medicalSpecialty', 'availableService'),
                    ),
                    'Pharmacy' => array(
                        'label'       => 'Pharmacy',
                        'description' => 'A pharmacy dispensing prescription and over-the-counter medications.',
                        'group'       => 'medical',
                        'fields'      => array('availableService'),
                    ),
                    'Physician' => array(
                        'label'       => 'Physician',
                        'description' => 'A physician or medical practice providing consultations and treatment.',
                        'group'       => 'medical',
                        'fields'      => array('medicalSpecialty', 'availableService'),
                    ),
                    'Physiotherapy' => array(
                        'label'       => 'Physiotherapy',
                        'description' => 'A physiotherapy or physical therapy practice aiding recovery and mobility.',
                        'group'       => 'medical',
                        'fields'      => array('medicalSpecialty', 'availableService'),
                    ),
                    'PlasticSurgery' => array(
                        'label'       => 'Plastic Surgery',
                        'description' => 'A medical practice specializing in reconstructive or cosmetic surgical procedures.',
                        'group'       => 'medical',
                        'fields'      => array('medicalSpecialty', 'availableService'),
                    ),
                    'Podiatric' => array(
                        'label'       => 'Podiatry',
                        'description' => 'A podiatry practice specializing in the diagnosis and treatment of foot conditions.',
                        'group'       => 'medical',
                        'fields'      => array('medicalSpecialty', 'availableService'),
                    ),
                    'PrimaryCare' => array(
                        'label'       => 'Primary Care',
                        'description' => 'A primary care practice providing general health care and preventive services.',
                        'group'       => 'medical',
                        'fields'      => array('medicalSpecialty', 'availableService'),
                    ),
                    'Psychiatric' => array(
                        'label'       => 'Psychiatry',
                        'description' => 'A psychiatric practice providing mental health diagnosis and treatment.',
                        'group'       => 'medical',
                        'fields'      => array('medicalSpecialty', 'availableService'),
                    ),
                    'PublicHealth' => array(
                        'label'       => 'Public Health',
                        'description' => 'A public health organization providing community health programs and services.',
                        'group'       => 'medical',
                        'fields'      => array('medicalSpecialty', 'availableService'),
                    ),
                ),
            ),
            'MedicalClinic' => array(
                'label'       => 'Medical Clinic',
                'description' => 'A clinic providing outpatient medical care services.',
                'group'       => 'medical',
                'fields'      => array('medicalSpecialty', 'availableService'),
            ),
            'ProfessionalService' => array(
                'label'       => 'Professional Service',
                'description' => 'A business providing professional services such as accounting, consulting, or marketing.',
                'group'       => 'none',
                'fields'      => array(),
            ),
            'RadioStation' => array(
                'label'       => 'Radio Station',
                'description' => 'A radio broadcasting station.',
                'group'       => 'none',
                'fields'      => array(),
            ),
            'RealEstateAgent' => array(
                'label'       => 'Real Estate Agent',
                'description' => 'A real estate agency or individual agent facilitating property transactions.',
                'group'       => 'none',
                'fields'      => array(),
            ),
            'RecyclingCenter' => array(
                'label'       => 'Recycling Center',
                'description' => 'A facility that accepts and processes recyclable materials.',
                'group'       => 'none',
                'fields'      => array(),
            ),
            'Restaurant' => array(
                'label'       => 'Restaurant',
                'description' => 'A food establishment primarily serving prepared meals on-site.',
                'group'       => 'food',
                'fields'      => array('servesCuisine', 'hasMenu', 'acceptsReservations'),
            ),
            'SelfStorage' => array(
                'label'       => 'Self Storage',
                'description' => 'A facility where individuals or businesses can rent storage units.',
                'group'       => 'none',
                'fields'      => array(),
            ),
            'ShoppingCenter' => array(
                'label'       => 'Shopping Center',
                'description' => 'A shopping mall or shopping center containing multiple retail stores.',
                'group'       => 'none',
                'fields'      => array(),
            ),
            'SportsActivityLocation' => array(
                'label'       => 'Sports Activity Location',
                'description' => 'A venue for sports or recreational activities such as a gym, golf course, or bowling alley.',
                'group'       => 'none',
                'fields'      => array(),
                'subtypes'    => array(
                    'BowlingAlley' => array(
                        'label'       => 'Bowling Alley',
                        'description' => 'A facility providing bowling lanes and related recreational activities.',
                        'group'       => 'none',
                        'fields'      => array(),
                    ),
                    'ExerciseGym' => array(
                        'label'       => 'Exercise Gym',
                        'description' => 'A gym providing exercise equipment and fitness facilities.',
                        'group'       => 'none',
                        'fields'      => array(),
                    ),
                    'GolfCourse' => array(
                        'label'       => 'Golf Course',
                        'description' => 'A golf course or golf club offering play and related facilities.',
                        'group'       => 'none',
                        'fields'      => array(),
                    ),
                    'PublicSwimmingPool' => array(
                        'label'       => 'Public Swimming Pool',
                        'description' => 'A public swimming pool or aquatic facility.',
                        'group'       => 'none',
                        'fields'      => array(),
                    ),
                    'SkiResort' => array(
                        'label'       => 'Ski Resort',
                        'description' => 'A ski resort offering skiing, snowboarding, and alpine recreation.',
                        'group'       => 'none',
                        'fields'      => array(),
                    ),
                    'SportsClub' => array(
                        'label'       => 'Sports Club',
                        'description' => 'A sports club hosting athletic activities, leagues, and training programs.',
                        'group'       => 'none',
                        'fields'      => array(),
                    ),
                    'StadiumOrArena' => array(
                        'label'       => 'Stadium / Arena',
                        'description' => 'A stadium or arena hosting sports events and large-scale performances.',
                        'group'       => 'none',
                        'fields'      => array(),
                    ),
                    'TennisComplex' => array(
                        'label'       => 'Tennis Complex',
                        'description' => 'A tennis facility with courts and related amenities.',
                        'group'       => 'none',
                        'fields'      => array(),
                    ),
                ),
            ),
            'Store' => array(
                'label'       => 'Store',
                'description' => 'A retail store selling physical goods.',
                'group'       => 'none',
                'fields'      => array(),
                'subtypes'    => array(
                    'BikeStore' => array(
                        'label'       => 'Bike Store',
                        'description' => 'A retail store selling bicycles, parts, and accessories.',
                        'group'       => 'none',
                        'fields'      => array(),
                    ),
                    'BookStore' => array(
                        'label'       => 'Book Store',
                        'description' => 'A retail store selling books, magazines, and related media.',
                        'group'       => 'none',
                        'fields'      => array(),
                    ),
                    'ClothingStore' => array(
                        'label'       => 'Clothing Store',
                        'description' => 'A retail store selling clothing and fashion apparel.',
                        'group'       => 'none',
                        'fields'      => array(),
                    ),
                    'ComputerStore' => array(
                        'label'       => 'Computer Store',
                        'description' => 'A retail store selling computers, software, and technology products.',
                        'group'       => 'none',
                        'fields'      => array(),
                    ),
                    'ConvenienceStore' => array(
                        'label'       => 'Convenience Store',
                        'description' => 'A small retail store open extended hours offering everyday goods and snacks.',
                        'group'       => 'none',
                        'fields'      => array(),
                    ),
                    'DepartmentStore' => array(
                        'label'       => 'Department Store',
                        'description' => 'A large retail establishment offering a wide range of goods across departments.',
                        'group'       => 'none',
                        'fields'      => array(),
                    ),
                    'ElectronicsStore' => array(
                        'label'       => 'Electronics Store',
                        'description' => 'A store specializing in consumer electronics and related accessories.',
                        'group'       => 'none',
                        'fields'      => array(),
                    ),
                    'Florist' => array(
                        'label'       => 'Florist',
                        'description' => 'A florist shop selling fresh flowers, plants, and floral arrangements.',
                        'group'       => 'none',
                        'fields'      => array(),
                    ),
                    'FurnitureStore' => array(
                        'label'       => 'Furniture Store',
                        'description' => 'A store selling furniture and home furnishings.',
                        'group'       => 'none',
                        'fields'      => array(),
                    ),
                    'GardenStore' => array(
                        'label'       => 'Garden Store',
                        'description' => 'A store selling garden supplies, plants, and outdoor living products.',
                        'group'       => 'none',
                        'fields'      => array(),
                    ),
                    'GroceryStore' => array(
                        'label'       => 'Grocery Store',
                        'description' => 'A retail store selling food, beverages, and household consumables.',
                        'group'       => 'none',
                        'fields'      => array(),
                    ),
                    'HardwareStore' => array(
                        'label'       => 'Hardware Store',
                        'description' => 'A store selling tools, hardware, and home improvement supplies.',
                        'group'       => 'none',
                        'fields'      => array(),
                    ),
                    'HobbyShop' => array(
                        'label'       => 'Hobby Shop',
                        'description' => 'A specialty store selling hobby supplies, games, and collectibles.',
                        'group'       => 'none',
                        'fields'      => array(),
                    ),
                    'HomeGoodsStore' => array(
                        'label'       => 'Home Goods Store',
                        'description' => 'A store selling household goods, kitchenware, and home accessories.',
                        'group'       => 'none',
                        'fields'      => array(),
                    ),
                    'JewelryStore' => array(
                        'label'       => 'Jewelry Store',
                        'description' => 'A retailer specializing in jewelry, watches, and precious items.',
                        'group'       => 'none',
                        'fields'      => array(),
                    ),
                    'LiquorStore' => array(
                        'label'       => 'Liquor Store',
                        'description' => 'A store selling alcoholic beverages for off-premise consumption.',
                        'group'       => 'none',
                        'fields'      => array(),
                    ),
                    'MensClothingStore' => array(
                        'label'       => 'Men\'s Clothing Store',
                        'description' => 'A retail store specializing in men\'s clothing and fashion.',
                        'group'       => 'none',
                        'fields'      => array(),
                    ),
                    'MobilePhoneStore' => array(
                        'label'       => 'Mobile Phone Store',
                        'description' => 'A retail store selling mobile phones, plans, and accessories.',
                        'group'       => 'none',
                        'fields'      => array(),
                    ),
                    'MovieRentalStore' => array(
                        'label'       => 'Movie Rental Store',
                        'description' => 'A store that rents or sells movies and home video content.',
                        'group'       => 'none',
                        'fields'      => array(),
                    ),
                    'MusicStore' => array(
                        'label'       => 'Music Store',
                        'description' => 'A store selling musical instruments, recordings, and music accessories.',
                        'group'       => 'none',
                        'fields'      => array(),
                    ),
                    'OfficeEquipmentStore' => array(
                        'label'       => 'Office Equipment Store',
                        'description' => 'A store selling office equipment, supplies, and business products.',
                        'group'       => 'none',
                        'fields'      => array(),
                    ),
                    'OutletStore' => array(
                        'label'       => 'Outlet Store',
                        'description' => 'A discount retail store selling overstock or past-season merchandise.',
                        'group'       => 'none',
                        'fields'      => array(),
                    ),
                    'PawnShop' => array(
                        'label'       => 'Pawn Shop',
                        'description' => 'A pawn shop offering loans against collateral and reselling pledged goods.',
                        'group'       => 'none',
                        'fields'      => array(),
                    ),
                    'PetStore' => array(
                        'label'       => 'Pet Store',
                        'description' => 'A store selling pets, pet supplies, and related products.',
                        'group'       => 'none',
                        'fields'      => array(),
                    ),
                    'ShoeStore' => array(
                        'label'       => 'Shoe Store',
                        'description' => 'A retail store specializing in footwear and shoe accessories.',
                        'group'       => 'none',
                        'fields'      => array(),
                    ),
                    'SportingGoodsStore' => array(
                        'label'       => 'Sporting Goods Store',
                        'description' => 'A store selling sports equipment, athletic wear, and outdoor gear.',
                        'group'       => 'none',
                        'fields'      => array(),
                    ),
                    'TireShop' => array(
                        'label'       => 'Tire Shop',
                        'description' => 'A shop selling and installing tires and related automotive services.',
                        'group'       => 'none',
                        'fields'      => array(),
                    ),
                    'ToyStore' => array(
                        'label'       => 'Toy Store',
                        'description' => 'A retail store selling toys, games, and children\'s products.',
                        'group'       => 'none',
                        'fields'      => array(),
                    ),
                    'WholesaleStore' => array(
                        'label'       => 'Wholesale Store',
                        'description' => 'A wholesale retailer selling large quantities of goods at reduced prices.',
                        'group'       => 'none',
                        'fields'      => array(),
                    ),
                ),
            ),
            'TelevisionStation' => array(
                'label'       => 'Television Station',
                'description' => 'A television broadcasting station.',
                'group'       => 'none',
                'fields'      => array(),
            ),
            'TouristInformationCenter' => array(
                'label'       => 'Tourist Information Center',
                'description' => 'A center that provides travel information and assistance to visitors.',
                'group'       => 'none',
                'fields'      => array(),
            ),
            'TravelAgency' => array(
                'label'       => 'Travel Agency',
                'description' => 'A business that arranges travel, accommodation, and related services for customers.',
                'group'       => 'none',
                'fields'      => array(),
            ),
        );
    }

    public static function get_type_keys() {
        return array_keys(self::get_flat_types());
    }

    public static function get_flat_types() {
        $flat = array();
        $types = self::get_types();
        foreach ($types as $key => $type) {
            $flat[$key] = $type;
            if (isset($type['subtypes']) && is_array($type['subtypes'])) {
                foreach ($type['subtypes'] as $subkey => $subtype) {
                    $flat[$subkey] = $subtype;
                }
            }
        }
        return $flat;
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
