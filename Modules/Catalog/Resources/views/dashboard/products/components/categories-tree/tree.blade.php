@php
    $selectedCats = !empty($selectedCats) ? $selectedCats : []; 
    $id = Str::random(50);
@endphp

<div id="categories-tree"></div>
<div style="display: none">
    {!! field()->multiSelect('category_id' , '' , $treeAllCategories,$selectedCats,['class' => '']) !!}
</div>
    
@push('scripts')
        
    <script>
        let categories = @json($treeMainCategories);
        let selectedCats = @json($selectedCats);
        // prettier-ignore
        const tree = new dhx.Tree("categories-tree", {
			editable: true,
			dragMode: "both",
			checkbox: true
		});
		tree.data.parse(categories);

        if(selectedCats.length)
            selectedCats.map((id) => tree.checkItem(id));
        
        tree.events.on('afterSelect', function () {
			const value = tree.getState();
			let filtered = [];
			
			for(key in value){
				if (value[key].selected != 0) {
					filtered.push(key);
				}
			}
            $('#category_id').val(filtered);
		});
    </script>
@endpush