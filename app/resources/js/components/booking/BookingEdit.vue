<template>
    <v-layout row justify-center>
        <v-flex xs12>
            <v-icon class="mr-2">fas fa-receipt</v-icon>
            Booking B001202
        </v-flex>
        <v-flex xs12 md8>

            <v-stepper non-linear class="mb-5">
                <v-stepper-header>
                    <v-stepper-step
                        editable
                        step="1"
                    >
                        New Ticket
                    </v-stepper-step>

                    <v-divider></v-divider>

                    <v-stepper-step
                        editable
                        step="2"
                    >
                        Validation
                    </v-stepper-step>

                    <v-divider></v-divider>

                    <v-stepper-step
                        step="3"
                        editable
                    >
                        Pre-emission
                    </v-stepper-step>

                    <v-divider></v-divider>

                    <v-stepper-step
                        step="4"
                        editable
                    >
                        Emission
                    </v-stepper-step>

                    <v-divider></v-divider>

                    <v-stepper-step
                        step="5"
                        editable
                    >
                        Notification
                    </v-stepper-step>
                </v-stepper-header>
            </v-stepper>

            <v-row>
                <v-col>
                    <v-btn class="ma-2 float-right" small outlined fab color="grey">
                        <v-icon>mdi-pencil</v-icon>
                    </v-btn>
                </v-col>
            </v-row>

            <v-card class="mb-5">
                <v-card-text class="red white--text">
                    <h2>Action Required</h2>
                    <h4>One price is increased during the pre-emission process:</h4>
                </v-card-text>

                <v-card-text class="grey lighten-4">
                    <h2>Flight MXP-STN</h2>
                    <h4>Price increased from 100.30$ to 130.30$</h4>
                </v-card-text>

                <v-card-actions class="d-sm-inline-flex d-block">
                    <v-btn class="ma-2" outlined color="indigo">Emmit the Ticket</v-btn>
                    <v-btn class="ma-2" outlined color="indigo">Cancel Product</v-btn>
                    <v-btn class="ma-2" outlined color="indigo">Cancel Intire Booking</v-btn>
                    <v-btn class="ma-2" outlined color="indigo">Send a Pay-Link</v-btn>
                </v-card-actions>
            </v-card>

            <v-expansion-panels accordion multiple="true">
                <v-expansion-panel>
                    <v-expansion-panel-header>
                        <h3>Flight</h3>
                    </v-expansion-panel-header>
                    <v-expansion-panel-content>
                        <v-row>
                            <v-col>
                                <h6>Departure</h6>
                                <h1>MXP</h1>
                                <h6>Milan, Malpensa, Italy</h6>
                            </v-col>
                            <v-col class="text-center">
                                <div class="row">
                                    <div class="col-xs-12 col-md-2  text-center mt-md-6">
                                        <h6>12:00</h6>
                                    </div>
                                    <div class="col-xs-12 col-md-8 text-center">
                                        <span class="red--text display-2">&#8594;</span>
                                        <h6 class="grey--text">1 h and 55 m 22:30</h6>
                                    </div>
                                    <div class="col-xs-12 col-md-2 text-center mt-md-6">
                                        <h6>12:00</h6>
                                    </div>
                                </div>
                            </v-col>
                            <v-col class="text-right">
                                <h6>&nbsp;</h6>
                                <h1>STN</h1>
                                <h6>London, Stansted, United Kingdom</h6>
                            </v-col>
                            <v-col>
                                <h6 class="text-right mb-3">14.11.2019</h6>
                                <v-img class="float-left mr-1 company-img" src="https://media.glassdoor.com/sqll/6965/ryanair-squarelogo-1438176710344.png"></v-img>
                                <h4>Ryanair FR4193</h4>
                                <v-text-field
                                    label="PNR"
                                    dense
                                    single-line
                                    filled
                                ></v-text-field>
                            </v-col>
                        </v-row>
                        <v-divider></v-divider>
                        <v-row>
                            <v-col>
                                <h6>Return</h6>
                                <h1>STN</h1>
                                <h6>London, Stansted, United Kingdom</h6>
                            </v-col>
                            <v-col class="text-center">
                                <div class="row">
                                    <div class="col-xs-12 col-md-2 text-center mt-md-6">
                                        <h6>12:00</h6>
                                    </div>
                                    <div class="col-xs-12 col-md-8 text-center">
                                        <span class="red--text display-2">&#8594;</span>
                                        <h6 class="grey--text">1 h and 55 m 22:30</h6>
                                    </div>
                                    <div class="col-xs-12 col-md-2 text-center mt-md-6">
                                        <h6>12:00</h6>
                                    </div>
                                </div>
                            </v-col>
                            <v-col class="text-right">
                                <h6>&nbsp;</h6>
                                <h1>WAW</h1>
                                <h6>London, Stansted, United Kingdom</h6>
                            </v-col>
                            <v-col>
                                <h6 class="text-right mb-3">14.11.2019</h6>
                                <v-img class="float-left mr-1 company-img" src="https://media.glassdoor.com/sqll/6965/ryanair-squarelogo-1438176710344.png"></v-img>
                                <h4>Ryanair FR4193</h4>
                                <v-text-field
                                    label="PNR"
                                    dense
                                    single-line
                                    filled
                                ></v-text-field>
                            </v-col>
                        </v-row>

                        <v-row>
                            <v-col>
                                <span class="my-sep">stop 4 h and 50 m</span>
                            </v-col>
                        </v-row>

                        <v-row>
                            <v-col>
                                <h6>Return</h6>
                                <h1>STN</h1>
                                <h6>London, Stansted, United Kingdom</h6>
                            </v-col>
                            <v-col class="text-center">
                                <div class="row">
                                    <div class="col-xs-12 col-md-2 text-center mt-md-6">
                                        <h6>12:00</h6>
                                    </div>
                                    <div class="col-xs-12 col-md-8 text-center">
                                        <span class="red--text display-2">&#8594;</span>
                                        <h6 class="grey--text">1 h and 55 m 22:30</h6>
                                    </div>
                                    <div class="col-xs-12 col-md-2 text-center mt-md-6">
                                        <h6>12:00</h6>
                                    </div>
                                </div>
                            </v-col>
                            <v-col class="text-right">
                                <h6>&nbsp;</h6>
                                <h1>WAW</h1>
                                <h6>London, Stansted, United Kingdom</h6>
                            </v-col>
                            <v-col>
                                <h6 class="text-right mb-3">&nbsp;</h6>
                                <v-img class="float-left mr-1 company-img" src="https://media.glassdoor.com/sqll/6965/ryanair-squarelogo-1438176710344.png"></v-img>
                                <h4>Ryanair FR4193</h4>
                                <v-text-field
                                    label="PNR"
                                    dense
                                    single-line
                                    filled
                                ></v-text-field>
                            </v-col>
                        </v-row>

                        <v-row class="grey lighten-4">
                            <v-col cols="12" md="4">
                                <h5 class="mb-2">Passengers</h5>
                                <v-list>
                                    <v-list-item-group>
                                        <v-list-item
                                            v-for="(item, i) in ['John Doe', 'Tim Cook']"
                                            :key="i"
                                        >
                                            <v-list-item-content>
                                                <v-list-item-title>
                                                    {{item}}
                                                </v-list-item-title>
                                                <v-list-item-subtitle>
                                                    <v-img class="float-left mr-1" src="https://upload.wikimedia.org/wikipedia/en/0/03/Flag_of_Italy.svg" style="height: auto; width: 20px;"></v-img> Italy 24/12/1984
                                                </v-list-item-subtitle>
                                            </v-list-item-content>
                                        </v-list-item>
                                    </v-list-item-group>
                                </v-list>
                            </v-col>
                            <v-col cols="12" md="4"></v-col>
                            <v-col cols="12" md="4"></v-col>
                        </v-row>
                        <v-row>
                            <v-col class="text-right">
                                <v-btn bottom="true" small>Change Product</v-btn>
                            </v-col>
                        </v-row>
                    </v-expansion-panel-content>
                </v-expansion-panel>

                <v-expansion-panel>
                    <v-expansion-panel-header>
                        <h3>Flight Insurance</h3>
                    </v-expansion-panel-header>
                    <v-expansion-panel-content>
                        <v-row>
                            <v-col>
                                <div style="height: 56px; width: 180px; background-size: contain; margin-left: -1.5em; margin-top:-2.5em; background-image: url(https://www.obau.pl/wp-content/uploads/2015/08/allianz-logo.jpg);">                                </div>
                                <h5>Allianz Italy</h5>
                            </v-col>
                            <v-col></v-col>
                            <v-col></v-col>
                        </v-row>

                        <v-row class="grey lighten-4">
                            <v-col cols="12" md="4">
                                <h5 class="mb-2">Insured</h5>
                                <v-list>
                                    <v-list-item-group>
                                        <v-list-item
                                            v-for="(item, i) in ['John Doe', 'Tim Cook']"
                                            :key="i"
                                        >
                                            <v-list-item-content>
                                                <v-list-item-title>
                                                    {{item}}
                                                </v-list-item-title>
                                                <v-list-item-subtitle>
                                                    <v-img class="float-left mr-1" src="https://upload.wikimedia.org/wikipedia/en/0/03/Flag_of_Italy.svg" style="height: auto; width: 20px;"></v-img> Italy 24/12/1984
                                                </v-list-item-subtitle>
                                            </v-list-item-content>
                                        </v-list-item>
                                    </v-list-item-group>
                                </v-list>
                            </v-col>
                            <v-col cols="12" md="4"></v-col>
                            <v-col cols="12" md="4"></v-col>
                        </v-row>
                        <v-row>
                            <v-col class="text-right">
                                <v-btn bottom="true" small>Change Product</v-btn>
                            </v-col>
                        </v-row>
                    </v-expansion-panel-content>
                </v-expansion-panel>

                <v-expansion-panel>
                    <v-expansion-panel-header>
                        <h3>Rented Car</h3>
                    </v-expansion-panel-header>
                    <v-expansion-panel-content>
                        <v-row style="border-bottom: 1px solid #cccccc;">
                            <v-col cols="12" md="2">
                                <v-img src="https://www.stadionews.it/wp-content/uploads/2017/08/Sicily-By-Car-1024x412.jpg"></v-img>
                                <h4>Sicily by Car</h4>
                            </v-col>
                            <v-col cols="12" md="3">
                                <v-img src="https://www.fairwayford.co.uk/images/cmax.png"></v-img>
                            </v-col>
                            <v-col cols="12" md="4">
                                <h1>Ford C-Max</h1>
                                <h3>Sau Asemanator</h3>
                            </v-col>
                            <v-col cols="12" md="3">
                                <v-text-field
                                    label="Supplier Code"
                                    dense
                                    single-line
                                    filled
                                ></v-text-field>
                                <v-text-field
                                    label="Internal Code"
                                    dense
                                    single-line
                                    filled
                                ></v-text-field>
                            </v-col>
                        </v-row>

                        <v-row>
                            <v-col>
                                <h5>Pickup</h5>
                                <v-btn icon class="float-right">
                                    <v-icon color="grey lighten-1">mdi-information</v-icon>
                                </v-btn>
                                <h4>Napoli - Aeroporto Capodichino (NAP) Office in the airport</h4>

                                <v-btn class="ma-2" rounded outlined>
                                    <v-icon left>mdi-phone</v-icon> Phone
                                </v-btn>
                                <v-btn class="ma-2" rounded outlined>
                                    <v-icon left>mdi-email</v-icon> Email
                                </v-btn>
                            </v-col>
                            <v-col>
                                <h5>Dropoff</h5>
                                <v-btn icon class="float-right">
                                    <v-icon color="grey lighten-1">mdi-information</v-icon>
                                </v-btn>
                                <h4>Napoli - Aeroporto Capodichino (NAP) Office in the airport</h4>

                                <v-btn class="ma-2" rounded outlined>
                                    <v-icon left>mdi-phone</v-icon> Phone
                                </v-btn>
                                <v-btn class="ma-2" rounded outlined>
                                    <v-icon left>mdi-email</v-icon> Email
                                </v-btn>
                            </v-col>
                        </v-row>

                        <v-row class="grey lighten-4">
                            <v-col cols="12" md="4">
                                <h5 class="mb-2">Driver</h5>
                                <v-list>
                                    <v-list-item-group>
                                        <v-list-item
                                            v-for="(item, i) in ['Tim Cook']"
                                            :key="i"
                                        >
                                            <v-list-item-content>
                                                <v-list-item-title>
                                                    {{item}}
                                                </v-list-item-title>
                                                <v-list-item-subtitle>
                                                    <v-img class="float-left mr-1" src="https://upload.wikimedia.org/wikipedia/en/0/03/Flag_of_Italy.svg" style="height: auto; width: 20px;"></v-img> Italy 24/12/1984
                                                </v-list-item-subtitle>
                                            </v-list-item-content>
                                        </v-list-item>
                                    </v-list-item-group>
                                </v-list>
                            </v-col>
                            <v-col cols="12" md="4"></v-col>
                            <v-col cols="12" md="4"></v-col>
                        </v-row>
                        <v-row>
                            <v-col class="text-right">
                                <v-btn bottom="true" small>Change Product</v-btn>
                            </v-col>
                        </v-row>
                    </v-expansion-panel-content>
                </v-expansion-panel>
            </v-expansion-panels>

            <v-row>
                <v-col>
                    <v-btn class="mx-auto float-right" medium color="primary">Add Product</v-btn>
                </v-col>
            </v-row>
        </v-flex>
        <v-flex xs12 md4>
            <v-card class="mb-4">
                <v-card-text>
                    <v-row>
                        <v-col cols="6">
                            <h2>Payment Complete</h2>
                            <h4>Mobilpay</h4>
                        </v-col>
                        <v-col cols="6">
                            <v-img src="https://pngimage.net/wp-content/uploads/2018/06/mobilpay-png-4.png"></v-img>
                            <span class="float-right">Mastercard - 7981</span>
                            <v-img class="mt-1 mr-2" src="https://cdn.freebiesupply.com/images/large/2x/mastercard-logo-png-transparent.png" width="20px"></v-img>
                        </v-col>
                    </v-row>
                </v-card-text>

                <v-card-actions class="grey lighten-4">
                    <table style="display: grid">
                        <tr>
                            <th>
                                <h4>Total price:</h4>
                            </th>
                            <th>
                                <span class="float-right">130.30$</span>
                            </th>
                        </tr>
                        <tr>
                            <th>
                                <h4>Commission:</h4>
                            </th>
                            <th>
                                <span class="float-right">30$</span>
                            </th>
                        </tr>
                    </table>
                </v-card-actions>
            </v-card>

            <v-card class="mb-5">
                <v-card-text>
                    <v-timeline :reverse="reverse" dense>
                        <v-timeline-item
                            v-for="n in 2"
                            :key="n"
                        >
                            <span slot="opposite">Tus eu perfecto</span>
                            <v-card class="elevation-2">
                                <v-card-title class="headline">Lorem ipsum</v-card-title>
                                <v-card-text>
                                    Lorem ipsum dolor sit amet, no nam oblique veritus. Commune scaevola imperdiet nec ut, sed euismod convenire principes at. Est et nobis iisque percipit, an vim zril disputando voluptatibus, vix an salutandi sententiae.
                                </v-card-text>
                            </v-card>
                        </v-timeline-item>
                    </v-timeline>
                </v-card-text>
            </v-card>
        </v-flex>
    </v-layout>
