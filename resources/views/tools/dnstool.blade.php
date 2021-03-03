@component('components.master')
<div class="justify-center">
    <h2 class="text-2xl font-bold text-center text-gray-200 mt-4">DNS Lookup</h2>
</div>
<div class="flex-grow">
    <div class="flex">
        <div class="ml-4 w-full mr-8">
            <form method="POST" action="/tools/dnstool/check">
                @csrf
                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <label class="text-xl font-semibold text-center text-white mt-4">Domain:</label><br />
                <input class="rounded bg-gray-700 placeholder-gray-400 w-full pl-2 text-gray-200" type="text" name="DomainTXT" placeholder="Domain" /><br /><br />
                <button type="submit" class="rounded m-4 h-8 mt-0 w-16 hover:bg-gray-100 bg-gray-300">Submit</button><br />
            </form>
            <div class="text-white">
                @if (! empty($result['hookStatus']))
                    <h1> Something broke: {{ $result['hookStatus'] }}</h1>
                @else
                    @if (empty($result))

                    @else
                    <table class="table-auto w-full">
                    <caption class="text-2xl text-center text-gray-200 mb-4 font-bold"> DOMAIN INFO </caption>
                    <thead class="mt-4">
                        <tr class="text-md font-bold text-center text-gray-200 mt-8 bg-gray-600">
                            <th class="px-4 py-2 border-b-2 border-gray-200">WHOIS</th>
                            <th class="px-4 py-2 border-b-2 border-gray-200">Info</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class='bg-gray-700 odd:bg-gray-800 hover:bg-gray-600'>
                            <td class='border-b-2 border-gray-200 text-gray-300 px-2 py-1'>
                                Domain:
                            </td>
                            <td class='border-b-2 border-gray-200 text-gray-300 px-2 py-1'>
                               {{ $result['domain'] ?? 'no domain' }}
                            </td>
                        </tr>

                        <tr class='bg-gray-700 odd:bg-gray-800 hover:bg-gray-600'>
                            <td class='border-b-2 border-gray-200 text-gray-300 px-2 py-1'>
                                Registrar:
                            </td>
                            <td class='border-b-2 border-gray-200 text-gray-300 px-2 py-1'>
                               {{ $result['registrar'] ?? 'no domain' }}
                            </td>
                        </tr>

                        @foreach ($result['status'] as $dnsValue['status'])
                            <tr class='bg-gray-700 odd:bg-gray-800 hover:bg-gray-600'>
                               <td class='border-b-2 border-gray-200 text-gray-300 px-2 py-1'>
                                    Status:
                                </td>
                                <td class='border-b-2 border-gray-200 text-gray-300 px-2 py-1'>
                                   {{ $dnsValue['status'] ?? 'status missing lol' }}
                                </td>
                            </tr>
                        @endforeach

                            <tr class='bg-gray-700 odd:bg-gray-800 hover:bg-gray-600'>
                               <td class='border-b-2 border-gray-200 text-gray-300 px-2 py-1'>
                                    Registrant Name:
                                </td>
                                <td class='border-b-2 border-gray-200 text-gray-300 px-2 py-1'>
                                   {{ $result['registrantName'] ?? 'No Registrant Name' }}
                                </td>
                            </tr>
                            <tr class='bg-gray-700 odd:bg-gray-800 hover:bg-gray-600'>
                               <td class='border-b-2 border-gray-200 text-gray-300 px-2 py-1'>
                                    Eligibility Type:
                                </td>
                                <td class='border-b-2 border-gray-200 text-gray-300 px-2 py-1'>
                                   {{ $result['eligibilityType'] ?? 'No eligibility Type' }}
                                </td>
                            </tr>
                            <tr class='bg-gray-700 odd:bg-gray-800 hover:bg-gray-600'>
                               <td class='border-b-2 border-gray-200 text-gray-300 px-2 py-1'>
                                    Registrant ID:
                                </td>
                                <td class='border-b-2 border-gray-200 text-gray-300 px-2 py-1'>
                                   {{ $result['eligibilityID'] ?? 'No eligibility ID' }}
                                </td>
                            </tr>

                        @foreach ($result['nameservers'] as $dnsValue['nameservers'])
                            <tr class='bg-gray-700 odd:bg-gray-800 hover:bg-gray-600'>
                               <td class='border-b-2 border-gray-200 text-gray-300 px-2 py-1'>
                                    Nameservers:
                                </td>
                                <td class='border-b-2 border-gray-200 text-gray-300 px-2 py-1'>
                                   {{ $dnsValue['nameservers'] ?? 'nameservers missing lol' }}
                                </td>
                            </tr>
                        @endforeach

                            <tr class='bg-gray-700 odd:bg-gray-800 hover:bg-gray-600'>
                               <td class='border-b-2 border-gray-200 text-gray-300 px-2 py-1'>
                                    Create Date:
                                </td>
                                <td class='border-b-2 border-gray-200 text-gray-300 px-2 py-1'>
                                   {{ $result['createDate'] ?? 'Create date missing lol' }}
                                </td>
                            </tr>
                            <tr class='bg-gray-700 odd:bg-gray-800 hover:bg-gray-600'>
                               <td class='border-b-2 border-gray-200 text-gray-300 px-2 py-1'>
                                    Update Date:
                                </td>
                                <td class='border-b-2 border-gray-200 text-gray-300 px-2 py-1'>
                                   {{ $result['UpdateDate'] ?? 'Update date missing lol' }}
                                </td>
                            </tr>

                            <tr class='bg-gray-700 odd:bg-gray-800 hover:bg-gray-600'>
                               <td class='border-b-2 border-gray-200 text-gray-300 px-2 py-1'>
                                    Expire Date:
                                </td>
                                <td class='border-b-2 border-gray-200 text-gray-300 px-2 py-1'>
                                   {{ $result['expireDate'] ?? 'Expire date missing lol' }}
                                </td>
                            </tr>

                    </tbody>
                    <thead class="mt-4">
                        <tr class="text-md font-bold text-center text-gray-200 mt-8 bg-gray-600">
                            <th class="px-4 py-2 border-b-2 border-gray-200">DNS RECORD</th>
                            <th class="px-4 py-2 border-b-2 border-gray-200">Info</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($result['aRecord'] as $dnsValue['aRecord'])
                            <tr class='bg-gray-700 odd:bg-gray-800 hover:bg-gray-600'>
                               <td class='border-b-2 border-gray-200 text-gray-300 px-2 py-1'>
                                    A Record:
                                </td>
                                <td class='border-b-2 border-gray-200 text-gray-300 px-2 py-1'>
                                   {{ $dnsValue['aRecord']->getValue() ?? '' }}
                                </td>
                            </tr>
                        @endforeach

                        @foreach ($result['aaaaRecord'] as $dnsValue['aaaaRecord'])
                            <tr class='bg-gray-700 odd:bg-gray-800 hover:bg-gray-600'>
                               <td class='border-b-2 border-gray-200 text-gray-300 px-2 py-1'>
                                    AAAA Record:
                                </td>
                                <td class='border-b-2 border-gray-200 text-gray-300 px-2 py-1'>
                                   {{ $dnsValue['aaaaRecord']->getValue() ?? '' }}
                                </td>
                            </tr>
                        @endforeach

                        @foreach ($result['mxRecord'] as $dnsValue['mxRecord'])
                            <tr class='bg-gray-700 odd:bg-gray-800 hover:bg-gray-600'>
                               <td class='border-b-2 border-gray-200 text-gray-300 px-2 py-1'>
                                    MX Record:
                                </td>
                                <td class='border-b-2 border-gray-200 text-gray-300 px-2 py-1'>
                                   {{ $dnsValue['mxRecord']->getValue() ?? '' }} : {{ $dnsValue['mxRecord']->getPrio() ?? '' }}
                                </td>
                            </tr>
                        @endforeach

                        @foreach ($result['txtRecord'] as $dnsValue['txtRecord'])
                            <tr class='bg-gray-700 odd:bg-gray-800 hover:bg-gray-600'>
                               <td class='border-b-2 border-gray-200 text-gray-300 px-2 py-1'>
                                    TXT Record:
                                </td>
                                <td class='border-b-2 border-gray-200 text-gray-300 px-2 py-1'>
                                   {{ $dnsValue['txtRecord']->getValue() ?? '' }}
                                </td>
                            </tr>
                        @endforeach

                        @foreach ($result['soaRecord'] as $dnsValue['soaRecord'])
                            <tr class='bg-gray-700 odd:bg-gray-800 hover:bg-gray-600'>
                               <td class='border-b-2 border-gray-200 text-gray-300 px-2 py-1'>
                                    SOA Record:
                                </td>
                                <td class='border-b-2 border-gray-200 text-gray-300 px-2 py-1'>
                                   {{ $dnsValue['soaRecord']->getValue() ?? '' }}
                                </td>
                            </tr>
                        @endforeach
                    @endif
                @endif
                </table>
            </div>
        </div>
        <div class="w-full">
            <div class=" w-2/5 float-right mr-8 overflow-hidden shadow-lg rounded-lg bg-gray-600 border-r border-b border-l border-gray-400">
                <h1 class=" text-center text-gray-200 text-xl font-bold" >How to use:</h1>
                <p class="t text-md text-gray-400 px-2 py-4 font-semibold"> Enter Domain</p>
                <p class="t text-md text-gray-400 px-2 py-4 font-semibold"> ???</p>
                <p class="t text-md text-gray-400 px-2 py-4 font-semibold"> Profit </p>
            </div>
        </div>
    </div>
</div>
@endcomponent
