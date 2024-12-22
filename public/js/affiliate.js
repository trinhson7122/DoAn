function initChartAffiliate(url) {
    const chartDom = document.getElementById("chartAffiliate");
    const myChart = echarts.init(chartDom);

    ajax(url, "get", {}, function (res) {
        const option = {
            tooltip: {
                trigger: "axis",
                axisPointer: {
                    type: "cross",
                },
                formatter: function (params) {
                    let value = params[0].value; // Lấy giá trị từ tooltip
                    let formattedValue = value + " K"; // Thêm chữ K vào giá trị
                    return (
                        params[0].name +
                        "<br/>" +
                        params[0].marker +
                        "Tổng tiền: " +
                        formattedValue
                    );
                },
            },
            toolbox: {
                show: true,
                feature: {
                    saveAsImage: {},
                },
            },
            xAxis: {
                type: "category",
                data: res.data.x,
            },
            yAxis: {
                type: "value",
                axisLabel: {
                    formatter: "{value}K",
                },
            },
            series: [
                {
                    data: res.data.y,
                    type: "bar",
                    name: "Tổng tiền",
                    color: "#8EB486",
                },
            ],
        };
        myChart.setOption(option);
    });

    $(window).on("resize", function () {
        if (myChart != null && myChart != undefined) {
            myChart.resize();
        }
    });
}
