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
            <form method="POST" action="/tools/o365mxrecords/check">
                @csrf
                <label class="text-xl font-semibold text-center text-white mt-4">cPanel Username:</label><br />
                <input class="rounded bg-gray-700 placeholder-gray-400 w-full pl-2 text-gray-200" type="text" name="cPanelUsername" placeholder="cPanel Username" /><br />
                <label class="text-xl font-semibold text-center text-white mt-4">Domain: </label><br />
                <input class="rounded bg-gray-700 placeholder-gray-400 w-full pl-2 text-gray-200" type="text" name="DomainName" placeholder="Domain" /><br />
                <button type="submit" class="rounded m-4 h-8 w-16 hover:bg-gray-100 bg-gray-300">Submit</button><br />
            </form>
            <label class="text-xl font-semibold text-center text-white mt-4">API Magic: </label><br />
            <textarea class="rounded bg-gray-700 placeholder-gray-400 w-full pl-2 pt-2 text-gray-200" form="mxbuilder" name="output" id="output" rows="19" placeholder="API Magic">{{ $results ?? '' }}</textarea><br />
            <input class="rounded m-4 h-8 w-24 hover:bg-gray-100 bg-gray-300" type="button" onclick="myFunction()" value="Copy text" />
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
