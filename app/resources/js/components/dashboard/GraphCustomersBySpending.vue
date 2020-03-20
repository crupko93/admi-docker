<script>
import {Pie} from 'vue-chartjs';

export default {
    name: 'GraphCustomersBySpending',

    extends: Pie,

    props: {
        data: {type: Array, required: true}
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
                labels  : _.map(data, 'name'),
                datasets: [
                    {
                        label          : 'Total Customers by Spending',
                        data           : _.map(data, 'count'),
                        backgroundColor: _.map(data, 'color')
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
        this.refreshChart();
    }
};
</script>