</template>

<script>

export default {
    name: 'BookingEdit',

    props: { bookingId: String },

    data: () => ({
        /////////////////
        // Remote data //
        /////////////////
        bookingId : null,

        ////////////////
        // State data //
        ////////////////
        form: {},

        isLoading : false

    }),

    methods: {
        initialize () {
            this.form = {
                id         : '',
                name       : ''
            };
        },

        /**
         * Open current dialog
         * */
        new () {
            this.bookingId     = null;
        },

        /**
         * Open current dialog and fetch booking based on 'id' provided from props
         * */
        edit (id) {
            this.bookingId     = id;
        },

        submit () {
            this.successCallback();
        },

        /**
         * Emit success and close dialog
         */
        successCallback () {
            Snotify.success('Booking saved!', this.$t('success') + '!');
            this.$emit('booking-save');
            this.closeDialog();
        },

        closeDialog () {
            this.isLoading     = false;
            this.initialize();
        }
    },

    mounted () {

    }
};
</script>

<style>
    .company-img {
        width: 20px;
        height: 20px;
    }
    .my-sep {
        position: relative;
        display: block;
        font-weight: bold;
        background-color: white;
        width: -webkit-fit-content;
        width: -moz-fit-content;
        width: fit-content;
        margin: auto;
        padding: 0px 1em;
        font-size: 0.67em;
    }
    .my-sep:before {
        content: "";
        position: absolute;
        width: 125%;
        top: 50%;
        left: 100%;
        height: 1px;
        background-color: rgba(0, 0, 0, 0.12);
    }
    .my-sep:after {
        content: "";
        position: absolute;
        width: 125%;
        top: 50%;
        right: 100%;
        height: 1px;
        background-color: rgba(0, 0, 0, 0.12);
    }

    /*
    *
    * Media Query for xs and sm
    *
    */
    @media(max-width:767px){
        .my-sep:after, .my-sep:before{
            width: 75%;
        }
    }

</style>
