<div id="container-{{$id}}" class="chart-container"></div>
<script type="text/javascript">
    Highcharts.chart('container-{{$id}}', {
        chart: {
            type: '{{ $type }}',
            zoomType: 'x',
        },
        @if($title)
        title: {!! json_encode($title) !!} ,
        @endif
            @if($xAxis)
        xAxis: {!! json_encode($xAxis) !!},
        @endif
            @if($yAxis)
        yAxis: {!! json_encode($yAxis) !!},
        @endif
            @if($tooltip)
        tooltip: {!! json_encode($tooltip) !!},
        @endif
            @if($legend)
        legend: {!! json_encode($legend) !!},
        @endif
            @if($plotOptions)
        plotOptions: {!! json_encode($plotOptions) !!},
        @endif
            @if($series)
        series: {!! json_encode($series) !!},
        @endif
    });
</script>

