
<script>


function alertMessage(type,message){

	toastr[type](message);
}
	var menus = {
		"oneThemeLocationNoMenus" : "",
		"moveUp" : "Move up",
		"moveDown" : "Mover down",
		"moveToTop" : "Move top",
		"moveUnder" : "Move under of %s",
		"moveOutFrom" : "Out from under  %s",
		"under" : "Under %s",
		"outFrom" : "Out from %s",
		"menuFocus" : "%1$s. Element menu %2$d of %3$d.",
		"subMenuFocus" : "%1$s. Menu of subelement %2$d of %3$s."
	};
	var arraydata = [];     
	var addcustommenur= '{{ route("haddcustommenu") }}';
	var updateitemr= '{{ route("hupdateitem")}}';
	var generatemenucontrolr= '{{ route("hgeneratemenucontrol") }}';
	var deleteitemmenur= '{{ route("hdeleteitemmenu") }}';
	var deletemenugr= '{{ route("hdeletemenug") }}';
	var createnewmenur= '{{ route("hcreatenewmenu") }}';
	var csrftoken="{{ csrf_token() }}";
	var menuwr = "{{ url()->current() }}";

	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': csrftoken
		}
	});
</script>
<script type="text/javascript" src="/vendor/tocaan-menu/scripts.js"></script>
<script type="text/javascript" src="/vendor/tocaan-menu/scripts2.js"></script>
<script type="text/javascript" src="/vendor/tocaan-menu/menu.js"></script>


<script  src="/dukaan/js/vue@3.2.40/dist/vue.global.js"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>

<script>
    const { createApp } = Vue;
   let app = createApp({
        data() {
            return data;
        },
        mounted() {
            vueMounted();
        },
        created() {
            vueCreated();
        },
        methods: {},
    }).mount('#theApp');

    function redirect(data) {
        if (data['url']) {
            var url = data['url'];

            if (url) {
                if (data['blank'] && data['blank'] == true) {

                    window.open(url, '_blank');
                } else {
                    window.location.replace(url);
                }
            }
        }
    }

    function redirectToUrl(url,blank = false) {
        if (blank) {

            window.open(url, '_blank');
        } else {

            window.location.replace(url);
        }
    }

</script>