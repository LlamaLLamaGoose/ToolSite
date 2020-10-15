@component('components.master')
<div class="justify-center">
    <h2 class="text-2xl font-bold text-center text-gray-200 mt-4">Bulk IMAP Screens</h2>
</div>
<div class="flex-grow">
    <div class="flex">
        <div class="ml-4 w-full mr-8">
            <form method="POST" action="/tools/bulkscreen/check">
                @csrf
                <label class="text-xl font-semibold text-center text-white mt-4">IMAP Sync Commnds:</label><br />
                <textarea class="rounded bg-gray-700 placeholder-gray-400 w-full pl-2 pt-2 text-gray-200 text-xs" name="syncCommandsTXT" rows="16" placeholder="@include('components\tools\_example_bulkscreen')"></textarea><br />
                <button type="submit" class="rounded m-4 h-8 w-16 hover:bg-gray-100 bg-gray-300">Submit</button><br />

                <label class="text-xl font-semibold text-center text-white mt-4">Bulk Commands: </label><br />
                <textarea class="rounded bg-gray-700 placeholder-gray-400 w-full pl-2 pt-2 text-gray-200 text-xs" name="output" id="output" rows="26" placeholder="Bulk Screens">@if(!empty($results)){{ implode($results['screenS']) }}
{{ implode($results['screenR']) }}
                    @endif</textarea><br />

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
                <p class="t text-md text-gray-400 px-2 py-4 font-semibold"> Generate the IMAP commands here: <a href="https://chibi.moe/imapsync/"> Basic IMAPsync Command Generator.</a></p>
                <p class="t text-md text-gray-400 px-2 py-4 font-semibold"> Paste the commands into the textarea and hit submit</p>
                <p class="t text-md text-gray-400 px-2 py-4 font-semibold"> This will generate the commands to create detached screens running bash and the IMAP sync so they don't close afterwards.</p>
                <p class="t text-md text-gray-400 px-2 py-4 font-semibold"> This will also create the reconnect screens based on the email addresses used in the commands.</p>
            </div>
        </div>
    </div>
</div>
@endcomponent
