<?php

namespace App\Http\Controllers;

use dacoto\DomainValidator\Validator\Domain;
use GeoIp2\Database\Reader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Iodev\Whois\Exceptions\ConnectionException;
use Iodev\Whois\Exceptions\ServerMismatchException;
use Iodev\Whois\Exceptions\WhoisException;
use Iodev\Whois\Factory;
use Iodev\Whois\Modules\Tld\TldServer;

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

        require_once '../ToolSite/vendor/autoload.php';

        $readercont = new Reader('../ToolSite/GeoIP/GeoLite2-Country.mmdb');
        $readercity = new Reader('../ToolSite/GeoIP/GeoLite2-City.mmdb');
        $readerasn = new Reader('../ToolSite/GeoIP/GeoLite2-ASN.mmdb');

        if ($dbugmode == 1) {
            var_dump($input);
        }

        for ($i = 0; $i < count($input); $i++) {
            array_push($lists, array_filter(explode(' ', $input[$i])));
        }

        for ($i = 0; $i < count($lists); $i++) {
            if ($lists[$i] != null) {
                array_push($sorted_list, array_values($lists[$i]));
            }
        }

        for ($i = 0; $i < count($sorted_list); $i++) {
            if (count($sorted_list[$i]) == 1) {
                $ip = $sorted_list[$i][0];
                $ip = rtrim($ip);
                try {
                    $ip = gethostbyname($ip);
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
                    $result[$i]['isp'] = ($isp->autonomousSystemOrganization);
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

        require_once '../ToolSite/vendor/autoload.php';

        $readercont = new Reader('../ToolSite/GeoIP/GeoLite2-Country.mmdb');
        $readercity = new Reader('../ToolSite/GeoIP/GeoLite2-City.mmdb');
        $readerasn = new Reader('../ToolSite/GeoIP/GeoLite2-ASN.mmdb');

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
                    $ip = gethostbyname($ip);
                    $country = $readercont->country($ip);
                    $city = $readercity->city($ip);
                    $isp = $readerasn->asn($ip);

                    $name = $country->country->name;

                    if (in_array($name, [$au, $nz])) {
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
                    $result[$i]['isp'] = ($isp->autonomousSystemOrganization);
                } catch (\Exception $e) {
                    echo 'Caught!';
                    continue;
                }
                $i++;
            }
        }

        return view('tools/bulkip')->with(['results' => $result ?? []]);
    }

    public function bulkscreen(Request $request)
    {
        if (!isset($request->syncCommandsTXT)) {
            return view('tools/bulkscreen');
        }

        $input = explode(PHP_EOL, $request->syncCommandsTXT);

        for ($i = 0; $i < count($input); $i++) {
            preg_match('/--user1.\w{1,}.\w{1,}/', $input[$i], $screenNames);

            if (empty($screenNames[0])) {
                continue;
            }

            $screenNames[0] = str_replace('--user1 ', '', $screenNames[0]);
            $screenNames[0] = str_replace('@', '_', $screenNames[0]);
            $result['screenR'][$i] = "screen -r $screenNames[0]" . "\n";

            $syncCommands[$i] = rtrim($input[$i]);
            $syncCommands[$i] = str_replace("'", '"', $syncCommands[$i]);
            $result['screenS'][$i] = "screen -dmS $screenNames[0] bash -c '$syncCommands[$i]; exec bash'" . "\n";
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

        for ($i = 0; $i < count($inputDN); $i++) {
            $host[$i] = "$inputIP $inputDN[$i] www.$inputDN[$i]" . "\n";
        }

        $result = implode('', $host);

        return view('tools/hostfile')->with(['results' => $result ?? []]);
    }

    public function o365mxrecords(Request $request)
    {
        if (!isset($request->cPanelUsername)) {
            return view('tools/o365mxrecords');
        }

        if (!isset($request->DomainName)) {
            return view('tools/o365mxrecords');
        }

        $inputCP = $request->cPanelUsername;
        $inputDN = $request->DomainName;

        $microsoftdumb = str_replace('.', '-', $inputDN);

        //output array
        $host[0] = "uapi --user={$inputCP} Email add_mx domain={$inputDN} exchanger={$microsoftdumb}.mail.protection.outlook.com priority=0 | grep 'errors: 1'" . "\n";
        $host[1] = "uapi --user={$inputCP} Email set_always_accept domain={$inputDN} mxcheck=remote | grep 'errors: 1'" . "\n";
        $host[2] = "cpapi2 --user={$inputCP} ZoneEdit add_zone_record domain={$inputDN} name=autodiscover type=CNAME cname=autodiscover.outlook.com |  grep 'already'" . "\n";
        $host[3] = "cpapi2 --user={$inputCP} ZoneEdit add_zone_record domain={$inputDN} name=sip type=CNAME cname=sipdir.online.lync.com |  grep 'already'" . "\n";
        $host[4] = "cpapi2 --user={$inputCP} ZoneEdit add_zone_record domain={$inputDN} name=lyncdiscover type=CNAME cname=webdir.online.lync.com |  grep 'already'" . "\n";
        $host[5] = "cpapi2 --user={$inputCP} ZoneEdit add_zone_record domain={$inputDN} name=enterpriseregistration type=CNAME cname=enterpriseregistration.windows.net |  grep 'already'" . "\n";
        $host[6] = "cpapi2 --user={$inputCP} ZoneEdit add_zone_record domain={$inputDN} name=enterpriseenrollment type=CNAME cname=enterpriseenrollment.manage.microsoft.com |  grep 'already'" . "\n";
        $host[7] = "cpapi2 --user={$inputCP} ZoneEdit add_zone_record domain={$inputDN} name=msoid type=CNAME cname=clientconfig.microsoftonline-p.net |  grep 'already'" . "\n";
        $host[8] = "cpapi2 --user={$inputCP} ZoneEdit add_zone_record domain={$inputDN} name=_sip._tls type=SRV target=sipdir.online.lync.com priority=100 weight=1 port=443 |  grep 'already'" . "\n";
        $host[9] = "cpapi2 --user={$inputCP} ZoneEdit add_zone_record domain={$inputDN} name=_sipfederationtls._tcp type=SRV target=sipfed.online.lync.com priority=100 weight=1 port=5061 |  grep 'already'" . "\n";
        $host[10] = "cpapi2 --user={$inputCP} ZoneEdit add_zone_record domain={$inputDN} name={$inputDN} type=TXT txtdata='v=spf1 include:spf.protection.outlook.com -all' |  echo 'Update Me Manually'" . "\n";
        //paste
        $result = implode('', $host);

        return view('tools/o365mxrecords')->with(['results' => $result ?? []]);
    }

    public function dnsTool(Request $request)
    {
        $request->validate([
            'DomainTXT' => ['required', 'string', new Domain],
        ]);

        $domainFM = $request->DomainTXT;

        // create whois instance
        $whois = Factory::get()->createWhois();

        // Define custom whois host
        $customServers = [
            'love' => new TldServer('.love', 'whois.nic.love', false, Factory::get()->createTldParser()),
        ];

        // Add custom server to existing whois instance
        foreach ($customServers as $server) {
            $whois->getTldModule()->addServers([$server]);
        }

        // dns lookup
        $dnsLookup = new \FrankVanHest\DnsLookup\DnsLookup($domainFM);

        try {
            $info = $whois->loadDomainInfo($domainFM);
            if (!$info) {
                $dnsValue['hookStatus'] = 'Null if domain available';
                exit;
            }

            $dnsValue = [
                'domain' => $info->getData()['domainName'],
                'registrar' => $info->getData()['registrar'],
                'status' => $info->getData()['states'],
                'registrantName' => $info->getExtra()['groups'][0]['Registrant'] ?? '',
                'eligibilityType' => $info->getExtra()['groups'][0]['Eligibility Type'] ?? '',
                'eligibilityID' => $info->getExtra()['groups'][0]['Registrant ID'] ?? '',
                'nameservers' => $info->getExtra()['groups'][0]['Name Server'] ?? '',
                'UpdateDate' => $info->getExtra()['groups'][0]['Updated Date'] ?? '',
                'createDate' => $info->getExtra()['groups'][0]['Creation Date'] ?? '',
                'expireDate' => $info->getData()['expirationDate'] ?? '',
                'aRecord' => $dnsLookup->getRecordsByType('A') ?? '',
                'aaaaRecord' => $dnsLookup->getRecordsByType('AAAA') ?? '',
                'mxRecord' => $dnsLookup->getRecordsByType('MX') ?? '',
                'txtRecord' => $dnsLookup->getRecordsByType('TXT') ?? '',
                'soaRecord' => $dnsLookup->getRecordsByType('SOA') ?? '',
                'hookStatus' => '',
            ];
        } catch (ConnectionException $e) {
            $dnsValue['hookStatus'] = 'Disconnect or connection timeout';
        } catch (ServerMismatchException $e) {
            $dnsValue['hookStatus'] = 'TLD server (.com for google.com) not found in current server hosts';
        } catch (WhoisException $e) {
            $dnsValue['hookStatus'] = "Whois server responded with error '{$e->getMessage()}'";
        }

        return view('tools/dnstool')->with(['result' => $dnsValue ?? []]);
    }
}
