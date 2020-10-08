<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ToolsController extends Controller
{
    public function ipcheck(Request $request) {
        //dd($request->IPAddressTXT);

        $lists = [];
        $sorted_list = [];
        $input = explode(PHP_EOL, $request->IPAddressTXT);

        dd($input);
/*
        $dbugmode = 0;
        require_once '/home/nicholasc/public_html/vendor/autoload.php';
        use GeoIp2\Database\Reader;

        $readercont = new Reader('/usr/local/share/GeoIP/GeoLite2-Country.mmdb');
        $readercity = new Reader('/usr/local/share/GeoIP/GeoLite2-City.mmdb');
        $readerasn = new Reader('/usr/local/share/GeoIP/GeoLite2-ASN.mmdb');

        //check if form was submitted
        if(isset($_POST['SubmitButton'])){
            //get input text
            $lists = [];
            $sorted_list = [];
            $input = explode(PHP_EOL, $_POST['IPAddressTXT']);
            if ($dbugmode == 1) {
                var_dump($input);
            }
            for($i = 0; $i <= count($input) - 1; $i++) {
                array_push($lists, array_filter(explode(" ", $input[$i])));
            }
            for($i = 0; $i <= count($lists); $i++) {
                if($lists[$i] != NULL){
                    array_push($sorted_list, array_values($lists[$i]));
                }
            }
            //output array
            for ($i = 0; $i < count($sorted_list); $i++){
            if (count($sorted_list[$i]) == 1) {
                $ip = $sorted_list[$i][0];
                $ip = rtrim($ip);
                #echo rtrim($ip);
                try {
                    $country = $readercont->country($ip);
                    $city = $readercity->city($ip);
                    $isp = $readerasn->asn($ip);
                } catch (Exception $e) {
                    if ($dbugmode == 1) {
                        echo $e;
                    }
                    continue;
                }
                echo "<tr class='bg-gray-700 odd:bg-gray-800 hover:bg-gray-600'>";
                echo "<td class='border-b-2 border-gray-200 text-gray-300 px-2 py-1'>";
                    echo $ip;
                echo "</td>";
                echo "<td class='border-b-2 border-gray-200 text-gray-300 px-2 py-1'>";
                try {
                    print ($country->country->name);
                } catch (Exception $e) {
                    echo 'Caught!';
                }
                echo "</td>";
                echo "<td class='border-b-2 border-gray-200 text-gray-300 px-2 py-1'>";
                try {
                    print ($city->city->name);
                } catch (Exception $e) {
                    echo 'Caught!';
                }
                echo "</td>";
                echo "<td class='border-b-2 border-gray-200 text-gray-300 px-2 py-1'>";
                try {
                    print ($country->continent->name);
                } catch (Exception $e) {
                    echo 'Caught!';
                }
                echo "</td>";
                echo "<td class='border-b-2 border-gray-200 text-gray-300 px-2 py-1'>";
                try {
                    print ($isp->autonomousSystemOrganization);
                } catch (Exception $e) {
                    echo 'Caught!';
                }
                echo "</td>";
            echo "</tr>";
            }
            }} */
    }
}
