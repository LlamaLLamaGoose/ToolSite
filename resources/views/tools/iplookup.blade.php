@component('components.master')
<div class="justify-center">
    <h2 class="text-2xl font-bold text-center text-gray-200 mt-4">IP Lookup</h2>
</div>
<div class="flex-grow">
    <div class="flex">
        <div class="ml-4 w-full mr-8">
            <form method="POST" action="/tools/iplookup/check">
                @csrf
                <label class="text-xl font-semibold text-center text-white mt-4">IP List: </label><br />
                <textarea class="rounded bg-gray-700 placeholder-gray-400 w-full pl-2 pt-2 text-gray-200" name="IPAddressTXT" rows="8" placeholder="@include('components\tools\_example_iplookup')" >{{ old('IPAddressTXT') }}</textarea><br />

                <div>
                    <button type="submit" class="rounded m-4 h-8 w-16 hover:bg-gray-100 bg-gray-300">Submit</button>
                </div>
            </form>
            <br />
            <br />
            <table class="table-auto w-full">
                <caption class="text-2xl text-center text-gray-200 mb-4 font-bold"> Bulk IP Lookup </caption>
                <thead class="mt-4">
                    <tr class="text-md font-bold text-center text-gray-200 mt-8 bg-gray-600">
                        <th class="px-4 py-4 rounded-tl-lg border-b-2 border-gray-200">IP Addresses</th>
                        <th class="px-4 py-4 border-b-2 border-gray-200">Country</th>
                        <th class="px-8 py-4 border-b-2 border-gray-200">City</th>
                        <th class="px-4 py-4 border-b-2 border-gray-200">Continent</th>
                        <th class="px-8 py-4 rounded-tr-lg border-b-2 border-gray-200">ISP</th>
                    </tr>
                </thead>
                <tbody>
                    @if (empty($results ?? ''))

                    @else
                        @foreach ($results  as $result)
                        <tr class='bg-gray-700 odd:bg-gray-800 hover:bg-gray-600'>
                            <td class='border-b-2 border-gray-200 text-gray-300 px-2 py-1'>
                                {{ $result['ip'] }}
                            </td>
                            <td class='border-b-2 border-gray-200 text-gray-300 px-2 py-1'>
                                {{ $result['country'] }}
                            </td>
                            <td class='border-b-2 border-gray-200 text-gray-300 px-2 py-1'>
                                {{ $result['city'] }}
                            </td>
                            <td class='border-b-2 border-gray-200 text-gray-300 px-2 py-1'>
                                {{ $result['continent'] }}
                            </td>
                            <td class='border-b-2 border-gray-200 text-gray-300 px-2 py-1'>
                                {{ $result['isp'] }}
                            </td>
                        </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
            <br />
        </div>
        <div class="w-full">
            <div class=" w-2/5 float-right mr-8 overflow-hidden shadow-lg rounded-lg bg-gray-600 border-r border-b border-l border-gray-400">
                <h1 class=" text-center text-gray-200 text-xl font-bold" >How to use:</h1>
                <p class="t text-md text-gray-400 px-2 py-4 font-semibold"> Enter The IP address(s) you want to check in the text area and hit submit.</p>
                <p class="t text-md text-gray-400 px-2 py-4 font-semibold"> If you have multiple IP addresses you'll need to make sure each one is on a new line.</p>
                <p class="t text-md text-gray-400 px-2 py-4 font-semibold"> This will only work on IPv4 addresses and IPv6 addresses aren't included within the database. </p>
            </div>
        </div>
    </div>
</div>
@endcomponent
