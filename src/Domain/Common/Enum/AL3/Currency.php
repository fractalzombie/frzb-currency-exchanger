<?php

declare(strict_types=1);

/**
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 *
 * Copyright (c) 2023 Mykhailo Shtanko fractalzombie@gmail.com
 *
 * For the full copyright and license information, please view the LICENSE.MD
 * file that was distributed with this source code.
 */

namespace App\Domain\Common\Enum\AL3;

enum Currency: string
{
    case ADBUnitOfAccount = 'XUA';
    case Afghani = 'AFN';
    case AlgerianDinar = 'DZD';
    case ArgentinePeso = 'ARS';
    case ArmenianDram = 'AMD';
    case ArubanFlorin = 'AWG';
    case AustralianDollar = 'AUD';
    case AzerbaijanManat = 'AZN';
    case BahamianDollar = 'BSD';
    case BahrainiDinar = 'BHD';
    case Baht = 'THB';
    case Balboa = 'PAB';
    case BarbadosDollar = 'BBD';
    case BelarusianRuble = 'BYN';
    case BelizeDollar = 'BZD';
    case BermudianDollar = 'BMD';
    case BolivarSoberano = 'VED';
    case BolivarSoberanoOld = 'VES';
    case Boliviano = 'BOB';
    case BondMarketsUnitEuropeanCompositeUnitEURCO = 'XBA';
    case BondMarketsUnitEuropeanMonetaryUnitEMU6 = 'XBB';
    case BondMarketsUnitEuropeanUnitOfAccount17EUA17 = 'XBD';
    case BondMarketsUnitEuropeanUnitOfAccount9EUA9 = 'XBC';
    case BrazilianReal = 'BRL';
    case BruneiDollar = 'BND';
    case BulgarianLev = 'BGN';
    case BurundiFranc = 'BIF';
    case CFAFrancBCEAO = 'XOF';
    case CFAFrancBEAC = 'XAF';
    case CFPFranc = 'XPF';
    case CaboVerdeEscudo = 'CVE';
    case CanadianDollar = 'CAD';
    case CaymanIslandsDollar = 'KYD';
    case ChileanPeso = 'CLP';
    case CodesSpecificallyReservedForTestingPurposes = 'XTS';
    case ColombianPeso = 'COP';
    case ComorianFranc = 'KMF';
    case CongoleseFranc = 'CDF';
    case ConvertibleMark = 'BAM';
    case CordobaOro = 'NIO';
    case CostaRicanColon = 'CRC';
    case CubanPeso = 'CUP';
    case CzechKoruna = 'CZK';
    case Dalasi = 'GMD';
    case DanishKrone = 'DKK';
    case Denar = 'MKD';
    case DjiboutiFranc = 'DJF';
    case Dobra = 'STN';
    case DominicanPeso = 'DOP';
    case Dong = 'VND';
    case EastCaribbeanDollar = 'XCD';
    case EgyptianPound = 'EGP';
    case ElSalvadorColon = 'SVC';
    case EthiopianBirr = 'ETB';
    case Euro = 'EUR';
    case FalklandIslandsPound = 'FKP';
    case FijiDollar = 'FJD';
    case Forint = 'HUF';
    case GhanaCedi = 'GHS';
    case GibraltarPound = 'GIP';
    case Gold = 'XAU';
    case Gourde = 'HTG';
    case Guarani = 'PYG';
    case GuineanFranc = 'GNF';
    case GuyanaDollar = 'GYD';
    case HongKongDollar = 'HKD';
    case Hryvnia = 'UAH';
    case IcelandKrona = 'ISK';
    case IndianRupee = 'INR';
    case IranianRial = 'IRR';
    case IraqiDinar = 'IQD';
    case JamaicanDollar = 'JMD';
    case JordanianDinar = 'JOD';
    case KenyanShilling = 'KES';
    case Kina = 'PGK';
    case KuwaitiDinar = 'KWD';
    case Kwanza = 'AOA';
    case Kyat = 'MMK';
    case LaoKip = 'LAK';
    case Lari = 'GEL';
    case LebanesePound = 'LBP';
    case Lek = 'ALL';
    case Lempira = 'HNL';
    case Leone = 'SLE';
    case LeoneOld = 'SLL';
    case LiberianDollar = 'LRD';
    case LibyanDinar = 'LYD';
    case Lilangeni = 'SZL';
    case Loti = 'LSL';
    case MalagasyAriary = 'MGA';
    case MalawiKwacha = 'MWK';
    case MalaysianRinggit = 'MYR';
    case MauritiusRupee = 'MUR';
    case MexicanPeso = 'MXN';
    case MexicanUnidadDeInversionUDI = 'MXV';
    case MoldovanLeu = 'MDL';
    case MoroccanDirham = 'MAD';
    case MozambiqueMetical = 'MZN';
    case Mvdol = 'BOV';
    case Naira = 'NGN';
    case Nakfa = 'ERN';
    case NamibiaDollar = 'NAD';
    case NepaleseRupee = 'NPR';
    case NetherlandsAntilleanGuilder = 'ANG';
    case NewIsraeliSheqel = 'ILS';
    case NewTaiwanDollar = 'TWD';
    case NewZealandDollar = 'NZD';
    case Ngultrum = 'BTN';
    case NorthKoreanWon = 'KPW';
    case NorwegianKrone = 'NOK';
    case Ouguiya = 'MRU';
    case PaAnga = 'TOP';
    case PakistanRupee = 'PKR';
    case Palladium = 'XPD';
    case Pataca = 'MOP';
    case PesoConvertible = 'CUC';
    case PesoUruguayo = 'UYU';
    case PhilippinePeso = 'PHP';
    case Platinum = 'XPT';
    case PoundSterling = 'GBP';
    case Pula = 'BWP';
    case QatariRial = 'QAR';
    case Quetzal = 'GTQ';
    case Rand = 'ZAR';
    case RialOmani = 'OMR';
    case Riel = 'KHR';
    case RomanianLeu = 'RON';
    case Rufiyaa = 'MVR';
    case Rupiah = 'IDR';
    case RussianRuble = 'RUB';
    case RwandaFranc = 'RWF';
    case SDRSpecialDrawingRight = 'XDR';
    case SaintHelenaPound = 'SHP';
    case SaudiRiyal = 'SAR';
    case SerbianDinar = 'RSD';
    case SeychellesRupee = 'SCR';
    case Silver = 'XAG';
    case SingaporeDollar = 'SGD';
    case Sol = 'PEN';
    case SolomonIslandsDollar = 'SBD';
    case Som = 'KGS';
    case SomaliShilling = 'SOS';
    case Somoni = 'TJS';
    case SouthSudanesePound = 'SSP';
    case SriLankaRupee = 'LKR';
    case Sucre = 'XSU';
    case SudanesePound = 'SDG';
    case SurinamDollar = 'SRD';
    case SwedishKrona = 'SEK';
    case SwissFranc = 'CHF';
    case SyrianPound = 'SYP';
    case Taka = 'BDT';
    case Tala = 'WST';
    case TanzanianShilling = 'TZS';
    case Tenge = 'KZT';
    case TheCodesAssignedForTransactionsWhereNoCurrencyIsInvolved = 'XXX';
    case TrinidadAndTobagoDollar = 'TTD';
    case Tugrik = 'MNT';
    case TunisianDinar = 'TND';
    case TurkishLira = 'TRY';
    case TurkmenistanNewManat = 'TMT';
    case UAEDirham = 'AED';
    case USDollar = 'USD';
    case USDollarNextDay = 'USN';
    case UgandaShilling = 'UGX';
    case UnidadPrevisional = 'UYW';
    case UnidadDeFomento = 'CLF';
    case UnidadDeValorReal = 'COU';
    case UruguayPesoEnUnidadesIndexadasUI = 'UYI';
    case UzbekistanSum = 'UZS';
    case Vatu = 'VUV';
    case WIREuro = 'CHE';
    case WIRFranc = 'CHW';
    case Won = 'KRW';
    case YemeniRial = 'YER';
    case Yen = 'JPY';
    case YuanRenminbi = 'CNY';
    case ZambianKwacha = 'ZMW';
    case ZimbabweDollar = 'ZWL';
    case Zloty = 'PLN';
    case Kuna = 'HRK';
    case Empty = 'EMPTY';

    public function isEmpty(): bool
    {
        return self::Empty === $this;
    }
}
