am5.ready(function () {
    var root = am5.Root.new("status_chart");
    
    root.setThemes([
        am5themes_Animated.new(root)
    ]);
    
    var chart = root.container.children.push(am5percent.PieChart.new(root, {
        layout: root.verticalLayout,
    }));
    
    var series = chart.series.push(am5percent.PieSeries.new(root, {
        valueField: "value",
        categoryField: "category",
        alignLabels: false
    }));

    series.labels.template.adapters.add("y", function (y, target) {
        let dataItem = target.dataItem;
        if (dataItem) {
            let tick = dataItem.get("tick");
            if (tick) {
                    target.set("forceHidden", true);
                    tick.set("forceHidden", true);
            }
            return y;
        }
    });

    $.ajax({
        url: '../admin/pie-chart-data',
        type: 'GET',
        success: function (data) {
            series.data.setAll(data);

            var legend = chart.children.push(am5.Legend.new(root, {
                centerX: am5.percent(50),
                x: am5.percent(50),
                marginTop: 5,
                marginBottom: 15
            }));
            legend.data.setAll(series.dataItems);
        }
    });

    series.appear(1000, 100);

    root._logo.dispose();
});