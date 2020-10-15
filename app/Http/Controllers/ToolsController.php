<?php

namespace App\Http\Controllers;

use GeoIp2\Database\Reader;
use Illuminate\Http\Request;

class ToolsController extends Controller
{
    public function ipcheck(Request $request)
    {
        if (!isset($request->IPAddressTXT)) {
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
    return view('tools/iplookup')->with(['results' => $result ?? []]);
    }

    public function bulkip(Request $request)
    {
        if (!isset($request->IPAddressTXT)) {
            return view('tools/bulkip');
        }

        $dbugmode = 0;

        $lists = [];
        $sorted_list = [];
        $input = explode(PHP_EOL, $request->IPAddressTXT);

        $au = $request->australiaCB;
        $nz = $request->NZCB;

        require_once '../vendor/autoload.php';

        $readercont = new Reader('../GeoIP/GeoLite2-Country.mmdb');
        $readercity = new Reader('../GeoIP/GeoLite2-City.mmdb');
        $readerasn = new Reader('../GeoIP/GeoLite2-ASN.mmdb');

        if ($dbugmode == 1) {
            var_dump($input);
        }

        foreach ($input as $line) {
            // Split by space, trim extra spaces
            $rows = array_map('trim', explode(' ', $line));

            // Remove blank items and append to array
            $sorted_list[] = array_values(array_filter($rows));
        }
        $i = 0;
        //output array
        foreach ($sorted_list as $row) {

            if (count($row) == 2) {

                [ $hits, $ip ] = $row;

                try {
                    $country = $readercont->country($ip);
                    $city = $readercity->city($ip);
                    $isp = $readerasn->asn($ip);

                    $name = $country->country->name;

                    if (in_array($name, [ $au, $nz ])) {
                        continue;
                    }
                } catch (\Exception $e) {
                    continue;
                }

                $result[$i]['hits'] = $hits;

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
                $i++;
            }

        }
    //dump($result);
    return view('tools/bulkip')->with(['results' => $result ?? []]);
    }

    public function bulkscreen(Request $request)
    {
        if (!isset($request->syncCommandsTXT)) {
            return view('tools/bulkscreen');
        }

        $input = explode(PHP_EOL, $request->syncCommandsTXT);

        for ($i = 0; $i < count ($input); $i++) {
            
            preg_match('/--user1.\w{1,}.\w{1,}/', $input[$i], $screenNames);

            $screenNames[0] = str_replace("--user1 ", '', $screenNames[0]);
            $screenNames[0] = str_replace('@', '_', $screenNames[0]);
            $result['screenR'][$i] = "screen -r $screenNames[0]" . "\n";

            $syncCommands[$i] = rtrim($input[$i]);
            $syncCommands[$i] = str_replace("'", '"', $syncCommands[$i]);
            $result['screenS'][$i] = "screen -dmS $screenNames[0] bash -c '$syncCommands[$i]; exec bash'"  . "\n";
        }
        return view('tools/bulkscreen')->with(['results' => $result ?? []]);
    }

    public function hostfile(Request $request)
    {
        if (!isset($request->IPAddressTXT)) {
            return view('tools/hostfile');
        }

        if (!isset($request->DomainTXT)) {
            return view('tools/hostfile');
        }

        $inputIP = $request->IPAddressTXT;
        $inputDN = preg_split('/\s+/', $request->DomainTXT);

        for ($i = 0; $i < count ($inputDN); $i++){
            $host[$i] = "$inputIP $inputDN[$i] www.$inputDN[$i]" . "\n";
        }
    
    $result = implode ('', $host);       

    return view('tools/hostfile')->with(['results' => $result ?? []]);
    }
}