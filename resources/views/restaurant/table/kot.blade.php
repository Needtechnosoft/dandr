<script>
    function printKOT(type) {
        if(type==1){

            if(currentAdded.length==0){
                alert('No New Item To Print');
            }else{
                window.open(`{{route('restaurant.kot')}}?table_id=${currentTable.id}${currentAdded.map(o=>'&ids[]='+o.id).join('')}`);
                
            }
        }else{
            datas=[];
            $('input.hasKOT').each(function (index, element) {
                if(element.checked){
                    datas.push(element.value);
                }                
            });
            if(datas.length==0){
                alert("No Data Selected");
                return;
            }
            window.open(`{{route('restaurant.kot')}}?table_id=${currentTable.id}${datas.map(o=>'&ids[]='+o).join('')}`);

        }
    }
</script>