@component('components.master')
<div class="justify-center">
    <h2 class="text-2xl font-bold text-center text-gray-200 mt-4">Host File Builder</h2>
</div>
<div class="justify-center">
    <h2 class="text-2xl font-bold text-center text-gray-200 mt-4">MX Record Builder</h2>
</div>
<div class="flex-grow">
    <div class="flex">
        <div class="ml-4 w-full mr-8">
        <?php    
            //check if form was submitted
            if(isset($_POST['SubmitButton'])){
                //get input text
               $inputCP = $_POST['cPanelUsername'];
               $inputDN = $_POST['DomainName'];
               $outlookdumb = str_replace(".", '-', $inputDN);
               
               //output array
               $host[0] = "uapi --user=$inputCP Email add_mx domain=$inputDN exchanger=" . $outlookdumb . ".mail.protection.outlook.com priority=0 | grep 'errors: 1'" . "\n";
               $host[1] = "uapi --user=$inputCP Email set_always_accept domain=" . $inputDN . "mxcheck=remote | grep 'errors: 1'" . "\n";
               $host[2] = "cpapi2 --user=$inputCP ZoneEdit add_zone_record domain=$inputDN name=autodiscover type=CNAME cname=autodiscover.outlook.com |  grep 'already'" . "\n";
               $host[3] = "cpapi2 --user=$inputCP ZoneEdit add_zone_record domain=$inputDN name=sip type=CNAME cname=sipdir.online.lync.com |  grep 'already'" . "\n";
               $host[4] = "cpapi2 --user=$inputCP ZoneEdit add_zone_record domain=$inputDN name=lyncdiscover type=CNAME cname=webdir.online.lync.com |  grep 'already'" . "\n";
               $host[5] = "cpapi2 --user=$inputCP ZoneEdit add_zone_record domain=$inputDN name=enterpriseregistration type=CNAME cname=enterpriseregistration.windows.net |  grep 'already'" . "\n";
               $host[6] = "cpapi2 --user=$inputCP ZoneEdit add_zone_record domain=$inputDN name=enterpriseenrollment type=CNAME cname=enterpriseenrollment.manage.microsoft.com |  grep 'already'" . "\n";
               $host[7] = "cpapi2 --user=$inputCP ZoneEdit add_zone_record domain=$inputDN name=msoid type=CNAME cname=clientconfig.microsoftonline-p.net |  grep 'already'" . "\n";
               $host[8] = "cpapi2 --user=$inputCP ZoneEdit add_zone_record domain=$inputDN name=_sip._tls type=SRV target=sipdir.online.lync.com priority=100 weight=1 port=443 |  grep 'already'" . "\n";
               $host[9] = "cpapi2 --user=$inputCP ZoneEdit add_zone_record domain=$inputDN name=_sipfederationtls._tcp type=SRV target=sipfed.online.lync.com priority=100 weight=1 port=5061 |  grep 'already'" . "\n";
               $host[10] = "cpapi2 --user=$inputCP ZoneEdit add_zone_record domain=$inputDN name=$inputDN" . " type=TXT txtdata='v=spf1 include:spf.protection.outlook.com -all' |  echo 'Update Me Manually'" . "\n";
               //paste
               $host = implode ('', $host);
            }
        ?>
        <form action="" method="post" id="mxbuilder">
            <label class="text-xl font-semibold text-center text-white mt-4">cPanel Username:</label><br />
            <input class="rounded bg-gray-700 placeholder-gray-400 w-full pl-2 text-gray-200" type="text" name="cPanelUsername" placeholder="cPanel Username" /><br />
            <label class="text-xl font-semibold text-center text-white mt-4">Domain: </label><br />
            <input class="rounded bg-gray-700 placeholder-gray-400 w-full pl-2 text-gray-200" type="text" name="DomainName" placeholder="Domain" /><br />
            <input class="rounded m-4 h-8 w-16 hover:bg-gray-100 bg-gray-300" type="submit" name="SubmitButton" /><br />
            <label class="text-xl font-semibold text-center text-white mt-4">API Magic: </label><br />
            <textarea class="rounded bg-gray-700 placeholder-gray-400 w-full pl-2 pt-2 text-gray-200" form="mxbuilder" name="output" id="output" rows="6" placeholder="API Magic"> <?php echo $host; ?> </textarea><br />
            <input class="rounded m-4 h-8 w-24 hover:bg-gray-100 bg-gray-300" type="button" onclick="myFunction()" value="Copy text" />
            </form>
            <script>
            function myFunction() {
                var copyText = document.getElementById("output");
                copyText.select();
                document.execCommand("copy");
            }
            </script>
        </div>
        <div class="w-full">
            <div class=" w-2/5 float-right mr-8 overflow-hidden shadow-lg rounded-lg bg-gray-600 border-r border-b border-l border-gray-400">
                <h1 class=" text-center text-gray-200 text-xl font-bold" >How to use:</h1>
                <p class="t text-md text-gray-400 px-2 py-4 font-semibold"> Enter The cPanel username</p>
                <p class="t text-md text-gray-400 px-2 py-4 font-semibold"> Enter domain that you want have Office 365</p>
                <p class="t text-md text-gray-400 px-2 py-4 font-semibold"> ???</p>
                <p class="t text-md text-gray-400 px-2 py-4 font-semibold"> Profit </p>
            </div>
        </div>
    </div>
</div>
@endcomponent
