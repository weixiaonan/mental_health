<style>
    .input_tit{
        min-width: 30px;
        max-width: 180px;
        text-align: right;
    }
    .input_tit60{
        min-width: 60px;
        max-width: 180px;
        text-align: right;
    }
    .dialog-button{ text-align: center; }
</style>
<div style="padding:5px 0px;margin: 0px 5px;">
    <form id="role_set_tj" method="post">
        <input type="hidden" name="role_id" value="<?=$id?>">
        <input type="hidden" name="role_id_str" id="role_id_str">
        <ul id="role_node_tree" class="easyui-tree"
            url="index.php?d=admin&c=Role&m=role_node&role_id=<?=$id?>" data-options="checkbox:true,lines:true">
        </ul>

        </form>
</div>
<script type="text/javascript">
    function role_getChecked(){
        var nodes = $('#role_node_tree').tree('getChecked',['checked']);
        console.log(nodes);
        var s = '';
        for(var i=0; i<nodes.length; i++){
            var att = nodes[i].attributes;
            if(att != "" && att != undefined){
                //console.info(nodes[i].attributes.id_str);
                if (s != '') s += ',';
                s += nodes[i].attributes.id_str;
            }
        }
        $('#role_id_str').val(s);
    }
</script>