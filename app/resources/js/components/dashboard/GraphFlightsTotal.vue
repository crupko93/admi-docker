<script>
import { HorizontalBar } from 'vue-chartjs';

export default {
    name: 'GraphFlightsTotal',

    extends: HorizontalBar,

    props: {
        data: { type: Array, required: true }
    },

    data: () => ({
        // Chart options
        options: {
            responsive         : true,
            maintainAspectRatio: false
        }
    }),

    methods: {
        interpretData (data) {
            return {
                labels  : _.map(data, 'display'),
                datasets: [
                    {
                        label          : 'Total Flights',
                        data           : _.map(data, 'count'),
                        backgroundColor: '#38B2AC'
                    }
                ]
            };
        },

        refreshChart () {
            this.renderChart(this.interpretData(this.data, this.options));
        }
    },

    watch: {
        data () {
            this.refreshChart();
        }
    },

    mounted () {
        this.addPlugin({
            beforeInit (chart) {
                chart.options.legend = false;
            }
        });
        this.refreshChart();
    }
};
</script>
