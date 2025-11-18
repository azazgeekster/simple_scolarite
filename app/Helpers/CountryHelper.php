<?php

namespace App\Helpers;

class CountryHelper
{
    /**
     * Get list of countries with Morocco first
     *
     * @return array
     */
    public static function getCountries(): array
    {
        return [
            'MA' => 'Morocco / Maroc / المغرب',
            '' => '───────────────',
            'DZ' => 'Algeria / Algérie / الجزائر',
            'TN' => 'Tunisia / Tunisie / تونس',
            'LY' => 'Libya / Libye / ليبيا',
            'MR' => 'Mauritania / Mauritanie / موريتانيا',
            'EG' => 'Egypt / Égypte / مصر',
            '' => '───────────────',
            'AF' => 'Afghanistan',
            'AL' => 'Albania',
            'AE' => 'United Arab Emirates',
            'AR' => 'Argentina',
            'AU' => 'Australia',
            'AT' => 'Austria',
            'BE' => 'Belgium / Belgique',
            'BR' => 'Brazil / Brésil',
            'CA' => 'Canada',
            'CN' => 'China / Chine',
            'CZ' => 'Czech Republic',
            'DE' => 'Germany / Allemagne',
            'DK' => 'Denmark / Danemark',
            'ES' => 'Spain / Espagne',
            'FI' => 'Finland / Finlande',
            'FR' => 'France',
            'GB' => 'United Kingdom / Royaume-Uni',
            'GR' => 'Greece / Grèce',
            'HU' => 'Hungary / Hongrie',
            'ID' => 'Indonesia / Indonésie',
            'IE' => 'Ireland / Irlande',
            'IL' => 'Israel / Israël',
            'IN' => 'India / Inde',
            'IQ' => 'Iraq / العراق',
            'IR' => 'Iran / إيران',
            'IT' => 'Italy / Italie',
            'JO' => 'Jordan / Jordanie / الأردن',
            'JP' => 'Japan / Japon',
            'KR' => 'South Korea / Corée du Sud',
            'KW' => 'Kuwait / Koweït / الكويت',
            'LB' => 'Lebanon / Liban / لبنان',
            'MX' => 'Mexico / Mexique',
            'MY' => 'Malaysia / Malaisie',
            'NL' => 'Netherlands / Pays-Bas',
            'NO' => 'Norway / Norvège',
            'NZ' => 'New Zealand / Nouvelle-Zélande',
            'OM' => 'Oman / عمان',
            'PK' => 'Pakistan',
            'PL' => 'Poland / Pologne',
            'PT' => 'Portugal',
            'QA' => 'Qatar / قطر',
            'RO' => 'Romania / Roumanie',
            'RU' => 'Russia / Russie',
            'SA' => 'Saudi Arabia / Arabie Saoudite / السعودية',
            'SD' => 'Sudan / Soudan / السودان',
            'SE' => 'Sweden / Suède',
            'SG' => 'Singapore / Singapour',
            'SY' => 'Syria / Syrie / سوريا',
            'TH' => 'Thailand / Thaïlande',
            'TR' => 'Turkey / Turquie',
            'UA' => 'Ukraine',
            'US' => 'United States / États-Unis',
            'VN' => 'Vietnam',
            'YE' => 'Yemen / Yémen / اليمن',
            'ZA' => 'South Africa / Afrique du Sud',
        ];
    }

    /**
     * Get list of nationalities with Moroccan first
     *
     * @return array
     */
    public static function getNationalities(): array
    {
        return [
            'MA' => 'Moroccan / Marocain / مغربي',
            '' => '───────────────',
            'DZ' => 'Algerian / Algérien / جزائري',
            'TN' => 'Tunisian / Tunisien / تونسي',
            'LY' => 'Libyan / Libyen / ليبي',
            'MR' => 'Mauritanian / Mauritanien / موريتاني',
            'EG' => 'Egyptian / Égyptien / مصري',
            '' => '───────────────',
            'FR' => 'French / Français',
            'ES' => 'Spanish / Espagnol',
            'DE' => 'German / Allemand',
            'IT' => 'Italian / Italien',
            'GB' => 'British / Britannique',
            'US' => 'American / Américain',
            'CA' => 'Canadian / Canadien',
            'SA' => 'Saudi / Saoudien / سعودي',
            'AE' => 'Emirati / Émirien',
            'QA' => 'Qatari / Qatarien',
            'KW' => 'Kuwaiti / Koweïtien',
            'JO' => 'Jordanian / Jordanien / أردني',
            'LB' => 'Lebanese / Libanais / لبناني',
            'SY' => 'Syrian / Syrien / سوري',
            'IQ' => 'Iraqi / Irakien / عراقي',
            'YE' => 'Yemeni / Yéménite / يمني',
            'OM' => 'Omani / Omanais / عماني',
            'SD' => 'Sudanese / Soudanais / سوداني',
            'OTHER' => 'Other / Autre / أخرى',
        ];
    }
}
