$(function () {
    $(document).ready(function(){
        var dumps;
        $('.general_rubric').typeahead({
            source: function(query, process) {
                objects = [{"id":0,"label":"Категория не найдена"}];
                map = {};
                $.ajax({
                    type: 'POST',
                    url: "/admin/parser/getRubrics",
                    data: [],
                    success: success,
                    error:error,
                    dataType: "json"
                });
                function success(r_data){
                    objects = [];
                    $.each(r_data, function(i, object) {
                        map[object.label] = object;
                        objects.push(object.label);
                    });
                    data = r_data;
                    process(objects);
                }
                function error(r_data){
                    process(objects);
                }
            },
            updater: function(item) {
                $('.general_rubric').attr("data-id",map[item].id);
                return item;
            }
        });
        $('form').submit(function (){
            $.ajax({
                type: 'POST',
                url: "/admin/parser/getFrims",
                data: {
                    "rubric_id": $(".sub_rubric").attr("data-id"),
                },
                success: function (data) {
                    $("table").find("tbody").html(data);
                },
                dataType: "html"
            });
            return false;
        });
        $('.sub_rubric').typeahead({
            source: function(query, process) {
                objects = [{"id":0,"label":"Категория не найдена"}];
                map = {};
                $.ajax({
                    type: 'POST',
                    url: "/admin/parser/getRubrics",
                    data: {
                        "parent_id": $(".general_rubric").attr("data-id"),
                    },
                    success: success,
                    error:error,
                    dataType: "json"
                });
                function success(r_data){
                    objects = [];
                    $.each(r_data, function(i, object) {
                        map[object.label] = object;
                        objects.push(object.label);
                    });
                    data = r_data;
                    process(objects);
                }
                function error(r_data){
                    process(objects);
                }
            },
            updater: function(item) {
                $('.sub_rubric').attr("data-id",map[item].id);
                return item;
            }
        });
    });
  });