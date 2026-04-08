<script>
    function getCountryRegion() {
        var id = $("#country_id").val();
        jQuery.ajax({
            type: "GET",
            url: "/location/getCountryRegion/" + id,
            dataType: 'json',
            success: function (data) {
                var div = '';
                $.each(data, function (index, row) {
                    div += "<option value='" + row.id + "'>" + row.name + "</option>";
                });

                $("#region_id").html("<option value=''>Select Region</option>");
                $("#region_id").append(div);
            }
        });
    }

    function getRegionDistrict() {
        var id = $("#region_id").val();
        jQuery.ajax({
            type: "GET",
            url: "/location/getRegionDistrict/" + id,
            dataType: 'json',
            success: function (data) {
                var div = '';
                $.each(data, function (index, row) {
                    div += "<option value='" + row.id + "'>" + row.name + "</option>";
                });

                $("#district_id").html("<option value=''>Select District</option>");
                $("#district_id").append(div);
            }
        });
    }

    function getDistrictWard() {
        var id = $("#district_id").val();
        jQuery.ajax({
            type: "GET",
            url: "/location/getDistrictWard/" + id,
            dataType: 'json',
            success: function (data) {
                var div = '';
                $.each(data, function (index, row) {
                    div += "<option value='" + row.id + "'>" + row.name + "</option>";
                });

                $("#ward_id").html("<option value=''>Select ward</option>");
                $("#ward_id").append(div);
            }
        });
    }
</script>
