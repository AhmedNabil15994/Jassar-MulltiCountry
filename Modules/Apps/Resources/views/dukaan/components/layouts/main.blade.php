<!DOCTYPE html>
<html lang="{{locale()}}">
    <x-dukaan-head/>
    <body data-bs-spy="scroll" data-bs-target="#navbar-example2" >
        
        <div id="theApp">
            <x-dukaan-page-loader/>
            <x-dukaan-header/>
            <main>
                @yield('content')
            </main>
        </div>
        <x-dukaan-footer/>
        <x-dukaan-js/>
    </body>
</html>