<?php

namespace App\Http\Controllers;

use GeoIp2\Database\Reader;
use Illuminate\Http\Request;

class ToolsController extends Controller
{
    public function ipcheck(Request $request)
    {
        if (!isset($request->IPAddressTXT)){
            return view('tools/iplookup');
        }

        $dbugmode = 0;

        $lists = [];
        $sorted_list = [];
        $input = explode(PHP_EOL, $request->IPAddressTXT);

        require_once '../vendor/autoload.php';

        $readercont = new Reader('../GeoIP/GeoLite2-Country.mmdb');
        $readercity = new Reader('../GeoIP/GeoLite2-City.mmdb');
        $readerasn = new Reader('../GeoIP/GeoLite2-ASN.mmdb');

        if ($dbugmode == 1) {
            var_dump($input);
        }

        for($i = 0; $i < count($input); $i++) {
            array_push($lists, array_filter(explode(" ", $input[$i])));
        }

        for($i = 0; $i < count($lists); $i++) {
            if($lists[$i] != NULL){
                array_push($sorted_list, array_values($lists[$i]));
            }
        }

        for ($i = 0; $i < count($sorted_list); $i++) {
            if (count($sorted_list[$i]) == 1) {
                $ip = $sorted_list[$i][0];
                $ip = rtrim($ip);
                try {
                    $country = $readercont->country($ip);
                    $city = $readercity->city($ip);
                    $isp = $readerasn->asn($ip);
                } catch (\Exception $e) {
                    if ($dbugmode == 1) {
                        echo $e;
                    }
                    continue;
                }
                $result[$i]['ip'] = $ip;

                try {
                    $result[$i]['country'] = ($country->country->name);
                } catch (\Exception $e) {
                    echo 'Caught!';
                    continue;
                }
                try {
                    $result[$i]['city'] = ($city->city->name);
                } catch (\Exception $e) {
                    echo 'Caught!';
                    continue;
                }
                try {
                    $result[$i]['continent'] = ($country->continent->name);
                } catch (\Exception $e) {
                    echo 'Caught!';
                    continue;
                }
                try {
                    $result[$i]['isp'] =  ($isp->autonomousSystemOrganization);
                } catch (\Exception $e) {
                    echo 'Caught!';
                    continue;
                }
            }
        }
    return view('tools/iplookup')->with(['results' => $result]);
    }
}
